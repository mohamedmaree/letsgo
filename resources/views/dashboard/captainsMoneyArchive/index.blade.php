@extends('dashboard.layout.master')
	@section('title')
	أرشيف حسابات القادة
	@endsection
@section('content')
<div class="panel panel-flat">
	<div class="panel-heading">
		<h5 class="panel-title">أرشيف حسابات القادة</h5>
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
				<button class="btn bg-purple-300 btn-block btn-float btn-float-lg" type="button"><i class="icon-list-numbered"></i> <span>اجمالي عدد المعاملات : {{count($captainMoneyHistories)}} </span> </button>
			</div>
			<div class="col-xs-3">
				<a href="{{route('downloadCaptainsMoneyArchive')}}"><button class="btn btn-block btn-float btn-float-lg correspondent" style="background-color:#1b926c; color:#fff;" type="button" ><i class="fa fa-file-excel-o"></i> <span>تحميل Excel </span></button></a>
			</div>				
		</div>
	</div>
	<!-- /buttons -->

	<table class="table datatable-basic">
		<thead>
			<tr>
				<th>القائد</th>
				<th>النوع </th>
				<th>المبلغ</th>
				<th>الدورة</th>
				<th>تاريخ العملية</th>
			</tr>
		</thead>
		<tbody>
			@foreach($captainMoneyHistories as $MoneyHistory)
				<tr>
					<td>{{($MoneyHistory->captain)?$MoneyHistory->captain->name.' ( '.$MoneyHistory->captain->pin_code.' )':''}}</td>
					<td>{{($MoneyHistory->type == 'pay')?'دفع القائد':'استلم القائد'}}</td>
					<td>{{($MoneyHistory->amount.' '.$MoneyHistory->currency)}}</td>
					<td>{{date('Y-m-d',strtotime($MoneyHistory->start_date)) }} - {{ date('Y-m-d',strtotime($MoneyHistory->end_date))}}</td>
					<td>{{date('Y-m-d H:i ',strtotime($MoneyHistory->created_at))}}</td>
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