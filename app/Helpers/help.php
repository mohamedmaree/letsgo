<?php
use Illuminate\Support\Facades\Route;
use App\User;
use App\Role;
use App\Setting;
use App\Html;
use App\Contact;
use App\SmsEmailNotification;
use App\Permission;
use App\History;
use App\Notifications;
use Twilio\Rest\Client;
use Twilio\Jwt\ClientToken;
use App\userMeta;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use LaravelFCM\Facades\FCM;
use App\userBlocks;
use Jenssegers\Date\Date;
use App\Payments;
use App\usersOrdersHistory;
use App\savedPlaces;
use App\usersCoupons;
use App\Coupons;
use App\carTypes;
use App\Order;
use Illuminate\Support\Str;

function is_success( $code )
{

if(preg_match("/^(000.000.|000.100.1|000.[36]|000.400.[1][12]0)/", $code) || preg_match("/^(000.400.0[^3]|000.400.100)/", $code)){
  return true;

}
return false;

    // $arr = [
    //     '000.000.000',
    //     '000.000.100',
    //     '000.100.110',
    //     '000.100.111',
    //     '000.100.112',
    //     '000.300.000',
    //     '000.300.100',
    //     '000.300.101',
    //     '000.300.102',
    //     '000.600.000',
    //     '000.200.100'
    // ];

    // return in_array( $code, $arr ) ? true : false;
}


function waslRegisterDriverAndCar($identityNumber='',$dateOfBirthGregorian='',$emailAddress='',$mobileNumber='',$sequenceNumber='',$plateLetters='',$plateNumber='',$plateType=''){
  $plateLetterRight ='';
  $plateLetterMiddle = '';
  $plateLetterLeft = ''; 
  $letters = explode(' ', $plateLetters);
  $letters = (count($letters) > 1 )? $letters : $plateLetters;

  $plateLetterLeft= (isset($letters[0]))? $letters[0] : '';
  $plateLetterMiddle = (isset($letters[1]))? $letters[1] : '';
  $plateLetterRight = (isset($letters[2]))? $letters[2] : '';
  $dateOfBirthGregorian = date('Y-m-d',strtotime($dateOfBirthGregorian));
  $curl = curl_init();
  curl_setopt_array($curl, array(
    CURLOPT_URL => "https://wasl.api.elm.sa/api/dispatching/v2/drivers",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS =>"{\r\n\"driver\":{\r\n\"identityNumber\":\"$identityNumber\",\r\n\"dateOfBirthGregorian\": \"$dateOfBirthGregorian\",  \r\n\"emailAddress\": \"$emailAddress\", \r\n\"mobileNumber\": \"$mobileNumber\"\r\n},\r\n\"vehicle\":{\r\n\"sequenceNumber\": \"$sequenceNumber\",\r\n\"plateLetterRight\": \"$plateLetterRight\",\r\n\"plateLetterMiddle\": \"$plateLetterMiddle\",\r\n\"plateLetterLeft\": \"$plateLetterLeft\",\r\n\"plateNumber\": \"$plateNumber\",\r\n\"plateType\": \"$plateType\"  \r\n}\r\n}",
    CURLOPT_HTTPHEADER => array(
      "Content-Type:  application/json",
      "client-id: 9231983B-E072-4529-815C-63C77F1CD01B",
      "app-id: 3ff9739b",
      "app-key: a07e82327257c16b24bd468c6a848342",    
    ),
  ));
  $response = curl_exec($curl);
  curl_close($curl);
  return json_decode($response);  
} 

function waslChechEligibility($identityNumber = ''){
  $curl = curl_init();
  curl_setopt_array($curl, array(
    CURLOPT_URL => "https://wasl.api.elm.sa/api/dispatching/v2/drivers/eligibility/$identityNumber",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => array(
      "Content-Type:  application/json",
      "client-id: 9231983B-E072-4529-815C-63C77F1CD01B",
      "app-id: 3ff9739b",
      "app-key: a07e82327257c16b24bd468c6a848342", 
    ),
  ));
  $response = curl_exec($curl);
  curl_close($curl);
  return json_decode($response);  
}

function registerTrip($sequenceNumber ='',$driverId='',$tripId='',$distanceInMeters=0,$durationInSeconds=0,$customerRating=0.0,$customerWaitingTimeInSeconds=0,$originCityNameInArabic='',$destinationCityNameInArabic='',$originLatitude=0.0,$originLongitude=0.0,$destinationLatitude=0.0,$destinationLongitude=0.0,$pickupTimestamp='',$dropoffTimestamp='',$startedWhen='',$tripCost=0.0){
$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => "https://wasl.api.elm.sa/api/dispatching/v2/trips",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS =>"{\r\n\"sequenceNumber\":\"$sequenceNumber\",\r\n\"driverId\": \"$driverId\",\r\n\"tripId\": $tripId,\r\n\"distanceInMeters\":$distanceInMeters,\r\n\"durationInSeconds\":$durationInSeconds,\r\n\"customerRating\":$customerRating,\r\n\"customerWaitingTimeInSeconds\":$customerWaitingTimeInSeconds,\r\n\"originCityNameInArabic\":\"$originCityNameInArabic\",\r\n\"destinationCityNameInArabic\":\"$destinationCityNameInArabic\",\r\n\"originLatitude\":$originLatitude,\r\n\"originLongitude\": $originLongitude,\r\n\"destinationLatitude\": $destinationLatitude,\r\n\"destinationLongitude\":$destinationLongitude,\r\n\"pickupTimestamp\":\"$pickupTimestamp\",\r\n\"dropoffTimestamp\":\"$dropoffTimestamp\",\r\n\"startedWhen\":\"$startedWhen\"\r\n,\r\n\"tripCost\":\"$tripCost\"\r\n}",
  CURLOPT_HTTPHEADER => array(
    "Content-Type:  application/json",
      "client-id: 9231983B-E072-4529-815C-63C77F1CD01B",
      "app-id: 3ff9739b",
      "app-key: a07e82327257c16b24bd468c6a848342", 
  ),
));

