<?php

namespace App\Http\Controllers;

use Hash;
use Illuminate\Http\Request;
use App\SmsEmailNotification;
use Auth;
use App\User;
use App\Role;
use File;
use Session;
use Illuminate\Support\Facades\DB; 
use App\userDevices;
use Validator;
use App\userMeta;
use App\Exports\UsersExport;
use App\Exports\PaymentsExport;
use App\Exports\captainMoneyHistoryExport;
use App\Exports\UserMetaExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Notifications;
use App\usersCoupons;
use App\Coupons;
use App\Comments;
use App\userBlocks;
use App\Payments;
use App\Country;
use App\City;
use App\userCars;
use App\carTypes;
use App\usersOrdersHistory;
use App\userOrdersRatings;
use App\userAvailableTimes;
use App\Order;
use App\rewardsHistory;
use App\GuaranteesHistory;
use Jenssegers\Date\Date;
use App\captainMoneyHistory;
use App\Mail\PublicMessage;
use Mail;
use App\WelcomePageSetting;

class UsersController extends Controller{

    public function captainPerformance($userid ='', $page = 1, $lang = 'ar'){
            $user = User::findOrFail($userid);
            $page = ($page)?? 1;
            $lang  = ($lang)??'ar';
            app()->setLocale($lang);
            \Carbon\Carbon::setLocale($lang);

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
            $dataRatings = [];$totalRate = 0;
            $weekratings = userOrdersRatings::select('*', DB::raw('SUM(rate) as rate_sum ,COUNT(*) as count_ratings'))->where('user_id','=',$user->id)->where('created_at','>=',$start_date)->where('created_at','<=',$end_date)->first();
            if($weekratings){
              $totalRate = ($weekratings->rate_sum != 0)? ($weekratings->rate_sum / $weekratings->count_ratings) : 0;
            }
            $totalRate = round($totalRate,2);

            /*start available hours in that week*/
            $dataAvailabelHours  = []; $totalAvailableHours= 0;
            $weekAvailableHours = userAvailableTimes::select('*', DB::raw('SUM(num_minutes) as total_minutes'))->where('user_id','=',$user->id)->where('created_at','>=',$start_date)->where('created_at','<=',$end_date)->first();
            if($weekAvailableHours){
              $totalAvailableHours = (convertToHoursMins($weekAvailableHours->total_minutes))?? 0;
            }

            /*start accept rate in that week*/
            $dataAcceptance = [];$totalAccept='';$openedorders = 0;$finishedorders=0;$count_finished_orders=0;
            $weekopened    = usersOrdersHistory::select('*', DB::raw("COUNT(*) as allorders"))->where('captain_id','=',$user->id)->where('created_at','>=',$start_date)->where('created_at','<=',$end_date)->first();
            $weekaccepted  = usersOrdersHistory::select('*', DB::raw("COUNT(*) as finishedorders"))->where('captain_id','=',$user->id)->where('status','=','finished')->where('created_at','>=',$start_date)->where('created_at','<=',$end_date)->first();
            if($weekopened){
              $openedorders  = ($weekopened->allorders)?? 0;
              $finishedorders= ($weekaccepted->finishedorders)?? 0;
            }
            $totalAccept   = ( $finishedorders == 0 )? '0%' : round( ( $finishedorders / $openedorders ) * 100 ,1).'%';

            $dataOrders = [];$num_orders = 0;$totalPrices_of_orders=0;
            $num_cash_orders = 0; $totalPrices_of_cash_orders = 0;
            $total_vat = 0;$total_wasl = 0;
            /*start done orders*/
            if($orders = Order::where(['captain_id'=>$user->id,'status'=>'finished'])->where('created_at','>=',$start_date)->where('created_at','<=',$end_date)->orderBy('created_at','DESC')->get()){
                foreach($orders as $order){
                     $num_orders += 1;
                     $totalPrices_of_orders += floatval($order->price);
                     $total_vat += floatval($order->vat);
                     $total_wasl += floatval($order->wasl);
                     //calculate total cash captain take from clients
                     if($order->payment_type == 'cash'){
                        $num_cash_orders +=1;
                        $totalPrices_of_cash_orders += floatval($order->total_payments);
                     }
                }
            }
            $totalPrices_of_orders = round($totalPrices_of_orders,2);
            $total_vat = round($total_vat,2);
            $total_wasl = round($total_wasl,2);
            /*end done orders*/
            /*start withdraw orders of captain negative*/
            $num_withdraw_orders = 0; $totalPrices_of_withdraw_orders = 0;
            if($withdrawOrders = usersOrdersHistory::where(['captain_id'=>$user->id,'status'=>'withdraw'])->where('created_at','>=',$start_date)->where('created_at','<=',$end_date)->get()){
              foreach($withdrawOrders as $withdrawOrder) {
                     $num_withdraw_orders += 1;
                     $totalPrices_of_withdraw_orders += floatval($withdrawOrder->price);
              }
            }
            /*end withdraw orders of captain negative*/
            /*start client closed orders added to captain positive*/
            $num_closed_orders = 0; $totalPrices_of_closed_orders = 0;
            if($closedOrders = usersOrdersHistory::where(['captain_id'=>$user->id,'status'=>'closed'])->where('created_at','>=',$start_date)->where('created_at','<=',$end_date)->get()){
              foreach($closedOrders as $closedOrder) {
                     $num_closed_orders += 1;
                     $totalPrices_of_closed_orders += floatval($closedOrder->price);
              }
            }
            /*end client closed orders added to captain positive*/
            /*start rewards*/
            $num_rewards_orders = 0;
            $totalPrices_of_rewards_orders = 0;
            if($rewardsOrders = rewardsHistory::where('user_id','=',$user->id)->where('created_at','>=',$start_date)->where('created_at','<=',$end_date)->get()){
              foreach($rewardsOrders as $rewardsOrder) {
                     $num_rewards_orders += 1;
                     $totalPrices_of_rewards_orders += floatval($rewardsOrder->points);
              }
            }
            /*end rewards*/
            /*start guarantees*/
            $num_guarantee_orders = 0;
            $totalPrices_of_guarantee_orders = 0;
            if($guaranteeOrders = GuaranteesHistory::where('user_id','=',$user->id)->where('created_at','>=',$start_date)->where('created_at','<=',$end_date)->get()){
              foreach($guaranteeOrders as $guaranteeOrder) {
                     $num_guarantee_orders += 1;
                     $totalPrices_of_guarantee_orders += floatval($guaranteeOrder->guarantee);
              }
            }
            /*end guarantees*/
            //all money of captain orders after remove site percentage
            $site_percentage = setting('site_percentage');
            $app_commission  =  round( ( $site_percentage / 100) * $totalPrices_of_orders ,2) ;
            $totalPrices_of_orders          = round($totalPrices_of_orders - $app_commission , 2);
            $totalPrices_of_withdraw_orders = ($totalPrices_of_withdraw_orders * -1 ) + $totalPrices_of_closed_orders ;
            $num_withdraw_orders            = $num_withdraw_orders + $num_closed_orders;
            $totalPrices_of_cash_orders     = $totalPrices_of_cash_orders     * -1 ;
            $total = round($totalPrices_of_orders + $totalPrices_of_rewards_orders + $totalPrices_of_guarantee_orders + $totalPrices_of_withdraw_orders /*+ $totalPrices_of_cash_orders*/ , 2);


            $new_start_date = Date::parse($start_date)->format('j/m/Y');
            $new_end_date   = Date::parse($end_date)->format('j/m/Y');
            $currency   = ($user->country)?$user->country->{"currency_$lang"}:setting('site_currency_'.$lang);

            //***** growth rate *******//
                //get previous month num orders
                if(date('l') == $start_day){
                    $growth_start_date = date('Y-m-d H:i:s',strtotime('-'.($page)." $start_day 00:00:00"));
                    $growth_end_date   = date('Y-m-d H:i:s',strtotime('-'.($page)." $previous_day 23:59:59 "));
                }elseif(date('l') == $previous_day){
                    $growth_start_date = date('Y-m-d H:i:s',strtotime('-'.($page+1)." $start_day 00:00:00"));
                    $growth_end_date   = date('Y-m-d H:i:s',strtotime('-'.($page)." $previous_day 23:59:59 "));
                }else{
                    $growth_start_date = date('Y-m-d H:i:s',strtotime('-'.($page+1)." $start_day 00:00:00"));
                    $growth_end_date   = date('Y-m-d H:i:s',strtotime('-'.($page)." $previous_day 23:59:59 "));
                }


            $growth_dataOrders = [];$growth_num_orders = 0;$growth_totalPrices_of_orders=0;
            $growth_num_cash_orders = 0; $growth_totalPrices_of_cash_orders = 0;
            $growth_total_vat = 0;$growth_total_wasl = 0;
            /*start done orders*/
            if($growth_orders = Order::where(['captain_id'=>$user->id,'status'=>'finished'])->where('created_at','>=',$growth_start_date)->where('created_at','<=',$growth_end_date)->orderBy('created_at','DESC')->get()){
                foreach($growth_orders as $growth_order){
                     $growth_num_orders += 1;
                     $growth_totalPrices_of_orders += floatval($growth_order->price);
                     $growth_total_vat += floatval($growth_order->vat);
                     $growth_total_wasl += floatval($growth_order->wasl);
                     //calculate total cash captain take from clients
                     if($growth_order->payment_type == 'cash'){
                        $growth_num_cash_orders +=1;
                        $growth_totalPrices_of_cash_orders += floatval($growth_order->total_payments);
                     }
                }
            }
            $growth_totalPrices_of_orders = round($growth_totalPrices_of_orders,2);
            $growth_total_vat = round($growth_total_vat,2);
            $growth_total_wasl = round($growth_total_wasl,2);
            /*end done orders*/
            /*start withdraw orders of captain negative*/
            $growth_num_withdraw_orders = 0; $growth_totalPrices_of_withdraw_orders = 0;
            if($growth_withdrawOrders = usersOrdersHistory::where(['captain_id'=>$user->id,'status'=>'withdraw'])->where('created_at','>=',$growth_start_date)->where('created_at','<=',$growth_end_date)->get()){
              foreach($growth_withdrawOrders as $growth_withdrawOrder) {
                     $growth_num_withdraw_orders += 1;
                     $growth_totalPrices_of_withdraw_orders += floatval($growth_withdrawOrder->price);
              }
            }
            /*end withdraw orders of captain negative*/
            /*start client closed orders added to captain positive*/
            $growth_num_closed_orders = 0; $growth_totalPrices_of_closed_orders = 0;
            if($growth_closedOrders = usersOrdersHistory::where(['captain_id'=>$user->id,'status'=>'closed'])->where('created_at','>=',$growth_start_date)->where('created_at','<=',$growth_end_date)->get()){
              foreach($growth_closedOrders as $growth_closedOrder) {
                     $growth_num_closed_orders += 1;
                     $growth_totalPrices_of_closed_orders += floatval($growth_closedOrder->price);
              }
            }
            /*end client closed orders added to captain positive*/
            /*start rewards*/
            $growth_num_rewards_orders = 0;
            $growth_totalPrices_of_rewards_orders = 0;
            if($growth_rewardsOrders = rewardsHistory::where('user_id','=',$user->id)->where('created_at','>=',$start_date)->where('created_at','<=',$end_date)->get()){
              foreach($growth_rewardsOrders as $growth_rewardsOrder) {
                     $growth_num_rewards_orders += 1;
                     $growth_totalPrices_of_rewards_orders += floatval($growth_rewardsOrder->points);
              }
            }
            /*end rewards*/
            /*start guarantees*/
            $growth_num_guarantee_orders = 0;
            $growth_totalPrices_of_guarantee_orders = 0;
            if($growth_guaranteeOrders = GuaranteesHistory::where('user_id','=',$user->id)->where('created_at','>=',$growth_start_date)->where('created_at','<=',$growth_end_date)->get()){
              foreach($growth_guaranteeOrders as $growth_guaranteeOrder) {
                     $growth_num_guarantee_orders += 1;
                     $growth_totalPrices_of_guarantee_orders += floatval($growth_guaranteeOrder->guarantee);
              }
            }
            /*end guarantees*/
            //all money of captain orders after remove site percentage
            $growth_app_commission  =  round( ( $site_percentage / 100) * $growth_totalPrices_of_orders ,2) ;
            $growth_totalPrices_of_orders          = round($growth_totalPrices_of_orders - $growth_app_commission , 2);
            $growth_totalPrices_of_withdraw_orders = ($growth_totalPrices_of_withdraw_orders * -1 ) + $growth_totalPrices_of_closed_orders ;
            $growth_num_withdraw_orders            = $growth_num_withdraw_orders + $growth_num_closed_orders;
            $growth_totalPrices_of_cash_orders     = $growth_totalPrices_of_cash_orders     * -1 ;
            $growth_total = round($growth_totalPrices_of_orders + $growth_totalPrices_of_rewards_orders + $growth_totalPrices_of_guarantee_orders + $growth_totalPrices_of_withdraw_orders /*+ $totalPrices_of_cash_orders*/ , 2);

                $this_month_orders     = $total;
                $previous_month_orders = $growth_total;
                $difference  = $this_month_orders - $previous_month_orders;
                $growth_rate = ( $previous_month_orders > 0 )? $difference/$previous_month_orders : $this_month_orders;
                $growth_rate = $growth_rate * 100 ;
                $growth_rate = round( $growth_rate,1) ;

                // $this_month_orders     = Order::where(['captain_id'=>$user->id,'status'=>'finished'])->where('created_at','>=',$start_date)->where('created_at','<=',$end_date)->count();
                // $previous_month_orders = Order::where(['captain_id'=>$user->id,'status'=>'finished'])->where('created_at','>=',$growth_start_date)->where('created_at','<=',$growth_end_date)->count();
               
                // $difference  = $this_month_orders - $previous_month_orders;
                // $growth_rate = ( $previous_month_orders > 0 )? $difference/$previous_month_orders : $this_month_orders;
                // $growth_rate = $growth_rate * 100 ;
                // $growth_rate = round( $growth_rate,1) ;


            //***** growth rate *******//
            

            //day_profits_average 
                $day_profits_average = round( (($num_orders != 0)? $total / $num_orders : 0) , 2);
            //day_profits_average 


            $days[__('order.'.$start_day)] = date('Y-m-d',strtotime($start_date));
            $days[__('order.'.date('l',strtotime("+1 day $start_date")) )] = date('Y-m-d',strtotime("$start_date") + 60*60*24*1);
            $days[__('order.'.date('l',strtotime("+2 day $start_date")) )] = date('Y-m-d',strtotime("$start_date") + 60*60*24*2);
            $days[__('order.'.date('l',strtotime("+3 day $start_date")) )] = date('Y-m-d',strtotime("$start_date") + 60*60*24*3);
            $days[__('order.'.date('l',strtotime("+4 day $start_date")) )] = date('Y-m-d',strtotime("$start_date") + 60*60*24*4);
            $days[__('order.'.date('l',strtotime("+5 day $start_date")) )] = date('Y-m-d',strtotime("$start_date") + 60*60*24*5);
            $days[__('order.'.date('l',strtotime("+6 day $start_date")) )] = date('Y-m-d',strtotime("$start_date") + 60*60*24*6);
            $previous_day_orders = 0; $days_names = []; $days_values = [];
            foreach ($days as $key => $value) {
                
                $this_day_orders = Order::where(['captain_id'=>$user->id,'status'=>'finished'])->where('created_at','like','%'.$value.'%')->sum('price');
                $day_commission =  ($site_percentage / 100) * $this_day_orders ;
                $this_day_orders          = round($this_day_orders - $day_commission , 2);
                
                $days[$key] = $this_day_orders;//.' sar';
                
                // $diff = $this_day_orders - $previous_day_orders;
                // $orders_rate = ( $previous_day_orders > 0 )? $diff / $previous_day_orders : $this_day_orders;
                // $orders_rate = $orders_rate * 100 ;
                // $days[$key] .= ' <'.$orders_rate.'%>';
                $previous_day_orders = $this_day_orders;
                
                $days_names[] = $key;
                $days_values[] = $days[$key];
            }

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
        return view('user.captainPerformance',compact('user','page','orders','num_orders','totalPrices_of_orders','num_withdraw_orders','totalPrices_of_withdraw_orders','num_rewards_orders','totalPrices_of_rewards_orders','num_guarantee_orders','totalPrices_of_guarantee_orders','num_cash_orders','totalPrices_of_cash_orders','total','totalRate','totalAvailableHours','totalAccept','start_date','end_date','new_start_date','new_end_date','currency','captainMoneystatus','captainMoney','lang','app_commission','total_vat','total_wasl','growth_rate','day_profits_average','days_names','days_values'));
    }

