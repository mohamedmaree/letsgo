<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\chargeCards;
use Auth;
class chargeCardsExport implements FromCollection{

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection(){ 
              $cards = chargeCards::orderBy('created_at','DESC')->get();
              $data[]   = ['الكود ','القيمة','تاريخ الاضافه'];
              foreach($cards as $card){
                $data[] = ['الكود'       => $card->code,
                           'القيمة'      => $card->value.' '.setting('site_currency_ar'),
                           'تاريخ الاضافه'     => $card->created_at
                          ];
              }
            return $data;
    }
}
