<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\Payments;
class PaymentsExport implements FromCollection{

	    public function __construct($user_id='all',$type = 'balance'){
	    	$this->user_id = $user_id;
	    	$this->type = $type;
	    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection(){ 
        if($this->user_id == 'all'){
              $payments = Payments::with('user','seconduser')->where('wallet_type','=',$this->type)->orderBy('created_at','DESC')->get();
              $data[]   = ['الطرف الاول ','العملية','الطرف الثاني' ,'النوع' ,'المبلغ','الوقت'];
              foreach($payments as $payment){
                        $seconduser     = ($payment->second_user_id == 0)? setting('site_title') : (($payment->seconduser)? $payment->seconduser->name:'المستخدم غير موجود');
                        $secondusertype = ($payment->second_user_id == 0)? 'التطبيق' : (($payment->seconduser)? (($payment->seconduser->captain=='true')?'كابتن':'عميل'):'');
                        $firstuser      = ($payment->user_id == 0)? setting('site_title') : (($payment->user)? $payment->user->name:'المستخدم غير موجود');
                        $firstusertype  = ($payment->user_id == 0)? 'التطبيق' : (($payment->user)? (($payment->user->captain=='true')?'كابتن':'عميل'):'');             
                        $currency       = ($payment->country)?$payment->country->currency_ar:setting('site_currency_ar');
                           $operation = ($payment->operation)??'';
                           $operation = trans('user.'.$payment->operation) ;
             
                $data[] = ['الطرف الاول'    => $firstuser.'('.$firstusertype.')',
                           'العملية'        => $operation,
                           'الطرف الثاني'  => $seconduser.'('.$secondusertype.')',
                           'النوع'         => ($payment->type == 'add')?'أضافة':'خصم',
                           'المبلغ'        => $payment->amount.' '.$currency,
                           'الوقت'         => date('Y-m-d H:i',strtotime($payment->created_at))
                          ];
              }
        }else{
              $payments = Payments::with('user','seconduser')->where('user_id','=',$this->user_id)->where('wallet_type','=',$this->type)->orwhere('second_user_id','=',$this->user_id)->where('wallet_type','=',$this->type)->orderBy('created_at','DESC')->get();
              $data[]   = ['الطرف الاول ','العملية','الطرف الثاني' ,'النوع' ,'المبلغ','الوقت'];
              foreach($payments as $payment){
                        $seconduser     = ($payment->second_user_id == 0)? setting('site_title') : (($payment->seconduser)? $payment->seconduser->name:'المستخدم غير موجود');
                        $secondusertype = ($payment->second_user_id == 0)? 'التطبيق' : (($payment->seconduser)? (($payment->seconduser->captain=='true')?'كابتن':'عميل'):'');
                        $firstuser      = ($payment->user_id == 0)? setting('site_title') : (($payment->user)? $payment->user->name:'المستخدم غير موجود');
                        $firstusertype  = ($payment->user_id == 0)? 'التطبيق' : (($payment->user)? (($payment->user->captain=='true')?'كابتن':'عميل'):'');             
                        $currency       = ($payment->country)?$payment->country->currency_ar:setting('site_currency_ar');
                        $operation = ($payment->operation)??'';
                        $operation = trans('user.'.$payment->operation) ;          
                    $type = ($payment->type == 'add' || ($payment->type == 'subtract' && ($payment->operation == 'balance_transfer' || $payment->operation == 'guarantee' || $payment->operation == 'reward' || $payment->operation == 'order_price') && $payment->user_id != $this->user_id))?'add':'subtract';                 
                $data[] = ['الطرف الاول'    => $firstuser.'('.$firstusertype.')',
                           'العملية'        => $operation,
                           'الطرف الثاني'  => $seconduser.'('.$secondusertype.')',
                           'النوع'         => ($type == 'add')?'أضافة':'خصم',
                           'المبلغ'        => $payment->amount.' '.$currency,
                           'الوقت'         => date('Y-m-d H:i',strtotime($payment->created_at))
                          ];
              }          
        }
        return collect($data);
    }
}
