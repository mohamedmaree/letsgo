<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Order;
use DateTime;
use Carbon\Carbon;
use DateTimeZone;

class registerTripsToWasl extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'registerTripsToWasl:hourly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'send finished trips details to wasl ';

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
        if($orders = Order::where(['status' => 'finished' , 'sent_to_wasl' => 'false'])->orderBy('created_at','DESC')->get()){
           foreach ($orders as $order) {
            $sequenceNumber = ($order->car)? $order->car->sequenceNumber : '';
            $driverId       = ($order->captain)? $order->captain->identity_number : '';
            
            // $starttime = new DateTime($order->start_journey_time);
            // $start_journey_time =  $starttime->format(DateTime::ATOM);
            // $start_journey_time = str_replace('+01:00','.000', $start_journey_time);
            // $start_journey_time = str_replace('+02:00','.000', $start_journey_time);
            
            // $start_journey_time = new DateTime($order->start_journey_time, new DateTimeZone('Asia/Riyadh'));
            // $start_journey_time = $start_journey_time->format(DateTime::ATOM);
            $start_journey_time = new DateTime($order->start_journey_time, new DateTimeZone('Asia/Riyadh'));
            $start_journey_time = $start_journey_time->format(DateTime::ISO8601);
            $start_journey_time = str_replace("+", ".", $start_journey_time);

            
            // $endtime = new DateTime($order->end_journey_time);
            // $end_journey_time =  $endtime->format(DateTime::ATOM);
            // $end_journey_time = str_replace('+01:00','.000', $end_journey_time);
            // $end_journey_time = str_replace('+02:00','.000', $end_journey_time);
            
            // $end_journey_time = new DateTime($order->end_journey_time, new DateTimeZone('Asia/Riyadh'));
            // $end_journey_time = $end_journey_time->format(DateTime::ATOM);
            $end_journey_time = new DateTime($order->end_journey_time, new DateTimeZone('Asia/Riyadh'));
            $end_journey_time = $end_journey_time->format(DateTime::ISO8601);
            $end_journey_time = str_replace("+", ".", $end_journey_time);

            // $createdtime = new DateTime($order->created_at);
            // $created_at_time =  $createdtime->format(DateTime::ATOM);
            // $created_at_time = str_replace('+01:00','.000', $created_at_time);
            // $created_at_time = str_replace('+02:00','.000', $created_at_time);
            
            // $created_at_time = new DateTime($order->created_at, new DateTimeZone('Asia/Riyadh'));
            // $created_at_time = $created_at_time->format(DateTime::ATOM);
            $created_at_time = new DateTime($order->created_at, new DateTimeZone('Asia/Riyadh'));
            $created_at_time = $created_at_time->format(DateTime::ISO8601);
            $created_at_time = str_replace("+", ".", $created_at_time);
            
            $period = intval( ( strtotime($order->end_journey_time) - strtotime($order->start_journey_time)));
            $period = ($period <= 0)? 1 : $period;
           
            // dd($sequenceNumber.' ----- '.$driverId.'----  '.$order->id.' ---- '.($order->distance*1000).' ---- '.$period.'----'.(($order->customerRating)??4).'---'.(($order->initial_wait*60)??60).'---الرياض'.'-----'.'الرياض'.'-----'.$order->start_lat.'---'.$order->start_long.'----'.$order->end_lat.'----'.$order->end_long.'---'.$start_journey_time.'-----'.$end_journey_time.'----'.$created_at_time.'----'.(doubleval(round($order->price,2))));
            $distance = intval($order->distance) * 1000;
            $distance  = ( intval($distance) >= 1)? $distance : 1;

            $customerRating = (intval($order->customerRating) <= 0)? 4 : intval($order->customerRating);

            $initial_wait = (intval($order->initial_wait * 60) <= 0 )? 60 : intval($order->initial_wait * 60);

            $result = registerTrip($sequenceNumber,$driverId,$order->id,$distance,$period,$customerRating,$initial_wait,'الرياض','الرياض',$order->start_lat,$order->start_long,$order->end_lat,$order->end_long,$start_journey_time,$end_journey_time,$created_at_time,doubleval(round($order->price,2)));
            if($result->success){
                $order->sent_to_wasl = 'true';
                $order->save();
            }
           }
        }
        $this->info('sent all finished trips details to wasl.');
    }
}
