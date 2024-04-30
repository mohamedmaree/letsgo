<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\User;
use Auth;
class UsersExport implements FromCollection
{

        public function __construct($type){
            $this->type = $type;
        }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection(){ 
            if($this->type == 'allusers'){
                $users = User::with('Role')->latest()->get();
                $data[] = ['الاسم ','الهاتف','البريد الالكتروني ','الصلاحية','الحالة ','النوع ',' الرصيد','الطلبات ','تاريخ الاضافة '];
                $status = ''; $num_orders = 0;
                foreach($users as $user){
                    if($user->active == 'active'){
                        $status = 'نشط';
                    }elseif($user->active == 'block'){
                        $status = 'محظور';
                    }else{
                        $status = 'غير نشط';
                    }
                    if($user->captain == 'true'){
                        $num_orders = $user->num_done_orders;
                    }else{
                        $num_orders = $user->num_user_orders;
                    }

                  $data[] = ['الاسم'          => $user->name,
                             'الهاتف'         => '0'.$user->phone,
                             'البريد الالكتروني' => $user->email,
                             'الصلاحية'       => ($user->Role)? $user->Role->role:'',
                             'الحالة '         => $status, 
                             'النوع'          => ($user->captain=='true')?'كابتن':'عميل', 
                             'الرصيد'         => $user->balance, 
                             'الطلبات'        => $num_orders, 
                             'تاريخ الاضافة'  => date('Y-m-d H:i',strtotime($user->created_at))
                            ];
                }
            }elseif($this->type == 'clients'){
                $users = User::with('Role')->where('role','=','0')->where('captain','=','false')->latest()->get();
                $data[] = ['الاسم ','الهاتف','البريد الالكتروني ','الصلاحية','الحالة ','الرصيد','الطلبات ','تاريخ الاضافة '];
                $status = '';
                foreach($users as $user){
                    if($user->active == 'active'){
                        $status = 'نشط';
                    }elseif($user->active == 'block'){
                        $status = 'محظور';
                    }else{
                        $status = 'غير نشط';
                    }
                  $data[] = ['الاسم'          => $user->name,
                             'الهاتف'         => '0'.$user->phone,
                             'البريد الالكتروني' => $user->email,
                             'الصلاحية'       => ($user->Role)? $user->Role->role:'',
                             'الحالة '         => $status, 
                             'الرصيد'         => $user->balance, 
                             'الطلبات'        => $user->num_user_orders, 
                             'تاريخ الاضافة'  => date('Y-m-d H:i',strtotime($user->created_at))
                            ];
                }                
            }elseif($this->type == 'providers'){
                $users = User::with('Role')->where('role','=','0')->where('captain','=','true')->latest()->get();
                $data[] = ['الكود','الاسم ','الهاتف','البريد الالكتروني ','الحالة ','الرصيد ','رصيد المدفوعات الالكترونية','الطلبات ','تاريخ الاضافة '];
                $status = '';
                foreach($users as $user){
                    if($user->active == 'active'){
                        $status = 'نشط';
                    }elseif($user->active == 'block'){
                        $status = 'محظور';
                    }else{
                        $status = 'غير نشط';
                    }
                  $data[] = ['الكود'              => $user->pin_code,
                             'الاسم'              => $user->name,
                             'الهاتف'             => '0'.$user->phone,
                             'البريد الالكتروني' => $user->email,
                             'الحالة '             => $status,  
                             'الرصيد'             => $user->balance, 
                             'رصيد المدفوعات الالكترونية'    => number_format($user->balance_electronic_payment,2), 
                             'الطلبات'             => $user->num_done_orders, 
                             'تاريخ الاضافة'       => date('Y-m-d H:i',strtotime($user->created_at))
                            ];
                }
            }elseif($this->type == 'supervisiors'){
                $users = User::with('Role')->where('role','>','0')->latest()->get();
                $data[] = ['الاسم ','الهاتف','البريد الالكتروني ','الصلاحية','الحالة ','تاريخ الاضافة '];
                $status = ''; $num_orders = 0;
                foreach($users as $user){
                    if($user->active == 'active'){
                        $status = 'نشط';
                    }elseif($user->active == 'block'){
                        $status = 'محظور';
                    }else{
                        $status = 'غير نشط';
                    }
                  $data[] = ['الاسم'          => $user->name,
                             'الهاتف'         => '0'.$user->phone,
                             'البريد الالكتروني' => $user->email,
                             'الصلاحية'       => ($user->Role)? $user->Role->role:'',
                             'الحالة '        => $status, 
                             'تاريخ الاضافة'  => date('Y-m-d H:i',strtotime($user->created_at))
                            ];
                }
            }elseif($this->type == 'reviewers'){
                $users = User::with('Role')->where('role','>','0')->where('type','reviewer')->latest()->get();
                $data[] = ['الاسم ','الهاتف','البريد الالكتروني ','الرصيد','قيمة مراجعة الطلب','عدد طلبات تم مراجعتها','عدد طلبات مقبولة','عدد طلبات مرفوضة','تاريخ الاضافة '];
                foreach($users as $user){
                $data[] = ['الاسم'           => $user->name,
                            'الهاتف'         => '0'.$user->phone,
                             'البريد الالكتروني' => $user->email,
                             'الرصيد '        => $user->balance, 
                            'قيمة مراجعة الطلب'       => $user->review_order_value, 
                            'عدد طلبات تم مراجعتها'   => $user->num_reviewed_orders, 
                            'عدد طلبات مقبولة'        => $user->num_review_accepted_orders, 
                            'عدد طلبات مرفوضة'        => $user->num_review_refused_orders, 
                            'تاريخ الاضافة'   => date('Y-m-d H:i',strtotime($user->created_at))
                            ];
                }
            }elseif($this->type == 'ambassadors'){
                $users = User::with('Role')->where('role','>','0')->where('type','ambassador')->latest()->get();
                $data[] = ['الاسم ','الهاتف','البريد الالكتروني ','تاريخ الاضافة '];
                foreach($users as $user){
                  $data[] = ['الاسم'           => $user->name,
                             'الهاتف'         => '0'.$user->phone,
                             'البريد الالكتروني '        => $user->email, 
                             'تاريخ الاضافة'   => date('Y-m-d H:i',strtotime($user->created_at))
                            ];
                }
            }
            return collect($data);
    }
}
