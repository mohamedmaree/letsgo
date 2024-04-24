<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Payments;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Jenssegers\Date\Date;
use Validator;
use App\Country;
use App\Transactions;
use App\Order;
use App\Package;
use App\StcPhones;

class PaymentsController extends Controller{

    public function userPayments(Request $request){
            if($user = JWTAuth::parseToken()->authenticate()){
               $lang = $request->header('lang');
               $data = []; $seconduser = ''; $operation = ''; $type='';
               $wallet_type = $request->wallet_type??'balance';
                if($payments = Payments::with('user','seconduser')->where('wallet_type','=',$wallet_type)->where('user_id','=',$user->id)->where('status','=','finished')->orwhere('second_user_id','=',$user->id)->where('wallet_type','=',$request->wallet_type)->where('status','=','finished')->orderBy('created_at','DESC')->get()){
                    foreach($payments as $payment){
                            $operation = trans('user.'.$payment->operation);
                          if($payment->user_id == $user->id){
                             $seconduser       = ($payment->second_user_id == 0)? setting('site_title') : (($payment->seconduser)? $payment->seconduser->name : trans('user.user_notfound') );
                             $type             = ($payment->type == 'add')? 'add':'subtract';
                             if($operation == 'balance_transfer'){
                                if($type == 'add'){
                                  $operation = trans('user.balance_receive');
                                }else{
                                  $operation = trans('user.balance_transfer'); 
                                }
                             }
                             // $operation = ( $operation == 'balance_transfer') && ($type == 'add')?  trans('user.balance_receive'):trans('user.balance_transfer'); 
                          }else{
                             $seconduser       = ($payment->user_id == 0)? setting('site_title') : (($payment->user)? $payment->user->name: trans('user.user_notfound') );
                             $type             = ($payment->type == 'add')? 'subtract':'add';
                             if($operation == 'balance_transfer'){
                                if($type == 'add'){
                                  $operation = trans('user.balance_transfer');
                                }else{
                                  $operation = trans('user.balance_receive'); 
                                }
                             }
                             // $operation = ( $operation == 'balance_transfer') && ($type == 'add')? trans('user.balance_transfer') :  trans('user.balance_receive'); 
                          }                         
                          $currency = setting('site_currency_'.$lang);
                          if($country = Country::find($payment->country_id)){
                             $currency = $country->{"currency_$lang"};
                          }
                            $data[] = ['id'         => $payment->id,
                                       'seconduser' => $seconduser,
                                       'amount'     => $payment->amount.' '.$currency,
                                       'type'       => $type,
                                       'operation'  => $operation,
                                       'wallet_type' => $payment->wallet_type,
                                       'date'       => Date::parse($payment->created_at)->format('Y-m-d')
                                      ];
                    }
                }
            return response()->json(successReturn($data));
            }
          $msg = trans('user.user_notfound');    
          return response()->json(failReturn($msg));
    }


    // public function payment_status(Request $request){
    //     #parameters from payment getaway
    //     $amount                     =  convert2english( ($_GET['amount'])??'' );
    //     $trackid                    =  ($_GET['TrackId'])??'';
    //     $id                         =  ($_GET['TranId'])??'';

    //     if(isset($_GET['UserField1'])){
    //         if($_GET['UserField1'] == 'trip'){
    //             if($order = Order::with('user')->find($trackid)){
    //               if($client = $order->user){
    //                 $client->balance += round(floatval($amount),2);
    //                 $client->save();
    //               }

    //               $transaction   = new Transactions();
    //               $transaction->user_id        = $order->user_id;
    //               $transaction->transaction_id = $id;
    //               $transaction->amount         = $amount;
    //               $transaction->status         = 'success';
    //               $transaction->card_brand     = ($_GET['cardBrand'])??'';
    //               $transaction->save();
                  
    //               $msg = 'تمت العملة بنجاح.';
    //               return response()->json(successReturnMsg($msg));
    //             }
    //           $msg = 'فشل في العملية، هذه الرحله غير متاحة.';
    //           return response()->json(failReturn($msg));
    //         }
    //       $msg = 'فشل في العملية، UserField1 غير صحيح.';
    //       return response()->json(failReturn($msg));
    //     }
    //     $msg = 'فشل في العملية، UserField1 مطلوب.';
    //     return response()->json(failReturn($msg));
    // }


