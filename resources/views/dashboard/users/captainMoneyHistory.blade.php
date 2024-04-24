@extends('dashboard.layout.master')
	@section('title')
	أرشيف حسابات القائد 
	@endsection
<!-- style -->
@section('style')
@endsection
<!-- /style -->
@section('content')


<div class="row">
	<div class="col-md-12">
		<div class="panel panel-flat">
			<div class="panel-heading">
				<h6 class="panel-title">أرشيف حسابات القائد {{$captain->name}}</h6>
				<div class="heading-elements">
					<ul class="icons-list">
                		<li><a data-action="reload"></a></li>
                	</ul>
            	</div>
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-xs-3">
						<button class="btn bg-purple-300 btn-block btn-float btn-float-lg" type="button"><i class="icon-list-numbered"></i> <span>عدد التعاملات : {{count($captainMoneyHistories)}} </span> </button>
					</div>			
					<div class="col-xs-3">
						<a href="{{url('admin/downloadCaptainMoneyArchive/'.$captain->id)}}"><button class="btn btn-block btn-float btn-float-lg correspondent" style="background-color:#1b926c; color:#fff;" type="button" ><i class="fa fa-file-excel-o"></i> <span>تحميل Excel </span></button></a>
					</div>				
				</div>
			</div>
			<div class="panel-body">
				<div class="tabbable">
					<ul class="nav nav-tabs">
						<!-- supervisors reports  -->
						<li class="active"><a href="#basic-tab1" data-toggle="tab">التسويات</a></li>
						<!-- users reports -->
						<!-- <li><a href="#basic-tab1" data-toggle="tab">تقارير الاعضاء</a></li> -->
					</ul>

					<div class="tab-content">

						<!-- users reports -->
						<div class="tab-pane active" id="basic-tab1">
							<table class="table text-nowrap">
								<thead>
									<tr>
										<th>المبلغ</th>
										<th>الحالة</th>
										<th>الدورة </th>
										<th>التاريخ</th>
									</tr>
								</thead>
								<tbody>
									@foreach($captainMoneyHistories as $captainMoney)
										<tr>
											<td>
												{{$captainMoney->amount.' '.$captainMoney->currency}}
											</td>
											<td>
												{{($captainMoney->type =='pay')?'دفع':'استلم'}}
											</td>
											<td>
												{{date('Y-m-d H:i',strtotime($captainMoney->start_date)) }} - {{ date('Y-m-d H:i',strtotime($captainMoney->end_date))}}
											</td>
											<td>
												{{date('Y-m-d H:i',strtotime($captainMoney->created_at)) }}
											</td>
										</tr>
									@endforeach
								</tbody>
									<tr>
										<!-- pagination -->
										<td>{{$captainMoneyHistories->links()}}</td>
									</tr>
							</table>
						</div>

					</div>
				</div>
			</div>
		</div>

	</div>
</div>

@endsection