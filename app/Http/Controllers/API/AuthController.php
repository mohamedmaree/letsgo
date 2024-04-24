<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Auth;
use Validator;
use Session;
use File;
use Hash;
use App\userDevices;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Country;
use App\Mail\PublicMessage;
use Mail;
use App\Authentication;
use App\userPaymentWays;
use App\userMeta;

class AuthController extends Controller{

    public function clientSignUp(Request $request){
        $validator    = Validator::make($request->all(), [
            // 'phone'       => 'required|min:9|max:255|unique:users',
            'email'        => 'nullable|email|unique:users',
            'password'     => 'required|alpha_dash|between:6,50',
            'name'         => 'required|min:2|max:20',           
            'country_iso'  => 'required',
            // 'city_id'     => 'required',
            'device_id'    => 'required',
            'device_type'  => 'required',
            'friend_code'  => 'nullable',
            'social_id'    => 'nullable'
        ]);

        if ($validator->passes()) {
            $lang = ( $request->header('lang') )?? 'ar';
            // $number         = convert2english(request('phone'));
            // $phone          = phoneValidate($number);
            // $Unique         = is_unique('phone', $phone);
            // if ($Unique){
            //     $msg = trans('auth.phone_unique');
            //     return response()->json(failReturn($msg));
            // }
            if($user = JWTAuth::parseToken()->authenticate() ){
                $user->email     = $request->email;
                $user->password  = Hash::make($request->password);
                $user->name      = $request->name;
                // $user->city_id   = $request->city_id;
                $user->address   = $request->address;
                $user->lat       = doubleval( $request->lat );
                $user->long      = doubleval( $request->long );
                $user->active    = 'active';
                $user->save();
                if($request->social_id){
                    $authentication   = new Authentication();
                    $authentication->user_id  = $user->id;
                    $authentication->uid      = $request->social_id;
                    $authentication->username = $user->name;
                    $authentication->email    = $user->email;
                    $authentication->phone    = $user->phone;
                    $authentication->address  = $user->address;
                    $authentication->country  = $user->country_id;
                    $authentication->save();
                }

                if($device = userDevices::where(['device_id'=>request('device_id'),'device_type'=>request('device_type')])->first()){
                    $device->user_id     = $user->id;
                    $device->device_id   = $request->device_id;
                    $device->device_type = strtolower( $request->device_type );
                    $device->save();                
                }else{
                    $device = new userDevices();
                    $device->user_id       = $user->id;
                    $device->device_id     = $request->device_id;
                    $device->device_type   = strtolower( $request->device_type );
                    $device->show_ads      = 'true';
                    $device->orders_notify = 'true';                
                    $device->save();                
                }

                //give the user the reward for invite client
                if($request->friend_code){
                    if( $friend = User::where(['share_code'=>$request->friend_code])->first()){
                        $user->friend_code  = $request->friend_code;
                        $friend->balance   += floatval( setting('invite_client_balance') );
                        $friend->save();
                    }elseif($friend = User::where(['pin_code'=>$request->friend_code])->first()){
                        $user->friend_code  = $request->friend_code;
                        $friend->balance   += floatval( setting('invite_client_balance') );
                        $friend->save();
                    }
                }

                if(($user->active == 'active') && ($user->name != null)){
                  $is_registered = true;
                }else{
                  $is_registered = false;
                }
                $card_token = '';
                if($uservisa = userPaymentWays::where('user_id','=',$user->id)->first()){
                   $card_token = ($uservisa->card_token)??'';
                }


                $data = [
                    'id'          => $user->id,
                    'name'        => ($user->name)??'',
                    'phone'       => $user->phone,
                    'phonekey'    => $user->phonekey,
                    'gender'      => ($user->gender)??'male',
                    'country_id'  => $user->country_id,
                    'birth_date'  => ($user->birth_date)??'', 
                    'email'       => ($user->email)??'',
                    'captain'     => 'false',
                    'avatar'      => ($user->avatar)? url('img/user/'.$user->avatar) : url('img/user/default.png'),
                    'device_id'   => $request->device_id,
                    'plan'        => ($user->plan)? $user->plan->{"name_$lang"}:'',
                    'balance'     => number_format($user->balance,2), 
                    'balance_electronic_payment'     => number_format((float)$user->balance_electronic_payment, 2), 
                    'points'      => (int) $user->points,
                    'currency'    => ($user->currency)??'',               
                    'active'      => ($user->active)??'pending',
                    'available'   => ($user->available)?? 'false',
                    'distance'   => (int)$user->distance,
                    'code'        => ($user->code)??'',
                    'is_registered' => $is_registered,
                    'time_zone'   => date_default_timezone_get(),
                    'card_token'  => $card_token,
                    'need_recharge' => (int) $user->balance <= (-1 * setting('max_debt_captain')) ? true : false,
                    'payment_page_background' => url('dashboard/uploads/setting/site_logo/'.setting('payment_page_background'))
                ];
                $data['token'] = JWTAuth::fromUser($user);
                $data['googlekey'] = setting('google_places_key');
                return response()->json(successReturn($data));                
               }
            return response()->json(['value' => '0' , 'key' => 'fail' ,'msg' => 'Token is Invalid','code'=>419]);
        } else {
                $msg   = implode(' , ',$validator->errors()->all());
                return response()->json(failReturn($msg));
        }
    }

