@extends('dashboard.layout.master')
	@section('title')
	المنتجات
	@endsection
@section('content')
<div class="panel panel-flat">
	<div class="panel-heading">
		<h5 class="panel-title">{{ ($store_id != '')? (($store)?'منتجات '.$store->name_ar:'المنتجات') :'المنتجات'}} </h5>
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
				<button class="btn bg-blue btn-block btn-float btn-float-lg openAddModal" type="button" data-toggle="modal" data-target="#exampleModal"><i class="icon-plus3"></i> <span>اضافة منتج</span></button>
			</div>			
			<div class="col-xs-3">
				<button class="btn bg-purple-300 btn-block btn-float btn-float-lg" type="button"><i class="icon-list-numbered"></i> <span>عدد المنتجات : {{count($products)}} </span> </button>
			</div>
		</div>
	</div>
	<!-- /buttons -->

	<table class="table datatable-basic">
		<thead>
			<tr>
				<!-- <th>الصورة</th> -->
				<th>الاسم بالعربية</th>
				<th>الاسم بالانجليزية</th>
				<th>المتجر</th>
				<th>القسم بالمنيو</th>
				<th>السعر</th>
				<th>الوصف بالعربية</th>
				<th>الوصف بالانجليزية</th>
				<th>تاريخ الاضافة</th>
				<th>التحكم</th>
			</tr>
		</thead>
		<tbody>
			<?php $currency = setting('site_currency_ar');?>
			@foreach($products as $product)
				<tr>
					<!-- <td><img src="{{asset('img/store/products/'.$product->image)}}" class="img-circle" alt=""></td> -->
					<td>{{$product->name_ar}}</td>
					<td>{{$product->name_en}}</td>
					<td>{{($product->store)?$product->store->name_ar:''}}</td>
					<td>{{($product->menuCategory)?$product->menuCategory->name_ar:''}}</td>
					<td>{{$product->price}} {{$currency}}</td>
					<td>{{str_limit($product->description_ar,30)}}</td>
					<td>{{str_limit($product->description_en,30)}}</td>
					<td>{{Carbon\Carbon::parse($product->created_at)->format('d/m/Y - H:i A')}}</td>
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
									data-id="{{$product->id}}" 
									data-namear="{{$product->name_ar}}" 
									data-nameen="{{$product->name_en}}" 
									data-storeid="{{$product->store_id}}"
									data-menucategoryid="{{$product->menu_category_id}}"
									data-price="{{$product->price}}"
									data-descriptionar="{{$product->description_ar}}"
									data-descriptionen="{{$product->description_en}}"
									data-image="{{$product->image}}" 
									>
									<i class="icon-pencil7"></i>تعديل
									</a>
								</li>
								<!-- delete button -->
								<form action="{{route('DeleteProduct')}}" method="POST">
									{{csrf_field()}}
									<input type="hidden" name="id" value="{{$product->id}}">
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
	        <h5 class="modal-title" id="exampleModalLabel">أضافة منتج جديد</h5>
	      </div>
	      <div class="modal-body">
	        <div class="row">
	        	<form action="{{route('createProduct')}}" id="addproduct" method="post" enctype="multipart/form-data">
	        		{{csrf_field()}}
	        		<input type="hidden" name="store_id" value="{{$store_id}}"> 
	        		<div class="row">
                        <div class="col-sm-4">
	        				<label>القسم بالمنيو</label>
	        		    </div>
		        		<div class="col-sm-8" >
		        			<select name="menu_category_id" class="form-control" id="menu_id">
		        				<option selected="true" disabled="disabled" value=""> القسم بالمنيو </option>
			        			@foreach($menuCategories as $category)
	                                <option value="{{$category->id}}" >{{$category->name_ar}}</option>
			        			@endforeach
		        			</select>
					    </div>						            			
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
		        		<div class="col-sm-8" >
		        			<input type="text" name="name_ar" class="form-control" placeholder="اسم المنتج بالعربية">
		        		</div>
                        <div class="col-sm-4">
	        				<label>الاسم بالانجليزية</label>
	        		    </div>
		        		<div class="col-sm-8" >
		        			<input type="text" name="name_en" class="form-control" placeholder="اسم المنتج بالانجليزية">
		        		</div>	
                        <div class="col-sm-4">
	        				<label>السعر</label>
	        		    </div>
		        		<div class="col-sm-8" >
		        			<input type="number" name="price" class="form-control" placeholder="سعر المنتج" step="0.01" min="0"/>
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
	        <h5 class="modal-title" id="exampleModalLabel"> تعديل المنتج : <span class="productName"></span> </h5>
	      </div>
	      <div class="modal-body">
	        	<form action="{{route('updateProduct')}}" method="post" enctype="multipart/form-data">
	        		{{csrf_field()}}
	        		<input type="hidden" name="id" value="">
	        		<input type="hidden" name="edit_store_id" value="{{$store_id}}"> 
	        		<div class="row">
                        <div class="col-sm-4">
	        				<label>القسم بالمنيو</label>
	        		    </div>
		        		<div class="col-sm-8" >
		        			<select id="editMenuCategory" name="edit_menu_category_id" class="form-control">
		        				<option disabled="disabled" value=""> القسم بالمنيو </option>
			        			@foreach($menuCategories as $category)
	                                <option value="{{$category->id}}">{{$category->name_ar}}</option>
			        			@endforeach
		        			</select>
					    </div>						            			
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
		        		<div class="col-sm-8" >
		        			<input type="text" name="edit_name_ar" class="form-control" placeholder="اسم المنتج بالعربية">
		        		</div>
                        <div class="col-sm-4">
	        				<label>الاسم بالانجليزية</label>
	        		    </div>
		        		<div class="col-sm-8" >
		        			<input type="text" name="edit_name_en" class="form-control" placeholder="اسم المنتج بالانجليزية">
		        		</div>	
                        <div class="col-sm-4">
	        				<label>السعر</label>
	        		    </div>
		        		<div class="col-sm-8" >
		        			<input type="number" name="edit_price" class="form-control" placeholder="سعر المنتج" step="0.01" min="0"/>
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

    // $("#addproduct").submit(function(){
    //     var store = $('#store_id').find(":selected").val();
    //     if(store == ''){
    //       alert("يجب عليك اختيار المتجر.");
    //       return false;        	
    //     }
    // });

	$('.openEditmodal').on('click',function(){
		//get valus 
		var id             = $(this).data('id')
		var store_id       = $(this).data('storeid')
		var menu_category_id = $(this).data('menucategoryid')
		var image          = $(this).data('image')
		var name_ar        = $(this).data('namear')
		var name_en        = $(this).data('nameen')
		var price          = $(this).data('price')
		var description_ar = $(this).data('descriptionar')
		var description_en = $(this).data('descriptionen')


		//set values in modal inputs
		$("input[name='id']")                    .val(id)
		$("input[name='edit_store_id']")         .val(store_id)
		$("input[name='edit_name_ar']")          .val(name_ar)
		$("input[name='edit_name_en']")          .val(name_en)
		$("input[name='edit_price']")            .val(price)
		$("textarea[name='edit_description_ar']").val(description_ar);
		$("textarea[name='edit_description_en']").val(description_en);
		var link = "{{asset('img/store/products')}}" +'/'+ image;
		$(".photo").attr('src',link);

		$('.productName').text(name_ar)

		// $('#editStore option').each(function(){
		// 	if($(this).val() == store_id){
		// 		$(this).attr('selected','selected')
		// 	}
		// });	
		$('#editMenuCategory option').each(function(){
			if($(this).val() == menu_category_id){
				$(this).attr('selected','selected')
			}
		});				
	})

	$('.generalDelete').on('click',function(e){ 
		var result = confirm('هل تريد استمرار عملية الحذف ؟ ');
		if(result == false){
			e.preventDefault();
		}
	});

</script>

<!-- other code -->
<script type="text/javascript">
	function addChooseFile(){$("input[name='image']").click()}
	function ChooseFile(){$("input[name='edit_image']").click()}
</script>
<!-- /other code -->

@endsection