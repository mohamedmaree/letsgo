@extends('dashboard.layout.master')
	@section('title')
	الحسابات المالية
	@endsection
	<!-- style -->
	@section('style')
		<style type="text/css">


			.reset
			{
				border:none;
				background: #fff;
    			margin-right: 11px;
			}

			.icon-trash
			{
				margin-left: 8px;
    			color: red;
			}

			.icon-checkmark4
			{
				margin-left: 8px;
    			color: green;
			}

			.dropdown-menu
			{
				min-width: 135px;
			}

			#hidden
			{
				display: none;
			}
		</style>
	@endsection
	<!-- /style -->
@section('content')
<div class="panel panel-flat">
	<div class="panel-heading">
		<h5 class="panel-title">الحسابات الماليه </h5>
		<div class="heading-elements">
			<ul class="icons-list">
        		<li><a data-action="collapse"></a></li>
        		<!-- <li><a data-action="reload"></a></li> -->
        		<!-- <li><a data-action="close"></a></li> -->
        	</ul>
    	</div>
	</div>

	<table class="table datatable-basic">
		<thead>
			<tr>
				<th>اسم المحول</th>
				<th>المبلغ</th>
				<th>الحاله</th>
				<th>المديونيه</th>
				<th>البنك المحول له</th>
				<th>الهاتف</th>
				<th>الايميل</th>
				<th>التاريخ</th>
				<th>التحكم</th>
			</tr>
		</thead>
		<tbody>
			@foreach($accounts as $a)
				<tr>
					<td>{{$a->User->name}}</td>
					<td>{{$a->ammount}}</td>
					@if($a->status < 1)
					<td><span class="label label-danger">لم يتم التأكيد</span></td>
					@else
					<td><span class="label label-success">تم التأكيد</span></td>
					@endif
					<td>{{$a->User->arrears}}</td>
					<td>{{$a->bank_name}}</td>
					<td>{{$a->User->phone}}</td>
					<td>{{$a->User->email}}</td>
					<td>{{$a->created_at->diffForHumans()}}</td>
					<td>
					<ul class="icons-list">
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">
								<i class="icon-menu9"></i>
							</a>

							<ul class="dropdown-menu dropdown-menu-right">
								<!-- accept -->
								@if($a->status < 1)
								<form action="{{route('moneyaccept')}}" method="post" style="margin-bottom:10px">
									{{csrf_field()}}
									<input type="hidden" name="id" value="{{$a->User->id}}">
									<input type="hidden" name="ammount" value="{{$a->ammount}}">
									<input type="hidden" name="money_id" value="{{$a->id}}">
									<li><button type="submit" class="confirm reset"><i class="icon-checkmark4"></i>تأكيد</button></li>
								</form>
								<!-- accept And delete-->
								<form action="{{route('moneyacceptdelete')}}" method="post" style="margin-bottom:10px">
									{{csrf_field()}}
									<input type="hidden" name="id" value="{{$a->User->id}}">
									<input type="hidden" name="ammount" value="{{$a->ammount}}">
									<input type="hidden" name="money_id" value="{{$a->id}}">
									<li><button type="submit" class="confirm reset"><i class="icon-checkmark4"></i>تأكيد مع حذف</button></li>
								</form>
								@endif
								<!-- delete button -->
								<form action="{{route('moneydelete')}}" method="post">
									{{csrf_field()}}
									<input type="hidden" name="money_id" value="{{$a->id}}">
									<li><button type="submit" class="generalDelete reset"><i class="icon-trash"></i>حذف</button></li>
								</form>
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

<script type="text/javascript">
$(document).on('click','.confirm',function(e){
	var result = confirm('هل تريد استمرار تأكيد المعمله ؟ ')
		if(result == false)
		{
		e.preventDefault()
		}
});
	$('.generalDelete').on('click',function(e){ 
		var result = confirm('هل تريد استمرار عملية الحذف ؟ ');
		if(result == false){
			e.preventDefault();
		}
	});
</script>
@endsection



@endsection