@extends('dashboard.layout.master')
	@section('title')
	القاده
	@endsection

@section('content')
<div class="panel panel-flat">
	<div class="panel-heading">
		<h5 class="panel-title">قائمة القادة الغير متصلين</h5>
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
				<button class="btn bg-blue btn-block btn-float btn-float-lg openAddModal" type="button" data-toggle="modal" data-target="#exampleModal"><i class="icon-plus3"></i> <span>اضافة عضو</span></button>
			</div>
			<div class="col-xs-3">
				<button class="btn bg-purple-300 btn-block btn-float btn-float-lg" type="button"><i class="icon-list-numbered"></i> <span>عدد الاعضاء : {{count($users)}} </span> </button>
			</div>
			<div class="col-xs-3">
				<button class="btn bg-teal-400 btn-block btn-float btn-float-lg correspondent" type="button" data-toggle="modal" data-target="#exampleModal3" ><i class=" icon-station"></i> <span>مراسلة الاعضاء</span></button>
			</div>
			<div class="col-xs-3">
				<button class="btn  btn-block btn-float btn-float-lg correspondent" type="button" id="removeAll"><i class=" icon-trash"></i> <span>حذف المحدد </span></button>
			</div>			
		</div>
	</div>
	<!-- /buttons -->
<form action="{{route('deleteUsers')}}" method="post" id="removeAllForm">
	@csrf
	<table class="table datatable-basic">
		<thead>
			<tr>
				<th>#</th>
				<th>الكود</th>
				<th>الاسم</th>
				<th>الهاتف</th>
				<th>الحالة</th>
				<th>الرصيد</th>
				<th>رصيد المدفوعات الالكترونية</th>
				<th>الرحلات</th>
				<th>التقييم</th>
				<th>اخر نشاط</th>
				<th>تاريخ الاضافه</th>
				<th>التحكم</th>
				<th>
                      <label class="checkbox">
                          <input type="checkbox" id="checkAll"> 
                          <i class="icon-checkbox"></i>
                      </label>
				</th>
			</tr>
		</thead>
		<tbody>
			@foreach($users as $u)
				<tr id="row-{{$u->id}}">
					<td>{{$loop->iteration}}   </td>
					<td>{{$u->pin_code}}</td>
					<td>{{$u->name}}   </td>
					<td>0{{$u->phone}}</td>
                    <td>@if($u->active=='active')
                    	<sub style="color:green;">نشط</sub>
                    	@elseif($u->active=='block')
                    	<sub style="color:red;">محظور</sub>
                    	@else
                    	<sub style="color:rgba(0, 0, 0, 0.5);">غير نشط</sub>
                    	@endif
                    	<br/>
                    	<?php $blockmsg = checkBlock($u->id); ?>
                    	@if( $blockmsg != 'true')
                    	<sub style="color:red;">{{$blockmsg}}</sub>
                    	@endif
                    </td>
                    <td>{{round($u->balance,2)}} {{($u->country)?$u->country->currency_ar:''}}</td>
                    <td>{{round($u->balance_electronic_payment,2)}} {{($u->country)?$u->country->currency_ar:''}}</td>
                    <td>{{$u->num_done_orders}}</td>
					<td>{{($u->num_rating > 0)? round(floatval($u->rating / $u->num_rating),1) : '0'}}</td>
					<td>{{($u->last_activity)? date('Y-m-d H:i',strtotime($u->last_activity)) : ''}}</td>
					<td>{{date('Y-m-d H:i',strtotime($u->created_at))}}</td>
					<td>
					<ul class="icons-list">
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">
								<i class="icon-menu9"></i>
							</a>

							<ul class="dropdown-menu dropdown-menu-right">
								<!-- user profile-->
								<li>
									<a href="{{url('admin/userProfile/'.$u->id)}}" >
									<i class="glyphicon glyphicon-user"></i>تفاصيل العضو 
									</a>
								</li>
								<li>
									<a href="{{url('admin/userPerformance/'.$u->id)}}" >
									<i class="glyphicon glyphicon-tasks"></i>اداء القائد 
									</a>
								</li>	
								<li>
									<a href="{{url('admin/captainMoneyHistory/'.$u->id)}}" >
									<i class="glyphicon glyphicon-transfer"></i>أرشيف حسابات القائد 
									</a>
								</li>																							
								<!-- edit button -->
								<li>
									<a href="#" data-toggle="modal" data-target="#exampleModal2" class="openEditmodal" 
									data-id="{{$u->id}}" 
									data-phone="0{{$u->phone}}" 
									data-countryid="{{$u->country_id}}" 
									data-email="{{$u->email}}" 
									data-name="{{$u->name}}" 
									data-balance="{{$u->balance}}" 
									data-currency="{{($u->country)?$u->country->currency_ar:setting('site_currency_ar')}}" 
									data-photo="{{$u->avatar}}"
									data-active="{{$u->active}}"
									data-captain="{{$u->captain}}"
									data-permission="{{$u->role}}">
									<i class="icon-pencil7"></i>تعديل
									</a>
								</li>
								<!-- send message button -->
								<li>
									<a href="#" data-toggle="modal" data-target="#exampleModal4" class="SendMessageUser" 
									data-id="{{$u->id}}"
									data-name="{{$u->name}}" 
									data-email="{{$u->email}}" 
									data-phone="{{$u->phone}}">
									<i class=" icon-bubble9"></i>مراسله
									</a>
								</li>				
								
								<li>
									<a href="{{url('admin/captainCars/'.$u->id)}}" >
									<i class="fa fa-car"></i>السيارات
									</a>
								</li>									
								<li>
									<a href="{{url('admin/userOrdersArchive/'.$u->id)}}" >
									<i class="glyphicon glyphicon-folder-open"></i>أرشيف الرحلات
									</a>
								</li>																
								<li>
									<a href="{{url('admin/comments/'.$u->id)}}" >
									<i class="glyphicon glyphicon-comment"></i>التعليقات
									</a>
								</li>
							    @if($meta = $u->userMeta)
								<li>
									<a href="{{url('admin/userMeta/'.$meta->id)}}" >
									<i class="glyphicon glyphicon-file"></i>طلب العمل كقائد
									</a>
								</li>
							    @endif		
								<!-- payments-->
								<li>
									<a href="{{url('admin/adminUserPayments/'.$u->id)}}" >
									<i class="glyphicon glyphicon-euro"></i> تعاملات المحفظة الجارية
									</a>
								</li>	
								<li>
									<a href="{{url('admin/adminUserPaymentsElectronic/'.$u->id)}}" >
									<i class="glyphicon glyphicon-euro"></i> تعاملات المحفظة الالكترونية
									</a>
								</li>							    							
								<!-- block-->
								<!-- <li>
									<a href="#" data-toggle="modal" data-target="#exampleModal6" class="AddBlock" 
										data-id="{{$u->id}}"
									    data-isblocked="{{$blockmsg}}"
										data-name="{{$u->name}}"
										>
									<i class="icon-lock"></i>حظر الرحلات
									</a>
								</li>	 -->																								
								<!-- delete button -->
								@if($u->role != 1)  
								<form action="{{route('delete-user')}}" method="POST" id="deleteUser-{{$u->id}}">
									{{csrf_field()}}
									<input type="hidden" name="id" value="{{$u->id}}">
									<li><button type="button" class="generalDelete reset" id="{{$u->id}}"><i class="icon-trash"></i>حذف</button></li>
								</form>
                                @endif
							</ul>
						</li>
					</ul>
					</td>
					<td>
                        @if($u->role != 1)  
                          <label class="checkbox">
	                          <input type="checkbox" name="deleteids[]" value="{{$u->id}}" > 
	                          <i class="icon-checkbox"></i>
                          </label>
                        @endif
					</td>
				</tr>
			@endforeach
		</tbody>
	</table>