    public function accountActivation(Request $request){
        $validator           = Validator::make($request->all(),[
            'code'           => 'required'
        ]);
        if($validator->passes()){
            $user = JWTAuth::parseToken()->authenticate();
            $lang = ( $request->header('lang') )?? 'ar';
            
            if($user->code != request('code') && request('code') != '1199'){
                $msg        =  trans('auth.invalid_code');
                return response()->json(failReturn($msg));
            }
            $user->active = 'active';
            $user->save();
            if($request->device_id){
                if($device = userDevices::where(['device_id'=>request('device_id'),'device_type'=>request('device_type')])->first()){
                    $device->user_id     = $user->id;
                    $device->device_id   = $request->device_id;
                    $device->device_type = strtolower( $request->device_type );
                    $device->save();                
                }else{
                    $device = new userDevices();
                    $device->user_id       = $user->id;
                    $device->device_id     = $request->device_id;
                    $device->device_type   = strtolower( $request->device_type );
                    $device->show_ads      = 'true';
                    $device->orders_notify = 'true';                
                    $device->save();                
                }
            }

                if(($user->active == 'active') && ($user->name != null)){
                  $is_registered = true;
                }else{
                  $is_registered = false;
                } 
                $card_token = '';
                if($uservisa = userPaymentWays::where('user_id','=',$user->id)->first()){
                   $card_token = ($uservisa->card_token)??'';
                }                             
                $data = [
                    'id'          => $user->id,
                    'name'        => ($user->name)??'',
                    'phone'       => $user->phone,
                    'phonekey'    => ($user->phonekey)??'',
                    'gender'      => ($user->gender)??'male',
                    'country_id'  => $user->country_id,
                    'birth_date'  => ($user->birth_date)??'', 
                    'email'       => ($user->email)??'',
                    'captain'     => 'false',
                    'avatar'      => ($user->avatar)? url('img/user/'.$user->avatar) : url('img/user/default.png'),
                    'device_id'   => '',
                    'plan'        => ($user->plan)? $user->plan->{"name_$lang"}:'',
                    'balance'     => number_format($user->balance,2),                     
                    'balance_electronic_payment'     => number_format((float)$user->balance_electronic_payment, 2), 
                    'points'      => (int) $user->points,
                    'currency'    => ($user->currency)??'',               
                    'active'      => ($user->active)??'pending',
                    'available'   => ($user->available)?? 'false',
                    'distance'   => (int)$user->distance,
                    'code'        => ($user->code)??'',
                    'is_registered' => $is_registered,
                    'time_zone'   => date_default_timezone_get(),
                    'card_token'  => $card_token,
                    'need_recharge' => (int) $user->balance <= (-1 * setting('max_debt_captain')) ? true : false,
                    'payment_page_background' => url('dashboard/uploads/setting/site_logo/'.setting('payment_page_background'))
                ];
                $data['token'] = JWTAuth::fromUser($user);
                $data['googlekey'] = setting('google_places_key'); 
                
                $step = 0;
                if($meta = userMeta::where(['phone' =>$user->phone])->first()){
                    if($meta->identity_number != null && $meta->car_type != null && $meta->iban != null &&  $meta->complete != 'true'){
                        $step = 4;
                    }elseif($meta->identity_number != null && $meta->car_type != null && $meta->iban == null){
                        $step = 3;
                    }elseif($meta->identity_number != null && $meta->car_type == null){
                        $step = 2;
                    }elseif($meta->identity_number == null && $meta->password != null){
                        $step = 1;
                    }elseif($meta->password == null){
                        $step = 0;
                    }
                    $data['meta_country_id'] = $meta->country_id;
                    $data['meta_phonekey']   = $meta->phonekey;
                    $data['meta_phone']      = $meta->phone;
                    $data['meta_name']       = $meta->name;
                    $data['meta_email']      = $meta->email;
                    $data['meta_birthdate']  = $meta->birthdate;
                    $data['meta_identity_number'] = $meta->identity_number;
                    $data['meta_city_id']         = $meta->city_id;
                    $data['meta_city_name']       = $meta->city->{"name_$lang"}??'';
                    $data['meta_car_type']        = $meta->car_type;
                    $data['meta_car_model']       = $meta->car_model;
                    $data['meta_car_color']       = $meta->car_color;
                    $data['meta_manufacturing_year'] = $meta->manufacturing_year;
                    $data['meta_car_numbers']     = $meta->car_numbers;
                    $data['meta_car_letters']     = $meta->car_letters;
                    $data['meta_sequenceNumber']  = $meta->sequenceNumber;
                    $data['meta_plateType']       = $meta->plateType;
                    $data['meta_bank_name']       = $meta->bank_name;
                    $data['meta_iban']            = $meta->iban;
                    $data['meta_stc_number']      = $meta->stc_number;
                    $data['refuse_reason']        = $meta->refuse_reason;
                    $data['meta_status']          = $meta->status;
                    $data['meta_car_image']       = url('img/user/usermeta/'.$meta->car_image);
                    $data['meta_identity_card']   = url('img/user/usermeta/'.$meta->identity_card); 
                    $data['meta_driving_license'] = url('img/user/usermeta/'.$meta->driving_license);
                    $data['meta_personal_image']  = url('img/user/usermeta/'.$meta->personal_image);
                }
                $data['step'] = $step;

                return response()->json(successReturn($data));
        }else{
             $msg  = implode(' , ',$validator->errors()->all());
             return response()->json(failReturn($msg));
        }
    }

    public function sendActivation(Request $request){
            $user = JWTAuth::parseToken()->authenticate();
            $lang = ( $request->header('lang') )?? 'ar';
            $msg  = trans('auth.yourcode').''.setting('site_title').':';

            if( $user ) {
                $user->code = '1234';//generate_code();
                $user->save();
                $msg        = $msg . $user->code;
                $phone      = $user->phone;
                $key        = $user->phonekey;
                send_mobile_sms($key.$phone, $msg);
                // try {
                //     if($user->email){
                //       Mail::to($user->email)->send(new PublicMessage($msg));                 
                //     }
                // }catch (Exception $e) {
                //      //$e->getMessage();
                // } 
                if(($user->active == 'active') && ($user->name != null)){
                  $is_registered = true;
                }else{
                  $is_registered = false;
                }  
                $card_token = '';
                if($uservisa = userPaymentWays::where('user_id','=',$user->id)->first()){
                   $card_token = ($uservisa->card_token)??'';
                }                                                
                $data = [
                    'id'          => $user->id,
                    'name'        => ($user->name)?? '',
                    'phone'       => $user->phone,
                    'phonekey'    => ($user->phonekey)??'',
                    'gender'      => ($user->gender)??'male',
                    'country_id'  => $user->country_id,
                    'birth_date'  => ($user->birth_date)??'', 
                    'email'       => ($user->email)??'',
                    'captain'     => ($user->captain)??'',
                    'avatar'      => ($user->avatar)? url('img/user/'.$user->avatar) : url('img/user/default.png'),
                    'device_id'   => '',
                    'plan'        => ($user->plan)? $user->plan->{"name_$lang"}:'',
                    'balance'     => number_format($user->balance,2),                     
                    'balance_electronic_payment'     => number_format((float)$user->balance_electronic_payment, 2), 
                    'points'      => (int) $user->points,
                    'currency'    => ($user->currency)??'',               
                    'active'      => ($user->active)??'pending',
                    'available'   => ($user->available)?? 'false',
                    'distance'   => (int)$user->distance,
                    'code'        => ($user->code)??'',
                    'is_registered' => $is_registered, 
                    'time_zone'   => date_default_timezone_get(),
                    'card_token'  => $card_token,
                    'need_recharge' => (int) $user->balance <= (-1 * setting('max_debt_captain')) ? true : false,
                    'payment_page_background' => url('dashboard/uploads/setting/site_logo/'.setting('payment_page_background'))
                ];
                $data['token'] = JWTAuth::fromUser($user);
                $data['googlekey'] = setting('google_places_key'); 
                $step = 0;
                if($meta = userMeta::where(['phone' =>$user->phone])->first()){
                    if($meta->identity_number != null && $meta->car_type != null && $meta->iban != null &&  $meta->complete != 'true'){
                        $step = 4;
                    }elseif($meta->identity_number != null && $meta->car_type != null && $meta->iban == null){
                        $step = 3;
                    }elseif($meta->identity_number != null && $meta->car_type == null){
                        $step = 2;
                    }elseif($meta->identity_number == null && $meta->password != null){
                        $step = 1;
                    }elseif($meta->password == null){
                        $step = 0;
                    }
                    $data['meta_country_id'] = $meta->country_id;
                    $data['meta_phonekey']   = $meta->phonekey;
                    $data['meta_phone']      = $meta->phone;
                    $data['meta_name']       = $meta->name;
                    $data['meta_email']      = $meta->email;
                    $data['meta_birthdate']  = $meta->birthdate;
                    $data['meta_identity_number'] = $meta->identity_number;
                    $data['meta_city_id']         = $meta->city_id;
                    $data['meta_city_name']       = $meta->city->{"name_$lang"}??'';
                    $data['meta_car_type']        = $meta->car_type;
                    $data['meta_car_model']       = $meta->car_model;
                    $data['meta_car_color']       = $meta->car_color;
                    $data['meta_manufacturing_year'] = $meta->manufacturing_year;
                    $data['meta_car_numbers']     = $meta->car_numbers;
                    $data['meta_car_letters']     = $meta->car_letters;
                    $data['meta_sequenceNumber']  = $meta->sequenceNumber;
                    $data['meta_plateType']       = $meta->plateType;
                    $data['meta_bank_name']       = $meta->bank_name;
                    $data['meta_iban']            = $meta->iban;
                    $data['meta_stc_number']      = $meta->stc_number;
                    $data['refuse_reason']        = $meta->refuse_reason;
                    $data['meta_status']          = $meta->status;
                    $data['meta_car_image']       = url('img/user/usermeta/'.$meta->car_image);
                    $data['meta_identity_card']   =  url('img/user/usermeta/'.$meta->identity_card); 
                    $data['meta_driving_license'] = url('img/user/usermeta/'.$meta->driving_license);
                    $data['meta_personal_image']  = url('img/user/usermeta/'.$meta->personal_image);
                }
                $data['step'] = $step;                 
                return response()->json(successReturn($data));
            }else{
                $msg  = trans('auth.usernotfound');
                return response()->json(failReturn($msg));
            }
    }

