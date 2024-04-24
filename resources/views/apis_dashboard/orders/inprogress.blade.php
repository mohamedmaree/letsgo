@extends('apis_dashboard.layout.master')
	@section('title')
	طلبات قيد التنفيذ
	@endsection
@section('content')
<div class="panel panel-flat">
	<div class="panel-heading">
		<h5 class="panel-title">طلبات قيد التنفيذ</h5>
		<div class="heading-elements">
			<ul class="icons-list">
        		<li><a data-action="collapse"></a></li>
        		<li><a data-action="reload"></a></li>
        		<!-- <li><a data-action="close"></a></li> -->
        	</ul>
    	</div>
	</div>

	<!-- buttons -->
	<div class="panel-body">
		<div class="row">
			<div class="col-xs-3">
				<button class="btn bg-purple-300 btn-block btn-float btn-float-lg" type="button"><i class="icon-list-numbered"></i> <span>عدد الطلبات : {{count($orders)}} </span> </button>
			</div>
			
		</div>
	</div>
	<!-- /buttons -->
	<table class="table datatable-basic">
		<thead>
			<tr>
				<th>رقم الطلب</th>
				<th>العميل</th>
				<th>القائد </th>
				<th>النوع</th>
				<th>السعر</th>
				<th>الحالة</th>
				<th>من </th>
				<th>الي </th>
				<th>تاريخ الاضافة</th>
				<th>التحكم</th>
			</tr>
		</thead>
		<tbody>
			@foreach($orders as $order)
				<tr>
					<td>{{$order->id}}</td>
					<td>
					   	  {{($order->user_name)??''}}
					</td>
					<td>{{($order->captain)?$order->captain->name:''}}</td>
					<td>{{($order->cartype)?$order->cartype->name_ar:''}}</td>
					<td>{{$order->price}} {{$order->currency_ar}}</td>
					@if($order->status == 'open')
					<td>جديد</td>
                    @elseif($order->status == 'inprogress')
					<td>قيد التنفيذ</td>
					@elseif($order->status == 'finished')
					<td>منتهي</td>
                    @else
					<td>مغلق</td>
                    @endif
                    <td>{{str_limit($order->start_address,25) }}</td>
                    <td>{{str_limit($order->end_address,25) }}</td>
					<td>{{date('Y-m-d H:i',strtotime($order->created_at))}}</td>
					<td>
						<a href="{{url('apis/ApiShowOrder/'.$order->id)}}">مشاهدة</a>
					</td>				
				</tr>
			@endforeach
		</tbody>
	</table>

</div>

<!-- javascript -->
@section('script')
<script type="text/javascript" src="{{asset('dashboard/js/plugins/tables/datatables/datatables.min.js')}}"></script>
<script type="text/javascript" src="{{asset('dashboard/js/plugins/forms/selects/select2.min.js')}}"></script>
<script type="text/javascript" src="{{asset('dashboard/js/pages/datatables_basic.js')}}"></script>
@endsection


@endsection