@extends('dashboard.layout.master')
	@section('title')
	أقسام المنيو
	@endsection
@section('content')
<div class="panel panel-flat">
	<div class="panel-heading">
		<h5 class="panel-title">{{ ($store_id != '')? (($store)?'أقسام منيو '.$store->name_ar:'أقسام المنيو') :'أقسام المنيو'}} </h5>
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
				<button class="btn bg-blue btn-block btn-float btn-float-lg openAddModal" type="button" data-toggle="modal" data-target="#exampleModal"><i class="icon-plus3"></i> <span>اضافة قسم</span></button>
			</div>			
			<div class="col-xs-3">
				<button class="btn bg-purple-300 btn-block btn-float btn-float-lg" type="button"><i class="icon-list-numbered"></i> <span>عدد الأقسام بالمنيو : {{count($menuCategories)}} </span> </button>
			</div>
		</div>
	</div>
	<!-- /buttons -->

	<table class="table datatable-basic">
		<thead>
			<tr>
				<th>الاسم بالعربية</th>
				<th>الاسم بالانجليزية</th>
				<th>المتجر</th>
				<th>تاريخ الاضافة</th>
				<th>التحكم</th>
			</tr>
		</thead>
		<tbody>
			@foreach($menuCategories as $menuCategory)
				<tr>
					<td>{{$menuCategory->name_ar}}</td>
					<td>{{$menuCategory->name_en}}</td>
					<td>{{($menuCategory->store)?$menuCategory->store->name_ar:''}}</td>
					<td>{{Carbon\Carbon::parse($menuCategory->created_at)->format('d/m/Y - H:i A')}}</td>
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
									data-id="{{$menuCategory->id}}"
									data-namear="{{$menuCategory->name_ar}}"
									data-nameen="{{$menuCategory->name_en}}"
									data-storeid="{{$menuCategory->store_id}}"
									>
									<i class="icon-pencil7"></i>تعديل
									</a>
								</li>
								<!-- delete button -->
								<form action="{{route('DeleteMenuCategory')}}" method="POST">
									{{csrf_field()}}
									<input type="hidden" name="id" value="{{$menuCategory->id}}">
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
	        <h5 class="modal-title" id="exampleModalLabel">أضافة قسم جديد</h5>
	      </div>
	      <div class="modal-body">
	        <div class="row">
	        	<form action="{{route('createMenuCategory')}}" id="addproduct" method="post" enctype="multipart/form-data">
	        		{{csrf_field()}}
	        		<input type="hidden" name="store_id" value="{{$store_id}}">
	        		<div class="row">
                        <div class="col-sm-4">
	        				<label>الاسم بالعربية</label>
	        		    </div>
		        		<div class="col-sm-8" >
		        			<input type="text" name="name_ar" class="form-control" placeholder="اسم القسم بالعربية">
		        		</div>
                        <div class="col-sm-4">
	        				<label>الاسم بالانجليزية</label>
	        		    </div>
		        		<div class="col-sm-8" >
		        			<input type="text" name="name_en" class="form-control" placeholder="اسم القسم بالانجليزية">
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
	        <h5 class="modal-title" id="exampleModalLabel"> تعديل القسم : <span class="productName"></span> </h5>
	      </div>
	      <div class="modal-body">
	        	<form action="{{route('updateMenuCategory')}}" method="post" enctype="multipart/form-data">
	        		{{csrf_field()}}
	        		<input type="hidden" name="id" value="">
	        		<input type="hidden" name="edit_store_id" value="{{$store_id}}">
	        		<div class="row">
                        <div class="col-sm-4">
	        				<label>الاسم بالعربية</label>
	        		    </div>
		        		<div class="col-sm-8" >
		        			<input type="text" name="edit_name_ar" class="form-control" placeholder="اسم القسم بالعربية">
		        		</div>
                        <div class="col-sm-4">
	        				<label>الاسم بالانجليزية</label>
	        		    </div>
		        		<div class="col-sm-8" >
		        			<input type="text" name="edit_name_en" class="form-control" placeholder="اسم القسم بالانجليزية">
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
	<!-- /Edit user Modal -->
</div>
<!-- javascript -->
@section('script')
<script type="text/javascript" src="{{asset('dashboard/js/plugins/tables/datatables/datatables.min.js')}}"></script>
<script type="text/javascript" src="{{asset('dashboard/js/plugins/forms/selects/select2.min.js')}}"></script>
<script type="text/javascript" src="{{asset('dashboard/js/pages/datatables_basic.js')}}"></script>
@endsection


<script type="text/javascript">

    $("#addproduct").submit(function(){
        var store = $('#store_id').find(":selected").val();
        if(store == ''){
          alert("يجب عليك اختيار المتجر.");
          return false;        	
        }
    });

	$('.openEditmodal').on('click',function(){
		//get valus 
		var id             = $(this).data('id')
		var store_id       = $(this).data('storeid')
		var name_ar        = $(this).data('namear')
		var name_en        = $(this).data('nameen')

		//set values in modal inputs
		$("input[name='id']")                    .val(id)
		$("input[name='edit_store_id']")         .val(store_id)
		$("input[name='edit_name_ar']")          .val(name_ar)
		$("input[name='edit_name_en']")          .val(name_en)
		$('.productName').text(name_ar)

		// $('#editStore option').each(function(){
		// 	if($(this).val() == store_id){
		// 		$(this).attr('selected','selected')
		// 	}
		// });				
	})

	$('.generalDelete').on('click',function(e){ 
		var result = confirm('هل تريد استمرار عملية الحذف ؟ ');
		if(result == false){
			e.preventDefault();
		}
	});

</script>

@endsection