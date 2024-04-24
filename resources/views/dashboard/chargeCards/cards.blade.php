@extends('dashboard.layout.master')
	@section('title')
	كوبونات الشحن
	@endsection

@section('content')
<div class="panel panel-flat">
	<div class="panel-heading">
		<h5 class="panel-title">قائمة كوبونات الشحن</h5>
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
				<button class="btn bg-blue btn-block btn-float btn-float-lg openAddModal" type="button" data-toggle="modal" data-target="#exampleModal"><i class="icon-plus3"></i> <span>اضافة كوبون</span></button>
			</div>
			<div class="col-xs-3">
				<button class="btn bg-purple-300 btn-block btn-float btn-float-lg" type="button"><i class="icon-list-numbered"></i> <span>عدد الكوبونات : {{$allNewCards}} </span> </button>
			</div>
			<div class="col-xs-3">
				<a href="{{route('downloadchargeCards')}}"><button class="btn btn-block btn-float btn-float-lg correspondent" style="background-color:#1b926c; color:#fff;" type="button" ><i class="fa fa-file-excel-o"></i> <span>تحميل Excel </span></button></a>
			</div>					
			<div class="col-xs-3">
				<button class="btn  btn-block btn-float btn-float-lg correspondent" type="button" id="removeAll"><i class=" icon-trash"></i> <span>حذف المحدد </span></button>
			</div>				
		</div>
	</div>
	<!-- /buttons -->
<form action="{{route('DeletechargeCards')}}" method="POST"  id="removeAllForm">
	@csrf
	<table class="table datatable-basic">
		<thead>
			<tr>
				<th>#</th>
				<th>الكود</th>
				<th>القيمة</th>
				<th>تاريخ الاضافه</th>
				<th>التحكم</th>
				<th>
                      <label class="checkbox">
                          <input type="checkbox" id="checkAll"> 
                          <i class="icon-checkbox"></i>
                      </label>
				</th>				
			</tr>
		</thead>
		<tbody>
			<?php $i = 1;?>
			@foreach($cards as $card)
				<tr id="row-{{$card->id}}">
					<td>{{$i}}   </td>
					<td>{{$card->code}}   </td>
					<td>{{$card->value}} {{setting('site_currency_ar')}}</td>
					<td>{{$card->created_at->diffForHumans()}}</td>
					<td>
					<ul class="icons-list">
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">
								<i class="icon-menu9"></i>
							</a>

							<ul class="dropdown-menu dropdown-menu-right">
								<!-- delete button -->
								<form action="{{route('DeletechargeCard')}}" method="GET" id="DeletechargeCard-{{$card->id}}">
									{{csrf_field()}}
									<input type="hidden" name="id" value="{{$card->id}}">
									<li><button type="button" class="generalDelete reset" id="{{$card->id}}"><i class="icon-trash"></i>حذف</button></li>
								</form>
							</ul>
						</li>
					</ul>
					</td>
					<td>
                              <label class="checkbox">
		                          <input type="checkbox" name="deleteids[]" value="{{$card->id}}" > 
		                          <i class="icon-checkbox"></i>
	                          </label>
					</td>					
				</tr>
				<?php $i++;?>
			@endforeach
		</tbody>
	</table>
</form>
	{{$cards->links()}}
	<!-- Add coupon Modal -->
	<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="exampleModalLabel">أضافة كوبون جديد</h5>
	      </div>
	      <div class="modal-body">
	        <div class="row">
	        	<form action="{{route('createchargeCard')}}" method="POST">
	        		{{csrf_field()}}

	        		<div class="row">
						    <div class="col-sm-4">
		        			   <label>عدد الكوبونات</label>
		        		    </div>
		        			<div class="col-sm-8">
		        				<input type="number" name="num_cards" class="form-control" placeholder="عدد الكوبونات " min="1">
		        			</div>

						    <div class="col-sm-4">
		        			   <label>القيمة</label>
		        		    </div>
		        			<div class="col-sm-8">
		        				<input type="number" name="value" class="form-control" placeholder="القيمة " min="1">
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

	// $('.generalDelete').on('click',function(e){ 
	// 	var result = confirm('هل تريد استمرار عملية الحذف ؟ ');
	// 	if(result == false){
	// 		e.preventDefault();
	// 	}
	// });

	$('#removeAll').on('click',function(){ 
		var result = confirm('هل تريد استمرار عملية الحذف ؟ ');
		if(result == false){
		    e.preventDefault();		
		}else{
			$('#removeAllForm').submit();
		}
	});

	$('.generalDelete').on('click',function(e){ 
		var result = confirm('هل تريد استمرار عملية الحذف ؟ ');
		var id = $(this).attr('id');
		if(result == false){
			e.preventDefault();
		}else{
		        var jqxhr = $.get("{{url('admin/DeletechargeCard/')}}/"+id, function(data) {
		           if(data == 1){
		                $('#row-'+id).hide(500);
		           }
		       });
		}
	});


</script>

@endsection