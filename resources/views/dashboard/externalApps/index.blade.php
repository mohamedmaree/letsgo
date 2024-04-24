@extends('dashboard.layout.master')
	@section('title')
	تطبيقات مربوطة API
	@endsection

@section('content')
<div class="panel panel-flat">
	<div class="panel-heading">
		<h5 class="panel-title">قائمة التطبيقات المربوطة API</h5>
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
				<button class="btn bg-blue btn-block btn-float btn-float-lg openAddModal" type="button" data-toggle="modal" data-target="#exampleModal"><i class="icon-plus3"></i> <span>اضافة تطبيق</span></button>
			</div>
			<div class="col-xs-3">
				<button class="btn bg-purple-300 btn-block btn-float btn-float-lg" type="button"><i class="icon-list-numbered"></i> <span>عدد التطبيقات : {{count($externalApps)}} </span> </button>
			</div>	
		</div>
	</div>
	<!-- /buttons -->
	@csrf
	<table class="table datatable-basic">
		<thead>
			<tr>
				<th>اسم التطبيق</th>
				<th>اسم العميل</th>
				<th>الايميل</th>
				<th>الهاتف</th>
				<th>app id</th>
				<th>server key</th>
				<th>تاريخ الاضافه</th>
				<th>التحكم</th>
			</tr>
		</thead>
		<tbody>
			@foreach($externalApps as $externalApp)
				<tr>
					<td>{{$externalApp->app_name}}   </td>
					<td>{{$externalApp->client_name}}</td>
					<td>{{$externalApp->email}}</td>
                    <td>{{'0'.$externalApp->phone}}</td>
                    <td>{{$externalApp->app_id}}</td>
                    <td>{{$externalApp->server_key}}</td>
					<td>{{date('Y-m-d H:i',strtotime($externalApp->created_at))}}</td>
					<td>
					<ul class="icons-list">
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">
								<i class="icon-menu9"></i>
							</a>

							<ul class="dropdown-menu dropdown-menu-right">
								<!-- edit button -->
								<li>
									<a href="#" data-toggle="modal" data-target="#exampleModal2" class="openEditmodal" 
									data-id="{{$externalApp->id}}" 
									data-email="{{$externalApp->email}}" 
									data-phone="{{'0'.$externalApp->phone}}" 
									data-clientname="{{$externalApp->client_name}}"
									data-appname="{{$externalApp->app_name}}"
									<i class="icon-pencil7"></i>تعديل
									</a>
								</li>
								<!-- delete button -->
								<form action="{{route('DeleteExternalApp')}}" method="POST" id="DeleteCouponForm">
									{{csrf_field()}}
									<input type="hidden" name="id" value="{{$externalApp->id}}">
									<li><button type="submit" id="delete" class="generalDelete reset" ><i class="icon-trash"></i>حذف</button></li>
								</form>
							</ul>
						</li>
					</ul>
					</td>
				</tr>
			@endforeach
		</tbody>
	</table>