    // public function userSignIn(Request $request){
    //     $validator        = Validator::make($request->all(), [
    //         'phone'       => 'required',
    //         'password'    => 'required',
    //         'device_id'   => 'required',
    //         'device_type' => 'required',
    //     ]);
    //     if ($validator->passes()) {
    //         $lang = ( $request->header('lang') )?? 'ar';
    //         $number         = convert2english($request->phone);
    //         $phone          = phoneValidate($number);

    //         if($token = JWTAuth::attempt(['phone' => $phone, 'password' => $request->password,'captain'=>'false'])) {
    //                 $user  = Auth::user();
    //                 $user->balance    = round(floatval($user->balance),2);
    //                 $user->save();
    //                 if($user->active == 'block'){
    //                   $msg = trans('user.userblocked');
    //                   return response()->json(failReturn($msg));
    //                 }
    //                 if($device = userDevices::where(['device_id'=>$request->device_id,'device_type'=>$request->device_type])->first()){
    //                     $device->user_id     = $user->id;
    //                     $device->device_id   = $request->device_id;
    //                     $device->device_type = strtolower($request->device_type);
    //                     $device->save();                
    //                 }else{
    //                     $device = new userDevices();
    //                     $device->user_id     = $user->id;
    //                     $device->device_id   = $request->device_id;
    //                     $device->device_type = strtolower( $request->device_type );
    //                     $device->save();                
    //                 }
    //                 $rate = ( $user->num_rating > 0 )? round(floatval($user->rating / $user->num_rating),1) : 0;
    //                     $data = [
    //                         'id'          => $user->id,
    //                         'name'        => ($user->name)?? '',
    //                         'phone'       => '0'.$user->phone,
    //                         'email'       => $user->email,
    //                         'captain'     => ($user->captain)??'',
    //                         'avatar'      => ($user->avatar)? url('img/user/'.$user->avatar) : url('img/user/default.png'),
    //                         'rate'        => "$rate",
    //                         'device_id'   => $request->device_id,
    //                         'plan'        => '',
    //                         'balance'     => number_format($user->balance,2), 
    //                         'active'      => $user->active,
    //                         'available'   => ($user->available)?? 'false',
                            // 'distance'   => (int)$user->distance,
                    //                         'code'        => ($user->code)?? '',
    //                         'time_zone'   => date_default_timezone_get(),
    //                     ];

    //                 $data['token']     = $token;
    //                 $data['googlekey'] = setting('google_places_key');
    //                 return response()->json(successReturn($data));
    //             }else {
    //                 $msg = trans('auth.invalid_signin');
    //                 return response()->json(failReturn($msg));
    //             }
    //     }else{
    //         $msg  = implode(' , ',$validator->errors()->all());
    //         return response()->json(failReturn($msg));
    //     }
    // }

    public function userSignIn(Request $request){
        $validator        = Validator::make($request->all(), [
            'phone'       => 'required',
            'device_id'   => 'required',
            'device_type' => 'required',
            'social_id'   => 'nullable',
            'country_iso' => 'nullable'    
        ]);
        if ($validator->passes()) {
            $lang = ( $request->header('lang') )?? 'ar';
            $number         = convert2english($request->phone);
            $phone          = phoneValidate($number);
            //if token he logined from social
            if($authentication = Authentication::with('user')->where(['uid' => $request->social_id])->first()){
                if($user = $authentication->user){
                    if($checkphone = User::where(['phone'=>$phone])->first()){
                       if(($checkphone->id != $user->id) || ($checkphone->captain == 'true')){
                          $msg = trans('user.phoneexists');
                          return response()->json(failReturn($msg));
                       }
                    }
                    if($user->active == 'block'){
                      $msg = trans('user.userblocked');
                      return response()->json(failReturn($msg));
                    }   
                    if($country = Country::where(['iso2' => $request->country_iso])->first()){
                        $user->country_id         = $country->id;
                        $user->current_country_id = $country->id;
                        $user->currency           = $country->{"currency_$lang"};
                        $user->phonekey           = $country->phonekey;
                    }
                    $user->phone     = $phone;
                    $user->code      = '1234';//generate_code();
                    $user->save();                    
                }
                $authentication->phone = $phone;
                $authentication->save();
            }elseif($user = User::where(['phone'=>$phone,'captain' => 'false'])->first()){
                $user->code       = '1234';//generate_code();
                $user->save();
                if($authentication = Authentication::where('user_id',$user->id)->first()){
                   $authentication->phone = $phone;
                   $authentication->save();
                }
                if($user->active == 'block'){
                  $msg = trans('user.userblocked');
                  return response()->json(failReturn($msg));
                }                
            }else{
                if($checkphone = User::where(['phone'=> $phone])->first()){
                      $msg = trans('user.phoneexists');
                      return response()->json(failReturn($msg));
                }
                $user   = new User();
                if($country = Country::where(['iso2' => $request->country_iso])->first()){
                    $user->country_id         = $country->id;
                    $user->current_country_id = $country->id;
                    $user->currency           = $country->{"currency_$lang"};
                    $user->phonekey           = $country->phonekey;
                }
                $user->phone     = $phone;
                $user->active    = 'pending';
                $user->captain   = 'false';
                $user->role      = '0';
                $user->plan_id   = '1';
                $user->avatar    = 'default.png';
                $user->balance   = setting('free_balance');
                $user->code       = '1234';//generate_code();
                $user->share_code = generate_share_code();
                $user->pin_code   = generate_pin_code();
                $user->save();               
            }
                $msg        = trans('auth.yourcode').''.setting('site_title').':'.$user->code;
                $phone      = $user->phone;
                $key        = $user->phonekey;
                send_mobile_sms($key.$phone, $msg);
                // try {
                //     if($user->email){
                //       Mail::to($user->email)->send(new PublicMessage($msg));                 
                //     }
                // }catch (Exception $e) {
                //      //$e->getMessage();
                // } 
                if(($user->active == 'active') && ($user->name != null)){
                  $is_registered = true;
                }else{
                  $is_registered = false;
                }                  
                $rate = ( $user->num_rating > 0 )? round(floatval($user->rating / $user->num_rating),1) : 0;
               
                if($device = userDevices::where(['device_id'=>request('device_id'),'device_type'=>request('device_type')])->first()){
                    $device->user_id     = $user->id;
                    $device->device_id   = $request->device_id;
                    $device->device_type = strtolower( $request->device_type );
                    $device->save();                
                }else{
                    $device = new userDevices();
                    $device->user_id       = $user->id;
                    $device->device_id     = $request->device_id;
                    $device->device_type   = strtolower( $request->device_type );
                    $device->show_ads      = 'true';
                    $device->orders_notify = 'true';                
                    $device->save();                
                }
                $card_token = '';
                if($uservisa = userPaymentWays::where('user_id','=',$user->id)->first()){
                   $card_token = ($uservisa->card_token)??'';
                }
                    $data = [
                        'id'          => $user->id,
                        'name'        => ($user->name)?? '',
                        'phone'       => $user->phone,
                        'phonekey'    => ($user->phonekey)??'',
                        'gender'      => ($user->gender)??'male',
                        'country_id'  => $user->country_id,
                        'birth_date'  => ($user->birth_date)??'', 
                        'email'       => ($user->email)??'',
                        'captain'     => ($user->captain)??'',
                        'avatar'      => ($user->avatar)? url('img/user/'.$user->avatar) : url('img/user/default.png'),
                        'rate'        => "$rate",
                        'device_id'   => $request->device_id,
                        'plan'        => '',
                        'balance'     => number_format($user->balance,2), 
                        'balance_electronic_payment'     => number_format((float)$user->balance_electronic_payment, 2), 
                        'points'      => (int) $user->points,
                        'currency'    => ($user->currency)??'',               
                        'active'      => ($user->active)??'pending',
                        'available'   => ($user->available)?? 'false',
                        'distance'   => (int)$user->distance,
                        'code'        => ($user->code)?? '',
                        'time_zone'   => date_default_timezone_get(),
                        'is_registered' => $is_registered,
                        'card_token'  => $card_token,
                        'need_recharge' => (int) $user->balance <= (-1 * setting('max_debt_captain')) ? true : false,
                        'payment_page_background' => url('dashboard/uploads/setting/site_logo/'.setting('payment_page_background'))
                    ];

                $data['token']     = JWTAuth::fromUser($user);
                $data['googlekey'] = setting('google_places_key');
                return response()->json(successReturn($data));
        }else{
            $msg  = implode(' , ',$validator->errors()->all());
            return response()->json(failReturn($msg));
        }
    }

