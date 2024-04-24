@extends('dashboard.layout.master')
	@section('title')
	تصنيفات السيارات
	@endsection
@section('content')
<div class="panel panel-flat">
	<div class="panel-heading">
		<h5 class="panel-title"> تصنيفات السيارات </h5>
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
				<button class="btn bg-blue btn-block btn-float btn-float-lg openAddModal" type="button" data-toggle="modal" data-target="#exampleModal"><i class="icon-plus3"></i> <span>اضافة تصنيف</span></button>
			</div>			
			<div class="col-xs-3">
				<button class="btn bg-purple-300 btn-block btn-float btn-float-lg" type="button"><i class="icon-list-numbered"></i> <span>عدد التصنيفات : {{count($cartypes)}} </span> </button>
			</div>
		</div>
	</div>
	<!-- /buttons -->

	<table class="table datatable-basic">
		<thead>
			<tr>
				<th>الصورة</th>
				<th>الاسم بالعربية</th>
				<th>الاسم بالانجليزية</th>
				<th>النوع</th>
				<th>الخدمة</th>
				<th>عدد الأشخاص</th>
				<th>أقصي حمولة</th>
				<th>تاريخ الاضافة</th>
				<th>التحكم</th>
			</tr>
		</thead>
		<tbody>
			@foreach($cartypes as $cartype)
				<tr>
					<td><img src="{{asset('img/car/'.$cartype->image)}}" class="img-circle" alt=""></td>
					<td>{{$cartype->name_ar}}</td>
					<td>{{$cartype->name_en}}</td>
					<td>{{($cartype->type=='people')?'أشخاص':'بضائع'}}</td>
					<td>@if($cartype->order_type == 'food')
                          توصيل طعام
						@elseif($cartype->order_type == 'both')
                          توصيل طعام وأشخاص
						@else
                          توصيل أشخاص
						@endif
					</td>
					<td>{{$cartype->num_persons}}</td>
					<td>{{$cartype->max_weight}}</td>
					<td>{{date('Y-m-d H:i',strtotime($cartype->created_at))}}</td>
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
									data-id="{{$cartype->id}}" 
									data-namear="{{$cartype->name_ar}}" 
									data-nameen="{{$cartype->name_en}}" 
									data-type="{{$cartype->type}}"
									data-ordertype="{{$cartype->order_type}}"
									data-numpersons="{{$cartype->num_persons}}"
									data-maxweight="{{$cartype->max_weight}}"
									data-image="{{$cartype->image}}"
									>
									<i class="icon-pencil7"></i>تعديل
									</a>
								</li>
								<!-- delete button -->
								<form action="{{route('DeleteCartype')}}" method="POST">
									{{csrf_field()}}
									<input type="hidden" name="id" value="{{$cartype->id}}">
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
	        <h5 class="modal-title" id="exampleModalLabel">أضافة تصنيف جديد</h5>
	      </div>
	      <div class="modal-body">
	        <div class="row">
	        	<form action="{{route('createCartype')}}" method="post" enctype="multipart/form-data">
	        		{{csrf_field()}}
	        		<div class="row">
                        <div class="col-sm-4">
	        				<label>الصورة</label>
	        		    </div>
		        		<div class="col-sm-8" >
		        			<i class="icon-camera"  onclick="addChooseFile()" style="cursor: pointer;"></i>
		        			<div class="images-upload-block">
		        				<input type="file" name="image" class="image-uploader" id="hidden">
		        			</div>
		        		</div>	        			
                        <div class="col-sm-4">
	        				<label>الاسم بالعربية</label>
	        		    </div>
		        		<div class="col-sm-8" >
		        			<input type="text" name="name_ar" class="form-control" placeholder="الاسم بالعربية">
		        		</div>
                        <div class="col-sm-4">
	        				<label>الاسم بالانجليزية</label>
	        		    </div>
		        		<div class="col-sm-8" >
		        			<input type="text" name="name_en" class="form-control" placeholder="الاسم بالانجليزية">
		        		</div>	
                        <div class="col-sm-4">
	        				<label>النوع</label>
	        		    </div>
		        		<div class="col-sm-8" >
                            <select name="type" class="form-control">
                                <option value="people"> أشخاص</option>
                                <option value="goods"> بضائع</option>
                            </select>
		        		</div>	
		        		<div class="col-sm-4">
	        				<label>الخدمة</label>
	        		    </div>
		        		<div class="col-sm-8" >
                            <select name="order_type" class="form-control">
                                <option value="trip"> توصيل أشخاص</option>
                                <option value="food"> توصيل طعام</option>
                                <option value="both"> توصيل طعام وأشخاص</option>
                            </select>
		        		</div>					
                        <div class="col-sm-4">
	        				<label>الحد الأقصي لعدد الأشخاص</label>
	        		    </div>
		        		<div class="col-sm-8" >
		        			<input type="number" name="num_persons" class="form-control" placeholder="عدد الأشخاص" min="0" />
		        		</div>
		        		<div class="col-sm-4">
	        				<label>الحد الأقصي للحمولة</label>
	        		    </div>
		        		<div class="col-sm-8" >
		        			<input type="text" name="max_weight" class="form-control" placeholder="الحد الأقصي للحمولة" />
		        		</div>		        		
	        		</div>

					<div class="row"> 
				      <div class="col-sm-12" style="margin-top: 10px">
				      	<button type="submit" class="btn btn-primary" onclick="this.form.submit(); this.disabled=true;"> اضافة</button>
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
	        <h5 class="modal-title" id="exampleModalLabel"> تعديل التصنيف : <span class="cartypeName"></span> </h5>
	      </div>
	      <div class="modal-body">
	        	<form action="{{route('updateCartype')}}" method="post" enctype="multipart/form-data">
	        		{{csrf_field()}}
	        		<input type="hidden" name="id" value="">
	        		<div class="row">
                        <div class="col-sm-4">
	        				<label>الصورة</label>
	        		    </div>
		        		<div class="col-sm-8" >
		        			<img src="" class="photo" style="width: 120px;height: 120px;cursor: pointer;margin-bottom:10px;" onclick="ChooseFile()">
		        			<input type="file" name="edit_image" style="display: none;">
		        		</div>	        			
                        <div class="col-sm-4">
	        				<label>الاسم بالعربية</label>
	        		    </div>
		        		<div class="col-sm-8" >
		        			<input type="text" name="edit_name_ar" class="form-control" placeholder="الاسم بالعربية">
		        		</div>
                        <div class="col-sm-4">
	        				<label>الاسم بالانجليزية</label>
	        		    </div>
		        		<div class="col-sm-8" >
		        			<input type="text" name="edit_name_en" class="form-control" placeholder="الاسم بالانجليزية">
		        		</div>	
		        		<div class="col-sm-4">
	        				<label>النوع</label>
	        		    </div>
		        		<div class="col-sm-8" >
                            <select name="edit_type" class="form-control" id="type">
                                <option value="people"> أشخاص</option>
                                <option value="goods"> بضائع</option>
                            </select>
		        		</div>	
		        		<div class="col-sm-4">
	        				<label>الخدمة</label>
	        		    </div>
		        		<div class="col-sm-8" >
                            <select name="edit_order_type" class="form-control" id="order_type">
                                <option value="trip"> توصيل أشخاص</option>
                                <option value="food"> توصيل طعام</option>
                                <option value="both"> توصيل طعام وأشخاص</option>
                            </select>
		        		</div>					
                        <div class="col-sm-4">
	        				<label>الحد الأقصي لعدد الأشخاص</label>
	        		    </div>
		        		<div class="col-sm-8" >
		        			<input type="number" name="edit_num_persons" class="form-control" placeholder="عدد الأشخاص" min="0" />
		        		</div>
		        		<div class="col-sm-4">
	        				<label>الحد الأقصي للحمولة</label>
	        		    </div>
		        		<div class="col-sm-8" >
		        			<input type="text" name="edit_max_weight" class="form-control" placeholder="الحد الأقصي للحمولة" />
		        		</div>		        		
	        		</div>

					<div class="row"> 
				      <div class="col-sm-12" style="margin-top: 10px">
				      	<button type="submit" class="btn btn-primary" onclick="this.form.submit(); this.disabled=true;">حفظ التعديلات</button>
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
<script type="text/javascript" src="{{asset('dashboard/js/plugins/forms/selects/select2.min.js')}}"></script>
<script type="text/javascript" src="{{asset('dashboard/js/pages/datatables_basic.js')}}"></script>
@endsection



