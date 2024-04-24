@extends('dashboard.layout.master')
    @section('title')
	 {{$profile->name}}
	@endsection

    @section('style')
    	<style>
	    	.usermetaimg {
	    		width: 160px;
				height: 150px;
				border-radius: 10px;
	    	}

    		.info p {
    			font-size: 18px;
				color: #191919;
    		}

    		.info p span {
    			font-size: 16px;
				color: #2bb673;
    		}

    		.SMS {
    			color: #2bb673;
				font-size: 16px;
				cursor: pointer;
    		}

    	</style>

	@endsection
@section('content')

<div class="row">
	<div class="col-md-4">
		<div class="panel panel-flat">
			<div class="panel-body">
				<div class="row">
					<div class="col-md-4">
						<img class="usermetaimg" src="{{asset('img/user/'.$profile->avatar)}}" />
					</div>
				</div>	

			</div>
		</div>	
	</div>

	<div class="col-md-8">
		<div class="panel panel-flat">
			<div class="panel-body">
				<div class="row">
					<div class="col-md-12">
						<div class="info">
							<div class="row">
								<div class="col-md-12">
									<p> <i class="fa fa-car"></i> النوع : <span> {{($profile->captain=='true')?'قائد':'عميل'}}</span> </p>
									<p>
										<i class="fa fa-user"></i>
									 	الاسم : <span> {{$profile->name}} ({{$profile->pin_code}})</span> 
						 			</p>
									<p> <i class="fa fa-envelope"></i> الايميل : <span> {{$profile->email}} </span> </p>
									<p> <i class="fa fa-mobile"></i> الجوال : <span> {{'0'.$profile->phone}}
										<a href="#" data-toggle="modal" data-target="#exampleModal4" class="SendMessageUser" 
											data-id="{{$profile->id}}"
											data-name="{{$profile->name}}" 
										    data-email="{{$profile->email}}" 
											data-phone="0{{$profile->phone}}">
										( مراسلة )

										</a>
										 
									</span> </p>
								</div>

							</div>
						</div>
					</div>

				</div>	

			</div>
		</div>	
	</div>	
</div>	

<div class="row">
	<div class="col-md-12">
		<div class="panel panel-flat">
			<div class="panel-body">
				<div class="row">
					<div class="col-md-6">
						<p> <i class="fa fa-globe"></i> الدولة : <span> {{($profile->country)?$profile->country->name_ar:''}} </span> </p>
						<p> <i class="fa fa-map-marker"></i> المدينة : <span> {{($profile->city)?$profile->city->name_ar:''}} </span> </p>
						<p> <i class="fa fa-map-marker"></i> الدولة الحالية : <span> {{($profile->currentCountry)?$profile->currentCountry->name_ar:''}} </span> </p>
						<p> <i class="fa fa-map-marker"></i> العنوان : <span> {{$profile->address}} </span> </p>
						<p> <i class="fa fa-user"></i> الحالة : <span> 
							@if($profile->active == 'pending')
                                غير نشط
							@elseif($profile->active == 'active')
                               نشط
							@else
                               محظور
							@endif
   
						     </span> 
						</p>
						<p> <i class="glyphicon glyphicon-bitcoin"></i> النقاط : <span> {{$profile->points}} </span> </p>
						<p> <i class="fa fa-car"></i> عدد الرحلات : <span> {{($profile->captain == 'true')?$profile->num_done_orders:$profile->num_user_orders}} </span> </p>

					</div>

					<div class="col-md-6">
						<p> <i class="glyphicon glyphicon-usd"></i> الرصيد : <span> {{round($profile->balance,2)}} {{($profile->country)?$profile->country->currency_ar:''}} </span> </p>
                       <?php $rate = ( $profile->num_rating > 0 )? round(floatval($profile->rating / $profile->num_rating),1) : 0;?>
						<p> <i class="fa fa-star"></i> التقييم : <span> {{$rate}} ({{$profile->num_rating}})</span> </p>
															
						<p> <i class="fa fa-car"></i> السيارات : <span> <a href="{{url('admin/captainCars/'.$profile->id)}}"> مشاهدة </a></span> </p>
						<p> <i class="glyphicon glyphicon-tasks"></i> اداء القائد : <span> <a href="{{url('admin/userPerformance/'.$profile->id)}}"> مشاهدة </a></span> </p>
						<p> <i class="glyphicon glyphicon-comment"></i> التعليقات : <span> <a href="{{url('admin/comments/'.$profile->id)}}"> مشاهدة </a></span> </p>
						<p> <i class="glyphicon glyphicon-transfer"></i> أرشيف حسابات القائد : <span> <a href="{{url('admin/captainMoneyHistory/'.$profile->id)}}"> مشاهدة </a></span> </p>
						<p> <i class="glyphicon glyphicon-folder-open"></i> أرشيف الرحلات : <span> <a href="{{url('admin/userOrdersArchive/'.$profile->id)}}"> مشاهدة  </a></span> </p>
					    @if($meta = $profile->userMeta)
						<p> <i class="glyphicon glyphicon-file"></i> طلب العمل كقائد : <span> <a href="{{url('admin/userMeta/'.$meta->id)}}"> مشاهدة </a></span> </p>
						@endif
					</div>
				</div>	
				
			</div>
		</div>	
	</div>

</div>	


<!-- </div> -->

<!-- SMS Modal -->
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
									      	<button type="submit" class="btn btn-primary addCategory">ارسال</button>
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
									      	<button type="submit" class="btn btn-primary addCategory">ارسال</button>
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
									      	<button type="submit" class="btn btn-primary addCategory">ارسال</button>
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
<!-- /SMS Modal -->
	
<script type="text/javascript">
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