    public function hyperIndex(Request $request){
        $validator = Validator::make($request->all(),[
            'type'      => 'required|in:VISA,MADA,APPLEPAY,STC_PAY,AMEX',
            'amount'     => 'required',
        ]);
        if($validator->passes()){
            $authorization = 'OGFjZGE0Y2E4NDViYjgyYTAxODRhODZkYjVjOTA1ODl8TUQ4d2s1UFhkRw==';
            $user  = JWTAuth::parseToken()->authenticate();
            if (!$user) {
                $msg = 'المستخدم غير صحيح';
                return response()->json(failReturn($msg));
            }
            if ($request->type == 'VISA'){
                $entity_id = '8acda4ca845bb82a0184a86e7a55058e';
            }elseif ($request->type == 'MADA'){
                $entity_id = '8acda4ca845bb82a0184a86f24c00593';
            }elseif ($request->type == 'APPLEPAY'){
                $entity_id = '8acda4cc854f7b7f01855cac72a97503';
            }elseif ($request->type == 'STC_PAY'){
                $entity_id = '8acda4ca845bb82a0184a86e7a55058e';
            }
            $amount = number_format((float)$request->amount, 2, '.', '');
            $url = "https://oppwa.com/v1/checkouts";
//            $url = "https://test.oppwa.com/v1/checkouts";
//            $curlopt = true;
            $curlopt = false;

            $user_email = $user->email??$user->phone.'@letsgo-app.com';
            $country_iso = $user->country? $user->country->iso2 : 'SA';
            $user_address = $user->address?? $country_iso;

            $data = "entityId=" . $entity_id .
                "&amount=".$amount.
                "&currency=SAR" .
                "&merchantTransactionId=".rand(1111,9999).$user->id.
                "&customer.email=".$user_email.
                "&paymentType=DB".
                // "&testMode=EXTERNAL".
                "&billing.country=".$country_iso.
                "&customer.givenName=".$user->name.
                "&customer.surname=".$user->name.
                "&billing.street1=".substr($user_address, 0, 49).
                "&billing.city=".substr($user_address, 0, 49).
                "&billing.state=".substr($user_address, 0, 49).
                "&billing.postcode=".substr($user_address, 0, 29);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                "Authorization:Bearer " . $authorization));
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
            $transaction->card_brand     = $request->type;
            $transaction->status         = 'pending';
            $transaction->save();

