@extends('dashboard.layout.master')
    @section('title')
	 نسبة قبول {{$user->name}}
	@endsection
@section('content')

<div class="panel panel-flat">	
	<table class="table datatable-basic">
		<thead>
			<tr>
				<th>التاريخ</th>
				<th>عدد الطلبات </th>
				<th>الطلبات المقبولة</th>
				<th>نسبة القبول </th>
			</tr>
		</thead>
		<tbody>
			@foreach($userorders as $userorder)
				<tr>
					<td>{{date('d - m',strtotime($userorder->date))}}</td>
					<td>{{$userorder->count_orders}}</td>
					<?php $count_finished_orders = getFinishedOrdersByDate($userorder->date);?>
					<td>{{$count_finished_orders}}</td>
					<td>{{($count_finished_orders == 0 )? '0%' : round( ( $count_finished_orders / $userorder->count_orders ) * 100 ,1).'%'}}</td>
				</tr>
			@endforeach
		</tbody>
	</table>

</div>
	
@endsection