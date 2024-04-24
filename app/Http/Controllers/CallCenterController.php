<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 
use Auth;
use View;
use Session;
use File;
use App\User;
use App\Order;
use App\Conversation;
use App\userDevices;
class CallCenterController extends Controller{

 
    //captains in Onjob
    public function callCenter(){
      $captain = '' ; $order = '';

       $personsorders            = Order::with('captain','user','cartype')->where('status','=','open')->orderBy('created_at','DESC')->get();
       $personsorders_inprogress = Order::with('captain','user','cartype')->where('status','=','inprogress')->orderBy('created_at','DESC')->get();
       $personsorders_closed     = Order::with('captain','user','cartype')->where('status','=','closed')->where('created_at','>=',date('Y-m-d H:i:s',time()-60*15))->orderBy('created_at','DESC')->get();

      return view('dashboard.callCenter.index',get_defined_vars());
    }


    public function searchCaptainsAndOrders(Request $request){
      $this->validate($request,[
          'searchby'  =>'required',
          'search'    =>'required'
      ]);
        $order    = '';
        $captain  = '';
        $searchby = $request->searchby;
        $search   = $request->search;
        if($request->searchby == 'order_id'){
          if($order = Order::with('captain')->find($request->search)){
            $captain = $order->captain;
          }
        }elseif($request->searchby == 'phone'){
          $number = convert2english($request->search);
          $phone  = phoneValidate($number);

          if($captain = User::where('phone','=',$phone)->first()){
            $order = $captain->currentOrder->first();
          }
        }else{
          //with pin code
          $pin_code = convert2english($request->search);
          if($captain = User::where('pin_code','=',$pin_code)->first()){
            $order = $captain->currentOrder->first();
          }
        }

        if($order){
            $start_lat  = doubleval($order->start_lat);
            $start_long = doubleval($order->start_long);
            $distance   = floatval((setting('distance') * 0.1 ) / 15 );
            $min_lat    = $start_lat  - $distance;
            $min_long   = $start_long - $distance;
            $max_lat    = $start_lat  + $distance;
            $max_long   = $start_long + $distance;             

            $suggested_captains1   = User::where('users.captain','=','true')
                                        ->where('role','=','0')
                                        ->where('users.have_order','=','false')
                                        ->where('users.lat','>=',$min_lat)->where('users.lat','<=',$max_lat)->where('users.long','>=',$min_long)->where('users.long','<=',$max_long)
                                         // ->where('users.available','=','true')
                                        ->where('captain_current_car_type_id','=',$order->car_type_id)
                                        ->take(20)
                                        ->get();

            $suggested_captains2   = User::where('users.captain','=','true')
                                        ->where('role','=','0')
                                        ->where('users.have_order','=','false')
                                        ->where('users.lat','>=',$min_lat)->where('users.lat','<=',$max_lat)->where('users.long','>=',$min_long)->where('users.long','<=',$max_long)
                                         // ->where('users.available','=','true')
                                        ->take(20)
                                        ->get();

            $received_notify_captains = User::join('notifications', 'users.id', '=', 'notifications.user_id')
                                                  ->where('notifier_id','=',$order->user_id)
                                                  ->where('key','=','newOrder')
                                                  ->where('data','like','%'.'order_id:'.$order->id.'%')
                                                  ->distinct('users.id') 
                                                  ->select('users.id','users.name','users.phone','users.available','users.lat','users.long','users.num_done_orders')
                                                  ->get();

            $trip_conversations = Conversation::where('order_id','=',$order->id)->latest()->get();
        }

       $personsorders            = Order::with('captain','user','cartype')->where('status','=','open')->orderBy('created_at','DESC')->get();
       $personsorders_inprogress = Order::with('captain','user','cartype')->where('status','=','inprogress')->orderBy('created_at','DESC')->get();
       $personsorders_closed     = Order::with('captain','user','cartype')->where('status','=','closed')->where('created_at','>=',date('Y-m-d H:i:s',time()-60*15))->orderBy('created_at','DESC')->get();
  
      return view('dashboard.callCenter.index',get_defined_vars());
    }


