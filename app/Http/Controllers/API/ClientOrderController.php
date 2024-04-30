<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB; 
use Validator;
use App\Order;
use App\User;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\carTypes;
use App\Prices;
use App\savedPlaces;
use Jenssegers\Date\Date;
use App\Country;
use App\Coupons;
use App\usersCoupons;
use App\cancelReasons;
use App\orderPath;
use App\Notifications;
use DateTime;
use App\usersOrdersHistory;
use App\userDevices;
use Carbon;
use App\userPaymentWays;
use App\userBlocks;
use App\Conversation;
use App\userCars;

class ClientOrderController extends Controller{

    public function UserCreateOrder(Request $request){
        $validator        = Validator::make($request->all(),[
            'price_id'          => 'required',        
            'start_address'     => 'required',
            'start_lat'         => 'required',
            'start_long'        => 'required',
            'end_address'       => 'nullable',
            'end_lat'           => 'nullable',
            'end_long'          => 'nullable',
            'car_type_id'       => 'required',
            'expected_distance' => 'nullable',
            'expected_period'   => 'nullable',
            'expected_price'    => 'nullable', 
            'payment_type'      => 'required', 
            'type'              => 'required', //now, later
            'later_order_date'  => 'nullable|date|after:'.date('Y-m-d',strtotime('yesterday')),
            'later_order_time'  => 'nullable',
            'notes'             => 'nullable',
            // 'coupon'            => 'nullable'
        ]);

        if($validator->passes()){
            $user = JWTAuth::parseToken()->authenticate();
            $lang      = ($request->header('lang'))?? 'ar';
            if($order = Order::where('orders.user_id',$user->id)->where('orders.status','inprogress')->orwhere('orders.user_id',$user->id)->where('orders.status','open')->first()){
              $msg = trans('order.alreadyhaveOrder');
              return response()->json(failReturn($msg));
            }
          //***********start add coupon ***********//
            $coupon_code = convert2english($request->coupon);
            if($coupon_code){
              if($coupon = Coupons::where(['code'=> $coupon_code])->first()){
                //  if($coupon->num_to_use <= $coupon->num_used){
                //    $msg = trans('user.not_valid');
                //    return response()->json(failReturn($msg));
                //  }
                // if( strtotime($coupon->end_at) < strtotime('now') ){   
                //    $msg = trans('user.not_valid');
                //    return response()->json(failReturn($msg));
                // }
                if( ($coupon->num_to_use > $coupon->num_used) && (strtotime($coupon->end_at) >= strtotime('now') && (int)$coupon->total_cost < (int)$coupon->budget) ){
                  // if($usercoupon = usersCoupons::where(['user_id'=>$user->id,'used'=>'false'])->where('end_at','>=',date('Y-m-d'))->first()){
                  //    $msg = trans('user.have_unused_coupon');
                  //    return response()->json(failReturn($msg));
                  // }else{
                  if(usersCoupons::where(['user_id'=>$user->id,'coupon_id' => $coupon->id])->count() < $coupon->num_to_use_person){
                    $usercoupon = new usersCoupons();     
                    $usercoupon->coupon_id = $coupon->id;
                    $usercoupon->user_id   = $user->id;
                    $usercoupon->end_at    = $coupon->end_at;
                    $usercoupon->save();
                    $coupon->num_used      += 1;
                    $coupon->save();
                  }
                  //   $msg = trans('user.coupon_success');    
                  //   return response()->json(successReturnMsg($msg));   
                  // }  
                }     
              }
            } 
          //**********end add coupon ***********//  
          /****** start check if user have block ***/
            if($block = userBlocks::where('user_id','=',$user->id)->orderBy('created_at','DESC')->first()){
              $to_time    = strtotime( $block->to_time );
              $from_time  = strtotime( date('Y-m-d H:i:s') );
              $stillhours = round( ($to_time - $from_time) / 3600,2);
              if( $stillhours > 0){
                $to_time = Date::parse($to_time)->format('l j F h:i ');
                $to_time .= trans('order.'.date('a',strtotime($block->to_time)));
                $msg = trans('user.haveBlock',['date'=>$to_time]);
                return response()->json(failReturn($msg));               
              }else{
                $block->delete();
              }
            }
          /****** end check if user have block  ***/
          /**end check if client balance <  max client debt**/
            if(floatval($user->balance) < 0){
              if(setting('allow_debt_client') == 'true'){
                if( floatval($user->balance) <= ( -1 * floatval(setting('max_debt_client')) ) ){
                    $msg = trans('order.less_balance');
                    return response()->json(failReturn($msg));                 
                } 
              }else{
                    $msg = trans('order.less_balance');
                    return response()->json(failReturn($msg));                      
              } 
            }
          /**end check if client balance <  max client debt**/
           
            $order                        = new Order();
            $order->order_type            = 'trip';
            $order->price_id              = $request->price_id;
            $order->user_id               = $user->id;
            $order->car_type_id           = $request->car_type_id;          
            $order->type                  = $request->type;
            if($request->later_order_date){
              $order->later_order_date  = date('Y-m-d',strtotime($request->later_order_date));
              $order->later_order_time  = date('H:i:s',strtotime(str_replace('م', 'pm', str_replace('ص','am',$request->later_order_time) )));
            }
            $order->start_address       = $request->start_address;
            $order->start_lat           = doubleval( $request->start_lat );
            $order->start_long          = doubleval( $request->start_long );
            $order->end_address         = $request->end_address;
            $order->end_lat             = doubleval( $request->end_lat );
            $order->end_long            = doubleval( $request->end_long );
            $order->current_lat         = doubleval( $request->start_lat );
            $order->current_long        = doubleval( $request->start_long );            
            $order->expected_price      = $request->expected_price;
            if($country = Country::find($user->current_country_id) ){
                $order->country_id      = $country->id;
                $order->city_id         = $user->city_id;
                $order->currency_ar     = $country->currency_ar;
                $order->currency_en     = $country->currency_en;
            }else{
                $order->currency_ar     = setting('site_currency_ar');
                $order->currency_en     = setting('site_currency_en');              
            }  
            $order->payment_type        = $request->payment_type;
            $order->expected_distance   = $request->expected_distance;
            $order->expected_period     = $request->expected_period;
            $order->status              = 'open';
            $order->year                = date('Y');
            $order->month               = date('n');
            $order->hour                = date('H');
            $order->notes               = $request->notes;
            $order->save();
            $user->num_user_orders += 1;
            $user->save();

            // ** send notifications to captains Expect captains Blocked that User and captains have 0 balance in cash orders** //
            $start_lat  = doubleval($request->start_lat);
            $start_long = doubleval($request->start_long);
            $distance   = floatval((setting('distance') * 0.1 ) / 15 );
            $min_lat    = $start_lat  - $distance;
            $min_long   = $start_long - $distance;
            $max_lat    = $start_lat  + $distance;
            $max_long   = $start_long + $distance;             
            $max_debt_captain = setting('max_debt_captain');             
            if($order->payment_type == 'cash'){
              $nearCaptainDevices   = DB::table('users')->join('user_devices', 'users.id', '=', 'user_devices.user_id')
                                                                 ->where('users.captain','=','true')
                                                                 ->where('user_devices.user_id','!=',$user->id)
                                                                 ->where('users.id','!=',$user->id)
                                                                 ->where('users.have_order','=','false')
                                                                 ->where('users.active','=','active')
                                                                 ->where('users.lat','>=',$min_lat)->where('users.lat','<=',$max_lat)->where('users.long','>=',$min_long)->where('users.long','<=',$max_long)
                                                                 ->where('users.balance','>',(-1 * $max_debt_captain))
                                                                 ->where('users.available','=','true')
                                                                 // ->where('user_devices.orders_notify','=','true')
                                                                 ->where('users.captain_current_car_type_id','like','%'.$request->car_type_id.',%')
                                                                 ->select('users.id','users.lat','users.long','users.distance','user_devices.device_id','user_devices.device_type','user_devices.orders_notify')
                                                                 // ->orderBy('users.id','ASC')
                                                                 ->inRandomOrder()
                                                                 ->take(50)
                                                                 ->get();
            }else{
              $nearCaptainDevices   = DB::table('users')->join('user_devices', 'users.id', '=', 'user_devices.user_id')
                                                                 ->where('users.captain','=','true')
                                                                 ->where('user_devices.user_id','!=',$user->id)
                                                                 ->where('users.id','!=',$user->id)
                                                                 ->where('users.have_order','=','false')
                                                                 ->where('users.active','=','active')
                                                                 ->where('users.lat','>=',$min_lat)->where('users.lat','<=',$max_lat)->where('users.long','>=',$min_long)->where('users.long','<=',$max_long)
                                                                 ->where('users.available','=','true')
                                                                 // ->where('user_devices.orders_notify','=','true')
                                                                 ->where('users.captain_current_car_type_id','like','%'.$request->car_type_id.',%')
                                                                 ->select('users.id','users.lat','users.long','users.distance','user_devices.device_id','user_devices.device_type','user_devices.orders_notify')
                                                                 // ->orderBy('users.id','ASC')
                                                                 ->inRandomOrder()
                                                                 ->take(50)
                                                                 ->get();

            }

            $notifications = [];
            if($nearCaptainDevices){
              $devices      = $nearCaptainDevices;
              $notify_msg   = 'order.newOrder';
              $notify_title = 'order.newOrderTitle';
              $key          = 'newOrder';
              $extradata    = "order_id:".$order->id;
                $i = 0;
                foreach($devices as $device){
                  $captain_distance = $device->distance??setting('distance');
                  if((int)directDistance($device->lat,$device->long,$start_lat,$start_long) > (int)$captain_distance){  
                    $devices->forget($i);
                    $i++;
                  }
                    DB::table('notifications')->where(['user_id'=>$device->id,'key'=>'newOrder'])->delete();
                    $notifications[] = ['user_id'      => $device->id,
                                        'notifier_id'  => $user->id,
                                        'message'      => $notify_msg,
                                        'title'        => $notify_title,
                                        'data'         => $extradata,
                                        'order_status' => 'open',
                                        'key'          => $key,
                                        'created_at'   => date('Y-m-d H:i:s')
                                        ];
                }
                $uniqueNotifications = array_unique($notifications,SORT_REGULAR);
                Notifications::insert($uniqueNotifications);   
                #use FCM or One Signal Here :) 
                $notify_title_ar   = 'رحلة جديدة';
                $notify_title_en   = 'New Trip';
                // $message_ar        = 'هناك رحلة جديدة بالقرب منك.';
                // $message_en        = 'There is a new trip near you.';
                $message_ar = setting('newOrder_msg_ar');
                $message_en = setting('newOrder_msg_en');
                $data = ['title_ar' => $notify_title_ar,'title_en'=>$notify_title_en,'message_en'=>$message_en,'message_ar'=>$message_ar,'key'=>'newOrder','order_id'=>$order->id,'order_status'=>'open','type' => $order->type,'order_type' => $order->order_type];                           
                sendNotification($devices, $message_ar,$notify_title_ar,$data,'newOrder');
            }
            // ** END send notifications to captains Expect captains Blocked that User and captains have 0 balance in cash orders** //
           
            $msg = trans('order.sent_success');
            $data = ['msg' => $msg , 'order_id' => $order->id];
            return response()->json(successReturn($data));
        }else{
            $msg   = implode(' , ',$validator->errors()->all());
            return response()->json(failReturn($msg));
        }
    }