$response = curl_exec($curl);
curl_close($curl);
return json_decode( $response );  
}

function registerCaptainsLocations($driverIdentityNumber='',$vehicleSequenceNumber='',$latitude=0.0,$longitude=0.0,$hasCustomer=true,$updatedWhen=''){
  $curl = curl_init();
  curl_setopt_array($curl, array(
    CURLOPT_URL => "https://wasl.api.elm.sa/api/dispatching/v2/locations",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS =>"{\r\n\"locations\":\r\n[\r\n{\r\n\"driverIdentityNumber\":\"$driverIdentityNumber\",\r\n\"vehicleSequenceNumber\": \"$vehicleSequenceNumber\",\r\n\"latitude\": $latitude,\r\n\"longitude\":$longitude,\r\n\"hasCustomer\": $hasCustomer,\r\n\"updatedWhen\":\"$updatedWhen\"\r\n}\r\n]\r\n}",
    CURLOPT_HTTPHEADER => array(
      "Content-Type:  application/json",
      "client-id: 9231983B-E072-4529-815C-63C77F1CD01B",
      "app-id: 3ff9739b",
      "app-key: a07e82327257c16b24bd468c6a848342", 
    ),
  ));

  $response = curl_exec($curl);
  curl_close($curl);
  return json_decode($response);  
} 

function create_rand_numbers($digits = 6){ 
  return rand(pow(10, $digits-1), pow(10, $digits)-1); 
}

function create_random_secret_key($secret){ 
  return implode('-', str_split(Str::limit(encrypt($secret), 36, ''), 6)); 
}

function getUserCouponDiscount($user_id = false,$price = 0){
  $have_coupon = 'false'; $discount = 0;
  if($usercoupon = usersCoupons::where(['user_id'=>$user_id,'used'=>'false'])->where('end_at','>=',date('Y-m-d'))->latest()->first()){
    $coupon_id   = $usercoupon->coupon_id; 
    $have_coupon = 'true';
    if($coupon = Coupons::find($coupon_id)){
       if($coupon->type == 'percentage'){
          $discount = floatval($price) * (floatval( $coupon->value ) / 100) ;
          if ($discount > $coupon->max_discount) {
            $discount = $coupon->max_discount;
          }
       }else{
          if(floatval( $coupon->value ) > floatval($price)){
            $discount = floatval($price);
          }else{
            $discount = floatval( $coupon->value );
          }
       }

    }
    $usercoupon->used = 'true';
    $usercoupon->save();              
  }
  return ['discount' => round( $discount,2) , 'have_coupon' => $have_coupon];
}

function checkRushHour($lat = 0 , $long = 0){
    $lat        = doubleval($lat);
    $long       = doubleval($long);
    $around     = floatval((setting('distance') * 0.1 ) / 15 );
    $min_lat    = $lat  - $around;
    $min_long   = $long - $around;
    $max_lat    = $lat  + $around;
    $max_long   = $long + $around;          
    $num_orders = Order::where('start_lat','>=',$min_lat)
                             ->where('start_lat','<=',$max_lat)
                             ->where('start_long','>=',$min_long)
                             ->where('start_long','<=',$max_long)
                             ->where('status','=','open')->count();
    $rush_hour_percentage = 0;
    if($num_orders >= setting('third_rush_hour')){
      $rush_hour_percentage = setting('third_rush_hour_percentage');
    }elseif($num_orders >= setting('second_rush_hour')){
      $rush_hour_percentage = setting('second_rush_hour_percentage');
    }elseif($num_orders >= setting('first_rush_hour')){
      $rush_hour_percentage = setting('first_rush_hour_percentage'); 
    } 
  return $rush_hour_percentage;     
}

function getAddressBylatlng($lat = '' ,$long = '', $lang = 'ar'){
    $google_key = setting('google_places_key');
    $geocode = "https://maps.googleapis.com/maps/api/geocode/json?latlng=$lat,$long&sensor=false&key=$google_key&language=$lang";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $geocode);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    $response = curl_exec($ch);
    curl_close($ch);
    $output = json_decode($response);
    $dataarray = get_object_vars($output);
    if ($dataarray['status'] != 'ZERO_RESULTS' && $dataarray['status'] != 'INVALID_REQUEST') {
        if (isset($dataarray['results'][0]->formatted_address)) {
            $address = $dataarray['results'][0]->formatted_address;
        } else {
            $address = '';
        }
    } else {
        $address = '';
    }
    return $address;
}

