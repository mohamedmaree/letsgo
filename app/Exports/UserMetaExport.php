<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\userMeta;
use Auth;
class UserMetaExport implements FromCollection{

	    // public function __construct($type){
	    // 	// $this->type = $type;
	    // }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection(){ 
      $metas = userMeta::where('complete','=','false')->latest()->get();
                $data[]   = ['الاسم ','الهاتف','البريد الالكتروني','الدولة','المدينة','نوع السيارة','التاريخ'];
                foreach($metas as $meta){
                  $data[] = ['الاسم'           => $meta->name,
                             'الهاتف'         => '0'.$meta->phone,
                             'البريد الالكتروني' => $meta->email,
                             'الدولة'         => ($meta->country)?$meta->country->name_ar : '',
                             'المدينة'        => ($meta->city)?$meta->city->name_ar : '',
                             'نوع السيارة'    => $meta->car_type,
                             'التاريخ'        => date('Y-m-d H:i',strtotime($meta->created_at))
                            ];
                }
      return collect($data);
    }
}
