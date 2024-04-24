@extends('apis_dashboard.layout.master')
	@section('title')
	 صفحات الدرس {{$lesson->title_ar}} 
	@endsection

@section('content')
<div class="panel panel-flat">
	<div class="panel-heading">
		<h5 class="panel-title">قائمة صفحات درس {{$lesson->title_ar}}</h5>
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
				<button class="btn bg-blue btn-block btn-float btn-float-lg openAddModal" type="button" data-toggle="modal" data-target="#exampleModal"><i class="icon-plus3"></i> <span>اضافة صفحات للدرس</span></button>
			</div>
			<div class="col-xs-3">
				<button class="btn bg-purple-300 btn-block btn-float btn-float-lg" type="button"><i class="icon-list-numbered"></i> <span>عدد الصفحات : {{count($pages)}} </span> </button>
			</div>	
		</div>
	</div>
	<!-- /buttons -->
	@csrf
	<table class="table datatable-basic">
		<thead>
			<tr>
				<th>#</th>
				<th>الصورة</th>
				<th>المحتوي بالعربية</th>
				<th>المحتوي بالانجليزية</th>
				<th>تاريخ الاضافه</th>
				<th>التحكم</th>
			</tr>
		</thead>
		<tbody>
			@foreach($pages as $page)
				<tr>
					<td>{{$loop->iteration}}</td>
					<td><a href="{{url('img/book/'.$page->image)}}"><img src="{{url('img/book/'.$page->image)}}"></a></td>
					<td>{{str_limit($page->content_ar,500)}}</td>
					<td>{{str_limit($page->content_en,500)}}</td>
					<td>{{date('Y-m-d H:i',strtotime($page->created_at))}}</td>
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
									data-id="{{$page->id}}"
									data-contentar="{{$page->content_ar}}"
									data-contenten="{{$page->content_en}}"
									data-image="{{$page->image}}"
									/>
									<i class="icon-pencil7"></i>تعديل
									</a>
								</li>
								<form action="{{route('DeleteTeacherBookLessonPage')}}" method="POST" id="DeleteCouponForm">
									{{csrf_field()}}
									<input type="hidden" name="id" value="{{$page->id}}">
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
	        <h5 class="modal-title" id="exampleModalLabel">أضافة صفحة جديدة للدرس</h5>
	      </div>
	      <div class="modal-body">
	        <div class="row">
	        	<form action="{{route('createTeacherBookLessonPage')}}" method="POST"  enctype="multipart/form-data" >
	        		{{csrf_field()}}
	        		<input type="hidden" name="lesson_id" value="{{$lesson->id}}">
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
		        				<label>المحتوي بالعربية</label>
		        		    </div>	
						    <div class="col-sm-8">
			        			<textarea name="content_ar"  class="form-control" placeholder="المحتوي بالعربية" cols="50" rows="3"></textarea>
			        		</div>
	                        <div class="col-sm-4">
		        				<label>المحتوي بالانجليزية</label>
		        		    </div>		        	
			        		<div class="col-sm-8">
			        			<textarea name="content_en"  class="form-control" placeholder="المحتوي بالانجليزية" cols="50" rows="3"></textarea>
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
	        	<form action="{{route('updateTeacherBookLessonPage')}}" method="POST"  enctype="multipart/form-data" >
	        		{{csrf_field()}}
	        		<input type="hidden" name="id" value="">
	        		<input type="hidden" name="edit_lesson_id" value="{{($lesson->id)??''}}">
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
		        				<label>المحتوي بالعربية</label>
		        		    </div>	
						    <div class="col-sm-8">
			        			<textarea name="edit_content_ar"  class="form-control" placeholder="المحتوي بالعربية" cols="50" rows="3"></textarea>
			        		</div>
	                        <div class="col-sm-4">
		        				<label>المحتوي بالانجليزية</label>
		        		    </div>		        	
			        		<div class="col-sm-8">
			        			<textarea name="edit_content_en"  class="form-control" placeholder="المحتوي بالانجليزية" cols="50" rows="3"></textarea>
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
		var content_ar    = $(this).data('contentar')
		var content_en    = $(this).data('contenten')
		var image         = $(this).data('image')
		//set values in modal inputs
		$("input[name='id']")          .val(id)
		$("textarea[name='edit_content_ar']")   .val(content_ar)
		$("textarea[name='edit_content_en']")   .val(content_en)
		var link = "{{asset('img/book')}}" +'/'+ image;
		$(".photo").attr('src',link);
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