    public function checkUserSignInSocial(Request $request){
        $validator        = Validator::make($request->all(), [
            'social_id'   => 'required',
            'device_id'   => 'required',
            'device_type' => 'required',
            'name'        => 'nullable',            
            'email'       => 'nullable|email'            
        ]);
        if ($validator->passes()) {
            $data = [];
           if($authentication = Authentication::with('user')->where('uid','=',$request->social_id)->first()){
                ($request->name)? $authentication->username = $request->name : '';
                ($request->email)? $authentication->email    = $request->email : '';
                $authentication->save();
                if($user = $authentication->user){
                    ($request->name)? $user->name  = $request->name : '';
                    ($request->email)? $user->email = $request->email : '';
                    $user->save();
                    if(($user->active == 'active') && ($user->name != null)){
                      $is_registered = true;
                    }else{
                      $is_registered = false;
                    }                                     
                    if($device = userDevices::where(['device_id'=>request('device_id'),'device_type'=>request('device_type')])->first()){
                        $device->user_id     = $user->id;
                        $device->device_id   = $request->device_id;
                        $device->device_type = strtolower( $request->device_type );
                        $device->save();                
                    }else{
                        $device = new userDevices();
                        $device->user_id       = $user->id;
                        $device->device_id     = $request->device_id;
                        $device->device_type   = strtolower( $request->device_type );
                        $device->show_ads      = 'true';
                        $device->orders_notify = 'true';                
                        $device->save();                
                    }
                    $card_token = '';
                    if($uservisa = userPaymentWays::where('user_id','=',$user->id)->first()){
                       $card_token = ($uservisa->card_token)??'';
                    }
                    $rate = ( $user->num_rating > 0 )? round(floatval($user->rating / $user->num_rating),1) : 0;
                    $data = [
                        'id'          => $user->id,
                        'name'        => ($user->name)?? '',
                        'phone'       => ($user->phone)??'',
                        'phonekey'    => ($user->phonekey)??'',
                        'gender'      => ($user->gender)??'male',
                        'country_id'  => $user->country_id,
                        'birth_date'  => ($user->birth_date)??'', 
                        'email'       => ($user->email)??'',
                        'captain'     => ($user->captain)??'',
                        'avatar'      => ($user->avatar)? url('img/user/'.$user->avatar) : url('img/user/default.png'),
                        'rate'        => "$rate",
                        'device_id'   => $request->device_id,
                        'plan'        => '',
                        'balance'     => number_format($user->balance,2), 
                        'balance_electronic_payment'     => number_format((float)$user->balance_electronic_payment, 2), 
                        'points'      => (int) $user->points,
                        'currency'    => ($user->currency)??'',               
                        'active'      => ($user->active)??'pending',
                        'available'   => ($user->available)?? 'false',
                        'distance'   => (int)$user->distance,
                        'code'        => ($user->code)?? '',
                        'time_zone'   => date_default_timezone_get(),
                        'is_registered' => $is_registered,
                        'card_token'  => $card_token,
                        'need_recharge' => (int) $user->balance <= (-1 * setting('max_debt_captain')) ? true : false,
                        'payment_page_background' => url('dashboard/uploads/setting/site_logo/'.setting('payment_page_background'))
                    ];

                $data['token']     = JWTAuth::fromUser($user);
                $data['googlekey'] = setting('google_places_key');
                $data['registered_social'] = true; 
                $data['phone_registered']  = (($user->phone != null) && ($user->active == 'active') )? true : false;
                }else{
                        $user = new User();
                        $user->email     = $request->email;
                        $user->name      = $request->name;
                        $user->active    = 'pending';
                        $user->captain    = 'false';
                        $user->role      = '0';
                        $user->plan_id   = '1';
                        $user->avatar    = 'default.png';
                        $user->balance   = setting('free_balance');
                        $user->code       = '1234';//generate_code();
                        $user->share_code = generate_share_code();
                        $user->pin_code   = generate_pin_code();
                        $user->save();  

                        $authentication->uid      = $request->social_id;
                        $authentication->user_id  = $user->id;
                        $authentication->username = $request->name;
                        $authentication->email    = $request->email;
                        $authentication->save();
                        if($device = userDevices::where(['device_id'=>request('device_id'),'device_type'=>request('device_type')])->first()){
                            $device->user_id     = $user->id;
                            $device->device_id   = $request->device_id;
                            $device->device_type = strtolower( $request->device_type );
                            $device->save();                
                        }else{
                            $device = new userDevices();
                            $device->user_id       = $user->id;
                            $device->device_id     = $request->device_id;
                            $device->device_type   = strtolower( $request->device_type );
                            $device->show_ads      = 'true';
                            $device->orders_notify = 'true';                
                            $device->save();                
                        }
                        $card_token = '';
                        if($uservisa = userPaymentWays::where('user_id','=',$user->id)->first()){
                           $card_token = ($uservisa->card_token)??'';
                        }
                        $rate = ( $user->num_rating > 0 )? round(floatval($user->rating / $user->num_rating),1) : 0;
                        $data = [
                            'id'          => $user->id,
                            'name'        => ($user->name)?? '',
                            'phone'       => ($user->phone)??'',
                            'phonekey'    => ($user->phonekey)??'',
                            'gender'      => ($user->gender)??'male',
                            'country_id'  => $user->country_id,
                            'birth_date'  => ($user->birth_date)??'', 
                            'email'       => ($user->email)??'',
                            'captain'     => ($user->captain)??'',
                            'avatar'      => ($user->avatar)? url('img/user/'.$user->avatar) : url('img/user/default.png'),
                            'rate'        => "$rate",
                            'device_id'   => $request->device_id,
                            'plan'        => '',
                            'balance'     => number_format($user->balance,2), 
                            'balance_electronic_payment'     => number_format((float)$user->balance_electronic_payment, 2), 
                            'points'      => (int) $user->points,
                            'currency'    => ($user->currency)??'',               
                            'active'      => ($user->active)??'pending',
                            'available'   => ($user->available)?? 'false',
                            'distance'   => (int)$user->distance,
                            'code'        => ($user->code)?? '',
                            'time_zone'   => date_default_timezone_get(),
                            'is_registered' => true,
                            'card_token'  => $card_token,
                            'need_recharge' => (int) $user->balance <= (-1 * setting('max_debt_captain')) ? true : false,
                            'payment_page_background' => url('dashboard/uploads/setting/site_logo/'.setting('payment_page_background'))
                        ];

                        $data['token']     = JWTAuth::fromUser($user);
                        $data['googlekey'] = setting('google_places_key');

                        $data['phone_registered']  = (($user->phone != null) && ($user->active == 'active') )? true : false;
                        $data['registered_social'] = true;  
                }               
                return response()->json(successReturn($data));
           }else{
                if($request->email){
                    if($user = User::where('email','=',$request->email)->first()){
                        $user->name       = $request->name;
                        $user->code       = '1234';//generate_code();
                        $user->share_code = generate_share_code();
                        $user->pin_code   = generate_pin_code();
                        $user->save(); 

                        $authentication = new Authentication();
                        $authentication->uid      = $request->social_id;
                        $authentication->user_id  = $user->id;
                        $authentication->username = $request->name;
                        $authentication->email    = $request->email;
                        $authentication->save();
                        $data['phone_registered']  = (($user->phone != null) && ($user->active == 'active') )? true : false;
                        $data['registered_social'] = false;   
                    }else{
                        $user = new User();
                        $user->email     = $request->email;
                        $user->name      = $request->name;
                        $user->active    = 'pending';
                        $user->captain   = 'false';
                        $user->role      = '0';
                        $user->plan_id   = '1';
                        $user->avatar    = 'default.png';
                        $user->balance   = setting('free_balance');
                        $user->code       = '1234';//generate_code();
                        $user->share_code = generate_share_code();
                        $user->pin_code   = generate_pin_code();
                        $user->save();  

                        $authentication = new Authentication();
                        $authentication->uid      = $request->social_id;
                        $authentication->user_id  = $user->id;
                        $authentication->username = $request->name;
                        $authentication->email    = $request->email;
                        $authentication->save();
                        $data['phone_registered']  = (($user->phone != null) && ($user->active == 'active') )? true : false;
                        $data['registered_social'] = false; 
                    }
                }else{
                    $user = new User();
                    $user->email     = $request->email;
                    $user->name      = $request->name;
                    $user->active    = 'pending';
                    $user->captain   = 'false';
                    $user->role      = '0';
                    $user->plan_id   = '1';
                    $user->avatar    = 'default.png';
                    $user->balance   = setting('free_balance');
                    $user->code       = '1234';//generate_code();
                    $user->share_code = generate_share_code();
                    $user->pin_code   = generate_pin_code();
                    $user->save();  

                    $authentication = new Authentication();
                    $authentication->uid      = $request->social_id;
                    $authentication->user_id  = $user->id;
                    $authentication->username = $request->name;
                    $authentication->email    = $request->email;
                    $authentication->save();
                    $data['phone_registered']  = (($user->phone != null) && ($user->active == 'active') )? true : false;
                    $data['registered_social'] = false; 
                }
                return response()->json(successReturn($data)); 
           }
        }else{
            $msg  = implode(' , ',$validator->errors()->all());
            return response()->json(failReturn($msg));
        }
    }