    // public function UserCreateFoodOrder(Request $request){
    //     $validator        = Validator::make($request->all(),[
    //         'place_id'          => 'required',   
    //         'place_ref'         => 'nullable',   
    //         'place_name'        => 'required',   
    //         'start_address'     => 'required',
    //         'start_lat'         => 'required',
    //         'start_long'        => 'required',
    //         'end_address'       => 'required',
    //         'end_lat'           => 'required',
    //         'end_long'          => 'required',
    //         'expected_distance' => 'nullable',
    //         'expected_period'   => 'nullable',
    //         'expected_price'    => 'nullable', 
    //         'payment_type'      => 'required', 
    //         'notes'             => 'required',
    //         'coupon'            => 'nullable'
    //     ]);

    //     if($validator->passes()){
    //         $user = JWTAuth::parseToken()->authenticate();
    //         $lang      = ($request->header('lang'))?? 'ar';
    //         if($order = Order::where('orders.user_id',$user->id)->where('orders.status','inprogress')->orwhere('orders.user_id',$user->id)->where('orders.status','open')->first()){
    //           $msg = trans('order.alreadyhaveOrder');
    //           return response()->json(failReturn($msg));
    //         }
    //       //***********start add coupon ***********//
    //         $coupon_code = convert2english($request->coupon);
    //         if($coupon_code){
    //           if($coupon = Coupons::where(['code'=> $coupon_code])->first()){
    //             //  if($coupon->num_to_use <= $coupon->num_used){
    //             //    $msg = trans('user.not_valid');
    //             //    return response()->json(failReturn($msg));
    //             //  }
    //             // if( strtotime($coupon->end_at) < strtotime('now') ){   
    //             //    $msg = trans('user.not_valid');
    //             //    return response()->json(failReturn($msg));
    //             // }
    //             if( ($coupon->num_to_use > $coupon->num_used) && (strtotime($coupon->end_at) >= strtotime('now')) ){
    //               // if($usercoupon = usersCoupons::where(['user_id'=>$user->id,'used'=>'false'])->where('end_at','>=',date('Y-m-d'))->first()){
    //               //    $msg = trans('user.have_unused_coupon');
    //               //    return response()->json(failReturn($msg));
    //               // }else{
    //                 $usercoupon = new usersCoupons();     
    //                 $usercoupon->coupon_id = $coupon->id;
    //                 $usercoupon->user_id   = $user->id;
    //                 $usercoupon->end_at    = $coupon->end_at;
    //                 $usercoupon->save();
    //                 $coupon->num_used      += 1;
    //                 $coupon->save();
    //               //   $msg = trans('user.coupon_success');    
    //               //   return response()->json(successReturnMsg($msg));   
    //               // }  
    //             }     
    //           }
    //         } 
    //       //**********end add coupon ***********//  
    //       /****** start check if user have block ***/
    //         if($block = userBlocks::where('user_id','=',$user->id)->orderBy('created_at','DESC')->first()){
    //           $to_time    = strtotime( $block->to_time );
    //           $from_time  = strtotime( date('Y-m-d H:i:s') );
    //           $stillhours = round( ($to_time - $from_time) / 3600,2);
    //           if( $stillhours > 0){
    //             $to_time = Date::parse($to_time)->format('l j F h:i ');
    //             $to_time .= trans('order.'.date('a',strtotime($block->to_time)));
    //             $msg = trans('user.haveBlock',['date'=>$to_time]);
    //             return response()->json(failReturn($msg));               
    //           }else{
    //             $block->delete();
    //           }
    //         }
    //       /****** end check if user have block  ***/
    //       /**end check if client balance <  max client debt**/
    //         if(floatval($user->balance) < 0){
    //           if(setting('allow_debt_client') == 'true'){
    //             if( floatval($user->balance) <= ( -1 * floatval(setting('max_debt_client')) ) ){
    //                 $msg = trans('order.less_balance');
    //                 return response()->json(failReturn($msg));                 
    //             } 
    //           }else{
    //                 $msg = trans('order.less_balance');
    //                 return response()->json(failReturn($msg));                      
    //           } 
    //         }
    //       /**end check if client balance <  max client debt**/
           
    //         $order                      = new Order();
    //         $order->user_id             = $user->id;
    //         $order->order_type          = 'food';
    //         $order->place_id            = $request->place_id;
    //         $order->place_ref           = $request->place_ref;
    //         $order->place_name          = $request->place_name;
    //         $order->start_address       = $request->start_address;
    //         $order->start_lat           = doubleval( $request->start_lat );
    //         $order->start_long          = doubleval( $request->start_long );
    //         $order->end_address         = $request->end_address;
    //         $order->end_lat             = doubleval( $request->end_lat );
    //         $order->end_long            = doubleval( $request->end_long );
    //         $order->current_lat         = doubleval( $request->start_lat );
    //         $order->current_long        = doubleval( $request->start_long );            
    //         $order->expected_price      = $request->expected_price;
    //         if($country = Country::find($user->current_country_id) ){
    //             $order->country_id      = $country->id;
    //             $order->city_id         = $user->city_id;
    //             $order->currency_ar     = $country->currency_ar;
    //             $order->currency_en     = $country->currency_en;
    //         }else{
    //             $order->currency_ar     = setting('site_currency_ar');
    //             $order->currency_en     = setting('site_currency_en');              
    //         }  
    //         $order->payment_type        = $request->payment_type;
    //         $order->expected_distance   = $request->expected_distance;
    //         $order->expected_period     = $request->expected_period;
    //         $order->status              = 'open';
    //         $order->year                = date('Y');
    //         $order->month               = date('n');
    //         $order->hour                = date('H');
    //         $order->notes               = $request->notes;
    //         $order->save();
    //         $user->num_user_orders += 1;
    //         $user->save();

    //         // ** send notifications to captains Expect captains Blocked that User and captains have 0 balance in cash orders** //
    //         $start_lat  = doubleval($request->start_lat);
    //         $start_long = doubleval($request->start_long);
    //         $distance   = floatval((setting('distance') * 0.1 ) / 15 );
    //         $min_lat    = $start_lat  - $distance;
    //         $min_long   = $start_long - $distance;
    //         $max_lat    = $start_lat  + $distance;
    //         $max_long   = $start_long + $distance;             
    //         $max_debt_captain = setting('max_debt_captain');             
    //         if($order->payment_type == 'cash'){
    //           $nearCaptainDevices   = DB::table('users')->join('user_devices', 'users.id', '=', 'user_devices.user_id')
    //                                                              ->where('users.captain','=','true')
    //                                                              ->where('user_devices.user_id','!=',$user->id)
    //                                                              ->where('users.id','!=',$user->id)
    //                                                              ->where('users.have_order','=','false')
    //                                                              ->where('users.lat','>=',$min_lat)->where('users.lat','<=',$max_lat)->where('users.long','>=',$min_long)->where('users.long','<=',$max_long)
    //                                                              ->where('users.balance','>',(-1 * $max_debt_captain))
    //                                                              ->where('users.available','=','true')
    //                                                              ->where('users.order_type','=','food')
    //                                                              ->orwhere('users.captain','=','true')
    //                                                              ->where('user_devices.user_id','!=',$user->id)
    //                                                              ->where('users.id','!=',$user->id)
    //                                                              ->where('users.have_order','=','false')
    //                                                              ->where('users.lat','>=',$min_lat)->where('users.lat','<=',$max_lat)->where('users.long','>=',$min_long)->where('users.long','<=',$max_long)
    //                                                              ->where('users.balance','>',(-1 * $max_debt_captain))
    //                                                              ->where('users.available','=','true')
    //                                                              ->where('users.order_type','=','both')
    //                                                              ->select('users.id','users.lat','users.long','users.distance','user_devices.device_id','user_devices.device_type','user_devices.orders_notify')
    //                                                              // ->orderBy('users.id','ASC')
    //                                                              ->inRandomOrder()
    //                                                              ->take(50)
    //                                                              ->get();
    //         }else{
    //           $nearCaptainDevices   = DB::table('users')->join('user_devices', 'users.id', '=', 'user_devices.user_id')
    //                                                              ->where('users.captain','=','true')
    //                                                              ->where('user_devices.user_id','!=',$user->id)
    //                                                              ->where('users.id','!=',$user->id)
    //                                                              ->where('users.have_order','=','false')
    //                                                              ->where('users.lat','>=',$min_lat)->where('users.lat','<=',$max_lat)->where('users.long','>=',$min_long)->where('users.long','<=',$max_long)
    //                                                              ->where('users.available','=','true')
    //                                                              ->where('users.order_type','=','food')
    //                                                              ->orwhere('users.captain','=','true')
    //                                                              ->where('user_devices.user_id','!=',$user->id)
    //                                                              ->where('users.id','!=',$user->id)
    //                                                              ->where('users.have_order','=','false')
    //                                                              ->where('users.lat','>=',$min_lat)->where('users.lat','<=',$max_lat)->where('users.long','>=',$min_long)->where('users.long','<=',$max_long)
    //                                                              ->where('users.available','=','true')
    //                                                              ->where('users.order_type','=','both')
    //                                                              ->select('users.id','users.lat','users.long','users.distance','user_devices.device_id','user_devices.device_type','user_devices.orders_notify')
    //                                                              // ->orderBy('users.id','ASC')
    //                                                              ->inRandomOrder()
    //                                                              ->take(50)
    //                                                              ->get();

    //         }

    //         $notifications = [];
    //         if($nearCaptainDevices){
    //           $devices      = $nearCaptainDevices;
    //           $notify_msg   = 'order.newFoodOrder';
    //           $notify_title = 'order.newFoodOrderTitle';
    //           $key          = 'newOrder';
    //           $extradata    = "order_id:".$order->id;
    //             foreach($devices as $device){
    //               $captain_distance = $device->distance??setting('distance');
    //               if((int)directDistance($device->lat,$device->long,$start_lat,$start_long) > (int)$captain_distance){  
    //                 $devices->forget($i);
    //                 $i++;
    //               }
    //                 DB::table('notifications')->where(['user_id'=>$device->id,'key'=>'newOrder'])->delete();
    //                 $notifications[] = ['user_id'      => $device->id,
    //                                     'notifier_id'  => $user->id,
    //                                     'message'      => $notify_msg,
    //                                     'title'        => $notify_title,
    //                                     'data'         => $extradata,
    //                                     'order_status' => 'open',
    //                                     'key'          => $key,
    //                                     'created_at'   => date('Y-m-d H:i:s')
    //                                     ];
    //             }
    //             $uniqueNotifications = array_unique($notifications,SORT_REGULAR);
    //             Notifications::insert($uniqueNotifications);   
    //             #use FCM or One Signal Here :) 
    //             $notify_title_ar   = 'طلب جديد';
    //             $notify_title_en   = 'New Order';
    //             $message_ar        = 'هناك طلب جديد بالقرب منك';
    //             $message_en        = 'There is a new order near you.';
    //             $data = ['title_ar' => $notify_title_ar,'title_en'=>$notify_title_en,'message_en'=>$message_en,'message_ar'=>$message_ar,'key'=>'newOrder','order_id'=>$order->id,'order_status'=>'open','type' => $order->type,'order_type' => $order->order_type];                           
    //             sendNotification($devices, $message_ar,$notify_title_ar,$data,'newOrder');
    //         }
    //         // ** END send notifications to captains Expect captains Blocked that User and captains have 0 balance in cash orders** //
           
    //         $msg = trans('order.order_sent_success');
    //         $data = ['msg' => $msg , 'order_id' => $order->id];
    //         return response()->json(successReturn($data));
    //     }else{
    //         $msg   = implode(' , ',$validator->errors()->all());
    //         return response()->json(failReturn($msg));
    //     }
    // }
    
