@extends('dashboard.layout.master')
	@section('title')
	البريد الوارد
	@endsection
@section('content')
<div class="panel panel-flat">
	<div class="panel-heading">
		<h5 class="panel-title">قائمة البريد الوارد</h5>
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
				<button class="btn bg-purple-300 btn-block btn-float btn-float-lg" type="button"><i class="icon-list-numbered"></i> <span>عدد البريد الوارد : {{count($messages)}} </span> </button>
			</div>
		
		</div>
	</div>
	<!-- /buttons -->

	<table class="table datatable-basic">
		<thead>
			<tr>
				<th>الاسم</th>
				<th>الهاتف</th>
				<th>الرساله</th>
				<th>التاريخ</th>
				<th>التحكم</th>
			</tr>
		</thead>
		<tbody>
			@foreach($messages as $m)
				
				<tr @if($m->showOrNow == 0) style="background: #e0d0d0" @endif >
					<td><a href="{{route('showmessage',$m->id)}}">{{$m->name}}</a></td>
					<td>0{{$m->phone}}</td>
					<td><a href="{{route('showmessage',$m->id)}}">{{str_limit($m->message,30)}}</a></td>
					<td>{{date('Y-m-d h:i a',strtotime($m->created_at))}}</td>
					<td>
					<ul class="icons-list">
						<li>
							<form action="{{route('deletemessage')}}" method="POST">
								{{csrf_field()}}
								<input type="hidden" name="id" value="{{$m->id}}">
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
		var result = confirm('هل تريد استمرار عملية الحذف ؟ ');
		if(result == false){
			e.preventDefault();
		}
	});
</script>

@endsection