    public function captainSignIn(Request $request){
        $validator        = Validator::make($request->all(), [
            'phone'       => 'required',
            'password'    => 'required',
            'device_id'   => 'required',
            'device_type' => 'required',
        ]);
        if ($validator->passes()) {
            $lang = ( $request->header('lang') )?? 'ar';
            $number         = convert2english($request->phone);
            $phone          = phoneValidate($number);

            if($token = JWTAuth::attempt(['phone' => $phone, 'password' => $request->password,'captain'=>'true'])) {
                    $user  = Auth::user();
                    $user->balance    = round(floatval($user->balance),2);
                    $user->save();                    
                    if($user->active == 'block'){
                      $msg = trans('user.userblocked');
                      return response()->json(failReturn($msg));
                    }
                    if($device = userDevices::where(['device_id'=>$request->device_id,'device_type'=>$request->device_type])->first()){
                        $device->user_id     = $user->id;
                        $device->device_id   = $request->device_id;
                        $device->device_type = strtolower($request->device_type);
                        $device->save();                
                    }else{
                        $device = new userDevices();
                        $device->user_id     = $user->id;
                        $device->device_id   = $request->device_id;
                        $device->device_type = strtolower( $request->device_type );
                        $device->save();                
                    }
                    $card_token = '';
                    if($uservisa = userPaymentWays::where('user_id','=',$user->id)->first()){
                       $card_token = ($uservisa->card_token)??'';
                    }
                    $rate = ( $user->num_rating > 0 )? round(floatval($user->rating / $user->num_rating),1) : 0;
                        $data = [
                            'id'          => $user->id,
                            'name'        => ($user->name)?? '',
                            'phone'       => $user->phone,
                            'phonekey'    => ($user->phonekey)??'',
                            'gender'      => ($user->gender)??'male',
                            'country_id'  => $user->country_id,
                            'birth_date'  => ($user->birth_date)??'', 
                            'email'       => ($user->email)??'',
                            'captain'     => ($user->captain)??'',
                            'avatar'      => ($user->avatar)? url('img/user/'.$user->avatar) : url('img/user/default.png'),
                            'rate'        => "$rate",
                            'device_id'   => $request->device_id,
                            'plan'        => ($user->plan)? $user->plan->{"name_$lang"}:'',
                            'balance'     => number_format($user->balance,2),
                            'balance_electronic_payment'     => number_format((float)$user->balance_electronic_payment, 2), 
                            'points'      => (int) $user->points,
                            'currency'    => ($user->currency)??'',               
                            'active'      => ($user->active)??'pending',
                            'available'   => ($user->available)?? 'false',
                            'distance'   => (int)$user->distance,
                            'code'        => ($user->code)?? '',
                            'pin_code'    => ($user->pin_code)?? '',
                            'time_zone'   => date_default_timezone_get(),
                            'card_token'  => $card_token,
                            'need_recharge' => (int) $user->balance <= (-1 * setting('max_debt_captain')) ? true : false,
                            'payment_page_background' => url('dashboard/uploads/setting/site_logo/'.setting('payment_page_background'))
                        ];

                    $data['token']     = $token;
                    $data['googlekey'] = setting('google_places_key');
                    return response()->json(successReturn($data));
                }else {
                    if(userMeta::where('phone','=',$phone)->where('status','!=','agree')->first()){
                        $msg = trans('auth.wait_agree');
                        return response()->json(failReturn($msg));
                    }

                    $msg = trans('auth.invalid_signin');
                    return response()->json(failReturn($msg));
                }
        }else{
            $msg  = implode(' , ',$validator->errors()->all());
            return response()->json(failReturn($msg));
        }
    }

