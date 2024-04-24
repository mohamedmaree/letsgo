<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\captainMoneyHistory;
class captainMoneyHistoriesExport implements FromCollection{

	    // public function __construct($type){
	    // 	// $this->type = $type;
	    // }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection(){ 
        $captainMoneyHistories = captainMoneyHistory::with('captain')->orderBy('created_at','DESC')->get();
            $data[]   = ['القائد ','النوع','المبلغ','الدورة' ,'تاريخ العملية'];
            foreach($captainMoneyHistories as $MoneyHistory){
              $data[] = ['القائد'         => ($MoneyHistory->captain)?$MoneyHistory->captain->name.' ( '.$MoneyHistory->captain->pin_code.' )':'',
                         'النوع'          => ($MoneyHistory->type == 'pay')?'دفع القائد':'استلم القائد',
                         'المبلغ'         => ($MoneyHistory->amount.' '.$MoneyHistory->currency),
                         'الدورة'          => date('Y-m-d H:i',strtotime($MoneyHistory->start_date)).' - '.date('Y-m-d H:i',strtotime($MoneyHistory->end_date)),
                         'تاريخ العملية'   => date('Y-m-d H:i ',strtotime($MoneyHistory->created_at))
                        ];
            }
        return $data;
    }
}
