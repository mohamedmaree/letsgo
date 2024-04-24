<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\Profits;
use Illuminate\Support\Facades\DB; 
use Auth;
class ProfitsExport implements FromCollection{

	    // public function __construct($type){
	    // 	// $this->type = $type;
	    // }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection(){ 
               $ProfitsByDay      = Profits::with('provider')->select('*', DB::raw('SUM(value) as total_value'))->groupBy('date')->get();
                $currency = setting('site_currency_ar');
                $data[]   = ['التاريخ ','إجمالي الأرباح'];
                foreach($ProfitsByDay as $profit){
                  $data[] = ['التاريخ'       => $profit->date,
                             'الربح'         => $profit->total_value.' '.$currency
                            ];
                }
            return $data;
    }
}