function getCityBylatlng($lat = '' ,$long = '', $lang = 'ar'){
    $google_key = setting('google_places_key');
    $geocode = "https://maps.googleapis.com/maps/api/geocode/json?latlng=$lat,$long&sensor=false&key=$google_key&language=$lang";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $geocode);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    $response = curl_exec($ch);
    curl_close($ch);
    $output = json_decode($response);
    $dataarray = get_object_vars($output);

    if ($dataarray['status'] != 'ZERO_RESULTS' && $dataarray['status'] != 'INVALID_REQUEST') {
        if(isset($dataarray['results'][0]->address_components)){            
            
            if(isset($dataarray['results'][0]->address_components[1])){
                if($dataarray['results'][0]->address_components[1]->types[0] == 'locality' || $dataarray['results'][0]->address_components[1]->types[0] == 'administrative_area_level_1' ){
                   return $dataarray['results'][0]->address_components[1]->short_name;
                }
            } 

            if(isset($dataarray['results'][0]->address_components[2])){
                if($dataarray['results'][0]->address_components[2]->types[0] == 'locality' || $dataarray['results'][0]->address_components[2]->types[0] == 'administrative_area_level_1' ){
                   return $dataarray['results'][0]->address_components[2]->short_name;
                }
            } 
            if(isset($dataarray['results'][0]->address_components[3])){
              if($dataarray['results'][0]->address_components[3]->types[0] == 'locality' || $dataarray['results'][0]->address_components[3]->types[0] == 'administrative_area_level_1' ){
                 return $dataarray['results'][0]->address_components[3]->short_name;
              }
            }
            if(isset($dataarray['results'][0]->address_components[4])){
              if($dataarray['results'][0]->address_components[4]->types[0] == 'locality' || $dataarray['results'][0]->address_components[4]->types[0] == 'administrative_area_level_1' ){
                 return $dataarray['results'][0]->address_components[4]->short_name;
              }
            }
            if(isset($dataarray['results'][0]->address_components[5])){
              if($dataarray['results'][0]->address_components[5]->types[0] == 'locality' || $dataarray['results'][0]->address_components[5]->types[0] == 'administrative_area_level_1' ){
                 return $dataarray['results'][0]->address_components[5]->short_name;
              }
            }            
        }else{
            $short_name = '';
        }
    }else {
        $short_name = '';
    }
    return $short_name;
}

function getFinishedOrdersByDate($date=""){
  return (usersOrdersHistory::where(['date'=>$date,'status'=>'finished'])->count())?? 0;
}

function insavedplaces($place_id=false,$user_id = 0){
    if($place_id != false){
      if($savedPlaces = savedPlaces::where(['place_id'=>$place_id,'user_id'=>$user_id])->first()){
        return true;
      }
    }
    return false;
}

function convertToHoursMins($num_minutes) {
    if ($num_minutes < 1) {
        return $num_minutes;
    }
    $hours = floor($num_minutes / 60);
    $minutes = ($num_minutes % 60);
    if(config('app.locale') == 'en'){
      $format = '%02d h : %02d m';
    }else{
      $format = '%02d س : %02d د';
    }
    return sprintf($format, $hours, $minutes);
}

function directDistance( $latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo){
    $earthRadius = 6371000;
    // convert from degrees to radians
    $latFrom = deg2rad(doubleval( $latitudeFrom) );
    $lonFrom = deg2rad(doubleval( $longitudeFrom) );
    $latTo   = deg2rad(doubleval( $latitudeTo) );
    $lonTo   = deg2rad(doubleval( $longitudeTo) );

    $lonDelta = $lonTo - $lonFrom;
    $a = pow(cos($latTo) * sin($lonDelta), 2) + pow(cos($latFrom) * sin($latTo) - sin($latFrom) * cos($latTo) * cos($lonDelta), 2);
    $b = sin($latFrom) * sin($latTo) + cos($latFrom) * cos($latTo) * cos($lonDelta);
    $angle = atan2(sqrt($a), $b);
    $in_km = ($angle * $earthRadius) / 1000 ;
    return round($in_km, 2);
}

function GetDrivingDistance($lat1='', $long1='',$lat2='', $long2='' ,$lang ='ar'){
    $google_key = setting('google_places_key');
    $url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=".$lat1.",".$long1."&destinations=".$lat2.",".$long2."&mode=driving&language=".$lang."&key=".$google_key;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    $result = curl_exec($ch);
    curl_close($ch);
    $response = json_decode($result, true);
    $time_text = '';
    if($response['rows']){
      if($response['rows'][0]['elements'][0]['status'] == 'ZERO_RESULTS' || ($response['rows'][0]['elements'][0]['status'] == 'NOT_FOUND') ){
        $distance = directDistance($lat1, $long1, $lat2, $long2);
        $time     = ceil($distance * 2).' mins' ;
        $time_text = $time; 
        $distance = $distance * 1000; // in meter
      }else{
        $distance = $response['rows'][0]['elements'][0]['distance']['value'];  // in Meter
        $time_text     = $response['rows'][0]['elements'][0]['duration']['text'];  //in seconds     
        $time          = intval(intval($response['rows'][0]['elements'][0]['duration']['value']) / 60) ;  //in seconds 
        $time          = ($time <= 0)? 1 : $time;     
      }
    }else{
        $distance = directDistance($lat1, $long1, $lat2, $long2);
        $time     = ceil($distance * 2).' mins' ; 
        $time_text = $time; 
        $distance = $distance * 1000;  // in Meter
    }        
    //in text format
    // $distance = $response['rows'][0]['elements'][0]['distance']['text']; 
    // $time     = $response['rows'][0]['elements'][0]['duration']['text']; 
    $in_kms = ($distance / 1000); //in kms 
    $in_kms = round($in_kms, 2);

    return ['distance' => $in_kms , 'time' => $time , 'time_text'=>$time_text];
}