</form>
	<!-- Add coupon Modal -->
	<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="exampleModalLabel">أضافة تطبيق جديد</h5>
	      </div>
	      <div class="modal-body">
	        <div class="row">
	        	<form action="{{route('createExternalApp')}}" method="POST">
	        		{{csrf_field()}}

	        		<div class="row">
		        		<div class="col-sm-12">
						    <div class="col-sm-4">
		        			   <label>اسم التطبيق</label>
		        		    </div>
		        			<div class="col-sm-8">
		        				<input type="text" name="app_name" class="form-control" placeholder="اسم التطبيق " required="">
		        			</div>
		        			 <div class="col-sm-4">
		        			   <label>اسم العميل</label>
		        		    </div>
		        			<div class="col-sm-8">
		        				<input type="text" name="client_name" class="form-control" placeholder="اسم العميل " required="">
		        			</div>
		        			<div class="col-sm-4">
		        			   <label>البريد الالكتروني</label>
		        		    </div>
		        			<div class="col-sm-8">
		        				<input type="email" name="email" class="form-control" placeholder="البريد الالكتروني" required>
		        			</div>
		        			<div class="col-sm-4">
		        			   <label>الهاتف</label>
		        		    </div>
		        			<div class="col-sm-8">
		        				<input type="text" name="phone" class="form-control" placeholder="الهاتف" required>
		        			</div>
		        			<div class="col-sm-4">
		        			   <label>كلمة المرور</label>
		        		    </div>
		        			<div class="col-sm-8">
		        				<input type="password" name="password" class="form-control" placeholder="كلمة المرور">
		        		    </div>			        		
		        		</div>			        		
	        		</div>

			        <div class="col-sm-12" style="margin-top: 10px">
				      	<button type="submit" class="btn btn-primary addCategory">اضافه</button>
				        <button type="button" class="btn btn-secondary" data-dismiss="modal">أغلاق</button>
			        </div>

	        	</form>
	        </div>
	      </div>

	    </div>
	  </div>
	</div>
	<!-- Add coupon Modal -->

	<!-- Edit coupon Modal -->
	<div class="modal fade" id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="exampleModalLabel"> تعديل بيانات التطبيق </h5>
	      </div>
	      <div class="modal-body">
	        	<form action="{{route('updateExternalApp')}}" method="post" >
	        		{{csrf_field()}}
	        		<input type="hidden" name="id" value="">
	        		<div class="row">
		        		<div class="col-sm-12">
						    <div class="col-sm-4">
		        			   <label>اسم التطبيق</label>
		        		    </div>
		        			<div class="col-sm-8">
		        				<input type="text" name="edit_app_name" class="form-control" placeholder="اسم التطبيق " required="">
		        			</div>
		        			 <div class="col-sm-4">
		        			   <label>اسم العميل</label>
		        		    </div>
		        			<div class="col-sm-8">
		        				<input type="text" name="edit_client_name" class="form-control" placeholder="اسم العميل " required="">
		        			</div>
		        			<div class="col-sm-4">
		        			   <label>البريد الالكتروني</label>
		        		    </div>
		        			<div class="col-sm-8">
		        				<input type="email" name="edit_email" class="form-control" placeholder="البريد الالكتروني" required>
		        			</div>
		        			<div class="col-sm-4">
		        			   <label>الهاتف</label>
		        		    </div>
		        			<div class="col-sm-8">
		        				<input type="text" name="edit_phone" class="form-control" placeholder="الهاتف" required>
		        			</div>
		        			<div class="col-sm-4">
		        			   <label>كلمة المرور</label>
		        		    </div>
		        			<div class="col-sm-8">
		        				<input type="password" name="edit_password" class="form-control" placeholder="كلمة المرور">
		        		    </div>			        		
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

</div>

<!-- javascript -->
@section('script')
<script type="text/javascript" src="{{asset('dashboard/js/plugins/tables/datatables/datatables.min.js')}}"></script>
<script type="text/javascript" src="{{asset('dashboard/js/plugins/forms/selects/select2.min.js')}}"></script>
<script type="text/javascript" src="{{asset('dashboard/js/pages/datatables_basic.js')}}"></script>
@endsection



<script type="text/javascript">

	$('.openEditmodal').on('click',function(){
		//get valus 
		var id          = $(this).data('id')
		var email       = $(this).data('email')
		var phone       = $(this).data('phone')
		var client_name = $(this).data('clientname')
		var app_name    = $(this).data('appname')

		//set values in modal inputs
		$("input[name='id']")             .val(id)
		$("input[name='edit_email']")      .val(email)
		$("input[name='edit_phone']")     .val(phone)
		$("input[name='edit_client_name']").val(client_name)
		$("input[name='edit_app_name']")  .val(app_name)

	})

	$('.generalDelete').on('click',function(e){ 
		var result = confirm('هل تريد استمرار عملية الحذف ؟ ');
		if(result == false){
			e.preventDefault();
		}
	});
</script>

@endsection