    public function clientCurrentOrder(Request $request){
      $data = [];
      if($user = JWTAuth::parseToken()->authenticate()){
        $lang = ($request->header('lang'))??'ar';     
        if($order = Order::with('captain','car')->where('orders.user_id','=',$user->id)->where('orders.status','=','inprogress')->orwhere('orders.user_id','=',$user->id)->where('orders.status','=','open')->first()){
            $data = [   'id'               => $order->id ,
                        'status'           => $order->status,
                        'type'             => $order->type,
                        'has_captain'      => ($order->captain_id)? true : false
                    ];
        }else{
            $data = [   'id'               => 0,
                        'status'           => '',
                        'type'             => '',
                        'has_captain'      => false
                    ];        
        }
      }else{
        return response()->json(['value' => '0' , 'key' => 'fail' ,'msg' => 'Token is Invalid','code'=>419]);
      }
      return response()->json(successReturn($data));
    }
    
    public function reNotifyCaptains(Request $request){
        $validator        = Validator::make($request->all(),[
            'order_id'    => 'required',
        ]);
        if($validator->passes()){
          $data = [];
          $user = JWTAuth::parseToken()->authenticate();
          $lang = ($request->header('lang'))?? 'ar';
          if($order = Order::find($request->order_id) ){
              if($order->status == 'closed'){
                $msg = trans('order.order_closed');
                return response()->json(failReturn($msg)); 
              }elseif(($order->status == 'inprogress')){
                $msg = trans('order.order_received');
                return response()->json(failReturn($msg)); 
              }elseif($order->status == 'finished') {
                $msg = trans('order.order_finished');
                return response()->json(failReturn($msg)); 
              } 

              DB::table('notifications')->where(['key'=>'newOrder'])
                                        ->where('data','like','%'.'order_id:'.$order->id.'%')
                                        ->delete();
            // ** send notifications to captains Expect captains Blocked that User and captains have 0 balance in cash orders** //
            $start_lat  = doubleval($order->start_lat);
            $start_long = doubleval($order->start_long);
            $distance   = floatval((setting('distance') * 0.1 ) / 15 );
            $min_lat    = $start_lat  - $distance;
            $min_long   = $start_long - $distance;
            $max_lat    = $start_lat  + $distance;
            $max_long   = $start_long + $distance;             
            $max_debt_captain = setting('max_debt_captain');             
            if($order->payment_type == 'cash'){
              // if($order->order_type == 'food'){
              //   $nearCaptainDevices   = DB::table('users')->join('user_devices', 'users.id', '=', 'user_devices.user_id')
              //                                                      ->where('users.captain','=','true')
              //                                                      ->where('user_devices.user_id','!=',$user->id)
              //                                                      ->where('users.id','!=',$user->id)
              //                                                      ->where('users.have_order','=','false')
              //                                                      ->where('users.lat','>=',$min_lat)->where('users.lat','<=',$max_lat)->where('users.long','>=',$min_long)->where('users.long','<=',$max_long)
              //                                                      ->where('users.balance','>',(-1 * $max_debt_captain))
              //                                                      ->where('users.available','=','true')
              //                                                      ->where('users.order_type','=','food')
              //                                                      ->orwhere('users.captain','=','true')
              //                                                      ->where('user_devices.user_id','!=',$user->id)
              //                                                      ->where('users.id','!=',$user->id)
              //                                                      ->where('users.have_order','=','false')
              //                                                      ->where('users.lat','>=',$min_lat)->where('users.lat','<=',$max_lat)->where('users.long','>=',$min_long)->where('users.long','<=',$max_long)
              //                                                      ->where('users.balance','>',(-1 * $max_debt_captain))
              //                                                      ->where('users.available','=','true')
              //                                                      ->where('users.order_type','=','both')
              //                                                      ->select('users.id','user_devices.device_id','user_devices.device_type','user_devices.orders_notify')
              //                                                      // ->orderBy('users.id','ASC')
              //                                                      ->inRandomOrder()
              //                                                      ->take(50)
              //                                                      ->get();
              // }else{
                $nearCaptainDevices   = DB::table('users')->join('user_devices', 'users.id', '=', 'user_devices.user_id')
                                                                   ->where('users.captain','=','true')
                                                                   ->where('user_devices.user_id','!=',$user->id)
                                                                   ->where('users.id','!=',$user->id)
                                                                   ->where('users.have_order','=','false')
                                                                   ->where('users.lat','>=',$min_lat)->where('users.lat','<=',$max_lat)->where('users.long','>=',$min_long)->where('users.long','<=',$max_long)
                                                                   ->where('users.balance','>',(-1 * $max_debt_captain))
                                                                   ->where('users.available','=','true')
                                                                   // ->where('user_devices.orders_notify','=','true')
                                                                   ->where('users.captain_current_car_type_id','like','%'.$order->car_type_id.',%')
                                                                   ->select('users.id','user_devices.device_id','user_devices.device_type','user_devices.orders_notify')
                                                                   ->orderBy('users.id','ASC')
                                                                   ->get();
              // }
            }else{
              // if($order->order_type == 'food'){
              //   $nearCaptainDevices   = DB::table('users')->join('user_devices', 'users.id', '=', 'user_devices.user_id')
              //                                                      ->where('users.captain','=','true')
              //                                                      ->where('user_devices.user_id','!=',$user->id)
              //                                                      ->where('users.id','!=',$user->id)
              //                                                      ->where('users.have_order','=','false')
              //                                                      ->where('users.lat','>=',$min_lat)->where('users.lat','<=',$max_lat)->where('users.long','>=',$min_long)->where('users.long','<=',$max_long)
              //                                                      ->where('users.available','=','true')
              //                                                      ->where('users.order_type','=','food')
              //                                                      ->orwhere('users.captain','=','true')
              //                                                      ->where('user_devices.user_id','!=',$user->id)
              //                                                      ->where('users.id','!=',$user->id)
              //                                                      ->where('users.have_order','=','false')
              //                                                      ->where('users.lat','>=',$min_lat)->where('users.lat','<=',$max_lat)->where('users.long','>=',$min_long)->where('users.long','<=',$max_long)
              //                                                      ->where('users.available','=','true')
              //                                                      ->where('users.order_type','=','both')
              //                                                      ->select('users.id','user_devices.device_id','user_devices.device_type','user_devices.orders_notify')
              //                                                      // ->orderBy('users.id','ASC')
              //                                                      ->inRandomOrder()
              //                                                      ->take(50)
              //                                                      ->get();
              // }else{
                $nearCaptainDevices   = DB::table('users')->join('user_devices', 'users.id', '=', 'user_devices.user_id')
                                                                   ->where('users.captain','=','true')
                                                                   ->where('user_devices.user_id','!=',$user->id)
                                                                   ->where('users.id','!=',$user->id)
                                                                   ->where('users.have_order','=','false')
                                                                   ->where('users.lat','>=',$min_lat)->where('users.lat','<=',$max_lat)->where('users.long','>=',$min_long)->where('users.long','<=',$max_long)
                                                                   ->where('users.available','=','true')
                                                                   // ->where('user_devices.orders_notify','=','true')
                                                                   ->where('users.captain_current_car_type_id','like','%'.$order->car_type_id.',%')
                                                                   ->select('users.id','user_devices.device_id','user_devices.device_type','user_devices.orders_notify')
                                                                   ->orderBy('users.id','ASC')
                                                                   ->get();                
              // }

            }

            // if($order->order_type == 'food'){
            //   $notifications = [];
            //   if($nearCaptainDevices){
            //     $devices      = $nearCaptainDevices;
            //     $notify_msg   = 'order.newFoodOrder';
            //     $notify_title = 'order.newFoodOrderTitle';
            //     $key          = 'newOrder';
            //     $extradata    = "order_id:".$order->id;
            //       $i = 0;
            //       foreach($devices as $device){
            //         if(usersOrdersHistory::where(['captain_id' => $device->id,'status'=>'withdraw'])->first()){  
            //           $devices->forget($i);
            //           $i++;
            //         }
            //         DB::table('notifications')->where(['user_id'=>$device->id,'key'=>'newOrder'])->delete();
            //           $notifications[] = ['user_id'      => $device->id,
            //                               'notifier_id'  => $user->id,
            //                               'message'      => $notify_msg,
            //                               'title'        => $notify_title,
            //                               'data'         => $extradata,
            //                               'order_status' => 'open',
            //                               'key'          => $key,
            //                               'created_at'   => date('Y-m-d H:i:s')
            //                               ];
            //       }
            //       $uniqueNotifications = array_unique($notifications,SORT_REGULAR);
            //       Notifications::insert($uniqueNotifications);   
            //       #use FCM or One Signal Here :) 
            //       $notify_title_ar   = 'طلب جديد';
            //       $notify_title_en   = 'New Order';
            //       $message_ar        = 'هناك طلب جديد بالقرب منك';
            //       $message_en        = 'There is a new order near you.';
            //       $data = ['title_ar' => $notify_title_ar,'title_en'=>$notify_title_en,'message_en'=>$message_en,'message_ar'=>$message_ar,'key'=>'newOrder','order_id'=>$order->id,'order_status'=>'open','type' => $order->type,'order_type' => $order->order_type];                           
            //       sendNotification($devices, $message_ar,$notify_title_ar,$data,'newOrder');
            //   }
            // }else{
              $notifications = [];
              if($nearCaptainDevices){
                $devices      = $nearCaptainDevices;
                $notify_msg   = 'order.newOrder';
                $notify_title = 'order.newOrderTitle';
                $key          = 'newOrder';
                $extradata    = "order_id:".$order->id;
                  $i=0;
                  foreach($devices as $device){
                      if(usersOrdersHistory::where(['captain_id' => $device->id,'status'=>'withdraw'])->first()){  
                        $devices->forget($i);
                        $i++;
                      }
                      $notifications[] = ['user_id'      => $device->id,
                                          'notifier_id'  => $user->id,
                                          'message'      => $notify_msg,
                                          'title'        => $notify_title,
                                          'data'         => $extradata,
                                          'order_status' => 'open',
                                          'key'          => $key,
                                          'created_at'   => date('Y-m-d H:i:s')
                                          ];
                  }
                  $uniqueNotifications = array_unique($notifications,SORT_REGULAR);
                  Notifications::insert($uniqueNotifications);   
                  #use FCM or One Signal Here :) 
                  $notify_title_ar   = 'رحلة جديدة';
                  $notify_title_en   = 'New Trip';
                  // $message_ar        = 'هناك رحلة جديدة بالقرب منك.';
                  // $message_en        = 'There is a new trip near you.';
                  $message_ar = setting('newOrder_msg_ar');
                  $message_en = setting('newOrder_msg_en');
                  $data = ['title_ar' => $notify_title_ar,'title_en'=>$notify_title_en,'message_en'=>$message_en,'message_ar'=>$message_ar,'key'=>'newOrder','order_id'=>$order->id,'order_status'=>'open','type' => $order->type,'order_type' => $order->order_type];                           
                  sendNotification($devices, $message_ar,$notify_title_ar,$data,'newOrder');
              }
            // }
            // ** END send notifications to captains Expect captains Blocked that User and captains have 0 balance in cash orders** //
            $msg = trans('order.sent_success');
            $data = ['msg' => $msg , 'order_id' => $order->id];
            return response()->json(successReturn($data));
          }
          $msg = trans('order.order_notavailable');
          return response()->json(failReturn($msg));
        }else{
            $msg   = implode(' , ',$validator->errors()->all());
            return response()->json(failReturn($msg));
        }
    } 
    
    
    public function orderDetails(Request $request){
        $validator        = Validator::make($request->all(),[
            'order_id'    => 'required',
            // 'join_id'     => 'nullable',
            'lat'         => 'nullable',
            'long'        => 'nullable'
        ]);
        if($validator->passes()){
          $data = [];
          $user = JWTAuth::parseToken()->authenticate();
          $lang = ($request->header('lang'))?? 'ar';
          if($request->lat){
            $user->lat  = doubleval( $request->lat );
            $user->long = doubleval( $request->long );
            $user->save();
          }

          if($order = Order::find($request->order_id) ){
              if($order->status == 'closed'){
                $msg = trans('order.order_closed');
                return response()->json(failReturn($msg)); 
              }elseif(($order->status == 'inprogress') && ($order->captain_id != $user->id) && ($order->user_id != $user->id) && ($user->type == 'captain')){
                $msg = trans('order.order_received');
                return response()->json(failReturn($msg)); 
              }elseif($order->status == 'finished' && $order->confirm_payment == 'true') {
                $msg = trans('order.order_finished');
                return response()->json(failReturn($msg)); 
              } 
              if( ($order->type == 'later') && ($order->captain_in_road == 'false')){
                $now            = strtotime(date('Y-m-d H:i:s'));
                $laterdatetime  = strtotime($order->later_order_date.' '.$order->later_order_time);
                $stillminutes   = round( ($now - $laterdatetime) / 60,2);
                if( ($stillminutes >= intval(setting('order_close_time')) ) ) {
                  $order->status = 'closed';
                  $order->save();
                  $msg = trans('order.later_order_closed');
                  return response()->json(failReturn($msg));
                } 
              }      

              // DB::table('notifications')->where(['user_id'=>$user->id,'key'=>'newOrder'])
              //                                                   ->where('data','like','%'.'order_id:'.$order->id.'%')
              //                                                   ->delete();

              //check if that order with captain and not that captain
              // if( ($order->captain_id != $user->id && $order->captain_id != null )  && ($user->type == 'captain') ){
              //   // dd('captain_id = '.$order->captain_id.' user_id'.$user->id.' user->captain = '.$user->captain);
              //   $msg = trans('order.order_notavailable');
              //   return response()->json(failReturn($msg));
              // }
            /** increase that captain opened orders**/
            if( ($user->id != $order->user_id) && ($user->type == 'captain') ){
                $userOrderHistory = usersOrdersHistory::where(['captain_id' => $user->id,'order_id'=>$order->id ])->first();
                // if($userOrderHistory){
                //   if($userOrderHistory->status == 'opened' && $user->id != $order->captain_id ){
                //     $msg = trans('order.order_notavailable');
                //     return response()->json(failReturn($msg));
                //   }
                // }else{
                //    $usersOrdersHistory = new usersOrdersHistory();
                //    $usersOrdersHistory->captain_id  = $user->id;
                //    $usersOrdersHistory->order_id    = $order->id;
                //    $usersOrdersHistory->status      = 'opened';
                //    $usersOrdersHistory->date        = date('Y-m-d');
                //    $usersOrdersHistory->month       = date('Y-m');
                //    $usersOrdersHistory->save();
                //    $user->num_opened_orders        += 1;
                //    $user->save();
                // }
                if(!$userOrderHistory){
                  $usersOrdersHistory = new usersOrdersHistory();
                  $usersOrdersHistory->captain_id  = $user->id;
                  $usersOrdersHistory->order_id    = $order->id;
                  $usersOrdersHistory->status      = 'opened';
                  $usersOrdersHistory->date        = date('Y-m-d');
                  $usersOrdersHistory->month       = date('Y-m');
                  $usersOrdersHistory->save();
                  $user->num_opened_orders        += 1;
                  $user->save();
                }
            }
            /** end increase that captain opened orders**/
            $path = '';
            $current_distance = '0';
            $journey_time = '00:00:00';
            if($order->status != 'open'){
              if($orderpoints = orderPath::where(['order_id'=>$order->id,'captain_id'=>$order->captain_id])->get()){
                foreach ($orderpoints as $point) {
                   $path = $point->lat.','.$point->long.'|';
                }
                $path = rtrim($path,'|');
              }
              $start_journey_time = new DateTime( $order->start_journey_time );
              $now                = new DateTime( date('Y-m-d H:i:s') );
              $difference         = $start_journey_time->diff($now);
              $journey_time       = $difference->format("%H:%I:%S");
              $order->period      = $journey_time;
              $order->save();
              $current_lat        = doubleval( $request->lat );
              $current_long       = doubleval( $request->long );
              $current_distance   = directDistance($order->start_lat,$order->start_long,$current_lat,$current_long);//GetPathAndDirections($order->start_lat,$order->start_long,$current_lat,$current_long,$path,$lang);
            }
            $current_distance     = $current_distance.' '.trans('order.km');

            $later    = false;
            $later_time_come = false; 
            $datetime = '';
            if($order->type == 'later'){
               $later = true;
               $datetime1 = $order->later_order_date.' '.$order->later_order_time;
               $datetime  = Date::parse($datetime1)->format('j F h:i '); 
               $datetime .= trans('order.'.date('a',strtotime($datetime1)));
               if(strtotime(date('Y-m-d H:i:s')) >= strtotime($order->later_order_date.' '.$order->later_order_time)){
                $later_time_come = true; 
               } 
            }
            $reception_time = Date::parse($order->reception_time)->format('j F h:i '); 
            $reception_time .= trans('order.'.date('a',strtotime($order->reception_time))); 

            //check status of captain to client
            $captain_status = 'pending';
            if($order->status == 'finished' && $order->confirm_payment == 'true'){
               $captain_status = 'captain_finished';
            }elseif($order->status == 'finished' && $order->confirm_payment == 'false'){
               $captain_status = 'captain_finished_no_payment';
            }elseif($order->captain_in_road == 'true' && $order->captain_arrived == 'true' && $order->start_journey == 'true'){
               $captain_status = 'start_journey';
            }elseif($order->captain_in_road == 'true' && $order->captain_arrived == 'true' && $order->start_journey == 'false'){
               $captain_status = 'captain_arrived';
            }elseif($order->captain_in_road == 'true' && $order->captain_arrived == 'false' && $order->start_journey == 'false'){
               $captain_status = 'captain_in_road';
            }elseif($order->status == 'inprogress' && $order->captain_in_road == 'false' && $order->captain_arrived == 'false' && $order->start_journey == 'false'){
               $captain_status = 'captain_accept';
            }    

              $clients = [];
                if($client = $order->user){
                        $rate   = ($client->num_rating > 0 )? round(floatval($client->rating / $client->num_rating),1) : 0.0;
                        $clients[] = ['id'                    => $order->id,
                                      'order_id'              => $order->id,
                                      'client_id'             => $client->id,
                                      'name'                  => ($client->name)??'',
                                      'phone'                 => '0'.$client->phone,
                                      'avatar'                => ($client->avatar)? url('img/user/'.$client->avatar): url('img/user/default.png'),
                                      'client_start_lat'      => ($order->start_lat)? "$order->start_lat" : '',
                                      'client_start_long'     => ($order->start_long)? "$order->start_long": '',
                                      'client_start_address'  => ($order->start_address)??'',
                                      'client_end_lat'        => ($order->end_lat)? "$order->end_lat" : '',
                                      'client_end_long'       => ($order->end_long)? "$order->end_long" : '',
                                      'client_end_address'    => ($order->end_address)??'',
                                      'client_status'         => ($order->status)??'',
                                      'captain_status'        => $captain_status,
                                      'role'                  => 'owner',
                                      'rate'                  => $rate
                                     ]; 
                    $required_price = $order->required_price;
                    if($client->balance < 0){
                      $required_price .= ' + '.trans('user.previousDebts').' '.abs(round(floatval($client->balance),2));
                    }                                                                                
                }

              /********start create conversation between users **/
              $conversation_id = 0;
              if($order->status != 'open' && $order->order_src == 'internal'){
                if($conv = Conversation::where(['user1' => $order->user_id,'user2' => $order->captain_id,'order_id'=> $order->id])->first()){
                    $conversation_id = $conv->id;
                }else{
                    $conv = new Conversation();
                    $conv->user1    = $order->user_id;
                    $conv->user2    = $order->captain_id;
                    $conv->order_id = $order->id;
                    $conv->save();
                    $conversation_id = $conv->id;
                } 
              } 
              /********end create conversation between users **/            
              $current_country = currentCountry();
              $curr_timezone   = Country::where('iso2','=',$current_country['iso'])->first();
              
              $captain_distance = directDistance($order->start_lat, $order->start_long, $request->lat, $request->long);
              $expected_captain_period  = ceil($captain_distance * 2).' mins' ;  

              //start save app percentage 
              $app_percentage     = (floatval($order->price) * ( setting('site_percentage') / 100) );
              $captain_percentage = round(floatval($order->price) - floatval($app_percentage) ,2) ;
            $data = ['id'                    => $order->id,
                     'order_type'            => ($order->order_type)??'trip',
                     'order_src'             => ($order->order_src)??'internal',
                     'package_type'          => ($order->package_type)??'food',
                     'place_id'              => ($order->place_id)??'',
                     'user_name'             => ($order->user_name)??'',
                     'user_phone'            => ($order->user_phone)??'',
                     'place_ref'             => ($order->place_ref)??'',
                     'place_name'            => ($order->place_name)??'',
                     'icon'                  => url('img/icons/restaurant.png'),
                     'country_id'            => ($order->country_id)??0,
                     'conversation_id'       => ($conversation_id)??0,
                     'client_phone'          => ($order->user)? '0'.$order->user->phone:'',
                     'client_avatar'         => ($order->user)? url('img/user/'.$order->user->avatar): url('img/user/default.png'),
                     'cartype'               => ($order->cartype)? $order->cartype->{"name_$lang"} :'',
                     'car_image'             => ($order->car)? url('img/car/'.$order->car->image) : '',
                     'start_lat'             => "$order->start_lat",
                     'start_long'            => "$order->start_long",
                     'start_address'         => ($order->start_address)??'',
                     'end_lat'               => "$order->end_lat",
                     'end_long'              => "$order->end_long",
                     'end_address'           => ($order->end_address)??'',
                     'reception_time'        => $reception_time,
                     'later'                 => $later,
                     'later_time_come'       => $later_time_come,
                     'datetime'              => $datetime,
                     'later_order_date'      => ($order->later_order_date)??'',
                     'later_order_time'      => ($order->later_order_time)??'',
                     'payment_type'          => ($order->payment_type)??'',
                     'start_journey_time'    => ($order->start_journey_time)??'',
                     'start_journey_time_ms' => ($order->start_journey_time)? strtotime($order->start_journey_time):0,
                     'journey_time'          => $journey_time,
                     'period'                => ($order->period)??'',
                     'time_zone'             => date_default_timezone_get(),
                     'time_zone_utc'         => ($curr_timezone->timezone)??'UTC+3',
                     'current_distance'      => $current_distance,
                     'distance'              => $current_distance,
                     'expected_price'        => ($order->expected_price)??'',
                     'price'                 => ($order->price)??'',
                     'required_price'        => ($required_price)??'',
                     'vat'                   => (string)$order->vat,
                     'wasl'                  => (string)$order->wasl,
                     'currency'              => ($order->{"currency_$lang"})?? setting('site_currency_'.$lang),
                     'clients'               => $clients,
                     'expected_period'       => ($order->expected_period)??'',
                     'captain_inway_expected_period' => $expected_captain_period, 
                     'captain_status'        => $captain_status,
                     'status'                => ($order->status)??'',
                     'rush_hour_percentage'  => ($order->rush_hour_percentage == '0')?'1.x':"$order->rush_hour_percentage",
                     'app_percentage'        => "$app_percentage".' '.$order->{"currency_$lang"},
                     'captain_percentage'    => "$captain_percentage".' '.$order->{"currency_$lang"} ,                   
                     'notes'                 => ($order->notes)??''
                    ];
            return response()->json(successReturn($data));
          }
          $msg = trans('order.order_notavailable');
          return response()->json(failReturn($msg));
        }else{
            $msg   = implode(' , ',$validator->errors()->all());
            return response()->json(failReturn($msg));
        }
    }

