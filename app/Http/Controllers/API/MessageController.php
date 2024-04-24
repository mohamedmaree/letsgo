<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Conversation;
use App\Message;
use App\Order;
use App\cancelReasons;
use App\AdminConversation;
use Illuminate\Support\Facades\DB; 

class MessageController extends Controller{

    public function create(Request $request){
        $lang = $request->header('lang'); $data = [];
        $user = JWTAuth::parseToken()->authenticate();
        if (!($conversation = AdminConversation::where(['user1' => $user->id])->first())) {
            $conversation = new AdminConversation();
            $conversation->user1 = $user->id;
            $conversation->user2 = 0;
            $conversation->save();
        }
        $data['conversation_id'] = $conversation->id;
        return response()->json(successReturn($data));
    }
    
    
    public function conversation(Request $request){
      $validator        = Validator::make($request->all(),[
          'conversation_id'    => 'required',
      ]);
      if($validator->passes()){
        $lang = $request->header('lang'); $data = [];
        $user = JWTAuth::parseToken()->authenticate();
        $currency = setting('site_currency_'.$lang);
        $is_captain = false;
        if($conv = Conversation::find($request->conversation_id)){
            DB::table('messages')->where('conversation_id','=',$request->conversation_id)->where('user_id','!=',$user->id)->where('seen','!=','true')->update(['seen' => 'true']);                
            if($order = Order::find($conv->order_id)){
                if($order->captain_id == $user->id){
                  $is_captain = true;
                }else{
                  $is_captain = false;
                }     
              $data['order'] = [ 'id'                    => $order->id,
                                 'order_type'            => ($order->order_type)??'trip',
                                 'place_id'              => ($order->place_id)??'',
                                 'place_ref'             => ($order->place_ref)??'',
                                 'place_name'            => ($order->place_name)??'',
                                 'icon'                  => url('img/icons/restaurant.png'),   
                                 'start_lat'             => "$order->start_lat",
                                 'start_long'            => "$order->start_long",
                                 'start_address'         => ($order->start_address)??'',
                                 'end_lat'               => "$order->end_lat",
                                 'end_long'              => "$order->end_long",
                                 'end_address'           => ($order->end_address)??'',
                                 'time_zone'             => date_default_timezone_get()                              
              ];  
            }
            $data['yourinfo'] = ['id'          => $user->id,
                                 'name'        => ($user->name)??'',
                                 'lat'         => doubleval( $user->lat ),
                                 'long'        => doubleval( $user->long ),
                                 'is_captain'  => $is_captain
                                ];        
            if($conv->user1 == $user->id){
              $seconduser = $conv->seconduser;
            }else{
              $seconduser = $conv->firstuser;
            }
            $rate = 0 ; $avatar = url('img/user/default.png');
            $name = '' ; $phone = ''; $type = ''; $seconduser_id = 0 ;
            $seconduser_lat = 23.8859 ; $seconduser_long = 45.0792;
            if($seconduser){
              $seconduser_id   = $seconduser->id;
              $rate            = ($seconduser->num_rating > 0 )? round(floatval($seconduser->rating / $seconduser->num_rating),1) : 0;
              $avatar          = ($seconduser->avatar)?url('img/user/'.$seconduser->avatar) : url('img/user/default.png');
              $name            = $seconduser->name;
              $phone           = '0'.$seconduser->phone;
              $type            = ($seconduser->captain == 'true')? 'captain' : 'client';
              $seconduser_lat  = doubleval( $seconduser->lat );
              $seconduser_long = doubleval( $seconduser->long );
            }
            $data['seconduser'] = ['id'       => $seconduser_id,
                                   'name'     => ($name)??'',
                                   'phone'    => $phone,
                                   'type'     => $type,
                                   'lat'      => $seconduser_lat,
                                   'long'     => $seconduser_long,                               
                                   'rate'     => $rate,
                                   'avatar'   => $avatar,
                                   ];
            $data['messages'] = [];
            if($messages = Message::where(['conversation_id'=>$request->conversation_id])->orderBy('created_at','ASC')->get()){
               foreach($messages as $msg){
                if(($msg->type == 'image') || ($msg->type == 'sound') ){
                   $content = url('chatuploads/'.$msg->content);
                }else{
                   $content = $msg->content;
                }
                if($msg->user_id == $user->id){
                  $sender = 'you';
                }else{
                  $sender = 'seconduser';
                }
                $data['messages'][] = ['sender'   => $sender,
                                       'user_id'  => $msg->user_id,
                                       'username' => ($msg->user)?$msg->user->name:'',
                                       'content'  => $content,
                                       'type'     => $msg->type,
                                       'avatar'   => ($msg->user)? url('img/user/'.$msg->user->avatar) : url('img/user/default.png'),
                                       'date'     => $msg->created_at->diffForHumans()
                                      ]; 
               }
            }
            if($is_captain){
               $reasons = cancelReasons::where('type','=','withdraw')->orderBy('id','ASC')->get();
            }else{
               $reasons = cancelReasons::where('type','=','cancel')->orderBy('id','ASC')->get();
            }
            foreach($reasons as $reason){
                $data['reasons'][] = ['id' => $reason->id,'reason' => $reason->{"reason_$lang"} ];
            } 
            return response()->json(successReturn($data));
          }else{
            $msg   = trans('order.conv_notfound');
            return response()->json(failReturn($msg));            
          }
      }else{
        $msg   = implode(' , ',$validator->errors()->all());
        return response()->json(failReturn($msg));
      }
    }


    public function uploadFile(Request $request){
        $validator    = Validator::make($request->all(),[
            'file'    => 'required',
        ]);

        if($validator->passes()){
            $user = JWTAuth::parseToken()->authenticate();
            if($request->hasFile('file')) {
                $file            = $request->file('file');
                $name            = md5($request->file('file')->getClientOriginalName()).time().rand(99999,1000000).'.'.$file->getClientOriginalExtension();
                $destinationPath = public_path('/chatuploads');
                $imagePath       = $destinationPath. "/".  $name;
                $file->move($destinationPath, $name);
                $data = ['name' => $name,'url'=> url('chatuploads/'.$name) ];
              return response()->json(successReturn($data));
            }            
            $msg = "file required";
            return response()->json(failReturn($msg));
        }else{
            $msg   = implode(' , ',$validator->errors()->all());
            return response()->json(failReturn($msg));
        }
    }


}