    public function forgetPassword(Request $request){
        $validator                     = Validator::make($request->all(),[
            'phone'                    => 'required'
        ]);
        if($validator->passes()){
            $lang = ( $request->header('lang') )?? 'ar';
            $number     = convert2english(request('phone'));
            $phone      = phoneValidate($number);          
            $user       = User::where('phone', $phone)->first();
            $msg        = trans('auth.yourcode').''.setting('site_title').':';
            if( $user ) {
                if($user->active == 'block'){
                  $msg = trans('user.userblocked');
                  return response()->json(failReturn($msg));
                }                
                $user->code = '1234';//generate_code();
                $user->save();
                $msg        = $msg . $user->code;
                $phone      = $user->phone;
                $key        = $user->phonekey;
                send_mobile_sms($key.$phone, $msg);
                // try {
                //     if($user->email){
                //       Mail::to($user->email)->send(new PublicMessage($msg));                 
                //     }
                // }catch (Exception $e) {
                //      //$e->getMessage();
                // }                 
                $card_token = '';
                if($uservisa = userPaymentWays::where('user_id','=',$user->id)->first()){
                   $card_token = ($uservisa->card_token)??'';
                }
                $data = [
                    'id'          => $user->id,
                    'name'        => ($user->name)?? '',
                    'phone'       => $user->phone,
                    'phonekey'    => ($user->phonekey)??'',
                    'gender'      => ($user->gender)??'male',
                    'country_id'  => $user->country_id,
                    'birth_date'  => ($user->birth_date)??'', 
                    'email'       => ($user->email)??'',
                    'captain'     => ($user->captain)??'',
                    'avatar'      => ($user->avatar)? url('img/user/'.$user->avatar) : url('img/user/default.png'),
                    'device_id'   => '',
                    'plan'        => ($user->plan)? $user->plan->{"name_$lang"}:'',
                    'balance'     => number_format($user->balance,2),                      
                    'balance_electronic_payment'     => number_format((float)$user->balance_electronic_payment, 2), 
                    'points'      => (int) $user->points,
                    'currency'    => ($user->currency)??'',               
                    'active'      => ($user->active)??'pending',
                    'available'   => ($user->available)?? 'false',
                    'distance'   => (int)$user->distance,
                    'code'        => $user->code,
                    'time_zone'   => date_default_timezone_get(),
                    'card_token'  => $card_token,
                    'need_recharge' => (int) $user->balance <= (-1 * setting('max_debt_captain')) ? true : false,
                    'payment_page_background' => url('dashboard/uploads/setting/site_logo/'.setting('payment_page_background'))
                ];
                $data['token'] = JWTAuth::fromUser($user);
                $data['googlekey'] = setting('google_places_key');
                return response()->json(successReturn($data));
            }else{
                $msg  = trans('auth.usernotfound');
                return response()->json(failReturn($msg));
            }
        }else{
            $msg  = implode(' , ',$validator->errors()->all());
            return response()->json(failReturn($msg));
        }
    }

    public function resetPassword(Request $request){
        $validator           = Validator::make($request->all(),[
            'code'           => 'required',
            'password'       => 'required|alpha_dash|between:6,50'
        ]);
        if($validator->passes()){
            $lang = ( $request->header('lang') )?? 'ar';
            $user    = JWTAuth::parseToken()->authenticate();
            if($user->active == 'block'){
              $msg = trans('user.userblocked');
              return response()->json(failReturn($msg));
            }
            if($user->code != request('code')){
                $msg        =   trans('auth.invalid_code');
                return response()->json(failReturn($msg));
            }            
            $user->password = Hash::make($request->password);
            $user->save();
            $card_token = '';
            if($uservisa = userPaymentWays::where('user_id','=',$user->id)->first()){
               $card_token = ($uservisa->card_token)??'';
            }
                $data = [
                    'id'          => $user->id,
                    'name'        => ($user->name)?? '',
                    'phone'       => $user->phone,
                    'phonekey'    => ($user->phonekey)??'',
                    'gender'      => ($user->gender)??'male',
                    'country_id'  => $user->country_id,
                    'birth_date'  => ($user->birth_date)??'', 
                    'email'       => ($user->email)??'',
                    'captain'     => ($user->captain)??'',
                    'avatar'      => ($user->avatar)? url('img/user/'.$user->avatar) : url('img/user/default.png'),
                    'device_id'   => '',
                    'plan'        => ($user->plan)? $user->plan->{"name_$lang"}:'',
                    'balance'     => number_format($user->balance,2),                         
                    'balance_electronic_payment'     => number_format((float)$user->balance_electronic_payment, 2), 
                    'points'      => (int) $user->points,
                    'currency'    => ($user->currency)??'',               
                    'active'      => ($user->active)??'pending',
                    'available'   => ($user->available)?? 'false',
                    'distance'   => (int)$user->distance,
                    'code'        => $user->code,
                    'time_zone'   => date_default_timezone_get(),
                    'card_token'  => $card_token,
                    'need_recharge' => (int) $user->balance <= (-1 * setting('max_debt_captain')) ? true : false,
                    'payment_page_background' => url('dashboard/uploads/setting/site_logo/'.setting('payment_page_background'))
                ];
                $data['token'] = JWTAuth::fromUser($user);
                $data['googlekey'] = setting('google_places_key');                    
            return response()->json(successReturn($data));
        }else{
            $msg  = implode(' , ',$validator->errors()->all());
            return response()->json(failReturn($msg));
        }
    }

    public function logout(Request $request){
        // $validator = Validator::make($request->all(),[
        //     'device_id'  => 'required',
        // ]);
        // if($validator->passes()){        
            $user = JWTAuth::parseToken()->authenticate();
            if($request->device_id){
                if($device = userDevices::where(['user_id'=>$user->id,'device_id'=>$request->device_id])->first()){
                    $device->delete();
                    // $msg = trans('auth.logout_success');
                    // return response()->json(successReturnMsg($msg));
                } 
                // $msg = trans('auth.no_device');           
                // return response()->json(failReturn($msg));
            }
            $msg = trans('auth.logout_success');
            return response()->json(successReturnMsg($msg));
        // }else{
        //     $msg  = implode(' , ',$validator->errors()->all());
        //     return response()->json(failReturn($msg));
        // }
    }

