@extends('dashboard.layout.master')
	@section('title')
	قائمة الأسعار
	@endsection
@section('content')
<div class="panel panel-flat">
	<div class="panel-heading">
		<h5 class="panel-title"> قائمة الأسعار </h5>
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
				<button class="btn bg-blue btn-block btn-float btn-float-lg openAddModal" type="button" data-toggle="modal" data-target="#exampleModal"><i class="icon-plus3"></i> <span>اضافة خطة أسعار</span></button>
			</div>			
			<div class="col-xs-3">
				<button class="btn bg-purple-300 btn-block btn-float btn-float-lg" type="button"><i class="icon-list-numbered"></i> <span>عدد الخطط : {{count($prices)}} </span> </button>
			</div>
		</div>
	</div>
	<!-- /buttons -->

	<table class="table datatable-basic">
		<thead>
			<tr>
				<th>الفئه</th>
				<th>فتح العداد</th>
				<th>سعر KM</th>
				<th>سعر دقيقة الانتظار</th>
				<th>الحد الأدني للمشوار </th>
				<th>إلغاء الراكب للرحلة</th>
				<th>إلالغاء القائد للرحلة</th>
				<th>التحكم</th>
			</tr>
		</thead>
		<tbody>
			@foreach($prices as $price)
				<tr>
					<td>{{($price->cartype)? $price->cartype->name_ar:''}}</td>
					<td>{{$price->counter}}</td>
					<td>{{$price->km_price}}</td>
					<td>{{$price->waiting_minute}}</td>
					<td>{{$price->min_price}}</td>
					<td>{{$price->client_cancel}}</td>
					<td>{{$price->captain_cancel}}</td>
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
									data-id="{{$price->id}}"  
									data-type="{{$price->type}}" 
									data-cartypeid="{{$price->car_type_id}}" 
									data-counter="{{$price->counter}}" 
									data-kmprice="{{$price->km_price}}"
									data-waitingminute="{{$price->waiting_minute}}"
									data-minprice="{{$price->min_price}}"
									data-clientcancel="{{$price->client_cancel}}"
									data-captaincancel="{{$price->captain_cancel}}"
									>
									<i class="icon-pencil7"></i>تعديل
									</a>
								</li>
								<!-- delete button -->
								<form action="{{route('DeletePrice')}}" method="POST">
									{{csrf_field()}}
									<input type="hidden" name="id" value="{{$price->id}}">
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
	        <h5 class="modal-title" id="exampleModalLabel">أضافة خطة أسعار جديدة</h5>
	      </div>
	      <div class="modal-body">
	        <div class="row">
	        	<form action="{{route('createPrice')}}" method="post" enctype="multipart/form-data">
	        		{{csrf_field()}}
	        		<div class="row">
                        <div class="col-sm-4">
	        				<label>الفئة</label>
	        		    </div>
		        		<div class="col-sm-6" >
		        	  	  <select name="car_type_id" class="form-control">
		        	  	  	@foreach($cartypes as $type)
		        	  	  	<option value="{{$type->id}}"> {{$type->name_ar}}</option>
		        	  	  	@endforeach
		        	  	  </select>
		        		</div>

                        <div class="col-sm-4">
	        				<label>النوع</label>
	        		    </div>
		        		<div class="col-sm-6" >
		        	  	  <select name="type" class="form-control">
		        	  	  	<option value="people"> أشخاص</option>
		        	  	  	<option value="goods"> بضائع</option>
		        	  	  </select>
		        		</div>

                        <div class="col-sm-4">
	        				<label>فتح العداد</label>
	        		    </div>
		        		<div class="col-sm-6" >
		        			<input type="number" name="counter" class="form-control" placeholder="0" min="0" step="0.01"/>
		        		</div>	
		        		<div class="col-sm-2" >
                            وحده
		        		</div>

                        <div class="col-sm-4">
	        				<label>سعر KM</label>
	        		    </div>
		        		<div class="col-sm-6" >
		        			<input type="number" name="km_price" class="form-control" placeholder="0" min="0" step="0.01"/>
		        		</div>					
		        		<div class="col-sm-2" >
                            وحده
		        		</div>

                        <div class="col-sm-4">
	        				<label>سعر دقيقة الانتظار</label>
	        		    </div>
		        		<div class="col-sm-6" >
		        			<input type="number" name="waiting_minute" class="form-control" placeholder="0" min="0" step="0.01"/>
		        		</div>					
		        		<div class="col-sm-2" >
                            وحده
		        		</div>	

                        <div class="col-sm-4">
	        				<label>الحد الأدني للمشوار</label>
	        		    </div>
		        		<div class="col-sm-6" >
		        			<input type="number" name="min_price" class="form-control" placeholder="0" min="0" step="0.01"/>
		        		</div>					
		        		<div class="col-sm-2" >
                            وحده
		        		</div>			        		

                        <div class="col-sm-4">
	        				<label>إلغاء الراكب للرحلة</label>
	        		    </div>
		        		<div class="col-sm-6" >
		        			<input type="number" name="client_cancel" class="form-control" placeholder="0" min="0" step="0.01"/>
		        		</div>					
		        		<div class="col-sm-2" >
                            وحده
		        		</div>
                        
                        <div class="col-sm-4">
	        				<label>إلغاء القائد للرحلة</label>
	        		    </div>
		        		<div class="col-sm-6" >
		        			<input type="number" name="captain_cancel" class="form-control" placeholder="0" min="0" step="0.01"/>
		        		</div>					
		        		<div class="col-sm-2" >
                            وحده
		        		</div>
	        		</div>

					<div class="row"> 
				      <div class="col-sm-12">
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
	        <h5 class="modal-title" id="exampleModalLabel"> تعديل خطة أسعار </h5>
	      </div>
	      <div class="modal-body">
	        	<form action="{{route('updatePrice')}}" method="post" enctype="multipart/form-data">
	        		{{csrf_field()}}
	        		<input type="hidden" name="id" value="">
	        		<div class="row">
                        <div class="col-sm-4">
	        				<label>الفئة</label>
	        		    </div>
		        		<div class="col-sm-6" >
		        	  	  <select name="edit_car_type_id" id="edit_car_type_id" class="form-control">
		        	  	  	@foreach($cartypes as $type)
		        	  	  	<option value="{{$type->id}}"> {{$type->name_ar}}</option>
		        	  	  	@endforeach
		        	  	  </select>
		        		</div>

                        <div class="col-sm-4">
	        				<label>النوع</label>
	        		    </div>
		        		<div class="col-sm-6" >
		        	  	  <select name="edit_type" id="edit_type" class="form-control">
		        	  	  	<option value="people"> أشخاص</option>
		        	  	  	<option value="goods"> بضائع</option>
		        	  	  </select>
		        		</div>

                        <div class="col-sm-4">
	        				<label>فتح العداد</label>
	        		    </div>
		        		<div class="col-sm-6" >
		        			<input type="number" name="edit_counter" class="form-control" placeholder="0" min="0" step="0.01"/>
		        		</div>	
		        		<div class="col-sm-2" >
                            وحده
		        		</div>

                        <div class="col-sm-4">
	        				<label>سعر KM</label>
	        		    </div>
		        		<div class="col-sm-6" >
		        			<input type="number" name="edit_km_price" class="form-control" placeholder="0" min="0" step="0.01"/>
		        		</div>					
		        		<div class="col-sm-2" >
                            وحده
		        		</div>	        		
	
                        <div class="col-sm-4">
	        				<label>سعر دقيقة الانتظار</label>
	        		    </div>
		        		<div class="col-sm-6" >
		        			<input type="number" name="edit_waiting_minute" class="form-control" placeholder="0" min="0" step="0.01"/>
		        		</div>					
		        		<div class="col-sm-2" >
                            وحده
		        		</div>	

                        <div class="col-sm-4">
	        				<label>الحد الأدني للمشوار</label>
	        		    </div>
		        		<div class="col-sm-6" >
		        			<input type="number" name="edit_min_price" class="form-control" placeholder="0" min="0" step="0.01"/>
		        		</div>					
		        		<div class="col-sm-2" >
                            وحده
		        		</div>			        		

                        <div class="col-sm-4">
	        				<label>إلغاء الراكب للرحلة</label>
	        		    </div>
		        		<div class="col-sm-6" >
		        			<input type="number" name="edit_client_cancel" class="form-control" placeholder="0" min="0" step="0.01"/>
		        		</div>					
		        		<div class="col-sm-2" >
                            وحده
		        		</div>

                        <div class="col-sm-4">
	        				<label>إلغاء القائد للرحلة</label>
	        		    </div>
		        		<div class="col-sm-6" >
		        			<input type="number" name="edit_captain_cancel" class="form-control" placeholder="0" min="0" step="0.01"/>
		        		</div>					
		        		<div class="col-sm-2" >
                            وحده
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
		var id                        = $(this).data('id')
		var type                      = $(this).data('type')
		var cartypeid                 = $(this).data('cartypeid')
		var counter                   = $(this).data('counter')
		var kmprice                   = $(this).data('kmprice')
		var waitingminute             = $(this).data('waitingminute')
		var minprice                  = $(this).data('minprice')
		var clientcancel              = $(this).data('clientcancel')
		var captaincancel             = $(this).data('captaincancel')

		//set values in modal inputs 
		$("input[name='id']")                      .val(id)
		$("input[name='edit_counter']")            .val(counter)
		$("input[name='edit_km_price']")           .val(kmprice);
		$("input[name='edit_waiting_minute']")     .val(waitingminute);
		$("input[name='edit_min_price']")          .val(minprice);
		$("input[name='edit_client_cancel']")      .val(clientcancel);
		$("input[name='edit_captain_cancel']")     .val(captaincancel);
		$('#edit_car_type_id option').each(function(){
			if($(this).val() == cartypeid){
				$(this).attr('selected','selected')
			}
		});	

		$('#edit_type option').each(function(){
			if($(this).val() == type){
				$(this).attr('selected','selected')
			}
		});	


	});

	$('.generalDelete').on('click',function(e){ 
		var result = confirm('هل تريد استمرار عملية الحذف ؟ ');
		if(result == false){
			e.preventDefault();
		}
	});
</script>

@endsection