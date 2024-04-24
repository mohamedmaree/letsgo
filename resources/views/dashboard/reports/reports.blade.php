@extends('dashboard.layout.master')
	@section('title')
	التقارير
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
				<h6 class="panel-title">التقارير</h6>
				<div class="heading-elements">
					<ul class="icons-list">
                		<li><a data-action="reload"></a></li>
                	</ul>
            	</div>
			</div>

			<div class="panel-body">
				<div class="tabbable">
					<ul class="nav nav-tabs">
						<!-- supervisors reports  -->
						<li class="active"><a href="#basic-tab2" data-toggle="tab">تقارير المشرفين</a></li>
						<!-- users reports -->
						<!-- <li><a href="#basic-tab1" data-toggle="tab">تقارير الاعضاء</a></li> -->
					</ul>

					<div class="tab-content">

						<!-- users reports -->
						<div class="tab-pane" id="basic-tab1">
							<table class="table text-nowrap">
								<thead>

									<tr>
										<th style="width: 50px">الوقت</th>
										<th style="width: 300px;">العضو</th>
										<th>الحدث</th>
									</tr>
								</thead>
								<tbody>
									@foreach($usersReports as $r)
										<tr>
											<td class="text-center">
												<h6 class="no-margin"><small class="display-block text-size-small no-margin">{{date('Y-m-d H:i',strtotime($r->created_at))}}</small></h6>
											</td>
											<td>
												<div class="media-left media-middle">
													<span class="btn bg-teal-400 btn-rounded btn-icon btn-xs">
														<img class="img-circle" src="{{asset('img/user/'.$r->User->avatar)}}">
													</span>
												</div>

												<div class="media-body">
													<a href="#" class="display-inline-block text-default text-semibold letter-icon-title">{{($r->User->name)??'مستخدم غير موجود'}}</a>
													<div class="text-muted text-size-small"><span class="status-mark border-coral position-left"></span>عضو</div>
												</div>
											</td>
											<td>
												<span class="text-semibold">{!!$r->event!!}</span>
											</td>
										</tr>
									@endforeach
								</tbody>
									<tr>
										<!-- delete users reports -->
										<td>
										@if(count($usersReports) > 0)
											<form action="{{route('deleteusersreports')}}" method="post" >
												{{csrf_field()}}
												<button type="submit" class="btn btn-xs btn-danger generalDelete" name="">حذف الكل</button>
											</form>
										@endif
										</td>
										<!-- pagination -->
										<td>{{$usersReports->links()}}</td>
									</tr>
							</table>
						</div>

						<!-- supervisors reports  -->
						<div class="tab-pane active" id="basic-tab2">
							<table class="table text-nowrap">
								<thead>
									<tr>
										<th style="width: 50px">الوقت</th>
										<th style="width: 300px;">المشرف</th>
										<th>الحدث</th>
									</tr>
								</thead>
								<tbody>
									@foreach($supervisorReports as $r)
										<tr>
											<td class="text-center">
												<h6 class="no-margin"><small class="display-block text-size-small no-margin">{{date('Y-m-d H:i',strtotime($r->created_at))}}</small></h6>
											</td>
											<td>
												<div class="media-left media-middle">
													<span class="btn bg-teal-400 btn-rounded btn-icon btn-xs">
														<img class="img-circle" src="{{asset('img/user/'.($r->User->avatar)??'default.png')}}">
													</span>
												</div>

												<div class="media-body">
													<a href="#" class="display-inline-block text-default text-semibold letter-icon-title">{{($r->User->name)??'مستخدم غير موجود'}}</a>
													<div class="text-muted text-size-small"><span class="status-mark border-blue position-left"></span>{{($r->User->Role->role)??''}}</div>
												</div>
											</td>
											<td>
												<span class="text-semibold">{!!$r->event!!}</span>
											</td>
										</tr>
									@endforeach
								</tbody>
								<!-- delete supervisors reports -->
								<tr>
									<td>
									@if(count($supervisorReports) > 0)
										<form action="{{route('deletesupervisorsreports')}}" method="post" >
											{{csrf_field()}}
											<button type="submit" class="btn btn-xs btn-danger generalDelete" name="">حذف الكل</button>
										</form>
									@endif
									</td>
									<!-- pagination -->
									<td>{{$supervisorReports->links()}}</td>
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