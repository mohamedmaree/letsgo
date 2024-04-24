@extends('dashboard.layout.master')
	@section('title')
	  الضمانات
	@endsection
@section('content')
<div class="panel panel-flat">
	<div class="panel-heading">
		<h5 class="panel-title">الضمانات </h5>
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
				<button class="btn bg-blue btn-block btn-float btn-float-lg openAddModal" type="button" data-toggle="modal" data-target="#exampleModal"><i class="icon-plus3"></i> <span>اضافة ضمان</span></button>
			</div>			
			<div class="col-xs-3">
				<button class="btn bg-purple-300 btn-block btn-float btn-float-lg" type="button"><i class="icon-list-numbered"></i> <span>عدد الضمانات : {{count($guarantees)}} </span> </button>
			</div>
		</div>
	</div>
	<!-- /buttons -->

	<table class="table datatable-basic">
		<thead>
			<tr>
				<th>#</th>
				<th>من تاريخ </th>
				<th>من وقت </th>
				<th>الي تاريخ</th>
				<th>الي وقت </th>
				<th>عدد الرحلات</th>
				<th>عدد المستخدمين</th>
				<th> تم الاستخدام</th>
				<th>قيمة الضمان</th>
				<th>تاريخ الاضافة</th>
				<th>التحكم</th>
			</tr>
		</thead>
		<tbody>
			@foreach($guarantees as $guarantee)
				<tr>
					<td>{{$guarantee->id}}</td>
					<td>{{$guarantee->from_date}}</td>
					<td>{{date('H:i',strtotime($guarantee->from_time))}}</td>
					<td>{{$guarantee->to_date}}</td>
					<td>{{date('H:i',strtotime($guarantee->to_time))}}</td>
					<td>{{$guarantee->num_orders}}</td>
					<td>{{$guarantee->num_users}}</td>
					<td>{{$guarantee->num_used}}</td>
					<td>{{$guarantee->guarantee}}</td>
					<td>{{date('Y-m-d H:i',strtotime($guarantee->created_at))}}</td>
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
										data-id="{{$guarantee->id}}" 
										data-descriptionar="{{$guarantee->description_ar}}" 
									    data-descriptionen="{{$guarantee->description_en}}" 
										data-fromdate="{{$guarantee->from_date}}" 
										data-fromtime="{{$guarantee->from_time}}" 
										data-todate="{{$guarantee->to_date}}"
										data-totime="{{$guarantee->to_time}}"
										data-numorders="{{$guarantee->num_orders}}"
										data-numusers="{{$guarantee->num_users}}"
										data-guarantee="{{$guarantee->guarantee}}"
									>
									<i class="icon-pencil7"></i>تعديل
									</a>
								</li>
								<!-- delete button -->
								<form action="{{route('DeleteGuarantees')}}" method="POST">
									{{csrf_field()}}
									<input type="hidden" name="id" value="{{$guarantee->id}}">
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

	<!-- Add workstage Modal -->
	<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="exampleModalLabel">أضافة ضمان جديدة</h5>
	      </div>
	      <div class="modal-body">
	        <div class="row">
	        	<form action="{{route('createGuarantees')}}" id="addplan" method="post" enctype="multipart/form-data">
	        		{{csrf_field()}}
	        		<div class="row">
						<div class="col-sm-4">
	        				<label>الوصف بالعربية</label>
	        		    </div>
		        		<div class="col-sm-8" >
		        			<input type="text" name="description_ar" class="form-control" placeholder="الوصف بالعربية">
		        		</div>
                        <div class="col-sm-4">
	        				<label>الوصف بالانجليزية</label>
	        		    </div>
		        		<div class="col-sm-8" >
		        			<input type="text" name="description_en" class="form-control" placeholder="الوصف بالانجليزية">
		        		</div>	
                        <div class="col-sm-4">
	        				<label>من تاريخ</label>
	        		    </div>
		        		<div class="col-sm-8" >
		        			<input type="date" name="from_date" class="form-control" placeholder="">
		        		</div>
                        <div class="col-sm-4">
	        				<label>من وقت</label>
	        		    </div>
		        		<div class="col-sm-8" >
		        			<input type="time" name="from_time" class="form-control" placeholder="">
		        		</div>	
		        		<div class="col-sm-4">
	        				<label>الي تاريخ</label>
	        		    </div>
		        		<div class="col-sm-8" >
		        			<input type="date" name="to_date" class="form-control" placeholder="">
		        		</div>
                        <div class="col-sm-4">
	        				<label>الي وقت</label>
	        		    </div>
		        		<div class="col-sm-8" >
		        			<input type="time" name="to_time" class="form-control" placeholder="">
		        		</div>	
                        <div class="col-sm-4">
	        				<label>عدد الرحلات</label>
	        		    </div>
		        		<div class="col-sm-8" >
		        			<input type="number" name="num_orders" class="form-control" placeholder="عدد الرحلات" />
		        		</div>	
						<div class="col-sm-4">
	        				<label>عدد المستخدمين</label>
	        		    </div>
		        		<div class="col-sm-8" >
		        			<input type="number" name="num_users" class="form-control" placeholder="عدد المستخدمين" />
		        		</div>					
                        <div class="col-sm-4">
	        				<label>قيمة الضمان</label>
	        		    </div>		        	
		        		<div class="col-sm-8">
		        			<input type="number" name="guarantee" class="form-control" placeholder="قيمة الضمان" />
		        		</div>		        		
	        		</div>

					<div class="row"> 
				      <div class="col-sm-12" style="margin-top: 10px">
				      	<button type="submit" class="btn btn-primary" > اضافة</button>
				        <button type="button" class="btn btn-secondary" data-dismiss="modal">أغلاق</button>
				      </div>
				    </div>
	        	</form>
	        </div>
	      </div>

	    </div>
	  </div>
	</div>
	<!-- /Add workstage Modal -->

	<!-- Edit workstage Modal -->
	<div class="modal fade" id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="exampleModalLabel"> تعديل الضمان</h5>
	      </div>
	      <div class="modal-body">
	        	<form action="{{route('updateGuarantees')}}" method="post" enctype="multipart/form-data">
	        		{{csrf_field()}}
	        		<input type="hidden" name="id" value="">
	        		<div class="row">
						<div class="col-sm-4">
	        				<label>الوصف بالعربية</label>
	        		    </div>
		        		<div class="col-sm-8" >
		        			<input type="text" name="edit_description_ar" class="form-control" placeholder="الوصف بالعربية">
		        		</div>
                        <div class="col-sm-4">
	        				<label>الوصف بالانجليزية</label>
	        		    </div>
		        		<div class="col-sm-8" >
		        			<input type="text" name="edit_description_en" class="form-control" placeholder="الوصف بالانجليزية">
		        		</div>	

                        <div class="col-sm-4">
	        				<label>من تاريخ</label>
	        		    </div>
		        		<div class="col-sm-8" >
		        			<input type="date" name="edit_from_date" class="form-control" placeholder="">
		        		</div>
                        <div class="col-sm-4">
	        				<label>من وقت</label>
	        		    </div>
		        		<div class="col-sm-8" >
		        			<input type="time" name="edit_from_time" class="form-control" placeholder="">
		        		</div>	
		        		<div class="col-sm-4">
	        				<label>الي تاريخ</label>
	        		    </div>
		        		<div class="col-sm-8" >
		        			<input type="date" name="edit_to_date" class="form-control" placeholder="">
		        		</div>
                        <div class="col-sm-4">
	        				<label>الي وقت</label>
	        		    </div>
		        		<div class="col-sm-8" >
		        			<input type="time" name="edit_to_time" class="form-control" placeholder="">
		        		</div>	
                        <div class="col-sm-4">
	        				<label>عدد الرحلات</label>
	        		    </div>
		        		<div class="col-sm-8" >
		        			<input type="number" name="edit_num_orders" class="form-control" placeholder="عدد الرحلات" />
		        		</div>	
						<div class="col-sm-4">
	        				<label>عدد المستخدمين</label>
	        		    </div>
		        		<div class="col-sm-8" >
		        			<input type="number" name="edit_num_users" class="form-control" placeholder="عدد المستخدمين" />
		        		</div>					
                        <div class="col-sm-4">
	        				<label>قيمة الضمان</label>
	        		    </div>		        	
		        		<div class="col-sm-8">
		        			<input type="number" name="edit_guarantee" class="form-control" placeholder="قيمة الضمان" />
		        		</div>		        		
	        		</div>

					<div class="row"> 
				      <div class="col-sm-12" style="margin-top: 10px">
				      	<button type="submit" class="btn btn-primary" >حفظ التعديلات</button>
				        <button type="button" class="btn btn-secondary" data-dismiss="modal">أغلاق</button>
				      </div>
				    </div>
	        	</form>
			</div>
	      </div>
	    </div>
	  </div>
	</div>
	<!-- /Edit user Modal -->

