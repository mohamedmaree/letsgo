<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB; 
use App\User;
use App\Plans;
use App\userOrdersRatings;
use App\usersOrdersHistory;
use App\userAvailableTimes;
use App\Order;

class updateCaptainsPlans extends Command{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'updateCaptainsPlans:monthly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'update captains plans every month.';

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
            $month = date('Y-m');
            if($captains = User::where('captain','=','true')->where('last_activity','>=',date('Y-m-d H:i:s',(time()- 60*60*24*29) ) )->get()){
               $plans = Plans::orderBy('id','DESC')->get();
               if(count($plans) > 0){
                   foreach($captains as $captain) {
                        /*start rate in that month*/
                        $totalRate = 0.0;
                        $monthratings = userOrdersRatings::select('*', DB::raw('SUM(rate) as rate_sum ,COUNT(*) as count_ratings'))->where('user_id','=',$captain->id)->where('month','=',$month)->first();
                        if($monthratings){
                          $totalRate = ($monthratings->rate_sum != 0)? round($monthratings->rate_sum / $monthratings->count_ratings,1) : 0.0;
                        }
                        /*end rate*/

                        /*start accept rate in that month*/
                        $totalAccept='';$openedorders = 0;$finishedorders=0;
                        $monthopened    = usersOrdersHistory::select('*', DB::raw("COUNT(*) as allorders"))->where('captain_id','=',$captain->id)->where('month','=',$month)->first();
                        $monthaccepted  = usersOrdersHistory::select('*', DB::raw("COUNT(*) as finishedorders"))->where('captain_id','=',$captain->id)->where('status','=','finished')->where('month','=',$month)->first();
                        if($monthopened){
                          $openedorders  = ($monthopened->allorders)?? 0; 
                          $finishedorders= ($monthaccepted->finishedorders)?? 0; 
                        }
                        $totalAccept   = ( $finishedorders == 0 )? 0 : round( ( $finishedorders / $openedorders ) * 100 ,1);
                        /*end accept rate hours*/            

                        /*start available hours in that month*/
                        $totalAvailableHours= '0';
                        $monthAvailableHours = userAvailableTimes::select('*', DB::raw('SUM(num_minutes) as total_minutes'))->where('user_id','=',$captain->id)->where('month','=',$month)->first();
                        if($monthAvailableHours){
                          $totalAvailableHours = intval($monthAvailableHours->total_minutes / 60);
                        }
                        /*end available hours*/ 

                        /*start num of finished orders in month*/
                        $num_orders = Order::where(['captain_id'=>$captain->id,'status'=>'finished'])->where('year','=',date('Y'))->where('month','=',date('n'))->count();
                        /*end num of finished orders in month*/
                        /*start update captain plan*/
                        foreach($plans as $plan){
                            if( ($captain->plan_id != $plan->id) && ($totalAvailableHours >= $plan->working_hours) && ( $totalAccept >= $plan->acceptance_rate) && ( $totalRate >= $plan->rate) && ( $num_orders >= $plan->num_orders) ){
                                $captain->plan_id  = $plan->id;
                                $captain->balance += $plan->reward;
                                $captain->save();
                                $plan->num_users += 1;
                                $plan->save();
                                break;
                            }
                        }
                        /*end update captain plan*/
                    }
               }
            }

        $this->info('captains plans updated.');
    }
}