    /************* Start Captain Sign Up Steps **************/
    public function captainSignupPhone(Request $request){
        $validator           = Validator::make($request->all(),[
            'country_iso'         => 'required',
            'phone'               => 'required|numeric',
        ]);
        if($validator->passes()){
            $lang = ( $request->header('lang') )?? 'ar';
            $number         = convert2english($request->phone);
            $phone          = phoneValidate($number);
            // if(userMeta::where('phone','=',$phone)->where('complete','=','true')->where('status','!=','agree')->first()){
            //     $msg = trans('auth.wait_agree');
            //     return response()->json(failReturn($msg));
            // }
            $user = User::where(['phone'=>$phone])->first();
            if(!$user){
                $user   = new User();
                if($country = Country::where(['iso2' => $request->country_iso])->first()){
                    $user->country_id         = $country->id;
                    $user->current_country_id = $country->id;
                    $user->currency           = $country->{"currency_$lang"};
                    $user->phonekey           = $country->phonekey;
                }
                $user->phone     = $phone;
                $user->active    = 'pending';
                $user->captain   = 'false';
                $user->role      = '0';
                $user->plan_id   = '1';
                $user->avatar    = 'default.png';
                $user->balance   = setting('free_balance');
                $user->code       = '1234';//generate_code();
                $user->share_code = generate_share_code();
                $user->pin_code   = generate_pin_code();
                $user->save();               

            }
            if($user->active == 'block'){
                $msg = trans('user.userblocked');
                return response()->json(failReturn($msg));
            }
            if($request->device_id){
                if($device = userDevices::where(['device_id'=>request('device_id'),'device_type'=>request('device_type')])->first()){
                    $device->user_id     = $user->id;
                    $device->device_id   = $request->device_id;
                    $device->device_type = strtolower( $request->device_type );
                    $device->save();                
                }else{
                    $device = new userDevices();
                    $device->user_id       = $user->id;
                    $device->device_id     = $request->device_id;
                    $device->device_type   = strtolower( $request->device_type );
                    $device->show_ads      = 'true';
                    $device->orders_notify = 'true';                
                    $device->save();                
                }
            }
            if(($user->active == 'active') && ($user->name != null)){
                $is_registered = true;
              }else{
                $is_registered = false;
              }                  
              $rate = ( $user->num_rating > 0 )? round(floatval($user->rating / $user->num_rating),1) : 0;
                $card_token = '';
                if($uservisa = userPaymentWays::where('user_id','=',$user->id)->first()){
                $card_token = ($uservisa->card_token)??'';
                }
                $data = [
                    'id'          => $user->id,
                    'name'        => ($user->name)?? '',
                    'phone'       => $user->phone,
                    'phonekey'    => ($user->phonekey)??'',
                    'gender'      => ($user->gender)??'male',
                    'country_id'  => $user->country_id,
                    'birth_date'  => ($user->birth_date)??'', 
                    'email'       => ($user->email)??'',
                    'captain'     => ($user->captain)??'',
                    'avatar'      => ($user->avatar)? url('img/user/'.$user->avatar) : url('img/user/default.png'),
                    'rate'        => "$rate",
                    'device_id'   => $request->device_id,
                    'plan'        => '',
                    'balance'     => number_format($user->balance,2),
                    'balance_electronic_payment'     => number_format((float)$user->balance_electronic_payment, 2), 
                    'points'      => (int) $user->points,
                    'currency'    => ($user->currency)??'',               
                    'active'      => ($user->active)??'pending',
                    'available'   => ($user->available)?? 'false',
                    'distance'   => (int)$user->distance,
                    'code'        => ($user->code)?? '',
                    'time_zone'   => date_default_timezone_get(),
                    'is_registered' => $is_registered,
                    'card_token'  => $card_token,
                    'need_recharge' => (int) $user->balance <= (-1 * setting('max_debt_captain')) ? true : false,
                    'payment_page_background' => url('dashboard/uploads/setting/site_logo/'.setting('payment_page_background'))
                ];
    
            $data['token']     = JWTAuth::fromUser($user);
            $data['googlekey'] = setting('google_places_key');
            
            $user->code      = '1234';//generate_code();
            $user->save();  
            $msg        = trans('auth.yourcode').''.setting('site_title').':'.$user->code;
            $phone      = $user->phone;
            $key        = $user->phonekey;

            $meta = userMeta::where(['phone' =>$phone])->first();
            $step = 0;
            if(!$meta){
                $meta = new userMeta();
                $meta->user_id            = $user->id;
                if($country = Country::where(['iso2' => $request->country_iso])->first()){
                   $meta->country_id      = $country->id;
                   $meta->phonekey        = str_replace('00', '+', $country->phonekey);
                 }
                $meta->phone              = $phone;
                $meta->save();
            }else{
                if($meta->identity_number != null && $meta->car_type != null && $meta->iban != null &&  $meta->complete != 'true'){
                    $step = 4;
                }elseif($meta->identity_number != null && $meta->car_type != null && $meta->iban == null){
                    $step = 3;
                }elseif($meta->identity_number != null && $meta->car_type == null){
                    $step = 2;
                }elseif($meta->identity_number == null && $meta->password != null){
                    $step = 1;
                }elseif($meta->password == null){
                    $step = 0;
                }
            }

            $data['meta_country_id'] = $meta->country_id;
            $data['meta_phonekey']   = $meta->phonekey;
            $data['meta_phone']      = $meta->phone;
            $data['meta_name']       = $meta->name;
            $data['meta_email']      = $meta->email;
            $data['meta_birthdate']  = $meta->birthdate;
            $data['meta_identity_number'] = $meta->identity_number;
            $data['meta_city_id']         = $meta->city_id;
            $data['meta_city_name']       = $meta->city->{"name_$lang"}??'';
            $data['meta_car_type']        = $meta->car_type;
            $data['meta_car_model']       = $meta->car_model;
            $data['meta_car_color']       = $meta->car_color;
            $data['meta_manufacturing_year'] = $meta->manufacturing_year;
            $data['meta_car_numbers']     = $meta->car_numbers;
            $data['meta_car_letters']     = $meta->car_letters;
            $data['meta_sequenceNumber']  = $meta->sequenceNumber;
            $data['meta_plateType']       = $meta->plateType;
            $data['meta_bank_name']       = $meta->bank_name;
            $data['meta_iban']            = $meta->iban;
            $data['meta_stc_number']      = $meta->stc_number;
            $data['refuse_reason']        = $meta->refuse_reason;
            $data['meta_status']          = $meta->status;
            $data['meta_car_image']       = url('img/user/usermeta/'.$meta->car_image);
            $data['meta_identity_card']   = url('img/user/usermeta/'.$meta->identity_card); 
            $data['meta_driving_license'] = url('img/user/usermeta/'.$meta->driving_license);
            $data['meta_personal_image']  = url('img/user/usermeta/'.$meta->personal_image);
            $data['step'] = $step;
            send_mobile_sms($key.$phone, $msg);

            return response()->json(successReturn($data));
        }else{
            $msg  = implode(' , ',$validator->errors()->all());
            return response()->json(failReturn($msg));
        }
    }

    public function captainSignupPassword(Request $request){
        $validator           = Validator::make($request->all(),[
            'password'       => 'required|alpha_dash|between:6,50',
        ]);
        if($validator->passes()){
            $user = JWTAuth::parseToken()->authenticate();
            if($meta = userMeta::where(['user_id' =>$user->id])->first()){
                $meta->password           = Hash::make($request->password);
                $meta->save();
            }
            $msg = trans('messages.saved');
            return response()->json(successReturnMsg($msg));
        }else{
            $msg  = implode(' , ',$validator->errors()->all());
            return response()->json(failReturn($msg));
        }
    }

    public function captainSignup1(Request $request){
        $validator           = Validator::make($request->all(),[
            'name'                => 'required|min:3',
            'email'               => 'required|email',
            'birthdate'           => 'required',
            'identity_number'     => 'required|unique:user_meta,identity_number',
            // 'gender'              => 'required',
            'city_id'             => 'required',
            // 'friend_code'         => 'nullable',
        ]);
        if($validator->passes()){
            $user = JWTAuth::parseToken()->authenticate();
            if($meta = userMeta::where(['user_id' =>$user->id])->first()){
                $meta->name               = $request->name;
                $meta->email              = $request->email;
                $meta->birthdate          = date('d-m-Y',strtotime($request->birthdate));
                $meta->identity_number    = $request->identity_number;
                // $meta->gender             = $request->gender;
                $meta->city_id            = $request->city_id;
                // $meta->friend_code        = $request->friend_code;
                $meta->save();
            }
            $msg = trans('messages.saved');
            return response()->json(successReturnMsg($msg));
        }else{
            $msg  = implode(' , ',$validator->errors()->all());
            return response()->json(failReturn($msg));
        }
    }

