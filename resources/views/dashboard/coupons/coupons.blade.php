@extends('dashboard.layout.master')
	@section('title')
	كوبونات الخصم
	@endsection

@section('content')
<div class="panel panel-flat">
	<div class="panel-heading">
		<h5 class="panel-title">قائمة كوبونات الخصم</h5>
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
			<div class="col-xs-6">
				<a href="#"><button class="btn bg-red-300 btn-block btn-float btn-float-lg" type="button"><span style="font-size:large;">{{round($coupons->sum('total_cost'),2)}} </span><span> اجمالي الصرف  </span> </button></a>
			</div>
			
			<div class="col-xs-2">
				<button class="btn bg-blue btn-block btn-float btn-float-lg openAddModal" type="button" data-toggle="modal" data-target="#exampleModal"><i class="icon-plus3"></i> <span>اضافة كوبون</span></button>
			</div>


			<div class="col-xs-2">
				<button class="btn bg-purple-300 btn-block btn-float btn-float-lg" type="button"><i class="icon-list-numbered"></i> <span>عدد الكوبونات : {{count($coupons)}} </span> </button>
			</div>	

		</div>
	</div>
	<!-- /buttons -->
	@csrf
	<table class="table datatable-basic">
		<thead>
			<tr>
				<th>الكود</th>
				<th>نوع الخصم</th>
				<th>القيمة</th>
				<th>مرات الاستخدام</th>
				<th>مرات الاستخدام للشخص</th>
				<th>الحد الاقصي للخصم</th>
				<th>ميزانية الكوبون</th>
				<th>اجمالي الصرف</th>
				<th>مستخدم</th>
				<th>الانتهاء</th>
				<th>تاريخ الاضافه</th>
				<th>التحكم</th>
			</tr>
		</thead>
		<tbody>
			@foreach($coupons as $coupon)
				<tr>
					<td>{{$coupon->code}}   </td>
					<td>{{$coupon->type}}</td>
					<td>{{$coupon->value}}</td>
                    <td>{{$coupon->num_to_use}}</td>
                    <td>{{$coupon->num_to_use_person}}</td>
                    <td>{{$coupon->max_discount}}</td>
                    <td>{{$coupon->budget}}</td>
                    <td>{{$coupon->total_cost}}</td>
                    <td>{{$coupon->num_used}}</td>
                    <td>{{$coupon->end_at}}</td>
					<td>{{$coupon->created_at->diffForHumans()}}</td>
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
									data-id="{{$coupon->id}}" 
									data-code="{{$coupon->code}}" 
									data-type="{{$coupon->type}}" 
									data-value="{{$coupon->value}}"
									data-numtouse="{{$coupon->num_to_use}}"
									data-numused="{{$coupon->num_used}}"

									data-numtouseperson="{{$coupon->num_to_use_person}}"
									data-maxdiscount="{{$coupon->max_discount}}"
									data-budget="{{$coupon->budget}}"
									data-totalcost="{{$coupon->total_cost}}"

									data-endat="{{$coupon->end_at}}">
									<i class="icon-pencil7"></i>تعديل
									</a>
								</li>
								<!-- delete button -->
								<form action="{{route('DeleteCoupon')}}" method="POST" id="DeleteCouponForm">
									{{csrf_field()}}
									<input type="hidden" name="id" value="{{$coupon->id}}">
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
	        <h5 class="modal-title" id="exampleModalLabel">أضافة كوبون جديد</h5>
	      </div>
	      <div class="modal-body">
	        <div class="row">
	        	<form action="{{route('createCoupon')}}" method="POST">
	        		{{csrf_field()}}

	        		<div class="row">
		        		<div class="col-sm-8">
		        			<input type="text" name="code" class="form-control" placeholder="الكود" >
		        		</div>
		        		<div class="col-sm-4">
		        			<button type="button" class="btn btn-secondary generateCode" >انشاء كود</button>
		        		</div>
		        		<div class="col-sm-12">
		        			<div class="col-sm-4">
		        				<label>النوع</label>
		        		    </div>
		        			<div class="col-sm-8">
			        			<select name="type" class="form-control" id="type">
									<option value="0" disabled selected> النوع </option>
										<option value="percentage">نسبة مئوية</option>
										<option value="amount">قيمة ثابتة</option>
								</select>
						    </div>
						    <div class="col-sm-4">
		        			   <label>القيمة</label>
		        		    </div>
		        			<div class="col-sm-8">
		        				<input type="number" name="value" class="form-control" placeholder="القيمة " min="1" step="0.01">
		        			</div>

		        			<div class="col-sm-4">
		        			   <label>عدد مرات الاستخدام</label>
		        		    </div>
		        			<div class="col-sm-8">
		        				<input type="number" name="num_to_use" class="form-control" placeholder="مرات الاستخدام" min="1">
		        			</div>
							<div class="col-sm-4">
								<label> عدد مرات الاستخدام للفرد</label>
							 </div>
							 <div class="col-sm-8">
								 <input type="number" name="num_to_use_person" class="form-control" placeholder="عدد مرات الاستخدام للفرد" min="1">
							 </div>
							 <div class="col-sm-4">
								<label>الحد الاقصي للخصم</label>
							 </div>
							 <div class="col-sm-8">
								 <input type="number" name="max_discount" class="form-control" placeholder="الحد الاقصي للخصم" min="0" step="0.01">
							 </div>
							 <div class="col-sm-4">
								<label>ميزانية الكوبون</label>
							 </div>
							 <div class="col-sm-8">
								 <input type="number" name="budget" class="form-control" placeholder="ميزانية الكوبون" min="0" step="0.01">
							 </div>

		        			<div class="col-sm-4">
		        			   <label>تاريخ الانتهاء</label>
		        		    </div>
		        			<div class="col-sm-8">
		        				<input type="date" name="end_at" class="form-control" placeholder="تاريخ الانتهاء">
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
	        <h5 class="modal-title" id="exampleModalLabel"> تعديل الكوبون </h5>
	      </div>
	      <div class="modal-body">
	        	<form action="{{route('updateCoupon')}}" method="post" >
	        		{{csrf_field()}}
	        		<input type="hidden" name="id" value="">
	        		<div class="row">
		        		<div class="col-sm-8">
		        			<input type="text" name="edit_code" class="form-control" placeholder="الكود" >
		        		</div>
		        		<div class="col-sm-4">
		        			<button type="button" class="btn btn-secondary generateCode" >انشاء كود</button>
		        		</div>
		        		<div class="col-sm-12">
		        			<div class="col-sm-4">
		        				<label>النوع</label>
		        		    </div>
		        			<div class="col-sm-8">
			        			<select name="edit_type" class="form-control" id="edit_type">
									<option value="0" disabled selected> النوع </option>
										<option value="percentage">نسبة مئوية</option>
										<option value="amount">قيمة ثابتة</option>
								</select>
						    </div>
						    <div class="col-sm-4">
		        			   <label>القيمة</label>
		        		    </div>
		        			<div class="col-sm-8">
		        				<input type="number" name="edit_value" class="form-control" placeholder="القيمة " min="1" step="0.01">
		        			</div>
		        			<div class="col-sm-4">
		        			   <label>عدد مرات الاستخدام</label>
		        		    </div>
		        			<div class="col-sm-8">
		        				<input type="number" name="edit_num_to_use" class="form-control" placeholder="مرات الاستخدام" min="1">
		        			</div>
		        			<div class="col-sm-4">
		        			   <label>المرات المستخدمة</label>
		        		    </div>
		        			<div class="col-sm-8">
		        				<input type="number" name="edit_num_used" class="form-control" placeholder="المرات المستخدمة" min="1" disabled="disabled">
		        			</div>	

							<div class="col-sm-4">
								<label> عدد مرات الاستخدام للفرد</label>
							 </div>
							 <div class="col-sm-8">
								 <input type="number" name="edit_num_to_use_person" class="form-control" placeholder="عدد مرات الاستخدام للفرد" min="1">
							 </div>
							 <div class="col-sm-4">
								<label>الحد الاقصي للخصم</label>
							 </div>
							 <div class="col-sm-8">
								 <input type="number" name="edit_max_discount" class="form-control" placeholder="الحد الاقصي للخصم" min="0" step="0.01">
							 </div>
							 <div class="col-sm-4">
								<label>ميزانية الكوبون</label>
							 </div>
							 <div class="col-sm-8">
								 <input type="number" name="edit_budget" class="form-control" placeholder="ميزانية الكوبون" min="0" step="0.01">
							 </div>
							 <div class="col-sm-4">
								<label> اجمالي الصرف</label>
							 </div>
							 <div class="col-sm-8">
								 <input type="number" name="edit_total_cost" class="form-control" placeholder="اجمالي الصرف" min="0" step="0.01" disabled="disabled">
							 </div>
		        			<div class="col-sm-4">
		        			   <label>تاريخ الانتهاء</label>
		        		    </div>
		        			<div class="col-sm-8">
		        				<input type="date" name="edit_end_at" class="form-control" placeholder="تاريخ الانتهاء">
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

    $('.generateCode').on('click' ,function(e) { //any select change on the dropdown with id country trigger this code
        e.preventDefault();
        $.get("<?=url('admin/generateCode/');?>", function(data) {
		        $("input[name='code']").val(data)
		        $("input[name='edit_code']").val(data)
                console.log(data);              
        });

    });

	$('.openEditmodal').on('click',function(){
		//get valus 
		var id         = $(this).data('id')
		var code       = $(this).data('code')
		var type       = $(this).data('type')
		var value      = $(this).data('value')
		var num_to_use = $(this).data('numtouse')
		var num_used   = $(this).data('numused')

		var num_to_use_person   = $(this).data('numtouseperson')
		var max_discount    = $(this).data('maxdiscount')
		var budget          = $(this).data('budget')
		var total_cost      = $(this).data('totalcost')

		var end_at     = $(this).data('endat')

		//set values in modal inputs
		$("input[name='id']")             .val(id)
		$("input[name='edit_code']")      .val(code)
		$("select[name='edit_type']")     .val(type)
		$("input[name='edit_value']")     .val(value)
		$("input[name='edit_num_to_use']").val(num_to_use)
		$("input[name='edit_num_used']")  .val(num_used)
		$("input[name='edit_num_to_use_person']")  .val(num_to_use_person)
		$("input[name='edit_max_discount']")  .val(max_discount)
		$("input[name='edit_budget']")  .val(budget)
		$("input[name='edit_total_cost']")  .val(total_cost)
		$("input[name='edit_end_at']")    .val(end_at)

	})

	$('.generalDelete').on('click',function(e){ 
		var result = confirm('هل تريد استمرار عملية الحذف ؟ ');
		if(result == false){
			e.preventDefault();
		}
	});
</script>

@endsection