function GetPathAndDirections($lat1='', $long1='',$lat2='', $long2='' ,$path='',$lang ='ar'){
    // $path = '31.0345612,31.3489804|31.0328805,31.36542648';
    //https://maps.googleapis.com/maps/api/directions/json?origin=31.0345612,31.3489804&destination=31.0034004,31.3730575&waypoints=31.0328805,31.36542648&mode=driving&language=ar&key=AIzaSyDYjCVA8YFhqN2pGiW4I8BCwhlxThs1Lc0
    $google_key = setting('google_places_key');
    $url = "https://maps.googleapis.com/maps/api/directions/json?origin=".$lat1.",".$long1."&destination=".$lat2.",".$long2."&waypoints=".$path."&mode=driving&language=".$lang."&key=".$google_key;
    $ch  = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    $result = curl_exec($ch);
    curl_close($ch);
    $response = json_decode($result, true);
    // routes->0->legs->
    //                [0,1,..]->
    //                          distance&duration&end_location->lat&lng
    $distance = 0; 
    $time     = 0;
    if($response['routes']){
        foreach($response['routes'][0]['legs'] as $road){
           $distance += $road['distance']['value'];
           // $time     += $road['duration']['value'];
        }    
    }else{
        $distance = directDistance($lat1, $long1, $lat2, $long2);
        $distance = $distance * 1000;  // in Meter
        // $time     = intval($distance * 1.2).' '.trans('order.minute') ; 
    }        
    $in_kms = ($distance / 1000); //in kms 
    $in_kms = round($in_kms, 2);

    return $in_kms;
}

function currentCountry(){
    $ip = '';
    if(isset($_SERVER['REMOTE_ADDR'])){
      $ip = $_SERVER['REMOTE_ADDR']; // This will contain the ip of the request
    }
    $data = array(  'iso'      => 'SA',          // EG
                    'name'     => 'saudi arabia',//"Egypt"
                    'currency' => 'SAR',   //"EGP"
                    'symbol'   => 'SR',    // "£"
                    'ratio'    => '3.750', //to USD  "17.3873"
                    'time_zone'=> 'Asia/Riyadh'
                  );
    // $url = "http://www.geoplugin.net/json.gp?ip=".$ip;
    //   // if(is_readable($url)){
    //     $geoplugin = @file_get_contents($url,true);
    //     if($geoplugin === FALSE){
    //         return $data;
    //     }else{
    //       $dataArray = json_decode($geoplugin);
    //       if($dataArray){
    //         $data = array('iso'      => $dataArray->geoplugin_countryCode,    // EG
    //                       'name'     => $dataArray->geoplugin_countryName,    //"Egypt"
    //                       'currency' => $dataArray->geoplugin_currencyCode,   //"EGP"
    //                       'symbol'   => $dataArray->geoplugin_currencySymbol, // "£"
    //                       'ratio'    => $dataArray->geoplugin_currencyConverter, //to USD  "17.3873"
    //                       'time_zone'=> $dataArray->geoplugin_timezone
    //                     );
    //       }
    //     }       
      // }
    return  $data;
}

function convertCurrency($amount,$from_currency,$to_currency){
  $apikey        = setting('currencyconverterapi');
  $from_Currency = urlencode($from_currency);
  $to_Currency   = urlencode($to_currency);
  $rate = 1;
  
  // if(strtoupper($from_Currency) != strtoupper($to_Currency) ){
  //     $query         = "{$from_Currency}_{$to_Currency}";
  //     $url = @file_get_contents("http://api.currencyconverterapi.com/api/v6/convert?q={$query}&compact=ultra&apiKey={$apikey}");
  //     if($url === false) {
  //       $countries = $from_currency.'_'.$to_currency;
  //       $json = @file_get_contents('http://free.currencyconverterapi.com/api/v5/convert?q='.$countries.'&compact=ultra');
  //     }else{
  //       $json = @file_get_contents("http://api.currencyconverterapi.com/api/v6/convert?q={$query}&compact=ultra&apiKey={$apikey}");
  //     }
  //     $obj   = json_decode($json, true);
  //     $rate  = floatval($obj["$query"]);
  // }
  $total = $rate * $amount;
  return number_format($total, 2, '.', '');
} 

function savePayment($user_id=0,$second_user_id=0,$amount=0,$type='',$operation='',$status='inprogress',$country_id = 1,$wallet_type='balance'){
   $payment = new Payments();
   $payment->user_id        = $user_id;
   $payment->second_user_id = $second_user_id;
   $payment->amount         = $amount;
   $payment->type           = $type;
   $payment->status         = $status;
   $payment->operation      = $operation;
   $payment->country_id     = $country_id;
   $payment->wallet_type    = $wallet_type;
   $payment->save();
   return $payment->id; 
}

function checkBlock($user_id = false){
    if($user_id != false){
        if($block = userBlocks::where('user_id','=',$user_id)->first()){
              $to_time   = strtotime($block->to_time);
              $from_time = strtotime(date('Y-m-d H:i:s'));
              $stillhours= intval( round( ($to_time - $from_time) / 3600,2) );
              if( $stillhours > 0){
                $date = Date::parse($block->to_time)->format('h:i').''.trans('order.'.date('a'));
                return "حظر طلبات (".$date.")";            
              }else{
                $block->delete();
                return 'true';
              }
        }
       return 'true';
    }
    return 'true';
}

function setting($key=false){
           if($key!=false){
              if($s = Setting::where('set_key','=',$key)->first()){
                return $s->set_value;
              }
              return false;
           } 
           return false; 
  } 

function successReturn($data = array(),$msg=''){
    $user_status = (Auth::user())? Auth::user()->active : 'active';
    return ['value' => '1' , 'key' => 'success' , 'data' => $data, 'msg' => $msg , 'code' => 200 , 'user_status' => $user_status];
}

function successReturnMsg($msg = ''){
    $user_status = (Auth::user())? Auth::user()->active : 'active';
    return ['value' => '1' , 'key' => 'success' , 'msg' => $msg,'code' => 200 , 'user_status' => $user_status];
}

function failReturn($msg = ''){
    $user_status = (Auth::user())? Auth::user()->active : 'active';
    return ['value' => '0' , 'key' => 'fail' , 'msg' => $msg , 'code' => 401 , 'user_status' => $user_status];
}

function failActionReturn($msg = ''){
    $user_status = (Auth::user())? Auth::user()->active : 'active';
    return ['value' => '2' , 'key' => 'fail' , 'msg' => $msg , 'code' => 401 , 'user_status' => $user_status];
}

