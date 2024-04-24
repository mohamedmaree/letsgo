<?php

namespace App\Exports;

use App\Order;
use Maatwebsite\Excel\Concerns\FromCollection;

class ordersExport implements FromCollection
{
	public function __construct($type){
	    	$this->type = $type;
	}

    public function collection()
    {
            if($this->type == 'all'){
                $orders = Order::with('user','captain')->where('order_type','=','trip')->latest()->get();
                $data[] = ['رقم الرحلة ','العميل','القائد','النوع ','السعر ','الحالة ','من','الي','تاريخ الاضافة '];             
                foreach($orders as $order){
					if($order->status == 'open'){
					   $status = 'جديد';
                    }elseif($order->status == 'inprogress'){
					   $status = 'قيد التنفيذ';
					}elseif($order->status == 'finished'){
					   $status = 'منتهي';
                    }else{
					   $status = 'مغلق';
                    }                   	
                  $data[] = [
                             'رقم الرحلة'      => $order->id,
                             'العميل'          => ($order->user->name)??'',
                             'القائد'          => ($order->captain->name)??'',
                             'النوع'           => ($order->cartype)?$order->cartype->name_ar:'',
                             'السعر'           => $order->price.' '.$order->currency_ar, 
                             'الحالة'          => $status, 
                             'من'              => $order->start_address,
                             'الي'             => $order->end_address,
                             'تاريخ الاضافة'   => date('Y-m-d H:i',strtotime($order->created_at))
                            ];
                }
            }elseif($this->type == 'open'){
                $orders = Order::with('user')->where('order_type','=','trip')->where('status','=','open')->latest()->get();
                $data[] = ['رقم الرحلة ','العميل','النوع ','السعر المتوقع','من','الي','تاريخ الاضافة '];                                   
                foreach($orders as $order){                     
                  $data[] = [
                             'رقم الرحلة'       => $order->id,
                             'العميل'          => ($order->user->name)??'',
                             'النوع'           => ($order->cartype)?$order->cartype->name_ar:'',
                             'السعر المتوقع'   => $order->expected_price.' '.$order->currency_ar, 
                             'من'              => $order->start_address,
                             'الي'             => $order->end_address,
                             'تاريخ الاضافة'   => date('Y-m-d H:i',strtotime($order->created_at))
                            ];
                }               
            }elseif($this->type == 'inprogress'){
                $orders = Order::with('user','captain')->where('order_type','=','trip')->where('status','=','inprogress')->latest()->get();
                $data[] = ['رقم الرحلة ','العميل','القائد','النوع ','السعر ','من','الي','تاريخ الاضافة '];                                   
                foreach($orders as $order){                  	
                  $data[] = [
                             'رقم الرحلة'       => $order->id,
                             'العميل'          => ($order->user->name)??'',
                             'القائد'          => ($order->captain->name)??'',
                             'النوع'           => ($order->cartype)?$order->cartype->name_ar:'',
                             'السعر'           => $order->price.' '.$order->currency_ar, 
                             'من'              => $order->start_address,
                             'الي'             => $order->end_address,
                             'تاريخ الاضافة'   => date('Y-m-d H:i',strtotime($order->created_at))
                            ];
                }   
            }elseif($this->type == 'finished'){
                $orders = Order::with('user','captain')->where('order_type','=','trip')->where('status','=','finished')->latest()->get();
                $data[] = ['رقم الرحلة ','العميل','القائد','النوع ','السعر ','من','الي','تاريخ الاضافة '];                                   
                foreach($orders as $order){                	
                 $data[] = [
                             'رقم الرحلة'       => $order->id,
                             'العميل'          => ($order->user->name)??'',
                             'القائد'          => ($order->captain->name)??'',
                             'النوع'           => ($order->cartype)?$order->cartype->name_ar:'',
                             'السعر'           => $order->price.' '.$order->currency_ar, 
                             'من'              => $order->start_address,
                             'الي'             => $order->end_address,
                             'تاريخ الاضافة'   => date('Y-m-d H:i',strtotime($order->created_at))
                            ];
                }
            }elseif($this->type == 'closed'){
                $orders = Order::with('user','captain')->where('order_type','=','trip')->where('status','=','closed')->latest()->get();
                $data[] = ['رقم الرحلة ','العميل','القائد','النوع ','السعر ','من','الي','تاريخ الاضافة '];                                   
                foreach($orders as $order){                	
                 $data[] = [
                             'رقم الرحلة'       => $order->id,
                             'العميل'          => ($order->user->name)??'',
                             'القائد'          => ($order->captain->name)??'',
                             'النوع'           => ($order->cartype)?$order->cartype->name_ar:'',
                             'السعر'           => $order->price.' '.$order->currency_ar, 
                             'من'              => $order->start_address,
                             'الي'             => $order->end_address,
                             'تاريخ الاضافة'   => date('Y-m-d H:i',strtotime($order->created_at))
                            ];
                }
            }            
            return $data;
    }
}
