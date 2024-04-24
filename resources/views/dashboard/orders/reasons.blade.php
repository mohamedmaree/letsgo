@extends('dashboard.layout.master')
	@section('title')
	أسباب الإغلاق والانسحاب
	@endsection

@section('content')
<div class="panel panel-flat">
	<div class="panel-heading">
		<h5 class="panel-title">أسباب الإغلاق والانسحاب</h5>
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
				<button class="btn bg-blue btn-block btn-float btn-float-lg openAddModal" type="button" data-toggle="modal" data-target="#exampleModal"><i class="icon-plus3"></i> <span>اضافة سبب</span></button>
			</div>
			<div class="col-xs-3">
				<button class="btn bg-purple-300 btn-block btn-float btn-float-lg" type="button"><i class="icon-list-numbered"></i> <span>عدد الأسباب : {{count($reasons)}} </span> </button>
			</div>	
		</div>
	</div>
	<!-- /buttons -->
	@csrf
	<table class="table datatable-basic">
		<thead>
			<tr>
				<th>السبب بالعربية </th>
				<th>السبب بالانجليزية</th>
				<th>النوع</th>
				<th>تاريخ الاضافه</th>
				<th>التحكم</th>
			</tr>
		</thead>
		<tbody>
			@foreach($reasons as $reason)
				<tr>
					<td>{{$reason->reason_ar}}   </td>
					<td>{{$reason->reason_en}}</td>
					<td>{{($reason->type=='cancel')?'سبب إغلاق':'سبب إنسحاب'}}</td>
					<td>{{$reason->created_at->diffForHumans()}}</td>
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
									data-id="{{$reason->id}}" 
									data-reasonar="{{$reason->reason_ar}}" 
									data-reasonen="{{$reason->reason_en}}" 
									data-type="{{$reason->type}}">
									<i class="icon-pencil7"></i>تعديل
									</a>
								</li>
								<!-- delete button -->
								<form action="{{route('DeleteReason')}}" method="POST" id="DeleteReasonForm">
									{{csrf_field()}}
									<input type="hidden" name="id" value="{{$reason->id}}">
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
	        <h5 class="modal-title" id="exampleModalLabel">أضافة سبب جديد</h5>
	      </div>
	      <div class="modal-body">
	        <div class="row">
	        	<form action="{{route('createReason')}}" method="POST">
	        		{{csrf_field()}}

	        		<div class="row">
		        		<div class="col-sm-12">
						    <div class="col-sm-4">
		        			   <label>السبب بالعربية</label>
		        		    </div>
		        			<div class="col-sm-8">
		        				<input type="text" name="reason_ar" class="form-control" placeholder="السبب بالعربية ">
		        			</div>
		        			<div class="col-sm-4">
		        			   <label>السبب بالانجليزية</label>
		        		    </div>
		        			<div class="col-sm-8">
		        				<input type="text" name="reason_en" class="form-control" placeholder="السبب بالانجليزية">
		        			</div>	
		        			<div class="col-sm-4">
		        				<label>النوع</label>
		        		    </div>
		        			<div class="col-sm-8">
			        			<select name="type" class="form-control" id="type">
									<option value="0" disabled selected> النوع </option>
										<option value="cancel">سبب إغلاق</option>
										<option value="withdraw">سبب إنسحاب</option>
								</select>
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
	        <h5 class="modal-title" id="exampleModalLabel"> تعديل السبب </h5>
	      </div>
	      <div class="modal-body">
	        	<form action="{{route('updateReason')}}" method="post" >
	        		{{csrf_field()}}
	        		<input type="hidden" name="id" value="">
	        		<div class="row">
		        		<div class="col-sm-12">
						    <div class="col-sm-4">
		        			   <label>السبب بالعربية</label>
		        		    </div>
		        			<div class="col-sm-8">
		        				<input type="text" name="edit_reason_ar" class="form-control" placeholder="السبب بالعربية ">
		        			</div>
		        			<div class="col-sm-4">
		        			   <label>السبب بالانجليزية</label>
		        		    </div>
		        			<div class="col-sm-8">
		        				<input type="text" name="edit_reason_en" class="form-control" placeholder="السبب بالانجليزية">
		        			</div>	
		        			<div class="col-sm-4">
		        				<label>النوع</label>
		        		    </div>
		        			<div class="col-sm-8">
			        			<select name="edit_type" class="form-control" id="edit_type">
									<option value="0" disabled selected> النوع </option>
										<option value="cancel">سبب إلغاء</option>
										<option value="withdraw">سبب إنسحاب</option>
								</select>
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
	$("#checkAll").change(function () {
	    $("input:checkbox").prop('checked', $(this).prop("checked"));
	});

	$('.openEditmodal').on('click',function(){
		//get valus 
		var id         = $(this).data('id') ;
		var type       = $(this).data('type');
		var reason_ar  = $(this).data('reasonar');
		var reason_en  = $(this).data('reasonen');
		//set values in modal inputs
		$("input[name='id']")             .val(id)
		$("select[name='edit_type']")     .val(type)
		$("input[name='edit_reason_ar']").val(reason_ar)
		$("input[name='edit_reason_en']").val(reason_en)

	})

	$('.generalDelete').on('click',function(e){ 
		var result = confirm('هل تريد استمرار عملية الحذف ؟ ');
		if(result == false){
			e.preventDefault();
		}
	});
</script>

@endsection