    public function captainAvailableTimes($userid ='', $page = 1 , $lang = 'ar'){
            $user = User::findOrFail($userid);
            $page = ($page)?? 1;
            $lang  = ($lang)??'ar';
            app()->setLocale($lang);
            \Carbon\Carbon::setLocale($lang);

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

            $availableHours = userAvailableTimes::select('*', DB::raw('SUM(num_minutes) as total_minutes'))->where('user_id','=',$user->id)->where('created_at','>=',$start_date)->where('created_at','<=',$end_date)->groupBy('date')->orderBy('created_at','ASC')->get();
        return view('user.captainAvailableTimes',compact('user','availableHours'));
    }

    public function captainOrdersRatings($userid ='', $page = 1 ,$lang ='ar'){
            $user = User::findOrFail($userid);
            $page = ($page)?? 1;
            $lang  = ($lang)??'ar';
            app()->setLocale($lang);
            \Carbon\Carbon::setLocale($lang);

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
            $ratings = userOrdersRatings::select('*', DB::raw('AVG(rate) as rate_average, COUNT(*) as count_ratings'))->where('user_id','=',$user->id)->where('created_at','>=',$start_date)->where('created_at','<=',$end_date)->groupBy('date')->orderBy('date','ASC')->get();
        return view('user.captainOrdersRatings',compact('user','ratings'));
    }

    public function captainOrdersAcceptance($userid ='', $page = 1 , $lang='ar'){
            $user = User::findOrFail($userid);
            $page = ($page)?? 1;
            $lang  = ($lang)??'ar';
            app()->setLocale($lang);
            \Carbon\Carbon::setLocale($lang);
                        
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
            $userorders = DB::table('users_orders_history')->select('*', DB::raw("COUNT(*) as count_orders"))->where('captain_id','=',$user->id)->where('created_at','>=',$start_date)->where('created_at','<=',$end_date)->groupBy('date')->orderBy('created_at','ASC')->get();
        return view('user.captainOrdersAcceptance',compact('user','userorders'));
    }    

    public function guideVideo(){
      $this->data['guide_video'] = setting('guide_video');
      return view('user.guideVideo',$this->data);
    }

    public function captainSignupForm($friend_code =''){
        $this->data['friend_code']     = $friend_code;
        $this->data['countries']       = Country::orderBy('name_ar','ASC')->get();
        $this->data['current_country'] = (isset(currentCountry()['iso']))? currentCountry()['iso'] : 'SA';
        $country_id = 1;
        if($country = Country::where(['iso2'=>$this->data['current_country']])->first()){
            $country_id = $country->id;
        }
        $this->data['cities']          = City::where(['country_id'=>$country_id])->orderBy('name_ar','ASC')->get();
        $this->data['welcomePageSettings'] = WelcomePageSetting::pluck('value', 'key');
        return view('user.captainSignupForm',$this->data);
    }

    public function captainSignup(Request $request){
        $this->validate(request(), [
            'name'                => 'required|min:3',
            'country_id'          => 'required|integer',
            'phone'               => 'required|numeric',
            'password'            => 'required|alpha_dash|between:6,50',
            'email'               => 'required|email',
            'birthdate'           => 'required',
            'identity_number'     => 'required|min:5',
            'gender'              => 'required',
            'city_id'             => 'required|integer',
            'friend_code'         => 'nullable',
        ]);
            $number         = convert2english($request->phone);
            $phone          = phoneValidate($number);
            if($meta = userMeta::where(['phone' =>$phone])->first()){
              $meta->delete();
            }            
           $meta = new userMeta();
           // $meta->user_id            = $user->id;
           $meta->name               = $request->name;
           if($country = Country::find($request->country_id)){
              $meta->country_id         = $country->id;
              $meta->phonekey           = str_replace('00', '+', $country->phonekey);
            }
           $meta->phone              = $phone;
           $meta->password           = Hash::make($request->password);
           $meta->email              = $request->email;
           $meta->birthdate          = date('d-m-Y',strtotime($request->birthdate));
           $meta->identity_number    = $request->identity_number;
           $meta->gender             = $request->gender;
           $meta->city_id            = $request->city_id;
           $meta->friend_code        = $request->friend_code;
           $meta->save();
           Session::put('meta_id',$meta->id);
           return redirect('captainSignupForm3');
           // return redirect('captainSignupForm')->with('successmsg','تم ارسال طلبك بنجاح ستقوم الادارة بالتواصل معك,شكراً.');

    } 

    // public function captainSignupForm2(){
    //     return view('user.captainSignupForm2',$this->data);
    // }

    // public function captainSignup2(Request $request){
    //     $this->validate(request(), [
    //         'captain_type'  => 'required',
    //         'service_in'    => 'required',
    //         'service_type'  => 'required'
    //     ]);
    //        if($meta = userMeta::find(Session::get('meta_id'))){
    //            $meta->captain_type    = $request->captain_type;
    //            $meta->service_in      = $request->service_in;
    //            $meta->service_type    = $request->service_type;
    //            $meta->save();
    //            return redirect('captainSignupForm3');
    //        }
    //        return redirect('captainSignupForm')->with('msg','من فضلك قم باعادة ادخال البيانات المطلوبة مرة أخري.');
    // } 

    public function captainSignupForm3(){
        return view('user.captainSignupForm3',$this->data);
    }

    public function captainSignup3(Request $request){
        $this->validate(request(), [
            'identity_card'   => 'required|mimes:jpeg,png,jpg,gif,doc,docx,pdf',
            'driving_license' => 'required|mimes:jpeg,png,jpg,gif,doc,docx,pdf',
            'car_form'        => 'required|mimes:jpeg,png,jpg,gif,doc,docx,pdf',
            'iban'            => 'required|mimes:jpeg,png,jpg,gif,doc,docx,pdf',
            'car_insurance'   => 'required|mimes:jpeg,png,jpg,gif,doc,docx,pdf',
            'personal_image'  => 'required|mimes:jpeg,png,jpg,gif,doc,docx,pdf',
            'authorization_image'        => 'nullable|mimes:jpeg,png,jpg,gif,doc,docx,pdf',
        ]);

           if($meta = userMeta::find(Session::get('meta_id'))){
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
                if($request->hasFile('iban')) {
                    $image           = $request->file('iban');
                    $name            = md5($request->file('iban')->getClientOriginalName()).time().rand(99999,1000000).'.'.$image->getClientOriginalExtension();
                    $destinationPath = public_path('/img/user/usermeta');
                    $imagePath       = $destinationPath. "/".  $name;
                    $image->move($destinationPath, $name);
                    $meta->iban    = $name;
                }   
                if($request->hasFile('car_insurance')) {
                    $image           = $request->file('car_insurance');
                    $name            = md5($request->file('car_insurance')->getClientOriginalName()).time().rand(99999,1000000).'.'.$image->getClientOriginalExtension();
                    $destinationPath = public_path('/img/user/usermeta');
                    $imagePath       = $destinationPath. "/".  $name;
                    $image->move($destinationPath, $name);
                    $meta->car_insurance    = $name;
                } 
                if($request->hasFile('personal_image')) {
                    $image           = $request->file('personal_image');
                    $name            = md5($request->file('personal_image')->getClientOriginalName()).time().rand(99999,1000000).'.'.$image->getClientOriginalExtension();
                    $destinationPath = public_path('/img/user/usermeta');
                    $imagePath       = $destinationPath. "/".  $name;
                    $image->move($destinationPath, $name);
                    $meta->personal_image    = $name;
                }                 
                                             
               $meta->save();
               return redirect('captainSignupForm4');
           }
           return redirect('captainSignupForm')->with('msg','من فضلك قم باعادة ادخال البيانات المطلوبة مرة أخري.');
    }

    public function captainSignupForm4(){
        $this->data['plateTypes'] = ['1' => 'خصوصي' ,'2' => 'نقل عام' ,'3' => 'نقل خاص' ,'4' => 'حافلة صغيرة عامة', '5' => 'حافلة صغيرة خاصة', '6' => 'اجرة' ,'7' => 'معدات ثقيلة', '8' => 'تصدير' ,'9' =>'دبلوماسي' ,/*'10' =>'دراجة نارية',*/ '11' => 'مؤقت'];
        return view('user.captainSignupForm4',$this->data);
    }