    public function searchCaptainsAndOrdersAjax(Request $request){
      $this->validate($request,[
          'searchby'  =>'nullable',
          'search'    =>'nullable'
      ]);
        $order    = '';
        $captain  = '';
        $searchby = $request->searchby;
        $search   = $request->search;
        
        if($request->searchby == 'order_id'){
          if($order = Order::with('captain')->find($request->search)){
            $captain = $order->captain;
          }
        }elseif($request->searchby == 'phone'){
          $number = convert2english($request->search);
          $phone  = phoneValidate($number);

          if($captain = User::where('phone','=',$phone)->first()){
            $order = $captain->currentOrder->first();
          }
        }else{
          //with pin code
          $pin_code = convert2english($request->search);
          if($captain = User::where('pin_code','=',$pin_code)->first()){
            $order = $captain->currentOrder->first();
          }
        }

        if($order){
            $start_lat  = doubleval($order->start_lat);
            $start_long = doubleval($order->start_long);
            $distance   = floatval((setting('distance') * 0.1 ) / 15 );
            $min_lat    = $start_lat  - $distance;
            $min_long   = $start_long - $distance;
            $max_lat    = $start_lat  + $distance;
            $max_long   = $start_long + $distance;             

            $suggested_captains1   = User::where('users.captain','=','true')
                                        ->where('role','=','0')
                                        ->where('users.have_order','=','false')
                                        ->where('users.lat','>=',$min_lat)->where('users.lat','<=',$max_lat)->where('users.long','>=',$min_long)->where('users.long','<=',$max_long)
                                         // ->where('users.available','=','true')
                                        ->where('captain_current_car_type_id','=',$order->car_type_id)
                                        ->take(20)
                                        ->get();

            $suggested_captains2   = User::where('users.captain','=','true')
                                        ->where('role','=','0')
                                        ->where('users.have_order','=','false')
                                        ->where('users.lat','>=',$min_lat)->where('users.lat','<=',$max_lat)->where('users.long','>=',$min_long)->where('users.long','<=',$max_long)
                                         // ->where('users.available','=','true')
                                        ->take(20)
                                        ->get();

            $received_notify_captains = User::join('notifications', 'users.id', '=', 'notifications.user_id')
                                                ->where('notifier_id','=',$order->user_id)
                                                ->where('key','=','newOrder')
                                                ->where('data','like','%'.'order_id:'.$order->id.'%')
                                                ->distinct('users.id') 
                                                ->select('users.id','users.name','users.phone','users.available','users.lat','users.long','users.num_done_orders')
                                                ->get();

            $trip_conversations = Conversation::where('order_id','=',$order->id)->latest()->get();
        }


       $personsorders            = Order::with('captain','user','cartype')->where('status','=','open')->orderBy('created_at','DESC')->get();
       $personsorders_inprogress = Order::with('captain','user','cartype')->where('status','=','inprogress')->orderBy('created_at','DESC')->get();
       $personsorders_closed     = Order::with('captain','user','cartype')->where('status','=','closed')->where('created_at','>=',date('Y-m-d H:i:s',time()-60*15))->orderBy('created_at','DESC')->get();

      return view('dashboard.callCenter.searchCaptainsAndOrdersAjax',get_defined_vars());
    }

 
    public function notifyOrderToCaptain(Request $request)
    {
        $order_id = $request->order_id;
        $captain_id = $request->captain_id;

        if ($order = Order::find($order_id)) {
            if ($captain = User::find($captain_id)) {
                
                $devices = userDevices::where(['user_id' => $captain->id])->get();
                
                //send notification for captain
                $notify_title_ar   = 'رحلة جديدة';
                $notify_title_en   = 'New Trip';
                // $message_ar        = 'هناك رحلة جديدة بالقرب منك.';
                // $message_en        = 'There is a new trip near you.';
                $message_ar = setting('newOrder_msg_ar');
                $message_en = setting('newOrder_msg_en');
                $data = ['title_ar' => $notify_title_ar,'title_en'=>$notify_title_en,'message_en'=>$message_en,'message_ar'=>$message_ar,'key'=>'newOrder','order_id'=>$order->id,'order_status'=>'open','type' => $order->type,'order_type' => $order->order_type];                           
                sendNotification($devices, $message_ar,$notify_title_ar,$data,'newOrder');
                notify($captain->id,$order->user_id,'order.newOrderTitle', 'order.newOrder', "order_id:" . $order->id, 'open', 'newOrder');
            }
        }
        $msg = 'order.notifySuccessfully';
        return response()->json(successReturnMsg($msg));
    } 

}
