@extends('dashboard.layout.master')
	@section('title')
	  سيارات {{$user->name}}
	@endsection
@section('content')
<div class="panel panel-flat">
	<div class="panel-heading">
		<h5 class="panel-title"> سيارات {{$user->name}}</h5>
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
				<button class="btn bg-blue btn-block btn-float btn-float-lg openAddModal" type="button" data-toggle="modal" data-target="#exampleModal"><i class="icon-plus3"></i> <span>اضافة سيارة</span></button>
			</div>			
			<div class="col-xs-3">
				<button class="btn bg-purple-300 btn-block btn-float btn-float-lg" type="button"><i class="icon-list-numbered"></i> <span>عدد السيارات : {{count($cars)}} </span> </button>
			</div>
		</div>
	</div>
	<!-- /buttons -->

	<table class="table datatable-basic">
		<thead>
			<tr>
				<th>الصورة</th>
				<th>النوع </th>
				<th>الماركة </th>
				<th>الموديل</th>
				<th>سنة الصنع </th>
				<th>رقم السيارة</th>
				<th>تاريخ الاضافة</th>
				<th>التحكم</th>
			</tr>
		</thead>
		<tbody>
			@foreach($cars as $car)
				<tr>
					<td><img src="{{asset('img/car/'.$car->image)}}" class="img-circle" alt=""></td>
					<td>
                        <?php $car_type_ids = explode(',', $car->car_type_id);?>
                            @if($car_type_ids)
                                @foreach($car_type_ids as $car_type_id)
                                    @if($cartype = getCarType($car_type_id))
                                        {{$cartype->name_ar.' ,'}}
                                    @endif
                                @endforeach
                            @endif
					</td>
					<td>{{$car->brand}}</td>
					<td>{{$car->model}}</td>
					<td>{{$car->year}}</td>
					<td>{{$car->car_number}}</td>
					<td>{{date('Y-m-d H:i',strtotime($car->created_at))}}</td>
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
										data-id="{{$car->id}}" 
										data-userid="{{$user->id}}" 
									    data-image="{{$car->image}}"
										data-cartypeid="{{$car->car_type_id}}" 
										data-brand="{{$car->brand}}" 
										data-model="{{$car->model}}"
										data-year="{{$car->year}}"
										data-carnumber="{{$car->car_number}}"
									>
									<i class="icon-pencil7"></i>تعديل
									</a>
								</li>
								<!-- delete button -->
								<form action="{{route('DeleteCaptainCar')}}" method="POST">
									{{csrf_field()}}
									<input type="hidden" name="id" value="{{$car->id}}">
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
	        <h5 class="modal-title" id="exampleModalLabel">أضافة سيارة جديدة</h5>
	      </div>
	      <div class="modal-body">
	        <div class="row">
	        	<form action="{{route('createCaptainCar')}}" id="addplan" method="post" enctype="multipart/form-data">
	        		{{csrf_field()}}
	        		<input type="hidden" name="user_id" value="{{$user->id}}">
	        		<div class="row">
		        		<div class="col-sm-3 text-center">
		        			<label style="margin-bottom: 0">اختيار صوره</label>
		        			<i class="icon-camera"  onclick="addChooseFile()" style="cursor: pointer;"></i>
		        			<div class="images-upload-block">
		        				<input type="file" name="image" class="image-uploader" id="hidden">
		        			</div>
		        		</div>
		        		<div class="col-sm-9">
							<select name="car_type_id[]" class="form-control" multiple>
								@foreach($cartypes as $cartype)
									<option value="{{$cartype->id}}">{{$cartype->name_ar}}</option>
								@endforeach
							</select>
		        			<input type="text" name="brand" class="form-control" placeholder="الماركة">
		        			<input type="text" name="model" class="form-control" placeholder="الموديل">
						</div>
		        		<div class="col-sm-3 text-center">
		        		</div>
		        		<div class="col-sm-9" >
		        			<input type="text" name="year" class="form-control" placeholder="سنة الصنع">
		        			<input type="text" name="car_number" class="form-control" placeholder="رقم السيارة">
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
	        <h5 class="modal-title" id="exampleModalLabel"> تعديل السيارة</h5>
	      </div>
	      <div class="modal-body">
	        	<form action="{{route('updateCaptainCar')}}" method="post" enctype="multipart/form-data">
	        		{{csrf_field()}}
	        		<input type="hidden" name="id" value="">
	        		<input type="hidden" name="edit_user_id">
	        		<div class="row">
		        		<div class="col-sm-3 text-center">
		        			<img src="" class="photo" style="width: 120px;height: 120px;cursor: pointer;margin-bottom:10px;" onclick="ChooseFile()">
		        			<input type="file" name="edit_image" style="display: none;">
		        		</div>	
		        		<div class="col-sm-9">
							<select name="edit_car_type_id[]" class="form-control" id="cartypes" multiple>
								@foreach($cartypes as $cartype)
									<option value="{{$cartype->id}}">{{$cartype->name_ar}}</option>
								@endforeach
							</select>
		        			<input type="text" name="edit_brand" class="form-control" placeholder="الماركة">
		        			<input type="text" name="edit_model" class="form-control" placeholder="الموديل">
						</div>  		        		        			     			
                        <div class="col-sm-3 text-center"></div>
		        		<div class="col-sm-9" >
		        			<input type="text" name="edit_year" class="form-control" placeholder="سنة الصنع">
		        			<input type="text" name="edit_car_number" class="form-control" placeholder="رقم السيارة">
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
<!-- <script type="text/javascript" src="{{asset('dashboard/js/plugins/forms/selects/select2.min.js')}}"></script> -->
<script type="text/javascript" src="{{asset('dashboard/js/pages/datatables_basic.js')}}"></script>
@endsection



<script type="text/javascript">
	$('.openEditmodal').on('click',function(){
		//get valus 
		var id               = $(this).data('id')
		var user_id          = $(this).data('userid')
		var car_type_id      = $(this).data('cartypeid')
		var brand            = $(this).data('brand')
		var model            = $(this).data('model')
		var year             = $(this).data('year')
		var car_number       = $(this).data('carnumber')
		var image            = $(this).data('image')

		//set values in modal inputs
		$("input[name='id']")             .val(id)
		$("input[name='edit_user_id']")    .val(user_id)
		$("input[name='edit_brand']")      .val(brand)
		$("input[name='edit_model']")      .val(model)
		$("input[name='edit_year']")       .val(year)		
		$("input[name='edit_car_number']") .val(car_number)		
		
		$('#cartypes option').each(function(){
			if($(this).val() == car_type_id){
				$(this).attr('selected','')
			}
		});

		var link = "{{asset('img/car/')}}" +'/'+ image
		$(".photo").attr('src',link)
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