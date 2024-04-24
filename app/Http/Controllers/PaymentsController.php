<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Auth;
use App\User;
use Session;
use Validator;
// use Paytabs;

use App\Payments;
use App\Exports\PaymentsExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Transactions;
use App\Package;

class PaymentsController extends Controller{
   
    public function payment($user_id = 0,$package_id = 0){
        if($user = User::find($user_id)){
            Auth::login($user);
            $amount = '';
            if($package_id){
                $package = Package::find($package_id);
                $type   = ( $package->type )??'';
                $amount = ( $package->price )??'';
                session(['package_id' => $package_id , 'type' => $type]);
            }
          return view('payment.transfer',compact('user','amount'));
        }
          return view('payment.payment');
    }

    public function sendPaymentCode(Request $request){
        $validator    = Validator::make($request->all(), [
            'phone'   => 'required|min:9|max:255',
        ]);
        if ($validator->passes()) {

            $msg            = trans('auth.yourcode').''.setting('site_title').' : ';
            $number         = convert2english(request('phone'));
            $phone          = phoneValidate($number);
            // if (substr($number, 0, 1) === '0'){
            //     $number = substr($number, 1);
            // }
            // $phone          = $number;

            if( $user = User::where(['phone'=>$phone])->first()) {
                // if($user->delegate == 'true'){
                //    return back()->with('errormsg','هذا الحساب مسجل كمندوب بالفعل.');  
                // }
                $user->code = generate_code();
                $user->save();
                $msg        = $msg . $user->code;
                $phone      = $user->phone;
                $key        = $user->phonekey;
                send_mobile_sms($key.''.$phone, $msg);
                $phone = $user->phone;
                Session::put('phone',$phone);
                return redirect('paymentUserCode/'.$phone);
            }else{
                 return back()->with('errormsg','رقم الهاتف الذى ادخلتة لا ينتمي لأي مستخدم.');  
            }
        }else {
                $msg   = implode(' , ',$validator->errors()->all());
                return back()->with('errormsg',$msg); 
        }            
    }

    public function paymentUserCode($phone = ''){
        return view('payment.code',['phone'=>$phone]);
    }

    public function paymentCodeVerfication(Request $request){
        $validator         = Validator::make($request->all(),[
            'phone'        => 'required',
            'code'         => 'required',
        ]);
        if($validator->passes()){
            $number         = convert2english(request('phone'));
            $phone          = phoneValidate($number);
            // if (substr($number, 0, 1) === '0'){
            //     $number = substr($number, 1);
            // }
            // $phone          = $number;            
            if($user = User::where(['phone'=>$phone,'code'=>$request->code])->first()){
                // if($user->delegate == 'true'){
                //    return back()->with('errormsg','هذا الحساب مسجل كمندوب بالفعل.');  
                // }
                if($user->code != request('code')){
                    $msg        = 'كود التحقق الذى ادخلتة غير صحيح.';
                    return back()->with('errormsg','كود التحقق الذى ادخلتة غير صحيح.');  
                }
                Auth::login($user);
                return view('payment.transfer',compact('user'));
            }
            return back()->with('errormsg','كود التحقق الذى ادخلتة غير صحيح.');  
        }else{
            return back()->with('errormsg','يجب ادخال كود التحقق.');  
        }
    }

