@extends('dashboard.layout.master')
	@section('title')
	مدن {{$country->name_ar}}
	@endsection
@section('content')
<div class="panel panel-flat">
	<div class="panel-heading">
		<h5 class="panel-title"> مدن {{$country->name_ar}} </h5>
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
				<button class="btn bg-blue btn-block btn-float btn-float-lg openAddModal" type="button" data-toggle="modal" data-target="#exampleModal"><i class="icon-plus3"></i> <span>اضافة مدينة</span></button>
			</div>			
			<div class="col-xs-3">
				<button class="btn bg-purple-300 btn-block btn-float btn-float-lg" type="button"><i class="icon-list-numbered"></i> <span>عدد المدن : {{count($cities)}} </span> </button>
			</div>
		</div>
	</div>
	<!-- /buttons -->

	<table class="table datatable-basic">
		<thead>
			<tr>
				<th>#</th>
				<th>الاسم بالعربية</th>
				<th>الاسم بالانجليزية</th>
				<th>تاريخ الاضافة</th>
				<th>التحكم</th>
			</tr>
		</thead>
		<tbody>
			<?php $i = 1;?>
			@foreach($cities as $city)
				<tr>
					<td>{{$i}}</td>
					<td>{{$city->name_ar}}</td>
					<td>{{$city->name_en}}</td>
					<td>{{$city->created_at->diffForHumans()}}</td>
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
									data-id="{{$city->id}}" 
									data-countryid="{{$city->country_id}}" 
									data-namear="{{$city->name_ar}}" 
									data-nameen="{{$city->name_en}}"
									>
									<i class="icon-pencil7"></i>تعديل
									</a>
								</li>
								<!-- delete button -->
								<form action="{{route('DeleteCity')}}" method="POST">
									{{csrf_field()}}
									<input type="hidden" name="id" value="{{$city->id}}">
									<li><button type="submit" class="generalDelete reset"><i class="icon-trash"></i>حذف</button></li>
								</form>
							</ul>
						</li>
					</ul>
					</td>
				</tr>
				<?php $i++;?>
			@endforeach
		</tbody>
	</table>

	<!-- Add workstage Modal -->
	<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="exampleModalLabel">أضافة مدينة جديدة</h5>
	      </div>
	      <div class="modal-body">
	        <div class="row">
	        	<form action="{{route('createCity')}}" method="post" enctype="multipart/form-data">
	        		{{csrf_field()}}
	        		<input type="hidden" name="country_id" value="{{$country->id}}">
	        		<div class="row">      			
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
	        <h5 class="modal-title" id="exampleModalLabel"> تعديل المدينة : <span class="cityName"></span> </h5>
	      </div>
	      <div class="modal-body">
	        	<form action="{{route('updateCity')}}" method="post" enctype="multipart/form-data">
	        		{{csrf_field()}}
	        		<input type="hidden" name="id" value="">
	        		<input type="hidden" name="edit_country_id" value="">
	        		<div class="row">
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
<script type="text/javascript" src="{{asset('dashboard/js/plugins/forms/selects/select2.min.js')}}"></script>
<script type="text/javascript" src="{{asset('dashboard/js/pages/datatables_basic.js')}}"></script>
@endsection



<script type="text/javascript">

	$('.openEditmodal').on('click',function(){
		//get valus 
		var id             = $(this).data('id')
		var country_id     = $(this).data('countryid')
		var name_ar        = $(this).data('namear')
		var name_en        = $(this).data('nameen')

		$("input[name='id']")              .val(id)
		$("input[name='edit_country_id']") .val(country_id)
		$("input[name='edit_name_ar']")    .val(name_ar)
		$("input[name='edit_name_en']")    .val(name_en)
		$('.cityName').text(name_ar)	
	})

	$('.generalDelete').on('click',function(e){ 
		var result = confirm('هل تريد استمرار عملية الحذف ؟ ');
		if(result == false){
			e.preventDefault();
		}
	});
</script>

@endsection