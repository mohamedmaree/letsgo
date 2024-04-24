@extends('apis_dashboard.layout.master')
    @section('title')
	 عرض طلب : {{$order->id}}
              
	@endsection
@section('content')
<div class="panel panel-flat">
	<div class="panel-heading">
		<h5 class="panel-title">عرض طلب : {{$order->id}}
                
	    </h5>
		<div class="heading-elements">
			<ul class="icons-list">
        		<li><a data-action="collapse"></a></li>
        		<li><a data-action="reload"></a></li>
        	</ul>
    	</div>
	</div>
	<div class="panel panel-flat">
		<div class="panel-body">
			<div class="row text-center">
				<div class="col-sm-12 alert alert-success">
					<div class="col-sm-4">العميل : {{($order->user_name)??''}} </div>
					<div class="col-sm-4">القائد : {{($order->captain)?$order->captain->name:''}}</div>
				    <div class="col-sm-4">حالة الطلب : 
				            @if($order->status == 'open')
                                مفتوح 
                           	@elseif($order->status == 'inprogress')
                                قيد التنفيذ
                           	@elseif($order->status == 'finished')
                                منتهي
                            @else
                                مغلق
                           	@endif
				    </div>
				</div>
				
				<br>
				<table class="table table-bordered table-strapped">
					<tbody>
                        <tr>
                           <td> نوع السيارة</td>
                           <td>{{($order->cartype)?$order->cartype->name_ar: ''}}</td>
						</tr>
						<tr>
                           <td> معلومات السيارة</td>
                           <td>{{($order->car)?$order->car->brand.'('.$order->car->model.'-'.$order->car->year.')' : ''}}</td>
						</tr>
						<tr>
                           <td> رقم السيارة</td>
                           <td>{{($order->car)?$order->car->car_number:''}}</td>
						</tr>
						<tr>
                           <td> صورة السيارة</td>
                           <td><a href="{{($order->car)? url('img/car/'.$order->car->image) : url('img/car/default.png')}}"><img src="{{($order->car)? url('img/car/'.$order->car->image) : url('img/car/default.png')}}"> </a></td>
						</tr>

						<tr>
                           <td> نقطة الانطلاق</td>
                           <td>{{$order->start_address}}</td>
						</tr>
						<tr>
                           <td> نقطة النهاية </td>
                           <td>{{$order->end_address}}</td>
						</tr>
						<tr>
                           <td> السعر المتوقع</td>
                           <td>{{ floatval($order->expected_price)}} {{$order->currency_ar}}</td>
						</tr>
						<tr>
                           <td> السعر النهائي للرحلة</td>
                           <td>{{$order->price}} {{$order->currency_ar}}</td>
						</tr>
						<tr>
                           <td> المبلغ المطلوب نقدا </td>
                           <td>{{$order->required_price}} {{$order->currency_ar}}</td>
						</tr>
						
						<tr>
                           <td> تأكيد الدفع  </td>
                           <td>{{($order->confirm_payment == 'true')?'تم':'لم يتم'}}</td>
						</tr>
						<tr>
                           <td> اجمالي المدفوع نقداً </td>
                           <td>{{$order->total_payments}} {{$order->currency_ar}}</td>
						</tr>
						<tr>
                           <td> الدولة </td>
                           <td>{{($order->country)?$order->country->name_ar:''}}
                           </td>
						</tr>
						<tr>
                           <td> نوع الدفع </td>
                           <td>{{($order->payment_type=='cash')?'نقدي':'بطاقة ائتمانية'}}</td>
						</tr>
						<tr>
                           <td> المسافة المتوقعة </td>
                           <td>{{$order->expected_distance}}</td>
						</tr>
						<tr>
                           <td> المدة المتوقعة </td>
                           <td>{{$order->expected_period}}</td>
						</tr>
						<tr>
                           <td> مسافة الطلب </td>
                           <td>{{$order->distance}}</td>
						</tr>
						<tr>
                           <td> مدة الطلب </td>
                           <td>{{$order->period}}</td>
						</tr>
						<tr>
                           <td> وقت الانتظار المبدئي </td>
                           <td>{{$order->initial_wait}}</td>
						</tr>
						<tr>
                           <td> وقت الانتظار أثناء الطلب </td>
                           <td>{{$order->during_order_wait}}</td>
						</tr>
						<tr>
                           <td> وقت استقبال الطلب </td>
                           <td>{{$order->reception_time}}</td>
						</tr>
						<tr>
                           <td> القائد فى الطريق </td>
                           <td>{{($order->captain_in_road == 'true')?'نعم':'لا'}}</td>
						</tr>
						<tr>
                           <td> القائد وصل لاستلام الطلب</td>
                           <td>{{($order->captain_arrived == 'true')?'نعم'.' ('.$order->captain_arrived_time.')':'لا'}}</td>
						</tr>
						<tr>
                           <td> القائد بدأ الطلب </td>
                           <td>{{($order->start_journey == 'true')?'نعم'.' ('.$order->start_journey_time.')':'لا'}}</td>
						</tr>
						<tr>
                           <td> وقت تسليم الطلب </td>
                           <td>{{$order->end_journey_time}}</td>
						</tr>
						<tr>
                           <td> التفاصيل </td>
                           <td>{{$order->notes}}</td>
						</tr>										
						@if($order->close_reason)
						<tr>
                            <td>سبب الإغلاق</td>
                            <td>{{$order->close_reason}}</td>
                        </tr>
						@endif
						<tr>
                           <td>  تاريخ الطلب  </td>
                           <td>{{date('Y-m-d H:i',strtotime($order->created_at))}}</td>
						</tr>												
                    </tbody>
				</table>

			</div>
		</div>
	</div>
</div>



@endsection