    public function get_client_ip(){
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if(getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if(getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if(getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if(getenv('HTTP_FORWARDED'))
           $ipaddress = getenv('HTTP_FORWARDED');
        else if(getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }

    // public function paytabs_payment(Request $request) {
    //     $this->validate(request(), [
    //         'amount'            => 'required',
    //     ]);
    //         if(Auth::check()){
    //             $user = Auth::user();
    //             $merchant_reference = 'user.'.$user->id.'.'.time();
    //             $amount             = $request->amount;
    //             $MERCHANT_EMAIL     = setting('paytabs_merchant_email');
    //             $SECRET_KEY         = setting('paytabs_secret_key');
    //             $pt                 = Paytabs::getInstance($MERCHANT_EMAIL,$SECRET_KEY);
    //             $result = $pt->create_pay_page(array(
    //                 "merchant_email"  => $MERCHANT_EMAIL,
    //                 'secret_key'      => $SECRET_KEY,
    //                 'title'           => setting('site_title'),
    //                 'cc_first_name'   => $user->name,
    //                 'cc_last_name'    => "-- ".$user->name,
    //                 'email'           => ($user->email)??'0'.$user->phone,
    //                 'cc_phone_number' => $user->phonekey,
    //                 'phone_number'    => $user->phone,
    //                 'billing_address' => ($user->address)? (($user->country)? $user->country->name_ar : 'SAU') : 'SAU' ,
    //                 'city'            => ($user->address)? (($user->country)? $user->country->name_ar : 'SAU') : 'SAU' ,
    //                 'state'           => ($user->address)? (($user->country)? $user->country->name_ar : 'SAU') : 'SAU' ,
    //                 'postal_code'     => ($user->address)? (($user->country)? $user->country->name_ar : 'SAU') : 'SAU' ,
    //                 'country'              => "SAU",
    //                 'address_shipping'     => ($user->address)? (($user->country)? $user->country->name_ar : 'SAU') : 'SAU' ,
    //                 'city_shipping'        => ($user->address)? (($user->country)? $user->country->name_ar : 'SAU') : 'SAU' ,
    //                 'state_shipping'       => ($user->address)? (($user->country)? $user->country->name_ar : 'SAU') : 'SAU' ,
    //                 'postal_code_shipping' => ($user->address)? (($user->country)? $user->country->name_ar : 'SAU') : 'SAU' ,
    //                 'country_shipping'     => "SAU",
    //                 "products_per_title"   => "شحن المحفظة في ".setting('site_title'),
    //                 'currency'             => setting('site_currency_en'),
    //                 "unit_price"           => $amount,
    //                 'quantity'             => "1",
    //                 'other_charges'        => "0",
    //                 'amount'               => $amount,
    //                 'discount'             =>"0",
    //                 "msg_lang"             => "arabic",
    //                 "reference_no"         => $merchant_reference,
    //                 "site_url"             => setting('site_url'),
    //                 'return_url'           => route('paytabs_response'),
    //                 "cms_with_version"     => "API USING PHP"
    //             ));

    //             if($result->response_code == 4012){
    //                return redirect($result->payment_url);
    //             }
    //             return back()->with('msg',$result->result);
    //     }
    //     return back()->with('msg','حدث خطأ ما يرجي اعادة المحاولة.');
    // }

    // public function paytabs_response(Request $request){
    //     $MERCHANT_EMAIL     = setting('paytabs_merchant_email');
    //     $SECRET_KEY         = setting('paytabs_secret_key');        
    //     $pt     = Paytabs::getInstance($MERCHANT_EMAIL, $SECRET_KEY);
    //     $result = $pt->verify_payment($request->payment_reference);
    //     if($result->response_code == 100){
    //         // Payment Success
    //            $user = Auth::user();
    //            $user->balance += round(($result->transaction_amount),2) ;
    //            $user->save();
    //            return redirect('payment')->with('successmsg','تم اضافة التحويل بنجاح , رصيدك الحالى ( '.$user->balance.' '.setting('site_currency_ar').' ).');            
    //     }
    //     return redirect('payment')->with('msg',$result->result);
    // }

    function transferBalance(Request $request) {
        $this->validate(request(), [
            'amount'            => 'required',
        ]);
        if(Auth::check()){
            $amount = number_format((float)$request->amount, 2, '.', '');
            return view('payment.transferBalance',compact('amount'));
        }
        return redirect('payment');
    }

    function visaTransferBalance($amount) {
        if(Auth::check()){
            $user = Auth::user();
                $url = "https://oppwa.com/v1/checkouts";
                // $url = "https://test.oppwa.com/v1/checkouts";
                $curlopt = false;
            $user_email = $user->email? $user->email:$user->phone.'@letsgo.com';
            $country_iso = ($user->country)? $user->country->iso2 : 'SA';
            $user_address = ($user->address)? $user->address : $country_iso;
            $data = "entityId=8acda4ca845bb82a0184a86e7a55058e".
                "&amount=".$amount.
                "&currency=SAR" .
                "&merchantTransactionId=".rand(1111,9999).$user->id.
                "&customer.email=".$user_email.
                "&paymentType=DB".
                // "&testMode=EXTERNAL".
                "&billing.country=".$country_iso.
                "&customer.givenName=".$user->name.
                "&customer.surname=".$user->name.
                "&billing.street1=".$user_address.
                "&billing.city=".$user_address.
                "&billing.state=".substr($user_address, 0, 49).
                "&billing.postcode=".substr($user_address, 0, 29);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                "Authorization:Bearer OGFjZGE0Y2E4NDViYjgyYTAxODRhODZkYjVjOTA1ODl8TUQ4d2s1UFhkRw=="));
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $curlopt);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $responseData = curl_exec($ch);
            if(curl_errno($ch)) {
                return curl_error($ch);
            }
            curl_close($ch);
            $responseData = json_decode($responseData);
            $checkoutId = $responseData->id;
            #save the transaction
            $transaction   = new Transactions();
            $transaction->user_id        = $user->id;
            $transaction->transaction_id = $checkoutId;
            $transaction->amount         = $amount;
            $transaction->status         = 'pending';
            $transaction->save();
            return view('payment.visaTransferBalance',compact('user','checkoutId'));
        }
        return redirect('payment');
    }

    public function visaTransferBalanceResult(Request $request){
        $id = $request->resourcePath;
        $user = Auth::user();

            $url = "https://oppwa.com/".$id;
            // $url = "https://test.oppwa.com/".$id;
            $curlopt = false;
        $url .= "?entityId=8acda4ca845bb82a0184a86e7a55058e";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Authorization:Bearer OGFjZGE0Y2E4NDViYjgyYTAxODRhODZkYjVjOTA1ODl8TUQ4d2s1UFhkRw=="));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $curlopt);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $responseData = curl_exec($ch);
        if(curl_errno($ch)) {
            return curl_error($ch);
        }
        curl_close($ch);
        $responseData = json_decode( $responseData, true );
        $code = isset($responseData[ 'result' ][ 'code' ] ) ? $responseData[ 'result' ][ 'code' ]  :'-1';
        if(is_success($code )){
            $checkoutId = $responseData['ndc'];
            if($transaction = Transactions::with('user')->where('transaction_id','=',$checkoutId)->first()){
                $transaction->status = 'success';
                $transaction->save();
            }
            // if(session('package_id')){
            //     if($package = Package::find(session('package_id'))){
            //         $num_days         = $package->num_days;
            //         $package_end_date = date('Y-m-d', strtotime(' + '.$package->num_days.' days'));
            //         $user->update(['package_id' => $package->id,'package_start_date' => date('Y-m-d') , 'package_end_date' => $package_end_date  ]);
            //     }
            // }else{
                // $user->balance += $responseData['amount'];
                // $user->save();
            // }
            if(session('package_id')){
                if($package = Package::find(session('package_id'))){
                    $user->balance += $package->offer_price;
                    $user->save();
                    $package->increment('num_sells');
                }
            }else{
                $user->balance += $responseData['amount'];
                $user->save(); 
            }

            // savePayment($user->id,0,$responseData['amount'],'add','balance_transfer','finished',$user->current_country_id);
            return redirect()->route('paymentSuccess')->with('successmsg','تم اضافة التحويل بنجاح , رصيدك الحالى ( '.$user->balance.' '.setting('site_currency_ar').' ).');
        // return redirect('payment/'.$user->id)->with('successmsg','تم اضافة التحويل بنجاح , رصيدك الحالى ( '.$user->balance.' '.setting('site_currency_ar').' ).');
        }else{
            return redirect('payment/'.$user->id)->with('msg','حدث خطأ ما ');
        }
    }

    function madaTransferBalance($amount) {
        if(Auth::check()){
            $user = Auth::user();
                $url = "https://oppwa.com/v1/checkouts";
                // $url = "https://test.oppwa.com/v1/checkouts";
                $curlopt = false;
            $user_email = $user->email?$user->email:$user->phone.'@letsgo.com';
            $country_iso = ($user->country)? $user->country->iso2 : 'SA';
            $user_address = ($user->address)? $user->address : $country_iso;
            $data = "entityId=8acda4ca845bb82a0184a86f24c00593".
                "&amount=".$amount.
                "&currency=SAR" .
                "&merchantTransactionId=".rand(1111,9999).$user->id.
                "&customer.email=".$user_email.
                "&paymentType=DB".
                // "&testMode=INTERNAL".
                "&billing.country=".$country_iso.
                "&customer.givenName=".$user->name.
                "&customer.surname=".$user->name.
                "&billing.street1=".$user_address.
                "&billing.city=".$user_address.
                "&billing.state=".substr($user_address, 0, 49).
                "&billing.postcode=".substr($user_address, 0, 29);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                "Authorization:Bearer OGFjZGE0Y2E4NDViYjgyYTAxODRhODZkYjVjOTA1ODl8TUQ4d2s1UFhkRw=="));
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $curlopt);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $responseData = curl_exec($ch);
            if(curl_errno($ch)) {
                return curl_error($ch);
            }
            curl_close($ch);
            $responseData = json_decode($responseData);
            $checkoutId = $responseData->id;
            #save the transaction
            $transaction   = new Transactions();
            $transaction->user_id        = $user->id;
            $transaction->transaction_id = $checkoutId;
            $transaction->amount         = $amount;
            $transaction->status         = 'pending';
            $transaction->save();
            return view('payment.madaTransferBalance',compact('user','checkoutId'));
        }
        return redirect('payment');
    }

    public function madaTransferBalanceResult(Request $request){
        $id = $request->resourcePath;
        $user = Auth::user();

            $url = "https://oppwa.com/".$id;
            // $url = "https://test.oppwa.com/".$id;
            $curlopt = false;
        $url .= "?entityId=8acda4ca845bb82a0184a86f24c00593";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Authorization:Bearer OGFjZGE0Y2E4NDViYjgyYTAxODRhODZkYjVjOTA1ODl8TUQ4d2s1UFhkRw=="));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $curlopt);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $responseData = curl_exec($ch);
        if(curl_errno($ch)) {
            return curl_error($ch);
        }
        curl_close($ch);

        $responseData = json_decode( $responseData, true );
        $code = isset($responseData[ 'result' ][ 'code' ] ) ? $responseData[ 'result' ][ 'code' ]  :'-1';
        if(is_success($code )){
            $checkoutId = $responseData['ndc'];
            if($transaction = Transactions::with('user')->where('transaction_id','=',$checkoutId)->first()){
                $transaction->status = 'success';
                $transaction->save();
            }
            // if(session('package_id')){
            //     if($package = Package::find(session('package_id'))){
            //         $num_days         = $package->num_days;
            //         $package_end_date = date('Y-m-d', strtotime(' + '.$package->num_days.' days'));
            //         $user->update(['package_id' => $package->id,'package_start_date' => date('Y-m-d') , 'package_end_date' => $package_end_date  ]);
            //     }
            // }else{
                // $user->balance += $responseData['amount'];
                // $user->save();
            // }

            if(session('package_id')){
                if($package = Package::find(session('package_id'))){
                    $user->balance += $package->offer_price;
                    $user->save();
                    $package->increment('num_sells');
                }
            }else{
                $user->balance += $responseData['amount'];
                $user->save(); 
            }

            // savePayment($user->id,0,$responseData['amount'],'add','balance_transfer','finished',$user->current_country_id);
            return redirect()->route('paymentSuccess')->with('successmsg','تم اضافة التحويل بنجاح , رصيدك الحالى ( '.$user->balance.' '.setting('site_currency_ar').' ).');
        // return redirect('payment/'.$user->id)->with('successmsg','تم اضافة التحويل بنجاح , رصيدك الحالى ( '.$user->balance.' '.setting('site_currency_ar').' ).');
        }else{
            return redirect('payment/'.$user->id)->with('msg','حدث خطأ ما ');
        }
    }

    function appleTransferBalance($amount) {
        if(Auth::check()){
            $user = Auth::user();
            
            $url = "https://oppwa.com/v1/checkouts";
            // $url = "https://test.oppwa.com/v1/checkouts";
            $curlopt = false;
            $user_email = $user->email?$user->email:$user->phone.'@letsgo.com';
            $country_iso = ($user->country)? $user->country->iso2 : 'SA';
            $user_address = ($user->address)? $user->address : $country_iso;
            $data = "entityId=8acda4cc854f7b7f01855cac72a97503".
                "&amount=".$amount.
                "&currency=SAR" .
                "&merchantTransactionId=".rand(1111,9999).$user->id.
                "&customer.email=".$user_email.
                "&paymentType=DB".
                // "&testMode=EXTERNAL".
                "&billing.country=".$country_iso.
                "&customer.givenName=".$user->name.
                "&customer.surname=".$user->name.
                "&billing.street1=".$user_address.
                "&billing.city=".$user_address.
                "&billing.state=".substr($user_address, 0, 49).
                "&billing.postcode=".substr($user_address, 0, 29);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                "Authorization:Bearer OGFjZGE0Y2E4NDViYjgyYTAxODRhODZkYjVjOTA1ODl8TUQ4d2s1UFhkRw=="));
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $curlopt);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $responseData = curl_exec($ch);
            if(curl_errno($ch)) {
                return curl_error($ch);
            }
            curl_close($ch);
            $responseData = json_decode($responseData);
            $checkoutId = $responseData->id;


            #save the transaction
            $transaction   = new Transactions();
            $transaction->user_id        = $user->id;
            $transaction->transaction_id = $checkoutId;
            $transaction->amount         = $amount;
            $transaction->status         = 'pending';
            $transaction->save();
            return view('payment.appleTransferBalance',compact('user','checkoutId'));
        }
        return redirect('payment');
    }

    public function appleTransferBalanceResult(Request $request){
        $id = $request->resourcePath;
        $user = Auth::user();
            $url = "https://oppwa.com/".$id;
            // $url = "https://test.oppwa.com/".$id;
            $curlopt = false;
        $url .= "?entityId=8acda4cc854f7b7f01855cac72a97503";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Authorization:Bearer OGFjZGE0Y2E4NDViYjgyYTAxODRhODZkYjVjOTA1ODl8TUQ4d2s1UFhkRw=="));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $curlopt);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $responseData = curl_exec($ch);
        if(curl_errno($ch)) {
            return curl_error($ch);
        }
        curl_close($ch);
        $responseData = json_decode( $responseData, true );
        $code = isset($responseData[ 'result' ][ 'code' ] ) ? $responseData[ 'result' ][ 'code' ]  :'-1';

        if(is_success($code )){
            $checkoutId = $responseData['ndc'];
            if($transaction = Transactions::with('user')->where('transaction_id','=',$checkoutId)->first()){
                $transaction->status = 'success';
                $transaction->save();
            }
            // if(session('package_id')){
            //     if($package = Package::find(session('package_id'))){
            //         $num_days         = $package->num_days;
            //         $package_end_date = date('Y-m-d', strtotime(' + '.$package->num_days.' days'));
            //         $user->update(['package_id' => $package->id,'package_start_date' => date('Y-m-d') , 'package_end_date' => $package_end_date  ]);
            //     }
            // }else{
                // $user->balance += $responseData['amount'];
                // $user->save();
            // }
            if(session('package_id')){
                if($package = Package::find(session('package_id'))){
                    $user->balance += $package->offer_price;
                    $user->save();
                    $package->increment('num_sells');
                }
            }else{
                $user->balance += $responseData['amount'];
                $user->save(); 
            }

            // savePayment($user->id,0,$responseData['amount'],'add','balance_transfer','finished',$user->current_country_id);
            return redirect()->route('paymentSuccess')->with('successmsg','تم اضافة التحويل بنجاح , رصيدك الحالى ( '.$user->balance.' '.setting('site_currency_ar').' ).');
        // return redirect('payment/'.$user->id)->with('successmsg','تم اضافة التحويل بنجاح , رصيدك الحالى ( '.$user->balance.' '.setting('site_currency_ar').' ).');
        }else{
            return redirect('payment/'.$user->id)->with('msg','حدث خطأ ما ');
        }
    }

    function stcTransferBalance($amount) {
        if(Auth::check()){
            $user = Auth::user();
                $url = "https://oppwa.com/v1/checkouts";
                // $url = "https://test.oppwa.com/v1/checkouts";
                $curlopt = false;
            $user_email = $user->email?$user->email:$user->phone.'@letsgo.com';
            $country_iso = ($user->country)? $user->country->iso2 : 'SA';
            $user_address = ($user->address)? $user->address : $country_iso;
            $data = "entityId=8acda4ca845bb82a0184a86e7a55058e".
                "&amount=".$amount.
                "&currency=SAR" .
                "&merchantTransactionId=".rand(1111,9999).$user->id.
                "&customer.email=".$user_email.
                "&paymentType=DB".
                // "&testMode=EXTERNAL".
                "&billing.country=".$country_iso.
                "&customer.givenName=".$user->name.
                "&customer.surname=".$user->name.
                "&billing.street1=".$user_address.
                "&billing.city=".$user_address.
                "&billing.state=".substr($user_address, 0, 49).
                "&billing.postcode=".substr($user_address, 0, 29); 

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                "Authorization:Bearer OGFjZGE0Y2E4NDViYjgyYTAxODRhODZkYjVjOTA1ODl8TUQ4d2s1UFhkRw=="));
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $curlopt);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $responseData = curl_exec($ch);
            if(curl_errno($ch)) {
                return curl_error($ch);
            }
            curl_close($ch);
            $responseData = json_decode($responseData);
            $checkoutId = $responseData->id;
            #save the transaction
            $transaction   = new Transactions();
            $transaction->user_id        = $user->id;
            $transaction->transaction_id = $checkoutId;
            $transaction->amount         = $amount;
            $transaction->status         = 'pending';
            $transaction->save();
            return view('payment.stcTransferBalance',compact('user','checkoutId'));
        }
        return redirect('payment');
    }

    public function stcTransferBalanceResult(Request $request){
        $id = $request->resourcePath;
        $user = Auth::user();
            $url = "https://oppwa.com/".$id;
            // $url = "https://test.oppwa.com/".$id;
            $curlopt = false;
        $url .= "?entityId=8acda4ca845bb82a0184a86e7a55058e";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Authorization:Bearer OGFjZGE0Y2E4NDViYjgyYTAxODRhODZkYjVjOTA1ODl8TUQ4d2s1UFhkRw=="));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $curlopt);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $responseData = curl_exec($ch);
        if(curl_errno($ch)) {
            return curl_error($ch);
        }
        curl_close($ch);

        $responseData = json_decode( $responseData, true );
        $code = isset($responseData[ 'result' ][ 'code' ] ) ? $responseData[ 'result' ][ 'code' ]  :'-1';

        if(is_success($code )){
            $checkoutId = $responseData['ndc'];
            if($transaction = Transactions::with('user')->where('transaction_id','=',$checkoutId)->first()){
                $transaction->status = 'success';
                $transaction->save();
            }
            // if(session('package_id')){
            //     if($package = Package::find(session('package_id'))){
            //         $num_days         = $package->num_days;
            //         $package_end_date = date('Y-m-d', strtotime(' + '.$package->num_days.' days'));
            //         $user->update(['package_id' => $package->id,'package_start_date' => date('Y-m-d') , 'package_end_date' => $package_end_date  ]);
            //     }
            // }else{
                // $user->balance += $responseData['amount'];
                // $user->save();
            // }
            if(session('package_id')){
                if($package = Package::find(session('package_id'))){
                    $user->balance += $package->offer_price;
                    $user->save();
                    $package->increment('num_sells');
                }
            }else{
                $user->balance += $responseData['amount'];
                $user->save(); 
            }

            // savePayment($user->id,0,$responseData['amount'],'add','balance_transfer','finished',$user->current_country_id);
            return redirect()->route('paymentSuccess')->with('successmsg','تم اضافة التحويل بنجاح , رصيدك الحالى ( '.$user->balance.' '.setting('site_currency_ar').' ).');
        // return redirect('payment/'.$user->id)->with('successmsg','تم اضافة التحويل بنجاح , رصيدك الحالى ( '.$user->balance.' '.setting('site_currency_ar').' ).');
        }else{
            return redirect('payment/'.$user->id)->with('msg','حدث خطأ ما ');
        }
    }

    public function paymentSuccess(){
        return view('payment.hyperSuccess');
    }


//************ Admin payments reports ***********//
    public function adminPaymentsReport(Request $request){
       $payments = Payments::with('user','seconduser')->orderBy('created_at','DESC')->paginate($this->limit);
       return view('dashboard.payments.payments',compact('payments'));
    }

    public function downloadPaymentsReport(){
        return Excel::download( new PaymentsExport('all'), 'PaymentsExport.xlsx');        
    }
}
