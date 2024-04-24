@extends('teacher_apis_dashboarddashboard.layout.master')
	@section('title')
	 وحدات كتاب {{$book->name_ar}} 
	@endsection

@section('content')
<div class="panel panel-flat">
	<div class="panel-heading">
		<h5 class="panel-title">قائمة وحدات كتاب {{$book->name_ar}}</h5>
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
				<button class="btn bg-blue btn-block btn-float btn-float-lg openAddModal" type="button" data-toggle="modal" data-target="#exampleModal"><i class="icon-plus3"></i> <span>اضافة وحده</span></button>
			</div>
			<div class="col-xs-3">
				<button class="btn bg-purple-300 btn-block btn-float btn-float-lg" type="button"><i class="icon-list-numbered"></i> <span>عدد الوحدات : {{count($units)}} </span> </button>
			</div>	
		</div>
	</div>
	<!-- /buttons -->
	@csrf
	<table class="table datatable-basic">
		<thead>
			<tr>
				<th>#</th>
				<th>الاسم بالعربية</th>
				<th>الاسم بالانجليزية</th>
				<th>اسم الكتاب</th>
				<th>عدد الدروس</th>
				<th>تاريخ الاضافه</th>
				<th>التحكم</th>
			</tr>
		</thead>
		<tbody>
			@foreach($units as $unit)
				<tr>
					<td>{{$loop->iteration}}</td>
					<td>{{$unit->name_ar}}</td>
					<td>{{$unit->name_en}}</td>
					<td>{{($unit->book)?$unit->book->name_ar : ''}}</td>
					<td>{{$unit->num_lessons}}</td>
					<td>{{date('Y-m-d H:i',strtotime($unit->created_at))}}</td>
					<td>
					<ul class="icons-list">
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">
								<i class="icon-menu9"></i>
							</a>

							<ul class="dropdown-menu dropdown-menu-right">
								<li>
									<a href="{{url('teacher/teacherBookLessons/'.$unit->id)}}" />
									<i class="glyphicon glyphicon-th-list"></i>دروس الوحده
									</a>
								</li>
								<!-- edit button -->
								<li>
									<a href="#" data-toggle="modal" data-target="#exampleModal2" class="openEditmodal" 
									data-id="{{$unit->id}}"
									data-namear="{{$unit->name_ar}}"
									data-nameen="{{$unit->name_en}}"
									data-numlessons="{{$unit->num_lessons}}"
									/>
									<i class="icon-pencil7"></i>تعديل
									</a>
								</li>
								<form action="{{route('DeleteTeacherBookUnit')}}" method="POST" id="DeleteCouponForm">
									{{csrf_field()}}
									<input type="hidden" name="id" value="{{$unit->id}}">
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
	        <h5 class="modal-title" id="exampleModalLabel">أضافة وحده جديدة</h5>
	      </div>
	      <div class="modal-body">
	        <div class="row">
	        	<form action="{{route('createTeacherBookUnit')}}" method="POST"  enctype="multipart/form-data" >
	        		{{csrf_field()}}
	        		<input type="hidden" name="book_id" value="{{$book->id}}">

	        		<div class="row">
		        		<div class="col-sm-12">
                            <div class="col-sm-4">
		        				<label>الاسم بالعربية</label>
		        		    </div>
		        			<div class="col-sm-8">
		        				<input type="text" name="name_ar" class="form-control" placeholder="الاسم بالعربية"/>
						    </div>
						    <div class="col-sm-4">
		        				<label>الاسم بالانجليزية</label>
		        		    </div>
		        			<div class="col-sm-8">
		        				<input type="text" name="name_en" class="form-control" placeholder="الاسم بالانجليزية"/>
						    </div>
						    
						    <div class="col-sm-4">
		        				<label>عدد الدروس</label>
		        		    </div>
		        			<div class="col-sm-8">
		        				<input type="number" name="num_lessons" class="form-control" placeholder="عدد الدروس" step="1"/>
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
	        <h5 class="modal-title" id="exampleModalLabel"> تعديل بيانات الوحده </h5>
	      </div>
	      <div class="modal-body">
	        	<form action="{{route('updateTeacherBookUnit')}}" method="POST"  enctype="multipart/form-data" >
	        		{{csrf_field()}}
	        		<input type="hidden" name="id" value="">
	        		<input type="hidden" name="edit_book_id" value="{{$book->id}}">
	        		<div class="row">
		        		<div class="col-sm-12">
		        			
                            <div class="col-sm-4">
		        				<label>الاسم بالعربية</label>
		        		    </div>
		        			<div class="col-sm-8">
		        				<input type="text" name="edit_name_ar" class="form-control" placeholder="الاسم بالعربية"/>
						    </div>
						    <div class="col-sm-4">
		        				<label>الاسم بالانجليزية</label>
		        		    </div>
		        			<div class="col-sm-8">
		        				<input type="text" name="edit_name_en" class="form-control" placeholder="الاسم بالانجليزية"/>
						    </div>
						    
						    <div class="col-sm-4">
		        				<label>عدد الدروس</label>
		        		    </div>
		        			<div class="col-sm-8">
		        				<input type="number" name="edit_num_lessons" class="form-control" placeholder="عدد الدروس" step="1"/>
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
		var id             = $(this).data('id')
		var name_ar        = $(this).data('namear')
		var name_en        = $(this).data('nameen')
		var num_lessons    = $(this).data('numlessons')
		//set values in modal inputs
		$("input[name='id']")          .val(id)
		$("input[name='edit_name_ar']")   .val(name_ar)
		$("input[name='edit_name_en']")   .val(name_en)
		$("input[name='edit_num_lessons']")   .val(num_lessons)
			
});

	$('.generalDelete').on('click',function(e){ 
		var result = confirm('هل تريد استمرار عملية الحذف ؟ ');
		if(result == false){
			e.preventDefault();
		}
	});
</script>
@endsection