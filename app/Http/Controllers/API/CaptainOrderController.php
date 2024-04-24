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
use App\Prices;
use App\savedPlaces;
use Jenssegers\Date\Date;
use App\Country;
use App\Coupons;
use App\usersCoupons;
use App\orderWithdrawReasons;
use App\cancelReasons;
use App\orderPath;
use App\userCars;
use DateTime;
use App\Http\Controllers\API\ClientOrderController;
use App\userDevices;
use App\usersOrdersHistory;
use App\Notifications;
use App\Guarantees;
use App\GuaranteesHistory;
use App\Rewards;
use App\rewardsHistory;
use App\Profits;
use App\Mail\invoiceEmailMessage;
use Mail;
use App\Conversation;
use App\CashBack;
use App\UsersCashBack;

class CaptainOrderController extends Controller{

    //captain home map
    public function CaptainNearOrders(Request $request){
        $validator = Validator::make($request->all(),[
            'lat'    => 'required',
            'long'   => 'required'
        ]);
        if($validator->passes()){
          $user       = JWTAuth::parseToken()->authenticate();
          $lat        = doubleval($request->lat);
          $long       = doubleval($request->long);
          $user->lat  = $lat;
          $user->long = $long;
          $user->save();
          $distance   = floatval((setting('distance') * 0.1 ) / 15 );
          $min_lat    = $lat  - $distance;
          $min_long   = $long - $distance;
          $max_lat    = $lat  + $distance;
          $max_long   = $long + $distance;
          $lang       = ($request->header('lang'))?? 'ar';
          $rush_hour  = false;
          $rush_hour_percentage = '';

          $third_rush_hour      = 0;
          $third_rush_hour_percentage = 0;
          $second_rush_hour     = 0;
          $second_rush_hour_percentage = 0;
          $first_rush_hour = 0;
          $first_rush_hour_percentage = 0;
            $orders = Order::where('captain_id','!=',$user->id)->where('start_lat','>=',$min_lat)->where('start_lat','<=',$max_lat)->where('start_long','>=',$min_long)->where('start_long','<=',$max_long)->where('status','=','open')->get();
            $dataOrders = [];
            foreach($orders as $order){
              $dataOrders [] = ['id'               => $order->id,
                                'start_lat'        => "$order->start_lat",
                                'start_long'       => "$order->start_long",
                                'start_address'    => $order->start_address,
                                'end_lat'          => ($order->end_lat)? "$order->end_lat" : "",
                                'end_long'         => ($order->end_long)? "$order->end_long" : "",
                                'end_address'      => ($order->end_address)?? "",
                               ];
            }
            if(count( $orders ) >= setting('third_rush_hour')){
              $rush_hour            = true;
              $rush_hour_percentage = setting('third_rush_hour_percentage');
              $third_rush_hour = setting('third_rush_hour');
              $third_rush_hour_percentage = setting('third_rush_hour_percentage');
            }elseif(count( $orders ) >= setting('second_rush_hour')){
              $rush_hour            = true;
              $rush_hour_percentage = setting('second_rush_hour_percentage');
              $second_rush_hour = setting('second_rush_hour');
              $second_rush_hour_percentage = setting('second_rush_hour_percentage');
            }elseif(count( $orders ) >= setting('first_rush_hour')){
              $rush_hour            = true;
              $rush_hour_percentage = setting('first_rush_hour_percentage');
              $first_rush_hour = setting('first_rush_hour');
              $first_rush_hour_percentage = setting('first_rush_hour_percentage');
            }
            //check if captain have orders now
            $captain_have_order = false;
            $current_order_id   = 0;
            $captain_status     = 'pending';
            $journey_type       = 'single';

            if($captainCurrentOrder = Order::where('captain_id','=',$user->id)->where('status','=','inprogress')->where('type','=','now')
                                             ->orwhere('captain_id','=',$user->id)->where('status','=','inprogress')->where('type','=','later')->where('later_order_date','=',date('Y-m-d'))->where('later_order_time','>=',time() )
                                             ->orwhere('captain_id','=',$user->id)->where('status','=','inprogress')->where('captain_in_road','=','true')
                                             ->orwhere('captain_id','=',$user->id)->where('status','=','finished')->where('confirm_payment','=','false')->orderBy('start_journey_time','DESC')->first()){

              $captain_have_order = true;
              $current_order_id   = $captainCurrentOrder->id;
                if($captainCurrentOrder->status == 'finished' && $captainCurrentOrder->confirm_payment == 'true'){
                   $captain_status = 'captain_finished';
                }elseif($captainCurrentOrder->status == 'finished' && $captainCurrentOrder->confirm_payment == 'false'){
                   $captain_status = 'captain_finished_no_payment';
                }elseif($captainCurrentOrder->captain_in_road == 'true' && $captainCurrentOrder->captain_arrived == 'true' && $captainCurrentOrder->start_journey == 'true'){
                   $captain_status = 'start_journey';
                }elseif($captainCurrentOrder->captain_in_road == 'true' && $captainCurrentOrder->captain_arrived == 'true' && $captainCurrentOrder->start_journey == 'false'){
                   $captain_status = 'captain_arrived';
                }elseif($captainCurrentOrder->captain_in_road == 'true' && $captainCurrentOrder->captain_arrived == 'false' && $captainCurrentOrder->start_journey == 'false'){
                   $captain_status = 'captain_in_road';
                }elseif($captainCurrentOrder->status == 'inprogress' && $captainCurrentOrder->captain_in_road == 'false' && $captainCurrentOrder->captain_arrived == 'false' && $captainCurrentOrder->start_journey == 'false'){
                   $captain_status = 'captain_accept';
                }
            }
          $data = ['captain_have_order'   => $captain_have_order,
                   'current_order_id'     => $current_order_id,
                   'captain_status'       => $captain_status,
                   'balance'              => number_format($user->balance,2),
                   'balance_electronic_payment'     => number_format((float)$user->balance_electronic_payment, 2), 
                   'rush_hour'            => $rush_hour,
                   'rush_hour_percentage' => $rush_hour_percentage,
                   'first_rush_hour'      => intval($first_rush_hour),
                   'first_rush_hour_percentage'  => intval($first_rush_hour_percentage),
                   'second_rush_hour'            => intval($second_rush_hour),
                   'second_rush_hour_percentage' => intval($second_rush_hour_percentage),
                   'third_rush_hour'             => intval($third_rush_hour),
                   'third_rush_hour_percentage'  => intval($third_rush_hour_percentage),
                   'orders'                      => $dataOrders,
                   'num_notifications'           => (Notifications::where(['user_id' => $user->id, 'seen'=>'false'])->count())??0
                   ];
          return response()->json(successReturn($data));
        }else{
            $msg  = implode(' , ',$validator->errors()->all());
            return response()->json(failReturn($msg));
        }
    }

    public function CaptainLaterOrders(Request $request){
      $data = [];
      $user = JWTAuth::parseToken()->authenticate();
      if($orders = Order::where('captain_id',$user->id)->where('status','inprogress')->orwhere('captain_id',$user->id)->where('status','finished')->where('confirm_payment','=','false')->orderBy('later_order_date','ASC')->orderBy('later_order_time','ASC')->orderBy('created_at','DESC')->get()){

        foreach ($orders as $order) {
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

          $can_update = (strtotime('now') < strtotime($order->later_order_date.' '.$order->later_order_time)) ? true : false;
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
          if(($order->type == 'now') || ( ($order->type == 'later') && (strtotime('now') >= strtotime($order->later_order_date.' '.$order->later_order_time) ) ) ){
            $datetime = trans('order.now');
          }
          $data[] = [ 'id'               => $order->id ,
                      'order_type'       => ($order->order_type)??'trip',
                      'place_id'         => ($order->place_id)??'',
                      'place_ref'        => ($order->place_ref)??'',
                      'place_name'       => ($order->place_name)??'',
                      'icon'             => url('img/icons/restaurant.png'),          
                      'can_update'       => $can_update, 
                      'captain_status'   => $captain_status,
                      'start_journey'    => $order->start_journey,
                      'date'             => $datetime,
                      'later_order_date' => ($order->later_order_date)?? '', 
                      'later_order_time' => ($order->later_order_time)?? '', 
                      'start_lat'        => "$order->start_lat",
                      'start_long'       => "$order->start_long",
                      'start_address'    => ($order->start_address)??'',
                      'end_lat'          => "$order->end_lat",
                      'end_long'         => "$order->end_long",
                      'end_address'      => ($order->end_address)??'',
                    ];
        }
      }
      return response()->json(successReturn($data));
    }    

