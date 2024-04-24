<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;
use View;
use Session;
use File;
use App\Questions;
use App\User;
use App\Page;
use App\Role;
use App\Contact;
use App\Order;
use App\Comments;
use App\orderBids;
use App\Profits;
use App\userDevices;
use App\userMeta;
use App\carTypes;
use App\userCars;
use App\Tickets;

use App\Notifications;
use App\SmsEmailNotification;
use App\Mail\PublicMessage;
use Mail;
class DashBoardController extends Controller{

    public function Index(){
       $this->data['num_users']             = User::count();
       $this->data['num_supervisiors']      = User::where('role','>','0')->count();
       $this->data['num_clients']           = User::where('role','=','0')->where('captain','=','false')->count();
       $this->data['num_captains']          = User::where('role','=','0')->where('captain','=','true')->count();
       $this->data['num_roles']             = Role::count();
       $this->data['num_contacts']          = Contact::count();
       $this->data['num_open_orders']       = Order::where('status','=','open')->count();
       $this->data['num_inprogress_orders'] = Order::where('status','=','inprogress')->count();
       $this->data['num_finished_orders']   = Order::where('status','=','finished')->count();
       $this->data['num_closed_orders']     = Order::where('status','=','closed')->count();
       $this->data['num_cartypes']          = carTypes::count();
       $this->data['num_cars']              = userCars::count();
       $this->data['android_devices']       = userDevices::where('device_type','=','android')->count();
       $this->data['ios_devices']           = userDevices::where('device_type','=','ios')->count();
       $this->data['num_newcontacts']       = Contact::where('showOrNow','=',0)->count();
       $this->data['num_newUserMetas']      = userMeta::where('seen','=','false')->count();

       $this->data['day1'] = Order::where("created_at",'like','%'.date('Y-m-d',strtotime('yesterday')-60*60*24*6).'%' )->count();
       $this->data['day2'] = Order::where("created_at",'like','%'.date('Y-m-d',strtotime('yesterday')-60*60*24*5).'%' )->count();
       $this->data['day3'] = Order::where("created_at",'like','%'.date('Y-m-d',strtotime('yesterday')-60*60*24*4).'%' )->count();
       $this->data['day4'] = Order::where("created_at",'like','%'.date('Y-m-d',strtotime('yesterday')-60*60*24*3).'%' )->count();
       $this->data['day5'] = Order::where("created_at",'like','%'.date('Y-m-d',strtotime('yesterday')-60*60*24*2).'%' )->count();
       $this->data['day6'] = Order::where("created_at",'like','%'.date('Y-m-d',strtotime('yesterday')-60*60*24).'%' )->count();
       $this->data['day7'] = Order::where("created_at",'like','%'.date('Y-m-d ',strtotime('yesterday')).'%' )->count();

       $this->data['profitday1'] = (Profits::where("date",'=',date('Y-m-d',strtotime('yesterday')-60*60*24*6))->select('*', DB::raw('SUM(value) as total_value'))->groupBy('date')->first()->total_value)?? 0 ;
       $this->data['profitday2'] = (Profits::where("date",'=',date('Y-m-d',strtotime('yesterday')-60*60*24*5))->select('*', DB::raw('SUM(value) as total_value'))->groupBy('date')->first()->total_value)?? 0;
       $this->data['profitday3'] = (Profits::where("date",'=',date('Y-m-d',strtotime('yesterday')-60*60*24*4))->select('*', DB::raw('SUM(value) as total_value'))->groupBy('date')->first()->total_value)?? 0;
       $this->data['profitday4'] = (Profits::where("date",'=',date('Y-m-d',strtotime('yesterday')-60*60*24*3))->select('*', DB::raw('SUM(value) as total_value'))->groupBy('date')->first()->total_value)?? 0;
       $this->data['profitday5'] = (Profits::where("date",'=',date('Y-m-d',strtotime('yesterday')-60*60*24*2))->select('*', DB::raw('SUM(value) as total_value'))->groupBy('date')->first()->total_value)?? 0;
       $this->data['profitday6'] = (Profits::where("date",'=',date('Y-m-d',strtotime('yesterday')-60*60*24))->select('*', DB::raw('SUM(value) as total_value'))->groupBy('date')->first()->total_value)?? 0;
       $this->data['profitday7'] = (Profits::where("date",'=',date('Y-m-d ',strtotime('yesterday')))->select('*', DB::raw('SUM(value) as total_value'))->groupBy('date')->first()->total_value)?? 0;
       $this->data['orders']     = Order::where('status','open')->get();
       $this->data['hour0']       = Order::where("hour",'=','00')->count();
       $this->data['hour1']       = Order::where("hour",'=','01')->count();
       $this->data['hour2']       = Order::where("hour",'=','02')->count();
       $this->data['hour3']       = Order::where("hour",'=','03')->count();
       $this->data['hour4']       = Order::where("hour",'=','04')->count();
       $this->data['hour5']       = Order::where("hour",'=','05')->count();
       $this->data['hour6']       = Order::where("hour",'=','06')->count();
       $this->data['hour7']       = Order::where("hour",'=','07')->count();
       $this->data['hour8']       = Order::where("hour",'=','08')->count();
       $this->data['hour9']       = Order::where("hour",'=','09')->count();
       $this->data['hour10']      = Order::where("hour",'=','10')->count();
       $this->data['hour11']      = Order::where("hour",'=','11')->count();
       $this->data['hour12']      = Order::where("hour",'=','12')->count();
       $this->data['hour13']      = Order::where("hour",'=','13')->count();
       $this->data['hour14']      = Order::where("hour",'=','14')->count();
       $this->data['hour15']      = Order::where("hour",'=','15')->count();
       $this->data['hour16']      = Order::where("hour",'=','16')->count();
       $this->data['hour17']      = Order::where("hour",'=','17')->count();
       $this->data['hour18']      = Order::where("hour",'=','18')->count();
       $this->data['hour19']      = Order::where("hour",'=','19')->count();
       $this->data['hour20']      = Order::where("hour",'=','20')->count();
       $this->data['hour21']      = Order::where("hour",'=','21')->count();
       $this->data['hour22']      = Order::where("hour",'=','22')->count();
       $this->data['hour23']      = Order::where("hour",'=','23')->count();
       return view('dashboard.index.index',$this->data);
    }

