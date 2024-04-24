<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Notifications;
use Validator;
use Illuminate\Support\Facades\DB; 
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Order;
use App\Conversation;
use App\Country;
use App\Offers;
class NotificationsController extends Controller{

    public function getNotifications(Request $request){
      $validator        = Validator::make($request->all(),[
          'page'        => 'required'
      ]);
      if($validator->passes()){
        $user   = JWTAuth::parseToken()->authenticate();
        $lang   = $request->header('lang')??'ar';
        $page   = ($request->page)? $request->page : 1;
        $limit  = $this->limit;
        $offset = ( $request->page - 1 ) * $limit;
        $captain_status  = 'pending';
        $ordermintime  = date('Y-m-d H:i:s',strtotime('-128 hours',strtotime(date('Y-m-d H:i:s'))));
        DB::table('notifications')->where('created_at','<',$ordermintime)->where('user_id','=',$user->id)->where('seen','=','true')->delete(); 
        
        $notifications = Notifications::with('notifier')->where('user_id','=',$user->id)->orderBy('created_at','DESC')->skip($offset)->take($limit)->paginate($limit);
        $dataNotifications = [];
        $new_trips_arr = [];   
           foreach($notifications as $notify){
              $x = explode(':', $notify->data);
              $extra_data = []; $order_status = ''; 
              for($i = 0 ; $i < count($x) ; $i=$i+2){
                $extra_data[$x[$i]] = $x[$i+1];
                  if($x[0] == 'order_id'){
                    if($notify->key == 'newOrder'){
                        if(in_array($x[1], $new_trips_arr)){
                           continue 2;
                        }
                        $new_trips_arr[] = $x[1];
                    }
                    if($order = Order::find($x[1])){
                      $order_status = $order->status;
                      if(($order->status != 'open') && ($order->captain_id != $user->id) ){
                        $notify->delete();
                        continue 2;
                      }
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
                    }
                  }
              }
               
              $notifier_name  = ($notify->notifier)? $notify->notifier->name:'';
              $notifier_image = ($notify->notifier)? url('img/user/'.$notify->notifier->avatar):url('img/user/avatar.png');
              if(($notify->key == 'activeCaptain') || ($notify->key == 'from_admin') || ($notify->key == 'answerContact') ){
                $notifier_name  = setting('site_title');
                $notifier_image = url('dashboard/uploads/setting/site_logo/'.setting('site_logo'));              
              }
              $title = trans($notify->title);

              if( ( isset($x[3]) ) && ($notify->key == 'balance_transfer' )){
                if(isset($x[1])){
                  $country = Country::find($x[1]);
                  $transfer_currency = ($country)? $country->{"currency_$lang"}:setting('site_currency_'.$lang); 
                }else{
                  $transfer_currency = $user->currency;
                }
                $amount = $x[3].' '.$transfer_currency;
                $text = trans($notify->message,['name'=>$notifier_name,'amount'=>$amount]);
              }else{
                $text  = trans($notify->message,['name'=>$notifier_name]);
              }

             //end of get place name with lang
             $dataNotifications[] = [ 'id'             => $notify->id,
                                      'user_id'        => ($notify->notifier_id)??0,
                                      'name'           => ($notifier_name)??'',
                                      'image'          => ($notifier_image)??'',
                                      'title'          => ($title)??'',
                                      'text'           => ($text)??'',
                                      'data'           => $extra_data,
                                      'order_status'   => ($order_status)??'',
                                      'captain_status' => ($captain_status)??'',
                                      'key'            => ($notify->key)??'',
                                      'seen'           => ($notify->seen)??'',
                                      'date'           => ($notify->created_at->diffForHumans())??''
                                      ];
           }       
          
          DB::table('notifications')->where(['user_id'=>$user->id,'seen'=>'false'])->update(['seen' => 'true']);

          $total_rows  = $notifications->total();
          $lastPage    = $notifications->lastPage();
          $currentPage = $notifications->currentPage();
          $perPage     = $notifications->perPage();
          $total_pages = ceil( $total_rows / $perPage ); 
          $next_page = ($page + 1 > $total_pages )? '' : $page + 1 ;

          $data = ['notifications' => $dataNotifications , 'current_page' => "".$currentPage."" , 'per_page' => $perPage , 'all_pages' => $total_pages , 'next_page' => "".$next_page."" ];
        return response()->json(successReturn($data));
      }else{
        $msg   = implode(' , ',$validator->errors()->all());
        return response()->json(failReturn($msg));
      }
    }
 
    public function numNotifications(Request $request){
        $user   = JWTAuth::parseToken()->authenticate();
        $num_notifications = Offers::where('end_at','>',date('Y-m-d'))->count();//Notifications::where(['user_id'=>$user->id,'seen'=>'false'])->count();
        $data['num_notifications'] = $num_notifications; 
        return response()->json(successReturn($data));
    }

    public function deleteNotification(Request $request){
      $id   = $request->id;
      $user = JWTAuth::parseToken()->authenticate();
      if($notify = Notifications::where(['id'=>$id,'user_id'=>$user->id])->first()){
        $notify->delete();
        $msg = trans('user.deleteNotify');
        return response()->json(successReturnMsg($msg));                  
      }
      $msg   = trans('user.notfound_notify');
      return response()->json(failReturn($msg));
    } 
}