    public function captainSignup4(Request $request){
        $this->validate(request(), [
            'car_image'          => 'required|mimes:jpeg,png,jpg,gif,doc,docx,pdf',
            'car_type'           => 'required|min:3',
            'car_model'          => 'required|min:2',
            'car_color'          => 'required|min:2',
            'manufacturing_year' => 'required|min:2',
            'car_numbers'        => 'required',
            'car_letters'        => 'required',
            'sequenceNumber'     => 'required',
            'plateType'          => 'required'
        ]);
        if($request->reading_terms == 'on'){
           if($meta = userMeta::find(Session::get('meta_id'))){
                $plateTypes = ['1' => 'خصوصي' ,'2' => 'نقل عام' ,'3' => 'نقل خاص' ,'4' => 'حافلة صغيرة عامة', '5' => 'حافلة صغيرة خاصة', '6' => 'اجرة' ,'7' => 'معدات ثقيلة', '8' => 'تصدير' ,'9' =>'دبلوماسي' ,/*'10' =>'دراجة نارية',*/ '11' => 'مؤقت'];
                if($request->hasFile('car_image')) {
                    $image           = $request->file('car_image');
                    $name            = md5($request->file('car_image')->getClientOriginalName()).time().rand(99999,1000000).'.'.$image->getClientOriginalExtension();
                    $destinationPath = public_path('/img/user/usermeta');
                    $imagePath       = $destinationPath. "/".  $name;
                    $image->move($destinationPath, $name);
                    $meta->car_image    = $name;
                }             
               $meta->car_type           = $request->car_type;
               $meta->car_model          = $request->car_model;
               $meta->car_color          = $request->car_color;
               $meta->manufacturing_year = $request->manufacturing_year;
               $meta->car_numbers        = $request->car_numbers;
               $meta->car_letters        = (strpos($request->car_letters, ' ') !== false )? $request->car_letters :  implode(' ',str_split($request->car_letters));
               $meta->sequenceNumber     = $request->sequenceNumber;
               $meta->plateType          = $request->plateType;
               $meta->plateType_txt      = (isset($plateTypes[$request->plateType]))? $plateTypes[$request->plateType] : '' ;
               $meta->complete           = 'true';
               $meta->save();  

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
              
               return redirect('captainSignupForm')->with('successmsg','تم ارسال طلبك بنجاح ستقوم الادارة بالتواصل معك,شكراً.');
           }
           return redirect('captainSignupForm')->with('msg','من فضلك قم باعادة ادخال البيانات المطلوبة مرة أخري.');
       }else{
           return back()->with('msg','يجب عليك الموافقة علي الشروط والأحكام.');
       }
        return redirect('captainSignupForm');
    } 

    #allusers page
    public function allUsers(){
        $users = User::with('Role')->latest()->get();
        $roles = Role::latest()->get();
        $countries = Country::orderBy('iso2','ASC')->get();
        return view('dashboard.users.allusers',compact('users','roles','countries'));
    }

    public function downloadAllUsers(){
        return Excel::download( new UsersExport('allusers'), 'AllUsers.xlsx');        
    }
    
    #users page
    public function users(){
        $users = User::with('Role')->where('role','=','0')->where('captain','=','false')->latest()->get();
        $roles = Role::latest()->get();
        $countries = Country::orderBy('iso2','ASC')->get();
        return view('dashboard.users.users',compact('users','roles','countries'));
    }

    public function downloadClients(){
        return Excel::download( new UsersExport('clients'), 'clients.xlsx');        
    }

    public function providers(){
        $users = User::with('Role')->where('role','=','0')->where('captain','=','true')->latest()->get();
        $roles = Role::latest()->get();
        $countries = Country::orderBy('iso2','ASC')->get();
        return view('dashboard.users.providers',compact('users','roles','countries'));
    }
    public function downloadProviders(){
        return Excel::download( new UsersExport('providers'), 'providers.xlsx');        
    }

    public function blockedProviders(){
      $users = User::with('Role')->where('role','=','0')->where('captain','=','true')->where('active','=','block')->latest()->get();
      $roles = Role::latest()->get();
      $countries = Country::orderBy('iso2','ASC')->get();
      return view('dashboard.users.providers',compact('users','roles','countries'));
    }

    public function onlineProviders(){
      $users = User::with('Role')->where('role','=','0')->where('captain','=','true')->whereNotNull('last_activity')->where('last_activity','>=',date('Y-m-d H:i:s',strtotime('-24 hours')))->latest()->get();
      $roles = Role::latest()->get();
      $countries = Country::orderBy('iso2','ASC')->get();
      return view('dashboard.users.onlineProviders',compact('users','roles','countries'));
    }
    public function offlineProviders(){
      $users = User::with('Role')->where('role','=','0')->where('captain','=','true')->where('last_activity','<',date('Y-m-d H:i:s',strtotime('-24 hours')))
                                  ->orwhere('role','=','0')->where('captain','=','true')->whereNull('last_activity')                          
                                  ->latest()->get();
      $roles = Role::latest()->get();
      $countries = Country::orderBy('iso2','ASC')->get();
      return view('dashboard.users.offlineProviders',compact('users','roles','countries'));
    }
    
    public function supervisiors(){
        $users = User::with('Role')->where('role','>','0')->latest()->get();
        $roles = Role::latest()->get();
        $countries = Country::orderBy('iso2','ASC')->get();
        return view('dashboard.users.supervisiors',compact('users','roles','countries'));
    }

    public function downloadSupervisiors(){
        return Excel::download( new UsersExport('supervisiors'), 'supervisiors.xlsx');        
    }

    public function reviewers(){
      $users = User::with('Role')->where('role','>','0')->where('type','reviewer')->latest()->get();
      $roles = Role::latest()->get();
      $countries = Country::orderBy('iso2','ASC')->get();
      return view('dashboard.users.reviewers',compact('users','roles','countries'));
    }

    public function downloadReviewers(){
        return Excel::download( new UsersExport('reviewers'), 'reviewers.xlsx');        
    }

    public function ambassadors(){
      $users = User::with('Role')->where('role','>','0')->where('type','ambassador')->latest()->get();
      $roles = Role::latest()->get();
      $countries = Country::orderBy('iso2','ASC')->get();
      return view('dashboard.users.ambassadors',compact('users','roles','countries'));
    }

    public function captainsAddedByAmbassador($ambassador_id = null){
      if($ambassador_id){
        $ids = userMeta::where('creator_id',$ambassador_id)->pluck('user_id')->toArray();
        $users = User::with('Role')->whereIn('id',$ids)->where('captain','=','true')->latest()->get();
      }else{
        $ids = userMeta::where('creator_id',Auth()->user()->id)->pluck('user_id')->toArray();
        $users = User::with('Role')->whereIn('id',$ids)->where('captain','=','true')->latest()->get();
      }

      $roles = Role::latest()->get();
      $countries = Country::orderBy('iso2','ASC')->get();
      return view('dashboard.users.captainsAddedByAmbassador',compact('users','roles','countries'));
    }

    public function downloadAmbassadors(){
        return Excel::download( new UsersExport('ambassadors'), 'ambassadors.xlsx');        
    }

    public function usersRegisterReasons(){
        $userReasons = User::where('reason','!=','')->latest()->get();
        return view('dashboard.users.usersRegisterReasons',compact('userReasons'));
    }

    public function completeUsersMeta(){
      $metas = userMeta::where('complete','=','true')->latest()->get();
      $all_metas = userMeta::count();
      $complete_metas = userMeta::where('complete','=','true')->count();
      $uncomplete_metas = userMeta::where('complete','=','false')->count();
      $agree_metas = userMeta::where('status','=','agree')->count();
      $refused_metas = userMeta::where('status','=','refused')->count();
      $pending_metas = userMeta::where('status','=','pending')->count();
      return view('dashboard.users.usermetas',get_defined_vars());
  }

  public function uncompleteUsersMeta(){
      $metas = userMeta::where('complete','=','false')->latest()->get();
      $all_metas = userMeta::count();
      // $complete_metas = userMeta::where('complete','=','true')->count();
      $uncomplete_metas = userMeta::where('complete','=','false')->count();
      $agree_metas = userMeta::where('status','=','agree')->count();
      $refused_metas = userMeta::where('status','=','refused')->count();
      $pending_metas = userMeta::where('status','=','pending')->where('complete','=','true')->count();
      return view('dashboard.users.usermetas',get_defined_vars());
  }

  public function downloadUncompleteUserMeta(){
    return Excel::download( new UserMetaExport(), 'uncomplete_captain_requests.xlsx');        
  }

  public function emailUncompleteUsersMeta(Request $request){
    $this->validate($request,[
        'email_message' =>'required|min:1'
    ]);

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
        $users = userMeta::whereNotNull('email')->where('complete','=','false')->where('status','=','pending')->get();
        foreach($users as $u){
            Mail::to($u->email)->send(new PublicMessage(  $request->email_message  ));
        }
        return back()->with('success','تم ارسال الرساله');
    }
}     

public function SmsMessageUncompleteUsersMeta(Request $request){
  $this->validate($request,[
      'sms_message' =>'required'
  ]);
  $users = userMeta::where('complete','=','false')->where('status','=','pending')->get();
  $numbers = '';
  foreach ($users as $u){
    $numbers.= $u->phonekey.''.$u->phone.',';
  }
  send_mobile_sms($numbers,$request->sms_message);
  return back()->with('success','تم الارسال بنجاح.'); 
}

public function notificationUncompleteUsersMeta(Request $request){
  $this->validate($request,[
      'notification_message' =>'required',
      'notification_title'   => 'nullable'
  ]);
      $devices = DB::table('user_devices')->join('user_meta', 'user_meta.user_id', '=', 'user_devices.user_id')
                                          ->where('user_meta.complete','=','false')->where('user_meta.status','=','pending')
                                          ->select('user_devices.device_id','user_devices.device_type','user_devices.show_ads','user_meta.user_id')
                                          ->get(); 
      #use FCM or One Signal Here :) 
      $notify_title = ($request->notification_title)? $request->notification_title : setting('site_title');
      $message_ar   = $request->notification_message;
      $message_en   = $request->notification_message;
      $data = ['title' => $notify_title,'message_en'=>$message_en,'message_ar'=>$message_ar,'key'=>'from_admin'];
      sendNotification($devices, $message_ar,$notify_title,$data);
          foreach($devices as $device){
              $notifications[] = ['user_id'      => $device->user_id,
                                  'notifier_id'  => '',
                                  'message'      => $message_ar,
                                  'title'        => $notify_title,
                                  'data'         => 'user_id:'.$device->user_id,
                                  'order_status' => '',
                                  'key'          => 'from_admin',
                                  'created_at'   => date('Y-m-d H:i:s')
                                  ];
          }
          $result = array_unique($notifications,SORT_REGULAR);
          Notifications::insert($result);
      return back()->with('success','تم الارسال بنجاح.'); 
}

  public function pendingUsersMeta(){
    $metas = userMeta::where('status','=','pending')->where('complete','=','true')->latest()->get();
    $all_metas = userMeta::count();
    // $complete_metas = userMeta::where('complete','=','true')->count();
    $uncomplete_metas = userMeta::where('complete','=','false')->count();
    $agree_metas = userMeta::where('status','=','agree')->count();
    $refused_metas = userMeta::where('status','=','refused')->count();
    $pending_metas = userMeta::where('status','=','pending')->where('complete','=','true')->count();
    return view('dashboard.users.usermetas',get_defined_vars());
}

public function agreedUsersMeta(){
  $metas = userMeta::where('status','=','agree')->latest()->get();
  $all_metas = userMeta::count();
  // $complete_metas = userMeta::where('complete','=','true')->count();
  $uncomplete_metas = userMeta::where('complete','=','false')->count();
  $agree_metas = userMeta::where('status','=','agree')->count();
  $refused_metas = userMeta::where('status','=','refused')->count();
  $pending_metas = userMeta::where('status','=','pending')->where('complete','=','true')->count();
  return view('dashboard.users.usermetas',get_defined_vars());
}

public function refusedUsersMeta(){
  $metas = userMeta::where('status','=','refused')->latest()->get();
  $all_metas = userMeta::count();
  // $complete_metas = userMeta::where('complete','=','true')->count();
  $uncomplete_metas = userMeta::where('complete','=','false')->count();
  $agree_metas = userMeta::where('status','=','agree')->count();
  $refused_metas = userMeta::where('status','=','refused')->count();
  $pending_metas = userMeta::where('status','=','pending')->where('complete','=','true')->count();
  return view('dashboard.users.usermetas',get_defined_vars());
}

public function reviewerAgreedUsersMeta($reviewer_id = null){
  $metas = userMeta::where('reviewer_id','=',$reviewer_id)->where('status','=','agree')->latest()->get();
  return view('dashboard.users.reviewerUsersMetas',get_defined_vars());
}