            return response()->json(successReturn($checkoutId));
        }else{
            $msg   = implode(' , ',$validator->errors()->all());
            return response()->json(failReturn($msg));
        }
    }

    public function hyperResult(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'type'      => 'required|in:VISA,MADA,APPLEPAY,STC_PAY,AMEX',
            'resourcePath' => 'required',
            'order_id'           => 'nullable',
            'package_id'         => 'nullable',
        ]);
        if($validator->passes()){

            $authorization = 'OGFjZGE0Y2E4NDViYjgyYTAxODRhODZkYjVjOTA1ODl8TUQ4d2s1UFhkRw==';
            $user  = JWTAuth::parseToken()->authenticate();
            $id = $request->resourcePath;

            if (!$user) {
                $msg = 'المستخدم غير صحيح';
                return response()->json(failReturn($msg));
            }

            if ($request->type == 'VISA'){
                $entity_id = '8acda4ca845bb82a0184a86e7a55058e';
            }elseif ($request->type == 'MADA'){
                $entity_id = '8acda4ca845bb82a0184a86f24c00593';
            }elseif ($request->type == 'APPLEPAY'){
                $entity_id = '8acda4cc854f7b7f01855cac72a97503';
            }elseif ($request->type == 'STC_PAY'){
                $entity_id = '8acda4ca845bb82a0184a86e7a55058e';
            }

            $url = "https://oppwa.com/".$id;
            // $url = "https://test.oppwa.com/".$id;
            $curlopt = false;
//            $curlopt = true;
            $url .= "?entityId=" . $entity_id;

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                "Authorization:Bearer " . $authorization));
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
                    $transaction->order_id   = $request->order_id ?? null;
                    $transaction->package_id = $request->package_id ?? null;
                    $transaction->save();
                }
            
                    if($request->order_id){
                        if($order = Order::find($request->order_id)){
                          $order->confirm_payment = 'true';
                          $order->save();
                          $user->balance = floatval($user->balance)  + floatval( $responseData['amount'] );
                          $user->save();
                          $msg = 'تم الدفع بنجاح';
                          savePayment($user->id,0,$responseData['amount']??0,'subtract','order_price','finished',$user->current_country_id,'balance');
                        }
                    }
                    if($request->package_id){
                        if($package = Package::find($request->package_id)){
                            $user->balance = (float)$user->balance  + (float) $package->offer_price;
                            $user->save();
                            $package->increment('num_sells');
                            $msg = 'تم الدفع بنجاح';
                            savePayment($user->id,0, $package->offer_price??0,'add','balance_charge','finished',$user->current_country_id,'balance');
                        }
                    }else{
                        $user->balance = floatval($user->balance)  + floatval( $responseData['amount'] );
                        $user->save();
                        $msg = 'تم اضافة التحويل بنجاح , رصيدك الحالى ( '.$user->balance.' '.setting('site_currency_ar').' ).';
                        savePayment($user->id,0,$responseData['amount']??0,'add','balance_charge','finished',$user->current_country_id,'balance');
                    }

                return response()->json(successReturnMsg($msg));
                // return redirect('payment/'.$user->id)->with('successmsg','تم اضافة التحويل بنجاح , رصيدك الحالى ( '.$user->balance.' '.setting('site_currency_ar').' ).');
            }else{
                $msg = 'حدث خطأ ما ';
                return response()->json(failReturn($msg));
            }

        }else{
            $msg   = implode(' , ',$validator->errors()->all());
            return response()->json(failReturn($msg));
        }
    }


    public function captainHyperIndex(Request $request){
        $validator = Validator::make($request->all(),[
            'type'      => 'required|in:VISA,MADA,APPLEPAY,STC_PAY,AMEX',
            'amount'     => 'required',
        ]);

        if($validator->passes()){
            $authorization = 'OGFjOWE0Y2Q4ZTEzMWRkMTAxOGU1YWZhM2I0MjU2NWR8NnE5bnFKTXl5NGhHRW5KNA==';
            $user  = JWTAuth::parseToken()->authenticate();
            if (!$user) {
                $msg = 'المستخدم غير صحيح';
                return response()->json(failReturn($msg));
            }
            if ($request->type == 'VISA'){
                $entity_id = '8acda4cd8ec7c33b018ee1bb2aae6725';
            }elseif ($request->type == 'MADA'){
                $entity_id = '8ac9a4cd8e131dd1018e5afd74225688';
            }elseif ($request->type == 'APPLEPAY'){
                $entity_id = '8ac9a4cd8e131dd1018e5afb01755668';
            }elseif ($request->type == 'STC_PAY'){
                $entity_id = '8acda4cd8ec7c33b018ee1bb2aae6725';
            }
            $amount = number_format((float)$request->amount, 2, '.', '');
            $url = "https://oppwa.com/v1/checkouts";
//            $url = "https://test.oppwa.com/v1/checkouts";
//            $curlopt = true;
            $curlopt = false;

            $user_email = $user->email??$user->phone.'@letsgo-app.com';
            $country_iso = $user->country? $user->country->iso2 : 'SA';
            $user_address = $user->address?? $country_iso;

            $data = "entityId=" . $entity_id .
                "&amount=".$amount.
                "&currency=SAR" .
                "&merchantTransactionId=".rand(1111,9999).$user->id.
                "&customer.email=".$user_email.
                "&paymentType=DB".
                // "&testMode=EXTERNAL".
                "&billing.country=".$country_iso.
                "&customer.givenName=".$user->name.
                "&customer.surname=".$user->name.
                "&billing.street1=".substr($user_address, 0, 49).
                "&billing.city=".substr($user_address, 0, 49).
                "&billing.state=".substr($user_address, 0, 49).
                "&billing.postcode=".substr($user_address, 0, 29);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                "Authorization:Bearer " . $authorization));
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
            $transaction->card_brand     = $request->type;
            $transaction->status         = 'pending';
            $transaction->save();

            return response()->json(successReturn($checkoutId));
        }else{
            $msg   = implode(' , ',$validator->errors()->all());
            return response()->json(failReturn($msg));
        }
    }

    public function captainHyperResult(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'type'      => 'required|in:VISA,MADA,APPLEPAY,STC_PAY,AMEX',
            'resourcePath' => 'required',
            'order_id'           => 'nullable',
            'package_id'         => 'nullable',
        ]);
        if($validator->passes()){

            $authorization = 'OGFjOWE0Y2Q4ZTEzMWRkMTAxOGU1YWZhM2I0MjU2NWR8NnE5bnFKTXl5NGhHRW5KNA==';
            $user  = JWTAuth::parseToken()->authenticate();
            $id = $request->resourcePath;

            if (!$user) {
                $msg = 'المستخدم غير صحيح';
                return response()->json(failReturn($msg));
            }

            if ($request->type == 'VISA'){
                $entity_id = '8acda4cd8ec7c33b018ee1bb2aae6725';
            }elseif ($request->type == 'MADA'){
                $entity_id = '8ac9a4cd8e131dd1018e5afd74225688';
            }elseif ($request->type == 'APPLEPAY'){
                $entity_id = '8ac9a4cd8e131dd1018e5afb01755668';
            }elseif ($request->type == 'STC_PAY'){
                $entity_id = '8acda4cd8ec7c33b018ee1bb2aae6725';
            }

            $url = "https://oppwa.com/".$id;
            // $url = "https://test.oppwa.com/".$id;
            $curlopt = false;
//            $curlopt = true;
            $url .= "?entityId=" . $entity_id;

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                "Authorization:Bearer " . $authorization));
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
                    $transaction->order_id   = $request->order_id ?? null;
                    $transaction->package_id = $request->package_id ?? null;
                    $transaction->save();
                }
            
                    if($request->order_id){
                        if($order = Order::find($request->order_id)){
                          $order->confirm_payment = 'true';
                          $order->save();
                          $user->balance = floatval($user->balance)  + floatval( $responseData['amount'] );
                          $user->save();
                          $msg = 'تم الدفع بنجاح';
                          savePayment($user->id,0,$responseData['amount']??0,'subtract','order_price','finished',$user->current_country_id,'balance');
                        }
                    }
                    if($request->package_id){
                        if($package = Package::find($request->package_id)){
                            $user->balance = (float)$user->balance  + (float) $package->offer_price;
                            $user->save();
                            $package->increment('num_sells');
                            $msg = 'تم الدفع بنجاح';
                            savePayment($user->id,0, $package->offer_price??0,'add','balance_charge','finished',$user->current_country_id,'balance');
                        }
                    }else{
                        $user->balance = floatval($user->balance)  + floatval( $responseData['amount'] );
                        $user->save();
                        $msg = 'تم اضافة التحويل بنجاح , رصيدك الحالى ( '.$user->balance.' '.setting('site_currency_ar').' ).';
                        savePayment($user->id,0,$responseData['amount']??0,'add','balance_charge','finished',$user->current_country_id,'balance');
                    }

                return response()->json(successReturnMsg($msg));
                // return redirect('payment/'.$user->id)->with('successmsg','تم اضافة التحويل بنجاح , رصيدك الحالى ( '.$user->balance.' '.setting('site_currency_ar').' ).');
            }else{
                $msg = 'حدث خطأ ما ';
                return response()->json(failReturn($msg));
            }

        }else{
            $msg   = implode(' , ',$validator->errors()->all());
            return response()->json(failReturn($msg));
        }
    }

    public function paymentWays(Request $request){
      $ways = [ 
                // ['title' => 'apple pay','type'=>'APPLEPAY','image'=> url('img/apple.png')],
                // ['title' => 'visa','type'=>'VISA','image'=> url('img/visa.png')],
                // ['title' => 'master','type'=>'MASTER','image'=> url('img/master.png')],
                ['title' => 'mada','type'=>'MADA','image'=> url('img/mada.png')],
                // ['title' => 'stc','type'=>'STC_PAY','image'=> url('img/stc.png')],
              ];
        return response()->json(successReturn($ways));
    }
    
    public function stcSendRequest(Request $request){
        $validator = Validator::make($request->all(),[
            'DeviceId'   => 'required',
            'Amount'     => 'required',
            'MobileNo'   => 'required',
            'RefNum'     => 'required' //trip id
        ]);

        if($validator->passes()){
            $user  = JWTAuth::parseToken()->authenticate();
            if (!$user) {
                $msg = 'المستخدم غير صحيح';
                return response()->json(failReturn($msg));
            }

            $post_feilds_arr = ['DirectPaymentAuthorizeV4RequestMessage' =>
                    [
                        'MobileNo' => $request->MobileNo,
                        'Amount'  => $request->Amount,
                        'BranchId' => 'string',
                        'TellerId' => 'string',
                        'DeviceId' => 'string',
                        'RefNum' => 'string',
                        'BillNumber' => 'string',
                        'MerchantNote' => 'string',
                    ]
                ];

            // $url = 'https://sandbox.b2b.stcpay.com.sa/B2B.DirectPayment.WebApi/DirectPayment/V4/DirectPaymentAuthorize';
            $url = 'https://b2b.stcpay.com.sa/B2B.DirectPayment.WebApi/DirectPayment/V4/DirectPaymentAuthorize';
            // Initialize cURL
            $ch = curl_init();
            // Set cURL options
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_feilds_arr));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'X-ClientCode:77266565988',//72001025774 //77266565988
            ]);
            // Load the PFX file
            curl_setopt($ch, CURLOPT_SSLCERT,  '/var/www/html/letsgo/public/.well-known/certificate.pfx');
            curl_setopt($ch, CURLOPT_SSLCERTTYPE, 'P12'); // Specify PFX format
            curl_setopt($ch, CURLOPT_SSLCERTPASSWD, 'Aa900900@@');
            $response = curl_exec($ch);
            curl_close($ch);
            $responseData = json_decode($response);
        //    dd($responseData);
            $checkoutId = ($responseData->DirectPaymentAuthorizeV4ResponseMessage->STCPayPmtReference)??'';
            #save the transaction
            $transaction   = new Transactions();
            $transaction->user_id        = $user->id;
            $transaction->transaction_id = $checkoutId;
            $transaction->amount         = $request->Amount;
            $transaction->order_id       = $request->RefNum ?? null;
            $transaction->card_brand     = 'stc';
            $transaction->status         = 'pending';
            $transaction->save();

            if(!StcPhones::where('user_id','=',$user->id)->where('phone','=',$request->MobileNo)->first()){
                $stcPhone = new StcPhones();
                $stcPhone->user_id       = $user->id;
                $stcPhone->phone         = $request->MobileNo;
                $stcPhone->save();
            }
            return response()->json(successReturn($responseData));
        }else{
            $msg   = implode(' , ',$validator->errors()->all());
            return response()->json(failReturn($msg));
        }
    }

    public function stcResult(Request $request){
        $validator = Validator::make($request->all(),[
            'OtpReference'       => 'required',
            'Otpvalue'           => 'required',
            'STCPayPmtReference' => 'required',
            'order_id'           => 'nullable',
            'package_id'         => 'nullable',
        ]);
        if($validator->passes()){
            $user  = JWTAuth::parseToken()->authenticate();
            if (!$user) {
                $msg = 'المستخدم غير صحيح';
                return response()->json(failReturn($msg));
            }
            $post_feilds_arr = ['DirectPaymentConfirmV4RequestMessage' =>
                    [
                        'OtpReference' => $request->OtpReference,
                        'Otpvalue'  => $request->Otpvalue,
                        'STCPayPmtReference' => $request->STCPayPmtReference,
                        'TokenReference' => 'string',
                        'TokenizenYn' => true
                    ]
                ];

            // $url = 'https://sandbox.b2b.stcpay.com.sa/B2B.DirectPayment.WebApi/DirectPayment/V4/DirectPaymentConfirm';
            $url = 'https://b2b.stcpay.com.sa/B2B.DirectPayment.WebApi/DirectPayment/V4/DirectPaymentConfirm';
            //  $url = 'https://test.b2b.stcpay.com.sa/B2B.DirectPayment.WebApi/DirectPayment/V4/DirectPaymentConfirm';

            // Initialize cURL
            $ch = curl_init();
            // Set cURL options
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_feilds_arr));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'X-ClientCode:77266565988',//72001025774 //77266565988
            ]);
            // Load the PFX file
            curl_setopt($ch, CURLOPT_SSLCERT,  '/var/www/html/letsgo/public/.well-known/certificate.pfx');
            curl_setopt($ch, CURLOPT_SSLCERTTYPE, 'P12'); // Specify PFX format
            curl_setopt($ch, CURLOPT_SSLCERTPASSWD, 'Aa900900@@');
            $response = curl_exec($ch);
            curl_close($ch);
            $responseData = json_decode( $response);
            if(isset($responseData->DirectPaymentConfirmV4ResponseMessage)){
                if($responseData->DirectPaymentConfirmV4ResponseMessage->PaymentStatus == 2 || $responseData->DirectPaymentConfirmV4ResponseMessage->PaymentStatusDesc == 'Paid'){
                    $checkoutId = $request->STCPayPmtReference;
                    if($transaction = Transactions::with('user')->where('transaction_id','=',$checkoutId)->first()){
                        $transaction->status = 'success';
                        $transaction->order_id = $request->order_id ?? null;
                        $transaction->package_id = $request->package_id ?? null;
                        $transaction->save();
                    }

                    if($request->order_id){
                        if($order = Order::find($request->order_id)){
                          $order->confirm_payment = 'true';
                          $order->save();
                          $user->balance = floatval($user->balance)  + floatval( $responseData->DirectPaymentConfirmV4ResponseMessage->Amount );
                          $user->save();
                          $msg = 'تم الدفع بنجاح';
                        
                          savePayment($user->id,0,$transaction->amount??0,'subtract','order_price','finished',$user->current_country_id,'balance');
                        }
                    }
                    if($request->package_id){
                        if($package = Package::find($request->package_id)){
                            $user->balance = (float)$user->balance  + (float) $package->offer_price;
                            $user->save();
                            $package->increment('num_sells');
                            $msg = 'تم الدفع بنجاح';
                            savePayment($user->id,0, $package->offer_price??0,'add','balance_charge','finished',$user->current_country_id);
                        }
                    }else{
                        $user->balance = floatval($user->balance)  + floatval( $responseData->DirectPaymentConfirmV4ResponseMessage->Amount );
                        $user->save();
                        $msg = 'تم اضافة التحويل بنجاح , رصيدك الحالى ( '.$user->balance.' '.setting('site_currency_ar').' ).';
                        savePayment($user->id,0,$transaction->amount??0,'add','balance_charge','finished',$user->current_country_id);
                    }
                      return response()->json(successReturnMsg($msg));
                    // return redirect('payment/'.$user->id)->with('successmsg','تم اضافة التحويل بنجاح , رصيدك الحالى ( '.$user->balance.' '.setting('site_currency_ar').' ).');
                }else{
                    $msg = $responseData->Text;
                    return response()->json(failReturn($msg));
                }
            }else{
                    $stcError = isset($responseData->Text)? $responseData->Text : '';
                    $msg = ' عذراً لقد فشلت عملية الدفع ' .$stcError ;
                    return response()->json(failReturn($msg));
            }
        }else{
            $msg   = implode(' , ',$validator->errors()->all());
            return response()->json(failReturn($msg));
        }
    }

    public function stcPayout(Request $request){
        $validator = Validator::make($request->all(),[
            'Amount'       => 'required',
            'MobileNumber' => 'required',
        ]);

        if($validator->passes()){
            $user  = JWTAuth::parseToken()->authenticate();
            if (!$user) {
                $msg = 'المستخدم غير صحيح';
                return response()->json(failReturn($msg));
            }

            if ((float)$request->Amount > (float)$user->balance_electronic_payment) {
                $msg = 'عذراً المبلغ  المطلوب اكبر من الرصيد المتاح لك.';
                return response()->json(failReturn($msg));
            }
            $stc_percentage = round((float) $request->Amount * ( setting('stc_percentage') / 100) ,2);
            $final_amount   = round((float) $request->Amount - $stc_percentage ,2);

            // $url = 'https://sandbox.b2b.stcpay.com.sa/B2B.MerchantTransactionsWebApi/MerchantTransactions/v3/PaymentOrderPay';
            $url = 'https://b2b.stcpay.com.sa/B2B.MerchantTransactions.WebApi/MerchantTransactions/v3/PaymentOrderPay';
            // Initialize cURL
            $ch = curl_init();
            // Set cURL options
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, '{
                "PaymentOrderRequestMessage": {
                    "CustomerReference" : "'.$user->id.'_'.time().'",
                    "Description":"string",
                    "ValueDate" : "string",
                    "Payments": 
                    [
                        {
                            "MobileNumber": "'.$request->MobileNumber.'",
                            "ItemReference": "'.$user->id.'_'.time().'",
                            "Amount":'.$final_amount.',
                        }
                    ]
                }
            }');
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'X-ClientCode:77266565988',//72001025774 //77266565988
            ]);
            // Load the PFX file
            curl_setopt($ch, CURLOPT_SSLCERT,  '/var/www/html/letsgo/public/.well-known/certificate.pfx');
            curl_setopt($ch, CURLOPT_SSLCERTTYPE, 'P12'); // Specify PFX format
            curl_setopt($ch, CURLOPT_SSLCERTPASSWD, 'Aa900900@@');
            $response = curl_exec($ch);
            curl_close($ch);

            $responseData = json_decode($response);
        //    dd($responseData);
            $checkoutId = ($responseData->PaymentOrderResponseMessage->PaymentOrderReference)??'';
            #save the transaction
            if($checkoutId){

                $transaction   = new Transactions();
                $transaction->user_id        = $user->id;
                $transaction->transaction_id = $checkoutId;
                $transaction->amount         = $request->Amount;
                $transaction->card_brand     = 'stc';
                $transaction->status         = 'success';
                $transaction->save();
                
                $user->balance_electronic_payment = (float)$user->balance_electronic_payment  - (float) $request->Amount;
                $user->save();
                savePayment($user->id,0,(float) $final_amount,'subtract','stc_balance_receive','finished',$user->current_country_id,'electronic_balance');
                savePayment($user->id,0,$stc_percentage,'subtract','transaction_percentage','finished',$user->current_country_id,'electronic_balance');

                if(!StcPhones::where('user_id','=',$user->id)->where('phone','=',$request->MobileNo)->first()){
                    $stcPhone = new StcPhones();
                    $stcPhone->user_id       = $user->id;
                    $stcPhone->phone         = $request->MobileNo;
                    $stcPhone->save();
                }
                return response()->json(successReturn($responseData));
            }else{
                $stcError = isset($responseData->Text)? $responseData->Text : '';
                $msg = ' عذراً لقد فشلت عملية التحويل '.$stcError;
                return response()->json(failReturn($msg));
            }
        }else{
            $msg   = implode(' , ',$validator->errors()->all());
            return response()->json(failReturn($msg));
        }
    }

    public function userStcPhones(Request $request){
        if($user = JWTAuth::parseToken()->authenticate()){
           $lang = $request->header('lang')??'ar';
           $data = [];
            if($stcPhones = StcPhones::where('user_id','=',$user->id)->get()){
                foreach($stcPhones as $stcPhone){
                        $data[] = ['id'         => $stcPhone->id,
                                   'user_id'    => $stcPhone->user_id,
                                   'phone'      => $stcPhone->phone,
                                   'date'       => Date::parse($stcPhone->created_at)->format('Y-m-d')
                                  ];
                }
            }
        return response()->json(successReturn($data));
        }
        $msg = trans('user.user_notfound');    
        return response()->json(failReturn($msg));
    }



}