    public function UpdateCaptainLaterOrder(Request $request){
        $validator        = Validator::make($request->all(),[
            'order_id'          => 'required|integer',
            'later_order_date'  => 'required|date|after:'.date('Y-m-d',strtotime('yesterday')),
            'later_order_time'  => 'required',
        ]);
        if($validator->passes()){
          $data = [];
          $user = JWTAuth::parseToken()->authenticate();
          $lang = ($request->header('lang'))?? 'ar';
          if($order = Order::where('id','=',$request->order_id)->where('captain_id','=',$user->id)->first() ){
             
            if(strtotime('now') > strtotime($request->later_order_date.' '.$request->later_order_time)){
                $time = Date::parse(date('Y-m-d H:i:s'))->format('j F h:i'); 
                $time .= trans('order.'.date('a'));  
                $msg = trans('order.oldDate',['time' => $time]);
                return response()->json(failReturn($msg));
            }
             $order->later_order_date    = date('Y-m-d',strtotime($request->later_order_date));
             $order->later_order_time    = date('H:i:s',strtotime($request->later_order_time));
             $order->type                = 'later';
             $order->save();
             $datetime = $order->later_order_date.' '.$order->later_order_time;
            $msg = trans('order.updateOrderTimeSuccess');
            return response()->json(successReturnMsg($msg)); 
          }
          $msg = trans('order.order_notavailable');
          return response()->json(failReturn($msg));
        }else{
            $msg   = implode(' , ',$validator->errors()->all());
            return response()->json(failReturn($msg));
        }
    }