function failReturnData($data = [],$msg='' ){
    $user_status = (Auth::user())? Auth::user()->active : 'active';
    return ['value' => '0' , 'key' => 'fail' , 'msg' => $msg, 'data' => $data , 'code' => 401, 'user_status' => $user_status];
}

//calculate if pray time near
function praytime($from_lat='',$from_long='',$lang='ar'){
        $praytimes = []; $msg = '';
        $url="http://api.aladhan.com/v1/calendar?latitude=".$from_lat."&longitude=".$from_long."&method=4&month=".date("m")."&year=".date("Y");
          $jsonresult = @file_get_contents($url,true);
          if($jsonresult === FALSE){
            return '';
          }else{
            if($results    = json_decode($jsonresult)){        
              if(isset($results->data)){
                $currentday = intval(date('d'))-1 ;
                $praytimes['Fajr']    = $results->data[$currentday]->timings->Fajr;
                $praytimes['Dhuhr']   = $results->data[$currentday]->timings->Dhuhr;
                $praytimes['Asr']     = $results->data[$currentday]->timings->Asr;
                $praytimes['Maghrib'] = $results->data[$currentday]->timings->Maghrib;
                $praytimes['Isha']    = $results->data[$currentday]->timings->Isha;
                foreach($praytimes as $key=>$value){
                  $praytime  = substr($value, 0, 5);
                  $to_time   = strtotime(date("Y-m-d")." ".$praytime);
                  $from_time = strtotime(date('Y-m-d H:i'));
                  $minutes   = intval( round( ($to_time - $from_time) / 60,2) );
                  if(($minutes <= 30) && ($minutes >= 0) ){
                    $msg = setting('pray_msg_'.$lang);
                  }
                } 
              } 
            }
          }
    return $msg;   
}

function notify($user_id=false,$notifier_id=false,$title='',$message='',$data='',$status='',$key=''){
      if(($user_id !=false) && ($message != '') && ($data != '')){
        $notification = new Notifications();
        $notification->user_id      = $user_id;
        $notification->notifier_id  = $notifier_id;
        $notification->title        = $title;
        $notification->message      = $message;
        $notification->data         = $data;
        $notification->order_status = $status;
        $notification->key          = $key;
        $notification->save();
        return $notification->id;
      }
}

function sendNotification($devices = [],$message = '',$title='',$data=[],$neworder=''){
            $iosTokens=[]; $androidTokens=[];
            if(count($devices) > 0){
                if(count($devices) > 1){
                  foreach($devices as $device) {
                      // if($neworder == 'newOrder'){
                          // if($device->near_orders_notify == 'true'){
                              // if($device->device_type == 'ios'){
                              //   $iosTokens[] = $device->device_id;
                              // }else{
                              //   $androidTokens[] = $device->device_id;
                              // }
                          // }
                      // }elseif($neworder == 'newOrderwithPlace'){
                          // if($device->orders_notify == 'true'){
                          //     if($device->device_type == 'ios'){
                          //       $iosTokens[] = $device->device_id;
                          //     }else{
                          //       $androidTokens[] = $device->device_id;
                          //     }
                          // }
                      // }else{
                              if($device->device_type == 'ios'){
                                $iosTokens[] = $device->device_id;
                              }else{
                                $androidTokens[] = $device->device_id;
                              }
                      // }
                  }
              }else{
                if(isset($devices->device_type)){
                  if($devices->device_type == 'ios'){
                    $iosTokens[] = $devices->device_id;
                  }else{
                    $androidTokens[] = $devices->device_id;
                  }
                }elseif(count($devices)){
                    foreach($devices as $device) {
                        if($device->device_type == 'ios'){
                            $iosTokens[] = $device->device_id;
                        }else{
                            $androidTokens[] = $device->device_id;
                        }
                    }
                }
              }
            }
            // elseif(count($devices) == 1){
            //   if($devices->device_type == 'ios'){
            //     $iosTokens[] = $devices->device_id;
            //   }else{
            //     $androidTokens[] = $devices->device_id;
            //   }
            // }
            if(count($iosTokens) > 0){
                $optionBuilder = new OptionsBuilder();
                $optionBuilder->setTimeToLive(60*20);
                $optionBuilder->setMutableContent(true);
                $notificationBuilder = new PayloadNotificationBuilder($title);
                if($neworder == 'newOrder'){
                $notificationBuilder->setBody($message)
                                    // ->setBadge(1)
                                    ->setSound('ring.caf');
                }else{
                $notificationBuilder->setBody($message)
                                    // ->setBadge(1)
                                    ->setSound('default');
                }
                $dataBuilder = new PayloadDataBuilder();
                $dataBuilder->addData($data);

                $option = $optionBuilder->build();
                $notification = $notificationBuilder->build();
                $databuild = $dataBuilder->build();
                $downstreamResponse = FCM::sendTo($iosTokens, $option, $notification, $databuild);
            }
            if(count($androidTokens) > 0){
                $optionBuilder = new OptionsBuilder();
                $optionBuilder->setTimeToLive(60*20);
                $optionBuilder->setMutableContent(true);
                $notificationBuilder = new PayloadNotificationBuilder($title);
                $notificationBuilder->setBody($message)
                                    // ->setBadge(1)
                                    ->setSound('default');
                $dataBuilder = new PayloadDataBuilder();
                $dataBuilder->addData($data);

                $option = $optionBuilder->build();
                $notification = Null;
                $databuild = $dataBuilder->build();
                $downstreamResponse = FCM::sendTo($androidTokens, $option, $notification, $databuild);
            }
                // $tokens = "dx0vu3gBtho:APA91bE-X-6yjUmZd-dbpchOt-qPC4sJXDfs_0sDuEXwfviluD5E3FcWCQv0NXRTs_De6Ja_2eNeizw9yz7IBa2UrIJBNyHK0FrmPQRArq2mzv355MWrH1DbWFhA1vRqS67gO3v_iVAu";//MYDATABASE::pluck('fcm_token')->toArray();
}

