<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB; 
use App\User;
use Hash;
use Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Comments;
use App\userDevices;
use App\usersCoupons;
use App\Coupons;
use App\Order;
use App\Country;
use App\chargeCards;
use App\chargeCardsUsers;
use App\userCars;
use App\captainBlockUser;
use Jenssegers\Date\Date;
use App\userAvailableTimes;
use App\userOrdersRatings;
use App\usersOrdersHistory;
use DateTime;
use App\userPaymentWays;
use App\rewardsHistory;
use App\GuaranteesHistory;
use App\savedPlaces;
use App\Points;
use App\captainMoneyHistory;
use App\Package;

class UserController extends Controller{

    public function saveCreditCard(Request $request){
        $validator   = Validator::make($request->all(),[
            'card_token'     => 'required',
        ]);
        if($validator->passes()){
            $lang = ($request->header('lang'))?? 'ar'; 
            $user = JWTAuth::parseToken()->authenticate();
            
            $userPaymentWay  = new userPaymentWays();
            $userPaymentWay->user_id    = $user->id;
            $userPaymentWay->card_token = $request->card_token;
            $userPaymentWay->save();
            
            $card_token = $request->card_token;
            $rate = ( $user->num_rating > 0 )? round(floatval($user->rating / $user->num_rating),1) : 0.0;
            $data = [
                'id'          => $user->id,
                'name'        => ($user->name)?? '',
                'phone'       => $user->phone,
                'phonekey'    => ($user->phonekey)??'',
                'email'       => ($user->email)??'',
                'birth_date'  => ($user->birth_date)??'',
                'gender'      => ($user->gender)??'male',
                'country_id'  => $user->country_id,
                'captain'     => ($user->captain)??'false',
                'avatar'      => ($user->avatar)? url('img/user/'.$user->avatar) : url('img/user/default.png'),
                'rate'        => "$rate",
                'device_id'   => '',
                'plan'        => ($user->plan)? $user->plan->{"name_$lang"}:'',
                'balance'     => number_format($user->balance ,2),                
                'balance_electronic_payment'     => number_format((float)$user->balance_electronic_payment, 2), 
                'points'      => (int) $user->points,
                'currency'    => ($user->currency)??'',               
                'active'      => ($user->active)??'pending',
                'available'   => ($user->available)?? 'false',
                'distance'   => (int)$user->distance,
                'code'        => ($user->code)?? '',
                'share_code'  => ($user->share_code)?? '',
                'pin_code'    => ($user->pin_code)?? '',
                'time_zone'   => date_default_timezone_get(),
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

    public function nearstores(Request $request){      
        $validator   = Validator::make($request->all(),[
            'lat'         => 'required',
            // 'long'        => 'required'
        ]);
        if($validator->passes()){

          $user = JWTAuth::parseToken()->authenticate();
          $lang = ($request->header('lang'))?? 'ar'; 
          $data = [] ; $places = []; $saved_places = []; $distance = 0;$next_page_token=''; $opening_hours = []; $icon = '';
          $googlekey = setting('google_places_key'); 
          // $cats      = 'cafe|restaurant|supermarket|bakery|pharmacy';
          if( ($request->lat == '') || ($request->long == '') ){
              $lat       = doubleval( 23.8859 );
              $long      = doubleval( 45.0792 );
          }else{
              $lat       = doubleval($request->lat);
              $long      = doubleval($request->long); 
              $user->lat  = $lat;
              $user->long = $long;
              $user->save();            
          }        
          $next_page_token = ($request->next_page_token == '' )?'':'&pagetoken='.$request->next_page_token;
          $url = "https://maps.googleapis.com/maps/api/place/nearbysearch/json?location=".$lat.",".$long."&rankby=distance&key=".$googlekey."&language=".$lang.$next_page_token;
          $jsonresult = file_get_contents($url);
          $results    = json_decode($jsonresult);
          if($results->results){
             foreach($results->results as $result){
                  $infav = false;
                  $icon = url('img/marker.png');
                  $distance = directDistance($lat,$long,$result->geometry->location->lat,$result->geometry->location->lng);
                  $distance = intval($distance * 1000);
                  if(insavedplaces($result->place_id,$user->id)){
                      $infav = true;
                  }else{
                      $places[] = [  'name'            => $result->name,
                                     'lat'             => $result->geometry->location->lat,
                                     'lng'             => $result->geometry->location->lng,
                                     'icon'            => $icon,
                                     'place_id'        => $result->place_id,
                                     'reference'       => $result->reference,
                                     'vicinity'        => $result->vicinity,
                                     'distance'        => $distance,
                                     'infav'           => $infav
                                ];                    
                  }                        
                                               
             }
             $next_page_token = (isset($results->next_page_token))?$results->next_page_token:'';
          }
          if($savedplaces = savedPlaces::where(['user_id'=>$user->id])->orderBy('created_at','DESC')->get()){       
                  foreach($savedplaces as $place){
                          $distance = directDistance($lat,$long,$place->lat,$place->long);
                          $distance = intval($distance * 1000);
                          // if($distance >= 1){
                          //   $distance = ($lang == 'ar')? $distance.' كم':$distance.' KM';                    
                          // }else{
                          //   $distance = ($lang == 'ar')? $distance.' م':$distance.' M';                    
                          // }
                            $saved_places[] = [
                                'id'         => $place->id,
                                'name'       => ($place->name)??'',
                                'address'    => $place->address,
                                'place_id'   => ($place->place_id)??'',
                                'lat'        => doubleval($place->lat),
                                'long'       => doubleval($place->long),
                                'distance'   => $distance,
                                'infav'      => true
                            ];
                  }
          }
          $dis = array_column($places, 'distance');
          array_multisort($dis, SORT_ASC, $places); 
          // $dis = array_column($saved_places, 'distance');
          // array_multisort($dis, SORT_ASC, $saved_places);         
          $data = ['places'=>$places,'next_page_token'=>$next_page_token,'saved_places' =>$saved_places];
          return response()->json(successReturn($data));        
        }else{
            $msg  = implode(' , ',$validator->errors()->all());
            return response()->json(failReturn($msg));
        }
    }

    public function searchStores(Request $request){
        $validator   = Validator::make($request->all(),[
            'lat'         => 'required',
            // 'long'        => 'required'
        ]);
        if($validator->passes()){
          $user = JWTAuth::parseToken()->authenticate();
          $lang = ($request->header('lang'))?? 'ar';
          $data = [] ; $places = []; $distance = 0; $next_page_token=''; $icon='';
          $googlekey = setting('google_places_key'); 
          $name = urlencode( $request->name ); 
          if( ($request->lat == '') || ($request->long == '') ){
              $lat       = doubleval( 23.8859 );
              $long      = doubleval( 45.0792 );
          }else{
              $lat       = doubleval($request->lat);
              $long      = doubleval($request->long); 
              $user->lat  = $lat;
              $user->long = $long;
              $user->save();            
          }
            $next_page_token = ($request->next_page_token == '' )?'':'&pagetoken='.$request->next_page_token;
            $url = "https://maps.googleapis.com/maps/api/place/nearbysearch/json?location=".$lat.",".$long."&name=".$name."&opennow=true&rankby=distance&key=".$googlekey."&language=".$lang.$next_page_token;
            $jsonresult = file_get_contents($url);
            $results    = json_decode($jsonresult);
            if($results->results){
               foreach($results->results as $result){
                  $infav = false;
                  $icon = url('img/marker.png');
                  $distance = directDistance($lat,$long,$result->geometry->location->lat,$result->geometry->location->lng);
                  // $distance = intval($distance);//* 1000);
                  if(insavedplaces($result->place_id,$user->id)){
                      $infav = true;
                  }else{
                      $places[] = ['name'            => $result->name,
                                   'lat'             => $result->geometry->location->lat,
                                   'lng'             => $result->geometry->location->lng,
                                   'icon'            => $icon,
                                   'place_id'        => $result->place_id,
                                   'reference'       => $result->reference,
                                   'vicinity'        => '',//$result->vicinity,
                                   'distance'        => $distance,
                                   'infav'           => $infav
                              ];
                  }
             }
             $next_page_token = (isset($results->next_page_token))?$results->next_page_token:'';
          }else{
              $url  = "https://maps.googleapis.com/maps/api/place/findplacefromtext/json?input=".$name."&inputtype=textquery&fields=place_id,reference,formatted_address,name,geometry,types,icon&key=".$googlekey."&language=".$lang.$next_page_token;
              $jsonresult = file_get_contents($url);
              $results    = json_decode($jsonresult); 
              if($results->candidates){
                 foreach($results->candidates as $result){
                      $infav = false;
                      $icon = url('img/marker.png');
                      $distance = directDistance($lat,$long,$result->geometry->location->lat,$result->geometry->location->lng);
                      // $distance = intval($distance );//* 1000);
                      if(insavedplaces($result->place_id,$user->id)){
                          $infav = true;
                      }else{
                          $places[] = ['name'            => $result->name,
                                       'lat'             => $result->geometry->location->lat,
                                       'lng'             => $result->geometry->location->lng,
                                       'icon'            => $icon,
                                       'place_id'        => $result->place_id,
                                       'reference'       => $result->reference,
                                       'vicinity'        => '',//$result->vicinity,
                                       'distance'        => $distance,
                                       'infav'           => $infav
                                  ];
                      }
                 }
                 $next_page_token = (isset($results->next_page_token))?$results->next_page_token:'';
              }  
          } 
          $dis = array_column($places, 'distance');
          array_multisort($dis, SORT_ASC, $places);       
          $data = ['places' => $places];
          return response()->json(successReturn($data));        
        }else{
            $msg  = implode(' , ',$validator->errors()->all());
            return response()->json(failReturn($msg));
        }
    }

    public function savePlace(Request $request){
         $validator = Validator::make($request->all(), [
            'name'      => 'required',
            'place_id'  => 'nullable',
            'address'   => 'required',
            'lat'       => 'required',
            'long'      => 'required'
        ]);
        if ($validator->passes()){
            $user = JWTAuth::parseToken()->authenticate();
            $lang = $request->header('lang');
            if($place = savedPlaces::where(['user_id'=>$user->id,'lat' => doubleval( $request->lat ) , 'long' => doubleval( $request->long ) ])->first()){       
                 $place->delete();
                 $msg = trans('user.removeSuccess');
                 return response()->json(successReturnMsg($msg));           
            }else{
                $savedplace = new savedPlaces();
                $savedplace->user_id    = $user->id;
                $savedplace->name       = $request->name;
                $savedplace->place_id   = $request->place_id;
                $savedplace->address    = $request->address;
                $savedplace->lat        = doubleval( $request->lat );
                $savedplace->long       = doubleval( $request->long ); 
                $savedplace->save();
                $msg = trans('user.saveplacesuccess');
                return response()->json(successReturnMsg($msg)); 
            }       
        }else{
            $msg = implode(',',$validator->errors()->all());
            return response()->json(failReturn($msg)); 
        }
    }

    public function savedPlaces(Request $request){
      $user = JWTAuth::parseToken()->authenticate();
      $data = [];
      if($places = savedPlaces::where(['user_id'=>$user->id])->orderBy('created_at','DESC')->get()){       
            foreach($places as $place){
                      $data[] = [
                          'id'         => $place->id,
                          'name'       => ($place->name)??'',
                          'place_id'   => ($place->place_id)??'',
                          'address'    => $place->address,
                          'lat'        => doubleval($place->lat),
                          'long'       => doubleval($place->long)
                      ];
            }
            return response()->json(successReturn($data));                
      }
      $msg = trans('user.no_saved_places');
      return response()->json(failReturn($msg)); 
    }

    public function deleteSavedplace(Request $request){
      $user  = JWTAuth::parseToken()->authenticate();
      if($place = savedPlaces::where(['user_id'=>$user->id,'id'=>$request->id])->first()){
         $place->delete();
         $msg = trans('user.removeSuccess');
         return response()->json(successReturnMsg($msg));
      } 
        $msg = trans('user.no_saved_place');
        return response()->json(failReturn($msg));     
    }

    public function myProfile(Request $request){
           if($profile = JWTAuth::parseToken()->authenticate() ){
            $lang = $request->header('lang');
            $rate = ( $profile->num_rating > 0 )? round(floatval($profile->rating / $profile->num_rating),1) : 0.0;
            $profile->balance            = round(floatval($profile->balance),2);
            $profile->save();
                $card_token = '';
                if($uservisa = userPaymentWays::where('user_id','=',$profile->id)->first()){
                   $card_token = ($uservisa->card_token)??'';
                }
                $data = [
                    'id'          => $profile->id,
                    'name'        => ($profile->name)?? '',
                    'phone'       => $profile->phone,
                    'phonekey'    => ($profile->phonekey)??'',
                    'email'       => ($profile->email)??'',
                    'birth_date'  => ($profile->birth_date)??'',
                    'gender'      => ($profile->gender)??'male',
                    'country_id'  => $profile->country_id,
                    'captain'     => $profile->captain,
                    'avatar'      => ($profile->avatar)? url('img/user/'.$profile->avatar) : url('img/user/default.png'),
                    'rate'        => "$rate",
                    'device_id'   => '',
                    'plan'        => ($profile->plan)? $profile->plan->{"name_$lang"}:'',
                    'balance'     => number_format($profile->balance ,2),                             
                    'balance_electronic_payment'     => number_format((float)$profile->balance_electronic_payment, 2), 
                    'points'      => (int) $profile->points,
                    'currency'    => ($profile->currency)??'',               
                    'active'      => ($profile->active)??'pending',
                    'available'   => ($profile->available == 'true' && (int)$profile->balance > (-1 * setting('max_debt_captain')))? 'true':'false',
                    'distance'   => (int)$profile->distance,
                    'min_distance'   => (int)setting('min_distance'),
                    'max_distance'   => (int)setting('max_distance'),
                    'code'        => ($profile->code)?? '',
                    'share_code'  => ($profile->share_code)?? '',
                    'pin_code'    => ($profile->pin_code)?? '',
                    'time_zone'   => date_default_timezone_get(),
                    'card_token'  => $card_token,
                    'need_recharge' => (int) $profile->balance <= (-1 * setting('max_debt_captain')) ? true : false,
                    'payment_page_background' => url('dashboard/uploads/setting/site_logo/'.setting('payment_page_background'))
                ];
                $data['token']     = JWTAuth::fromUser($profile);
                $data['googlekey'] = setting('google_places_key');            
              return response()->json(successReturn($data));
           }
    }

    public function myPoints(Request $request){
          if($user = JWTAuth::parseToken()->authenticate() ){
            $data['points'] = intval( $user->points );
          }
          return response()->json(successReturn($data));
    }
    
    public function inviteClientBalance(Request $request){
          $share_code = '';
          $lang = $request->header('lang');
          if($user = JWTAuth::parseToken()->authenticate() ){
            $share_code =  $user->share_code;
          }
          $data['share_code']            = $share_code;
          $data['invite_client_balance'] = setting('invite_client_balance');
          $data['currency']              = setting('site_currency_'.$lang);
          return response()->json(successReturn($data));
    }    
    
    public function editProfile(Request $request){
        // $validator        = Validator::make($request->all(),[
            // 'name'  => 'required|min:3|max:20',
            // 'email'       => 'required|email',
            // 'avatar'      => 'image|mimes:jpeg,png,jpg,gif,svg',
        // ]);

        // if($validator->passes()){
            $user = JWTAuth::parseToken()->authenticate();
            $lang = $request->header('lang');
            if($request->name){
              $user->name     = $request->name;
            }
            if($request->phone){
              $number         = convert2english(request('phone'));
              $phone          = phoneValidate($number);
              if($checkphone = User::where(['phone'=>$phone])->first()){
                if($checkphone->id != $user->id){
                  $msg = trans('user.phoneexists');
                  return response()->json(failReturn($msg));
                }else{
                  $user->phone = $phone;
                }
              }else{
                $user->phone = $phone;
              }
            }  
            if($request->email){
              if($checkmail = User::where(['email' => $request->email])->first()){
                if($checkmail->id != $user->id){
                  $msg = trans('user.emailexists');
                  return response()->json(failReturn($msg));
                }else{
                  $user->email = $request->email;
                }
              }else{
                $user->email = $request->email;
              }
            }
            if($request->birth_date){
              $user->birth_date = $request->birth_date;
            }
            if($request->gender){
              $user->gender     = $request->gender;
            }
            if($request->hasFile('avatar')) {
                $image           = $request->file('avatar');
                $name            = md5($request->file('avatar')->getClientOriginalName()).time().rand(99999,1000000).'.'.$image->getClientOriginalExtension();
                $destinationPath = public_path('/img/user');
                $imagePath       = $destinationPath. "/".  $name;
                $image->move($destinationPath, $name);
                $user->avatar    = $name;
            }
            $user->save();
            $card_token = '';
            if($uservisa = userPaymentWays::where('user_id','=',$user->id)->first()){
               $card_token = ($uservisa->card_token)??'';
            }
            $rate = ( $user->num_rating > 0 )? round(floatval($user->rating / $user->num_rating),1) : 0.0;
            $data = [
                'id'                  => $user->id,
                'name'                => ($user->name)?? '',
                'phone'               => $user->phone,
                'phonekey'            => ($user->phonekey)??'',
                'gender'              => ($user->gender)??'male',
                'country_id'          => $user->country_id,
                'birth_date'          => ($user->birth_date)??'',                
                'email'               => ($user->email)??'',                
                'captain'             => $user->captain,
                'avatar'              => ($user->avatar)? url('img/user/'.$user->avatar) : url('img/user/default.png'),
                'rate'                => "$rate",
                'device_id'           => '',
                'plan'                => ($user->plan)? $user->plan->{"name_$lang"}:'',
                'balance'             => number_format($user->balance ,2),
                'balance_electronic_payment'     => number_format((float)$user->balance_electronic_payment, 2), 
                'points'              => (int) $user->points,
                'currency'            => ($user->currency)??'',
                'num_orders'          => floatval( $user->num_done_orders ),
                'num_comments'        => floatval( $user->num_comments ),
                'active'              => ($user->active)??'pending',
                'available'           => ($user->available)?? 'false',
                'distance'   => (int)$user->distance,
                'code'                => ($user->code)??'',
                'pin_code'            => ($user->pin_code)??'',
                'time_zone'           => date_default_timezone_get(),
                'card_token'          => $card_token,
                'need_recharge' => (int) $user->balance <= (-1 * setting('max_debt_captain')) ? true : false,
                'payment_page_background' => url('dashboard/uploads/setting/site_logo/'.setting('payment_page_background'))
              ];
                $data['token']     = JWTAuth::fromUser($user);
                $data['googlekey'] = setting('google_places_key');   
                         
            return response()->json(successReturn($data));
        // }else{
        //       $msg   = implode(' , ',$validator->errors()->all());
        //       return response()->json(failReturn($msg));
        // }
    }

    public function CaptainPerformance(Request $request){
            $user  = JWTAuth::parseToken()->authenticate();
            $lang  = $request->header('lang');
            $was_available = 'false';
            if($user->available == 'true'){
              $was_available   = 'true';
              $user->available = 'false';
              if($userAvailableTimes = userAvailableTimes::where('user_id','=',$user->id)->where('to','=',null)->orderBy('created_at','DESC')->first()){
                 $userAvailableTimes->to = date('Y-m-d H:i:s');
                 $userAvailableTimes->save();
                 $from_time  = strtotime( $userAvailableTimes->from );
                 $to_time    = strtotime( $userAvailableTimes->to );
                 $minutes    = intval( ($to_time - $from_time) / 60);
                 $userAvailableTimes->num_minutes += $minutes;
                 $userAvailableTimes->save();
                 if($allusertimes = userAvailableTimes::where('user_id','=',$user->id)->get()){
                    foreach($allusertimes as $usertime){
                      $from_time  = strtotime( $usertime->from );
                      $to_time    = strtotime( $usertime->to );
                      $minutes    = intval( ($to_time - $from_time) / 60);
                      $user->num_available_minutes += $minutes;
                      $user->save(); 
                    }
                  }
              }
              $user->save();
            }

            $page  = ($request->page)?? 1;
            $start_day    = setting('start_day');
            $previous_day = date('l',strtotime("-1 day $start_day")); 
            if($page == 1){
              if(date('l') == $start_day){
                $start_date = date('Y-m-d H:i:s',strtotime('today 00:00:00'));
                $end_date   = date('Y-m-d H:i:s'); 
              }else{
                $start_date = date('Y-m-d H:i:s',strtotime("-1 $start_day 00:00:00"));
                $end_date   = date('Y-m-d H:i:s');  
              }
            }else{
              if(date('l') == $start_day){
                $start_date = date('Y-m-d H:i:s',strtotime('-'.($page-1)." $start_day 00:00:00"));
                $end_date   = date('Y-m-d H:i:s',strtotime('-'.($page-1)." $previous_day 23:59:59 "));
              }elseif(date('l') == $previous_day){
                $start_date = date('Y-m-d H:i:s',strtotime('-'.$page." $start_day 00:00:00"));
                $end_date   = date('Y-m-d H:i:s',strtotime('-'.($page-1)." $previous_day 23:59:59 "));
              }else{
                $start_date = date('Y-m-d H:i:s',strtotime('-'.$page." $start_day 00:00:00"));
                $end_date   = date('Y-m-d H:i:s',strtotime('-'.($page-1)." $previous_day 23:59:59 "));                
              }
            }
            $captainMoneystatus = 'pending';
            if($captainMoney = captainMoneyHistory::where(['captain_id'=>$user->id,'start_date'=>$start_date])->first()){
               $captainMoneystatus = 'finished';
            }          
            /*start rate in that week*/
            $dataRatings = [];$totalRate = 0.0;
            $weekratings = userOrdersRatings::select('*', DB::raw('SUM(rate) as rate_sum ,COUNT(*) as count_ratings'))->where('user_id','=',$user->id)->where('created_at','>=',$start_date)->where('created_at','<=',$end_date)->first();
            if($weekratings){
              $totalRate = ($weekratings->rate_sum != 0)? $weekratings->rate_sum / $weekratings->count_ratings : 0.0;
            }
            if($ratings = userOrdersRatings::select('*', DB::raw('AVG(rate) as rate_average, COUNT(*) as count_ratings'))->where('user_id','=',$user->id)->where('created_at','>=',$start_date)->where('created_at','<=',$end_date)->groupBy('date')->orderBy('date','ASC')->get()){
                foreach($ratings as $rating){
                   $dataRatings[] = ['date'          => date('d - m',strtotime($rating->date)),
                                     'count_ratings' => $rating->count_ratings,
                                     'rate_average'  => $rating->rate_average
                                    ];
                }
            }
            /*end rate*/

            
            /*start available hours in that week*/
            $dataAvailabelHours  = []; $totalAvailableHours= '0';
            $weekAvailableHours = userAvailableTimes::select('*', DB::raw('SUM(num_minutes) as total_minutes'))->where('user_id','=',$user->id)->where('created_at','>=',$start_date)->where('created_at','<=',$end_date)->first();
            if($weekAvailableHours){
              $totalAvailableHours = (convertToHoursMins($weekAvailableHours->total_minutes))?? '0';
            }
            if($availableHours = userAvailableTimes::select('*', DB::raw('SUM(num_minutes) as total_minutes'))->where('user_id','=',$user->id)->where('created_at','>=',$start_date)->where('created_at','<=',$end_date)->groupBy('date')->orderBy('created_at','ASC')->get()){
                foreach($availableHours as $availableHour){
                   $dataAvailabelHours[] = ['date'          => date('d - m',strtotime($availableHour->date)),
                                            'total_minutes' => (convertToHoursMins($availableHour->total_minutes))??''
                                           ];
                }
            }
            /*end available hours*/

            /*start accept rate in that week*/
            $dataAcceptance = [];$totalAccept='';$openedorders = 0;$finishedorders=0;$count_finished_orders=0;
            $weekopened    = usersOrdersHistory::select('*', DB::raw("COUNT(*) as allorders"))->where('captain_id','=',$user->id)->where('created_at','>=',$start_date)->where('created_at','<=',$end_date)->first();
            $weekaccepted  = usersOrdersHistory::select('*', DB::raw("COUNT(*) as finishedorders"))->where('captain_id','=',$user->id)->where('status','=','finished')->where('created_at','>=',$start_date)->where('created_at','<=',$end_date)->first();
            if($weekopened){
              $openedorders  = ($weekopened->allorders)?? 0; 
              $finishedorders= ($weekaccepted->finishedorders)?? 0; 
            }
            $totalAccept   = ( $finishedorders == 0 )? '0%' : round( ( $finishedorders / $openedorders ) * 100 ,1).'%';
            
            if($userorders = DB::table('users_orders_history')->select('*', DB::raw("COUNT(*) as count_orders"))->where('captain_id','=',$user->id)->where('created_at','>=',$start_date)->where('created_at','<=',$end_date)->groupBy('date')->orderBy('created_at','ASC')->get() ){
                foreach($userorders as $userorder){
                   $count_finished_orders = getFinishedOrdersByDate($userorder->date);
                   $dataAcceptance[] = [ 'date'            => date('d - m',strtotime($userorder->date)),
                                         'count_orders'    => $userorder->count_orders,
                                         'finished_orders' => $count_finished_orders ,
                                         'percentage'      => ($count_finished_orders == 0 )? '0%' : round( ( $count_finished_orders / $userorder->count_orders ) * 100 ,1).'%',
                                        ];
                }
            }
            /*end accept rate hours*/            
            
            $dataOrders = [];$num_orders = 0;$totalPrices_of_orders = 0.0;
            $num_cash_orders = 0; $totalPrices_of_cash_orders = 0.0;
            /*start done orders*/
            if($orders = Order::where(['captain_id'=>$user->id,'status'=>'finished'])->where('created_at','>=',$start_date)->where('created_at','<=',$end_date)->orderBy('created_at','DESC')->get()){
                foreach($orders as $order){
                     $num_orders += 1;
                     $totalPrices_of_orders += floatval($order->price);
                     //calculate total cash captain take from clients
                     if($order->payment_type == 'cash'){
                        $num_cash_orders +=1;
                        $totalPrices_of_cash_orders += floatval($order->total_payments);
                     }
                     if($lang == 'ar'){
                       $created_at = Date::parse($order->created_at)->format('l j F h:i ');
                       $created_at .= trans('order.'.date('a',strtotime($order->created_at)));
                     }else{
                       $created_at = date('l j F h:i ',strtotime($order->created_at));
                       $created_at .= trans('order.'.date('a',strtotime($order->created_at)));
                     }
                     $dataOrders[] = [ 'id'       => $order->id ,
                                       'price'    => round(floatval($order->price),2),
                                       'currency' => ($order->{"currency_$lang"})?? setting('site_currency_'.$lang),
                                       'date'     => $created_at
                                     ];
                }
            }
            /*end done orders*/
            /*start withdraw orders of captain negative*/
            $num_withdraw_orders = 0; $totalPrices_of_withdraw_orders = 0.0;
            if($withdrawOrders = usersOrdersHistory::where(['captain_id'=>$user->id,'status'=>'withdraw'])->where('created_at','>=',$start_date)->where('created_at','<=',$end_date)->get()){
              foreach($withdrawOrders as $withdrawOrder) {
                     $num_withdraw_orders += 1;
                     $totalPrices_of_withdraw_orders += floatval($withdrawOrder->price); 
              }
            }
            /*end withdraw orders of captain negative*/
            /*start client closed orders added to captain positive*/
            $num_closed_orders = 0; $totalPrices_of_closed_orders = 0.0;
            if($closedOrders = usersOrdersHistory::where(['captain_id'=>$user->id,'status'=>'closed'])->where('created_at','>=',$start_date)->where('created_at','<=',$end_date)->get()){
              foreach($closedOrders as $closedOrder) {
                     $num_closed_orders += 1;
                     $totalPrices_of_closed_orders += floatval($closedOrder->price); 
              }
            }
            /*end client closed orders added to captain positive*/

            /*start rewards*/
            $num_rewards_orders = 0;
            $totalPrices_of_rewards_orders = 0.0;
            if($rewardsOrders = rewardsHistory::where('user_id','=',$user->id)->where('created_at','>=',$start_date)->where('created_at','<=',$end_date)->get()){
              foreach($rewardsOrders as $rewardsOrder) {
                     $num_rewards_orders += 1;
                     $totalPrices_of_rewards_orders += floatval($rewardsOrder->points); 
              }
            }            
            /*end rewards*/       
            /*start guarantees*/
            $num_guarantee_orders = 0;
            $totalPrices_of_guarantee_orders = 0.0;
            if($guaranteeOrders = GuaranteesHistory::where('user_id','=',$user->id)->where('created_at','>=',$start_date)->where('created_at','<=',$end_date)->get()){
              foreach($guaranteeOrders as $guaranteeOrder) {
                     $num_guarantee_orders += 1;
                     $totalPrices_of_guarantee_orders += floatval($guaranteeOrder->guarantee); 
              }
            }            
            /*end guarantees*/
            //all money of captain orders after remove site percentage
            $totalPrices_of_orders          = $totalPrices_of_orders - ( (setting('site_percentage') / 100) * $totalPrices_of_orders );
            $totalPrices_of_withdraw_orders = ($totalPrices_of_withdraw_orders * -1 ) + $totalPrices_of_closed_orders ;
            $num_withdraw_orders            = $num_withdraw_orders + $num_closed_orders;
            $totalPrices_of_cash_orders     = $totalPrices_of_cash_orders     * -1 ;
            $total = $totalPrices_of_orders + $totalPrices_of_rewards_orders + $totalPrices_of_guarantee_orders + $totalPrices_of_withdraw_orders + $totalPrices_of_cash_orders;
            
            $data = ['num_orders'                     => $num_orders,
                     'totalPrices_of_orders'          => round(floatval($totalPrices_of_orders),2) ,
                     'num_withdraw_orders'            => $num_withdraw_orders,
                     'totalPrices_of_withdraw_orders' => round(floatval($totalPrices_of_withdraw_orders),2),
                     'num_rewards_orders'             => $num_rewards_orders,
                     'totalPrices_of_rewards_orders'  => round(floatval($totalPrices_of_rewards_orders),2),
                     'num_guarantee_orders'           => $num_guarantee_orders,
                     'totalPrices_of_guarantee_orders'=> round(floatval($totalPrices_of_guarantee_orders),2),
                     'num_cash_orders'                => $num_cash_orders,
                     'totalPrices_of_cash_orders'     => round(floatval($totalPrices_of_cash_orders),2),
                     'total'                          => round(floatval($total),2),
                     'currency'                       => ($user->country)?$user->country->{"currency_$lang"}:setting('site_currency_'.$lang),
                     'orders'                         => $dataOrders,
                     'totalrate'                      => round(floatval($totalRate),2),
                     'ratings'                        => $dataRatings,
                     'totalAvailableHours'            => $totalAvailableHours,
                     'AvailabelHours'                 => $dataAvailabelHours, 
                     'totalAccept'                    => $totalAccept,
                     'dataAcceptance'                 => $dataAcceptance,
                     'captainMoneystatus'             => $captainMoneystatus,
                     'start_date'                     => ($lang == 'ar')? Date::parse($start_date)->format('Y j F') : date('j F Y',strtotime($start_date)),
                     'end_date'                       => ($lang == 'ar')? Date::parse($end_date)->format('Y j F') : date('j F Y',strtotime($end_date)),
                     'date'                           => ($lang == 'ar')? Date::parse($start_date)->format('Y j F').' - '.Date::parse($end_date)->format('Y j F') : date('j F Y',strtotime($start_date)).' - '.date('j F Y',strtotime($end_date)),
                     'page'                           => intval($page)
                    ]; 
                    if($was_available == 'true'){
                      $user->available = 'true';
                      $user->save();                                      
                      $userAvailableTimes          = new userAvailableTimes();
                      $userAvailableTimes->user_id = $user->id;
                      $userAvailableTimes->from    = date('Y-m-d H:i:s');
                      $userAvailableTimes->date    = date('Y-m-d');
                      $userAvailableTimes->month   = date('Y-m');
                      $userAvailableTimes->save();          
                    }
            return response()->json(successReturn($data));
    }

    public function changeAvailable(){
      $data = [];
      if($user = JWTAuth::parseToken()->authenticate()){
        if($user->available == 'true'){
          $user->available = 'false';
          if($userAvailableTimes = userAvailableTimes::where('user_id','=',$user->id)->where('to','=',null)->orderBy('created_at','DESC')->first()){
             $userAvailableTimes->to = date('Y-m-d H:i:s');
             $userAvailableTimes->save();
             $from_time  = strtotime( $userAvailableTimes->from );
             $to_time    = strtotime( $userAvailableTimes->to );
             $minutes    = intval( ($to_time - $from_time) / 60);
             $userAvailableTimes->num_minutes += $minutes;
             $userAvailableTimes->save();
             if($allusertimes = userAvailableTimes::where('user_id','=',$user->id)->get()){
                foreach($allusertimes as $usertime){
                  $from_time  = strtotime( $usertime->from );
                  $to_time    = strtotime( $usertime->to );
                  $minutes    = intval( ($to_time - $from_time) / 60);
                  $user->num_available_minutes += $minutes;
                  $user->save(); 
                }
              }
          }
          $msg = trans('user.notavailable');
        }else{
          $user->available = 'true';
          $userAvailableTimes          = new userAvailableTimes();
          $userAvailableTimes->user_id = $user->id;
          $userAvailableTimes->from    = date('Y-m-d H:i:s');
          $userAvailableTimes->date    = date('Y-m-d');
          $userAvailableTimes->month   = date('Y-m');
          $userAvailableTimes->save();          
          $msg = trans('user.available');
        }
        $user->save();
        $data = ['msg' => $msg, 'available' => ($user->available == 'true' && (int)$user->balance > (-1 * setting('max_debt_captain')))? 'true':'false','distance' => (int)$user->distance];
      }
      return response()->json(successReturn($data));
    }

    public function useBalanceFirst(Request $request){
      $data = [];
      if($user = JWTAuth::parseToken()->authenticate()){
        if($request->use_balance_first == 'true'){
          $user->use_balance_first = 'true';        
        }else{
          $user->use_balance_first = 'false';        
        }
        $user->save();
        $data['use_balance_first'] = $user->use_balance_first;
      }
      return response()->json(successReturn($data));
    }

    public function updateDistance(Request $request){
      $data = [];
      if($user = JWTAuth::parseToken()->authenticate()){
        $user->distance = (int)$request->distance;        
        $user->save();
        $data['distance'] = $user->distance;
      }
      return response()->json(successReturn($data));
    }

    public function years(){
        $start_year = "2010";
        for ($nowYear = $start_year; $nowYear <= date('Y'); $nowYear++) {
            $years[]  = "$nowYear";
        }
        $data = ['years' => $years , 'current_year' => date('Y'),'current_month' => date('n')];
        return response()->json(successReturn($data));
    }

    public function userArchive(Request $request){
        $user  = JWTAuth::parseToken()->authenticate();
        $year  = ($request->year)?? $year = date('Y');
        $month = ($request->month)?? $month = date('n');
        $lang  = $request->header('lang');
        $orders = Order::where(['user_id'=>$user->id,'year'=>$year,'month'=>$month,'status'=>'finished'])->orderBy('created_at','ASC')->get();
        $data = []; $avatar = url('img/user/default.png') ; $name= '';
          if(count($orders) > 0){
            foreach($orders as $order) {
              if($captain = User::find($order->captain_id)){
                $avatar = ($captain->avatar)? url('img/user/'.$captain->avatar) : url('img/user/default.png');
                $name   = $captain->name.' '.$captain->name;
              }
              $data[] = ['id'            => $order->id,
                         'order_type'    => ($order->order_type)??'trip',
                         'place_id'      => ($order->place_id)??'',
                         'place_ref'     => ($order->place_ref)??'',
                         'place_name'    => ($order->place_name)??'',
                         'icon'          => url('img/icons/restaurant.png'),   
                         'avatar'        => $avatar,
                         'name'          => $name,
                         'can_update'       => false,
                         'later'            => false,
                         'cartype'          => ($order->cartype)? $order->cartype->{"name_$lang"}:'',
                         'payment_type'     => ($order->payment_type)??'',
                         'later_order_date' => ($order->later_order_date)?? '', 
                         'later_order_time' => ($order->later_order_time)?? '',                          
                         'start_lat'     => "$order->start_lat",
                         'start_long'    => "$order->start_long",
                         'start_address' => ($order->start_address)?? '',
                         'end_lat'       => "$order->end_lat",
                         'end_long'      => "$order->end_long",
                         'end_address'   => ($order->end_address)?? '',
                         'price'         => ($order->price)??'',
                         'currency'      => ($order->{"currency_$lang"})?? setting('site_currency_'.$lang),
                         'month'         => $order->month,
                         'date'          => ($lang == 'ar')? Date::parse($order->created_at)->format('F j'): date('F j',strtotime($order->created_at)),
                         'time'          => Date::parse($order->created_at)->format('h:i ').trans('order.'.date('a'))
                        ];
            }
        }
      return response()->json(successReturn($data));
    }

/* Start Money transfer */
    public function YourBalance(Request $request){
        $lang = $request->header('lang')??'ar';
        $user = JWTAuth::parseToken()->authenticate();
        $currency = ($user->country)? $user->country->{"currency_$lang"} : setting("site_currency_$lang");
        $currentBalance = '';
        // if($user->country_id != $user->current_country_id){
        //   $newcurrency = ($user->currentCountry)? $user->currentCountry->{"currency_$lang"} : setting("site_currency_$lang");
        //   $currentBalance = ' = '.intval($user->balance * $request->exchangeRate).' '.$newcurrency;
        // }
        $balance = number_format(floatval($user->balance), 2);//.$currentBalance;
        $use_balance_first = ($user->use_balance_first == 'true')? 'true':'false';
        $c = [];
        $countries = Country::orderBy("iso2",'ASC')->get();
        foreach($countries as $country){
                $c[] = ['id'   => $country->id,
                        'iso'  => $country->iso2,
                        'key'  => ($country->phonekey == null)?'':$country->phonekey,
                        'name' => ($country->{"name_$lang"} == null)? '': $country->{"name_$lang"},
                        'currency'  => $country->{"currency_$lang"}
                       ];
        }
        $curCountry = currentCountry();
        $iso = ($curCountry['iso'] == null)? '': $curCountry['iso'];
        $data = ['balance'                    => $balance,
                 'balance_electronic_payment' => number_format((float)$user->balance_electronic_payment, 2), 
                 'currency'                   => $currency,
                 'use_balance_first'          => $use_balance_first,
                 'points'                     => (int) $user->points,
                 'countries'                  => $c ,
                 'currentCountry'             => $iso
                ];
       return response()->json(successReturn($data));
    }

    // public function transferMoney(Request $request){
    //     $validator = Validator::make($request->all(), [
    //         'phone'           => 'required',
    //         'transfer_amount' => 'required',
    //         'country_id'      => 'required'
    //     ]);
    //     if ($validator->passes()){
    //         $user = JWTAuth::parseToken()->authenticate();
    //         $lang = $request->header('lang');
    //         $number = convert2english($request->phone);
    //         $phone  = phoneValidate($number);            
    //         if($seconduser = User::where('phone','=',$phone)->first()){
    //             if($user->id == $seconduser->id){
    //                 $msg = trans('user.notallowedtoyourself');
    //                 return response()->json(failReturn($msg));
    //             }                
    //             $amount  = intval( $request->transfer_amount );
    //             $country = Country::find($request->country_id);
    //             $f_currency             = ($user->country)? $user->country->currency_en:setting('site_currency_en');
    //             $transfer_currency      = ($country)? $country->currency_en:setting('site_currency_en'); 
    //             $transfer_currency_ar   = ($country)? $country->currency_ar:setting('site_currency_ar');               
    //             $firstUserExchangeRate  = convertCurrency(1,$f_currency,$transfer_currency);
    //             $s_currency             = ($seconduser->country)? $seconduser->country->currency_en:setting('site_currency_en');
    //             $secondUserExchangeRate = convertCurrency(1,$transfer_currency,$s_currency);
    //             if( (intval($request->transfer_amount) > intval($user->balance * $firstUserExchangeRate ) ) || ( (intval($request->transfer_amount) <= 0 ) ) ){
    //                 $msg = trans('user.muchamount');
    //                 return response()->json(failReturn($msg));
    //             } 
    //             $user->balance       -= round( floatval($amount / $firstUserExchangeRate) ,2);
    //             $user->save();
    //             $seconduser->balance += round( floatval($amount * $secondUserExchangeRate),2);
    //             $seconduser->save();
        
    //             $payment_id = savePayment($user->id,$seconduser->id,$amount,'subtract','balance_transfer','finished',$request->country_id,'balance');

    //             notify($seconduser->id,$user->id,'user.transferBalanceTitle','user.transferBalance',"country_id:".$request->country_id.":amount:".$amount.":payment_id:".$payment_id,'','balance_transfer');         
    //             $devices = userDevices::where(['user_id'=>$seconduser->id])->get();
    //             // #use FCM or One Signal Here :) 
    //             $notify_title   = setting('site_title');
    //             $notify_message = trans('user.transferBalance',['name'=>$user->name,'amount'=>$amount.' '.$transfer_currency]);
    //             $message_ar     = 'قام '.$user->name.' بتحويل '.$amount.' '.$transfer_currency_ar.' اليك';
    //             $message_en     = $user->name.' sent '.$amount.' '.$transfer_currency.' to you.';
    //             $data = ['title' => $notify_title,'message_en'=>$message_en,'message_ar'=>$message_ar,'payment_id'=>$payment_id,'key'=>'balance_transfer'];
    //             sendNotification($devices, $notify_message,$notify_title,$data);                

    //             $msg = trans('user.transfersuccess');
    //           return response()->json(successReturnMsg($msg));
    //         }else{
    //           $msg = trans('user.user_notfound');
    //           return response()->json(failReturn($msg));
    //         }
    //     }else{
    //         $msg = implode(',',$validator->errors()->all());
    //         return response()->json(failReturn($msg)); 
    //     }
    // }
  
/* End Money transfer*/
    // public function replacePoints(Request $request){
    //   $user = JWTAuth::parseToken()->authenticate();
    //   $lang = $request->header('lang');
    //   if($uppoints = Points::where('points','<=',$user->points)->orderBy('points','DESC')->first()){
    //      $user->points -= $uppoints->points;
    //      $user->balance    += round($uppoints->amount,2);
    //      $user->save();
    //      $currency = ($user->country)? $user->country->{"currency_$lang"}:setting("site_currency_$lang");
    //      $msg = trans('user.replacepointsSuccess',['amount'=>$uppoints->amount.' '.$currency]);
    //      return response()->json(successReturnMsg($msg));
    //   }
    //   $msg = trans('user.notenoughpoints');
    //   return response()->json(failReturn($msg));
    // }
    
    public function addCoupon(Request $request){
        $validator       = Validator::make($request->all(),[
            'coupon'       => 'required',
        ]);
        if($validator->passes()){ 
          $coupon_code = convert2english($request->coupon);
          if($coupon = Coupons::where(['code' => $coupon_code])->first()){
             if($coupon->num_to_use <= $coupon->num_used){
               $msg = trans('user.not_valid');
               return response()->json(failReturn($msg));
             }
            if( strtotime($coupon->end_at) < strtotime('now') ){   
               $msg = trans('user.not_valid');
               return response()->json(failReturn($msg));
            }
            if((int)$coupon->total_cost >= (int)$coupon->budget){
              $msg = trans('user.not_valid');
              return response()->json(failReturn($msg));
            }
            if(usersCoupons::where(['user_id'=>$user->id,'coupon_id' => $coupon->id])->count() < $coupon->num_to_use_person){
              $usercoupon = new usersCoupons();     
              $usercoupon->coupon_id = $coupon->id;
              $usercoupon->user_id   = $user->id;
              $usercoupon->end_at    = $coupon->end_at;
              $usercoupon->save();
              $coupon->num_used      += 1;
              $coupon->save();
            }else{
              $msg = trans('user.used_coupon_before');
              return response()->json(failReturn($msg));
            }
            // $user = JWTAuth::parseToken()->authenticate();
            // if($usercoupon = usersCoupons::where(['user_id'=>$user->id,'used'=>'false'])->where('end_at','>=',date('Y-m-d'))->first()){
            //    $msg = trans('user.have_unused_coupon');
            //    return response()->json(failReturn($msg));
            // }else{
            //   $usercoupon = new usersCoupons();     
            //   $usercoupon->coupon_id = $coupon->id;
            //   $usercoupon->user_id   = $user->id;
            //   $usercoupon->end_at    = $coupon->end_at;
            //   $usercoupon->save();
            //   $coupon->num_used      += 1;
            //   $coupon->save();
              $msg = trans('user.coupon_success');    
              return response()->json(successReturnMsg($msg));   
            // }       
          } 
          $msg = trans('user.error_coupon');    
          return response()->json(failReturn($msg));
        }else{
            $msg  = implode(' , ',$validator->errors()->all());
            return response()->json(failReturn($msg));
        }
    }

    public function addChargeCard(Request $request){
        $validator       = Validator::make($request->all(),[
            'code'       => 'required',
        ]);
        if($validator->passes()){ 
            $user = JWTAuth::parseToken()->authenticate();
            $lang = $request->header('lang');
            if($card = chargeCards::where(['code'=>$request->code])->first()){
                $user->balance += round(floatval( $card->value ) ,2);
                $user->save();
                $usedcard = new chargeCardsUsers();
                $usedcard->user_id = $user->id;
                $usedcard->code    = $card->code;
                $usedcard->value   = $card->value;
                $usedcard->save();
                $card->delete();
                $currency = ($user->country)? $user->country->{"currency_$lang"} : setting("site_currency_$lang");
                $amount   = $card->value.' '.$currency;
                $msg = trans('user.charge_card_success',['amount' => $amount]);    
                return response()->json(successReturnMsg($msg));
          } 
          $msg = trans('user.error_coupon');    
          return response()->json(failReturn($msg));
        }else{
            $msg  = implode(' , ',$validator->errors()->all());
            return response()->json(failReturn($msg));
        }
    }
    
    public function ratingUser(Request $request){
        $validator = Validator::make($request->all(),[
            'user_id'       => 'required',
            'rating'        => 'required',
            'is_client'     => 'nullable',
            'comment'       => 'nullable',
            // 'block_client'  => 'nullable',
            // 'block_captain' => 'nullable'
        ]);
        if($validator->passes()){
          $user = JWTAuth::parseToken()->authenticate();
            if($user->id == $request->user_id){
                $msg = trans('user.rating_youself');
                return response()->json(failReturn($msg)); 
            }
            if($anotheruser = User::find($request->user_id)){
                if($request->comment){
                    $anotheruser->num_comments += 1;
                    $comment = new Comments();
                    $comment->user_id    = $user->id;
                    $comment->profile_id = $anotheruser->id;
                    $comment->rate       = $request->rating;
                    $comment->comment    = e( $request->comment );
                    $comment->date       = date('Y-m-d');
                    $comment->month      = date('Y-m');
                    $comment->save();
                }
                $anotheruser->num_rating += 1;
                $anotheruser->rating     += $request->rating;
                $anotheruser->save();
                
                if($request->is_client == 'true'){
                  $userOrdersRatings          = new userOrdersRatings();
                  $userOrdersRatings->user_id = $anotheruser->id;
                  $userOrdersRatings->rate    = $request->rating;
                  $userOrdersRatings->date    = date('Y-m-d');
                  $userOrdersRatings->month   = date('Y-m');
                  $userOrdersRatings->save();
                  if($lastOrder = Order::where(['user_id'=> $user->id,'status' => 'finished'])->orderBy('created_at','DESC')->first()){
                     $lastOrder->customerRating = $request->rating;
                     $lastOrder->save();
                  }
                }  
            
            $msg = trans('user.rating_success');
            return response()->json(successReturnMsg($msg));
            }
            $msg = trans('user.user_notfound');
            return response()->json(failReturn($msg));                
        }else{
            $msg  = implode(' , ',$validator->errors()->all());
            return response()->json(failReturn($msg));
        }
    }

    public function profileComments(Request $request){
            $user = JWTAuth::parseToken()->authenticate();
            $data = [];
            if($comments = Comments::where('profile_id','=',$user->id)->orderBy('created_at','DESC')->get()){
              foreach($comments as $comment){
                $data[]= [
                    'name'             => ($comment->user)? $comment->user->name : '',
                    'avatar'           => ($comment->user)? url('img/user/'.$comment->user->avatar): url('img/user/default.png') ,
                    'rate'             => round(floatval($comment->rate),1),
                    'comment'          => $comment->comment,
                    'date'             => date_format(date_create($comment->created_at), 'Y-m-d')
                ];
              }
              return response()->json(successReturn($data));            
            }
            $msg = trans('user.no_comments'); 
            return response()->json(failReturn($msg));
    }

    public function updateUserlocale(Request $request){
            if( $user = JWTAuth::parseToken()->authenticate() ) {
                if($request->lat){
                  $user->lat  = doubleval( $request->lat );
                  $user->long = doubleval( $request->long );                
                  $user->save(); 
                }             
                $currentcountry = currentCountry();
                if($country = Country::where(['iso2'=>$currentcountry['iso']])->first() ){
                    $lang = $request->header('lang');
                    $user->current_country_id = $country->id;
                    $user->currency           = $country->{"currency_$lang"};
                    $user->balance            = round(floatval($user->balance),2);
                    $user->save();
                } 
                $data = [ 'currency'   => $user->currency,
                          'country_id' => ($user->current_country_id) ?? '',
                          'active'     => ($user->active)??'pending',
                          'available'  => ($user->available)?? 'false',
                          'distance'   => (int)$user->distance,
                          'captain'    => $user->captain,
                          'lat'        => doubleval($user->lat),
                          'long'       => doubleval($user->long),
                          'month'      => date('m')
                        ];
                return response()->json(successReturn($data));
            }else{
                $msg  = trans('user.user_notfound');
                return response()->json(failReturn($msg));
            }
    }
    
    public function changePassword(Request $request){
        $validator = Validator::make($request->all(), [
            'current_password'  => 'required',
            'password'          => 'required|alpha_dash|between:6,50'
        ]);
        if ($validator->passes()){
            $user = JWTAuth::parseToken()->authenticate();
            if (Hash::check($request->current_password, $user->password)) {
                $user->password = Hash::make($request->password);
                $user->save();
                 $msg =  trans('user.change_password') ;
                return response()->json(successReturnMsg($msg));
            }else{
                $msg =  trans('user.incorrect_password');
                return response()->json(failReturn($msg));
            }
        }else{
            $msg = implode(',',$validator->errors()->all());
            return response()->json(failReturn($msg)); 
        }
    }

    public function checkUserStatus(Request $request){
        $user = JWTAuth::parseToken()->authenticate();
        $data['captain']  = $user->captain;
        $data['active']   = ($user->active)??'pending';
        return response()->json(successReturn($data));
    }

    public function deviceData(Request $request){
        $validator = Validator::make($request->all(), [
            'device_id'  => 'required'
        ]);
        if ($validator->passes()){
           $user = JWTAuth::parseToken()->authenticate();
           if($device = userDevices::where(['device_id'=>$request->device_id,'user_id'=>$user->id])->first() ){
            $data = [
                'device_id'           => $device->device_id,
                'show_ads'            => $device->show_ads,
                'orders_notify'       => $device->orders_notify,
                'lang'                => $device->lang
            ];
              return response()->json(successReturn($data));
           }
            $msg = trans('user.device_not_found');
            return response()->json(failReturn($msg)); 
        }else{
            $msg = implode(',',$validator->errors()->all());
            return response()->json(failReturn($msg)); 
        }
    }

    public function updateDeviceData(Request $request){
        $validator = Validator::make($request->all(), [
            'device_id'          => 'required',
            'show_ads'           => 'nullable',
            'orders_notify'      => 'required'
        ]);
        if($validator->passes()){
           $user = JWTAuth::parseToken()->authenticate();
           if($device = userDevices::where(['device_id'=>$request->device_id,'user_id'=>$user->id])->first() ){
              $device->show_ads           = $request->show_ads;
              $device->orders_notify      = $request->orders_notify;
              $device->lang               = $request->header('lang');
              // $device->near_orders_notify = $request->near_orders_notify;
              $device->save();
            $msg = trans('user.saveDeviceData');
            return response()->json(successReturn($msg));
           }
            $msg = trans('user.device_not_found');
            return response()->json(failReturn($msg)); 
        }else{
            $msg = implode(',',$validator->errors()->all());
            return response()->json(failReturn($msg)); 
        }
    }

    public function updateDeviceId(Request $request){
        $validator = Validator::make($request->all(), [
            'old_device_id'          => 'required',
            'new_device_id'          => 'required'
        ]);
        if ($validator->passes()){
           $user = JWTAuth::parseToken()->authenticate();
           if($device = userDevices::where(['device_id'=>$request->old_device_id,'user_id'=>$user->id])->first() ){
              $device->device_id  = $request->new_device_id;
              $device->save();
            $msg = trans('user.saveDeviceData');
            return response()->json(successReturn($msg));
           }
            $msg = trans('user.device_not_found');
            return response()->json(failReturn($msg)); 
        }else{
            $msg = implode(',',$validator->errors()->all());
            return response()->json(failReturn($msg)); 
        }
    }

    public function packages(Request $request) {
        $user = JWTAuth::parseToken()->authenticate();
        $type = $user->captain == 'true' ? 'provider' : 'user';
        $lang = ($request->header('lang'))?? 'ar';
        $currency = setting('site_currency_'.$lang);
        $data = [];
        if($packages = Package::whereType($type)->orderBy('price','ASC')->get()){
            foreach($packages as $package){
                // $now       = time(); // or your date as well
                // $your_date = strtotime($user->package_end_date);
                // $datediff = $your_date - $now ;
                // $remaining_days = round($datediff / (60 * 60 * 24));
                $data[]= [
                    'id'               => $package->id , 
                    'name'             => (string) $package->{"name_$lang"} , 
                    'description'      => (string) $package->{"description_$lang"} , 
                    'price'            => (string) $package->price ,
                    'offer_price'      => (string) $package->offer_price ,
                    'offer_percent'    => (string) $package->offer_percent ,
                    'currency'         => $currency,
                    // 'num_days'         => (integer) $package->num_days ,
                    // 'subscribe'        =>  $package->id == $user->package_id ? true : false ,
                    // 'remaining_days'   =>  $remaining_days,
                ];
            }
        }
        return response()->json(successReturn($data)); 
    }  

    public function subscribePackage(Request $request){
        $validator = Validator::make($request->all(), [
            'package_id'          => 'required',
        ]);
        if ($validator->passes()){
            if($package = Package::where(['id'=>$request->package_id])->first() ){
                $user = JWTAuth::parseToken()->authenticate();
                $num_days         = $package->num_days;
                $package_end_date = date('Y-m-d', strtotime(' + '.$package->num_days.' days'));
                $user->update(['package_id' => $package->id,'package_start_date' => date('Y-m-d') , 'package_end_date' => $package_end_date  ]);

                $msg = trans('user.subscribe_success');
                return response()->json(successReturn($msg));
            }else{
                $msg = trans('user.package_not_found');
                return response()->json(failReturn($msg)); 
            }
        }else{
            $msg = implode(',',$validator->errors()->all());
            return response()->json(failReturn($msg)); 
        }
    }

    public function cancelPackage(Request $request){
        $validator = Validator::make($request->all(), [
            'package_id'          => 'required',
        ]);
        if ($validator->passes()){
            if($package = Package::where(['id'=>$request->package_id])->first() ){
                $user = JWTAuth::parseToken()->authenticate();
                $user->update(['package_id' => null,'package_start_date' => null , 'package_end_date' => null  ]);
                $msg = trans('user.cancel_subscribe_success');
                return response()->json(successReturn($msg));
            }else{
                $msg = trans('user.package_not_found');
                return response()->json(failReturn($msg)); 
            }
        }else{
            $msg = implode(',',$validator->errors()->all());
            return response()->json(failReturn($msg)); 
        }
        
    }

    public function removeAccount(Request $request){
        $user = JWTAuth::parseToken()->authenticate();
        userDevices::where(['user_id'=>$user->id])->delete();
        $user->delete();
        $msg = trans('auth.remove_success');
        return response()->json(successReturnMsg($msg));
    }

  public function checkBalance(){
    $user = JWTAuth::parseToken()->authenticate();
    $data =['need_recharge' => (int) $user->balance <= (-1 * setting('max_debt_captain'))  ? true : false,
          'payment_page_background' => url('dashboard/uploads/setting/site_logo/'.setting('payment_page_background'))
      ];
    return response()->json(successReturn($data));                
  }

    
}