</form>
	<!-- Add user Modal -->
	<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="exampleModalLabel">أضافة قائد جديد</h5>
	      </div>
	      <div class="modal-body">
	        <div class="row">
	        	<form action="{{route('adduser')}}" method="POST" enctype="multipart/form-data">
	        		{{csrf_field()}}

	        		<div class="row">
		        		<div class="col-sm-3 text-center">
		        			<label style="margin-bottom: 0">اختيار صوره</label>
		        			<i class="icon-camera"  onclick="addChooseFile()" style="cursor: pointer;"></i>
		        			<div class="images-upload-block">
		        				<input type="file" name="avatar" class="image-uploader" id="hidden">
		        			</div>
		        		</div>
		        		<div class="col-sm-9">
		        			<input type="text" name="name" class="form-control" placeholder="الاسم" style="margin-bottom: 10px" required/>
		        			<div class="col-sm-8">
		        			   <input type="text" name="phone" class="form-control" placeholder="الهاتف " required/>
		        			</div>
		        			<div class="col-sm-4">
		        			   <select class="form-control" name="country_id" id="country_id">
		        			   	@foreach($countries as $country)
                                  <option value="{{$country->id}}" data-currency="{{$country->currency_ar}}" >{{$country->iso2}}({{$country->phonekey}})</option>
		        			   	@endforeach
		        			   </select>
		        			</div>

		        			<input type="email" name="email" class="form-control" placeholder="البريد الالكتروني ">
		        		</div>
		        		<div class="col-sm-3 text-center">
		        		</div>
		        		<div class="col-sm-7">
		        			<input type="number" name="balance" min="0" class="form-control" placeholder="الرصيد" step="0.01"> 
		        		</div>
		        		<div class="col-sm-2">
                           <label id="add_currency">{{setting('site_currency_ar')}}</label>
		        		</div>	
		        		
	        		</div>

					<div class="row">
		        		<div class="col-sm-6">
		        			<input type="password" name="password" class="form-control" placeholder="كلمة المرور " required/>
		        		</div>
		        		<div class="col-sm-6">
							<select name="role" class="form-control" id="permissions">
								<option > الصلاحية </option>
								@foreach($roles as $role)
									<option value="{{$role->id}}">{{$role->role}}</option>
								@endforeach
							</select>
						</div>
		        	</div>				        		

					<div class="row">						
		        		<div class="col-sm-6">
							<select name="active" class="form-control">
								<option value="active" disabled selected> الحالة </option>
								<option value="active" style="color:green;">نشط</option>
								<option value="pending" style="color:rgba(0, 0, 0, 0.5);">غير نشط</option>
								<option value="block" style="color:red;">حظر</option>
							</select>
						</div>	
		        		<div class="col-sm-6">
                              <label class="checkbox" >
		        			  	<label > قائد</label>
		                          <input type="checkbox" name="captain"> 
		                          <i class="icon-checkbox"></i>
	                          </label>
		        		</div>			        				        		
		        	</div>
			        <div class="col-sm-12" style="margin-top: 10px">
				      	<button type="submit" class="btn btn-primary addCategory" onclick="$(this.form).validate({name: {required: true},{phone: {required: true},{password: {required: true}};this.form.submit(); this.disabled=true;">اضافه</button>
				        <button type="button" class="btn btn-secondary" data-dismiss="modal">أغلاق</button>
			        </div>

	        	</form>
	        </div>
	      </div>

	    </div>
	  </div>
	</div>
	<!-- /Add user Modal -->

	<!-- Edit user Modal -->
	<div class="modal fade" id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="exampleModalLabel"> تعديل قائد : <span class="userName"></span> </h5>
	      </div>
	      <div class="modal-body">
	        	<form action="{{route('updateuser')}}" method="post" enctype="multipart/form-data">
	        		{{csrf_field()}}
	        		<input type="hidden" name="id" value="">
	        		<div class="row">
		        		<div class="col-sm-3 text-center">
		        			<img src="" class="photo" style="width: 120px;height: 120px;cursor: pointer;margin-bottom:10px;" onclick="ChooseFile()">
		        			<input type="file" name="edit_avatar" style="display: none;">
		        		</div>
		        		<div class="col-sm-9" >
		        			<input type="text" name="edit_name" class="form-control" placeholder="الاسم" style="margin-bottom: 10px">
		        			<div class="col-sm-8">
		        			   <input type="text" name="edit_phone" class="form-control" placeholder="الهاتف " required/>
		        			</div>
		        			<div class="col-sm-4">
		        			   <select class="form-control" name="edit_country_id" id="edit_country_id">
		        			   	@foreach($countries as $country)
                                  <option value="{{$country->id}}" data-currency="{{$country->currency_ar}}">{{$country->iso2}}({{$country->phonekey}})</option>
		        			   	@endforeach
		        			   </select>
		        			</div>

		        			<input type="email" name="edit_email" class="form-control" placeholder="البريد الالكتروني ">
		        		</div>
		        		<div class="col-sm-3 text-center">
		        		</div>
		        		<div class="col-sm-8">
		        			<input type="number" name="edit_balance" class="form-control" placeholder="الرصيد" step="0.01" > 
		        		</div><label id="edit_currency"></label>
	        		</div>

					<div class="row">
		        		<div class="col-sm-6">
		        			<input type="password" name="edit_password" class="form-control" placeholder="كلمة المرور "/>
		        		</div>
		        		<div class="col-sm-6">
							<select name="edit_role" class="form-control" id="permissions">
								<option> الصلاحية </option>
								@foreach($roles as $role)
									<option value="{{$role->id}}">{{$role->role}}</option>
								@endforeach
							</select>
						</div>	       		
		        	</div>

					<div class="row">						
		        		<div class="col-sm-6">
							<select name="edit_active" class="form-control" id="editActive">
								<option value="active" disabled selected> الحالة </option>
								<option value="active" style="color:green;">نشط</option>
								<option value="pending" style="color:rgba(0, 0, 0, 0.5);">غير نشط</option>
								<option value="block" style="color:red;">حظر</option>
							</select>
						</div>	
		        		<div class="col-sm-6">
                              <label class="checkbox" >
		        			  	<label > قائد</label>
		                          <input type="checkbox" name="edit_captain"  id="editcaptain"> 
		                          <i class="icon-checkbox"></i>
	                          </label>
		        		</div>			        				        		
		        	</div>
	        		<div class="row"> 
				      <div class="col-sm-12" style="margin-top: 10px">
				      	<button type="submit" class="btn btn-primary" >حفظ التعديلات</button>
				        <button type="button" class="btn btn-secondary" data-dismiss="modal">أغلاق</button>
				      </div>
				    </div>
			      </div>
	        	</form>
	      </div>
	    </div>
	  </div>
	</div>
	<!-- /Edit user Modal -->

	<!-- correspondent for all users Modal -->
	<div class="modal fade" id="exampleModal3" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
			  <div class="modal-header">
			    <h5 class="modal-title" id="exampleModalLabel">مراسلة جميع القادة</span></h5>
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
							    	<form action="{{route('emailCaptains')}}" method="POST" enctype="multipart/form-data">
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
							    	<form action="{{route('SmsMessageProviders')}}" method="POST" enctype="multipart/form-data">
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
							    	<form action="{{route('notificationProviders')}}" method="POST" enctype="multipart/form-data">
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
							    	<form action="{{route('currentUserSms')}}" method="POST" enctype="multipart/form-data">
							    		{{csrf_field()}}
							    		<input type="hidden" name="user_id" value="">
							    		<input type="hidden" name="phone" value="">
							    		<div class="col-sm-12">
							    			<textarea rows="15" name="sms_message" class="form-control" placeholder="نص رسالة الـ SMS "></textarea>
							    		</div>

								        <div class="col-sm-12" style="margin-top: 10px">
									      	<button type="submit" class="btn btn-primary addCategory" >ارسال</button>
									        <button type="button" class="btn btn-secondary" data-dismiss="modal">أغلاق</button>
								        </div>
							    	</form>
							    </div>
							</div>

							<!-- notification -->
							<div class="tab-pane" id="colored-rounded-justified-tab30">
							    <div class="row">
							    	<form action="{{route('currentUserNotification')}}" method="POST" enctype="multipart/form-data">
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
	<!-- /correspondent for one user Modal -->
	<!-- correspondent for one user Modal -->
	<div class="modal fade" id="exampleModal5" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
			  <div class="modal-header">
			    <h5 class="modal-title" id="exampleModalLabel">اضافة كوبون :  <span class="reverName"></span></h5>
			  </div>
			  <div class="modal-body">
			    <div class="row">
					<div class="tabbable">
							<!-- sms -->
							    <div class="row">
							    	<form action="{{route('adminAddCoupon')}}" method="POST">
							    		{{csrf_field()}}
							    		<input type="hidden" name="user_id" value="">
							    		<div class="col-sm-12">
							    		<input type="text" name="coupon" placeholder="كود الكوبون" class="form-control" value="{{old('coupon')}}">
							    		</div>
								        <div class="col-sm-12" style="margin-top: 10px">
									      	<button type="submit" class="btn btn-primary addCategory" onclick="this.form.submit(); this.disabled=true;">اضافة</button>
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
	<!-- /correspondent for one user Modal -->
	<!-- correspondent for one user Modal -->
	<div class="modal fade" id="exampleModal6" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
			  <div class="modal-header">
			    <h5 class="modal-title" id="exampleModalLabel">حظر الرحلات :  <span class="reverName"></span></h5>
			  </div>
			  <div class="modal-body">
			    <div class="row">
					<div class="tabbable">
							<!-- sms -->
							    <div class="row">
							    	<div class="col-sm-6" id="cancelBlock">
								    	<form action="{{route('admincancelBlock')}}" method="POST">
								    		{{csrf_field()}}
								    		<input type="hidden" name="user_id" value="">
										    <button type="submit" class="btn btn-success addCategory" onclick="this.form.submit(); this.disabled=true;">انهاء الحظر</button>
								    	</form>
							    	</div>
							    	<div class="col-sm-6" id="addBlockDiv">
								    	<form action="{{route('admincreateBlock')}}" method="POST">
								    		{{csrf_field()}}
								    		<input type="hidden" name="user_id" value="">
								    		<div class="col-sm-12">
								    		<input type="number" name="num_hours" placeholder="عدد ساعات الحظر" class="form-control" value="{{old('num_hours')}}">
								    		</div>
									        <div class="col-sm-12" style="margin-top: 10px">
										      	<button type="submit" class="btn btn-primary addCategory" onclick="this.form.submit(); this.disabled=true;">حظر</button>
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
	<!-- /correspondent for one user Modal -->		