#role name
function Role(){
    $role = Role::findOrFail(Auth::user()->role);
    if($role)
    {
        return $role->role;
    }else{
        return 'عضو';
    }
}

#messages notification
function Notification(){
    $messages = Contact::where('showOrNow',0)->latest()->get(); 
    return $messages;
}

function newUserMetas(){
    $usermetas = userMeta::where('seen','=','false')->latest()->get(); 
    return $usermetas;
}

#upload image base64
function save_img($base64_img, $img_name, $path)
{
    $full_path = $_SERVER['DOCUMENT_ROOT'].'/'.$path;
    $image_data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64_img));
    $image_data;
    $source = imagecreatefromstring($image_data);
    $angle = 0;
    $rotate = imagerotate($source, $angle, 0); // if want to rotate the image
    $imageName = $img_name . '.png';
    $path_new = $full_path . '/' . $imageName;
    $imageSave = imagejpeg($rotate, $path_new, 100);
    if($imageSave)
    {
        return true;
    }else
    {
        return false;
    }  
}
    


#report
function History($user_id,$event)
{
    $report = new History;
    $user = User::findOrFail($user_id);
   if($user->role > 0)
   {
       $report->user_id = $user->id;
       $report->event   = 'قام '.$user->name .' '.$event;
       $report->supervisor = 1;
       $report->save();
   }else
   {
       $report->user_id = $user->id;
       $report->event   = 'قام '.$user->name .' '.$event;
       $report->supervisor = 0;
       $report->save();
   }

}

#current route
function currentRoute()
{
    $routes = Route::getRoutes();
    foreach ($routes as $value)
    {
        if($value->getName() === Route::currentRouteName()) 
        {
            echo $value->getAction()['title'] ;
        }
    }
}

#email colors
function EmailColors()
{
    $html = Html::select('email_header_color','email_footer_color','email_font_color')->first();
    return $html;
}

function phoneValidate($number = ''){
    if (substr($number, 0, 1) === '0'){
        $number = substr($number, 1);
    }
    if (substr($number, 0, 4) === '+966'){
        $number = substr($number, 4);
    }
    if (substr($number, 0, 4) === '0966'){
        $number = substr($number, 4);
    } 
    if (substr($number, 0, 3) === '+20'){
        $number = substr($number, 3);
    }
    if (substr($number, 0, 3) === '020'){
        $number = substr($number, 3);
    }     
    $phone = preg_replace('/\s+/', '', $number);
    return $phone; 
}

function convert2english($string) {
    $newNumbers = range(0, 9);
    $arabic = array('٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩');
    $string =  str_replace($arabic, $newNumbers, $string);
    return $string;
}

function is_unique($key,$value){
    $user  = User::where($key , $value)->first();
    if( $user ){
        return true;
    }
    return false;
}

function getuser($user_id = false){
    $user  = User::find($user_id);
    return ($user)?? '';
}

function getcartype($car_type_id = false){
    $cartype  = carTypes::find($car_type_id);
    return ($cartype)?? '';
}


function generate_code() {
    $characters = '0123456789';
    $charactersLength = strlen($characters);
    $token = '';
    $length = 4;
    for ($i = 0; $i < $length; $i++) {
        $token .= $characters[rand(0, $charactersLength - 1)];
    }
    if($user = User::where(['code'=>$token])->first()){
        return generate_code();
    }
    return $token;
}

function generate_share_code() {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyz';//ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $token = '';
        $length = 6;
        for ($i = 0; $i < $length; $i++) {
            $token .= $characters[rand(0, $charactersLength - 1)];
        }
        if($user = User::where(['share_code'=>$token])->first()){
            return generate_share_code();
        }
        return $token;
}

function generate_pin_code() {
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $token = '';
        $length = 6;
        for ($i = 0; $i < $length; $i++) {
            $token .= $characters[rand(0, $charactersLength - 1)];
        }
        if($user = User::where(['pin_code'=>$token])->first()){
            return generate_pin_code();
        }
        return $token;
}

#send sms
function send_mobile_sms($numbers, $msg) {
    if($smsprovider = SmsEmailNotification::where('type','!=','smtp')
                                         ->where('type','!=','fcm')
                                         ->where('type','!=','onesignal')
                                         ->where('active','=','true')
                                         ->first() ){

    $mobile   = $smsprovider->number; 
    $sender   = $smsprovider->sender_name; 
    $password = $smsprovider->password; 
    $provider = $smsprovider->type;
    switch ($provider) {
        case 'unifonic' :
            unifonic_sms($mobile,$sender,$password,$numbers,$msg);
            break;
        case 'mobily':
            mobily_sms($mobile,$sender,$password,$numbers, $msg);
            break;
        case 'yamamah':
            yamamah_sms($mobile,$sender,$password,$numbers, $msg);
            break;
        case 'oursms':
            oursms_sms($mobile,$sender,$password,$numbers, $msg);
            break; 
        case 'hisms':
            hisms_sms($mobile,$sender,$password,$numbers, $msg);
            break;  
        case '4jawaly':
            jawaly_sms($mobile,$sender,$password,$numbers, $msg);
            break; 
        case 'nexmosms':
            nexmosms_sms($mobile,$sender,$password,$numbers, $msg);
            break; 
        case 'twilio':
            twilio_sms($mobile,$sender,$password,$numbers, $msg);
            break; 
        case 'gateway':
            gateway($mobile,$sender,$password,$numbers, $msg);
            break; 
        case 'msegat':
            msegat($mobile,$sender,$password,$numbers, $msg);
            break; 
        }

    }
    return true; 
}

