<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB; 
use App\Notifications;

class LaterOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laterorders:hourly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'send ahourly notifications to clients have later order';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(){
        // dd(date('H:i:s',time()+60*60));
            $devices = DB::table('orders')->join('user_devices', 'orders.captain_id', '=', 'user_devices.user_id')
                                        ->where('later_order_date','=',date('Y-m-d'))->where('later_order_time','<=', date('H:i:s',time()+60*60) )->where('orders.status','inprogress')
                                        ->orwhere('later_order_date','=',date('Y-m-d'))->where('later_order_time','<=', date('H:i:s',time()+60*60) )->where('orders.status','open')
                                        ->distinct('device_id') 
                                        ->select('orders.id','orders.type','orders.order_type','orders.status','user_devices.user_id','user_devices.device_id','user_devices.device_type','user_devices.orders_notify')
                                        ->get();   
// dd($devices);
            $notifications = [];
            if($devices){
              $notify_msg   = 'order.laterOrder';
              $notify_title = 'order.laterOrderTitle';                
              $key          = 'newOrder';
              // $extradata    = "order_id:".$order->id;
                foreach($devices as $device){
                    $notifications[] = ['user_id'      => $device->user_id,
                                        'notifier_id'  => '',
                                        'message'      => $notify_msg,
                                        'title'        => $notify_title,
                                        'data'         => "order_id:".$device->id,
                                        'order_status' => $device->status,
                                        'key'          => $key,
                                        'created_at'   => date('Y-m-d H:i:s')
                                        ];

                    #use FCM or One Signal Here :) 
                    $notify_title_ar   = 'رحلة لاحقة';
                    $notify_title_en   = 'Later Trip';
                    $message_ar = 'لديك رحلة لاحقة بعد أقل من ساعة.';
                    $message_en = 'You have a later trip after less than an hour.';
                    $data = ['title_ar' => $notify_title_ar,'title_en'=>$notify_title_en,'message_en'=>$message_en,'message_ar'=>$message_ar,'key'=>'newOrder','order_id'=>$device->id,'order_status'=>$device->status,'type' => $device->type,'order_type' => $device->order_type];                           
                    sendNotification($device, $message_ar,$notify_title_ar,$data,'newOrder');

                }
                $uniqueNotifications = array_unique($notifications,SORT_REGULAR);
                Notifications::insert($uniqueNotifications);   
            }

        $this->info('notifications sent All later orders clients and captains.');
    }
}
