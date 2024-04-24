@extends('dashboard.layout.master')
	@section('title')
	 باقات الاشتراك 
	@endsection

@section('content')
<div class="panel panel-flat">
	<div class="panel-heading">
		<h5 class="panel-title">قائمة باقات الاشتراك للقادة</h5>
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
				<button class="btn bg-blue btn-block btn-float btn-float-lg openAddModal" type="button" data-toggle="modal" data-target="#exampleModal"><i class="icon-plus3"></i> <span>اضافة باقة</span></button>
			</div>
			<div class="col-xs-3">
				<button class="btn bg-purple-300 btn-block btn-float btn-float-lg" type="button"><i class="icon-list-numbered"></i> <span>عدد الباقات : {{count($packages)}} </span> </button>
			</div>	
			<div class="col-xs-3">
				<a href="#"><button class="btn bg-red-300 btn-block btn-float btn-float-lg" type="button"><span style="font-size:large;">{{$packages->sum('num_sells')}} </span><span>اجمالي عدد المبيعات </span> </button></a>
			</div>
			<div class="col-xs-3">
				<a href="#"><button class="btn bg-red-300 btn-block btn-float btn-float-lg" type="button"><span style="font-size:large;">{{$total}} </span><span>اجمالي المبيعات </span> </button></a>
			</div>
		</div>
	</div>
	<!-- /buttons -->
	@csrf
	<table class="table datatable-basic">
		<thead>
			<tr>
				<th>الاسم بالعربية</th>
				<th>الاسم بالانجليزية</th>
				<!-- <th>عدد الايام</th> -->
				<th>السعر</th>
				<th>السعر بعد الاضافة</th>
				<th>نسبة الاضافة</th>
				<th>الوصف بالعربية</th>
				<th>الوصف بالانجليزية</th>
				<th>المبيعات</th>
				{{-- <th>نوع العميل</th> --}}
				<th>تاريخ الاضافه</th>
				<th>التحكم</th>
			</tr>
		</thead>
		<tbody>
			@foreach($packages as $package)
				<tr>
					<td>{{$package->name_ar}}</td>
					<td>{{$package->name_en}}</td>
                    <td>{{$package->price}}</td>
                    <td>{{$package->offer_price}}</td>
                    <td>{{$package->offer_percent}}</td>
                    <!-- <td>{{$package->num_days}}</td> -->
                    <td>{{$package->description_ar}}</td>
					<td>{{$package->description_en}}</td>
					<td>{{$package->num_sells}}</td>
					{{-- <td>{{$package->type == 'user'? 'عميل' : 'كابتن'}}</td> --}}
					<td>{{date('Y-m-d H:i',strtotime($package->created_at))}}</td>
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
									data-id="{{$package->id}}" 
									data-namear="{{$package->name_ar}}" 
									data-nameen="{{$package->name_en}}"
									data-descriptionar="{{$package->description_ar}}" 
									data-descriptionen="{{$package->description_en}}" 
									data-price="{{$package->price}}" 
									data-offerprice="{{$package->offer_price}}" 
									data-offerpercent="{{$package->offer_percent}}" 
									data-numdays="{{$package->num_days}}"
									{{-- data-type="{{$package->type}}" --}}
									/>
									<i class="icon-pencil7"></i>تعديل
									</a>
								</li>
								<form action="{{route('DeleteCaptainPackage')}}" method="POST" id="DeleteCouponForm">
									{{csrf_field()}}
									<input type="hidden" name="id" value="{{$package->id}}">
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
	        <h5 class="modal-title" id="exampleModalLabel">أضافة باقة جديدة</h5>
	      </div>
	      <div class="modal-body">
	        <div class="row">
	        	<form action="{{route('createCaptainPackage')}}" method="POST"  enctype="multipart/form-data" >
	        		{{csrf_field()}}

	        		<div class="row">
		        		<div class="col-sm-12">

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
		        				<label>السعر</label>
		        		    </div>
		        			<div class="col-sm-8">
		        				<input type="number" step="0.01" name="price" class="form-control" placeholder="السعر"/>
						    </div>
						    <div class="col-sm-4">
		        				<label>السعر بعد الاضافة</label>
		        		    </div>
		        			<div class="col-sm-8">
		        				<input type="number" step="0.01" name="offer_price" class="form-control" placeholder="السعر بعد الاضافة"/>
						    </div>
						    <div class="col-sm-4">
		        				<label>نسبة الاضافة</label>
		        		    </div>
		        			<div class="col-sm-8">
		        				<input type="text" name="offer_percent" class="form-control" placeholder="نسبة الاضافة"/>
						    </div>
						    <!-- <div class="col-sm-4">
		        				<label>عدد الايام</label>
		        		    </div>
		        			<div class="col-sm-8">
		        				<input type="number" step="1" name="num_days" class="form-control" placeholder="عدد الايام"/>
						    </div>	 -->					    
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
{{-- 
							<div class="col-sm-4">
								<label>نوع العميل</label>
							</div>
							<div class="col-sm-8">
								<select name="type" id="" class="form-control">
									<option value="user">عميل</option>
									<option value="provider">كابتن</option>
								</select>
							</div> --}}
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
	        <h5 class="modal-title" id="exampleModalLabel"> تعديل الباقة </h5>
	      </div>
	      <div class="modal-body">
	        	<form action="{{route('updateCaptainPackage')}}" method="POST"  enctype="multipart/form-data" >
	        		{{csrf_field()}}
	        		<input type="hidden" name="id" value="">
	        		<div class="row">
		        		<div class="col-sm-12">
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
		        				<label>السعر</label>
		        		    </div>
		        			<div class="col-sm-8">
		        				<input type="number" step="0.01" name="edit_price" class="form-control" placeholder="السعر"/>
						    </div>
						    <div class="col-sm-4">
		        				<label>السعر بعد الاضافة</label>
		        		    </div>
		        			<div class="col-sm-8">
		        				<input type="number" step="0.01" name="edit_offer_price" class="form-control" placeholder="السعر بعد الاضافة"/>
						    </div>
						    <div class="col-sm-4">
		        				<label>نسبة الاضافة</label>
		        		    </div>
		        			<div class="col-sm-8">
		        				<input type="text" name="edit_offer_percent" class="form-control" placeholder="نسبة الاضافة"/>
						    </div>