    public function clientFinishedOrderDetails(Request $request){
        $validator        = Validator::make($request->all(),[
            'order_id'    => 'required|integer'
        ]);
        if($validator->passes()){
          $data = [];
          $user = JWTAuth::parseToken()->authenticate();
          $lang = ($request->header('lang'))?? 'ar';
          if($order = Order::where(['id' => $request->order_id,'status'=>'finished'])->first()){         
              if($car = $order->car){
                $car_brand  = $car->brand.' ('.$car->model.')';
                $car_number = $car->car_number;
              } 
              $rate   = ($order->captain->num_rating > 0 )? round(floatval($order->captain->rating / $order->captain->num_rating),1) : 0.0;
              if($captain = User::find($order->captain_id)){
                $avatar = ($captain->avatar)? url('img/user/'.$captain->avatar) : url('img/user/default.png');
                $name   = $captain->name;
              } 

              $wait_price = 0 ; $open_counter = 0;  $moving_price  = 0;
              $start_lat  = ''; $start_long   = ''; $start_address = '';
              $end_lat    = ''; $end_long     = ''; $end_address   =' ';
              $payment_type = ''; $distance = '';   $period = ''; $initial_wait = 0;
              $during_order_wait = 0; $paid_balance = 0; $paid_cash = 0; $paid_online = 0; 
              $coupon_discount = 0; $added_balance = 0 ; $rush_hour_percentage = 0; $price = 0; $vat = 0; $wasl = 0;

              $start_lat            = $order->start_lat;
              $start_long           = $order->start_long;
              $start_address        = $order->start_address;
              $end_lat              = $order->end_lat;
              $end_long             = $order->end_long;
              $end_address          = $order->end_address;
              $payment_type         = $order->payment_type;
              $distance             = $order->distance;
              $period               = $order->period;
              $initial_wait         = $order->initial_wait;
              $during_order_wait    = $order->during_order_wait;                  
              $paid_balance         = $order->paid_balance;
              $paid_cash            = $order->paid_cash;
              $paid_online          = $order->paid_online;
              $coupon_discount      = $order->coupon_discount;
              $added_balance        = $order->added_balance;
              $rush_hour_percentage = $order->rush_hour_percentage;
              $price                = $order->price; 
              $vat                  = $order->vat;
              $wasl                 = $order->wasl;

              if($priceplan = Prices::find($order->price_id)){
                  $wait_price   = intval($order->initial_wait + $order->during_order_wait) * $priceplan->waiting_minute;
                  $open_counter = $priceplan->counter; 
                  $moving_price = ($priceplan->km_price * intval($order->distance) );
                  $moving_price = ($moving_price < $priceplan->min_price)? floatval($priceplan->min_price - $open_counter): $moving_price;
                  $moving_price = ($moving_price < 0)? 0 : $moving_price;
              }
              $app_percentage = floatval($order->price) * (floatval( (setting('site_percentage')) ) / 100) ;

                  $total_required_price = $order->required_price;
                  if($client = $order->user){
                    if($client->balance < 0){
                      //if pay with online
                      $total_required_price = floatval($total_required_price) + abs(round(floatval($client->balance),2));
                    }                                           
                  }

              $data = ['id'            => $order->id,
                       'order_type'            => ($order->order_type)??'trip',
                       'order_src'             => ($order->order_src)??'internal',
                       'package_type'          => ($order->package_type)??'food',
                       'place_id'              => ($order->place_id)??'',
                       'user_name'             => ($order->user_name)??'',
                       'user_phone'            => ($order->user_phone)??'',
                       'place_id'      => ($order->place_id)??'',
                       'place_ref'     => ($order->place_ref)??'',
                       'place_name'    => ($order->place_name)??'',
                       'icon'          => url('img/icons/restaurant.png'), 
                       'date'          => date('Y-m-d',strtotime($order->created_at)),
                       'captain_id'    => ($order->captain_id)??0,
                       'rate'          => doubleval($rate),
                       'avatar'        => $avatar,
                       'name'          => $name,
                       'start_lat'     => "$start_lat",
                       'start_long'    => "$start_long",
                       'start_address' => ($start_address)??'',
                       'end_lat'       => "$end_lat",
                       'end_long'      => "$end_long",
                       'end_address'   => ($end_address)??'',
                       'payment_type'  => ($payment_type)??'',
                       'car'           => ($car_brand)??'',
                       'car_number'    => ($car_number)??'',
                       'distance'      => round(floatval($distance) , 2).' '.trans('order.km'),
                       'period'        => ($period)??'',
                       'initial_wait'         => intval($initial_wait),
                       'during_order_wait'    => intval($during_order_wait),
                       'total_required_price'=> number_format((float)$total_required_price, 2, '.', ''),
                       'wait_price'           => round( floatval($wait_price) , 2),
                       'open_counter'         => round( floatval($open_counter) , 2),
                       'moving_price'         => round( floatval($moving_price) , 2),
                       'paid_balance'         => round( floatval($paid_balance) ,2),
                       'paid_cash'            => round( floatval($paid_cash) ,2),
                       'paid_visa'            => round( floatval($paid_online) ,2),
                       'coupon_discount'      => round( floatval($coupon_discount) ,2),
                       'added_balance'        => round( floatval($added_balance) ,2),
                       'rush_hour_percentage' => ($rush_hour_percentage)??'0',
                       'price'                => round( floatval($price) , 2), // order price
                       'vat'                  => number_format((float)$vat, 2, '.', ''),//round( floatval($vat) , 2),
                       'wasl'                 => round( floatval($wasl) , 2),
                       'app_percentage'       => round( floatval($app_percentage) ,2), //app profit
                       'currency'             => ($order->{"currency_$lang"})??''
                      ];
              return response()->json(successReturn($data));
          }
          $msg = trans('order.order_notavailable');
          return response()->json(failReturn($msg));
        }else{
            $msg   = implode(' , ',$validator->errors()->all());
            return response()->json(failReturn($msg));
        }
    }
    
