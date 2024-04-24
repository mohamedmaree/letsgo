@extends('dashboard.layout.master')
    @section('title')
	 اداء العضو {{$user->name}}
	@endsection

    @section('style')
    	<style>
	    	.usermetaimg {
	    		width: 160px;
				height: 150px;
				border-radius: 10px;
	    	}

    		.info p {
    			font-size: 18px;
				color: #191919;
    		}

    		.info p span {
    			font-size: 16px;
				color: #2bb673;
    		}

    		.SMS {
    			color: #2bb673;
				font-size: 16px;
				cursor: pointer;
    		}
    		.d-flex{
    			display: flex;
    		}
    		.panel1{
    			justify-content: space-between;
    			align-items: center;
    			padding: 10px;
    		}
    		.cap a i{
    			color:#2bb673;
    		}
    		.cap-info{
    			text-align: center;
    		}
    		.cap-info h3{
    			font-weight: bold;
    		}
    		.cap a{
    			display: flex;
    			flex-direction: column;
				font-size: 19px;
				justify-content: center;
				text-align: center;
				color:rgba(0,0,0,.55);
    		}
    		.panel2{
    			display: flex;
				flex-direction: column;
				justify-content: center;
				text-align: center;
				padding: 15px;
    		}
    		.panel2 i{
    			color:#2bb673;
    			font-size: 40px;
    			margin-bottom: 10px;
    		}
    		.lgray{
    			color:#797979;    					
    			font-size:16px;
    		}
    		.lgray+span{
    			font-size: 15px;
    		}
    		.info-item{
    			padding: 0 14px;
				border-bottom: 1px solid #dadada;
    		}
    		.info-item .info{
    			display: flex;
				justify-content: space-between;
    		}
    		.green{
    			color:#2bb673!important;
    		}
    		.info-item .info span{
    			font-size: 16px;
    		}
    		.info-item:last-of-type{
    			border-bottom: 0
    		}
    		.info-item .info h3{
    			font-size: 16px;
    			font-weight: bold;
    		}
    	</style>

	@endsection
@section('content')

<div class="panel panel1 d-flex">
	<div class="cap d-flex">
		<a href="{{url('admin/userPerformance/'.$user->id.'/'.($page+1))}}">
			<i class="fa fa-chevron-right" aria-hidden="true"></i>
			<span>أقدم</span>
		</a>
	</div>
	<div class="cap-info">
		<h3>{{$user->name}}</h3>
		<p>{{$new_start_date}} - {{$new_end_date}} 
		</p>
			@if($captainMoneystatus == 'finished')
				<p>
				( تمت التسوية <i class=" glyphicon glyphicon-ok-sign green"></i> )
			    </p>
			@endif		
	</div>
	<div class="cap d-flex">
		<?php $newpage = ($page <= 1)? 1 : $page-1; ?>
		@if($page > 1)
		<a href="{{url('admin/userPerformance/'.$user->id.'/'.$newpage)}}">
			<i class="fa fa-chevron-left" aria-hidden="true"></i>
			<span>أحدث</span>
		</a>
		@endif
	</div>
</div>
<div class="row">
 	<div class="col-md-4 col-xs-12">
 		<div class="panel panel2">
 			<a href="{{url('admin/userAvailableTimes/'.$user->id.'/'.$page)}}">
	 			<i class="fa fa-clock-o" aria-hidden="true"></i>
	 	    </a>
	 			<span class="lgray">
	 				عدد ساعات توفرك
	 			</span>
		 		<span>
		 			{{$totalAvailableHours}} 
		 		</span>
 		</div>
 	</div>
 	<div class="col-md-4 col-xs-12">
 		<div class="panel panel2">
 			<a href="{{url('admin/userOrdersRatings/'.$user->id.'/'.$page)}}">
 			  <i class="fa fa-star" aria-hidden="true"></i>
 		    </a>
 			<span class="lgray">
 				التقييم
 			</span>
	 		<span>
	 			{{round($totalRate,1)}}
	 		</span>
 		</div>
 	</div>
 	<div class="col-md-4 col-xs-12">
 		<div class="panel panel2">
 			<a href="{{url('admin/ordersAcceptance/'.$user->id.'/'.$page)}}">
 			  <i class="fa fa-check-circle-o" aria-hidden="true"></i>
 		    </a>
 			<span class="lgray">
 				نسبة القبول
 			</span>
	 		<span>
	 			{{$totalAccept}}
	 		</span>
 		</div>
 	</div>
