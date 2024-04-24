@extends('dashboard.layout.master')
	@section('title')
	رحلات الطعام المنتهية
	@endsection
@section('content')
<div class="panel panel-flat">
	<div class="panel-heading">
		<h5 class="panel-title">رحلات الطعام المنتهية</h5>
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
				<button class="btn bg-purple-300 btn-block btn-float btn-float-lg" type="button"><i class="icon-list-numbered"></i> <span>عدد الرحلات : {{count($orders)}} </span> </button>
			</div>
			<div class="col-xs-3">
				<a href="{{route('downloadFoodFinishedOrders')}}"><button class="btn btn-block btn-float btn-float-lg correspondent" style="background-color:#1b926c; color:#fff;" type="button" ><i class="fa fa-file-excel-o"></i> <span>تحميل Excel </span></button></a>
			</div>				
		</div>
	</div>
	<!-- /buttons -->
	<table class="table datatable-basic">
		<thead>
			<tr>
				<th>رقم الرحلة</th>
				<th>العميل</th>
				<th>القائد </th>
				<th>النوع</th>
				<th>السعر</th>
				<th>الحالة</th>
				<th>من </th>
				<th>الي </th>
				<th>تاريخ الاضافة</th>
				<th>التحكم</th>
			</tr>
		</thead>
		<tbody>
			@foreach($orders as $order)
				<tr>
					<td>{{$order->id}}</td>
					<td>
					   	  {{($order->user)?$order->user->name:''}}
					</td>
					<td>{{($order->captain)?$order->captain->name:''}}</td>
					<td>{{($order->cartype)?$order->cartype->name_ar:''}}</td>
					<td>{{$order->price}} {{$order->currency_ar}}</td>
					@if($order->status == 'open')
					<td>جديد</td>
                    @elseif($order->status == 'inprogress')
					<td>قيد التنفيذ</td>
					@elseif($order->status == 'finished')
					<td>منتهي</td>
                    @else
					<td>مغلق</td>
                    @endif
                    <td>{{str_limit($order->start_address,25) }}</td>
                    <td>{{str_limit($order->end_address,25) }}</td>
					<td>{{date('Y-m-d H:i',strtotime($order->created_at))}}</td>
					<td>
					<ul class="icons-list">
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">
								<i class="icon-menu9"></i>
							</a>
							<ul class="dropdown-menu dropdown-menu-right">
								<li>
									<a href="{{url('admin/showOrder/'.$order->id)}}"><i class="glyphicon glyphicon-eye-open "></i>مشاهدة</a>
								</li>
								<!-- edit button -->
								<li>
									<a href="#" data-toggle="modal" data-target="#exampleModal2" class="openEditmodal" 
										data-id="{{$order->id}}" 
										data-startaddress="{{$order->start_address}}"
										data-endaddress="{{$order->end_address}}"
										data-price="{{$order->price}}" 
										data-notes="{{$order->notes}}" 
	                                    data-status="{{$order->status}}">
										<i class="icon-pencil7"></i>تعديل
									</a>
								</li>
								<form action="{{route('AdmindeleteOrder')}}" method="POST" >
									{{csrf_field()}}
									<input type="hidden" name="id" value="{{$order->id}}">
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

	<!-- Edit order Modal -->
	<div class="modal fade" id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="exampleModalLabel"> تعديل الطلب  <span class="orderTitle"></span> </h5>
	      </div>
	      <div class="modal-body">
	        	<form action="{{route('AdminupdateOrder')}}" method="post" enctype="multipart/form-data">
	        		{{csrf_field()}}
	        		<input type="hidden" name="id" value="">
	        		<div class="row">
<!-- 		        	<div class="col-sm-3 text-center">
		        			<i class="icon-camera" onclick="ChooseFile();" ></i>
		        			<input type="file" name="edit_image" style="display: none;">
		        		</div> -->
		        		<div class="col-sm-12">
		        			<input type="text" name="edit_start_address" class="form-control" placeholder="نقطة الانطلاق">
		        		</div>	
		        		<div class="col-sm-12">
		        			<input type="text" name="edit_end_address" class="form-control" placeholder="نقطة الوصول">
		        		</div>				
		        		<div class="col-sm-12">
		        			<input type="text" name="edit_price" class="form-control" placeholder="السعر">
		        		</div>
		        		<div class="col-sm-12">
							<select name="edit_status" class="form-control" >
								<option value="open">جديد</option>
								<option value="inprogress">قيد التنفيذ</option>
								<option value="finished">منتهي</option>
								<option value="closed">مغلق</option>
							</select>
						</div>		        		
					</div>
 					<div class="row">
                        <div class="col-sm-12">
		        			<input type="text" name="edit_notes" class="form-control" placeholder="الملاحظات...">
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

	$('.openEditmodal').on('click',function(){
		//get valus 	
		var id             = $(this).data('id')
		// var place_name     = $(this).data('placename')
		var start_address  = $(this).data('startaddress')
		var end_address    = $(this).data('endaddress')
		var price          = $(this).data('price')
		var notes          = $(this).data('notes')
		var status         = $(this).data('status')

		//set values in modal inputs
		$("input[name='id']")             .val(id)
		$("input[name='edit_start_address']").val(start_address)
		// $("input[name='edit_receive_address']").val(receiveaddress)
		$("input[name='edit_end_address']").val(end_address)
		$("input[name='edit_price']").val(price)
		$("select[name='edit_status']").val(status)
		$("input[name='edit_notes']").val(notes)
		
	});
	$('.generalDelete').on('click',function(e){ 
		var result = confirm('هل تريد استمرار عملية الحذف ؟ ');
		if(result == false){
			e.preventDefault();
		}
	});	
</script>

<!-- other code -->
<script type="text/javascript">
	// function ChooseFile(){$("input[name='edit_image']").click()}
	// function addChooseFile(){$("input[name='attachment']").click()}
</script>
<!-- /other code -->

@endsection