    public function mapNotifications(){
      return view('dashboard.index.mapNotifications');
    }
    public function sendMapNotifications(Request $request){
           
            $start_lat  = doubleval($request->lat);
            $start_long = doubleval($request->lng);
            $distance   = floatval(($request->max_distance * 0.1 ) / 15 );
            $min_lat    = $start_lat  - $distance;
            $min_long   = $start_long - $distance;
            $max_lat    = $start_lat  + $distance;
            $max_long   = $start_long + $distance;

            $nearCaptainDevices   = DB::table('users')->join('user_devices', 'users.id', '=', 'user_devices.user_id')
                                                                 ->when($request->type,function($q){
                                                                    if(request('type') == 'clients'){
                                                                        $q->where('users.captain','=','false');
                                                                    }elseif(request('type') == 'captains'){
                                                                        $q->where('users.captain','=','true');
                                                                    }
                                                                 })
                                                                 ->where('users.lat','>=',$min_lat)->where('users.lat','<=',$max_lat)->where('users.long','>=',$min_long)->where('users.long','<=',$max_long)
                                                                 ->select('users.id','phonekey','phone','email','user_devices.device_id','user_devices.device_type','user_devices.orders_notify')
                                                                 ->get();

            $notifications = [];
            if($nearCaptainDevices){
                if($request->notify_type == 'notification'){
                      $devices      = $nearCaptainDevices;
                      $notify_msg   = $request->notification_message;
                      $notify_title = $request->notification_title;
                      $key          = 'from_admin';
                      $extradata    = "user_id:".Auth::user()->id;
                        foreach($devices as $device){
                            $notifications[] = ['user_id'      => $device->id,
                                                'notifier_id'  => '',
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
                        $notify_title = ($request->notification_title)? $request->notification_title : setting('site_title');
                        $message_ar   = $request->notification_message;
                        $message_en   = $request->notification_message;
                        $data = ['title' => $notify_title,'message_en'=>$message_en,'message_ar'=>$message_ar,'key'=>'from_admin'];
                        sendNotification($devices, $message_ar,$notify_title,$data);

                }elseif($request->notify_type == 'sms'){
                    $numbers = '';
                    foreach ($nearCaptainDevices as $u){
                      $numbers.= $u->phonekey.$u->phone.',';
                    }
                    send_mobile_sms($numbers,$request->notification_message);
                }elseif($request->notify_type == 'email'){
                    $checkConfig = SmsEmailNotification::where('type','=','smtp')->first();
                    if(
                        $checkConfig->username     == "" ||
                        $checkConfig->password     == "" ||
                        $checkConfig->sender_email == "" ||
                        $checkConfig->port         == "" ||
                        $checkConfig->host         == ""
                    ){
                        return back()->with('danger','لم يتم ارسال الرساله ! .. يرجى مراجعة بيانات ال SMTP'); 
                    }else{
                        foreach($nearCaptainDevices as $u){
                            Mail::to($u->email)->send(new PublicMessage(  $request->notification_message  ));
                        }
                    }

                }
            }

      return back()->with('success','تم الارسال بنجاح');
    }
    //captains in Onjob
    public function trackingCenterOnjobCaptains(){
      $captain = '' ; $order = '';

      $allCaptains   = User::join('orders', 'users.id', '=', 'orders.captain_id')
                                 ->where('orders.status','=','inprogress')
                                 ->where('orders.start_journey','=','true')
                                 ->where('users.captain','=','true')
                                 ->where('users.role','=','0')
                                 ->get();

      $num_onjob      = count($allCaptains);
      $num_offline    = User::where('captain','=','true')
                                 ->where('role','=','0')
                                 ->where('available','=','false')
                                 ->count();
      $num_available  = User::where('captain','=','true')
                                 ->where('role','=','0')
                                 ->where('available','=','true')
                                 ->where('have_order','=','false')
                                 ->count();
      $num_tocustomer = User::join('orders', 'users.id', '=', 'orders.captain_id')
                                 ->where('orders.status','=','inprogress')
                                 ->where('orders.captain_in_road','=','true')
                                 ->where('orders.start_journey','!=','true')
                                 ->where('users.captain','=','true')
                                 ->where('users.role','=','0')
                                 ->count();
      return view('dashboard.index.trackingCenterOnjobCaptains',compact('allCaptains','captain','order','num_onjob','num_offline','num_available','num_tocustomer'));
    }
    //captains in Onjob ajax
    public function getOnjobCaptainsLocation(){
      $data['captains'] =  User::join('orders', 'users.id', '=', 'orders.captain_id')
                                                     ->where('orders.status','=','inprogress')
                                                     ->where('orders.start_journey','=','true')
                                                     ->where('users.captain','=','true')
                                                     ->where('users.role','=','0')
                                                     ->get();
      $data['num_onjob']      = count($data['captains']);
      $data['num_offline']    = User::where('captain','=','true')
                                 ->where('role','=','0')
                                 ->where('available','=','false')
                                 ->count();
      $data['num_available']  = User::where('captain','=','true')
                                 ->where('role','=','0')
                                 ->where('available','=','true')
                                 ->where('have_order','=','false')
                                 ->count();
      $data['num_tocustomer'] = User::join('orders', 'users.id', '=', 'orders.captain_id')
                                 ->where('orders.status','=','inprogress')
                                 ->where('orders.captain_in_road','=','true')
                                 ->where('orders.start_journey','!=','true')
                                 ->where('users.captain','=','true')
                                 ->where('users.role','=','0')
                                 ->count();
      return response()->json(successReturn($data));
    }

    //captains in Offline
    public function trackingCenterOfflineCaptains(){
      $captain = '' ; $order = '';
      $allCaptains   = User::where('captain','=','true')
                                 ->where('role','=','0')
                                 ->where('available','=','false')
                                 ->get();

      $num_onjob      = User::join('orders', 'users.id', '=', 'orders.captain_id')
                                 ->where('orders.status','=','inprogress')
                                 ->where('orders.start_journey','=','true')
                                 ->where('users.captain','=','true')
                                 ->where('users.role','=','0')
                                 ->count();
      $num_offline    = count($allCaptains);
      $num_available  = User::where('captain','=','true')
                                 ->where('role','=','0')
                                 ->where('available','=','true')
                                 ->where('have_order','=','false')
                                 ->count();
      $num_tocustomer = User::join('orders', 'users.id', '=', 'orders.captain_id')
                                 ->where('orders.status','=','inprogress')
                                 ->where('orders.captain_in_road','=','true')
                                 ->where('orders.start_journey','!=','true')
                                 ->where('users.captain','=','true')
                                 ->where('users.role','=','0')
                                 ->count();
      return view('dashboard.index.trackingCenterOfflineCaptains',compact('allCaptains','captain','order','num_onjob','num_offline','num_available','num_tocustomer'));
    }
    //captains in Offline ajax
    public function getOfflineCaptainsLocation(){
      $data['captains'] =  User::where('captain','=','true')
                                 ->where('role','=','0')
                                 ->where('available','=','false')
                                 ->get();
      $data['num_onjob']      = User::join('orders', 'users.id', '=', 'orders.captain_id')
                                                     ->where('orders.status','=','inprogress')
                                                     ->where('orders.start_journey','=','true')
                                                     ->where('users.captain','=','true')
                                                     ->where('users.role','=','0')
                                                     ->count();
      $data['num_offline']    = count($data['captains']);
      $data['num_available']  = User::where('captain','=','true')
                                 ->where('role','=','0')
                                 ->where('available','=','true')
                                 ->where('have_order','=','false')
                                 ->count();
      $data['num_tocustomer'] = User::join('orders', 'users.id', '=', 'orders.captain_id')
                                 ->where('orders.status','=','inprogress')
                                 ->where('orders.captain_in_road','=','true')
                                 ->where('orders.start_journey','!=','true')
                                 ->where('users.captain','=','true')
                                 ->where('users.role','=','0')
                                 ->count();
      return response()->json(successReturn($data));
    }

    //captains Available
    public function trackingCenterAvailableCaptains(){
      $captain = '' ; $order = '';
      $allCaptains   = User::where('captain','=','true')
                                 ->where('role','=','0')
                                 ->where('available','=','true')
                                 ->where('have_order','=','false')
                                 ->get();

      $num_onjob      = User::join('orders', 'users.id', '=', 'orders.captain_id')
                                 ->where('orders.status','=','inprogress')
                                 ->where('orders.start_journey','=','true')
                                 ->where('users.captain','=','true')
                                 ->where('users.role','=','0')
                                 ->count();
      $num_offline    = User::where('captain','=','true')
                                 ->where('role','=','0')
                                 ->where('available','=','false')
                                 ->count();
      $num_available  = count($allCaptains);
      $num_tocustomer = User::join('orders', 'users.id', '=', 'orders.captain_id')
                                 ->where('orders.status','=','inprogress')
                                 ->where('orders.captain_in_road','=','true')
                                 ->where('orders.start_journey','!=','true')
                                 ->where('users.captain','=','true')
                                 ->where('users.role','=','0')
                                 ->count();
      return view('dashboard.index.trackingCenterAvailableCaptains',compact('allCaptains','captain','order','num_onjob','num_offline','num_available','num_tocustomer'));
    }
    //captains Available ajax
    public function getAvailableCaptainsLocation(){
      $data['captains'] =  User::where('captain','=','true')
                                 ->where('role','=','0')
                                 ->where('available','=','true')
                                 ->where('have_order','=','false')
                                 ->get();
      $data['num_onjob']      = User::join('orders', 'users.id', '=', 'orders.captain_id')
                                                     ->where('orders.status','=','inprogress')
                                                     ->where('orders.start_journey','=','true')
                                                     ->where('users.captain','=','true')
                                                     ->where('users.role','=','0')
                                                     ->count();
      $data['num_offline']    = User::where('captain','=','true')
                                 ->where('role','=','0')
                                 ->where('available','=','false')
                                 ->count();
      $data['num_available']  = count($data['captains']);
      $data['num_tocustomer'] = User::join('orders', 'users.id', '=', 'orders.captain_id')
                                 ->where('orders.status','=','inprogress')
                                 ->where('orders.captain_in_road','=','true')
                                 ->where('orders.start_journey','!=','true')
                                 ->where('users.captain','=','true')
                                 ->where('users.role','=','0')
                                 ->count();
      return response()->json(successReturn($data));
    }

    //captains in Tocustomer
    public function trackingCenterTocustomerCaptains(){
      $captain = '' ; $order = '';
      $allCaptains   = User::join('orders', 'users.id', '=', 'orders.captain_id')
                                 ->where('orders.status','=','inprogress')
                                 ->where('orders.captain_in_road','=','true')
                                 ->where('orders.start_journey','!=','true')
                                 ->where('users.captain','=','true')
                                 ->where('users.role','=','0')
                                 ->get();

      $num_onjob      = User::join('orders', 'users.id', '=', 'orders.captain_id')
                                 ->where('orders.status','=','inprogress')
                                 ->where('orders.start_journey','=','true')
                                 ->where('users.captain','=','true')
                                 ->where('users.role','=','0')
                                 ->count();
      $num_offline    = User::where('captain','=','true')
                                 ->where('role','=','0')
                                 ->where('available','=','false')
                                 ->count();
      $num_available  = User::where('captain','=','true')
                                 ->where('role','=','0')
                                 ->where('available','=','true')
                                 ->where('have_order','=','false')
                                 ->count();
      $num_tocustomer = count($allCaptains);
      return view('dashboard.index.trackingCenterTocustomerCaptains',compact('allCaptains','captain','order','num_onjob','num_offline','num_available','num_tocustomer'));
    }
    //captains in Tocustomer ajax
    public function getTocustomerCaptainsLocation(){
      $data['captains'] =  User::join('orders', 'users.id', '=', 'orders.captain_id')
                                 ->where('orders.status','=','inprogress')
                                 ->where('orders.captain_in_road','=','true')
                                 ->where('orders.start_journey','!=','true')
                                 ->where('users.captain','=','true')
                                 ->where('users.role','=','0')
                                 ->get();

      $data['num_onjob']      = User::join('orders', 'users.id', '=', 'orders.captain_id')
                                                     ->where('orders.status','=','inprogress')
                                                     ->where('orders.start_journey','=','true')
                                                     ->where('users.captain','=','true')
                                                     ->where('users.role','=','0')
                                                     ->count();
      $data['num_offline']    = User::where('captain','=','true')
                                 ->where('role','=','0')
                                 ->where('available','=','false')
                                 ->count();
      $data['num_available']  = User::where('captain','=','true')
                                 ->where('role','=','0')
                                 ->where('available','=','true')
                                 ->where('have_order','=','false')
                                 ->count();
      $data['num_tocustomer'] = count($data['captains']);

      return response()->json(successReturn($data));
    }

    public function searchDriver(Request $request){
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
        }elseif($request->searchby == 'ticket_id'){
          if($ticket = Tickets::with('order')->find($request->search)){
            if($order = $ticket->order){
              $captain = $order->captain;
            }
          }
        }else{
          //with pin code
          $pin_code = convert2english($request->search);
          if($captain = User::where('pin_code','=',$pin_code)->first()){
            $order = $captain->currentOrder->first();
          }
        }
      $num_onjob      = User::join('orders', 'users.id', '=', 'orders.captain_id')
                                 ->where('orders.status','=','inprogress')
                                 ->where('orders.start_journey','=','true')
                                 ->where('users.captain','=','true')
                                 ->where('users.role','=','0')
                                 ->count();
      $num_offline    = User::where('captain','=','true')
                                 ->where('role','=','0')
                                 ->where('available','=','false')
                                 ->count();
      $num_available  = User::where('captain','=','true')
                                 ->where('role','=','0')
                                 ->where('available','=','true')
                                 ->where('have_order','=','false')
                                 ->count();
      $num_tocustomer = User::join('orders', 'users.id', '=', 'orders.captain_id')
                                 ->where('orders.status','=','inprogress')
                                 ->where('orders.captain_in_road','=','true')
                                 ->where('orders.start_journey','!=','true')
                                 ->where('users.captain','=','true')
                                 ->where('users.role','=','0')
                                 ->count();
      return view('dashboard.index.trackingCenterOnjobCaptains',compact('captain','order','search','searchby','num_onjob','num_offline','num_available','num_tocustomer'));
    }

    public function getCurrentCaptainLocation($captain_id = 0){
      $data['captain'] = false;
      $data['order']   = false;
      if($data['captain'] = User::find($captain_id)){
//          dd($data['captain']->currentOrder);
        $data['order'] = $data['captain']->currentOrder->first();

      }
      $data['num_onjob']      = User::join('orders', 'users.id', '=', 'orders.captain_id')
                                                     ->where('orders.status','=','inprogress')
                                                     ->where('orders.start_journey','=','true')
                                                     ->where('users.captain','=','true')
                                                     ->where('users.role','=','0')
                                                     ->count();
      $data['num_offline']    = User::where('captain','=','true')
                                 ->where('role','=','0')
                                 ->where('available','=','false')
                                 ->count();
      $data['num_available']  = User::where('captain','=','true')
                                 ->where('role','=','0')
                                 ->where('available','=','true')
                                 ->where('have_order','=','false')
                                 ->count();
      $data['num_tocustomer'] = User::join('orders', 'users.id', '=', 'orders.captain_id')
                                 ->where('orders.status','=','inprogress')
                                 ->where('orders.captain_in_road','=','true')
                                 ->where('orders.start_journey','!=','true')
                                 ->where('users.captain','=','true')
                                 ->where('users.role','=','0')
                                 ->count();
      return response()->json(successReturn($data));
    }

    #users page
    public function question(){
        $questions = Questions::latest()->get();
    	return view('dashboard.questions.index',compact('questions',$questions));
    }

    #add user
    public function addQuestion(Request $request)
    {
        $this->validate($request,[
            'question'     =>'required|min:2|max:190',
            'answer'    =>'required|min:2'
        ]);
		$question = new Questions;
		$question->question   = $request->question;
		$question->answer     = $request->answer;
		$question->show       = ($request->show=='on')?'true':'false';
		$question->save();
        History(Auth::user()->id,'بأضافة سؤال جديد');
		Session::flash('success','تم اضافة السؤال بنجاح');
		return back();
    }

    #update user
    public function updateQuestion(Request $request)
    {
        $this->validate($request,[
            'id'     =>'required',
            'edit_question'    =>'required|min:2|max:190',
            'edit_answer'    =>'required|min:2'
        ]);

		$question = Questions::findOrFail($request->id);
		$question->question   = $request->edit_question;
		$question->answer     = $request->edit_answer;
		$question->show       = ($request->edit_show=='on')?'true':'false';
		$question->save();

        History(Auth::user()->id,'بتحديث السؤال '.$question->question);
		Session::flash('success','تم حفظ التعديلات');
		return back();
    }

    #delete user
    public function deleteQuestion(Request $request){
            $question = Questions::findOrFail($request->id);
            $question->delete();
            History(Auth::user()->id,'بحذف السؤال '.$question->question);
            Session::flash('success','تم الحذف');
            return back();
    }


}
