@extends('dashboard.layout.master')
    @section('title')
	 تقيمات {{$user->name}}
	@endsection
@section('content')

<div class="panel panel-flat">	
	<table class="table datatable-basic">
		<thead>
			<tr>
				<th>التاريخ</th>
				<th>عدد التقيمات </th>
				<th>المتوسط </th>
			</tr>
		</thead>
		<tbody>
			@foreach($ratings as $rating)
				<tr>
					<td>{{date('d - m',strtotime($rating->date))}}</td>
					<td>{{$rating->count_ratings}}</td>
					<td>{{$rating->rate_average}}</td>
				</tr>
			@endforeach
		</tbody>
	</table>

</div>
	
@endsection