@extends('dashboard.layout.master')
	@section('title')
	       رحلات تم الانسحاب منها
	@endsection
@section('content')
<div class="panel panel-flat">
	<div class="panel-heading">
		<h5 class="panel-title">رحلات تم الانسحاب منها </h5>
		<div class="heading-elements">
			<ul class="icons-list">
        		<li><a data-action="collapse"></a></li>
        		<li><a data-action="reload"></a></li>
        	</ul>
    	</div>
	</div>

	<!-- buttons -->
	<div class="panel-body">
		<div class="row">
			<div class="col-xs-3">
				<button class="btn bg-purple-300 btn-block btn-float btn-float-lg" type="button"><i class="icon-list-numbered"></i> <span>عدد الانسحابات : {{count($reasons)}} </span> </button>
			</div>			
		</div>
	</div>
	<!-- /buttons -->
	<table class="table datatable-basic">
		<thead>
			<tr>
				<th>القائد</th>
				<th>الطلب</th>
				<th>السبب</th>
				<th>التاريخ</th>
				<th>التحكم</th>
			</tr>
		</thead>
		<tbody>
			@foreach($reasons as $reason)
				<tr>
					<td>{{($reason->user)? $reason->user->name:''}}</td>
					<td><a href="{{url('admin/showOrder/'.$reason->order_id)}}">الطلب</a></td>
					<td>{{$reason->reason}}</td>
					<td>{{$reason->created_at->diffForHumans()}}</td>
					<td>
					<ul class="icons-list">
						<li>
							<form action="{{route('deleteOrderWithdrawReason')}}" method="POST">
								{{csrf_field()}}
								<input type="hidden" name="id" value="{{$reason->id}}">
								<li><button type="submit" class="generalDelete reset" title="حذف"><i class="icon-trash"></i></button></li>
							</form>
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
<script type="text/javascript">
	$('.generalDelete').on('click',function(e){ 
		var result = confirm('هل أنت متأكد ؟ ');
		if(result == false){
			e.preventDefault();
		}
	});
</script>

@endsection