    public function ClientLaterOrders(Request $request){
      $data = [];
      $user = JWTAuth::parseToken()->authenticate();
      $lang = $request->header('lang');
      // get orders of that user that he did or he joined
      $orders = Order::with('captain','car')->where('orders.user_id',$user->id)->where('orders.status','inprogress')
                        ->orwhere('orders.user_id',$user->id)->where('orders.status','open')
                        ->orderBy('orders.later_order_date','ASC')
                        ->orderBy('orders.later_order_time','ASC')
                        ->orderBy('orders.created_at','DESC')
                        ->get();       
      $datetime = '';
      if(count($orders) > 0){
        foreach ($orders as $order) {
          $can_update = false;
          $can_update = ( ($order->user_id == $user->id && strtotime('now') < strtotime($order->later_order_date.' '.$order->later_order_time) && $order->type == 'later') || ($order->user_id == $user->id && $order->type == 'now' && $order->captain_id == null) )? true : false;
          if($order->type == 'later'){
            if(strtotime($order->later_order_date) == strtotime(date('Y-m-d')) ){
                 $datetime1 = $order->later_order_date.' '.$order->later_order_time;
                 $datetime  = trans('order.today');
                 $datetime .= Date::parse($datetime1)->format('h:i '); 
                 $datetime .= trans('order.'.date('a',strtotime($datetime1)));             
            }elseif(strtotime($order->later_order_date) == strtotime('tomorrow')){
                 $datetime1 = $order->later_order_date.' '.$order->later_order_time;
                 $datetime  = trans('order.tomorrow');
                 $datetime .= Date::parse($datetime1)->format('h:i '); 
                 $datetime .= trans('order.'.date('a',strtotime($datetime1)));  
            }else{
                 $datetime1 = $order->later_order_date.' '.$order->later_order_time;
                 $datetime  = Date::parse($datetime1)->format('j F h:i '); 
                 $datetime .= trans('order.'.date('a',strtotime($datetime1)));  
            }
          }
          if(($order->type == 'now') || ( ($order->type == 'later') && (time() >= strtotime($order->later_order_date.' '.$order->later_order_time) ) ) ){
            $datetime = trans('order.now');
          }
          $avatar = ''; $name=''; $rate = 0.0;
          if($captain = $order->captain){
            $avatar = ($captain->avatar)? url('img/user/'.$captain->avatar) : url('img/user/default.png');
            $name   = $captain->name;
            $rate   = ($captain->num_rating > 0 )? round(floatval($captain->rating / $captain->num_rating),1) : 0.0;
          }
          $car_brand = '' ; $car_number= '';
          if($car = $order->car){
            $car_brand  = $car->brand.' ('.$car->model.')';
            $car_number = $car->car_number;
          } 

          $data[] = [ 'id'               => $order->id ,
                      'order_type'       => ($order->order_type)??'trip',
                      'place_id'         => ($order->place_id)??'',
                      'place_ref'        => ($order->place_ref)??'',
                      'place_name'       => ($order->place_name)??'',
                      'icon'             => url('img/icons/restaurant.png'),
                      'avatar'           => $avatar,
                      'name'             => $name,
                      'car_brand'        => $car_brand,
                      'car_number'       => $car_number,
                      'rate'             => $rate,
                      'can_update'       => $can_update,
                      'later'            => true,
                      'cartype'          => ($order->cartype)? $order->cartype->{"name_$lang"}:'',
                      'payment_type'     => ($order->payment_type)??'',
                      'price'            => ($order->price)??'',
                      'expected_price'   => ($order->expected_price)??'',
                      'currency'         => ($order->{"currency_$lang"})?? setting('site_currency_'.$lang),
                      'month'            => $order->month,                            
                      'later_order_date' => ($order->later_order_date)?? '', 
                      'later_order_time' => ($order->later_order_time)?? '', 
                      'start_lat'        => "$order->start_lat",
                      'start_long'       => "$order->start_long",
                      'start_address'    => ($order->start_address)??'',
                      'end_lat'          => "$order->end_lat",
                      'end_long'         => "$order->end_long",
                      'end_address'      => ($order->end_address)??'',
                      'date'             => $datetime,
                      'time'             => Date::parse($order->created_at)->format('h:i').' '.trans('order.'.date('a'))
                    ];

        }   
      }
      return response()->json(successReturn($data));
    }