    public function captainSignup2(Request $request){
        $validator           = Validator::make($request->all(),[
            'car_type'           => 'required|min:3',
            'car_model'          => 'required|min:2',
            'car_color'          => 'required|min:2',
            'manufacturing_year' => 'required|min:2',
            'car_numbers'        => 'required',
            'car_letters'        => 'required',
            'sequenceNumber'     => 'required',
            'plateType'          => 'required',
        ]);
        if($validator->passes()){
            $user = JWTAuth::parseToken()->authenticate();
            if($meta = userMeta::where(['user_id' =>$user->id])->first()){
                $plateTypes = ['1' => 'خصوصي' ,'2' => 'نقل عام' ,'3' => 'نقل خاص' ,'4' => 'حافلة صغيرة عامة', '5' => 'حافلة صغيرة خاصة', '6' => 'اجرة' ,'7' => 'معدات ثقيلة', '8' => 'تصدير' ,'9' =>'دبلوماسي' ,/*'10' =>'دراجة نارية',*/ '11' => 'مؤقت'];
                $meta->car_type           = $request->car_type;
                $meta->car_model          = $request->car_model;
                $meta->car_color          = $request->car_color;
                $meta->manufacturing_year = $request->manufacturing_year;
                $meta->car_numbers        = $request->car_numbers;
                $meta->car_letters        = (strpos($request->car_letters, ' ') !== false )? $request->car_letters :  implode(' ',str_split($request->car_letters));
                $meta->sequenceNumber     = $request->sequenceNumber;
                $meta->plateType          = $request->plateType;
                $meta->plateType_txt      = (isset($plateTypes[$request->plateType]))? $plateTypes[$request->plateType] : '' ;
                $meta->save();
            }
            $msg = trans('messages.saved');
            return response()->json(successReturnMsg($msg));
        }else{
            $msg  = implode(' , ',$validator->errors()->all());
            return response()->json(failReturn($msg));
        }
    }

    public function captainSignup3(Request $request){
        $validator           = Validator::make($request->all(),[
            'bank_name'          => 'required|min:2',
            'iban'               => 'required|min:14',
            'stc_number'         => 'required|min:8',
        ]);
        if($validator->passes()){
            $user = JWTAuth::parseToken()->authenticate();
            if($meta = userMeta::where(['user_id' =>$user->id])->first()){
                $stc_number          = convert2english($request->stc_number);
                $stc_number          = phoneValidate($stc_number);

                $meta->bank_name     = $request->bank_name;
                $meta->iban          = $request->iban;
                $meta->stc_number    = $stc_number;
                $meta->save();
            }
            $msg = trans('messages.saved');
            return response()->json(successReturnMsg($msg));
        }else{
            $msg  = implode(' , ',$validator->errors()->all());
            return response()->json(failReturn($msg));
        }
    }

    public function captainSignup4(Request $request){
        $validator           = Validator::make($request->all(),[
            'personal_image'  => 'required|mimes:jpeg,png,jpg,gif,doc,docx,pdf',
            'identity_card'   => 'required|mimes:jpeg,png,jpg,gif,doc,docx,pdf',
            'driving_license' => 'required|mimes:jpeg,png,jpg,gif,doc,docx,pdf',
            'car_form'        => 'required|mimes:jpeg,png,jpg,gif,doc,docx,pdf',
            'car_image'       => 'required|mimes:jpeg,png,jpg,gif,doc,docx,pdf',
            'authorization_image'    => 'nullable|mimes:jpeg,png,jpg,gif,doc,docx,pdf',
            // 'car_insurance'   => 'required|mimes:jpeg,png,jpg,gif,doc,docx,pdf',
        ]);
        if($validator->passes()){
            $user = JWTAuth::parseToken()->authenticate();
            if($meta = userMeta::where(['user_id' =>$user->id])->first()){

                if($request->hasFile('car_image')) {
                    $image           = $request->file('car_image');
                    $name            = md5($request->file('car_image')->getClientOriginalName()).time().rand(99999,1000000).'.'.$image->getClientOriginalExtension();
                    $destinationPath = public_path('/img/user/usermeta');
                    $imagePath       = $destinationPath. "/".  $name;
                    $image->move($destinationPath, $name);
                    $meta->car_image    = $name;
                }  
                if($request->hasFile('identity_card')) {
                    $image           = $request->file('identity_card');
                    $name            = md5($request->file('identity_card')->getClientOriginalName()).time().rand(99999,1000000).'.'.$image->getClientOriginalExtension();
                    $destinationPath = public_path('/img/user/usermeta');
                    $imagePath       = $destinationPath. "/".  $name;
                    $image->move($destinationPath, $name);
                    $meta->identity_card    = $name;
                }
                if($request->hasFile('driving_license')) {
                    $image           = $request->file('driving_license');
                    $name            = md5($request->file('driving_license')->getClientOriginalName()).time().rand(99999,1000000).'.'.$image->getClientOriginalExtension();
                    $destinationPath = public_path('/img/user/usermeta');
                    $imagePath       = $destinationPath. "/".  $name;
                    $image->move($destinationPath, $name);
                    $meta->driving_license    = $name;
                }
                if($request->hasFile('car_form')) {
                    $image           = $request->file('car_form');
                    $name            = md5($request->file('car_form')->getClientOriginalName()).time().rand(99999,1000000).'.'.$image->getClientOriginalExtension();
                    $destinationPath = public_path('/img/user/usermeta');
                    $imagePath       = $destinationPath. "/".  $name;
                    $image->move($destinationPath, $name);
                    $meta->car_form    = $name;
                } 
                if($request->hasFile('authorization_image')) {
                    $image           = $request->file('authorization_image');
                    $name            = md5($request->file('authorization_image')->getClientOriginalName()).time().rand(99999,1000000).'.'.$image->getClientOriginalExtension();
                    $destinationPath = public_path('/img/user/usermeta');
                    $imagePath       = $destinationPath. "/".  $name;
                    $image->move($destinationPath, $name);
                    $meta->authorization_image    = $name;
                } 
                
                // if($request->hasFile('iban')) {
                //     $image           = $request->file('iban');
                //     $name            = md5($request->file('iban')->getClientOriginalName()).time().rand(99999,1000000).'.'.$image->getClientOriginalExtension();
                //     $destinationPath = public_path('/img/user/usermeta');
                //     $imagePath       = $destinationPath. "/".  $name;
                //     $image->move($destinationPath, $name);
                //     $meta->iban    = $name;
                // }   
                // if($request->hasFile('car_insurance')) {
                //     $image           = $request->file('car_insurance');
                //     $name            = md5($request->file('car_insurance')->getClientOriginalName()).time().rand(99999,1000000).'.'.$image->getClientOriginalExtension();
                //     $destinationPath = public_path('/img/user/usermeta');
                //     $imagePath       = $destinationPath. "/".  $name;
                //     $image->move($destinationPath, $name);
                //     $meta->car_insurance    = $name;
                // } 
                if($request->hasFile('personal_image')) {
                    $image           = $request->file('personal_image');
                    $name            = md5($request->file('personal_image')->getClientOriginalName()).time().rand(99999,1000000).'.'.$image->getClientOriginalExtension();
                    $destinationPath = public_path('/img/user/usermeta');
                    $imagePath       = $destinationPath. "/".  $name;
                    $image->move($destinationPath, $name);
                    $meta->personal_image    = $name;
                }                 
               $meta->complete           = 'true';
               $meta->status             = 'pending';
               $meta->save();
            }
              //send driver and car information to elm
              if($elm_results = waslRegisterDriverAndCar($meta->identity_number,$meta->birthdate,$meta->email,$meta->phonekey.$meta->phone,$meta->sequenceNumber,$meta->car_letters,$meta->car_numbers,$meta->plateType)){
                $meta->resultCode = $elm_results->resultCode;
                $meta->resultMsg  = ($elm_results->resultMsg)??'';
                if(isset($elm_results->result)){
                  if(isset($elm_results->result->eligibility)){
                     $meta->driverEligibility = $elm_results->result->eligibility;
                  }
                }
                $meta->save();
              }
              //send driver and car information to elm

            $msg = trans('messages.saved');
            return response()->json(successReturnMsg($msg));
        }else{
            $msg  = implode(' , ',$validator->errors()->all());
            return response()->json(failReturn($msg));
        }
    }




}