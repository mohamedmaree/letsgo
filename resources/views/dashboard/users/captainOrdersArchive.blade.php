@extends('dashboard.layout.master')
	@section('title')
	رحلات القائد {{$user->name}}
 	@endsection
@section('content')
<div class="panel panel-flat">
	<div class="panel-heading">
		<h5 class="panel-title">رحلات القائد {{$user->name}}</h5>
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
				<button class="btn bg-purple-300 btn-block btn-float btn-float-lg" type="button"><i class="icon-list-numbered"></i> <span>عدد الرحلات : {{count($orders)}} </span> </button>
			</div>			
		</div>
	</div>
	<!-- /buttons -->
	<table class="table datatable-basic">
		<thead>
			<tr>
				<th>رقم الرحلة </th>
				<th>العميل </th>
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
					<td>{{$order->order_id}}</td>
					<td>{{($currentorder = $order->order)? (($client = $currentorder->user)?$client->name:''):'' }}</td>
					<td>{{($currentorder = $order->order)? (($cartype = $currentorder->cartype)?$cartype->name_ar:''):''}}</td>
					<td>{{($currentorder = $order->order)? $currentorder->price.' '.$currentorder->currency_ar:''}}</td>
					@if($order->status == 'created')
					<td>جديدة</td>
                    @elseif($order->status == 'withdraw')
					<td>انسحاب</td>
					@elseif($order->status == 'finished')
					<td>منتهية</td>
                    @elseif($order->status == 'closed')
					<td>مغلقة</td>
                    @endif
                    @if($currentorder = $order->order)
                    <td>{{str_limit($currentorder->start_address,25) }}</td>
                    <td>{{str_limit($currentorder->end_address,25) }}</td>
                    @else
                    <td></td>
                    <td></td>
                    @endif
					<td>{{date('Y-m-d H:i',strtotime($order->created_at))}}</td>
					<td>
					<ul class="icons-list">
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">
								<i class="icon-menu9"></i>
							</a>
							<ul class="dropdown-menu dropdown-menu-right">
								<li>
									<a href="{{url('admin/showOrder/'.$order->order_id)}}"><i class="glyphicon glyphicon-eye-open "></i>مشاهدة</a>
								</li>

							</ul>
						</li>
					</ul>
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