    public function CaptainAcceptOrder(Request $request){
        $validator        = Validator::make($request->all(),[
            'order_id'    => 'required',
        ]);
        if($validator->passes()){
          $data = [];
          $lang = ($request->header('lang'))?? 'ar';
          if($user = JWTAuth::parseToken()->authenticate()){
            if($order = Order::where(['id'=>$request->order_id])->first() ){
              if($order->status == 'closed'){
                $msg = trans('order.order_closed');
                return response()->json(failReturn($msg)); 
              }elseif(($order->status == 'inprogress') && ($order->captain_id != $user->id) && ($user->captain == 'true')){
                $msg = trans('order.order_received');
                return response()->json(failReturn($msg)); 
              }elseif($order->status == 'finished') {
                $msg = trans('order.order_finished');
                return response()->json(failReturn($msg)); 
              }   
            //check num of orders from that client today .. if >= 3 give block for the captain    
            if(Order::where('captain_id','=',$user->id)->where('user_id','=',$order->user_id)->where('status','=','finished')->where('created_at','like','%'.date('Y-m-d').'%')->count() >= 2){
              $user->active = 'block';
              $user->save();
              $msg = trans('order.fake_trips');
              return response()->json(failReturnData(['reason' => 'fake_trips'],$msg)); 
            }

              $max_debt_captain = setting('max_debt_captain');             
              if( (floatval($user->balance) <= (-1 * $max_debt_captain) ) && ($order->payment_type == 'cash') ){
                  $msg = trans('order.cantaccept');
                  return response()->json(failReturn($msg));                 
              } 
              DB::table('notifications')->where(['user_id'=>$user->id,'key'=>'newOrder'])
                                                                ->where('data','like','%'.'order_id:'.$order->id.'%')
                                                                ->delete();   
              $order->captain_id     = $user->id;
              $order->car_id         = $user->captain_current_car_id ? $user->captain_current_car_id : (($userCar = userCars::where('user_id','=',$order->captain_id)->first())? $userCar->id : null) ;
              // if($order->order_type == 'food'){
              //   $order->car_type_id  = $user->captain_current_car_type_id;
              // }
              $order->status         = 'inprogress';
              $order->reception_time = date('Y-m-d H:i:s');
              $order->save();
              $user->have_order      = 'true';
              $user->save();

              /********start create conversation between users **/
              $conversation_id = 0;
              if($order->user_id){
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

              //** send notification to client with accept order **//
              $devices         = userDevices::where(['user_id'=>$order->user_id])->get();
              // if($order->order_type == 'food'){
              //   $notify_title_ar = 'الموافقة علي الطلب';
              //   $notify_title_en = 'Accept The Order';
              //   $message_ar      = 'قام '.$user->name.' بالموافقة علي الطلب .';
              //   $message_en      = $user->name.' accept the order.';
              //   $notifyData      = ['title_ar' => $notify_title_ar,'title_en' =>$notify_title_en,'message_en'=>$message_en,'message_ar'=>$message_ar,'key'=>'AcceptOrder','order_id'=>$order->id,'conversation_id' => $conversation_id,'order_status'=>$order->status,'type' => $order->type,'order_type' => $order->order_type];
              //   sendNotification($devices, $message_ar,$notify_title_ar,$notifyData);
              //   notify($order->user_id,$user->id,'order.AcceptOrderTitle','order.AcceptOrder',"order_id:".$order->id.':conversation_id:'.$conversation_id,$order->status,'AcceptOrder');               
              // }else{
                $notify_title_ar = 'الموافقة علي الرحلة';
                $notify_title_en = 'Accept The Trip';
                // $message_ar      = 'قام '.$user->name.' بالموافقة علي الرحلة .';
                // $message_en      = $user->name.' accept the trip.';
                $message_ar = replacePlaceholders(setting('AcceptOrder_msg_ar'),['name' => $user->name]);
                $message_en = replacePlaceholders(setting('AcceptOrder_msg_en'),['name' => $user->name]);

                $notifyData      = ['title_ar' => $notify_title_ar,'title_en' =>$notify_title_en,'message_en'=>$message_en,'message_ar'=>$message_ar,'key'=>'AcceptOrder','order_id'=>$order->id,'conversation_id' => $conversation_id,'order_status'=>$order->status,'type' => $order->type,'order_type' => $order->order_type];
                sendNotification($devices, $message_ar,$notify_title_ar,$notifyData);
                notify($order->user_id,$user->id,'order.AcceptTripTitle','order.AcceptTrip',"order_id:".$order->id.':conversation_id:'.$conversation_id,$order->status,'AcceptOrder');                  
              // }
              //** end send notification to client with accept order **//
              $msg = trans('order.accept_success');
              $data['conversation_id'] = $conversation_id;
              return response()->json(successReturn($data,$msg));
            }
          $msg = trans('order.order_notavailable');
          return response()->json(failReturn($msg));          
          }else{
            return response()->json(['value' => '0' , 'key' => 'fail' ,'msg' => 'Token is Invalid','code'=>419]);
          }
        }else{
            $msg   = implode(' , ',$validator->errors()->all());
            return response()->json(failReturn($msg));
        }
    }

    public function CaptainInWayToOrderClient(Request $request){
        $validator        = Validator::make($request->all(),[
            'order_id'    => 'required',
        ]);
        if($validator->passes()){
          $data = [];
          $lang = ($request->header('lang'))?? 'ar';
          $user = JWTAuth::parseToken()->authenticate();
          if($order = Order::where(['id'=>$request->order_id])->first() ){
              if($order->status == 'closed'){
                $msg = trans('order.order_closed');
                return response()->json(failReturn($msg)); 
              }elseif(($order->status == 'inprogress') && ($order->captain_id != $user->id) && ($user->captain == 'true')){
                $msg = trans('order.order_received');
                return response()->json(failReturn($msg)); 
              }elseif($order->status == 'finished') {
                $msg = trans('order.order_finished');
                return response()->json(failReturn($msg)); 
              }   

            if($order->user_id == null && $order->order_src == 'internal'){
              $msg = trans('order.noclients');
              return response()->json(failReturn($msg)); 
            }
            $order->captain_in_road  = 'true';
            $order->save();

              /********start create conversation between users **/
              $conversation_id = 0;
              if($order->user_id){
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

            //** send notification to client that Captain in road **//
            $devices         = userDevices::where(['user_id'=>$order->user_id])->get();
            // if($order->order_type == 'food'){
            //   $notify_title_ar = 'فى الطريق للمتجر';
            //   $notify_title_en = 'In Way to store';
            //   $message_ar      = $user->name.' فى طريقة للمتجر.';
            //   $message_en      = $user->name.' in way to store.';
            //   $notifyData      = ['title_ar' => $notify_title_ar,'title_en' => $notify_title_en,'message_en'=>$message_en,'message_ar'=>$message_ar,'key'=>'inWayToOrder','order_id'=>$order->id,'conversation_id' => $conversation_id,'order_status'=>$order->status,'type' => $order->type,'order_type' => $order->order_type];
            //   sendNotification($devices, $message_ar,$notify_title_ar,$notifyData);
            //   notify($order->user_id,$user->id,'order.inWayToOrderTitle','order.inWayToOrder',"order_id:".$order->id,$order->status,'inWayToOrder');               
            // }else{
              $notify_title_ar = 'الكابتن جايك الحين!🚙';
              $notify_title_en = 'Captain is coming now!🚙';
              // $message_ar      = $user->name.' فى طريقة اليك.';
              // $message_en      = $user->name.' in way to you.';
              $message_ar = replacePlaceholders(setting('inWayToOrder_msg_ar'),['name' => $user->name]);
              $message_en = replacePlaceholders(setting('inWayToOrder_msg_en'),['name' => $user->name]);

              $notifyData      = ['title_ar' => $notify_title_ar,'title_en' => $notify_title_en,'message_en'=>$message_en,'message_ar'=>$message_ar,'key'=>'inWayToOrder','order_id'=>$order->id,'conversation_id' => $conversation_id,'order_status'=>$order->status,'type' => $order->type,'order_type' => $order->order_type];
              sendNotification($devices, $message_ar,$notify_title_ar,$notifyData);
              notify($order->user_id,$user->id,'order.inWayToTripTitle','order.inWayToTrip',"order_id:".$order->id,$order->status,'inWayToOrder');                
            // }
            //** end send notification to client that Captain in road **//
            $msg = trans('order.sure_InWay');
            $data['conversation_id'] = $conversation_id;
            return response()->json(successReturn($data,$msg));
          }
          $msg = trans('order.order_notavailable');
          return response()->json(failActionReturn($msg));
        }else{
            $msg   = implode(' , ',$validator->errors()->all());
            return response()->json(failReturn($msg));
        }
    }

    public function CaptainArrivedToOrderClient(Request $request){
        $validator        = Validator::make($request->all(),[
            'order_id'    => 'required|integer',
        ]);
        if($validator->passes()){
          $data = [];
          $lang = ($request->header('lang'))?? 'ar';
          $user = JWTAuth::parseToken()->authenticate();
          if($order = Order::where(['id'=>$request->order_id])->first() ){
              if($order->status == 'closed'){
                $msg = trans('order.order_closed');
                return response()->json(failReturn($msg)); 
              }elseif(($order->status == 'inprogress') && ($order->captain_id != $user->id) && ($user->captain == 'true')){
                $msg = trans('order.order_received');
                return response()->json(failReturn($msg)); 
              }elseif($order->status == 'finished') {
                $msg = trans('order.order_finished');
                return response()->json(failReturn($msg)); 
              }   
            $order->captain_arrived      = 'true';
            $order->captain_arrived_time = date('Y-m-d H:i:s');
            $order->save();
            /********start create conversation between users **/
            $conversation_id = 0;
            if($order->user_id){
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
            //** send notification to client that Captain Arrived **//
              $devices         = userDevices::where(['user_id'=> $order->user_id])->get();
            // if($order->order_type == 'food'){
            //   $notify_title_ar = 'وصول للمتجر';
            //   $notify_title_en = 'Captain Arrived Store';
            //   $message_ar      = $user->name.' وصل المتجر المحدد.';
            //   $message_en      = $user->name.' Arrived to store.';
            //   $notifyData      = ['title_ar' => $notify_title_ar,'title_en'=>$notify_title_en,'message_en'=>$message_en,'message_ar'=>$message_ar,'key'=>'arrivedToOrder','order_id'=>$order->id,'conversation_id' => $conversation_id,'order_status'=>$order->status,'type' => $order->type,'order_type' => $order->order_type];
            //   sendNotification($devices, $message_ar,$notify_title_ar,$notifyData);
            //   notify($order->user_id,$user->id,'order.arrivedToOrderTitle','order.arrivedToOrder',"order_id:".$order->id,$order->status,'arrivedToOrder');                 
            // }else{
              $notify_title_ar = 'أنا وصلت عندك!🙈';
              $notify_title_en = 'I have arrived there!🙈';
              // $message_ar      = $user->name.' وصل لاصطحابك من المكان المحدد.';
              // $message_en      = $user->name.' Arrived to pick you up from the exact place.';
              $message_ar = replacePlaceholders(setting('arrivedToOrder_msg_ar'),['name' => $user->name]);
              $message_en = replacePlaceholders(setting('arrivedToOrder_msg_en'),['name' => $user->name]);

              $notifyData      = ['title_ar' => $notify_title_ar,'title_en'=>$notify_title_en,'message_en'=>$message_en,'message_ar'=>$message_ar,'key'=>'arrivedToOrder','order_id'=>$order->id,'conversation_id' => $conversation_id,'order_status'=>$order->status,'type' => $order->type,'order_type' => $order->order_type];
              sendNotification($devices, $message_ar,$notify_title_ar,$notifyData);
              notify($order->user_id,$user->id,'order.arrivedToTripTitle','order.arrivedToTrip',"order_id:".$order->id,$order->status,'arrivedToOrder');                
            // }
            //** end send notification to client that Captain Arrived **//
            $msg = trans('order.sure_arrived');
            $data['conversation_id'] = $conversation_id;
            return response()->json(successReturn($data,$msg));
          }
          $msg = trans('order.order_notavailable');
          return response()->json(failActionReturn($msg));
        }else{
            $msg   = implode(' , ',$validator->errors()->all());
            return response()->json(failReturn($msg));
        }
    }

   //to start single journey ,or shared journey for all in the same time
    public function CaptainStartJourney(Request $request){
        $validator        = Validator::make($request->all(),[
            'order_id'    => 'required|integer',
        ]);
        if($validator->passes()){
          $data = [];
          $lang = ($request->header('lang'))?? 'ar';
          $user = JWTAuth::parseToken()->authenticate();
          if($order = Order::where(['id'=>$request->order_id])->first() ){
              if($order->status == 'closed'){
                $msg = trans('order.order_closed');
                return response()->json(failReturn($msg)); 
              }elseif(($order->status == 'inprogress') && ($order->captain_id != $user->id) && ($user->captain == 'true')){
                $msg = trans('order.order_received');
                return response()->json(failReturn($msg)); 
              }elseif($order->status == 'finished') {
                $msg = trans('order.order_finished');
                return response()->json(failReturn($msg)); 
              }   

              if($order->captain_in_road == 'false'){
                 $order->captain_in_road  = 'true';
                 $order->reception_time   = date('Y-m-d H:i:s');
              }
              if($order->captain_arrived == 'false'){
                 $order->captain_arrived      = 'true';
                 $order->captain_arrived_time = date('Y-m-d H:i:s');
                 $order->save();
              }  
              /********start create conversation between users **/
              $conversation_id = 0;
              if($order->user_id){
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
              if($order->start_journey == 'false'){
                  $order->start_journey      = 'true';
                  $order->start_journey_time = date('Y-m-d H:i:s');
                  $num_minutes = intval(( time() - strtotime($order->captain_arrived_time)) / 60);
                  $order->initial_wait       = $num_minutes;
                  $order->save();
                  //start send notification to order user with start journey  
                  $devices         = userDevices::where(['user_id'=> $order->user_id])->get();
                // if($order->order_type == 'food'){
                //   $notify_title_ar = 'استلام الطلب';
                //   $notify_title_en = 'Receipt of the order';
                //   $message_ar      = $user->name.' قام باستلام الطلب وفى الطريق اليك.';
                //   $message_en      = $user->name.' received the order and is on the way to you.';
                //   $notifyData      = ['title_ar' => $notify_title_ar,'title_en'=>$notify_title_en,'message_en'=>$message_en,'message_ar'=>$message_ar,'key'=>'startJourney','order_id'=>$order->id,'conversation_id' => $conversation_id,'order_status'=>$order->status,'type' => $order->type,'order_type' => $order->order_type];
                //   sendNotification($devices, $message_ar,$notify_title_ar,$notifyData);
                //   notify($order->user_id,$user->id,'order.receivedOrderTitle','order.receivedOrder',"order_id:".$order->id,$order->status,'startJourney'); 
                // }else{
                  $notify_title_ar = 'بدأنا المشوار!😎';
                  $notify_title_en = 'We started the journey!😎';
                  // $message_ar      = $user->name.' قام ببدأ الرحلة.';
                  // $message_en      = $user->name.' start trip';

                  $message_ar = replacePlaceholders(setting('startJourney_msg_ar'),['name' => $user->name]);
                  $message_en = replacePlaceholders(setting('startJourney_msg_en'),['name' => $user->name]);

                  $notifyData      = ['title_ar' => $notify_title_ar,'title_en'=>$notify_title_en,'message_en'=>$message_en,'message_ar'=>$message_ar,'key'=>'startJourney','order_id'=>$order->id,'conversation_id' => $conversation_id,'order_status'=>$order->status,'type' => $order->type,'order_type' => $order->order_type];
                  sendNotification($devices, $message_ar,$notify_title_ar,$notifyData);
                  notify($order->user_id,$user->id,'order.startJourneyTitle','order.startJourney',"order_id:".$order->id,$order->status,'startJourney');                   
                // }
                  //start send notification to order user with start journey                    
              } 
              $data['conversation_id'] = $conversation_id;
              $msg = trans('order.sure_start_journey');
              return response()->json(successReturn($data,$msg));                           
          }
          $msg = trans('order.order_notavailable');
          return response()->json(failActionReturn($msg));
        }else{
            $msg   = implode(' , ',$validator->errors()->all());
            return response()->json(failReturn($msg));
        }
    }         

    public function CaptainWithdrawOrder(Request $request){
        $validator       = Validator::make($request->all(),[
            'order_id'   => 'required|integer',
            // 'reason_id'  => 'nullable'
        ]);
        if($validator->passes()){
              $user = JWTAuth::parseToken()->authenticate();
              $lang = ($request->header('lang'))?? 'ar';
              $discount = 0;
              if($order = Order::where(['id'=>$request->order_id])->first() ){
                  if($order->status == 'closed'){
                    $msg = trans('order.order_closed');
                    return response()->json(failReturn($msg)); 
                  }elseif(($order->status == 'inprogress') && ($order->captain_id != $user->id) && ($user->captain == 'true')){
                    $msg = trans('order.order_received');
                    return response()->json(failReturn($msg)); 
                  }elseif($order->status == 'finished') {
                    $msg = trans('order.order_finished');
                    return response()->json(failReturn($msg)); 
                  }   
                    //delete notifications of that order to that client
                DB::table('notifications')->where('user_id','=',$order->user_id)
                                          ->where('notifier_id','=',$order->captain_id)
                                          ->where('data','like','%'.'order_id:'.$order->id.'%')
                                          ->orwhere('user_id','=',$order->captain_id)
                                          ->where('notifier_id','=',$order->user_id)
                                          ->where('data','like','%'.'order_id:'.$order->id.'%')
                                          ->delete();


                    //***Start Apply discount from captain balance to app ***//
                      if(usersOrdersHistory::where(['captain_id'=>$user->id,'order_id'=>$order->id,'status'=>'withdraw'])->count() == 0){
                        //***Start Apply discount from captain balance to app ***//
                        // if($order->order_type == 'food'){
                        //     $discount         = setting('captain_cancel');
                        //     $user->balance   -= round($discount,2);
                        //     savePayment($user->id,0,$discount,'subtract','order_withdraw','finished',$user->current_country_id,'balance' );
                        // }else{
                          if($priceplan = Prices::find($order->price_id)){
                            $discount         = floatval($priceplan->captain_cancel * $request->exchangeRate);
                            $user->balance   -= round($discount,2);
                            savePayment($user->id,0,$discount,'subtract','order_withdraw','finished',$user->current_country_id,'balance' );
                          } 
                        // }
                        $user->num_withdraw_orders += 1;
                        $usersOrdersHistory = new usersOrdersHistory();
                        $usersOrdersHistory->captain_id  = $user->id;
                        $usersOrdersHistory->order_id    = $order->id;
                        $usersOrdersHistory->status      = 'withdraw';
                        $usersOrdersHistory->price       = $discount;
                        $usersOrdersHistory->currency_ar = $order->currency_ar;
                        $usersOrdersHistory->currency_en = $order->currency_en;
                        $usersOrdersHistory->date        = date('Y-m-d');
                        $usersOrdersHistory->month       = date('Y-m');
                        $usersOrdersHistory->save();                        
                      }else{
                        $msg = trans('order.withdraw_success');
                        return response()->json(successReturnMsg($msg));
                      }                    
                      $user->have_order = 'false';
                      $user->save();
                      //***add order to withdraws orders ***//
                      if(orderWithdrawReasons::where(['user_id'=>$user->id,'order_id'=>$order->id])->count() == 0){  
                        if($reason = cancelReasons::find($request->reason_id)){
                          $withdrawReason = new orderWithdrawReasons();
                          $withdrawReason->user_id  = $user->id;
                          $withdrawReason->order_id = $order->id;
                          $withdrawReason->type     = $order->order_type;
                          $withdrawReason->reason   = $reason->reason_ar;
                          $withdrawReason->date     = date('Y-m-d');
                          $withdrawReason->save();
                        }
                      }

                      //***Start return order to new open orders ***/
                      $order->captain_id           = null;
                      $order->car_id               = null;
                      $order->initial_wait         = null;
                      $order->status               = 'open';
                      $order->captain_in_road      = 'false';
                      $order->captain_arrived      = 'false';
                      $order->start_journey        = 'false';
                      $order->reception_time       = null;
                      $order->captain_arrived_time = null;
                      $order->start_journey_time   = null;
                      $order->save();
                      //***End return order to new open orders ***/

                      //**Start notify order owner with Captain withdraw ******//
                      $devices         = userDevices::where(['user_id'=> $order->user_id])->get();
                    // if($order->order_type == 'food'){
                    //   $notify_title_ar = 'الانسحاب من اطلب';
                    //   $notify_title_en = 'Withdrew From Order';
                    //   $message_ar      = $user->name.' قام بالانسحاب من الطلب.';
                    //   $message_en      = $user->name.' withdrew from the order';
                    //   $notifyData      = ['title_ar' => $notify_title_ar,'title_en'=>$notify_title_en,'message_en'=>$message_en,'message_ar'=>$message_ar,'key'=>'withdrawOrder','order_id'=>$order->id,'order_status'=>$order->status,'type' => $order->type,'order_type' => $order->order_type];
                    //   sendNotification($devices, $message_ar,$notify_title_ar,$notifyData);
                    //   notify($order->user_id,$user->id,'order.withdrawOrderTitle','order.withdrawOrder',"order_id:".$order->id,$order->status,'withdrawOrder'); 
                    // }else{
                      $notify_title_ar = 'الانسحاب من الرحلة';
                      $notify_title_en = 'Withdrew From Trip';
                      // $message_ar      = $user->name.' قام بالانسحاب من الرحلة.';
                      // $message_en      = $user->name.' withdrew from the trip';

                      $message_ar = replacePlaceholders(setting('withdrawOrder_msg_ar'),['name' => $user->name]);
                      $message_en = replacePlaceholders(setting('withdrawOrder_msg_en'),['name' => $user->name]);
    
                      $notifyData      = ['title_ar' => $notify_title_ar,'title_en'=>$notify_title_en,'message_en'=>$message_en,'message_ar'=>$message_ar,'key'=>'withdrawOrder','order_id'=>$order->id,'order_status'=>$order->status,'type' => $order->type,'order_type' => $order->order_type];
                      sendNotification($devices, $message_ar,$notify_title_ar,$notifyData);
                      notify($order->user_id,$user->id,'order.withdrawTripTitle','order.withdrawTrip',"order_id:".$order->id,$order->status,'withdrawOrder');                       
                    // }
                      //**end notify order owner with Captain withdraw ******//
                      
                      //delete user order history to enable user opened that order before withdraw to open it again
                      DB::table('users_orders_history')->where('order_id' , $order->id)
                                                       ->where('captain_id','!=',$user->id)
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
              //                                                      ->select('users.id','users.lat','users.long','users.distance','user_devices.device_id','user_devices.device_type','user_devices.orders_notify')
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
                                                                   ->select('users.id','users.lat','users.long','users.distance','user_devices.device_id','user_devices.device_type','user_devices.orders_notify')
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
              //                                                      ->select('users.id','users.lat','users.long','users.distance','user_devices.device_id','user_devices.device_type','user_devices.orders_notify')
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
                                                                   ->select('users.id','users.lat','users.long','users.distance','user_devices.device_id','user_devices.device_type','user_devices.orders_notify')
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
            //         $captain_distance = $device->distance??setting('distance');
            //         if((int)directDistance($device->lat,$device->long,$start_lat,$start_long) > (int)$captain_distance){  
            //           $devices->forget($i);
            //           $i++;
            //         }
            //           DB::table('notifications')->where(['user_id'=>$device->id,'key'=>'newOrder'])->delete();
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
                  $i = 0;
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
           
                    $msg = trans('order.withdraw_success');
                    return response()->json(successReturnMsg($msg));
                }
                $msg = trans('order.order_notavailable');
                return response()->json(failActionReturn($msg));                
        }else{
            $msg  = implode(' , ',$validator->errors()->all());
            return response()->json(failReturn($msg));
        }
    }    

    public function CaptainFinishSimpleOrder(Request $request){ 
            $validator     = Validator::make($request->all(),[
            'order_id'     => 'required',
            'lat'          => 'required',
            'long'         => 'required'
        ]);
        if($validator->passes()){
            $user = JWTAuth::parseToken()->authenticate();
            $lang = ($request->header('lang'))?? 'ar';
            $added_value = setting('added_value');
            $wasl = setting('wasl_value');
            if($order = Order::where(['id'=>$request->order_id])->first()){
              if($order->status == 'closed'){
                $msg = trans('order.order_closed');
                return response()->json(failReturn($msg)); 
              }elseif(($order->status == 'inprogress') && ($order->captain_id != $user->id) && ($user->captain == 'true')){
                $msg = trans('order.order_received');
                return response()->json(failReturn($msg)); 
              }elseif($order->status == 'finished') {
                $msg = trans('order.order_finished');
                return response()->json(failReturn($msg)); 
              }   
              $order->end_lat     = doubleval($request->lat);
              $order->end_long    = doubleval($request->long);
              $end_address = '';
              if($request->address){
                $end_address = $request->address;
              }elseif($request->address == '' || $request->address == null){
                $end_address = getAddressBylatlng($request->lat,$request->long,$lang);
              }else{
                $end_address = $order->end_address;
              }
              $order->end_address = $end_address;//($request->address)? $request->address : getAddressBylatlng($request->lat,$request->long,$lang);
              $order->during_order_wait = intval($request->during_order_wait / 60);
              $order->save();
              //Start journey way from start to end
              $path = '';
              if($orderpoints = orderPath::where(['order_id'=>$order->id,'captain_id'=>$order->captain_id])->get()){
                foreach($orderpoints as $point) {
                   $path = $point->lat.','.$point->long.'|';
                }
                $path = rtrim($path,'|');
              }
              $distance        = GetPathAndDirections($order->start_lat,$order->start_long,$order->end_lat,$order->end_long,$path,$lang);
              $order->distance = $distance;              
              //End journey way from start to end
                      
              $start_journey_time = new DateTime( $order->start_journey_time );
              $now                = new DateTime( date('Y-m-d H:i:s') );
              $difference   = $start_journey_time->diff($now);
              $journey_time = $difference->format("%H:%I:%S");

              $order->period           = $journey_time;
              $order->end_journey_time = date('Y-m-d H:i:s');
              $total_journey_waits = intval($order->during_order_wait) + intval( $order->initial_wait ) ;//($order->service_in == 'mycity')? intval($order->during_order_wait) + intval( $order->initial_wait ) : 0;
              // if($order->order_type == 'food'){  
              //   $pricedata = ClientOrderController::expectedFoodDeliveryPrice($distance);
              // }else{
                $pricedata = ClientOrderController::expectedPrice($order->car_type_id,$distance,$order->start_lat,$order->start_long,$total_journey_waits);
              // }
              $price     = (isset($pricedata['price']))? $pricedata['price'] : 0;
              $order->price     = round($price,2);                 

              //******Start Apply coupon**//
              $couponData         = getUserCouponDiscount($order->user_id,$price);
              $coupon_discount    = $couponData['discount'];
              $client_have_coupon = $couponData['have_coupon']; 
              $priceAfterCoupon   = round( floatval($price) - $coupon_discount , 2);
              $order->have_coupon = $client_have_coupon;
              $vat   = ($price > 0 )? round( floatval( ($price * ( $added_value / 100 )) ),2) : '0';
              $priceAfterCouponVat = round( floatval($priceAfterCoupon + $vat + $wasl ), 2);
              $order->vat         = $vat;
              $order->wasl        = $wasl;
              //******End Apply coupon********//
              //start check if use user balance first or no 
              if($client = $order->user){
                  $client->num_done_orders        += 1;
                  $client->save();
                  if($client->use_balance_first == 'true'){
                      //check if client balance enough or no 
                      if(floatval($client->balance) >= $priceAfterCouponVat){
                         $client->balance -= $priceAfterCouponVat;
                         $client->save();
                         $order->paid_balance = $priceAfterCouponVat;
                         $required_price = 0;
                         savePayment($client->id,0,$priceAfterCouponVat,'subtract','order_price','finished',$client->current_country_id,'balance' );
                      }else{
                        if(floatval($client->balance) > 0){
                          $required_price      = $priceAfterCouponVat - floatval($client->balance);
                          $order->paid_balance = round(floatval($client->balance),2) ;
                          $client->balance     = 0;
                          $client->save();
                        }else{
                          $required_price = $priceAfterCouponVat;
                        }
                      }
                  }else{
                   $required_price = $priceAfterCouponVat;
                  }
              }

              //start check if use user balance first or no 
              $order->required_price  = $required_price;
              $order->coupon_discount = $coupon_discount;
              $rush_hour_percentage   = checkRushHour($order->start_lat,$order->start_long); 
              $rush_hour_percentage   = ($rush_hour_percentage == 0)? 0 : '1.'.$rush_hour_percentage.' x'; 
              $order->rush_hour_percentage = $rush_hour_percentage;
              $order->status          = 'finished';
              $order->save();
              
              // $user->balance_electronic_payment += (float) $order->paid_balance + (float) $order->coupon_discount;
              $user->have_order       = 'false';
              $user->save();
              //start save app percentage to profits
              $site_percentage    = setting('site_percentage');
              $app_percentage     = (floatval($order->price) * ( $site_percentage / 100) ) ;//+ floatval($vat) + floatval($wasl) ;
              $captain_percentage = round(floatval($order->price) - floatval($app_percentage) ,2) ;
              
              $profit              = new Profits();
              $profit->user_id     = $order->captain_id;
              $profit->total_price = $order->price;
              $profit->value       = round(floatval(convertCurrency($app_percentage,$order->currency_en,setting('site_currency_en')) ) , 2 );
              $profit->added_value = $vat;
              $profit->wasl_value  = $wasl;
              $profit->date        = date('Y-m-d');
              $profit->month       = date('Y-m');
              $profit->year        = date('Y');
              $profit->save(); 
              //end save app percentage to profits
              //start save captaine done order to history and captain done orders
              if($user->id == $order->captain_id){
                  if($usersOrdersHistory = usersOrdersHistory::where('captain_id','=',$user->id)->where('order_id','=',$order->id)->where('status','=','opened')->orwhere('captain_id','=',$user->id)->where('order_id','=',$order->id)->where('status','=','created')->first()){
                     $usersOrdersHistory->status      = 'finished';
                     $usersOrdersHistory->price       = $order->price;
                     $usersOrdersHistory->save();
                     $user->num_done_orders        += 1;
                     $user->save();
                  }
                //calculate captain done orders and ambassador new balance
                $creator_id = $user->userMeta->creator_id??0;
                if($ambassador = User::find($creator_id)){
                  //get captain num orders from creation to creation + ambassador_num_days
                  $creation_date = date('Y-m-d',strtotime($user->created));
                  $ambassador_end_date = date('Y-m-d',strtotime($user->created_at.' +'.setting('ambassador_num_days').'days'));
                  $captain_num_orders = Order::where('captain_id','=',$user->id)->where('created_at','>=',$creation_date)->where('created_at','<=',$ambassador_end_date)->count();
                  if($captain_num_orders == setting('ambassador_num_orders')){
                    $ambassador->balance = (float)$ambassador->balance + (float)setting('ambassador_balance');
                    $ambassador->save();
                  }
                }
              }     

            //***********start add cashBack ***********//
            if($order->payment_type == 'cash'){
              if($cashBack = CashBack::where('from_date','<=',date('Y-m-d'))->where('to_date','>=',date('Y-m-d'))->first()){
                if( (strtotime($cashBack->to_date.' '.$cashBack->to_time) >= strtotime('now') && (int)$cashBack->total_cost < (int)$cashBack->budget) ){
                  if($client = $order->user){
                    if(UsersCashBack::where(['user_id'=>$client->id,'cashback_id' => $cashBack->id])->count() < $cashBack->num_orders_one_user){
                    $UsersCashBack = new UsersCashBack();     
                    $UsersCashBack->cashback_id = $cashBack->id;
                    $UsersCashBack->user_id     = $client->id;
                    $UsersCashBack->save();
                      $cashback_amount = floatval($order->price) * (floatval( $cashBack->percentage ) / 100) ;
                      if ($cashback_amount > $cashBack->max_discount) {
                        $cashback_amount = $cashBack->max_discount;
                      }
                      $cashBack->total_cost += $cashback_amount;
                      $cashBack->save();

                      $client->balance += round($cashback_amount,2); 
                      $client->save();
                      savePayment(0,$client->id,$cashback_amount,'subtract','cashback','finished',$client->current_country_id,'balance');
                    }  
                  }
                }     
              }
            }
          //**********end add cashBack ***********// 

              //end save captaine done order to history              
              /*Start add Guarantees to Captain if achieve num orders  */  
              if($guarantee = Guarantees::where('to_date','>=',date('Y-m-d'))->first() ){
                if(strtotime($guarantee->to_date.' '.$guarantee->to_time) >= strtotime('now') ){
                  $captain_orders = Order::where('captain_id','=',$order->captain_id)->where('status','=','finished')->where('created_at','>=',$guarantee->from_date.' '.$guarantee->from_time)->where('created_at','<=',$guarantee->to_date.' '.$guarantee->to_time)->get();
                  if($captain_orders){
                    if(count($captain_orders) == intval($guarantee->num_orders) ){
                      $total_price = 0;
                      foreach($captain_orders as $captainOrder){
                        $total_price += floatval($captainOrder->price);
                      }
                      if(floatval($guarantee->guarantee) > floatval($total_price) ){
                        $GuaranteesHistory             = new GuaranteesHistory();
                        $GuaranteesHistory->user_id    = $order->captain_id;
                        $GuaranteesHistory->num_orders = $guarantee->num_orders;
                        $GuaranteesHistory->guarantee  = floatval($guarantee->guarantee) - floatval($total_price) ;
                        $GuaranteesHistory->date       = date('Y-m-d');
                        $GuaranteesHistory->month      = date('Y-m');
                        $GuaranteesHistory->save();
                       
                        $guarantee->num_used +=1;
                        $guarantee->save();

                        //start add Guarantees balance to captain balance
                        // $user->balance += round( floatval($guarantee->guarantee) - floatval($total_price),2); 
                        $user->balance_electronic_payment += round( floatval($guarantee->guarantee) - floatval($total_price),2); 
                        $user->save();
                        $amount = round( floatval($guarantee->guarantee) - floatval($total_price),2); 
                        savePayment(0,$user->id,$amount,'subtract','guarantee','finished',$user->current_country_id ,'electronic_balance');
                        //end add Guarantees balance to captain balance                        
                      }                    
                    }
                  }
                }
              }                        
              /*END add rewards to Captain if achieve num orders  */ 
              /*Start add rewards to Captain if achieve num orders  */  
              if($reward = Rewards::where('to_date','>=',date('Y-m-d'))->where('type','=','captain')->first() ){
                if(strtotime($reward->to_date.' '.$reward->to_time) >= strtotime('now') && $reward->num_used <= $reward->num_users){

                  if($user_captain_orders = Order::where('captain_id','=',$order->captain_id)->where('status','=','finished')->where('created_at','>=',$reward->from_date.' '.$reward->from_time)->where('created_at','<=',$reward->to_date.' '.$reward->to_time)->count()){
                    if($user_captain_orders == $reward->num_orders){
                      $rewardsHistory             = new rewardsHistory();
                      $rewardsHistory->user_id    = $order->captain_id;
                      $rewardsHistory->num_orders = $reward->num_orders;
                      $rewardsHistory->points     = $reward->points;
                      $rewardsHistory->date       = date('Y-m-d');
                      $rewardsHistory->month      = date('Y-m');
                      $rewardsHistory->save();
                    //start add Rewards balance to captain balance
                      // $user->balance += round( floatval($reward->points),2); 
                      $user->balance_electronic_payment += round( floatval($reward->points),2); 
                      $user->save();

                      $reward->num_used = (int)$reward->num_used + 1;
                      $reward->save();

                      $amount = round( floatval($reward->points),2);                      
                      savePayment(0,$user->id,$amount,'subtract','reward','finished',$user->current_country_id,'electronic_balance' );
                    //end add Rewards balance to captain balance                    
                    }
                  }

                }
              }                        
              /*END add rewards to Captain if achieve num orders  */  
              /*Start add rewards to client if achieve num orders  */  
              if($reward = Rewards::where('to_date','>=',date('Y-m-d'))->where('type','=','client')->first() ){
                if(strtotime($reward->to_date.' '.$reward->to_time) >= strtotime('now') && $reward->num_used < $reward->num_users ){
                  if($user_client_orders = Order::where('user_id','=',$order->user_id)->where('status','=','finished')->where('created_at','>=',$reward->from_date.' '.$reward->from_time)->where('created_at','<=',$reward->to_date.' '.$reward->to_time)->count()){
                    if($user_client_orders == $reward->num_orders){
                      if($client = $order->user){
                      //start add Rewards balance to client points balance
                        $client->points += $reward->points ;                    
                        $client->save();  

                        $reward->num_used = (int)$reward->num_used + 1;
                        $reward->save();
                      //end add Rewards balance to client points balance    
                      }
                      $rewardsHistory             = new rewardsHistory();
                      $rewardsHistory->user_id    = $order->user_id;
                      $rewardsHistory->num_orders = $reward->num_orders;
                      $rewardsHistory->points     = $reward->points;
                      $rewardsHistory->date       = date('Y-m-d');
                      $rewardsHistory->month      = date('Y-m');
                      $rewardsHistory->save();
                    }
                  }
                }
              }  
            
            
              /*END add rewards to client if achieve num orders  */  
                DB::table('notifications')->where('user_id','=',$order->user_id)
                                          ->where('notifier_id','=',$order->captain_id)
                                          ->where('data','like','%'.'order_id:'.$order->id.'%')
                                          ->orwhere('user_id','=',$order->captain_id)
                                          ->where('notifier_id','=',$order->user_id)
                                          ->where('data','like','%'.'order_id:'.$order->id.'%')
                                          ->delete();
              //**Start notify order owner with Captain finish order ******//
                  $devices         = userDevices::where(['user_id'=> $order->user_id])->get();
                // if($order->order_type == 'food'){  
                //   $notify_title_ar = 'وصول الطلب';
                //   $notify_title_en = 'Order Arrived';
                //   $message_ar      = 'قام '.$user->name.' بتأكيد وصول الطلب.';
                //   $message_en      = $user->name.' confirm order arrival';
                //   $notifyData      = ['title_ar' => $notify_title_ar,'title_en'=>$notify_title_en,'message_en'=>$message_en,'message_ar'=>$message_ar,'key'=>'finishSimpleOrder','order_id'=>$order->id,'order_status'=>$order->status,'type' => $order->type,'order_type' => $order->order_type,'payment_type' => $order->payment_type];
                //   sendNotification($devices, $message_ar,$notify_title_ar,$notifyData);
                //   notify($order->user_id,$user->id,'order.finishSimpleOrderTitle','order.finishSimpleOrder',"order_id:".$order->id,$order->status,'finishSimpleOrder');  
                // }else{
                  $notify_title_ar = 'أنتهى مشوارنا!👋';
                  $notify_title_en = 'Our journey is over!👋';
                  // $message_ar      = 'قام '.$user->name.' بتأكيد وصول الرحلة.';
                  // $message_en      = $user->name.' confirm trip arrival';
                  $message_ar = replacePlaceholders(setting('finishSimpleOrder_msg_ar'),['name' => $user->name]);
                  $message_en = replacePlaceholders(setting('finishSimpleOrder_msg_en'),['name' => $user->name]);

                  $notifyData      = ['title_ar' => $notify_title_ar,'title_en'=>$notify_title_en,'message_en'=>$message_en,'message_ar'=>$message_ar,'key'=>'finishSimpleOrder','order_id'=>$order->id,'order_status'=>$order->status,'type' => $order->type,'order_type' => $order->order_type,'payment_type' => $order->payment_type];
                  sendNotification($devices, $message_ar,$notify_title_ar,$notifyData);
                  notify($order->user_id,$user->id,'order.finishSimpleTripTitle','order.finishSimpleTrip',"order_id:".$order->id,$order->status,'finishSimpleOrder');                   
                // }
              //**end notify order owner with Captain finish order ******//
                  $required_price = $order->required_price;
                  
                  //start if payment type online .. deacrease the required price from user balance until user transaction success add amount to user balance
                  //this paramater to calculate total required price in that trip and previous trips to pay with online 
                  $total_required_price = $order->required_price;
                  if($order->payment_type != 'cash'){
                     //*Start calculate captain percentage and balance*//
                      $app_percentage     = (floatval($order->price) * ( $site_percentage / 100) ) ;//+ floatval($vat) + floatval($wasl) ;
                      $captain_percentage = round(floatval($order->price) - floatval($app_percentage) ,2) ;
                      


                      // no discount from balance in schedule 
                      // $user->balance      += round( floatval($captain_percentage) , 2);
                      $user->balance_electronic_payment      = round( floatval($user->balance_electronic_payment) + floatval($order->price) +floatval($vat) + floatval($wasl) - floatval($coupon_discount),2);//- floatval($app_percentage) - floatval($coupon_discount) ,2); //round( floatval($captain_percentage) , 2);
                      // $user->balance_electronic_payment      = round( floatval($user->balance_electronic_payment) + floatval($captain_percentage),2); //round( floatval($captain_percentage) , 2);
                      $user->balance      = round( floatval($user->balance) -  floatval($app_percentage) - floatval($vat) - floatval($wasl) , 2);
                      $user->save();

                      $balanceAmount = round( floatval($order->price) + floatval($vat) + floatval($wasl) - floatval($coupon_discount),2);//- floatval($app_percentage) - floatval($coupon_discount) ,2);
                      savePayment(0,$user->id,number_format($balanceAmount,2),'add','order_price','finished',$user->current_country_id,'electronic_balance' );
                      
                      savePayment(0,$user->id,number_format($app_percentage,2),'add','trip_percentage','finished',$user->current_country_id,'balance' );
                      savePayment(0,$user->id,number_format($vat,2),'add','trip_vat','finished',$user->current_country_id,'balance' );
                      savePayment(0,$user->id,number_format($wasl,2),'add','trip_wasl','finished',$user->current_country_id,'balance' );


                      if($client = $order->user){
                        if($client->balance < 0){
                          //if pay with online
                          $total_required_price = floatval($total_required_price) + abs(round(floatval($client->balance),2));
                        }
                        $client->balance = floatval($client->balance) - round(floatval($required_price),2);
                        $client->save();
                      }
                     //*End calculate captain percentage and balance*//
                     $order->app_percentage = $app_percentage;
                     $order->captain_percentage = $captain_percentage;
                     $order->confirm_payment = 'true';
                     $order->paid_online       = $total_required_price;
                     $order->save();

                     //delete all order notifications
                      DB::table('notifications')->where('user_id','=',$order->user_id)
                                                ->where('notifier_id','=',$order->captain_id)
                                                ->where('data','like','%'.'order_id:'.$order->id.'%')
                                                ->orwhere('user_id','=',$order->captain_id)
                                                ->where('notifier_id','=',$order->user_id)
                                                ->where('data','like','%'.'order_id:'.$order->id.'%')
                                                ->delete();
                  }
                  //end if payment type online .. deacrease the required price from user balance until user transaction success add amount to user balance

                    $start_lat     = $order->start_lat;
                    $start_long    = $order->start_long;
                    $start_address = $order->start_address;
                    $end_lat       = $order->end_lat;
                    $end_long      = $order->end_long;
                    $end_address   = $order->end_address;

                  $name   = '';
                  $avatar = url('img/user/default.png');                  
                  if($client = $order->user){
                    $name          = $client->name;
                    $avatar        = ($client->avatar)? url('img/user/'.$client->avatar):url('img/user/default.png');
                    if(($client->balance < 0 ) && ($order->payment_type == 'cash') ){
                      $required_price .= ' + '.trans('user.previousDebts').' '.abs(round(floatval($client->balance),2));
                    }                    
                  }
                  $required_price .= ' '.$order->{"currency_$lang"};
                  $data = [ 'order_id'      => $order->id,
                            'order_type'    => ($order->order_type)??'trip',
                            'order_src'     => ($order->order_src)??'internal',
                            'package_type'  => ($order->package_type)??'food',
                            'place_id'      => ($order->place_id)??'',
                            'user_name'     => ($order->user_name)??'',
                            'user_phone'    => ($order->user_phone)??'',
                            'place_id'      => ($order->place_id)??'',
                            'place_ref'     => ($order->place_ref)??'',
                            'place_name'    => ($order->place_name)??'',
                            'icon'          => url('img/icons/restaurant.png'),                  
                            'client_id'     => ($order->user_id)??0,
                            'distance'      => $distance.' '.trans('order.km'),
                            'period'        => ($order->period)?? '',
                            'price'         => $order->price.' '.$order->{"currency_$lang"},
                            'required_price'=> $required_price,
                            'total_required_price'=> number_format((float)$total_required_price, 2, '.', ''),
                            'vat'           => (string)$order->vat.' '.$order->{"currency_$lang"},
                            'wasl'          => (string)$order->wasl.' '.$order->{"currency_$lang"},
                            'payment_type'  => $order->payment_type,
                            'name'          => ($name)??'',
                            'avatar'        => ($avatar)??url('img/user/default.png'),
                            'start_lat'     => "$start_lat",
                            'start_long'    => "$start_long",
                            'start_address' => ($start_address)?? '',
                            'end_lat'       => "$end_lat",
                            'end_long'      => "$end_long",
                            'end_address'   => ($end_address)?? '',
                            'app_percentage' => "$app_percentage".' '.$order->{"currency_$lang"},
                            'captain_percentage' => "$captain_percentage".' '.$order->{"currency_$lang"}
                          ];
              return response()->json(successReturn($data));
            }
            $msg = trans('order.order_notavailable');
            return response()->json(failActionReturn($msg));          
        }else{
            $msg  = implode(' , ',$validator->errors()->all());
            return response()->json(failReturn($msg));
        }
    }    

    public function CaptainSimpleOrderPriceDetails(Request $request){
            $validator     = Validator::make($request->all(),[
            'order_id'     => 'required'
        ]);
        if($validator->passes()){
            $user = JWTAuth::parseToken()->authenticate();
            $lang = ($request->header('lang'))?? 'ar';
            if($order = Order::where(['id'=>$request->order_id])->first()){
              if($order->status == 'closed'){
                $msg = trans('order.order_closed');
                return response()->json(failReturn($msg)); 
              }elseif(($order->status == 'inprogress') && ($order->captain_id != $user->id) && ($user->captain == 'true')){
                $msg = trans('order.order_received');
                return response()->json(failReturn($msg)); 
              }elseif($order->status == 'finished') {
                $msg = trans('order.order_finished');
                return response()->json(failReturn($msg)); 
              }   
                  $required_price = $order->required_price;
                  $total_required_price = $order->required_price;
                  if($client = $order->user){
                    $name          = $client->name;
                    $avatar        = ($client->avatar)? url('img/user/'.$client->avatar):url('img/user/default.png');
                    $start_lat     = $order->start_lat;
                    $start_long    = $order->start_long;
                    $start_address = $order->start_address;
                    $end_lat       = $order->end_lat;
                    $end_long      = $order->end_long;
                    $end_address   = $order->end_address;                    
                    if($client->balance < 0){
                      $required_price .= ' + '.trans('user.previousDebts').' '.abs(round(floatval($client->balance),2));
                      //if pay with online
                      $total_required_price = floatval($total_required_price) + abs(round(floatval($client->balance),2));
                    }                                           
                  }
                  $required_price .= ' '.$order->{"currency_$lang"};
                  $data = [ 'order_id'      => $order->id,
                            'order_type'    => ($order->order_type)??'trip',
                            'order_src'     => ($order->order_src)??'internal',
                            'package_type'  => ($order->package_type)??'food',
                            'place_id'      => ($order->place_id)??'',
                            'user_name'     => ($order->user_name)??'',
                            'user_phone'    => ($order->user_phone)??'',
                            'place_id'      => ($order->place_id)??'',
                            'user_name'     => ($order->user_name)??'',
                            'user_phone'    => ($order->user_phone)??'',
                            'place_id'      => ($order->place_id)??'',
                            'place_ref'     => ($order->place_ref)??'',
                            'place_name'    => ($order->place_name)??'',
                            'icon'          => url('img/icons/restaurant.png'),                     
                            'client_id'     => ($order->user_id)??0,
                            'distance'      => $order->distance.' '.trans('order.km'),
                            'period'        => ($order->period)?? '',
                            'price'         => $order->price.' '.$order->{"currency_$lang"},
                            'required_price'=> $required_price,
                            'total_required_price'=> number_format((float)$total_required_price, 2, '.', ''),
                            'vat'           => (string)$order->vat,
                            'wasl'          => (string)$order->wasl,
                            'payment_type'  => $order->payment_type,
                            'name'          => ($name)??'',
                            'avatar'        => ($avatar)??url('img/user/default.png'),
                            'start_lat'     => "$start_lat",
                            'start_long'    => "$start_long",
                            'start_address' => ($start_address)??'',
                            'end_lat'       => "$end_lat",
                            'end_long'      => "$end_long",
                            'end_address'   => ($end_address)?? '',
                          ];             
              return response()->json(successReturn($data));
            }
            $msg = trans('order.order_notavailable');
            return response()->json(failActionReturn($msg));          
        }else{
            $msg  = implode(' , ',$validator->errors()->all());
            return response()->json(failReturn($msg));
        }
    }    

    public function CaptainConfirmFinishSimpleOrder(Request $request){ 
            $validator     = Validator::make($request->all(),[
            'order_id'     => 'required|integer',
            'amount'       => 'required',
        ]);
        if($validator->passes()){
            $user = JWTAuth::parseToken()->authenticate();
            $lang = ($request->header('lang'))?? 'ar';
            if($order = Order::where(['id'=>$request->order_id])->first()){  
              if($order->status == 'closed'){
                $msg = trans('order.order_closed');
                return response()->json(failReturn($msg)); 
              }elseif(($order->status == 'inprogress') && ($order->captain_id != $user->id) && ($user->captain == 'true')){
                $msg = trans('order.order_received');
                return response()->json(failReturn($msg)); 
              }   
              $paid_amount = convert2english($request->amount);
                //**Start check if amount > required add still amount to balance**/
              if($orderClient = $order->user){
                if(floatval($paid_amount) > floatval($order->required_price) ){
                    $stillamount = round(floatval($paid_amount) - floatval($order->required_price) ,2);
                    if($stillamount > setting('max_tips')){
                      $msg = trans('order.notallowed_max_tips');
                      return response()->json(failReturn($msg));
                    }
                    $orderClient->balance += round(floatval($stillamount) ,2 );
                    $order->added_balance  = $added_balance = round( floatval($stillamount) ,2 );
                    $orderClient->save();
                    
                    savePayment($orderClient->id,$orderClient->id,$added_balance,'add','order_price','finished',$orderClient->current_country_id,'balance');
                    
                    $added_balance .= ' '.$order->{"currency_$lang"};
                    //send notifications to client with added balance
                      notify($orderClient->user_id,$user->id,'user.addedBalanceTitle','user.addedBalance',"order_id:".$order->id.":amount:".$added_balance,'','addedBalance');         
                      $devices = userDevices::where(['user_id' => $orderClient->user_id])->get();
                      // #use FCM or One Signal Here :) 
                      $notify_title   = trans('order.addedBalanceTitle');
                      $notify_message = trans('order.addedBalance',['amount' => $added_balance]);
                      // $message_ar     = 'تم اضافة '.$added_balance.' الي رصيدك.';
                      // $message_en     = 'Added '.$added_balance.' to your balance.';

                      $message_ar = replacePlaceholders(setting('addedBalance_msg_ar'),['amount' => $added_balance]);
                      $message_en = replacePlaceholders(setting('addedBalance_msg_en'),['amount' => $added_balance]);
                      
                      $data = ['title' => $notify_title,'message_en'=>$message_en,'message_ar'=>$message_ar,'order_id'=>$order->id,'amount' => $added_balance,'key'=>'addedBalance','type' => $order->type,'order_type' => $order->order_type];
                      sendNotification($devices,$notify_message,$notify_title,$data);                        
                }elseif(floatval($paid_amount) < floatval($order->required_price)){
                      if( floatval($orderClient->balance) -  (floatval($order->required_price) - floatval($paid_amount) ) >= 0 ){
                         $orderClient->balance -= round( floatval($order->required_price) - floatval($paid_amount) , 2);
                         $orderClient->save();
                         $balanceAmount = round( floatval(floatval($order->required_price) - floatval($paid_amount)) , 2);
                         savePayment($orderClient->id,0,$balanceAmount,'subtract','order_price','finished',$orderClient->current_country_id ,'balance');

                         $order->paid_balance += round( floatval($order->required_price) - floatval($paid_amount),2 );
                      }else{
                         if((setting('allow_debt_client') == 'true') ){
                            if( floatval(setting('max_debt_client')) >= abs( floatval($orderClient->balance) -  (floatval($order->required_price) - floatval($paid_amount) ) )){
                               $orderClient->balance -= round(floatval( floatval($order->required_price) - floatval($paid_amount) ) , 2);
                               $orderClient->save();
                              
                               $balanceAmount = round(floatval( floatval($order->required_price) - floatval($paid_amount) ) , 2);
                               savePayment($orderClient->id,0,$balanceAmount,'subtract','order_price','finished',$orderClient->current_country_id ,'balance');
                              
                               $order->paid_balance += round( floatval(floatval($order->required_price) - floatval($paid_amount)),2 );
                            }else{
                               $msg = trans('order.skipmaxdebt');
                               return response()->json(failReturn($msg));
                            }
                         }else{
                           $msg = trans('order.debtnotallowed');
                           return response()->json(failReturn($msg));
                         }
                      }
                }
              }
                //**End check if amount > required add still amount to balance**//

               //*Start calculate captain percentage and balance*//
                $site_percentage    = setting('site_percentage');
                $app_percentage     = (floatval($order->price) * ( $site_percentage / 100) );// + floatval($order->vat) + floatval($order->wasl) ;
                $captain_percentage = round(floatval($order->price) - floatval($app_percentage) ,2) ;

                
                // no discount from balance in schedule 
                $captain_paid_balance = round( floatval($order->price) + floatval($order->vat) + floatval($order->wasl) - floatval($order->coupon_discount) - floatval($paid_amount),2);//floatval($app_percentage) - floatval($order->coupon_discount) - floatval($paid_amount) , 2);
                if($captain_paid_balance > 0){
                  $user->balance_electronic_payment   = round( floatval($user->balance_electronic_payment) + floatval($captain_paid_balance) , 2);
                  savePayment(0,$user->id,$captain_paid_balance,'subtract','order_price','finished',$user->current_country_id,'electronic_balance' );
                }
              
                $user->balance      = round( floatval($user->balance) - floatval($app_percentage) - floatval($order->vat) - floatval($order->wasl), 2);
                $user->save();

                // $balanceAmount = round( floatval($app_percentage) + floatval($order->vat) + floatval($order->wasl) , 2);
                savePayment(0,$user->id,number_format($app_percentage,2),'add','trip_percentage','finished',$user->current_country_id,'balance' );
                savePayment(0,$user->id,number_format($order->vat,2),'add','trip_vat','finished',$user->current_country_id,'balance' );
                savePayment(0,$user->id,number_format($order->wasl,2),'add','trip_wasl','finished',$user->current_country_id,'balance' );

                      //               // no discount from balance in schedule 
                      // // $user->balance      += round( floatval($captain_percentage) , 2);
                      // $user->balance_electronic_payment      = round( floatval($user->balance_electronic_payment) + floatval($order->price) + floatval($vat) + floatval($wasl) - floatval($coupon_discount) ,2); //round( floatval($captain_percentage) , 2);
                      // // $user->balance_electronic_payment      = round( floatval($user->balance_electronic_payment) + floatval($captain_percentage),2); //round( floatval($captain_percentage) , 2);
                      // $user->balance      = round( floatval($user->balance) -  floatval($app_percentage) - floatval($vat) - floatval($wasl) , 2);
                      // $user->save();
                      
                      // $balanceAmount = round( floatval($order->price) + floatval($vat) + floatval($wasl) - floatval($coupon_discount) ,2);
                      // savePayment(0,$user->id,$balanceAmount,'add','order_price','finished',$user->current_country_id,'electronic_balance' );
                      

               //*End calculate captain percentage and balance*//
               $order->app_percentage = $app_percentage;
               $order->captain_percentage = $captain_percentage;
               $order->confirm_payment = 'true';
               $order->total_payments  = round(floatval($paid_amount),2);
               $order->paid_cash       = round(floatval($paid_amount),2);
               $order->save();
               //delete all order notifications
                DB::table('notifications')->where('user_id','=',$order->user_id)
                                          ->where('notifier_id','=',$order->captain_id)
                                          ->where('data','like','%'.'order_id:'.$order->id.'%')
                                          ->orwhere('user_id','=',$order->captain_id)
                                          ->where('notifier_id','=',$order->user_id)
                                          ->where('data','like','%'.'order_id:'.$order->id.'%')
                                          ->delete();

              //**Start notify order owner with Captain finish order ******//
                  $devices         = userDevices::where(['user_id'=> $order->user_id])->get();
                  $notify_title_ar = 'تم الدفع بنجاح!💰';
                  $notify_title_en = 'Payment completed successfully!💰';
                  // $message_ar      = 'قام '.$user->name.' بتأكيد عملية الدفع.';
                  // $message_en      = $user->name.' confirm payment';
                  $message_ar = replacePlaceholders(setting('ConfirmfinishSimpleOrder_msg_ar'),['amount' => $order->price]);
                  $message_en = replacePlaceholders(setting('ConfirmfinishSimpleOrder_msg_en'),['amount' => $order->price]);
                  
                  $notifyData      = ['title_ar' => $notify_title_ar,'title_en'=>$notify_title_en,'message_en'=>$message_en,'message_ar'=>$message_ar,'key'=>'ConfirmfinishSimpleOrder','order_id'=>$order->id,'amount'=>$order->paid_cash.' '.$order->{"currency_$lang"},'order_status'=>$order->status,'type' => $order->type,'order_type' => $order->order_type,'payment_type' => $order->payment_type];
                  sendNotification($devices, $message_ar,$notify_title_ar,$notifyData);
                  notify($order->user_id,$user->id,'order.ConfirmfinishSimpleOrderTitle','order.ConfirmfinishSimpleOrder',"order_id:".$order->id,$order->status,'ConfirmfinishSimpleOrder'); 
              //**end notify order owner with Captain finish order ******//

              // try {
              //   $currency     = $order->{"currency_$lang"};
              //   $paid_cash    = $order->paid_cash." ".$currency;
              //   $tripprice    = $order->price." ".$currency;
              //   $discount     = $order->coupon_discount." ".$currency;
              //   $requiredcash = $order->required_price." ".$currency;
              //   if($orderClient){
              //     Mail::to($orderClient->email)->send(new invoiceEmailMessage($paid_cash,$tripprice,$discount,$requiredcash));                 
              //   }
              //  }catch (Exception $e) {
              //    //$e->getMessage();
              //  } 
              $msg = trans('order.confirmFinishSuccess');
              return response()->json(successReturnMsg($msg));
            }
            $msg = trans('order.order_notavailable');
            return response()->json(failActionReturn($msg)); 
        }else{
            $msg  = implode(' , ',$validator->errors()->all());
            return response()->json(failReturn($msg));
        }
    }

    public function CaptainFinishedOrderDetails(Request $request){
        $validator        = Validator::make($request->all(),[
            'order_id'    => 'required|integer'
        ]);
        if($validator->passes()){
          $data = [];
          $user = JWTAuth::parseToken()->authenticate();
          $lang = ($request->header('lang'))?? 'ar';
          if($order = Order::where(['id' => $request->order_id,'captain_id'=>$user->id,'status'=>'finished'])->first()){
              if($car = $order->car){
                $car_brand  = $car->brand.' ('.$car->model.')';
                $car_number = $car->car_number;
              }   
            $wait_price = 0 ; $open_counter = 0; $moving_price = 0;
            if($priceplan = Prices::find($order->price_id)){
                $wait_price   = intval($order->initial_wait + $order->during_order_wait) * $priceplan->waiting_minute;
                $open_counter = $priceplan->counter; 
                $moving_price = ($priceplan->km_price * intval($order->distance) );
                $moving_price = ($moving_price < $priceplan->min_price)? floatval($priceplan->min_price - $open_counter): $moving_price;
                $moving_price = ($moving_price < 0)? 0 : $moving_price;
            }
            $app_percentage     = round(floatval( floatval($order->price) * (floatval( (setting('site_percentage')) ) / 100) ) ,2);
            $captain_percentage = round(floatval( floatval($order->price) - floatval($app_percentage) ) ,2) ;
            $data = ['id'                   => $order->id,
                     'order_type'           => ($order->order_type)??'trip',
                     'order_src'            => ($order->order_src)??'internal',
                     'package_type'         => ($order->package_type)??'food',
                     'place_id'             => ($order->place_id)??'',
                     'user_name'            => ($order->user_name)??'',
                     'user_phone'           => ($order->user_phone)??'',
                     'place_id'             => ($order->place_id)??'',
                     'place_ref'            => ($order->place_ref)??'',
                     'place_name'           => ($order->place_name)??'',
                     'icon'                 => url('img/icons/restaurant.png'),                
                     'date'                 => date('Y-m-d',strtotime($order->created_at)),
                     'start_lat'            => "$order->start_lat",
                     'start_long'           => "$order->start_long",
                     'start_address'        => ($order->start_address)??'',
                     'end_lat'              => "$order->end_lat",
                     'end_long'             => "$order->end_long",
                     'end_address'          => ($order->end_address)??'',
                     'payment_type'         => ($order->payment_type)??'',
                     'car'                  => ($car_brand)??'',
                     'car_number'           => ($car_number)??'',
                     'distance'             => round(floatval($order->distance) , 2).' '.trans('order.km'),
                     'period'               => ($order->period)??'',
                     'initial_wait'         => intval($order->initial_wait),
                     'during_order_wait'    => intval($order->during_order_wait),
                     'wait_price'           => round( floatval($wait_price) , 2),
                     'open_counter'         => round( floatval($open_counter) , 2),
                     'moving_price'         => round( floatval($moving_price) , 2),
                     'paid_balance'         => round( floatval($order->paid_balance) ,2),
                     'paid_cash'            => round( floatval($order->paid_cash) ,2),
                     'paid_visa'            => round( floatval($order->paid_online) ,2),
                     'coupon_discount'      => round( floatval($order->coupon_discount) ,2),
                     'added_balance'        => round( floatval($order->added_balance) ,2),
                     'rush_hour_percentage' => ($order->rush_hour_percentage)??'0',
                     'total_payments'       => round( floatval($order->total_payments) , 2),
                     'price'                => round( floatval($captain_percentage) , 2), // captain profit
                     'vat'                  => (string)$order->vat,
                     'wasl'                 => (string)$order->wasl,
                     'app_percentage'       => round( floatval($app_percentage) ,2),// app profit
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


}