<script type="text/javascript">

	$('.openEditmodal').on('click',function(){
		//get valus 
		var id             = $(this).data('id')
		var name_ar        = $(this).data('namear')
		var name_en        = $(this).data('nameen')
		var type           = $(this).data('type')
		var order_type     = $(this).data('ordertype')
		var num_persons    = $(this).data('numpersons')
		var max_weight     = $(this).data('maxweight')
		var image          = $(this).data('image')


		//set values in modal inputs
		$("input[name='id']")              .val(id)
		$("input[name='edit_name_ar']")    .val(name_ar)
		$("input[name='edit_name_en']")    .val(name_en)
		$("input[name='edit_num_persons']").val(num_persons);
		$("input[name='edit_max_weight']") .val(max_weight);
		var link = "{{asset('img/car/')}}" +'/'+ image
		$(".photo").attr('src',link)
		$('#type option').each(function(){
			if($(this).val() == type){
				$(this).attr('selected','selected')
			}
		});

		$('#order_type option').each(function(){
			if($(this).val() == order_type){
				$(this).attr('selected','selected')
			}
		});
		$('.cartypeName').text(name_ar)	
	})

	$('.generalDelete').on('click',function(e){ 
		var result = confirm('هل تريد استمرار عملية الحذف ؟ ');
		if(result == false){
			e.preventDefault();
		}
	});
</script>
<script type="text/javascript">
	function ChooseFile(){$("input[name='edit_image']").click()}
	function addChooseFile(){$("input[name='image']").click()}
</script>
@endsection