    public function ClientLaterOrder(Request $request){
        $validator        = Validator::make($request->all(),[
            'order_id'    => 'required'
        ]);
        if($validator->passes()){
          $data = [];
          $user = JWTAuth::parseToken()->authenticate();
          $lang = $request->header('lang');   
          $datetime = '';

          if($order = Order::with('captain','car')->find($request->order_id)){
              if($order->status == 'closed'){
                $msg = trans('order.order_closed');
                return response()->json(failReturn($msg)); 
              }elseif($order->status == 'finished') {
                $msg = trans('order.order_finished');
                return response()->json(failReturn($msg)); 
              }             
              $can_update = false;
              $can_update = ( ($order->user_id == $user->id && strtotime('now') < strtotime($order->later_order_date.' '.$order->later_order_time) && $order->type == 'later') || ($order->user_id == $user->id && $order->type == 'now' && $order->captain_id == null) )? true : false;
              if($order->type == 'later'){
                if(strtotime($order->later_order_date) == strtotime(date('Y-m-d')) ){
                     $datetime1 = $order->later_order_date.' '.$order->later_order_time;
                     $datetime  = trans('order.today');
                     $datetime .= Date::parse($datetime1)->format('h:i '); 
                     $datetime .= trans('order.'.date('a',strtotime($datetime1)));             
                }elseif(strtotime($order->later_order_date) == strtotime('tomorrow')){
                     $datetime1 = $order->later_order_date.' '.$order->later_order_time;
                     $datetime  = trans('order.tomorrow');
                     $datetime .= Date::parse($datetime1)->format('h:i '); 
                     $datetime .= trans('order.'.date('a',strtotime($datetime1)));  
                }else{
                     $datetime1 = $order->later_order_date.' '.$order->later_order_time;
                     $datetime  = Date::parse($datetime1)->format('j F h:i '); 
                     $datetime .= trans('order.'.date('a',strtotime($datetime1)));  
                }
              }
              if(($order->type == 'now') || ( ($order->type == 'later') && (time() >= strtotime($order->later_order_date.' '.$order->later_order_time) ) ) ){
                $datetime = trans('order.now');
              }
              $avatar = ''; $name=''; $rate = 0.0 ;
              if($captain = $order->captain){
                $avatar = ($captain->avatar)? url('img/user/'.$captain->avatar) : url('img/user/default.png');
                $name   = $captain->name;
                $rate   = ($captain->num_rating > 0 )? round(floatval($captain->rating / $captain->num_rating),1) : 0.0;
              }
              $car_brand = '' ; $car_number= '';
              if($car = $order->car){
                $car_brand  = $car->brand.' ('.$car->model.')';
                $car_number = $car->car_number;
              } 
              $data   = [ 'id'               => $order->id ,
                          'order_type'       => ($order->order_type)??'trip',
                          'place_id'         => ($order->place_id)??'',
                          'place_ref'        => ($order->place_ref)??'',
                          'place_name'       => ($order->place_name)??'',
                          'icon'             => url('img/icons/restaurant.png'),
                          'avatar'           => $avatar,
                          'name'             => $name,
                          'car_brand'        => $car_brand,
                          'car_number'       => $car_number,
                          'rate'             => $rate,
                          'can_update'       => $can_update,
                          'later'            => true,
                          'cartype'          => ($order->cartype)? $order->cartype->{"name_$lang"}:'',
                          'payment_type'     => ($order->payment_type)??'',
                          'price'            => ($order->price)??'',
                          'expected_price'   => ($order->expected_price)??'',
                          'currency'         => ($order->{"currency_$lang"})?? setting('site_currency_'.$lang),
                          'month'            => $order->month,                            
                          'later_order_date' => ($order->later_order_date)?? '', 
                          'later_order_time' => ($order->later_order_time)?? '', 
                          'start_lat'        => "$order->start_lat",
                          'start_long'       => "$order->start_long",
                          'start_address'    => ($order->start_address)??'',
                          'end_lat'          => "$order->end_lat",
                          'end_long'         => "$order->end_long",
                          'end_address'      => ($order->end_address)??'',
                          'date'             => $datetime,
                          'time'             => Date::parse($order->created_at)->format('h:i').' '.trans('order.'.date('a'))
                        ];
          return response()->json(successReturn($data));
          }
          $msg = trans('order.order_notavailable');
          return response()->json(failReturn($msg));
        }else{
            $msg   = implode(' , ',$validator->errors()->all());
            return response()->json(failReturn($msg));
        }
    }

    public function cancelReasons(Request $request){
      $lang    = $request->header('lang');
      $data = [];
      $user = JWTAuth::parseToken()->authenticate();
      if($user->captain == 'true'){
         $reasons = cancelReasons::where('type','withdraw')->orderBy('id','ASC')->get();
      }else{
         $reasons = cancelReasons::where('type','cancel')->orderBy('id','ASC')->get();
      }
      foreach($reasons as $reason){
        $data[] = ['id'     => $reason->id,
                   'type'   => ($reason->type)??'',
                   'reason' => $reason->{"reason_$lang"}
                  ];
      }
      return response()->json(successReturn($data)); 
    }
    
    // public function UpdateClientLaterOrder(Request $request){
    //     $validator        = Validator::make($request->all(),[
    //         'order_id'          => 'required|integer',
    //         'later_order_date'  => 'nullable|date|after:'.date('Y-m-d',strtotime('yesterday')),
    //         'later_order_time'  => 'nullable',
    //     ]);
    //     if($validator->passes()){
    //       $data = [];
    //       $user = JWTAuth::parseToken()->authenticate();
    //       $lang = ($request->header('lang'))?? 'ar';
    //       if($order = Order::where('id','=',$request->order_id)->where('user_id','=',$user->id)->first() ){
    //           if($order->status == 'closed'){
    //             $msg = trans('order.order_closed');
    //             return response()->json(failReturn($msg)); 
    //           }elseif(($order->status == 'inprogress')){
    //             $msg = trans('order.order_received');
    //             return response()->json(failReturn($msg)); 
    //           }elseif($order->status == 'finished') {
    //             $msg = trans('order.order_finished');
    //             return response()->json(failReturn($msg)); 
    //           } 

    //          if(strtotime('now') > strtotime($request->later_order_date.' '.$request->later_order_time)){
    //             $time = Date::parse(date('Y-m-d H:i:s'))->format('j F h:i'); 
    //             $time .= trans('order.'.date('a'));  
    //             $msg = trans('order.oldDate',['time' => $time]);
    //             return response()->json(failReturn($msg));
    //          }
    //           $order->later_order_date    = date('Y-m-d',strtotime($request->later_order_date));
    //           $order->later_order_time    = date('H:i',strtotime($request->later_order_time));
    //           $order->type                = 'later';
    //           $order->save();

    //          //**start send notification to captain and order users with time update**//
    //           $devices = userDevices::where(['user_id' => $order->captain_id])->get();
    //           $notify_title_ar = 'تغير وقت الرحلة';
    //           $notify_title_en = 'Change Trip Time';
    //           $message_ar      = 'قام '.$user->name.' بتغير وقت الرحلة الي '.$order->later_order_date.' '.$order->later_order_time;
    //           $message_en      = $user->name.' changed trip time to '.$order->later_order_date.' '.$order->later_order_time;
    //           $data = ['title_ar' => $notify_title_ar,'title_en' => $notify_title_en,'message_en' => $message_en,'message_ar' => $message_ar,'key' => 'updateOrderTime','order_id' => $order->id,'order_status' => $order->status,'type' => $order->type,'order_type' => $order->order_type];
    //           sendNotification($devices, $message_ar,$notify_title_ar,$data);
    //           $datetime = $order->later_order_date.' '.$order->later_order_time;
    //           notify($order->captain_id,$user->id,'order.updateOrderTimeTitle','order.updateOrderTime',"order_id:".$order->id.":datetime:".$datetime,$order->status,'updateOrderTime');               
    //          //**end send notification to captain and order users with time update**//
    //         $msg = trans('order.updateOrderTimeSuccess');
    //         return response()->json(successReturnMsg($msg)); 
    //       }
    //       $msg = trans('order.order_notavailable');
    //       return response()->json(failReturn($msg));
    //     }else{
    //         $msg   = implode(' , ',$validator->errors()->all());
    //         return response()->json(failReturn($msg));
    //     }
    // }

    public function ClientViewAcceptedOrderCaptain(Request $request){
        $validator        = Validator::make($request->all(),[
            'order_id'    => 'required|integer',
        ]);
        if($validator->passes()){
          $data = [];
          $lang = ($request->header('lang'))?? 'ar';
          $user = JWTAuth::parseToken()->authenticate();
          if($order = Order::find($request->order_id)){
              if($order->status == 'closed'){
                $msg = trans('order.order_closed');
                return response()->json(failReturn($msg)); 
              }elseif($order->status == 'finished') {
                $msg = trans('order.order_finished');
                return response()->json(failReturn($msg)); 
              }             
            $phone = '';$name='';$avatar='';$rate= '0';$car_brand='';$car_number='';$car_image='';
            $captain_lat = 0.0; $captain_long = 0.0;
            if($captain = $order->captain){
              $phone  = '0'.$captain->phone;
              $name   = $captain->name;
              $avatar = ($captain->avatar)? url('img/user/'.$captain->avatar):url('img/user/default.png');
              $rate   = ($captain->num_rating > 0 )? round(floatval($captain->rating / $captain->num_rating),1) : '0';
              $captain_lat  = $captain->lat;
              $captain_long = $captain->long;
            }
            if($car = $order->car){
              $car_brand  = $car->brand.' ('.$car->model.')';
              $car_number = ($car->car_number)?? '';
              $car_image  = ($car->image)? url('img/car/'.$car->image):url('img/car/'.$order->cartype->image);
            }elseif($car = userCars::where('user_id','=',$order->captain_id)->first()){
              $car_brand  = $car->brand.' ('.$car->model.')';
              $car_number = ($car->car_number)?? '';
              $car_image  = ($car->image)? url('img/car/'.$car->image):url('img/car/'.$order->cartype->image);
            }

            $distance = directDistance($order->start_lat, $order->start_long, $captain_lat, $captain_long);
            $expected_period  = ceil($distance * 2).' mins' ;                      
            
            $captain_status    = 'pending';
            if($order->status == 'finished' && $order->confirm_payment == 'true'){
               $captain_status = 'captain_finished';
            }elseif($order->status == 'finished' && $order->confirm_payment == 'false'){
               $captain_status = 'captain_finished_no_payment';
            }elseif($order->captain_in_road == 'true' && $order->captain_arrived == 'true' && $order->start_journey == 'true'){
               $captain_status = 'start_journey';
            }elseif($order->captain_in_road == 'true' && $order->captain_arrived == 'true' && $order->start_journey == 'false'){
               $captain_status = 'captain_arrived';
            }elseif($order->captain_in_road == 'true' && $order->captain_arrived == 'false' && $order->start_journey == 'false'){
               $captain_status = 'captain_in_road';
            }elseif($order->status == 'inprogress' && $order->captain_in_road == 'false' && $order->captain_arrived == 'false' && $order->start_journey == 'false'){
               $captain_status = 'captain_accept';
            } 

              /********start create conversation between users **/
              $conversation_id = 0;
              if($order->status != 'open' && $order->order_src == 'internal'){
                if($conv = Conversation::where(['user1' => $order->user_id,'user2' => $order->captain_id,'order_id'=> $order->id])->first()){
                    $conversation_id = $conv->id;
                }else{
                    $conv = new Conversation();
                    $conv->user1    = $order->user_id;
                    $conv->user2    = $order->captain_id;
                    $conv->order_id = $order->id;
                    $conv->save();
                    $conversation_id = $conv->id;
                } 
              } 
              /********end create conversation between users **/ 

            $data = ['id'             => $order->id,
                     'order_type'     => ($order->order_type)??'trip',
                     'place_id'       => ($order->place_id)??'',
                     'place_ref'      => ($order->place_ref)??'',
                     'place_name'     => ($order->place_name)??'',
                     'icon'           => url('img/icons/restaurant.png'),
                     'captain_id'     => ($order->captain_id)??0,
                     'conversation_id'=> ($conversation_id)??0,
                     'captain_lat'    => doubleval( $captain_lat ),
                     'captain_long'   => doubleval( $captain_long ),
                     'car_type_id'    => ($order->car_type_id)? $order->car_type_id : 0,//(($order->car)? $order->car->car_type_id : 0),
                     'start_lat'      => "$order->start_lat",
                     'start_long'     => "$order->start_long",
                     'start_address'  => ($order->start_address)??'',
                     'end_lat'        => "$order->end_lat",
                     'end_long'       => "$order->end_long",
                     'end_address'    => ($order->end_address)??'',
                     'phone'          => $phone,
                     'name'           => $name,
                     'avatar'         => $avatar,
                     'rate'           => "$rate",
                     'car_brand'      => $car_brand, 
                     'car_number'     => $car_number,
                     'car_image'      => $car_image,
                     'expected_period'=> ($expected_period)??'',
                     'captain_status' => $captain_status,
                     'payment_type'   => ($order->payment_type)??'cash',
                     'notes'          => ($order->notes)??''
                    ];
            return response()->json(successReturn($data));
          }
          $msg = trans('order.order_notavailable');
          return response()->json(failReturn($msg));
        }else{
            $msg   = implode(' , ',$validator->errors()->all());
            return response()->json(failReturn($msg));
        }
    }

