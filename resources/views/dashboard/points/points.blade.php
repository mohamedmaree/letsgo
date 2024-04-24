@extends('dashboard.layout.master')
	@section('title')
	  استبدال نقاط العملاء
	@endsection
@section('content')
<div class="panel panel-flat">
	<div class="panel-heading">
		<h5 class="panel-title"> استبدال نقاط العملاء </h5>
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
				<button class="btn bg-blue btn-block btn-float btn-float-lg openAddModal" type="button" data-toggle="modal" data-target="#exampleModal"><i class="icon-plus3"></i> <span>اضافة نقاط</span></button>
			</div>			
			<div class="col-xs-3">
				<button class="btn bg-purple-300 btn-block btn-float btn-float-lg" type="button"><i class="icon-list-numbered"></i> <span>عدد النقاط : {{count($points)}} </span> </button>
			</div>
		</div>
	</div>
	<!-- /buttons -->

	<table class="table datatable-basic">
		<thead>
			<tr>
				<th>النقاط</th>
				<th>المبلغ</th>
				<th>تلريخ الاضافة</th>
				<th>التحكم</th>
			</tr>
		</thead>
		<tbody>
			@foreach($points as $point)
				<tr>
					<td>{{$point->points}}</td>
					<td>{{$point->amount}}</td>
					<td>{{date('Y-m-d H:i',strtotime($point->created_at))}}</td>
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
										data-id="{{$point->id}}" 
										data-points="{{$point->points}}"
										data-amount="{{$point->amount}}"
									>
									<i class="icon-pencil7"></i>تعديل
									</a>
								</li>
								<!-- delete button -->
								<form action="{{route('DeletePoint')}}" method="POST">
									{{csrf_field()}}
									<input type="hidden" name="id" value="{{$point->id}}">
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
	        <h5 class="modal-title" id="exampleModalLabel">أضافة نقاط جديدة</h5>
	      </div>
	      <div class="modal-body">
	        <div class="row">
	        	<form action="{{route('createPoint')}}" method="post" enctype="multipart/form-data">
	        		{{csrf_field()}}
	        		<div class="row">
                        <div class="col-sm-4">
	        				<label>النقاط</label>
	        		    </div>		        	
		        		<div class="col-sm-8">
		        			<input type="number" name="points" class="form-control" placeholder="عدد النقاط" />
		        		</div>
                        <div class="col-sm-4">
	        				<label>الرصيد</label>
	        		    </div>		        	
		        		<div class="col-sm-8">
		        			<input type="number" name="amount" class="form-control" placeholder="المبلغ المستبدل" />
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
	        <h5 class="modal-title" id="exampleModalLabel"> تعديل الخطة : <span class="planName"></span> </h5>
	      </div>
	      <div class="modal-body">
	        	<form action="{{route('updatePoint')}}" method="post" enctype="multipart/form-data">
	        		{{csrf_field()}}
	        		<input type="hidden" name="id" value="">
	        		<div class="row">
                        <div class="col-sm-4">
	        				<label>النقاط</label>
	        		    </div>		        	
		        		<div class="col-sm-8">
		        			<input type="number" name="edit_points" class="form-control" placeholder="عدد النقاط" />
		        		</div>
                        <div class="col-sm-4">
	        				<label>الرصيد</label>
	        		    </div>		        	
		        		<div class="col-sm-8">
		        			<input type="number" name="edit_amount" class="form-control" placeholder="المبلغ المستبدل" />
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
		var points           = $(this).data('points')
		var amount           = $(this).data('amount')
		//set values in modal inputs
		$("input[name='id']")               .val(id)
		$("input[name='edit_points']")      .val(points)
		$("input[name='edit_amount']")      .val(amount)

	})

	$('.generalDelete').on('click',function(e){ 
		var result = confirm('هل تريد استمرار عملية الحذف ؟ ');
		if(result == false){
			e.preventDefault();
		}
	});

</script>
@endsection