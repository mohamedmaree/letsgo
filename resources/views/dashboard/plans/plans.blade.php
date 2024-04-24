@extends('dashboard.layout.master')
	@section('title')
	الخطط والمستويات
	@endsection
@section('content')
<div class="panel panel-flat">
	<div class="panel-heading">
		<h5 class="panel-title"> الخطط والمستويات </h5>
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
			<!-- <div class="col-xs-3">
				<button class="btn bg-blue btn-block btn-float btn-float-lg openAddModal" type="button" data-toggle="modal" data-target="#exampleModal"><i class="icon-plus3"></i> <span>اضافة مستوي</span></button>
			</div>	 -->		
			<div class="col-xs-3">
				<button class="btn bg-purple-300 btn-block btn-float btn-float-lg" type="button"><i class="icon-list-numbered"></i> <span>عدد المستويات : {{count($plans)}} </span> </button>
			</div>
		</div>
	</div>
	<!-- /buttons -->

	<table class="table datatable-basic">
		<thead>
			<tr>
				<th>الاسم بالعربية</th>
				<th>الاسم بالانجليزية</th>
				<th>ساعات العمل</th>
				<th>معدل القبول</th>
				<th>التقييم</th>
				<th>عدد الرحلات</th>
				<th>العلاوة</th>
				<th>عدد القاده</th>
				<th>تاريخ الاضافة</th>
				<th>التحكم</th>
			</tr>
		</thead>
		<tbody>
			@foreach($plans as $plan)
				<tr>
					<td>{{$plan->name_ar}}</td>
					<td>{{$plan->name_en}}</td>
					<td>{{$plan->working_hours}} ساعة</td>
					<td>{{$plan->acceptance_rate}} %</td>
					<td>{{$plan->rate}} </td>
					<td>{{$plan->num_orders}} رحلة</td>
					<td>{{$plan->reward}} وحده</td>
					<td>{{$plan->num_users}} عضو</td>
					<td>{{$plan->created_at->diffForHumans()}}</td>
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
									data-id="{{$plan->id}}"  
									data-namear="{{$plan->name_ar}}" 
									data-nameen="{{$plan->name_en}}" 
									data-workinghours="{{$plan->working_hours}}"
									data-acceptancerate="{{$plan->acceptance_rate}}"
									data-rate="{{$plan->rate}}"
									data-numorders="{{$plan->num_orders}}"
									data-reward="{{$plan->reward}}"
									>
									<i class="icon-pencil7"></i>تعديل
									</a>
								</li>
								<!-- delete button -->
								<form action="{{route('DeletePlan')}}" method="POST">
									{{csrf_field()}}
									<input type="hidden" name="id" value="{{$plan->id}}">
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
	        <h5 class="modal-title" id="exampleModalLabel">أضافة مستوي جديد</h5>
	      </div>
	      <div class="modal-body">
	        <div class="row">
	        	<form action="{{route('createPlan')}}" method="post" enctype="multipart/form-data">
	        		{{csrf_field()}}
	        		<div class="row">
                        <div class="col-sm-4">
	        				<label>الاسم بالعربية</label>
	        		    </div>
		        		<div class="col-sm-6" >
		        			<input type="text" name="name_ar" class="form-control" placeholder="الاسم بالعربية">
		        		</div>
                        <div class="col-sm-4">
	        				<label>الاسم بالانجليزية</label>
	        		    </div>
		        		<div class="col-sm-6" >
		        			<input type="text" name="name_en" class="form-control" placeholder="الاسم بالانجليزية">
		        		</div>	
                        <div class="col-sm-4">
	        				<label>ساعات العمل</label>
	        		    </div>
		        		<div class="col-sm-6" >
		        			<input type="text" name="working_hours" class="form-control" placeholder="90" />
		        		</div>					
		        		<div class="col-sm-2" >
                            ساعة
		        		</div>
                        <div class="col-sm-4">
	        				<label>معدل القبول</label>
	        		    </div>
		        		<div class="col-sm-6" >
		        			<input type="text" name="acceptance_rate" class="form-control" placeholder="80" /> 
		        		</div>	
		        		<div class="col-sm-2" >
                            %
		        		</div>		        			        		    
                        <div class="col-sm-4">
	        				<label>التقييم</label>
	        		    </div>		        	
		        		<div class="col-sm-6" >
		        			<input type="text" name="rate" class="form-control" placeholder="4.4" />
		        		</div>	
                        <div class="col-sm-4">
	        				<label>عدد الرحلات</label>
	        		    </div>		        	
		        		<div class="col-sm-6" >
		        			<input type="text" name="num_orders" class="form-control" placeholder="70" />
		        		</div>	
		        		<div class="col-sm-2" >
                            رحلة
		        		</div>		        		
                        <div class="col-sm-4">
	        				<label>العلاوة</label>
	        		    </div>		        	
		        		<div class="col-sm-6" >
		        			<input type="text" name="reward" class="form-control" placeholder="100" /> 
		        		</div>
		        		<div class="col-sm-2" >
                            ساعة
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
	        <h5 class="modal-title" id="exampleModalLabel"> تعديل المستوي : <span class="planName"></span> </h5>
	      </div>
	      <div class="modal-body">
	        	<form action="{{route('updatePlan')}}" method="post" enctype="multipart/form-data">
	        		{{csrf_field()}}
	        		<input type="hidden" name="id" value="">
	        		<div class="row">
                        <div class="col-sm-4">
	        				<label>الاسم بالعربية</label>
	        		    </div>
		        		<div class="col-sm-6" >
		        			<input type="text" name="edit_name_ar" class="form-control" placeholder="الاسم بالعربية">
		        		</div>
                        <div class="col-sm-4">
	        				<label>الاسم بالانجليزية</label>
	        		    </div>
		        		<div class="col-sm-6" >
		        			<input type="text" name="edit_name_en" class="form-control" placeholder="الاسم بالانجليزية">
		        		</div>	
                        <div class="col-sm-4">
	        				<label>ساعات العمل</label>
	        		    </div>
		        		<div class="col-sm-6" >
		        			<input type="text" name="edit_working_hours" class="form-control" placeholder="90" />
		        		</div>	
		        		<div class="col-sm-2" >
                            ساعة
		        		</div>		        						
                        <div class="col-sm-4">
	        				<label>معدل القبول</label>
	        		    </div>
		        		<div class="col-sm-6" >
		        			<input type="text" name="edit_acceptance_rate" class="form-control" placeholder="80" /> 
		        		</div>
		        		<div class="col-sm-2" >
                            %
		        		</div>		        				        		    
                        <div class="col-sm-4">
	        				<label>التقييم</label>
	        		    </div>		        	
		        		<div class="col-sm-6" >
		        			<input type="text" name="edit_rate" class="form-control" placeholder="4.4" />
		        		</div>	
                        <div class="col-sm-4">
	        				<label>عدد الرحلات</label>
	        		    </div>		        	
		        		<div class="col-sm-6" >
		        			<input type="text" name="edit_num_orders" class="form-control" placeholder="70" />
		        		</div>	
		        		<div class="col-sm-2" >
                            رحلة
		        		</div>		        		
                        <div class="col-sm-4">
	        				<label>العلاوة</label>
	        		    </div>		        	
		        		<div class="col-sm-6" >
		        			<input type="text" name="edit_reward" class="form-control" placeholder="100" /> 
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
		var id              = $(this).data('id')
		var name_ar         = $(this).data('namear')
		var name_en         = $(this).data('nameen')
		var working_hours   = $(this).data('workinghours')
		var acceptance_rate = $(this).data('acceptancerate')
		var rate            = $(this).data('rate')
		var num_orders      = $(this).data('numorders')
		var reward          = $(this).data('reward')

		//set values in modal inputs
		$("input[name='id']")                  .val(id)
		$("input[name='edit_name_ar']")        .val(name_ar)
		$("input[name='edit_name_en']")        .val(name_en)
		$("input[name='edit_working_hours']")  .val(working_hours)
		$("input[name='edit_acceptance_rate']").val(acceptance_rate);
		$("input[name='edit_rate']")           .val(rate);
		$("input[name='edit_num_orders']")     .val(num_orders);
		$("input[name='edit_reward']")         .val(reward);

		$('.planName').text(name_ar)	
	})

	$('.generalDelete').on('click',function(e){ 
		var result = confirm('هل تريد استمرار عملية الحذف ؟ ');
		if(result == false){
			e.preventDefault();
		}
	});
</script>

@endsection