    public function ClientShowTotalOrderPrice(Request $request){
        $validator        = Validator::make($request->all(),[
            'order_id'     => 'required|integer'
        ]);
        if($validator->passes()){
          $lang = ($request->header('lang'))?? 'ar';
          $user = JWTAuth::parseToken()->authenticate(); 
          if($order = Order::where(['id'=>$request->order_id,'user_id'=>$user->id])->first()){
            $avatar = url('img/user/default.png'); $name = '';
            if($captain = $order->captain){
              $avatar = url('img/user/'.$captain->avatar);
              $name   = $captain->name; 
            }
            $data = ['price'        => $order->required_price.' '.$order->{"currency_$lang"},
                     'payment_type' => $order->payment_type,
                     'avatar'       => $avatar,
                     'captain_id'   => ($order->captain_id)??0,
                     'name'         => $name
                    ];
            return response()->json(successReturn($data));
          }
          $msg = trans('order.order_notavailable');
          return response()->json(failReturn($msg));
        }else{
          $msg   = implode(' , ',$validator->errors()->all());
          return response()->json(failReturn($msg));
        }
  }

  //   public function ClientChangeOrderPaymentType(Request $request){
  //       $validator       = Validator::make($request->all(),[
  //           'order_id'     => 'required',
  //           'payment_type' => 'required',
  //           'coupon'       => 'nullable'
  //       ]);
  //       if($validator->passes()){
  //         $lang = ($request->header('lang'))?? 'ar';
  //         $user = JWTAuth::parseToken()->authenticate(); 
  //         if($order = Order::where(['id'=>$request->order_id,'user_id'=>$user->id])->first()){
  //             if($order->status == 'closed'){
  //               $msg = trans('order.order_closed');
  //               return response()->json(failReturn($msg)); 
  //             }elseif($order->status == 'finished') {
  //               $msg = trans('order.order_finished');
  //               return response()->json(failReturn($msg)); 
  //             }             
  //           $order->payment_type = $request->payment_type;
  //           //*** add coupon **//
  //           // if($usercoupon = usersCoupons::where(['user_id'=>$user->id,'used'=>'false'])->where('end_at','>=',date('Y-m-d'))->first()){
  //           //   $order->have_coupon = 'true';
  //           // }
  //           //*** end add coupon **// 
  //           $order->save();
  //           //send notification to captain with change payment way and reload finish order function           
  //             $devices         = userDevices::where(['user_id'=>$order->captain_id])->get();
  //             $notify_title_ar = 'تغير طريقة الدفع';
  //             $notify_title_en = 'Change Payment Way';
  //             $message_ar      = 'قام '.$user->name.' بتغير طريقة الدفع الي '.$order->payment_type;
  //             $message_en      = $user->name.' changes payment way to '.$order->payment_type;
  //             $data = ['title_ar' => $notify_title_ar,'title_en'=> $notify_title_en,'message_en'=>$message_en,'message_ar'=>$message_ar,'key'=>'changeOrderPayment','order_id'=>$order->id,'order_status'=>$order->status,'type' => $order->type,'order_type' => $order->order_type];
  //             sendNotification($devices, $message_ar,$notify_title_ar,$data);
  //             notify($order->captain_id,$user->id,'order.changeOrderPaymentTitle','order.changeOrderPayment',"order_id:".$order->id,$order->status,'changeOrderPayment');  
  //           //send notification to captain with change payment way and reload finish order function           
  //           $msg = trans('order.changePaymentType_success');
  //           return response()->json(successReturnMsg($msg));
  //         }
  //         $msg = trans('order.order_notavailable');
  //         return response()->json(failReturn($msg));
  //       }else{
  //         $msg   = implode(' , ',$validator->errors()->all());
  //         return response()->json(failReturn($msg));
  //       }
  // }

    public function ClientCancelOrder(Request $request){
        $validator         = Validator::make($request->all(),[
            'order_id'     => 'required',
            'reason_id'    => 'nullable'
        ]);
        if($validator->passes()){
              $user = JWTAuth::parseToken()->authenticate();
              $lang = ($request->header('lang'))?? 'ar';
              $discount = 0;
              if($order = Order::where('id','=',$request->order_id)->first()){
                  if($order->status == 'closed'){
                    $msg = trans('order.order_closed');
                    return response()->json(failReturn($msg)); 
                  }elseif($order->status == 'finished') {
                    $msg = trans('order.order_finished');
                    return response()->json(failReturn($msg)); 
                  } 
                      //***Start Apply discount from Client balance to Captain balance ***//
                      if($captain = $order->captain){
                        // if($order->order_type == 'food'){
                        //     if($order->captain_in_road == 'true'){
                        //       $discount = setting('client_cancel');
                        //       $user->balance    -= round(floatval($discount) ,2);
                        //       $user->save();
                        //       // $captain->balance += round(floatval($discount) ,2);
                        //       $captain->balance_electronic_payment      += round( floatval($discount) , 2);
                        //       $captain->save();    
                        //       savePayment($user->id,$captain->id,$discount,'subtract','order_cancel','finished',$captain->current_country_id,'electronic_balance');
                        //     }
                        // }else{
                          if($priceplan = Prices::find($order->price_id)){
                            if($order->captain_in_road == 'true'){
                              $discount = $priceplan->client_cancel;
                              $user->balance    -= round(floatval($discount) ,2);
                              $user->save();
                              // $captain->balance += round(floatval($discount) ,2);
                              $captain->balance_electronic_payment      += round( floatval($discount) , 2);
                              $captain->save();     
                              savePayment($user->id,$captain->id,$discount,'subtract','order_cancel','finished',$captain->current_country_id,'electronic_balance' );
                            }
                          } 
                        // }
                          $usersOrdersHistory = new usersOrdersHistory();
                          $usersOrdersHistory->captain_id  = $captain->id;
                          $usersOrdersHistory->order_id    = $order->id;
                          $usersOrdersHistory->status      = 'closed';
                          $usersOrdersHistory->price       = floatval($discount);
                          $usersOrdersHistory->currency_ar = $order->currency_ar;
                          $usersOrdersHistory->currency_en = $order->currency_en;
                          $usersOrdersHistory->date        = date('Y-m-d');
                          $usersOrdersHistory->month       = date('Y-m');
                          $usersOrdersHistory->save();                        
                      }

                      if($request->has('reason_id')){
                        if($reason = cancelReasons::find($request->reason_id)){
                          $order->close_reason = $reason->reason_ar;
                        }
                      }

                      $order->status       = 'closed';
                      $order->save();
                      if($captain = $order->captain){
                         $captain->have_order = 'false';
                         $captain->save();
                      }
                      $user->num_closed_orders   += 1;
                      $user->have_order           = 'false';
                      $user->save();
                      //**Start notify order Captain with client cancel ******//
                      $devices         = userDevices::where(['user_id'=>$order->captain_id])->get();
                      // if($order->order_type == 'food'){
                      //   $notify_title_ar = 'إلغاء الطلب';
                      //   $notify_title_en = 'Cancelled Order';
                      //   $message_ar      = 'قام '.$user->name.' بإلغاء الطلب.';
                      //   $message_en      = $user->name.' cancelled order.';
                      //   $data = ['title_ar' => $notify_title_ar,'title_en'=>$notify_title_en,'message_en'=>$message_en,'message_ar'=>$message_ar,'key'=>'cancelOrder','order_id'=>$order->id,'order_status'=>$order->status,'type' => $order->type,'order_type' => $order->order_type];
                      //   sendNotification($devices, $message_ar,$notify_title_ar,$data);
                      //   notify($order->captain_id,$user->id,'order.cancelOrderTitle','order.cancelOrder',"order_id:".$order->id,$order->status,'cancelOrder');  
                      // }else{
                        $notify_title_ar = 'إلغاء للرحلة';
                        $notify_title_en = 'Cancelled Trip';
                        // $message_ar      = 'قام '.$user->name.' بإلغاء للرحلة.';
                        // $message_en      = $user->name.' cancelled trip.';
                        $message_ar = replacePlaceholders(setting('cancelOrder_msg_ar'),['name' => $user->name]);
                        $message_en = replacePlaceholders(setting('cancelOrder_msg_ar'),['name' => $user->name]);

                        $data = ['title_ar' => $notify_title_ar,'title_en'=>$notify_title_en,'message_en'=>$message_en,'message_ar'=>$message_ar,'key'=>'cancelOrder','order_id'=>$order->id,'order_status'=>$order->status,'type' => $order->type,'order_type' => $order->order_type];
                        sendNotification($devices, $message_ar,$notify_title_ar,$data);
                        notify($order->captain_id,$user->id,'order.cancelTripTitle','order.cancelTrip',"order_id:".$order->id,$order->status,'cancelOrder');  
                      // }
                      //**Start delete all current order bids and apply bids and notifications**//
                      DB::table('notifications')->where('user_id','=',$user->id)
                                                ->where('data','like','%'.'order_id:'.$order->id.'%')
                                                ->orwhere('notifier_id','=',$user->id)
                                                ->where('data','like','%'.'order_id:'.$order->id.'%')
                                                ->delete();
                  $msg = trans('order.cancel_success');
                  return response()->json(successReturnMsg($msg)); 
              }
              $msg = trans('order.order_notavailable');
              return response()->json(failReturn($msg));
        }else{
            $msg  = implode(' , ',$validator->errors()->all());
            return response()->json(failReturn($msg));
        }
    }    
     
    public function expectedTime(Request $request){
        $validator  = Validator::make($request->all(),[
            'from_lat'    => 'required',
            'from_long'   => 'required',
            'to_lat'      => 'required',
            'to_long'     => 'required',
        ]);
        if($validator->passes()){
          $lang      = ($request->header('lang'))?? 'ar';
          $from_lat  = doubleval($request->from_lat);
          $from_long = doubleval($request->from_long);
          $to_lat    = doubleval($request->to_lat);
          $to_long   = doubleval($request->to_long);
          $expected  = GetDrivingDistance($from_lat,$from_long,$to_lat,$to_long,'en');
          $expected_distance = $expected['distance'];
          $expected_time     = $expected['time'];
          $data = ['distance' => $expected_distance,'time'=>$expected_time];
          return response()->json(successReturn($data));
        }else{
              $msg   = implode(' , ',$validator->errors()->all());
              return response()->json(failReturn($msg));
        }
    }