</div>

<!-- javascript -->
@section('script')
<script type="text/javascript" src="{{asset('dashboard/js/plugins/tables/datatables/datatables.min.js')}}"></script>
<!-- <script type="text/javascript" src="{{asset('dashboard/js/plugins/forms/selects/select2.min.js')}}"></script> -->
<script type="text/javascript" src="{{asset('dashboard/js/pages/datatables_basic.js')}}"></script>
@endsection



<script type="text/javascript">
	$('.openEditmodal').on('click',function(){
		//get valus 
		var id               = $(this).data('id')
		var description_ar        = $(this).data('descriptionar')
		var description_en        = $(this).data('descriptionen')
		var from_date        = $(this).data('fromdate')
		var from_time        = $(this).data('fromtime')
		var to_date          = $(this).data('todate')
		var to_time          = $(this).data('totime')
		var num_orders       = $(this).data('numorders')
		var num_users       = $(this).data('numusers')
		var guarantee        = $(this).data('guarantee')

		//set values in modal inputs
		$("input[name='id']")               .val(id)
		$("input[name='edit_description_ar']")    .val(description_ar)
		$("input[name='edit_description_en']")    .val(description_en)
		$("input[name='edit_from_date']")   .val(from_date)
		$("input[name='edit_from_time']")   .val(from_time)
		$("input[name='edit_to_date']")     .val(to_date)
		$("input[name='edit_to_time']")     .val(to_time)		
		$("input[name='edit_num_orders']")  .val(num_orders)		
		$("input[name='edit_num_users']")  .val(num_users)		
		$("input[name='edit_guarantee']")   .val(guarantee)	
				
	})

	$('.generalDelete').on('click',function(e){ 
		var result = confirm('هل تريد استمرار عملية الحذف ؟ ');
		if(result == false){
			e.preventDefault();
		}
	});

</script>
@endsection