public function reviewerRefusedUsersMeta($reviewer_id = null){
  $metas = userMeta::where('reviewer_id','=',$reviewer_id)->where('status','=','refused')->latest()->get();
  return view('dashboard.users.reviewerUsersMetas',get_defined_vars());
}
    #show message
    public function userMeta($id =''){
        
      // $url  = 'https://wasl.api.elm.sa/api/dispatching/v2/drivers';
      // $data = array("driver"=> array("identityNumber"=>"1234567890",
      //                           "dateOfBirthHijri"=> "1411/01/01",
      //                           "dateOfBirthGregorian"=> "1990-01-01",  
      //                           "emailAddress"=> "address@email.com", 
      //                           "mobileNumber"=> "+966512345678",
      //                          ),
      //               "vehicle"=> array("sequenceNumber"=> "123456879",
      //                           "plateLetterRight"=> "ا",
      //                           "plateLetterMiddle"=> "ا",
      //                           "plateLetterLeft"=> "ا",
      //                           "plateNumber"=> "1234",
      //                           "plateType"=> "1"  
      //                         )
      //               );

      // // use key 'http' even if you send the request to https://...
      // $options = array(
      //     'http' => array(
      //         'header'  => "Content-Type:application/json,
      //                       client-id:473B40CA-6971-40B4-ADB0-5E9A47E3749A,
      //                       app-id:f3c26073,
      //                       app-key:b267b8693440fbbf20d32924a63f3ec4",
      //         'method'  => 'POST',
      //         'content' => http_build_query($data)
      //     )
      // );
      // $context  = stream_context_create($options);
      // $result   = file_get_contents($url, false, $context);
      // if ($result === FALSE) { 
      //      dd('false');
      // }
      // dd($result);

// # Create a connection
// $data = array("name" => "Hagrid", "age" => "36");                                                                    
// $data_string = json_encode($data);                                                                                                                                                                                         
// $ch = curl_init('https://wasl.api.elm.sa/api/dispatching/v2/drivers');                                                                      
// curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
// curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  
// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
// curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
//     'Content-Type: application/json',                                                                                
//     'client-id:473B40CA-6971-40B4-ADB0-5E9A47E3749A',
//     'app-id:f3c26073',
//     'app-key:b267b8693440fbbf20d32924a63f3ec4',                                                                    
// ));                                                                                                                  
                                                                                                                     
// $result = curl_exec($ch);
// dd($result);

        $usermeta = userMeta::findOrFail($id);
        $usermeta->seen = 'true';
        if(Auth::user()->type == 'reviewer'){
          if(!$usermeta->reviewer_id){
            $usermeta->reviewer_id = Auth::user()->id;
          }else{
            return back()->with('danger','عذراً تم استلام الطلب من مراجع آخر'); 
          }
        }
        $usermeta->update();
        $cartypes = carTypes::orderBy('id','ASC')->get();
        return view('dashboard.users.userMeta',compact('usermeta','cartypes'));
    }

  public function addUserMeta(){
    $countries = Country::orderBy('name_ar','ASC')->get();
    $country_id = 1;
    if($country = Country::where(['iso2'=>'SA'])->first()){
        $country_id = $country->id;
    }
    $cities   = City::where(['country_id'=>$country_id])->orderBy('name_ar','ASC')->get();
    $plateTypes = ['1' => 'خصوصي' ,'2' => 'نقل عام' ,'3' => 'نقل خاص' ,'4' => 'حافلة صغيرة عامة', '5' => 'حافلة صغيرة خاصة', '6' => 'اجرة' ,'7' => 'معدات ثقيلة', '8' => 'تصدير' ,'9' =>'دبلوماسي' ,/*'10' =>'دراجة نارية',*/ '11' => 'مؤقت'];
    return view('dashboard.users.addUserMeta',compact('countries','plateTypes','cities'));
  }  

  public function storeUserMeta(Request $request){
    $this->validate($request,[
        'name'        => 'required',
        'phone'       => 'required',
        'email'       => 'required',
        'identity_number'     => 'required',
        'car_type'            => 'required',
        'car_model'           => 'required',
        'car_color'           => 'required',
        'manufacturing_year'  => 'required',
        'car_numbers'         => 'required',
        'car_letters'         => 'required',
        'sequenceNumber'      => 'required',
    ]); 

      $number         = convert2english($request->phone);
      $phone          = phoneValidate($number);
      if($meta = userMeta::where(['phone' =>$phone])->first()){
        $meta->delete();
      }            
      $meta = new userMeta();
      $meta->creator_id  = Auth::user()->id;
      $meta->name     = $request->name;
      $number         = convert2english($request->phone);
      $phone          = phoneValidate($number);
      $meta = userMeta::where(['phone' =>$phone])->first();
      if(!$meta){
          $meta = new userMeta();
          $meta->phone              = $phone;
      }

      if($country = Country::find($request->country_id)){
        $meta->country_id         = $country->id;
        $meta->phonekey           = str_replace('00', '+', $country->phonekey);
      }
      $meta->phone              = $phone;
      $meta->email              = $request->email;
      $meta->birthdate          = date('Y-m-d',strtotime(convert2english($request->birthdate) ));
      // $meta->birthdate_hijri    = date('Y-m-d',strtotime(convert2english($request->birthdate_hijri) ));
      $meta->identity_number    = convert2english($request->identity_number);
      $meta->gender             = $request->gender;
      // $meta->captain_type       = $request->captain_type;
      $meta->city_id            = $request->city_id;
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
        //  if($request->hasFile('iban')) {
        //       $image           = $request->file('iban');
        //       $name            = md5($request->file('iban')->getClientOriginalName()).time().rand(99999,1000000).'.'.$image->getClientOriginalExtension();
        //       $destinationPath = public_path('/img/user/usermeta');
        //       $imagePath       = $destinationPath. "/".  $name;
        //       $image->move($destinationPath, $name);
        //       $meta->iban    = $name;
        //   }   
        //   if($request->hasFile('car_insurance')) {
        //       $image           = $request->file('car_insurance');
        //       $name            = md5($request->file('car_insurance')->getClientOriginalName()).time().rand(99999,1000000).'.'.$image->getClientOriginalExtension();
        //       $destinationPath = public_path('/img/user/usermeta');
        //       $imagePath       = $destinationPath. "/".  $name;
        //       $image->move($destinationPath, $name);
        //       $meta->car_insurance    = $name;
        //   } 
          if($request->hasFile('personal_image')) {
              $image           = $request->file('personal_image');
              $name            = md5($request->file('personal_image')->getClientOriginalName()).time().rand(99999,1000000).'.'.$image->getClientOriginalExtension();
              $destinationPath = public_path('/img/user/usermeta');
              $imagePath       = $destinationPath. "/".  $name;
              $image->move($destinationPath, $name);
              $meta->personal_image    = $name;
          }            
          $plateTypes = ['1' => 'خصوصي' ,'2' => 'نقل عام' ,'3' => 'نقل خاص' ,'4' => 'حافلة صغيرة عامة', '5' => 'حافلة صغيرة خاصة', '6' => 'اجرة' ,'7' => 'معدات ثقيلة', '8' => 'تصدير' ,'9' =>'دبلوماسي' ,/*'10' =>'دراجة نارية',*/ '11' => 'مؤقت'];
          if($request->hasFile('car_image')) {
              $image           = $request->file('car_image');
              $name            = md5($request->file('car_image')->getClientOriginalName()).time().rand(99999,1000000).'.'.$image->getClientOriginalExtension();
              $destinationPath = public_path('/img/user/usermeta');
              $imagePath       = $destinationPath. "/".  $name;
              $image->move($destinationPath, $name);
              $meta->car_image    = $name;
          }             
         $meta->car_type           = $request->car_type;
         $meta->car_model          = $request->car_model;
         $meta->car_color          = $request->car_color;
         $meta->manufacturing_year = $request->manufacturing_year;
         $meta->car_numbers        = convert2english($request->car_numbers);
         $meta->car_letters        = $request->car_letters;
         $meta->sequenceNumber     = convert2english($request->sequenceNumber);
         $meta->plateType          = $request->plateType;
         $meta->plateType_txt      = (isset($plateTypes[$request->plateType]))? $plateTypes[$request->plateType] : '' ;
         
        $stc_number          = convert2english($request->stc_number);
        $stc_number          = phoneValidate($stc_number);

        $meta->bank_name     = $request->bank_name;
        $meta->iban          = $request->iban;
        $meta->stc_number    = $stc_number;

        $meta->complete           = 'true';
        $meta->status             = 'pending';
        $meta->save();   
           
    History(Auth::user()->id,'اضافة طلب العضو '.$meta->name.' للعمل كقائد');
    Session::flash('success','تم اضافة الطلب بنجاح.');
    return back();
}  

  public function editUserMeta($id =''){
    $usermeta = userMeta::findOrFail($id);
    $countries = Country::orderBy('name_ar','ASC')->get();
    $country_id = 1;
    if($country = Country::where(['iso2'=>'SA'])->first()){
        $country_id = $country->id;
    }
    $cities   = City::where(['country_id'=>$country_id])->orderBy('name_ar','ASC')->get();
    $plateTypes = ['1' => 'خصوصي' ,'2' => 'نقل عام' ,'3' => 'نقل خاص' ,'4' => 'حافلة صغيرة عامة', '5' => 'حافلة صغيرة خاصة', '6' => 'اجرة' ,'7' => 'معدات ثقيلة', '8' => 'تصدير' ,'9' =>'دبلوماسي' ,/*'10' =>'دراجة نارية',*/ '11' => 'مؤقت'];
    return view('dashboard.users.editUserMeta',compact('usermeta','countries','plateTypes','cities'));
  }  

  public function updateUserMeta(Request $request){
    $this->validate($request,[
        'id'          => 'required',
        'name'        => 'required',
        'phone'       => 'required',
        'email'       => 'required',
        'identity_number'     => 'required',
        'car_type'            => 'required',
        'car_model'           => 'required',
        'car_color'           => 'required',
        'manufacturing_year'  => 'required',
        'car_numbers'         => 'required',
        'car_letters'         => 'required',
        'sequenceNumber'      => 'required',
    ]); 
        $meta = userMeta::findOrFail($request->id);
        $meta->name               = $request->name;
        $number         = convert2english($request->phone);
        $phone          = phoneValidate($number);
        if($country = Country::find($request->country_id)){
          $meta->country_id         = $country->id;
          $meta->phonekey           = str_replace('00', '+', $country->phonekey);
        }
        $meta->phone              = $phone;
        $meta->email              = $request->email;
        $meta->birthdate          = date('Y-m-d',strtotime(convert2english($request->birthdate) ));
        // $meta->birthdate_hijri    = date('Y-m-d',strtotime(convert2english($request->birthdate_hijri) ));
        $meta->identity_number    = convert2english($request->identity_number);
        $meta->gender             = $request->gender;
        // $meta->captain_type       = $request->captain_type;
        $meta->city_id            = $request->city_id;
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
          //  if($request->hasFile('iban')) {
          //       $image           = $request->file('iban');
          //       $name            = md5($request->file('iban')->getClientOriginalName()).time().rand(99999,1000000).'.'.$image->getClientOriginalExtension();
          //       $destinationPath = public_path('/img/user/usermeta');
          //       $imagePath       = $destinationPath. "/".  $name;
          //       $image->move($destinationPath, $name);
          //       $meta->iban    = $name;
          //   }   
          //   if($request->hasFile('car_insurance')) {
          //       $image           = $request->file('car_insurance');
          //       $name            = md5($request->file('car_insurance')->getClientOriginalName()).time().rand(99999,1000000).'.'.$image->getClientOriginalExtension();
          //       $destinationPath = public_path('/img/user/usermeta');
          //       $imagePath       = $destinationPath. "/".  $name;
          //       $image->move($destinationPath, $name);
          //       $meta->car_insurance    = $name;
          //   } 
            if($request->hasFile('personal_image')) {
                $image           = $request->file('personal_image');
                $name            = md5($request->file('personal_image')->getClientOriginalName()).time().rand(99999,1000000).'.'.$image->getClientOriginalExtension();
                $destinationPath = public_path('/img/user/usermeta');
                $imagePath       = $destinationPath. "/".  $name;
                $image->move($destinationPath, $name);
                $meta->personal_image    = $name;
            }            
            $plateTypes = ['1' => 'خصوصي' ,'2' => 'نقل عام' ,'3' => 'نقل خاص' ,'4' => 'حافلة صغيرة عامة', '5' => 'حافلة صغيرة خاصة', '6' => 'اجرة' ,'7' => 'معدات ثقيلة', '8' => 'تصدير' ,'9' =>'دبلوماسي' ,/*'10' =>'دراجة نارية',*/ '11' => 'مؤقت'];
            if($request->hasFile('car_image')) {
                $image           = $request->file('car_image');
                $name            = md5($request->file('car_image')->getClientOriginalName()).time().rand(99999,1000000).'.'.$image->getClientOriginalExtension();
                $destinationPath = public_path('/img/user/usermeta');
                $imagePath       = $destinationPath. "/".  $name;
                $image->move($destinationPath, $name);
                $meta->car_image    = $name;
            }             
           $meta->car_type           = $request->car_type;
           $meta->car_model          = $request->car_model;
           $meta->car_color          = $request->car_color;
           $meta->manufacturing_year = $request->manufacturing_year;
           $meta->car_numbers        = convert2english($request->car_numbers);
           $meta->car_letters        = $request->car_letters;
           $meta->sequenceNumber     = convert2english($request->sequenceNumber);
           $meta->plateType          = $request->plateType;
           $meta->plateType_txt      = (isset($plateTypes[$request->plateType]))? $plateTypes[$request->plateType] : '' ;
           
          $stc_number          = convert2english($request->stc_number);
          $stc_number          = phoneValidate($stc_number);

          $meta->bank_name     = $request->bank_name;
          $meta->iban          = $request->iban;
          $meta->stc_number    = $stc_number;

          $meta->complete           = 'true';
          // $meta->status             = 'pending';
          $meta->save();   
             
      History(Auth::user()->id,'تعديل طلب العضو '.$meta->name.' للعمل كقائد');
      Session::flash('success','تم تعديل الطلب بنجاح.');
      return back();

  }  
  
    public function userProfile($id =''){
        $profile = User::findOrFail($id);
        return view('dashboard.users.userProfile',compact('profile'));
    }

    public function userPerformance($userid ='', $page = 1){
            $user = User::findOrFail($userid);
            $page = ($page)?? 1;
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
            $dataRatings = [];$totalRate = 0;
            $weekratings = userOrdersRatings::select('*', DB::raw('SUM(rate) as rate_sum ,COUNT(*) as count_ratings'))->where('user_id','=',$user->id)->where('created_at','>=',$start_date)->where('created_at','<=',$end_date)->first();
            if($weekratings){
              $totalRate = ($weekratings->rate_sum != 0)? ($weekratings->rate_sum / $weekratings->count_ratings) : 0;
            }

            /*start available hours in that week*/
            $dataAvailabelHours  = []; $totalAvailableHours= 0;
            $weekAvailableHours = userAvailableTimes::select('*', DB::raw('SUM(num_minutes) as total_minutes'))->where('user_id','=',$user->id)->where('created_at','>=',$start_date)->where('created_at','<=',$end_date)->first();
            if($weekAvailableHours){
              $totalAvailableHours = (convertToHoursMins($weekAvailableHours->total_minutes))?? 0;
            }

            /*start accept rate in that week*/
            $dataAcceptance = [];$totalAccept='';$openedorders = 0;$finishedorders=0;$count_finished_orders=0;
            $weekopened    = usersOrdersHistory::select('*', DB::raw("COUNT(*) as allorders"))->where('captain_id','=',$user->id)->where('created_at','>=',$start_date)->where('created_at','<=',$end_date)->first();
            $weekaccepted  = usersOrdersHistory::select('*', DB::raw("COUNT(*) as finishedorders"))->where('captain_id','=',$user->id)->where('status','=','finished')->where('created_at','>=',$start_date)->where('created_at','<=',$end_date)->first();
            if($weekopened){
              $openedorders  = ($weekopened->allorders)?? 0; 
              $finishedorders= ($weekaccepted->finishedorders)?? 0; 
            }
            $totalAccept   = ( $finishedorders == 0 )? '0%' : round( ( $finishedorders / $openedorders ) * 100 ,1).'%';       
            
            $dataOrders = [];$num_orders = 0;$totalPrices_of_orders=0;
            $num_cash_orders = 0; $totalPrices_of_cash_orders = 0;
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
                }
            }
            /*end done orders*/
            /*start withdraw orders of captain negative*/
            $num_withdraw_orders = 0; $totalPrices_of_withdraw_orders = 0;
            if($withdrawOrders = usersOrdersHistory::where(['captain_id'=>$user->id,'status'=>'withdraw'])->where('created_at','>=',$start_date)->where('created_at','<=',$end_date)->get()){
              foreach($withdrawOrders as $withdrawOrder) {
                     $num_withdraw_orders += 1;
                     $totalPrices_of_withdraw_orders += floatval($withdrawOrder->price); 
              }
            }
            /*end withdraw orders of captain negative*/
            /*start client closed orders added to captain positive*/
            $num_closed_orders = 0; $totalPrices_of_closed_orders = 0;
            if($closedOrders = usersOrdersHistory::where(['captain_id'=>$user->id,'status'=>'closed'])->where('created_at','>=',$start_date)->where('created_at','<=',$end_date)->get()){
              foreach($closedOrders as $closedOrder) {
                     $num_closed_orders += 1;
                     $totalPrices_of_closed_orders += floatval($closedOrder->price); 
              }
            }
            /*end client closed orders added to captain positive*/
            /*start rewards*/
            $num_rewards_orders = 0;
            $totalPrices_of_rewards_orders = 0;
            if($rewardsOrders = rewardsHistory::where('user_id','=',$user->id)->where('created_at','>=',$start_date)->where('created_at','<=',$end_date)->get()){
              foreach($rewardsOrders as $rewardsOrder) {
                     $num_rewards_orders += 1;
                     $totalPrices_of_rewards_orders += floatval($rewardsOrder->points); 
              }
            }            
            /*end rewards*/       
            /*start guarantees*/
            $num_guarantee_orders = 0;
            $totalPrices_of_guarantee_orders = 0;
            if($guaranteeOrders = GuaranteesHistory::where('user_id','=',$user->id)->where('created_at','>=',$start_date)->where('created_at','<=',$end_date)->get()){
              foreach($guaranteeOrders as $guaranteeOrder) {
                     $num_guarantee_orders += 1;
                     $totalPrices_of_guarantee_orders += floatval($guaranteeOrder->guarantee); 
              }
            }            
            /*end guarantees*/
            //all money of captain orders after remove site percentage
            $totalPrices_of_orders          = round($totalPrices_of_orders - ( (setting('site_percentage') / 100) * $totalPrices_of_orders ) , 2);
            $totalPrices_of_withdraw_orders = ($totalPrices_of_withdraw_orders * -1 ) + $totalPrices_of_closed_orders ;
            $num_withdraw_orders            = $num_withdraw_orders + $num_closed_orders;
            $totalPrices_of_cash_orders     = $totalPrices_of_cash_orders     * -1 ;
            $total = round($totalPrices_of_orders + $totalPrices_of_rewards_orders + $totalPrices_of_guarantee_orders + $totalPrices_of_withdraw_orders + $totalPrices_of_cash_orders , 2);
                              
            $new_start_date = Date::parse($start_date)->format('j F Y');
            $new_end_date   = Date::parse($end_date)->format('j F Y');
            $currency   = ($user->country)?$user->country->currency_ar:setting('site_currency_ar');            
            
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
        return view('dashboard.users.userPerformance',compact('user','page','orders','num_orders','totalPrices_of_orders','num_withdraw_orders','totalPrices_of_withdraw_orders','num_rewards_orders','totalPrices_of_rewards_orders','num_guarantee_orders','totalPrices_of_guarantee_orders','num_cash_orders','totalPrices_of_cash_orders','total','totalRate','totalAvailableHours','totalAccept','start_date','end_date','new_start_date','new_end_date','currency','captainMoneystatus','captainMoney'));
    }

    public function captainMoneyHistory($userid=false){
      if($userid != false){
        if($captain = User::find($userid)){
           $captainMoneyHistories = captainMoneyHistory::where('captain_id','=',$captain->id)->orderBy('created_at','DESC')->paginate(30);
           return view('dashboard.users.captainMoneyHistory',compact('captainMoneyHistories','captain'));
        }
        return back()->with('danger','هذا المستخدم غير موجود.');
      }
      return back();
    }

    public function downloadCaptainMoneyArchive($userid=false){
      if($userid != false){
        if($captain = User::find($userid)){
           return Excel::download( new captainMoneyHistoryExport($captain->id), 'captainMoneyArchive.xlsx');        
        }
        return back()->with('danger','هذا المستخدم غير موجود.');
      }
      return back();
    }

    public function finishCaptainWeekMoney(Request $request){
        $this->validate($request,[
            'captain_id'        =>'required',
            'total'             =>'required',
            'currency'          =>'required',
            'start_date'        =>'required',
            'end_date'          =>'required',
            'amount'            =>'required'
        ]);
        if($captainMoney = captainMoneyHistory::where(['captain_id'=>$request->captain_id,'start_date'=>$request->start_date])->first()){
            return back()->with('danger','لقد تم انهاء حساب هذه الدورة بالفعل!');
        }else{
            $captainMoney = new captainMoneyHistory();
            $captainMoney->captain_id = $request->captain_id;
            //the status of money to captain
            $captainMoney->type       = (floatval($request->total) <= 0 )? 'pay':'receive';
            $captainMoney->amount     = floatval($request->amount); 
            $captainMoney->currency   = $request->currency; 
            $captainMoney->start_date = $request->start_date; 
            $captainMoney->end_date   = $request->end_date; 
            $captainMoney->date       = date('Y-m-d'); 
            $captainMoney->month      = date('Y-m'); 
            $captainMoney->save();
            if($captain = User::find($request->captain_id)){
                if(floatval($request->total) <= 0){
                   $captain->balance += round(floatval($request->amount) , 2 );  
                }else{
                   $captain->balance -= round(floatval($request->amount) , 2 );  
                }
                $captain->save();
            }
            History(Auth::user()->id,'تم تأكيد انهاء حساب القائد '.$captain->name.' صاحب الكود('.$captain->pin_code.')');
        }
        return back()->with('success','تم الحفظ بنجاح.');
    }

    public function userAvailableTimes($userid ='', $page = 1){
            $user = User::findOrFail($userid);
            $page = ($page)?? 1;
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

            $availableHours = userAvailableTimes::select('*', DB::raw('SUM(num_minutes) as total_minutes'))->where('user_id','=',$user->id)->where('created_at','>=',$start_date)->where('created_at','<=',$end_date)->groupBy('date')->orderBy('created_at','ASC')->get();
        return view('dashboard.users.userAvailableTimes',compact('user','availableHours'));
    }

    public function userOrdersRatings($userid ='', $page = 1){
            $user = User::findOrFail($userid);
            $page = ($page)?? 1;
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
            $ratings = userOrdersRatings::select('*', DB::raw('AVG(rate) as rate_average, COUNT(*) as count_ratings'))->where('user_id','=',$user->id)->where('created_at','>=',$start_date)->where('created_at','<=',$end_date)->groupBy('date')->orderBy('date','ASC')->get();
        return view('dashboard.users.userOrdersRatings',compact('user','ratings'));
    }

    public function ordersAcceptance($userid ='', $page = 1){
            $user = User::findOrFail($userid);
            $page = ($page)?? 1;
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
            $userorders = DB::table('users_orders_history')->select('*', DB::raw("COUNT(*) as count_orders"))->where('captain_id','=',$user->id)->where('created_at','>=',$start_date)->where('created_at','<=',$end_date)->groupBy('date')->orderBy('created_at','ASC')->get();
        return view('dashboard.users.ordersAcceptance',compact('user','userorders'));
    }    

    public function refuseUserMeta(Request $request){
      $this->validate($request,[
          'id'     => 'required',
          'refuse_reason' => 'required'
      ]); 
      $usermeta = userMeta::findOrFail($request->id);
      $usermeta->status = 'refused';
      $usermeta->refuse_reason = $request->refuse_reason;
      $usermeta->reviewer_id = Auth::Id();
      $usermeta->save();

      $reviewer = Auth::user();
      $reviewer->balance = (float)$reviewer->balance + (float) $reviewer->review_order_value;
      $reviewer->num_reviewed_orders = (int) $reviewer->num_reviewed_orders + 1;
      $reviewer->num_review_refused_orders = (int) $reviewer->num_review_refused_orders + 1;
      $reviewer->save();

      send_mobile_sms($usermeta->phonekey.$usermeta->phone,setting('refuse_message'));

      History(Auth::user()->id,'برفض طلب العضو '.$usermeta->name.' للعمل كقائد');
      Session::flash('success','تم رفض الطلب بنجاح.');
      return redirect()->back();
  }

    public function agreeUserMeta(Request $request){
        $this->validate($request,[
            'id'          =>'required',
            'car_type_id' =>'required'
        ]);      
        $usermeta = userMeta::findOrFail($request->id);
        $usermeta->status = 'agree';
        $usermeta->car_type_id = $request->car_type_id;
        $usermeta->reviewer_id = Auth::Id();
        $usermeta->save();
        
        $reviewer = Auth::user();
        $reviewer->balance = (float)$reviewer->balance + (float) $reviewer->review_order_value;
        $reviewer->num_reviewed_orders = (int) $reviewer->num_reviewed_orders + 1;
        $reviewer->num_review_accepted_orders = (int) $reviewer->num_review_accepted_orders + 1;
        $reviewer->save();

        //if user account with that phone exists update date
        if($user = User::where('phone','=',$usermeta->phone)->first()){
           $usermeta->user_id  = $user->id;
           $usermeta->save();
        
           $user->name         = $usermeta->name;
           $user->password     = $usermeta->password;
           $user->email        = $usermeta->email;
           $user->country_id   = $usermeta->country_id;
           $user->city_id      = $usermeta->city_id;
          
           $user->birth_date         = date('d-m-Y',strtotime($usermeta->birthdate));
           $user->identity_number    = $usermeta->identity_number;
           $user->gender             = $usermeta->gender;

           $user->captain      = 'true';
           $user->captain_type = $usermeta->captain_type;
           // $user->service_in   = $usermeta->service_in;
           // $user->service_type = $usermeta->service_type;
           $user->active       = 'active';
           $user->role         = '0';
           $user->plan_id      = '1';           
           $user->balance      += intval(setting('free_balance'));
           //give the user the reward for invite captain
            if($usermeta->friend_code){
                if($friend = User::where(['pin_code' => $usermeta->friend_code])->first()){
                  $user->friend_code  = $request->friend_code;
                  $friend->balance   += floatval( setting('invite_captain_balance') );
                  $friend->save();
                }  
            }           
           $user->pin_code     = generate_pin_code();
           $user->share_code   = generate_share_code();        
           $user->avatar       = $usermeta->personal_image;
           $user->save(); 
           if($usermeta->personal_image){
             $oldPath = public_path('/img/user/usermeta/'.$usermeta->personal_image);
             $newPath = public_path('/img/user/'.$usermeta->personal_image);
             File::copy($oldPath , $newPath);
           }
           
            /*send notification to provider with admin agree*/
                $devices = userDevices::where(['user_id'=>$user->id])->get();
                $notify_title = setting('site_title');
                // $message_ar = 'تم الموافقة علي طلبك للعمل كقائد.';
                // $message_en = 'Your application has been approved as a captain.';
                $message_ar = setting('activeCaptain_msg_ar');
                $message_en = setting('activeCaptain_msg_en');
                $data = ['title' => $notify_title,'message_en'=>$message_en,'message_ar'=>$message_ar,'key'=>'activeCaptain'];
                sendNotification($devices, $message_ar,$notify_title,$data);
                notify($user->id,'',$notify_title,'user.activeCaptain',"user_id:".$user->id,'','activeCaptain');         
            /* end of send FCM notification */ 
            
            send_mobile_sms($user->phonekey.$user->phone,setting('agree_message'));
        }else{
        //create new user account with that data
           $user = new User();
           $user->phone        = $usermeta->phone;
           $user->phonekey     = ($usermeta->country)? $usermeta->country->phonekey :'00966';
           $user->password     = $usermeta->password;
           $user->name         = $usermeta->name;
           $user->email        = $usermeta->email;
           $user->country_id   = $usermeta->country_id;
           $user->city_id      = $usermeta->city_id;
           $user->birth_date         = date('d-m-Y',strtotime($usermeta->birthdate));
           $user->identity_number    = $usermeta->identity_number;
           $user->gender             = $usermeta->gender;
           $user->captain      = 'true';
           $user->active       = 'active';
           $user->role         = '0';
           $user->plan_id      = '1';  
           $user->balance      = intval(setting('free_balance'));
           $user->captain_type = $usermeta->captain_type;
           // $user->service_in   = $usermeta->service_in;
           // $user->service_type = $usermeta->service_type;
           $user->pin_code     = generate_pin_code();
           $user->share_code   = generate_share_code();        
           $user->avatar       = $usermeta->personal_image;
           $user->save(); 
          if($usermeta->personal_image){
             $oldPath = public_path('/img/user/usermeta/'.$usermeta->personal_image);
             $newPath = public_path('/img/user/'.$usermeta->personal_image);
             File::copy($oldPath , $newPath);
          }
           $usermeta->user_id  = $user->id;
           $usermeta->save();
           send_mobile_sms($user->phonekey.$user->phone,setting('agree_message'));
        }
        //add car data to user cars table
        // if($request->car_type_id){
          $plateTypes = ['1' => 'خصوصي' ,'2' => 'نقل عام' ,'3' => 'نقل خاص' ,'4' => 'حافلة صغيرة عامة', '5' => 'حافلة صغيرة خاصة', '6' => 'اجرة' ,'7' => 'معدات ثقيلة', '8' => 'تصدير' ,'9' =>'دبلوماسي' ,/*'10' =>'دراجة نارية',*/ '11' => 'مؤقت'];
          $car = new userCars();
          $car->user_id        = $user->id;
          $car->car_type_id    = $request->car_type_id;
          $car->sequenceNumber = $usermeta->sequenceNumber;
          $car->brand          = $usermeta->car_type;
          $car->model          = $usermeta->car_model;
          $car->color          = $usermeta->car_color;
          $car->year           = $usermeta->manufacturing_year;
          $car->car_number     = $usermeta->car_numbers.' '.$usermeta->car_letters ;
          $car->plateType      = $usermeta->plateType ;
          $car->plateType_txt  = (isset($plateTypes[$usermeta->plateType]))? $plateTypes[$usermeta->plateType] : '' ;
          $car->image          = $usermeta->car_image;
          $car->save();
          $user->captain_current_car_id      = $car->id;
          $user->captain_current_car_type_id = $car->car_type_id.',';
          $user->save();
        // }

        if($usermeta->car_image){
          $oldPath = public_path('/img/user/usermeta/'.$usermeta->car_image);
          $newPath = public_path('/img/car/'.$usermeta->car_image);
          File::copy($oldPath , $newPath);
        }

        //send driver and car information to elm
        if($elm_results = waslRegisterDriverAndCar($usermeta->identity_number,$usermeta->birthdate,$usermeta->email,$usermeta->phonekey.$usermeta->phone,$usermeta->sequenceNumber,$usermeta->car_letters,$usermeta->car_numbers,$usermeta->plateType)){
          $usermeta->resultCode = $elm_results->resultCode;
          $usermeta->resultMsg  = ($elm_results->resultMsg)??'';
          if(isset($elm_results->result)){
            if(isset($elm_results->result->eligibility)){
               $usermeta->driverEligibility = $elm_results->result->eligibility;
            }
          }
          $usermeta->save();
        }
        //send driver and car information to elm
        
        History(Auth::user()->id,'بقبول طلب العضو '.$user->name.' للعمل كقائد');
        Session::flash('success','تم قبول الطلب بنجاح.');
      return redirect()->back();
      // return redirect('admin/completeUsersMeta');
    }

    #delete mesage
    public function deleteUserMeta(Request $request){
        userMeta::findOrFail($request->id)->delete();
        Session::flash('success','تم حذف الطلب ');
        History(Auth::user()->id,'بحذف طلب عضو للعمل كمندوب');
        return redirect()->back();
    }
    #add user
    public function AddUser(Request $request){
        $this->validate($request,[
            'name'     =>'required|max:190',
            // 'email'    =>'required|email|unique:users',
            'phone'    =>'required|unique:users',//min:9|max:190|
            'avatar'   =>'nullable|image',
            'password' =>'required',//|min:6|max:190
            'role'     => 'required'
        ]);
        $number         = convert2english(request('phone'));
        $phone          = phoneValidate($number);
        // if (substr($number, 0, 1) === '0'){
        //     $number = substr($number, 1);
        // }
        // $phone         = preg_replace('/\s+/', '', $number);
        if($existsuser = User::where(['phone'=>$phone])->first()){
          return back()->with('danger','هذا الجوال مستخدم بالفعل.');
        }        
        $user               = new User();
        $user->name         = $request->name;
        $user->email        = $request->email;
        $user->phone        = $phone;
        $user->role         = $request->role;
        $user->country_id   = $request->country_id;
        // $user->service_in   = $request->service_in;
        // $user->service_type = $request->service_type;
        if($country = Country::find($request->country_id) ){
            $user->current_country_id = $country->id;
            $user->currency           = $country->currency_ar;
            $user->phonekey           = $country->phonekey;
        }     
        if($request->has('captain')){
           $user->captain = ($request->captain == 'on')? 'true':'false' ;
           $user->pin_code = generate_pin_code();
        }else{
           $user->captain = 'false';
        }
        if($request->balance){
           $user->balance = $request->balance;
        }else{
           $user->balance = '0';
        }        
        $user->active   = $request->active;
        $user->password = bcrypt($request->password);
        if($request->hasFile('avatar')) {
              $file = $request->file('avatar');
              $extension = $file->getClientOriginalExtension();
              $img_extensions = array("jpg","jpeg","gif","png","svg");
              if(in_array($extension,$img_extensions)){
                $filename = md5($file->getClientOriginalName()).time().rand(99999,1000000).'.'.$extension;
                $file->move(public_path('/img/user/'),$filename);
                $user->avatar    = $filename;
              }else{
                  return back()->with('danger','نوع الصورة التي ادخلتها غير صحيح, الأنواع المسموح بها [gif|jpg|jpeg|png|svg]');
              }
        }
        $user->code       = generate_code();
        $user->share_code = generate_share_code();
       
        $user->type         = $request->type;
        $user->review_order_value   = $request->review_order_value;
        $user->save();
        History(Auth::user()->id,'بأضافة العضو '.$user->name);
        return back()->with('success','تم اضافة العضو');
    }

    #update user
    public function UpdateUser(Request $request){
        $this->validate($request,[
            'id'          =>'required',
            'edit_name'   =>'required|max:190',
            // 'edit_email' =>'required|email|min:2|max:190|unique:users,email,'.$request->id,
            'edit_phone'  => 'required|unique:users,phone,'.$request->id,
            'edit_avatar' => 'nullable|image',
            'edit_role'   => 'required'
        ]);
        $number         = convert2english(request('edit_phone'));
        $phone          = phoneValidate($number);
        // if (substr($number, 0, 1) === '0'){
        //     $number = substr($number, 1);
        // }
        // $phone         = preg_replace('/\s+/', '', $number);
        
        if($existsuser = User::where('id','!=',$request->id)->where('phone','=',$phone)->first()){
          return back()->with('danger','هذا الجوال مستخدم بالفعل.');
        }           
        $user = User::findOrFail($request->id);
        $firstmsg = 'بتعديل ';
        $msg = '';
        if( ($request->has('edit_name')) && ($request->edit_name!=$user->name) ) {
            $msg .= 'الاسم الاول من '.$user->name .' الي '.$request->edit_name.'<br/>';
            $user->name     = $request->edit_name;
        }     
        if( ($request->has('edit_phone')) && ($user->phone != $phone) ) {
            $msg .= 'الهاتف من 0'.$user->phone .' الي 0'.$phone.'<br/>';
            $user->phone    = $phone;
        }
        if( ($request->has('edit_email')) && ($request->edit_email != $user->email) ) {
            $msg .= 'البريد الالكتروني من '.$user->email .' الي '.$request->edit_email.'<br/>';
           $user->email      = $request->edit_email;
        }
        if($request->edit_password){
            $msg .= 'كلمة المرور الي '.$request->edit_password.'<br/>';
            $user->password = Hash::make($request->edit_password);
        }
        $user->role         = $request->edit_role;
        $user->country_id   = $request->edit_country_id;
        // $user->service_in   = $request->edit_service_in;
        // $user->service_type = $request->edit_service_type;        
        if($country = Country::find($request->edit_country_id) ){
            $user->current_country_id = $country->id;
            $user->currency           = $country->currency_ar;
            $user->phonekey           = $country->phonekey;
        }                    
        if( ($request->has('edit_balance')) && ($request->edit_balance != $user->balance) ) {
           $msg .= 'الرصيد من '.$user->balance .' الي '.$request->edit_balance.'<br/>';
           if($request->edit_balance > $user->balance){
               $amount = floatval($request->edit_balance - $user->balance);
               savePayment(0,$user->id,$amount,'subtract','balance_transfer','finished',$user->country_id);
           }
           $user->balance = $request->edit_balance  ;
        }
        if( ($request->has('edit_active') ) && ($request->edit_active != $user->active ) ) {
            // $msg .= 'الحالة من '.$user->active.' الي '.$request->edit_active.'<br/>';
            if($request->edit_active == 'block'){
                /*send notification to mobile with user delete*/
                $devices = userDevices::where(['user_id'=>$user->id])->get();
                $notify_title   = setting('site_title');
                // $message_ar = 'تم حظر الحساب الخاص بك ';
                // $message_en = 'Account blocked from the app ';
                $message_ar = setting('block_user_msg_ar');
                $message_en = setting('block_user_msg_en');

                $data = ['title' => $notify_title,'message_ar'=>$message_ar,'message_en'=>$message_en,'key'=>'block_user'];
                sendNotification($devices, $message_ar,$notify_title,$data);
                /* end of send FCM notification */            
                History(Auth::user()->id,'بحظر العضو '.$user->name);            
            }elseif(($request->edit_active == 'active') && ($user->active == 'block') ){
                History(Auth::user()->id,'بانهاء حظر حساب العضو '.$user->name);            
            }                       
            $user->active    = $request->edit_active ;
        } 
            $captain = ($request->edit_captain == 'on')?'true':'false';
            if($captain != $user->captain ){
                $type = ($request->edit_captain == 'on')? 'كقائد':'كعميل';
                $msg .= 'للعمل '.$type ;
                $user->captain = $captain ;
                $user->pin_code = generate_pin_code();
            }
        if($request->hasFile('edit_avatar')){
              $file = $request->file('edit_avatar');
              $extension = $file->getClientOriginalExtension();
              $img_extensions = array("jpg","jpeg","gif","png","svg");
              if(in_array($extension,$img_extensions)){
                $filename = md5($file->getClientOriginalName()).time().rand(99999,1000000).'.'.$extension;
                $file->move(public_path('/img/user/'),$filename);
                $user->avatar    = $filename;
              }else{
                  return back()->with('danger','نوع الصورة التي ادخلتها غير صحيح, الأنواع المسموح بها [gif|jpg|jpeg|png|svg]');
              }
        }
        $user->review_order_value   = $request->edit_review_order_value;
        $user->save();
        if($msg){
           History(Auth::user()->id,$firstmsg.$msg.' للمستخدم '.$user->name);
        }
        return back()->with('success','تم حفظ التعديلات');
    }

    #delete user
    public function deleteUser(Request $request){
            $user = User::findOrFail($request->id);
           if($user->role == 1){
              Session::flash('danger','لا يمكن حذف هذا العضو');
              return 0;
           }else{
            if($user->avatar != 'default.png'){
               File::delete('img/user/'.$user->avatar);
            }
            DB::table('authentication')->where(['user_id'=>$user->id])->delete();            
            $user_id = $user->id;
            $user->delete();
            /*send notification to mobile with user delete*/
            $devices = userDevices::where(['user_id'=>$user->id])->get();
            $notify_title   = setting('site_title');
            // $message_ar = 'تم حذف الحساب الخاص بك ';
            // $message_en = 'Account deleted from the app';
            $message_ar = setting('delete_user_msg_ar');
            $message_en = setting('delete_user_msg_en');

            $data = ['title' => $notify_title,'message_ar'=>$message_ar,'message_en'=>$message_en,'key'=>'delete_user'];
            sendNotification($devices, $message_ar,$notify_title,$data);
            /* end of send FCM notification */            
            History(Auth::user()->id,'بحذف العضو '.$user->name);
            if($user_id == Auth::id()){
              Auth::logout();
              return redirect('admin/login');
            }
            return 1;
           }
    }

    #delete user
    public function deleteUsers(Request $request){
      $this->validate(Request(),['deleteids'=>'required']);
          $i = 1;
          $logout = false;
          foreach($request->deleteids as $id){
            if($user = User::find($id)){
                if($user->role == 1){
                  continue;
                }
                    if($user->avatar != 'default.png'){
                       File::delete('img/user/'.$user->avatar);
                    }
                DB::table('authentication')->where(['user_id'=>$user->id])->delete();            
                $user->delete();
                /*send notification to mobile with user delete*/
                    $devices = userDevices::where(['user_id'=>$user->id])->get();
                    $notify_title   = setting('site_title');
                  // $message_ar = 'تم حذف الحساب الخاص بك ';
                  // $message_en = 'Account deleted from the app';
                    $message_ar = setting('delete_user_msg_ar');
                    $message_en = setting('delete_user_msg_en');

                    $data = ['title' => $notify_title,'message_en'=>$message_en,'message_ar'=>$message_ar,'key'=>'delete_user'];
                    sendNotification($devices, $message_ar,$notify_title,$data);
                /* end of send FCM notification */ 
            $i++;    
              if($id == Auth::id()){
                $logout = true;
              }
            }
          }
            History(Auth::user()->id,'بحذف عدد '.$i.' عضو ');
            if($logout == true){
              Auth::logout();
              return redirect('admin/login');
            }
            return back()->with('success','تم الحذف');   
    }

    public function emailAllusers(Request $request){
        $this->validate($request,[
            'email_message' =>'required|min:1'
        ]);
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
            $users = User::get();
            foreach($users as $u){
                Mail::to($u->email)->send(new PublicMessage(  $request->email_message  ));
            }
            return back()->with('success','تم ارسال الرساله');
        }
    }
    
    public function emailClients(Request $request){
        $this->validate($request,[
            'email_message' =>'required|min:1'
        ]);        
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
            $users = User::where('role','=','0')->where('captain','=','false')->get();
            foreach($users as $u){
                Mail::to($u->email)->send(new PublicMessage(  $request->email_message  ));
            }
            return back()->with('success','تم ارسال الرساله');
        }
    }

    public function emailCaptains(Request $request){
        $this->validate($request,[
            'email_message' =>'required|min:1'
        ]);

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
            $users = User::where('role','=','0')->where('captain','=','true')->get();
            foreach($users as $u){
                Mail::to($u->email)->send(new PublicMessage(  $request->email_message  ));
            }
            return back()->with('success','تم ارسال الرساله');
        }
    } 

    public function emailSupervisiors(Request $request){
        $this->validate($request,[
            'email_message' =>'required|min:1'
        ]);

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
            $users = User::where('role','>','0')->get();
            foreach($users as $u){
                Mail::to($u->email)->send(new PublicMessage(  $request->email_message  ));
            }
            return back()->with('success','تم ارسال الرساله');
        }
    }     

    public function userOrdersArchive($user_id=false){
      if($user_id != false){
        if($user = User::find($user_id)){
          if($user->captain == 'true'){
            $orders = usersOrdersHistory::where('captain_id','=',$user->id)->where('status','!=','opened')->get();
            return view('dashboard.users.captainOrdersArchive',compact('orders','user'));
          }else{
            $orders = DB::table('orders')->leftJoin('order_users', 'order_users.order_id', '=', 'orders.id')
                                         ->where('order_users.user_id',$user->id)->where('join_order','agree')
                                         ->orwhere(function($query) use($user) {
                                            $query->where('orders.user_id',$user->id);
                                          })
                                         ->groupBy('orders.id')
                                         ->orderBy('orders.created_at','ASC')
                                         ->select('orders.id','orders.user_id','orders.captain_id','orders.car_type_id','orders.price','orders.currency_ar','orders.status','orders.notes','orders.start_lat','orders.start_long','orders.start_address','orders.end_lat','orders.end_long','orders.end_address','orders.created_at')
                                         ->get();
           return view('dashboard.users.userOrdersArchive',compact('orders','user'));
          }
        }
        return back()->with('danger','هذا المستخدم غير موجود.');
      }
      return back();
    }

    public function comments($id=false){
      if($id != false){
        if($user = User::find($id)){
           $comments = Comments::where('profile_id','=',$user->id)->orderBy('created_at','DESC')->paginate(30);
           return view('dashboard.users.comments',compact('comments','user'));
        }
        return back()->with('danger','هذا المستخدم غير موجود.');
      }
      return back();
    }

    public function deleteComment(Request $request){
        Comments::findOrFail($request->id)->delete();
        return back()->with('success','تم حذف التعليق بنجاح');
    }

    public function adminUserPayments($id=false){
      if($id != false){
        if($currentuser = User::find($id)){
           $payments = Payments::with('user','seconduser')->where('wallet_type','=','balance')->where('user_id','=',$currentuser->id)->orwhere('second_user_id','=',$currentuser->id)->orderBy('created_at','DESC')->paginate($this->limit);
           return view('dashboard.users.payments',compact('payments','currentuser'));
        }
        return back()->with('success','هذا المستخدم غير موجود.');
      }
      return back();
    }
    
    public function downloadadminUserPayments($user_id=0){
        return Excel::download( new PaymentsExport($user_id,'balance'), 'PaymentsExport.xlsx');        
    }

    public function adminUserPaymentsElectronic($id=false){
      if($id != false){
        if($currentuser = User::find($id)){
           $payments = Payments::with('user','seconduser')->where('wallet_type','=','electronic_balance')->where('user_id','=',$currentuser->id)->orwhere('second_user_id','=',$currentuser->id)->orderBy('created_at','DESC')->paginate($this->limit);
           return view('dashboard.users.paymentsElectronic',compact('payments','currentuser'));
        }
        return back()->with('success','هذا المستخدم غير موجود.');
      }
      return back();
    }
    
    public function downloadadminUserPaymentsElectronic($user_id=0){
        return Excel::download( new PaymentsExport($user_id,'electronic_balance'), 'PaymentsExport.xlsx');        
    }
    #sms correspondent for all users
    public function SmsMessageAll(Request $request){
        $this->validate($request,[
            'sms_message' =>'required'
        ]);
        $users = User::where('role','=','0')->get();
        $numbers = '';
        foreach ($users as $u){
          $numbers.= $u->phonekey.$u->phone.',';
        }
        send_mobile_sms($numbers,$request->sms_message);
        History(Auth::user()->id,'بارسال رسالة SMS "'.$request->sms_message.'" الي جميع الأعضاء');
        return back()->with('success','تم الارسال بنجاح.'); 
    }

    public function SmsMessageSupervisiors(Request $request){
        $this->validate($request,[
            'sms_message' =>'required'
        ]);
        $users = User::where('role','>','0')->get();
        $numbers = '';
        foreach ($users as $u){
          $numbers.= $u->phonekey.''.$u->phone.',';
        }
        send_mobile_sms($numbers,$request->sms_message);
        return back()->with('success','تم الارسال بنجاح.'); 
    }
    #sms correspondent for users
    public function SmsMessageClients(Request $request){
        $this->validate($request,[
            'sms_message' =>'required'
        ]);
        $users = User::where('role','=','0')->where('captain','=','false')->get();
        $numbers = '';
        foreach ($users as $u){
          $numbers.= $u->phonekey.$u->phone.',';
        }
        send_mobile_sms($numbers,$request->sms_message);
        History(Auth::user()->id,'بارسال رسالة SMS "'.$request->sms_message.'" الي جميع العملاء');
        return back()->with('success','تم الارسال بنجاح.'); 
    }
    
    #sms correspondent for providers
    public function SmsMessageProviders(Request $request){
        $this->validate($request,[
            'sms_message' =>'required'
        ]);
        $users = User::where('role','=','0')->where('captain','=','true')->get();
        $numbers = '';
        foreach ($users as $u){
          $numbers.= $u->phonekey.$u->phone.',';
        }
        send_mobile_sms($numbers,$request->sms_message);
        History(Auth::user()->id,'بارسال رسالة SMS "'.$request->sms_message.'" الي جميع المندوبين');
        return back()->with('success','تم الارسال بنجاح.'); 
    }

    #notification correspondent for all users
    public function notificationAllUsers(Request $request){
        $this->validate($request,[
            'notification_message' =>'required',
            'notification_title'   => 'nullable'
        ]);
            $devices = DB::table('user_devices')->join('users', 'users.id', '=', 'user_devices.user_id')
                                                ->where('users.role','=','0')
                                                ->select('user_devices.device_id','user_devices.device_type','user_devices.show_ads','users.id')
                                                ->get(); 
            #use FCM or One Signal Here :) 
            $notify_title = ($request->notification_title)? $request->notification_title : setting('site_title');
            $message_ar   = $request->notification_message;
            $message_en   = $request->notification_message;
            $data = ['title' => $notify_title,'message_en'=>$message_en,'message_ar'=>$message_ar,'key'=>'from_admin'];
            sendNotification($devices, $message_ar,$notify_title,$data);
                foreach($devices as $device){
                    $notifications[] = ['user_id'      => $device->id,
                                        'notifier_id'  => '',
                                        'message'      => $message_ar,
                                        'title'        => $notify_title,
                                        'data'         => 'user_id:'.$device->id,
                                        'order_status' => '',
                                        'key'          => 'from_admin',
                                        'created_at'   => date('Y-m-d H:i:s')
                                        ];
                }
                $result = array_unique($notifications,SORT_REGULAR);
                Notifications::insert($result);
            History(Auth::user()->id,'بارسال اشعار "'.$request->notification_message.'" الي جميع الاعضاء');
            return back()->with('success','تم الارسال بنجاح.'); 
    }

    public function notificationSupervisiors(Request $request){
        $this->validate($request,[
            'notification_message' =>'required',
            'notification_title'   => 'nullable'
        ]);
            $devices = DB::table('user_devices')->join('users', 'users.id', '=', 'user_devices.user_id')
                                                ->where('users.role','>','0')
                                                ->select('user_devices.device_id','user_devices.device_type','user_devices.show_ads','users.id')
                                                ->get(); 
            #use FCM or One Signal Here :) 
            $notify_title = ($request->notification_title)? $request->notification_title : setting('site_title');
            $message_ar   = $request->notification_message;
            $message_en   = $request->notification_message;
            $data = ['title' => $notify_title,'message_en'=>$message_en,'message_ar'=>$message_ar,'key'=>'from_admin'];
            sendNotification($devices, $message_ar,$notify_title,$data);
                foreach($devices as $device){
                    $notifications[] = ['user_id'      => $device->id,
                                        'notifier_id'  => '',
                                        'message'      => $message_ar,
                                        'title'        => $notify_title,
                                        'data'         => 'user_id:'.$device->id,
                                        'order_status' => '',
                                        'key'          => 'from_admin',
                                        'created_at'   => date('Y-m-d H:i:s')
                                        ];
                }
                $result = array_unique($notifications,SORT_REGULAR);
                Notifications::insert($result);
            return back()->with('success','تم الارسال بنجاح.'); 
    }

    #notification correspondent for clients
    public function notificationClients(Request $request){
        $this->validate($request,[
            'notification_message' =>'required',
            'notification_title'   => 'nullable'
        ]);
            $devices = DB::table('user_devices')->join('users', 'users.id', '=', 'user_devices.user_id')
                                                ->where('users.role','=','0')
                                                ->where('users.captain','=','false')
                                                ->select('user_devices.device_id','user_devices.device_type','users.id')
                                                ->get(); 
            #use FCM or One Signal Here :) 
            $notify_title = ($request->notification_title)? $request->notification_title : setting('site_title');
            $message_ar = $request->notification_message;
            $message_en = $request->notification_message;
            $data = ['title' => $notify_title,'message_en'=>$message_en,'message_ar'=>$message_ar,'key'=>'from_admin'];
            sendNotification($devices, $message_ar,$notify_title,$data);
                foreach($devices as $device){
                    $notifications[] = ['user_id'      => $device->id,
                                        'notifier_id'  => '',
                                        'message'      => $message_ar,
                                        'title'        => $notify_title,
                                        'data'         => 'user_id:'.$device->id,
                                        'order_status' => '',
                                        'key'          => 'from_admin',
                                        'created_at'   => date('Y-m-d H:i:s')
                                        ];
                }
                $result = array_unique($notifications,SORT_REGULAR);
                Notifications::insert($result);
            History(Auth::user()->id,'بارسال اشعار "'.$request->notification_message.'" الي جميع العلاء.');
            return back()->with('success','تم الارسال بنجاح.'); 
    }    

    #notification correspondent for all users
    public function notificationProviders(Request $request){
        $this->validate($request,[
            'notification_message' =>'required',
            'notification_title'   => 'nullable'
        ]);

            $devices = DB::table('user_devices')->join('users', 'users.id', '=', 'user_devices.user_id')
                                                ->where('users.role','=','0')
                                                ->where('users.captain','=','true')
                                                ->select('user_devices.device_id','user_devices.device_type','users.id')
                                                ->get(); 
            #use FCM or One Signal Here :) 
            $notify_title = ($request->notification_title)? $request->notification_title : setting('site_title');
            $message_ar = $request->notification_message;
            $message_en = $request->notification_message;
            $data = ['title' => $notify_title,'message_en'=>$message_en,'message_ar'=>$message_ar,'key'=>'from_admin'];
            sendNotification($devices, $message_ar,$notify_title,$data);
                foreach($devices as $device){
                    $notifications[] = ['user_id'      => $device->id,
                                        'notifier_id'  => '',
                                        'message'      => $message_ar,
                                        'title'        => $notify_title,
                                        'data'         => 'user_id:'.$device->id,
                                        'order_status' => '',
                                        'key'          => 'from_admin',
                                        'created_at'   => date('Y-m-d H:i:s')
                                        ];
                }
                $result = array_unique($notifications,SORT_REGULAR);
                Notifications::insert($result);
            History(Auth::user()->id,'بارسال اشعار "'.$request->notification_message.'" الي جميع المندوبين');
            return back()->with('success','تم الارسال بنجاح.'); 
    }    

    public function currentUserEmail(Request $request){
        $this->validate($request,[
            'email_message' =>'required|min:1'
        ]);
        $checkConfig = SmsEmailNotification::where('type','=','smtp')->first();
        if(
            $checkConfig->username     == "" ||
            $checkConfig->password     == "" ||
            $checkConfig->sender_email == "" ||
            $checkConfig->port         == "" ||
            $checkConfig->host         == ""
        ){
            Session::flash('danger','لم يتم ارسال الرساله ! .. يرجى مراجعة بيانات ال SMTP');
            return back();
        }else{
            Mail::to($request->email)->send(new PublicMessage($request->email_message));
            return back()->with('success','تم ارسال الرساله');
        }
    }

    #send sms for current user
    public function currentUserSms(Request $request){
        $this->validate($request,[
            'sms_message' =>'required',
            'phone'       =>'required'
        ]);
        if($user = User::where(['phone'=>$request->phone])->first()){
          send_mobile_sms($user->phonekey.$user->phone,$request->sms_message);
          History(Auth::user()->id,'بارسال رسالة SMS "'.$request->sms_message.'" الي العضو '.$user->name);
        }
        return back()->with('success','تم الارسال بنجاح.'); 
    }

    #send notification for current user
    public function currentUserNotification (Request $request){
        $this->validate($request,[
            'notification_message' =>'required',
            'user_id'              =>'required',
            'notification_title'   => 'nullable'
        ]);

            $user = User::findOrFail($request->user_id);
            $devices = userDevices::where(['user_id'=>$user->id])->get();
            #use FCM or One Signal Here :) 
            $notify_title = ($request->notification_title)? $request->notification_title : setting('site_title');
            $message_ar     = $request->notification_message;
            $message_en     = $request->notification_message;
            $data = ['title' => $notify_title,'message_en'=>$message_en,'message_ar'=>$message_ar,'key'=>'from_admin'];
            sendNotification($devices, $message_ar,$notify_title,$data);
            notify($user->id,'',$notify_title,$message_ar,'user_id:'.$user->id,'','from_admin');         
            History(Auth::user()->id,'بارسال اشعار "'.$request->notification_message.'" الي العضو '.$user->name);
            return back()->with('success','تم الارسال بنجاح.'); 
    } 

    public function adminAddCoupon(Request $request){
        $this->validate($request,[
            'coupon'  =>'required',
            'user_id' =>'required'
        ]);
          if($coupon = Coupons::where(['code'=>$request->coupon])->first()){
             if($coupon->num_to_use <= $coupon->num_used){
               return back()->with('danger','لم يعد هذا الكوبون صالح للاستخدام.');
             }
            if( strtotime($coupon->end_at) < strtotime('now') ){   
               return back()->with('danger','لم يعد هذا الكوبون صالح للاستخدام.');
            }
            $user = User::find($request->user_id);
            if($usercoupon = usersCoupons::where(['user_id'=>$user->id,'used'=>'false'])->where('end_at','>=',date('Y-m-d'))->first()){
               return back()->with('danger','لدية كوبون لم يستخدم بالفعل.');
            }else{
              $usercoupon = new usersCoupons();     
              $usercoupon->coupon_id = $coupon->id;
              $usercoupon->user_id   = $user->id;
              $usercoupon->end_at    = $coupon->end_at;
              $usercoupon->save();
              $coupon->num_used += 1;
              $coupon->save();
              History(Auth::user()->id,'بأضافة كوبون خصم الي '.$user->name);
              return back()->with('success','تم اضافة الكوبون للعميل بنجاح.');   
            }       
          } 
        return back()->with('danger','لا يوجد كوبون بهذا الكود.');
    } 

    #sms correspondent for all users
    public function admincreateBlock(Request $request){
        $this->validate($request,[
            'num_hours' => 'required',
            'user_id'   => 'required'
        ]);
        if($user = User::find($request->user_id)){
           if($block = userBlocks::where('user_id','=',$request->user_id)->first()){
               $block->num_hours = $request->num_hours;
               $block->datetime  = date('Y-m-d H:i:s');
               $block->to_time   = date('Y-m-d H:i:s',strtotime('+'.$request->num_hours.' hours',strtotime(date('Y-m-d H:i:s'))));
               $block->save();
           }else{
               $block = new userBlocks();
               $block->user_id   = $user->id;
               $block->num_hours = $request->num_hours;
               $block->datetime  = date('Y-m-d H:i:s');
               $block->to_time   = date('Y-m-d H:i:s',strtotime('+'.$request->num_hours.' hours',strtotime(date('Y-m-d H:i:s'))));
               $block->save();
           }
          History(Auth::user()->id,'بحظر طلبات العضو '.$user->name.' لمدة '.$block->num_hours.' ساعة/ساعات.');
          return back()->with('success','تم الحظر بنجاح.'); 
        }
        return back()->with('danger','هذا المستخدم غير موجود.'); 
    }

    public function admincancelBlock(Request $request){
        userBlocks::where('user_id','=',$request->user_id)->delete();
        $user = User::find($request->user_id);
        History(Auth::user()->id,'بانهاء حظر طلبات العضو '.$user->name);
        return back()->with('success','تم انهاء الحظر بنجاح.');
    }

    public function captainCars($id=false){
      if($id != false){
        if($user = User::find($id)){
           $cars = userCars::where('user_id','=',$user->id)->orderBy('created_at','DESC')->paginate(30);
           $cartypes = carTypes::orderBy('id','ASC')->get();
           return view('dashboard.users.captainCars',compact('cars','user','cartypes'));
        }
        return back()->with('danger','هذا المستخدم غير موجود.');
      }
      return back();
    }

    public function createCaptainCar(Request $request){
        $this->validate($request,[
            'user_id'     => 'required',
            'car_type_id' => 'required',
            'brand'       => 'required',
            'model'       => 'required',
            'year'        => 'required',
            'car_number'  => 'required',
            'image'       => 'required|image'
        ]);

        $car = new userCars();
        $car->user_id     = $request->user_id;
        $car->car_type_id = implode(',',$request->car_type_id);
        $car->brand       = $request->brand;
        $car->model       = $request->model;
        $car->year        = $request->year;
        $car->car_number  = $request->car_number;
        if($request->hasFile('image')) {
              $file = $request->file('image');
              $extension = $file->getClientOriginalExtension();
              $img_extensions = array("jpg","jpeg","gif","png","svg");
              if(in_array($extension,$img_extensions)){
                $filename = md5($file->getClientOriginalName()).time().rand(99999,1000000).'.'.$extension;
                $file->move(public_path('/img/car/'),$filename);
                $car->image    = $filename;
              }else{
                  return back()->with('danger','نوع الصورة التي ادخلتها غير صحيح, الأنواع المسموح بها [gif|jpg|jpeg|png|svg]');
              }
        }        
        $car->save();
        History(Auth::user()->id,'بأضافة سيارة جديدة ');
        return back()->with('success','تم اضافة سيارة جديدة بنجاح');
    }

    #update workstage
    public function updateCaptainCar(Request $request){
        $this->validate($request,[
            'id'               => 'required',
            'edit_user_id'     => 'required',
            'edit_car_type_id' => 'required',
            'edit_brand'       => 'required',
            'edit_model'       => 'required',
            'edit_year'        => 'required',
            'edit_car_number'  => 'required'
        ]);

        $car = userCars::findOrFail($request->id);
        $car->user_id     = $request->edit_user_id;
        $car->car_type_id = implode(',',$request->edit_car_type_id);
        $car->brand       = $request->edit_brand;
        $car->model       = $request->edit_model;
        $car->year        = $request->edit_year;
        $car->car_number  = $request->edit_car_number;
         if($request->hasFile('edit_image')) {
              $file = $request->file('edit_image');
              $extension = $file->getClientOriginalExtension();
              $img_extensions = array("jpg","jpeg","gif","png","svg");
              if(in_array($extension,$img_extensions)){
                $filename = md5($file->getClientOriginalName()).time().rand(99999,1000000).'.'.$extension;
                $file->move(public_path('/img/car/'),$filename);
                $car->image    = $filename;
              }else{
                  return back()->with('danger','نوع الصورة التي ادخلتها غير صحيح, الأنواع المسموح بها [gif|jpg|jpeg|png|svg]');
              }
        }        
        $car->save();       
        History(Auth::user()->id,'بتعديل بيانات السيارة.');
        return back()->with('success','تم حفظ التعديلات');
    }

    #delete workstage
    public function DeleteCaptainCar(Request $request){
            $car = userCars::findOrFail($request->id);
            $car->delete();
            History(Auth::user()->id,'بحذف سيارة.');
            return back()->with('success','تم الحذف.');
    }   


}
