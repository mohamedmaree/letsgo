@extends('dashboard.layout.master')
	@section('title')
	  الكاش باك
	@endsection
@section('content')
<div class="panel panel-flat">
	<div class="panel-heading">
		<h5 class="panel-title">الكاش باك </h5>
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
				<button class="btn bg-blue btn-block btn-float btn-float-lg openAddModal" type="button" data-toggle="modal" data-target="#exampleModal"><i class="icon-plus3"></i> <span>اضافة كاش باك</span></button>
			</div>			
			<div class="col-xs-3">
				<button class="btn bg-purple-300 btn-block btn-float btn-float-lg" type="button"><i class="icon-list-numbered"></i> <span>عدد الكاش باك : {{count($cashBacks)}} </span> </button>
			</div>
		</div>
	</div>
	<!-- /buttons -->

	<table class="table datatable-basic">
		<thead>
			<tr>
				<th>الاسم بالعربية </th>
				<th>من تاريخ </th>
				<th>من وقت </th>
				<th>الي تاريخ</th>
				<th>الي وقت </th>
				<th>نسبة الخصم</th>
				<th>الحد الاقصي للخصم</th>
				<th>الميزانية</th>
				<th>اجمالي الصرف</th>
				<th> مرات الاستخدام للشخص</th>
				<th>تاريخ الاضافة</th>
				<th>التحكم</th>
			</tr>
		</thead>
		<tbody>
			@foreach($cashBacks as $cashBack)
				<tr>
					<td>{{$cashBack->name_ar}}</td>
					<td>{{$cashBack->from_date}}</td>
					<td>{{date('H:i',strtotime($cashBack->from_time))}}</td>
					<td>{{$cashBack->to_date}}</td>
					<td>{{date('H:i',strtotime($cashBack->to_time))}}</td>
					<td>{{$cashBack->percentage}}</td>
					<td>{{$cashBack->max_discount}}</td>
					<td>{{$cashBack->budget}}</td>
					<td>{{$cashBack->total_cost}}</td>
					<td>{{$cashBack->num_orders_one_user}}</td>
					<td>{{date('Y-m-d H:i',strtotime($cashBack->created_at))}}</td>
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
										data-id="{{$cashBack->id}}"
										data-namear="{{$cashBack->name_ar}}" 
									    data-nameen="{{$cashBack->name_en}}"  
									    data-descriptionar="{{$cashBack->description_ar}}" 
									    data-descriptionen="{{$cashBack->description_en}}" 
										data-fromdate="{{$cashBack->from_date}}" 
										data-fromtime="{{$cashBack->from_time}}" 
										data-todate="{{$cashBack->to_date}}"
										data-totime="{{$cashBack->to_time}}"
										data-percentage="{{$cashBack->percentage}}"
										data-maxdiscount="{{$cashBack->max_discount}}"
										data-budget="{{$cashBack->budget}}"
										data-numordersoneuser="{{$cashBack->num_orders_one_user}}"
									>
									<i class="icon-pencil7"></i>تعديل
									</a>
								</li>
								<!-- delete button -->
								<form action="{{route('DeleteCashBack')}}" method="POST">
									{{csrf_field()}}
									<input type="hidden" name="id" value="{{$cashBack->id}}">
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
	        <h5 class="modal-title" id="exampleModalLabel">أضافة كاش باك جديد</h5>
	      </div>
	      <div class="modal-body">
	        <div class="row">
	        	<form action="{{route('createCashBack')}}" id="addplan" method="post" enctype="multipart/form-data">
	        		{{csrf_field()}}
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
	        				<label>نسبة الخصم</label>
	        		    </div>
		        		<div class="col-sm-8" >
		        			<input type="number" name="percentage" class="form-control" placeholder="نسبة الخصم" />
		        		</div>
						<div class="col-sm-4">
	        				<label>الحد الاقصي للخصم</label>
	        		    </div>
		        		<div class="col-sm-8" >
		        			<input type="number" name="max_discount" class="form-control" placeholder="الحد الاقصي للخصم" />
		        		</div>	
                        <div class="col-sm-4">
	        				<label>الميزانية</label>
	        		    </div>		        	
		        		<div class="col-sm-8">
		        			<input type="number" name="budget" class="form-control" placeholder="الميزانية" />
		        		</div>	
						<div class="col-sm-4">
	        				<label>مرات الاستخدام للشخص</label>
	        		    </div>		        	
		        		<div class="col-sm-8">
		        			<input type="number" name="num_orders_one_user" class="form-control" placeholder="مرات الاستخدام للشخص" />
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
	        <h5 class="modal-title" id="exampleModalLabel"> تعديل المكافأة</h5>
	      </div>
	      <div class="modal-body">
	        	<form action="{{route('updateCashBack')}}" method="post" enctype="multipart/form-data">
	        		{{csrf_field()}}
	        		<input type="hidden" name="id" value="">
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
	        				<label>نسبة الخصم</label>
	        		    </div>
		        		<div class="col-sm-8" >
		        			<input type="number" name="edit_percentage" class="form-control" placeholder="نسبة الخصم" />
		        		</div>
						<div class="col-sm-4">
	        				<label>الحد الاقصي للخصم</label>
	        		    </div>
		        		<div class="col-sm-8" >
		        			<input type="number" name="edit_max_discount" class="form-control" placeholder="الحد الاقصي للخصم" />
		        		</div>	
                        <div class="col-sm-4">
	        				<label>الميزانية</label>
	        		    </div>		        	
		        		<div class="col-sm-8">
		        			<input type="number" name="edit_budget" class="form-control" placeholder="الميزانية" />
		        		</div>	
						<div class="col-sm-4">
	        				<label>مرات الاستخدام للشخص</label>
	        		    </div>		        	
		        		<div class="col-sm-8">
		        			<input type="number" name="edit_num_orders_one_user" class="form-control" placeholder="مرات الاستخدام للشخص" />
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
		var name_ar        = $(this).data('namear')
		var name_en        = $(this).data('nameen')
		var description_ar        = $(this).data('descriptionar')
		var description_en        = $(this).data('descriptionen')
		var from_date        = $(this).data('fromdate')
		var from_time        = $(this).data('fromtime')
		var to_date          = $(this).data('todate')
		var to_time          = $(this).data('totime')
		var percentage       = $(this).data('percentage')
		var max_discount       = $(this).data('maxdiscount')
		var budget       = $(this).data('budget')
		var num_orders_one_user       = $(this).data('numordersoneuser')


		//set values in modal inputs
		$("input[name='id']")               .val(id)
		$("input[name='edit_name_ar']")    .val(name_ar)
		$("input[name='edit_name_en']")    .val(name_en)
		$("input[name='edit_description_ar']")    .val(description_ar)
		$("input[name='edit_description_en']")    .val(description_en)
		$("input[name='edit_from_date']")   .val(from_date)
		$("input[name='edit_from_time']")   .val(from_time)
		$("input[name='edit_to_date']")     .val(to_date)
		$("input[name='edit_to_time']")     .val(to_time)	

		$("input[name='edit_percentage']")  .val(percentage)		
		$("input[name='edit_max_discount']")  .val(max_discount)		
		$("input[name='edit_budget']")  .val(budget)		
		$("input[name='edit_num_orders_one_user']")  .val(num_orders_one_user)		
				
	})

	$('.generalDelete').on('click',function(e){ 
		var result = confirm('هل تريد استمرار عملية الحذف ؟ ');
		if(result == false){
			e.preventDefault();
		}
	});

</script>
@endsection