function nexmosms_sms($APPid,$sender,$APPSecret,$numbers, $msg){
/** put your credentials in .env , config/nexmo.php
NEXMO_KEY=my_api_key
NEXMO_SECRET=my_secret
**/
    Nexmo::message()->send([
        'to'   => $numbers,
        'from' => $sender,
        'text' => $msg
    ]);
    return true;
}

function twilio_sms($mobile,$sender,$password,$numbers, $msg){
/** put your credentials in .env , config/app.php
TWILIO_ACCOUNT_SID=ACXXXXXXXXXXXXXXXXXXX
TWILIO_AUTH_TOKEN=XXXXXXXXXXXXXXXXXXXX
TWILIO_APP_SID=APXXXXXXXXXXXXXXXXXXX
**/
        $accountSid = config('app.twilio')['TWILIO_ACCOUNT_SID'];
        $authToken  = config('app.twilio')['TWILIO_AUTH_TOKEN'];
        $appSid     = config('app.twilio')['TWILIO_APP_SID'];
        $client = new Client($accountSid, $authToken);
        try
        {
            $client->messages->create( $numbers,['from' => $mobile,'body' => $mobile]);
        }
        catch (Exception $e)
        {
            echo "Error: " . $e->getMessage();
        }
    return true;
}
function hisms_sms($mobile,$sender,$password,$numbers, $msg){ 
      $text = urlencode( $msg);
      $sender   = urlencode( $sender);
      $url  = "https://www.hisms.ws/api.php?send_sms&username=$mobile&password=$password&numbers=$numbers&sender=$sender&message=$text";    
      $result = file_get_contents($url,true);
      if($result === false){
        return false;
      }else{
        return true;         
      }  
}

function jawaly_sms($mobile,$sender,$password,$numbers, $msg){ 
    // $curl = curl_init();
    // $app_id   = $mobile;
    // $app_sec  = $password;
    // $app_hash  = base64_encode("$app_id:$app_sec");
   
    // $messages = [];
    // $messages["messages"] = [];
    // $messages["messages"][0]["text"] = $msg;
    // $messages["messages"][0]["numbers"][] = $numbers;
    // $messages["messages"][0]["sender"] = $sender;

    // curl_setopt_array($curl, array(
    // CURLOPT_URL => 'https://api-sms.4jawaly.com/api/v1/account/area/sms/send',
    // CURLOPT_RETURNTRANSFER => true,
    // CURLOPT_ENCODING => '',
    // CURLOPT_MAXREDIRS => 10,
    // CURLOPT_TIMEOUT => 0,
    // CURLOPT_FOLLOWLOCATION => true,
    // CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    // CURLOPT_CUSTOMREQUEST => 'POST',
    // CURLOPT_POSTFIELDS =>json_encode($messages),
    // CURLOPT_HTTPHEADER => array(
    //     'Accept: application/json',
    //     'Content-Type: application/json',
    //     'Authorization: Basic '.$app_hash
    // ),
    // ));

    // $response = curl_exec($curl);
    // curl_close($curl);
    // $response = json_decode($response);

    $app_id = $mobile;
    $app_sec = $password;
    $app_hash = base64_encode("{$app_id}:{$app_sec}");
    
    if (strpos($numbers, ',') !== false) {
      $numbers = explode(',', $numbers);
    }else{
      $numbers = [$numbers];
    }

    $messages = [
        "messages" => [
            [
                "text" => $msg,
                "numbers" => $numbers,
                "sender" => $sender
            ]
        ]
    ];
    
    $url = "https://api-sms.4jawaly.com/api/v1/account/area/sms/send";
    $headers = [
        "Accept: application/json",
        "Content-Type: application/json",
        "Authorization: Basic {$app_hash}"
    ];
    
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($messages));
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    
    $response = curl_exec($curl);
    $status_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);
    
    $response_json = json_decode($response, true);
    // if ($status_code == 200) {
    //     if (isset($response_json["messages"][0]["err_text"])) {
    //         echo $response_json["messages"][0]["err_text"];
    //     } else {
    //         echo "تم الارسال بنجاح  " . " job id:" . $response_json["job_id"];
    //     }
    // } elseif ($status_code == 400) {
    //     echo $response_json["message"];
    // } elseif ($status_code == 422) {
    //     echo "نص الرسالة فارغ";
    // } else {
    //     echo "محظور بواسطة كلاودفلير. Status code: {$status_code}";
    // }

    return true;         
}


function yamamah_sms($mobile,$sender,$password,$numbers, $msg){
$url = 'http://api.yamamah.com/SendSMS';
    $mobile   = $mobile;
    $password = $password;
    $msg = $msg;
    $sender = $sender;//urlencode($sender);
    $fields = array(
        "Username" => $mobile,
        "Password" => $password,
        "Tagname" => 'Ezzk',
        "Message" => $msg,
        "RecepientNumber" => $numbers,
//
    );
    $fields_string = json_encode($fields);
    //open connection
    $ch = curl_init($url);
    curl_setopt_array($ch, array(
        CURLOPT_POST => TRUE,
        CURLOPT_RETURNTRANSFER => TRUE,
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
        ),
        CURLOPT_POSTFIELDS => $fields_string
    ));

    $result = curl_exec($ch);
    curl_close($ch);
    return true;
}

