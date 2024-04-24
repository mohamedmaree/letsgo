@extends('dashboard.layout.master')
    @section('title')
	 ساعات توفر {{$user->name}}
	@endsection
@section('content')

<div class="panel panel-flat">	
	<table class="table datatable-basic">
		<thead>
			<tr>
				<th>التاريخ</th>
				<th>مدة التوفر </th>
			</tr>
		</thead>
		<tbody>
			@foreach($availableHours as $availableHour)
				<tr>
					<td>{{date('d - m',strtotime($availableHour->date))}}</td>
					<td>{{(convertToHoursMins($availableHour->total_minutes))??''}}</td>
				</tr>
			@endforeach
		</tbody>
	</table>

</div>
	
@endsection