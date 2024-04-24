@extends('apis_dashboard.layout.master')
	@section('title')
	 دروس {{$unit->name_ar}} 
	@endsection

@section('content')
<div class="panel panel-flat">
	<div class="panel-heading">
		<h5 class="panel-title">قائمة دروس {{$unit->name_ar}}</h5>
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
				<button class="btn bg-blue btn-block btn-float btn-float-lg openAddModal" type="button" data-toggle="modal" data-target="#exampleModal"><i class="icon-plus3"></i> <span>اضافة درس</span></button>
			</div>
			<div class="col-xs-3">
				<button class="btn bg-purple-300 btn-block btn-float btn-float-lg" type="button"><i class="icon-list-numbered"></i> <span>عدد الدروس : {{count($lessons)}} </span> </button>
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
				<th>اسم الوحده</th>
				<th>تاريخ الاضافه</th>
				<th>التحكم</th>
			</tr>
		</thead>
		<tbody>
			@foreach($lessons as $lesson)
				<tr>
					<td>{{$loop->iteration}}</td>
					<td>{{$lesson->title_ar}}</td>
					<td>{{$lesson->title_en}}</td>
					<td>{{($lesson->book)?$lesson->book->name_ar : ''}}</td>
					<td>{{($lesson->unit)?$lesson->unit->name_ar : ''}}</td>
					<td>{{date('Y-m-d H:i',strtotime($lesson->created_at))}}</td>
					<td>
					<ul class="icons-list">
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">
								<i class="icon-menu9"></i>
							</a>

							<ul class="dropdown-menu dropdown-menu-right">
								<li>
									<a href="{{url('teacher/teacherBookLessonPages/'.$lesson->id)}}" />
									<i class="glyphicon glyphicon-th-list"></i>صفحات الدرس
									</a>
								</li>
								<!-- edit button -->
								<li>
									<a href="#" data-toggle="modal" data-target="#exampleModal2" class="openEditmodal" 
									data-id="{{$lesson->id}}"
									data-titlear="{{$lesson->title_ar}}"
									data-titleen="{{$lesson->title_en}}"
									data-bookid="{{$lesson->book_id}}"
									data-unitid="{{$lesson->unit_id}}"
									data-video="{{$lesson->video}}"
									/>
									<i class="icon-pencil7"></i>تعديل
									</a>
								</li>
								<form action="{{route('DeleteTeacherBookLesson')}}" method="POST" id="DeleteCouponForm">
									{{csrf_field()}}
									<input type="hidden" name="id" value="{{$lesson->id}}">
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
	        <h5 class="modal-title" id="exampleModalLabel">أضافة درس جديد</h5>
	      </div>
	      <div class="modal-body">
	        <div class="row">
	        	<form action="{{route('createTeacherBookLesson')}}" method="POST"  enctype="multipart/form-data" >
	        		{{csrf_field()}}
	        		<input type="hidden" name="unit_id" value="{{$unit->id}}">
	        		<input type="hidden" name="book_id" value="{{($book->id)??''}}">
	        		<div class="row">
		        		<div class="col-sm-12">
                            <div class="col-sm-4">
		        				<label>العنوان بالعربية</label>
		        		    </div>
		        			<div class="col-sm-8">
		        				<input type="text" name="title_ar" class="form-control" placeholder="العنوان بالعربية"/>
						    </div>
						    <div class="col-sm-4">
		        				<label>العنوان بالانجليزية</label>
		        		    </div>
		        			<div class="col-sm-8">
		        				<input type="text" name="title_en" class="form-control" placeholder="العنوان بالانجليزية"/>
						    </div>
						    <div class="col-sm-4">
		        				<label>رابط الفيديو</label>
		        		    </div>
		        			<div class="col-sm-8">
		        				<input type="text" name="video" class="form-control" placeholder="رابط الفيديو" />
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
	        <h5 class="modal-title" id="exampleModalLabel"> تعديل بيانات الدرس </h5>
	      </div>
	      <div class="modal-body">
	        	<form action="{{route('updateTeacherBookLesson')}}" method="POST"  enctype="multipart/form-data" >
	        		{{csrf_field()}}
	        		<input type="hidden" name="id" value="">
	        		<input type="hidden" name="edit_book_id" value="{{($book->id)??''}}">
	        		<input type="hidden" name="edit_unit_id" value="{{$unit->id}}">
	        		<div class="row">
		        		<div class="col-sm-12">
                            <div class="col-sm-4">
		        				<label>العنوان بالعربية</label>
		        		    </div>
		        			<div class="col-sm-8">
		        				<input type="text" name="edit_title_ar" class="form-control" placeholder="العنوان بالعربية"/>
						    </div>
						    <div class="col-sm-4">
		        				<label>العنوان بالانجليزية</label>
		        		    </div>
		        			<div class="col-sm-8">
		        				<input type="text" name="edit_title_en" class="form-control" placeholder="العنوان بالانجليزية"/>
						    </div>
						    <div class="col-sm-4">
		        				<label>رابط الفيديو</label>
		        		    </div>
		        			<div class="col-sm-8">
		        				<input type="text" name="edit_video" class="form-control" placeholder="رابط الفيديو" />
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
		var id            = $(this).data('id')
		var title_ar      = $(this).data('titlear')
		var title_en      = $(this).data('titleen')
		var video         = $(this).data('video')
		//set values in modal inputs
		$("input[name='id']")          .val(id)
		$("input[name='edit_title_ar']")   .val(title_ar)
		$("input[name='edit_title_en']")   .val(title_en)
		$("input[name='edit_video']")   .val(video)
});

	$('.generalDelete').on('click',function(e){ 
		var result = confirm('هل تريد استمرار عملية الحذف ؟ ');
		if(result == false){
			e.preventDefault();
		}
	});
</script>
@endsection