@extends('apis_dashboard.layout.master')
	@section('title')
	 الكتب 
	@endsection

@section('content')
<div class="panel panel-flat">
	<div class="panel-heading">
		<h5 class="panel-title">قائمة الكتب</h5>
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
				<button class="btn bg-blue btn-block btn-float btn-float-lg openAddModal" type="button" data-toggle="modal" data-target="#exampleModal"><i class="icon-plus3"></i> <span>اضافة كتاب</span></button>
			</div>
			<div class="col-xs-3">
				<button class="btn bg-purple-300 btn-block btn-float btn-float-lg" type="button"><i class="icon-list-numbered"></i> <span>عدد الكتب : {{count($books)}} </span> </button>
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
				<th>المرحلة</th>
				<th>الصف</th>
				<th>المادة</th>
				<th>النوع</th>
				<th>الرقم التسلسلي</th>
				<th>عدد المبيعات</th>
				<th>تاريخ الاضافه</th>
				<th>التحكم</th>
			</tr>
		</thead>
		<tbody>
			@foreach($books as $book)
				<tr>
					<td>{{$loop->iteration}}</td>
					<td>{{$book->name_ar}}</td>
					<td>{{$book->name_en}}</td>
					<td>{{($book->stage)?$book->stage->name_ar : ''}}</td>
					<td>{{($book->grade)?$book->grade->name_ar : ''}}</td>
					<td>{{($book->course)?$book->course->name_ar : ''}}</td>
					<td>{{($book->type == 'free')?'مجاناً' : 'مدفوع('.$book->price.')'}}</td>
					<td>{{$book->serial_number}}</td>
					<td>{{$book->num_selles}}</td>
					<td>{{date('Y-m-d H:i',strtotime($book->created_at))}}</td>
					<td>
					<ul class="icons-list">
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">
								<i class="icon-menu9"></i>
							</a>

							<ul class="dropdown-menu dropdown-menu-right">
								<li>
									<a href="{{url('teacher/teacherBookUnits/'.$book->id)}}" />
									<i class="glyphicon glyphicon-th-list"></i>وحدات الكتاب
									</a>
								</li>
								<li>
									<a href="{{url('teacher/teacherQuestionsbanks/'.$book->id)}}" />
									<i class="glyphicon glyphicon-th-list"></i>بنوك الأسئلة
									</a>
								</li>
								<li>
									<a href="{{url('teacher/teacherExams/'.$book->id)}}" />
									<i class="glyphicon glyphicon-th-list"></i>الأختبارات
									</a>
								</li>
								<!-- edit button -->
								<li>
									<a href="#" data-toggle="modal" data-target="#exampleModal2" class="openEditmodal" 
									data-id="{{$book->id}}" 
									data-courseid="{{$book->course_id}}" 
									data-gradeid="{{$book->grade_id}}" 
									data-stageid="{{$book->stage_id}}" 
									data-namear="{{$book->name_ar}}"
									data-nameen="{{$book->name_en}}"
									data-descriptionar="{{$book->description_ar}}"
									data-descriptionen="{{$book->description_en}}"
									data-image="{{$book->image}}"
									data-serialnumber="{{$book->serial_number}}"
									data-type="{{$book->type}}"
									data-price="{{$book->price}}"
									data-numunits="{{$book->num_units}}"
									data-numlessons="{{$book->num_lessons}}"
									/>
									<i class="icon-pencil7"></i>تعديل
									</a>
								</li>
								<form action="{{route('DeleteTeacherBook')}}" method="POST" id="DeleteCouponForm">
									{{csrf_field()}}
									<input type="hidden" name="id" value="{{$book->id}}">
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
	        <h5 class="modal-title" id="exampleModalLabel">أضافة كتاب تعليمي</h5>
	      </div>
	      <div class="modal-body">
	        <div class="row">
	        	<form action="{{route('createTeacherBook')}}" method="POST"  enctype="multipart/form-data" >
	        		{{csrf_field()}}

	        		<div class="row">
		        		<div class="col-sm-12">
		        			<div class="col-sm-4">
		        				<label>الصورة</label>
		        		    </div>
		        			<div class="col-sm-8">
			        			<i class="icon-camera"  onclick="addChooseFile()" style="cursor: pointer;"></i>
			        			<div class="images-upload-block">
			        				<input type="file" name="image" class="image-uploader" id="hidden" >
			        			</div>
		        			</div>
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
	        					<label>المادة</label>
		        		    </div>
		        			<div class="col-sm-8">
		        				<select name="course_id" class="form-control">
	                              @foreach($courses as $course)
	                                  <option value="{{$course->id}}"> {{$course->name_ar}}</option>
	                              @endforeach
		        				</select>
						    </div>
						    <div class="col-sm-4">
	        					<label>المرحلة التعليمية</label>
		        		    </div>
		        			<div class="col-sm-8">
		        				<select name="stage_id" class="form-control">
	                              @foreach($stages as $stage)
	                                  <option value="{{$stage->id}}"> {{$stage->name_ar}}</option>
	                              @endforeach
		        				</select>
						    </div>
						    <div class="col-sm-4">
	        					<label>الصف التعليمي</label>
		        		    </div>
		        			<div class="col-sm-8">
		        				<select name="grade_id" class="form-control">
	                              @foreach($grades as $grade)
	                                  <option value="{{$grade->id}}"> {{$grade->name_ar}} ({{($grade->stage->name_ar)??''}})</option>
	                              @endforeach
		        				</select>
						    </div>
						    <div class="col-sm-4">
	        					<label>النوع</label>
		        		    </div>
		        			<div class="col-sm-8">
		        				<select name="type" class="form-control">
	                                  <option value="free"> مجاني</option>
	                                  <option value="paid"> مدفوع</option>
		        				</select>
						    </div>
						    <div class="col-sm-4">
		        				<label>السعر</label>
		        		    </div>
		        			<div class="col-sm-8">
		        				<input type="number" name="price" class="form-control" placeholder="السعر" step="0.01"/>
						    </div>
						    <div class="col-sm-4">
		        				<label>الرقم التسلسلي</label>
		        		    </div>
		        			<div class="col-sm-8">
		        				<input type="text" name="serial_number" class="form-control" placeholder="الرقم التسلسلي" />
						    </div>	
                            <div class="col-sm-4">
		        				<label>عدد الوحدات</label>
		        		    </div>
		        			<div class="col-sm-8">
		        				<input type="number" name="num_units" class="form-control" placeholder="عدد الوحدات" step="1"/>
						    </div>
						    <div class="col-sm-4">
		        				<label>عدد الدروس</label>
		        		    </div>
		        			<div class="col-sm-8">
		        				<input type="number" name="num_lessons" class="form-control" placeholder="عدد الدروس" step="1"/>
						    </div>
						    <div class="col-sm-4">
		        				<label>الوصف بالعربية</label>
		        		    </div>		        	
			        		<div class="col-sm-8">
			        			<textarea name="description_ar"  class="form-control" placeholder="الوصف بالعربية" cols="50" rows="3"></textarea>
			        		</div>
	                        <div class="col-sm-4">
		        				<label>الوصف بالانجليزية</label>
		        		    </div>		        	
			        		<div class="col-sm-8">
			        			<textarea name="description_en"  class="form-control" placeholder="الوصف بالانجليزية" cols="50" rows="3"></textarea>
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
	        <h5 class="modal-title" id="exampleModalLabel"> تعديل كتاب التعليمي </h5>
	      </div>
	      <div class="modal-body">
	        	<form action="{{route('updateTeacherBook')}}" method="POST"  enctype="multipart/form-data" >
	        		{{csrf_field()}}
	        		<input type="hidden" name="id" value="">
	        		<div class="row">
		        		<div class="col-sm-12">
		        			<div class="col-sm-4">
		        				<label>الصورة</label>
		        		    </div>
		        			<div class="col-sm-8">
			        			<img src="" class="photo" style="width: 120px;height: 120px;cursor: pointer;margin-bottom:10px;" onclick="ChooseFile()">
			        			<input type="file" name="edit_image" style="display: none;">
		        			</div>
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
	        					<label>المادة</label>
		        		    </div>
		        			<div class="col-sm-8">
		        				<select name="edit_course_id" class="form-control" id="course_id">
	                              @foreach($courses as $course)
	                                  <option value="{{$course->id}}"> {{$course->name_ar}}</option>
	                              @endforeach
		        				</select>
						    </div>
						    <div class="col-sm-4">
	        					<label>المرحلة التعليمية</label>
		        		    </div>
		        			<div class="col-sm-8">
		        				<select name="edit_stage_id" class="form-control" id="stage_id">
	                              @foreach($stages as $stage)
	                                  <option value="{{$stage->id}}"> {{$stage->name_ar}}</option>
	                              @endforeach
		        				</select>
						    </div>
						    <div class="col-sm-4">
	        					<label>الصف التعليمي</label>
		        		    </div>
		        			<div class="col-sm-8">
		        				<select name="edit_grade_id" class="form-control" id="grade_id">
	                              @foreach($grades as $grade)
	                                  <option value="{{$grade->id}}"> {{$grade->name_ar}} ({{($grade->stage->name_ar)??''}})</option>
	                              @endforeach
		        				</select>
						    </div>
						    <div class="col-sm-4">
	        					<label>النوع</label>
		        		    </div>
		        			<div class="col-sm-8">
		        				<select name="edit_type" class="form-control" id="type">
	                                  <option value="free"> مجاني</option>
	                                  <option value="paid"> مدفوع</option>
		        				</select>
						    </div>
						    <div class="col-sm-4">
		        				<label>السعر</label>
		        		    </div>
		        			<div class="col-sm-8">
		        				<input type="number" name="edit_price" class="form-control" placeholder="السعر" step="0.01"/>
						    </div>
						    <div class="col-sm-4">
		        				<label>الرقم التسلسلي</label>
		        		    </div>
		        			<div class="col-sm-8">
		        				<input type="text" name="edit_serial_number" class="form-control" placeholder="الرقم التسلسلي" />
						    </div>	
                            <div class="col-sm-4">
		        				<label>عدد الوحدات</label>
		        		    </div>
		        			<div class="col-sm-8">
		        				<input type="number" name="edit_num_units" class="form-control" placeholder="عدد الوحدات" step="1"/>
						    </div>
						    <div class="col-sm-4">
		        				<label>عدد الدروس</label>
		        		    </div>
		        			<div class="col-sm-8">
		        				<input type="number" name="edit_num_lessons" class="form-control" placeholder="عدد الدروس" step="1"/>
						    </div>
						    <div class="col-sm-4">
		        				<label>الوصف بالعربية</label>
		        		    </div>		        	
			        		<div class="col-sm-8">
			        			<textarea name="edit_description_ar"  class="form-control" placeholder="الوصف بالعربية" cols="50" rows="3"></textarea>
			        		</div>
	                        <div class="col-sm-4">
		        				<label>الوصف بالانجليزية</label>
		        		    </div>		        	
			        		<div class="col-sm-8">
			        			<textarea name="edit_description_en"  class="form-control" placeholder="الوصف بالانجليزية" cols="50" rows="3"></textarea>
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
		var image          = $(this).data('image')
		var course_id      = $(this).data('courseid')
		var stage_id       = $(this).data('stageid')
		var grade_id       = $(this).data('gradeid')
		var description_ar = $(this).data('descriptionar')
		var description_en = $(this).data('descriptionen')
		var serial_number  = $(this).data('serialnumber')
		var type           = $(this).data('type')
		var price          = $(this).data('price')
		var num_units      = $(this).data('numunits')
		var num_lessons    = $(this).data('numlessons')
		//set values in modal inputs
		$("input[name='id']")          .val(id)
		$("input[name='edit_name_ar']")   .val(name_ar)
		$("input[name='edit_name_en']")   .val(name_en)
		$("textarea[name='edit_description_ar']")   .val(description_ar)
		$("textarea[name='edit_description_en']")   .val(description_en)
		$("input[name='edit_serial_number']")   .val(serial_number)
		$("input[name='edit_price']")   .val(price)
		$("input[name='edit_num_units']")   .val(num_units)
		$("input[name='edit_num_lessons']")   .val(num_lessons)
		var link = "{{asset('img/book')}}" +'/'+ image;
		$(".photo").attr('src',link);	
		$('#course_id option').each(function(){
			if($(this).val() == course_id){
				$(this).attr('selected','selected')
			}
		});			
		$('#stage_id option').each(function(){
			if($(this).val() == stage_id){
				$(this).attr('selected','selected')
			}
		});	
		$('#grade_id option').each(function(){
			if($(this).val() == grade_id){
				$(this).attr('selected','selected')
			}
		});	
		$('#type option').each(function(){
			if($(this).val() == type){
				$(this).attr('selected','selected')
			}
		});					
});

	$('.generalDelete').on('click',function(e){ 
		var result = confirm('هل تريد استمرار عملية الحذف ؟ ');
		if(result == false){
			e.preventDefault();
		}
	});
</script>
<script type="text/javascript">
	function addChooseFile(){$("input[name='image']").click()}
	function ChooseFile(){$("input[name='edit_image']").click()}
</script>
@endsection