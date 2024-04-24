<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Order;
use App\User;
use DateTime;
use DateTimeZone;
class sendCaptainsLocationsToWasl extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sendCaptainsLocationsToWasl:15minutes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'send inprogress trips locations to wasl ';

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
        // if($orders = Order::where(['status' => 'inprogress' ])->get()){
        //    foreach ($orders as $order) {
        //     $sequenceNumber = ($order->car)? $order->car->sequenceNumber : '';
        //     $driverId       = ($order->captain)? $order->captain->identity_number : '';

        //     $createdtime = new DateTime(date('Y-m-d H:i:s',time()));
        //     $created_at_time =  $createdtime->format(DateTime::ATOM);
        //     $created_at_time = str_replace('+01:00','.000', $created_at_time);
        //     $created_at_time = str_replace('+02:00','.000', $created_at_time);
            
        //     $result = registerCaptainsLocations($driverId,$sequenceNumber,$order->current_lat,$order->current_long,true,$created_at_time);
        //    }
        // }

        if($availableCaptains = User::with('currentCar')->where(['captain'=>'true','available'=>'true','have_order' => 'true'])->orderBy('id','DESC')->get()){
           foreach($availableCaptains as $availableCaptain) {
            $sequenceNumber = ($availableCaptain->currentCar)? $availableCaptain->currentCar->sequenceNumber : '';
            $driverId       = ($availableCaptain->identity_number)?? '';

            // $createdtime     = new DateTime(date('Y-m-d H:i:s',time()));
            // $created_at_time =  $createdtime->format(DateTime::ATOM);
            // $created_at_time = str_replace('+01:00','.000', $created_at_time);
            // $created_at_time = str_replace('+02:00','.000', $created_at_time);
            
            // $created_at_time = new DateTime('NOW', new DateTimeZone('Asia/Riyadh'));
            // $created_at_time = $created_at_time->format(DateTime::ATOM);

            $created_at_time = new DateTime('NOW', new DateTimeZone('Asia/Riyadh'));
            $created_at_time = $created_at_time->format(DateTime::ISO8601);
            $created_at_time = str_replace("+", ".", $created_at_time);

            $result = registerCaptainsLocations($driverId,$sequenceNumber,$availableCaptain->lat,$availableCaptain->long,$availableCaptain->have_order,$created_at_time);
           }
        }
        $this->info('sent all inprogress trips locations to wasl.');
    }
}
