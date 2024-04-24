<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\captainMoneyHistory;
use App\User;
class captainMoneyHistoryExport implements FromCollection{

	    public function __construct($user_id){
	    	$this->user_id = $user_id;
	    }

    public function collection(){ 
            $captainMoneyHistories = captainMoneyHistory::where('captain_id','=',$this->user_id)->orderBy('created_at','DESC')->get();
            $data[]   = ['المبلغ','النوع','الدورة' ,'تاريخ العملية'];
            foreach($captainMoneyHistories as $MoneyHistory){
              $data[] = ['المبلغ'         => ($MoneyHistory->amount.' '.$MoneyHistory->currency),
                         'النوع'          => ($MoneyHistory->type == 'pay')?'دفع القائد':'استلم القائد',
                         'الدورة'          => date('Y-m-d H:i',strtotime($MoneyHistory->start_date)).' - '.date('Y-m-d H:i',strtotime($MoneyHistory->end_date)),
                         'تاريخ العملية'   => date('Y-m-d H:i ',strtotime($MoneyHistory->created_at))
                        ];
            }
        return $data;
    }
}
