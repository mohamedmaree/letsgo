@extends('dashboard.layout.master')
	@section('title')
	الأرباح
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
				<h6 class="panel-title">الأرباح</h6>
				<div class="heading-elements">
					<ul class="icons-list">
                		<li><a data-action="reload"></a></li>
                	</ul>
            	</div>
			</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-xs-3">
				<button class="btn bg-purple-300 btn-block btn-float btn-float-lg" type="button"><i class="icon-list-numbered"></i> <span>عدد التحويلات : {{$count}} </span> </button>
			</div>
			<div class="col-xs-3">
				<a href="{{route('downloadProfits')}}"><button class="btn btn-block btn-float btn-float-lg correspondent" style="background-color:#1b926c; color:#fff;" type="button" ><i class="fa fa-file-excel-o"></i> <span>تحميل Excel </span></button></a>
			</div>				
		</div>
	</div>
			<div class="panel-body">
				<div class="tabbable">
					<ul class="nav nav-tabs">
						<!-- supervisors reports  -->
						<li class="active"><a href="#basic-tab1" data-toggle="tab">الأرباح مجمعة باليوم</a></li>
					    <li><a href="#basic-tab3" data-toggle="tab">الأرباح مجمعة بالشهر</a></li>
						<li><a href="#basic-tab4" data-toggle="tab">الأرباح مجمعة بالسنة</a></li>
						<li><a href="#basic-tab2" data-toggle="tab">تفاصيل الأرباح</a></li>
					</ul>

					<div class="tab-content">
                        <!-- ProfitsByDay reports -->
						<div class="tab-pane active" id="basic-tab1">
							<table class="table text-nowrap">
								<thead>
									<tr>
										<th style="width: 50px">الوقت</th>
										<th>المبلغ</th>
										<th>الربح</th>
										<th>الضريبة</th>
										<th>عمولة وصل</th>
									</tr>
								</thead>
								<tbody>
									<?php $currency = setting('site_currency_ar');
                                          $total_day_total_price = 0;
                                          $total_day_value = 0;
                                          $total_day_added_value = 0;
                                          $total_day_wasl_value = 0;
									?>
									@foreach($ProfitsByDay as $dayProfit)
										<tr>
											<td class="text-center">
												<h6 class="no-margin"><small class="display-block text-size-small no-margin">{{$dayProfit->date}}</small></h6>
											</td>
											
											<td>
												<span class="text-semibold">{{round($dayProfit->total_total_price,2)}} {{$currency}}</span>
											</td>
											<td>
												<span class="text-semibold">{{round($dayProfit->total_value,2)}} {{$currency}}</span>
											</td>
											<td>
												<span class="text-semibold">{{round($dayProfit->total_added_value,2)}} {{$currency}}</span>
											</td>
											<td>
												<span class="text-semibold">{{round($dayProfit->total_wasl_value,2)}} {{$currency}}</span>
											</td>
										</tr>
										<?php   
										        $total_day_total_price   += round($dayProfit->total_total_price,2);
										        $total_day_value   += round($dayProfit->total_value,2);
												$total_day_added_value += round($dayProfit->total_added_value,2);
												$total_day_wasl_value  += round($dayProfit->total_wasl_value,2);
										?>
									@endforeach
								</tbody>
								<tfoot>
									<tr>
										<td style="width: 50px"></td>
										<td>اجمالي المبلغ : {{$total_day_total_price}} {{$currency}}</td>
										<td>اجمالي الربح : {{$total_day_value}} {{$currency}}</td>
										<td>اجمالي الضريبة : {{$total_day_added_value}} {{$currency}}</td>
										<td>اجمالي عمولة وصل : {{$total_day_wasl_value}} {{$currency}}</td>
									</tr>
								</tfoot>
									<tr>
										<!-- delete users reports -->
										<td></td>
										<td></td>
										<td></td>
										<!-- pagination -->
										<td>{{$ProfitsByDay->links()}}</td>
									</tr>
							</table>
						</div>

                        <!-- ProfitsByMonth reports -->
						<div class="tab-pane" id="basic-tab3">
							<table class="table text-nowrap">
								<thead>
									<tr>
										<th style="width: 50px">الوقت</th>
										<th>المبلغ</th>
										<th>الربح</th>
										<th>الضريبة</th>
										<th>عمولة وصل</th>
									</tr>
								</thead>
								<tbody>
									<<?php 
                                          $total_month_total_price= 0;
                                          $total_month_value = 0;
                                          $total_month_added_value = 0;
                                          $total_month_wasl_value = 0;
									 ?>
									@foreach($ProfitsByMonth as $monthProfit)
										<tr>
											<td class="text-center">
												<h6 class="no-margin"><small class="display-block text-size-small no-margin">{{$monthProfit->month}}</small></h6>
											</td>
											
											<td>
												<span class="text-semibold">{{round($monthProfit->total_total_price,2)}} {{$currency}}</span>
											</td>
											<td>
												<span class="text-semibold">{{round($monthProfit->total_value,2)}} {{$currency}}</span>
											</td>
											<td>
												<span class="text-semibold">{{round($monthProfit->total_added_value,2)}} {{$currency}}</span>
											</td>
											<td>
												<span class="text-semibold">{{round($monthProfit->total_wasl_value,2)}} {{$currency}}</span>
											</td>
										</tr>
										<?php   
										        $total_month_total_price       += round($monthProfit->total_total_price,2);
										        $total_month_value       += round($monthProfit->total_value,2);
												$total_month_added_value += round($monthProfit->total_added_value,2);
												$total_month_wasl_value  += round($monthProfit->total_wasl_value,2);
										?>
									@endforeach
								</tbody>
								<tfoot>
									<tr>
										<td style="width: 50px"></td>
										<td>اجمالي المبلغ : {{$total_month_total_price}} {{$currency}}</td>
										<td>اجمالي الربح : {{$total_month_value}} {{$currency}}</td>
										<td>اجمالي الضريبة : {{$total_month_added_value}} {{$currency}}</td>
										<td>اجمالي عمولة وصل : {{$total_month_wasl_value}} {{$currency}}</td>
									</tr>
								</tfoot>
									<tr>
										<!-- delete users reports -->
										<td></td>
										<td></td>
										<td></td>
										<!-- pagination -->
										<td>{{$ProfitsByMonth->links()}}</td>
									</tr>
							</table>
						</div>
                        <!-- ProfitsByYear reports -->
						<div class="tab-pane" id="basic-tab4">
							<table class="table text-nowrap">
								<thead>
									<tr>
										<th style="width: 50px">السنة</th>
										<th>المبلغ</th>
										<th>الربح</th>
										<th>الضريبة</th>
										<th>وصل</th>
									</tr>
								</thead>
								<tbody>
									<<?php 
                                          $total_year_total_price = 0;
                                          $total_year_value = 0;
                                          $total_year_added_value = 0;
                                          $total_year_wasl_value = 0;
									 ?>
									@foreach($ProfitsByYear as $yearProfit)
										<tr>
											<td class="text-center">
												<h6 class="no-margin"><small class="display-block text-size-small no-margin">{{$yearProfit->year}}</small></h6>
											</td>
											
											<td>
												<span class="text-semibold">{{round($yearProfit->total_total_price,2)}} {{$currency}}</span>
											</td>
											<td>
												<span class="text-semibold">{{round($yearProfit->total_value,2)}} {{$currency}}</span>
											</td>
											<td>
												<span class="text-semibold">{{round($yearProfit->total_added_value,2)}} {{$currency}}</span>
											</td>
											<td>
												<span class="text-semibold">{{round($yearProfit->total_wasl_value,2)}} {{$currency}}</span>
											</td>
										</tr>
										<?php   
										        $total_year_total_price   += round($yearProfit->total_total_price,2);
										        $total_year_value       += round($yearProfit->total_value,2);
												$total_year_added_value += round($yearProfit->total_added_value,2);
												$total_year_wasl_value  += round($yearProfit->total_wasl_value,2);
										?>
									@endforeach
								</tbody>
								<tfoot>
									<tr>
										<td style="width: 50px"></td>
										<td>اجمالي المبلغ : {{$total_year_total_price}} {{$currency}}</td>
										<td>اجمالي الربح : {{$total_year_value}} {{$currency}}</td>
										<td>اجمالي الضريبة : {{$total_year_added_value}} {{$currency}}</td>
										<td>اجمالي عمولة وصل : {{$total_year_wasl_value}} {{$currency}}</td>
									</tr>
								</tfoot>
									<tr>
										<!-- delete users reports -->
										<td></td>
										<td></td>
										<td></td>
										<!-- pagination -->
										<td>{{$ProfitsByYear->links()}}</td>
									</tr>
							</table>
						</div>						

                        <!-- ProfitsDetails reports -->
						<div class="tab-pane" id="basic-tab2">
							<table class="table text-nowrap">
								<thead>
									<tr>
										<th style="width: 50px">الوقت</th>
										<th style="width: 300px;">العميل</th>
										<th>المبلغ</th>
										<th>الربح</th>
										<th>الضريبة</th>
										<th>عمولة وصل</th>
									</tr>
								</thead>
								<tbody>
									@foreach($ProfitsDetails as $profit)
										<tr>
											<td class="text-center">
												<h6 class="no-margin"><small class="display-block text-size-small no-margin">{{date('Y-m-d H:i',strtotime($profit->created_at))}}</small></h6>
											</td>
											<td>
												<div class="media-left media-middle">
													<span class="btn bg-teal-400 btn-rounded btn-icon btn-xs">
														<img class="img-circle" src="{{($profit->provider)?url('img/user/'.$profit->provider->avatar):url('img/user/default.png')}}">
													</span>
												</div>

												<div class="media-body">
													<a href="{{url('admin/userProfile/'.$profit->user_id)}}" class="display-inline-block text-default text-semibold letter-icon-title">{{($profit->provider)?$profit->provider->name:''}}</a>
													<div class="text-muted text-size-small"><span class="status-mark border-coral position-left"></span>0{{($profit->provider)?$profit->provider->phone:''}}</div>
												</div>
											</td>
											<td>
												<span class="text-semibold">{{round($profit->total_price,2)}} {{$currency}}</span>
											</td>
											<td>
												<span class="text-semibold">{{round($profit->value,2)}} {{$currency}}</span>
											</td>
											<td>
												<span class="text-semibold">{{round($profit->added_value,2)}} {{$currency}}</span>
											</td>
											<td>
												<span class="text-semibold">{{round($profit->wasl_value,2)}} {{$currency}}</span>
											</td>
										</tr>
									@endforeach
								</tbody>
									<tr>
										<!-- delete users reports -->
										<td></td>
										<td></td>
										<td></td>
										<!-- pagination -->
										<td>{{$ProfitsDetails->links()}}</td>
									</tr>
							</table>

						</div>
					</div>
				</div>
			</div>
		</div>

	</div>
</div>


<!-- javascript -->
@section('script')
<script type="text/javascript">
	//stay in current tab after reload
	$(function() { 
	    // for bootstrap 3 use 'shown.bs.tab', for bootstrap 2 use 'shown' in the next line
	    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
	        // save the latest tab; use cookies if you like 'em better:
	        localStorage.setItem('lastTab', $(this).attr('href'));
	    });

	    // go to the latest tab, if it exists:
	    var lastTab = localStorage.getItem('lastTab');
	    if (lastTab) {
	        $('[href="' + lastTab + '"]').tab('show');
	    }
	});

</script>

@endsection
<!-- /javascript -->

@endsection