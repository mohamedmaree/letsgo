<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\userMeta;
class checkWaslEligibility extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'checkWaslEligibility:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'check drivers and cars register in wasl (elm) status ';

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
        if($metas = userMeta::where('status','!=','refused')->where('elm_status','!=','agree')/*->where('resultCode','=','success')*/->get()){
            foreach($metas as $meta){
                $driverEligibility = 'PENDING'; $vehicleEligibility = 'PENDING';
                if($elm_results = waslChechEligibility($meta->identity_number)){
                    if($meta->resultCode == 'DRIVER_NOT_FOUND'){
                        
                        if($wasl_register = waslRegisterDriverAndCar($meta->identity_number,$meta->birthdate,$meta->email,$meta->phonekey.$meta->phone,$meta->sequenceNumber,$meta->car_letters,$meta->car_numbers,$meta->plateType)){
                          $meta->resultCode = $wasl_register->resultCode;
                          if(isset($wasl_register->result)){
                            if(isset($wasl_register->eligibility)){
                               $meta->driverEligibility = $wasl_register->eligibility;
                            }
                          }
                          $meta->save();
                        }

                    }else{
                        $meta->driverEligibility     = $driverEligibility = ($elm_results->driverEligibility)??'';
                        $meta->eligibilityExpiryDate = ($elm_results->eligibilityExpiryDate)??'';
                        $meta->rejectionReasons      .= (isset($elm_results->rejectionReasons))? ((is_array($elm_results->rejectionReasons))? implode(',', $elm_results->rejectionReasons): $elm_results->rejectionReasons): '';
                        $meta->resultCode            = (isset($elm_results->resultCode)) ? $elm_results->resultCode : '';
                        if(isset($elm_results->vehicles)){
                           foreach($elm_results->vehicles as $vehicl){
                            $meta->vehicleEligibility = $vehicleEligibility = $vehicl->vehicleEligibility;
                           }
                        }
                        if($driverEligibility == 'VALID' && $vehicleEligibility == 'VALID'){
                            $meta->elm_status = 'agree';
                            send_mobile_sms($meta->phonekey.$meta->phone,"تم قبول طلبك يمكنك تسجيل الدخول لتطبيق lets'go قائد");
                        }elseif($driverEligibility == 'PENDING' || $vehicleEligibility == 'PENDING'){
                            $meta->elm_status = 'pending';
                        }else{
                            $meta->elm_status = 'refuse';
                        }
                        $meta->save(); 
                    }

                }else{
                    $meta->rejectionReasons      = 'DRIVER_NOT_FOUND';
                    $meta->resultCode            = 'DRIVER_NOT_FOUND';
                    $meta->save();
                }
            }
        }
                
        $this->info('update elm_status for all approved drivers and cars.');
    }
}