function oursms_sms($mobile,$sender,$password,$numbers, $msg){
$text     = urlencode( $msg);
$sender   = urlencode( $sender);
// auth call
$url = "http://www.oursms.net/api/sendsms.php?username=$mobile&password=$password&numbers=$numbers&message=$text&sender=$sender&unicode=E&return=full";    
      $result = @file_get_contents($url,true);
      if($result === false){
        return false;
      }else{
        return true;         
      }
}

function unifonic_sms($mobile,$sender,$password,$numbers, $msg){
$text     = urlencode( $msg);
$sender   = urlencode( $sender);
$numbers  = explode(',', $numbers);
if(is_array($numbers)){
  for ($i = 0 ; $i < count($numbers) ; $i++) { 
    if ( substr($numbers[$i], 0, 2) === '00' ){
        $numbers[$i] = substr($numbers[$i], 2);
    }elseif(substr($numbers[$i], 0, 1) === '+' ){
        $numbers[$i] = substr($numbers[$i], 1);
    }
  }
  $numbers = implode(',', $numbers);
}
$url = "http://api.unifonic.com/wrapper/sendSMS.php?userid=$mobile&password=$password&msg=$text&sender=$sender&to=$numbers&encoding=UTF8";    
      $result = @file_get_contents($url,true);
      if($result === false){
        return false;
      }else{
        return true;         
      }
}

function mobily_sms($mobile,$sender,$password,$numbers, $msg){
$client = new SoapClient("http://www.mobilywebservices.com/SMSWebService/SMSIntegration.asmx?wsdl");
$mobile = $mobile;             //رقم الجوال (إسم المستخدم) في موبايلي
$password = $password;             //كلمة المرور في موبايلي
$sender = $sender;          //اسم المرسل الذي سيظهر عند ارسال الرساله، ويتم تشفيره إلى  بشكل تلقائي إلى نوع التشفير (urlencode)
$numbers = $numbers;              //يجب كتابة الرقم بالصيغة الدولية مثل 96650555555 وعند الإرسال إلى أكثر من رقم يجب وضع الفاصلة (,) وهي التي عند حرف الواو بين كل رقمين 
$msg = $msg;    
$MsgID = rand(1,99999);         //رقم عشوائي يتم إرفاقه مع الإرساليه، في حال الرغبة بإرسال نفس الإرساليه في أقل من ساعه من إرسال الرساله الأولى.
$timeSend = 0;              //لتحديد وقت الإرساليه - 0 تعني الإرسال الآن
$dateSend = 0;              //لتحديد تاريخ الإرساليه - 0 تعني الإرسال الآن
$deleteKey = 152485;          //يمكنك من خلال هذه القيمة  القيمه يمكنك من خلالها حذف الرساله من خلال بوابة حذف الرسائل.
$lang = 3;                //عند إرسال نص الرسالة بدون أي تشفير يجب إرسال المتغير lang على بوابة الإرسال وبالقيمة 3 .  
$sendSMSParam = array(
                      'userName'=>$mobile,
                      'password'=>$password,
                      'numbers'=>$numbers, 
                      'sender'=>$sender, 
                      'message'=>$msg, 
                      'dateSend'=>$dateSend, 
                      'timeSend'=>$timeSend, 
                      'deleteKey'=>$deleteKey,
            'lang'=>$lang,
                      'messageId'=> $MsgID, 
                      'applicationType'=>'24', 
                      'domainName'=>''
                      );
$sendSMSProcess = $client->SendSMS($sendSMSParam);
echo $sendSMSProcess->SendSMSResult; 
return true;  
}


function gateway($mobile,$sender,$password,$numbers, $msg){

    $contextPostValues = http_build_query(array('user'=>$mobile, 'password'=>$password, 'msisdn'=>'00966'.$numbers, 'sid'=>$sender, 'msg'=>$msg, 'fl'=>0));
    $contextOptions['http'] = array('method' => 'POST', 'header'=>'Content-type: application/x-www-form-urlencoded', 'content'=> $contextPostValues, 'max_redirects'=>0, 'protocol_version'=> 1.0, 'timeout'=>10, 'ignore_errors'=>TRUE);
    $contextResouce = stream_context_create($contextOptions);
    $url = "apps.gateway.sa/vendorsms/pushsms.aspx";
    $arrayResult = file($url, FILE_IGNORE_NEW_LINES, $contextResouce);
    $result = $arrayResult[0];
        if ($result) {
            return true;
        } else {
            return false;
        }     
}

  // public function msegat($mobile,$sender,$password,$numbers, $msg) {
  //   $username = $mobile;
  //   $password = $password;
  //   $sender   = $sender;
  //   $ch = curl_init();
  //   curl_setopt($ch, CURLOPT_URL, "https://www.msegat.com/gw/sendsms.php");
  //   curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
  //   curl_setopt($ch, CURLOPT_HEADER, TRUE);
  //   curl_setopt($ch, CURLOPT_POST, TRUE);
  //   $fields = <<<EOT
  //       {
  //       "userName": "$username",
  //       "numbers": "$numbers",
  //       "userSender": "$sender",
  //       "apiKey": "$password",
  //       "msg": "$msg"
  //       }
  //       EOT;
  //   curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);

  //   curl_setopt($ch, CURLOPT_HTTPHEADER, array(
  //     "Content-Type: application/json",
  //   ));

  //   $response = curl_exec($ch);
  //   $info     = curl_getinfo($ch);
  //   curl_close($ch);
  //   return true;
  // }

function upload_img($base64_img ,$path) {
    $file     = base64_decode($base64_img);
    $safeName = str_random(10) . '.' . 'png';
    file_put_contents($path . $safeName, $file);
    return $safeName;
}

function replacePlaceholders($string, $data) {
  foreach ($data as $key => $value) {
      $string = str_replace($key, $value, $string);
  }
  return $string;
}