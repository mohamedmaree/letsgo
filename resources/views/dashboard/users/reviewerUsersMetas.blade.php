@extends('dashboard.layout.master')
	@section('title')
	طلبات العمل كقائد
	@endsection
@section('content')
<div class="panel panel-flat">
	<div class="panel-heading">
		<h5 class="panel-title">طلبات العملاء للعمل كقائد</h5>
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
			{{-- <div class="col-xs-8">
				<a href="#"><button class="btn bg-red-300 btn-block btn-float btn-float-lg" type="button"><span style="font-size: xxx-large;">{{Auth::user()->balance}}</span><span> رصيدك  </span> </button></a>
			</div>
			<div class="col-xs-4" style="margin-bottom: 5px;">
				<a href="#"><button class="btn bg-red-300 btn-block btn-float btn-float-lg" type="button"><i class="icon-list-numbered"></i> <span>عدد الطلبات الواردة : {{$all_metas}} </span> </button></a>
			</div>
			<div class="col-xs-4" style="margin-bottom: 5px;">
				<a href="{{route('uncompleteUsersMeta')}}"><button class="btn bg-red-300 btn-block btn-float btn-float-lg" type="button"><i class="icon-list-numbered"></i> <span>عدد الطلبات الغير مكتملة : {{$uncomplete_metas}} </span> </button></a>
			</div>
			<div class="col-xs-4" style="margin-bottom: 5px;">
				<a href="{{route('agreedUsersMeta')}}"><button class="btn bg-red-300 btn-block btn-float btn-float-lg" type="button"><i class="icon-list-numbered"></i> <span>عدد الطلبات المقبولة : {{$agree_metas}} </span> </button></a>
			</div>
			<div class="col-xs-4" style="margin-bottom: 5px;">
				<a href="{{route('pendingUsersMeta')}}"><button class="btn bg-red-300 btn-block btn-float btn-float-lg" type="button"><i class="icon-list-numbered"></i> <span>عدد الطلبات المعلقة : {{$pending_metas}} </span> </button></a>
			</div>
			<div class="col-xs-4" style="margin-bottom: 5px;">
				<a href="{{route('refusedUsersMeta')}}"><button class="btn bg-red-300 btn-block btn-float btn-float-lg" type="button"><i class="icon-list-numbered"></i> <span>عدد الطلبات المرفوضة : {{$refused_metas}} </span> </button></a>
			</div>
			<div class="col-xs-4">
				<a href="{{route('downloadUncompleteUserMeta')}}"><button class="btn btn-block btn-float btn-float-lg correspondent" style="background-color:#1b926c; color:#fff;" type="button" ><i class="fa fa-file-excel-o"></i> <span>تحميل Excel </span></button></a>
			</div>	
			<div class="col-xs-4">
				<button class="btn bg-teal-400 btn-block btn-float btn-float-lg correspondent" type="button" data-toggle="modal" data-target="#exampleModal3" ><i class=" icon-station"></i> <span>مراسلة الغير مكتملين</span></button>
			</div> --}}
		</div>
	</div>
	<!-- /buttons -->

	<table class="table datatable-basic">
		<thead>
			<tr>
				<th>الاسم</th>
				<th>الهاتف</th>
				<th>الدولة</th>
				<th>المدينة</th>
				<th>نوع السيارة</th>
				<th>الحالة</th>
				<th>مراجع البيانات</th>
				<th>الربط مع وصل</th>
				<th>التاريخ</th>
				<th>التحكم</th>
			</tr>
		</thead>
		<tbody>
			@foreach($metas as $m)
				<tr @if($m->seen != 'true') style="background: #e0d0d0" @endif >
					<td><a href="{{route('userMeta',$m->id)}}">{{$m->name}}</a></td>
					<td><a href="{{route('userMeta',$m->id)}}">0{{$m->phone}}</a></td>
					<td>{{($m->country)?$m->country->name_ar : ''}}</a></td>
					<td>{{($m->city)?$m->city->name_ar : ''}}</a></td>
					<td>{{$m->car_type}}</td>
					<td>
						@if($m->status == 'agree')
                            تمت الموافقة
						@elseif($m->status == 'refused')
                            تم الرفض
						@elseif($m->status == 'pending' && $m->complete == 'true')
                            معلق
						@else 
							غير مكتمل		
						@endif
					</td>
					<td>{{($m->reviewer)?$m->reviewer->name : ''}}</a></td>
					<td>
						@if($m->elm_status == 'agree')
                            تمت الموافقة
						@elseif($m->elm_status == 'refuse')
                            تم الرفض
						@else
                            معلق
						@endif
					</td>
					<td>{{$m->created_at->diffForHumans()}}</td>
					<td>
						<ul class="icons-list">
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">
									<i class="icon-menu9"></i>
								</a>
								<ul class="dropdown-menu dropdown-menu-right">
									<!-- send message button -->
									<li>
										<a href="#" data-toggle="modal" data-target="#exampleModal4" class="SendMessageUser" 
											data-id="{{$m->user_id}}"
											data-name="{{$m->name}}" 
											data-email="{{$m->email}}" 
											data-phone="{{$m->phone}}">
										<i class=" icon-bubble9"></i>مراسله
										</a>
									</li>
								
									<!-- delete button -->
									<form action="{{route('deleteUserMeta')}}" method="POST" id="deleteUser-{{$m->id}}">
										{{csrf_field()}}
										<input type="hidden" name="id" value="{{$m->id}}">
										<li><button type="submit" class="generalDelete reset" title="حذف" id="{{$m->id}}"><i class="icon-trash"></i>حذف</button></li>
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
	
	<!-- correspondent for all users Modal -->
	<div class="modal fade" id="exampleModal3" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
			  <div class="modal-header">
			    <h5 class="modal-title" id="exampleModalLabel">مراسلة </span></h5>
			  </div>
			  <div class="modal-body">
			    <div class="row">
					<div class="tabbable">
						<ul class="nav nav-tabs bg-slate nav-tabs-component nav-justified">
							<!-- email -->
							<li><a href="#colored-rounded-justified-tab1" data-toggle="tab">ايميل</a></li>
							<!-- sms -->
							<li class="active"><a href="#colored-rounded-justified-tab2" data-toggle="tab">رساله SMS</a></li>
							<!-- notification -->
							<li><a href="#colored-rounded-justified-tab3" data-toggle="tab">اشعار</a></li>
						</ul>

						<div class="tab-content">
							<!-- email -->
							<div class="tab-pane" id="colored-rounded-justified-tab1">
							    <div class="row">
							    	<form action="{{route('emailUncompleteUsersMeta')}}" method="POST" enctype="multipart/form-data">
							    		{{csrf_field()}}
							    		<div class="col-sm-12">
							    			<textarea rows="15" name="email_message" class="form-control" placeholder="نص رسالة الـ Email "></textarea>
							    		</div>

								        <div class="col-sm-12" style="margin-top: 10px">
									      	<button type="submit" class="btn btn-primary addCategory" onclick="this.form.submit(); this.disabled=true;">ارسال</button>
									        <button type="button" class="btn btn-secondary" data-dismiss="modal">أغلاق</button>
								        </div>
							    	</form>
							    </div>
							</div>															
							<!-- sms -->
							<div class="tab-pane active" id="colored-rounded-justified-tab2">
							    <div class="row">
							    	<form action="{{route('SmsMessageUncompleteUsersMeta')}}" method="POST" enctype="multipart/form-data">
							    		{{csrf_field()}}
							    		<div class="col-sm-12">
							    			<textarea rows="15" name="sms_message" class="form-control" placeholder="نص رسالة الـ SMS "></textarea>
							    		</div>

								        <div class="col-sm-12" style="margin-top: 10px">
									      	<button type="submit" class="btn btn-primary addCategory" onclick="this.form.submit(); this.disabled=true;">ارسال</button>
									        <button type="button" class="btn btn-secondary" data-dismiss="modal">أغلاق</button>
								        </div>
							    	</form>
							    </div>
							</div>

							<!-- noification -->
							<div class="tab-pane" id="colored-rounded-justified-tab3">
							    <div class="row">
							    	<form action="{{route('notificationUncompleteUsersMeta')}}" method="POST" enctype="multipart/form-data">
							    		{{csrf_field()}}
							    		<div class="col-sm-12">
                                            <input type="text" name="notification_title" class="form-control" placeholder="عنوان الاشعار" />
							    		</div>
							    		<div class="col-sm-12">
							    			<textarea rows="15" name="notification_message" class="form-control" placeholder="نص رسالة الـ Notification "></textarea>
							    		</div>

								        <div class="col-sm-12" style="margin-top: 10px">
									      	<button type="submit" class="btn btn-primary addCategory" onclick="this.form.submit(); this.disabled=true;">ارسال</button>
									        <button type="button" class="btn btn-secondary" data-dismiss="modal">أغلاق</button>
								        </div>
							    	</form>
							    </div>
							</div>
						</div>
					</div>
			    </div>
			  </div>

			</div>
		</div>
	</div>
	<!-- /correspondent for all users Modal -->
	<!-- correspondent for one user Modal -->
	<div class="modal fade" id="exampleModal4" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
			  <div class="modal-header">
			    <h5 class="modal-title" id="exampleModalLabel">مراسلة :  <span class="reverName"></span></h5>
			  </div>
			  <div class="modal-body">
			    <div class="row">
					<div class="tabbable">
						<ul class="nav nav-tabs bg-slate nav-tabs-component nav-justified">
							<!-- email -->
							<li><a href="#colored-rounded-justified-tab10" data-toggle="tab">ايميل</a></li>
							<!-- sms -->
							<li class="active"><a href="#colored-rounded-justified-tab20" data-toggle="tab">رساله SMS</a></li>
							<!-- notification -->
							<li><a href="#colored-rounded-justified-tab30" data-toggle="tab">اشعار</a></li>
						</ul>

						<div class="tab-content">
							<!-- email -->
							<div class="tab-pane" id="colored-rounded-justified-tab10">
							    <div class="row">
							    	<form action="{{route('currentUserEmail')}}" method="POST" enctype="multipart/form-data">
							    		{{csrf_field()}}
							    		<input type="hidden" name="email" value="">
							    		<div class="col-sm-12">
							    			<textarea rows="15" name="email_message" class="form-control" placeholder="نص رسالة الـ Email "></textarea>
							    		</div>

								        <div class="col-sm-12" style="margin-top: 10px">
									      	<button type="submit" class="btn btn-primary addCategory" onclick="this.form.submit(); this.disabled=true;">ارسال</button>
									        <button type="button" class="btn btn-secondary" data-dismiss="modal">أغلاق</button>
								        </div>
							    	</form>
							    </div>
							</div>								
							<!-- sms -->
							<div class="tab-pane active" id="colored-rounded-justified-tab20">
							    <div class="row">
							    	<form action="{{route('currentUserSms')}}" method="POST">
							    		{{csrf_field()}}
							    		<input type="hidden" name="user_id" value="">
							    		<input type="hidden" name="phone" value="">
							    		<div class="col-sm-12">
							    			<textarea rows="15" name="sms_message" class="form-control" placeholder="نص رسالة الـ SMS "></textarea>
							    		</div>

								        <div class="col-sm-12" style="margin-top: 10px">
									      	<button type="submit" class="btn btn-primary addCategory" onclick="this.form.submit(); this.disabled=true;">ارسال</button>
									        <button type="button" class="btn btn-secondary" data-dismiss="modal">أغلاق</button>
								        </div>
							    	</form>
							    </div>
							</div>

							<!-- notification -->
							<div class="tab-pane" id="colored-rounded-justified-tab30">
							    <div class="row">
							    	<form action="{{route('currentUserNotification')}}" method="POST">
							    		{{csrf_field()}}
							    		<input name="user_id" value="" type="hidden">
							    		<div class="col-sm-12">
                                            <input type="text" name="notification_title" class="form-control" placeholder="عنوان الاشعار" />
							    		</div>
							    		<div class="col-sm-12">
							    			<textarea rows="15" name="notification_message" class="form-control" placeholder="نص رسالة الـ Notification "></textarea>
							    		</div>

								        <div class="col-sm-12" style="margin-top: 10px">
									      	<button type="submit" class="btn btn-primary addCategory" onclick="this.form.submit(); this.disabled=true;">ارسال</button>
									        <button type="button" class="btn btn-secondary" data-dismiss="modal">أغلاق</button>
								        </div>
							    	</form>
							    </div>
							</div>
						</div>
					</div>
			    </div>
			  </div>

			</div>
		</div>
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
		//open send message modal
		$('.SendMessageUser').on('click',function(){
		var user_id    = $(this).data('id');
		var name       = $(this).data('name');
		var phone      = $(this).data('phone');
		var email      = $(this).data('email');
		$('.reverName').html(name);
		$('input[name="user_id"]').val(user_id);
		$('input[name="phone"]').val(phone);
		$('input[name="email"]').val(email);
	})

</script>

@endsection