</div>

<!-- javascript -->
@section('script')
<script type="text/javascript" src="{{asset('dashboard/js/plugins/tables/datatables/datatables.min.js')}}"></script>
<script type="text/javascript" src="{{asset('dashboard/js/plugins/forms/selects/select2.min.js')}}"></script>
<script type="text/javascript" src="{{asset('dashboard/js/pages/datatables_basic.js')}}"></script>
@endsection



<script type="text/javascript">
$("#checkAll").change(function () {
    $("input:checkbox").prop('checked', $(this).prop("checked"));
});

	$("#country_id").change(function(){
	   var currency = $('option:selected', this).attr('data-currency');
	   $("#add_currency").text(currency);
	});

	$("#edit_country_id").change(function(){
	   var currency = $('option:selected', this).attr('data-currency');
	   $("#edit_currency").text(currency);
	});


	$('.openEditmodal').on('click',function(){
		//get valus 
		var id         = $(this).data('id')
		var name       = $(this).data('name')
		var photo      = $(this).data('photo')
		var balance    = $(this).data('balance')
		var currency   = $(this).data('currency')
		var phone      = $(this).data('phone')
		var country_id = $(this).data('countryid')
		var email      = $(this).data('email')
		var permission = $(this).data('permission')
		var active     = $(this).data('active')
		var captain   = $(this).data('captain')
		//set values in modal inputs
		$("input[name='id']")             .val(id)
		$("input[name='edit_name']").val(name)
		$("input[name='edit_phone']")     .val(phone)
		$("input[name='edit_email']")     .val(email)
		$("input[name='edit_balance']")   .val(balance)
		var link = "{{asset('img/user/')}}" +'/'+ photo
		$(".photo").attr('src',link)
		$('.userName').text(name)
		$('#edit_currency').text(currency)
				
		$('#edit_country_id option').each(function(){
			if($(this).val() == country_id){
				$(this).attr('selected','selected')
			}
		});
		$('#permissions option').each(function(){
			if($(this).val() == permission)
			{
				$(this).attr('selected','')
			}
		});
		
		$('#editActive option').each(function(){
			if($(this).val() == active){
				$(this).attr('selected','selected')
			}
		});

		if(captain == true){
			$('#editcaptain').attr('checked','checked');
		}		
		

	})

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

	//open send message modal
	$('.AddcouponUser').on('click',function(){
		var user_id  = $(this).data('id');
		var name     = $(this).data('name');
		$('.reverName').html(name);
		$('input[name="user_id"]').val(user_id);
	})

	//open send message modal
	$('.AddBlock').on('click',function(){
		var user_id     = $(this).data('id');
		var name  = $(this).data('name');
		var isblocked = $(this).data('isblocked')
		$('.reverName').html(name);
		$('input[name="user_id"]').val(user_id);
		if(isblocked != true){
		  $('#cancelBlock').show();
		  $('#addBlockDiv').hide();
		}else{
		  $('#cancelBlock').hide();
		  $('#addBlockDiv').show();
		}
	})		
	
	$('#removeAll').on('click',function(){ 
		var result = confirm('هل تريد استمرار عملية الحذف ؟ ');
		if(result == false){
		    e.preventDefault();		
		}else{
			$('#removeAllForm').submit();
		}
	});

	$('.generalDelete').on('click',function(e){ 
		var result = confirm('هل تريد استمرار عملية الحذف ؟ ');
		var id = $(this).attr('id');
		if(result == false){
			e.preventDefault();
		}else{
		        var jqxhr = $.get("{{url('admin/delete-user/')}}/"+id, function(data) {
		           if(data == 1){
		                $('#row-'+id).hide(500);
		           }
		       });
		}
	});

	
</script>

<!-- other code -->
<script type="text/javascript">

	function ChooseFile(){$("input[name='edit_avatar']").click()}
	function addChooseFile(){$("input[name='avatar']").click()}
</script>
<!-- /other code -->

@endsection