<!-- 						    <div class="col-sm-4">
		        				<label>عدد الايام</label>
		        		    </div>
		        			<div class="col-sm-8">
		        				<input type="number" step="1" name="edit_num_days" class="form-control" placeholder="عدد الايام"/>
						    </div>	 -->					    
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
							{{-- <div class="col-sm-4">
								<label>نوع العميل</label>
							</div>
							<div class="col-sm-8">
								<select name="edit_type" id="" class="form-control">
									<option value="user">عميل</option>
									<option value="provider">كابتن</option>
								</select>
							</div> --}}
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
		var id                = $(this).data('id')
		var name_ar           = $(this).data('namear')
		var name_en           = $(this).data('nameen')
		var price             = $(this).data('price')
		var offer_price             = $(this).data('offerprice')
		var offer_percent           = $(this).data('offerpercent')
		// var num_days          = $(this).data('numdays')
		var description_ar    = $(this).data('descriptionar')
		var description_en    = $(this).data('descriptionen')
		// var type    = $(this).data('type')
		//set values in modal inputs
		$("input[name='id']")            .val(id)
		$("input[name='edit_name_ar']")   .val(name_ar)
		$("input[name='edit_name_en']")   .val(name_en)
		$("input[name='edit_price']")      .val(price)
		$("input[name='edit_offer_price']")      .val(offer_price)
		$("input[name='edit_offer_percent']")      .val(offer_percent)
		// $("select[name='edit_type']")      .val(type)
		// $("input[name='edit_num_days']")      .val(num_days)
		$("textarea[name='edit_description_ar']") .val(description_ar)
		$("textarea[name='edit_description_en']") .val(description_en)
});

	$('.generalDelete').on('click',function(e){ 
		var result = confirm('هل تريد استمرار عملية الحذف ؟ ');
		if(result == false){
			e.preventDefault();
		}
	});
</script>
@endsection