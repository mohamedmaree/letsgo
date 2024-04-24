@extends('dashboard.layout.master')
	@section('title')
	تعاملات المحفظة الالكترونية 
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
				<h6 class="panel-title"> تعاملات المحفظة الالكترونية {{$currentuser->name}}</h6>
				<div class="heading-elements">
					<ul class="icons-list">
                		<li><a data-action="reload"></a></li>
                	</ul>
            	</div>
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-xs-3">
						<a href="{{url('admin/downloadadminUserPayments/'.$currentuser->id)}}"><button class="btn btn-block btn-float btn-float-lg correspondent" style="background-color:#1b926c; color:#fff;" type="button" ><i class="glyphicon glyphicon-download"></i> <span>تحميل Excel </span></button></a>
					</div>				
				</div>
			</div>
			<div class="panel-body">
				<div class="tabbable">
					<ul class="nav nav-tabs">
						<!-- supervisors reports  -->
						<li class="active"><a href="#basic-tab1" data-toggle="tab">التعاملات </a></li>
						<!-- users reports -->
						<!-- <li><a href="#basic-tab1" data-toggle="tab">تقارير الاعضاء</a></li> -->
					</ul>

					<div class="tab-content">

						<!-- users reports -->
						<div class="tab-pane active" id="basic-tab1">
							<table class="table text-nowrap">
								<thead>

									<tr>
										<th style="width: 50px">الوقت</th>
										<th style="width: 300px;">الطرف الاول</th>
										<th>العملية</th>
										<th style="width: 300px;">الطرف الثاني</th>
										<th>النوع </th>
										<th>المبلغ </th>
									</tr>
								</thead>
								<tbody>
									<?php $firstuser=''; $firstuseravatar=''; $firstusertype=''; ?>
									<?php $seconduser=''; $seconduseravatar=''; $secondusertype=''; ?>
									@foreach($payments as $payment)
									    <?php $seconduser = ($payment->second_user_id == 0)? setting('site_title') : (($payment->seconduser)? $payment->seconduser->name:'المستخدم غير موجود');?>
									    <?php $seconduseravatar = ($payment->second_user_id == 0)? asset('dashboard/uploads/setting/site_logo/'.setting('site_logo')) : (($payment->seconduser)? url('img/user/'.$payment->seconduser->avatar):url('img/user/default.png'));?>
									    <?php $secondusertype = ($payment->second_user_id == 0)? 'التطبيق' : (($payment->seconduser)? (($payment->seconduser->captain=='true')?'كابتن':'عميل'):'');?>
									    <?php $firstuser = ($payment->user_id == 0)? setting('site_title') : (($payment->user)? $payment->user->name:'المستخدم غير موجود');?>
									    <?php $firstuseravatar = ($payment->user_id == 0)? asset('dashboard/uploads/setting/site_logo/'.setting('site_logo')) : (($payment->user)? url('img/user/'.$payment->user->avatar):url('img/user/default.png'));?>
									    <?php $firstusertype = ($payment->user_id == 0)? 'التطبيق' : (($payment->user)? (($payment->user->captain=='true')?'قائد':'عميل'):'');?>
										
										<?php $type = ($payment->type == 'add' || ($payment->type == 'subtract' && ($payment->operation == 'balance_transfer' || $payment->operation == 'guarantee' || $payment->operation == 'reward' || $payment->operation == 'order_price') && $payment->user_id != $currentuser->id))?'add':'subtract';?>									
										<tr style="{{($type=='add')?'background-color: #a7fe80;':'background-color: #fbc7c7;'}}">
											<td class="text-center">
												<h6 class="no-margin"><small class="display-block text-size-small no-margin">{{date('Y-m-d H:i',strtotime($payment->created_at))}}</small></h6>
											</td>
											<td>
												<div class="media-left media-middle">
													<span class="btn bg-teal-400 btn-rounded btn-icon btn-xs">
														<img class="img-circle" src="{{$firstuseravatar}}">
													</span>
												</div>

												<div class="media-body">
													<a href="#" class="display-inline-block text-default text-semibold letter-icon-title">{{$firstuser}}</a>
													<div class="text-muted text-size-small"><span class="status-mark border-coral position-left"></span>{{$firstusertype}}</div>
												</div>
											</td>
											<td>
												<span class="text-semibold">
							                            {{ trans('user.'.$payment->operation) }}
												</span>
											</td>											
											<td>
												<div class="media-left media-middle">
													<span class="btn bg-teal-400 btn-rounded btn-icon btn-xs">
														<img class="img-circle" src="{{$seconduseravatar}}">
													</span>
												</div>

												<div class="media-body">
													<a href="#" class="display-inline-block text-default text-semibold letter-icon-title">{{$seconduser}}</a>
													<div class="text-muted text-size-small"><span class="status-mark border-coral position-left"></span>{{$secondusertype}}</div>
												</div>
											</td>
											<td>
												<span class="text-semibold">{{($type == 'add')?'أضافة':'خصم'}}</span>
											</td>
											<td>
												<span class="text-semibold">{{round($payment->amount,2)}} {{($payment->country)?$payment->country->currency_ar:setting('site_currency_ar')}}</span>
											</td>
										</tr>
									@endforeach
								</tbody>
							</table>
						</div>
					</div>					
				</div>
			</div>
		</div>
							{{$payments->links()}}
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

	$('.generalDelete').on('click',function(e){ 
		var result = confirm('هل تريد استمرار عملية الحذف ؟ ');
		if(result == false){
			e.preventDefault();
		}
	});	
</script>

@endsection
<!-- /javascript -->

@endsection