</div>
<div class="panel">
	<div class="info-item">
		<h3>عدد الرحلات</h3>
		<div class="info">
			<span>{{$num_orders}}</span>
			<span>
				{{$totalPrices_of_orders.' '.$currency}}
			</span>
		</div>
	</div>
	<div class="info-item">
		<h3>المكافأت</h3>
		<div class="info">
			<span>{{$num_rewards_orders}}</span>
			<span>
				{{$totalPrices_of_rewards_orders.' '.$currency}}
			</span>
		</div>
	</div>
	<div class="info-item">
		<h3>الرحلات الملغية</h3>
		<div class="info">
			<span>{{$num_withdraw_orders}}</span>
			<span style="{{(floatval($totalPrices_of_withdraw_orders) >= 0)?'color:#2bb673;':'color:red;'}}">
				{{$totalPrices_of_withdraw_orders.' '.$currency}}
			</span>
		</div>
	</div>
	<div class="info-item">
		<h3>الضمانات</h3>
		<div class="info">
			<span>{{$num_guarantee_orders}}</span>
			<span>
				{{$totalPrices_of_guarantee_orders.' '.$currency}}
			</span>
		</div>
	</div>
	<div class="info-item">
		<h3>رسوم الرحلات النقدية</h3>
		<div class="info">
			<span>{{$num_cash_orders}}</span>
			<span style="color:red;">
				{{$totalPrices_of_cash_orders.' '.$currency}}
			</span>
		</div>
	</div>	
	<div class="info-item">
		<div class="info">
			@if(floatval($total) >= 0)
			<h3 class="green">الاجمالى</h3>
			<h3 class="green">
			@else
			<h3 style="color:red;">الاجمالى</h3>
			<h3 style="color:red;">			
			@endif
				{{$total.' '.$currency}}
			</h3>
		</div>
	</div>

	<div class="info-item">
		<div class="info">
			@if($captainMoneystatus == 'finished')
				<h3>المبلغ المدفوع {{($captainMoney->type == 'pay')?'من القائد':'من التطبيق'}}</h3>
				<h3>
					{{$captainMoney->amount.' '.$captainMoney->currency}}
				</h3>
			@else
			    <h3>المبلغ المدفوع {{(floatval($total) > 0 )?'من التطبيق':'من القائد'}}</h3>
				<h3>
				<form action="{{route('finishCaptainWeekMoney')}}" method="POST">
					{{csrf_field()}}
					<input type="hidden" name="captain_id" value="{{$user->id}}">
					<input type="hidden" name="total" value="{{$total}}">
					<input type="hidden" name="currency" value="{{$currency}}">
					<input type="hidden" name="start_date" value="{{$start_date}}">
					<input type="hidden" name="end_date" value="{{$end_date}}">
					<input type="number" name="amount" class="form-control" value="" step="0.01" placeholder="{{abs($total)}}" required>
					<button type="submit" class="btn btn-lg btn-primary form-control" >تأكيد</button>
				</form>
			    </h3>
			@endif
		</div>
	</div>	
</div>

<div class="panel panel-flat">	
	<table class="table datatable-basic">
		<thead>
			<tr>
				<th>رقم الرحلة</th>
				<th>سعر الرحلة</th>
				<th>تاريخ الرحلة </th>
				<th>التفاصيل</th>
			</tr>
		</thead>
		<tbody>
			@foreach($orders as $order)
				<tr>
					<td>{{$order->id}}</td>
					<td>{{$order->price}} {{$order->currency_ar}}</td>
					<td>{{date('Y-m-d H:i',strtotime($order->created_at))}}</td>
					<td><a href="{{url('admin/showOrder/'.$order->id)}}">مشاهدة</a></td>				
				</tr>
			@endforeach
		</tbody>
	</table>

</div>
	
@endsection