    public function expectedTimeNearestCar(Request $request){
        $validator  = Validator::make($request->all(),[
            'from_lat'    => 'required',
            'from_long'   => 'required',
        ]);
        if($validator->passes()){
          $lang       = ($request->header('lang'))?? 'ar';
          $from_lat   = doubleval($request->from_lat);
          $from_long  = doubleval($request->from_long);
          $distance   = floatval((setting('distance') * 0.1 ) / 15 );
          $min_lat    = $from_lat  - $distance;
          $min_long   = $from_long - $distance;
          $max_lat    = $from_lat  + $distance;
          $max_long   = $from_long + $distance; 
          $captains   = [];
          if($request->car_type_id){
            $nearstUsers = DB::select("SELECT *, ( 3959 * acos( cos( radians('".$from_lat."') ) * cos( radians( lat ) ) * cos( radians( users.long ) - radians('".$from_long."') ) + sin( radians('".$from_lat."') ) * sin( radians( lat ) ) ) ) AS distance FROM users where captain_current_car_type_id like '%".$request->car_type_id.",%' and captain = 'true' and active = 'active' and available = 'true' and users.lat!='' and users.long != '' ORDER BY distance ASC LIMIT 10 ");
          }else{
            $nearstUsers = DB::select("SELECT *, ( 3959 * acos( cos( radians('".$from_lat."') ) * cos( radians( lat ) ) * cos( radians( users.long ) - radians('".$from_long."') ) + sin( radians('".$from_lat."') ) * sin( radians( lat ) ) ) ) AS distance FROM users where captain = 'true' and active = 'active' and available = 'true' and users.lat!='' and users.long != '' ORDER BY distance ASC LIMIT 10 ");           
          }
          foreach($nearstUsers as $nearstUser){
            $captains[] = ['id'        => $nearstUser->id,
                           'lat'       => doubleval( $nearstUser->lat ),
                           'long'      => doubleval( $nearstUser->long ),
                           'distance'  => round( floatval($nearstUser->distance), 2 )
                        ];
          }
          $nearst_lat  = ($captains[0]['lat'])?? $from_lat;
          $nearst_long = ($captains[0]['long'])?? $from_long;
          $expected    = GetDrivingDistance($from_lat,$from_long,$nearst_lat,$nearst_long,'en');
          $expected_distance = $expected['distance'];
          $expected_time     = $expected['time'];
          $data = ['distance' => floatval( $expected_distance ),'time' => intval($expected_time) ,'captains' => $captains ];
          return response()->json(successReturn($data));
        }else{
            $msg   = implode(' , ',$validator->errors()->all());
            return response()->json(failReturn($msg));
        }
    }

    //used to return car types ,expected distance and time
    public function carTypes(Request $request){
        $validator  = Validator::make($request->all(),[
            'from_lat'    => 'required',
            'from_long'   => 'required',
            // 'to_lat'      => 'required',
            // 'to_long'     => 'required',
            'order_id'    => 'nullable'
        ]);
        if($validator->passes()){
          $user = JWTAuth::parseToken()->authenticate();
          $lang      = ($request->header('lang'))?? 'ar';
          $from_lat  = doubleval($request->from_lat);
          $from_long = doubleval($request->from_long);
          $to_lat    = doubleval($request->to_lat);
          $to_long   = doubleval($request->to_long);
          $added_value = setting('added_value');
          $wasl = setting('wasl_value');

          $expected  = [];
          if($request->to_lat){ 
            $expected  = GetDrivingDistance($from_lat,$from_long,$to_lat,$to_long,'en');
            $expected_distance = $expected['distance'];
            $expected_time     = $expected['time_text'];
          }else{
            $expected_distance = $expected['distance']  = '';
            $expected_time     = $expected['time_text'] = '';
          }

          $cars = []; 
          if($cartypes = carTypes::orderBy("id",'ASC')->get()){
            foreach ($cartypes as $cartype) {
              $pricedata      = $this->expectedPrice($cartype->id,$expected['distance'],$request->from_lat,$request->from_long,1);
              $price_id       = (isset($pricedata['price_id']))? $pricedata['price_id']:'' ;
              $expected_price = (isset($pricedata['price']) && ($request->to_lat != '') )? $pricedata['price']:'' ;
              $vat   = ($expected_price > 0 )? round( floatval( ($expected_price * ( $added_value / 100 )) ),2) : '0';
              $expected_price_after_vat = $expected_price + $vat + $wasl;
              $cars[] = [ 'id'             => $cartype->id,
                          'name'           => $cartype->{"name_$lang"},
                          'type'           => $cartype->type,
                          'max_weight'     => ($cartype->max_weight)??'',
                          'num_persons'    => intval( $cartype->num_persons ),
                          'image'          => ($cartype->image)? url('img/car/'.$cartype->image):url('img/car/default.png'),
                          'expected_price' => floor($expected_price).' - '.ceil($expected_price_after_vat),
                          'currency'       => ($user->currency)? $user->currency : setting('site_currency_'.$lang),
                          'price_id'       => $price_id
                        ];
            }
          }
          $use_balance_first = ($user->use_balance_first == 'true')? true: false;
          $have_visa = false;
          if($uservisa = userPaymentWays::where('user_id','=',$user->id)->first()){
            $have_visa = true;
          } 
          $data = [ 'cars'              => $cars,
                    'distance'          => (string)$expected_distance,
                    'time'              => (string)$expected_time ,
                    'use_balance_first' => $use_balance_first,
                    'balance'           => number_format($user->balance ,2),
                    'balance_electronic_payment'     => number_format((float)$user->balance_electronic_payment, 2), 
                    'currency'          => $user->currecny??'',
                    'have_visa'         => $have_visa
                  ];
          return response()->json(successReturn($data));
        }else{
              $msg   = implode(' , ',$validator->errors()->all());
              return response()->json(failReturn($msg));
        }
    }

    public function carType(Request $request){
        $validator  = Validator::make($request->all(),[
            'car_type_id' => 'required',  
            'from_lat'    => 'required',
            'from_long'   => 'required',
            // 'to_lat'      => 'required',
            // 'to_long'     => 'required',
            'order_id'    => 'nullable'
        ]);
        if($validator->passes()){
          $user = JWTAuth::parseToken()->authenticate();
          $lang      = ($request->header('lang'))?? 'ar';
          $from_lat  = doubleval($request->from_lat);
          $from_long = doubleval($request->from_long);
          $to_lat    = doubleval($request->to_lat);
          $to_long   = doubleval($request->to_long);
          $expected  = [];
          if($request->to_lat){ 
            $expected  = GetDrivingDistance($from_lat,$from_long,$to_lat,$to_long,'en');
            $expected_distance = $expected['distance'];
            $expected_time     = $expected['time_text'];
          }else{
            $expected_distance = $expected['distance']  = '';
            $expected_time     = $expected['time_text'] = '';
          }

          $cars = []; 
          if($cartype = carTypes::find($request->car_type_id)){
              $pricedata      = $this->expectedPrice($cartype->id,$expected['distance'],$request->from_lat,$request->from_long,1);
              $price_id       = (isset($pricedata['price_id']))? $pricedata['price_id']:'' ;
              $expected_price = (isset($pricedata['price']) && ($request->to_lat != '') )? $pricedata['price']:'' ;
              $cars[]  = [ 'id'            => $cartype->id,
                           'name'           => $cartype->{"name_$lang"},
                           'type'           => $cartype->type,
                           'max_weight'     => ($cartype->max_weight)??'',
                           'num_persons'    => intval( $cartype->num_persons ),
                           'image'          => ($cartype->image)? url('img/car/'.$cartype->image):url('img/car/default.png'),
                           'expected_price' => ''.round($expected_price,2).'',
                           'currency'       => ($user->currency)? $user->currency : setting('site_currency_'.$lang),
                           'price_id'       => $price_id
                        ];
          }
          $use_balance_first = ($user->use_balance_first == 'true')? true: false;
          $have_visa = false;
          if($uservisa = userPaymentWays::where('user_id','=',$user->id)->first()){
            $have_visa = true;
          } 
          $currency = ($user->currency)? $user->currency : setting('site_currency_'.$lang);
          $data = [ 'cars'              => $cars,
                    'distance'          => $expected_distance,
                    'time'              => $expected_time ,
                    'use_balance_first' => $use_balance_first,
                    'balance'           => number_format($user->balance ,2),
                    'balance_electronic_payment'     => number_format((float)$user->balance_electronic_payment, 2),
                    'currency'          => $currency,  
                    'have_visa'         => $have_visa,
                  ];                  
          return response()->json(successReturn($data));
        }else{
              $msg   = implode(' , ',$validator->errors()->all());
              return response()->json(failReturn($msg));
        }
    }

    public static function expectedPrice($car_type_id = 0,$distance=0,$lat='',$long='',$waiting_minutes = 1){
        $user = JWTAuth::parseToken()->authenticate();
        $price = 0;       
        if($priceplan = Prices::where(['car_type_id'=>$car_type_id])->first()){
              $price = $priceplan->counter + (floatval($priceplan->waiting_minute) * intval($waiting_minutes) ) +($priceplan->km_price * intval($distance) );
              $price = ($price < $priceplan->min_price)? $priceplan->min_price : $price;
        }
        //** check if in rush hour or not**//
          $rush_hour_percentage = checkRushHour($lat , $long );
          $price = ( $price + ( ($rush_hour_percentage / 100) * $price ) );
          $data['price_id'] = ($priceplan->id)?? 0;
          $data['price']    = round($price,2);
        //**end check if in rush hour or not **//
      return $data;
    }

    public function getExpectedDistancePriceTime(Request $request){
        $validator  = Validator::make($request->all(),[
            'from_lat'    => 'required',
            'from_long'   => 'required',
            'to_lat'      => 'required',
            'to_long'     => 'required'
        ]);
        if($validator->passes()){
          $user = JWTAuth::parseToken()->authenticate();
          $lang      = ($request->header('lang'))?? 'ar';
          $from_lat  = doubleval($request->from_lat);
          $from_long = doubleval($request->from_long);
          $to_lat    = doubleval($request->to_lat);
          $to_long   = doubleval($request->to_long);
          $expected  = [];
          if($request->to_lat){ 
            $expected  = GetDrivingDistance($from_lat,$from_long,$to_lat,$to_long,'en');
            $expected_distance = $expected['distance'];
            $expected_time     = $expected['time_text'];
          }else{
            $expected_distance = $expected['distance']  = '';
            $expected_time     = $expected['time_text'] = '';
          }

          $pricedata      = $this->expectedFoodDeliveryPrice($expected['distance']);
          $expected_price = (isset($pricedata['price']) && ($request->to_lat != '') )? $pricedata['price'] : '' ;
          $use_balance_first = ($user->use_balance_first == 'true')? true: false;
          $have_visa = false;
          if($uservisa = userPaymentWays::where('user_id','=',$user->id)->first()){
            $have_visa = true;
          } 
          $currency = ($user->currency)? $user->currency : setting('site_currency_'.$lang);
          $data = [ 'distance'          => $expected_distance,
                    'time'              => $expected_time ,
                    'use_balance_first' => $use_balance_first,
                    'balance'           => number_format( $user->balance ,2),
                    'balance_electronic_payment'     => number_format((float)$user->balance_electronic_payment, 2),  
                    'have_visa'         => $have_visa,
                    'expected_price'    => number_format($expected_price,2),
                    'currency'          => $currency,
                  ];                  
          return response()->json(successReturn($data));
        }else{
              $msg   = implode(' , ',$validator->errors()->all());
              return response()->json(failReturn($msg));
        }
    }

    public static function expectedFoodDeliveryPrice($distance = 0){
        $price = 0;       
        $km_price        = setting('km_price');
        $min_order_price = setting('min_order_price');        
        $delivery_price  = ceil(floatval($distance) * floatval($km_price));
        $delivery_price  = ( $delivery_price >= floatval($min_order_price) )? $delivery_price : $min_order_price;   
        $data['price'] = round($delivery_price,2);
      return $data;
    }


}