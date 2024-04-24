@extends('dashboard.layout.master')
	@section('title')
	 عروض القادة 
	@endsection

@section('content')
<div class="panel panel-flat">
	<div class="panel-heading">
		<h5 class="panel-title">قائمة عروض القادة</h5>
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
				<button class="btn bg-blue btn-block btn-float btn-float-lg openAddModal" type="button" data-toggle="modal" data-target="#exampleModal"><i class="icon-plus3"></i> <span>اضافة عرض</span></button>
			</div>
			<div class="col-xs-3">
				<button class="btn bg-purple-300 btn-block btn-float btn-float-lg" type="button"><i class="icon-list-numbered"></i> <span>عدد العروض : {{count($offers)}} </span> </button>
			</div>	
		</div>
	</div>
	<!-- /buttons -->
	@csrf
	<table class="table datatable-basic">
		<thead>
			<tr>
				<th>صورة الاعلان</th>
				<th>تاريخ الانتهاء</th>
				<th>العنوان</th>
				<th>ملاحظات</th>
				<th>تاريخ الاضافه</th>
				<th>التحكم</th>
			</tr>
		</thead>
		<tbody>
			@foreach($offers as $offer)
				<tr>
					<td><img src="{{asset('img/offers/'.$offer->image)}}" class="img-circle" alt=""></td>
					<td>{{$offer->end_at}}</td>
					<td>{{$offer->title}}</td>
                    <td>{{$offer->notes}}</td>
					<td>{{$offer->created_at->diffForHumans()}}</td>
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
									data-id="{{$offer->id}}" 
									data-image="{{$offer->image}}" 
									data-endat="{{$offer->end_at}}" 
									data-title="{{$offer->title}}" 
									data-notes="{{$offer->notes}}"/>
									<i class="icon-pencil7"></i>تعديل
									</a>
								</li>
								<form action="{{route('DeleteCaptainOffer')}}" method="POST" id="DeleteCouponForm">
									{{csrf_field()}}
									<input type="hidden" name="id" value="{{$offer->id}}">
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
	        <h5 class="modal-title" id="exampleModalLabel">أضافة عرض جديد</h5>
	      </div>
	      <div class="modal-body">
	        <div class="row">
	        	<form action="{{route('createCaptainOffer')}}" method="POST"  enctype="multipart/form-data" >
	        		{{csrf_field()}}

	        		<div class="row">
		        		<div class="col-sm-12">
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
		        				<label>تاريخ الانتهاء</label>
		        		    </div>
		        			<div class="col-sm-8">
		        				<input type="date" name="end_at" class="form-control" placeholder="تاريخ الانتهاء " required/>
						    </div>
                            <div class="col-sm-4">
		        				<label>العنوان</label>
		        		    </div>
		        			<div class="col-sm-8">
		        				<input type="text" name="title" class="form-control" placeholder="العنوان"/>
						    </div>						    
                            <div class="col-sm-4">
		        				<label>ملاحظات</label>
		        		    </div>
		        			<div class="col-sm-8">
                               <textarea name="notes"  class="form-control" placeholder="الملاحظات" cols="50" rows="3"></textarea>
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
	        <h5 class="modal-title" id="exampleModalLabel"> تعديل العرض </h5>
	      </div>
	      <div class="modal-body">
	        	<form action="{{route('updateCaptainOffer')}}" method="POST"  enctype="multipart/form-data" >
	        		{{csrf_field()}}
	        		<input type="hidden" name="id" value="">
	        		<div class="row">
		        		<div class="col-sm-12">
		        			<div class="col-sm-4">
		        				<label>صورة الاعلان</label>
		        		    </div>
		        			<div class="col-sm-8">
			        			<img src="" class="photo" style="width: 120px;height: 120px;cursor: pointer;margin-bottom:10px;" onclick="ChooseFile()">
			        			<input type="file" name="edit_image" style="display: none;">
		        			</div>

		        			<div class="col-sm-4">
		        				<label>الاسم بالعربية</label>
		        		    </div>
		        			<div class="col-sm-8">
		        				<input type="date" name="edit_end_at" class="form-control" placeholder="تاريخ الانتهاء" required/>
						    </div>
                            <div class="col-sm-4">
		        				<label>العنوان</label>
		        		    </div>
		        			<div class="col-sm-8">
		        				<input type="text" name="edit_title" class="form-control" placeholder="العنوان"/>
						    </div>						    
                            <div class="col-sm-4">
		        				<label>ملاحظات</label>
		        		    </div>
		        			<div class="col-sm-8">
                               <textarea name="edit_notes"  class="form-control" placeholder="الملاحظات"  cols="50" rows="3"></textarea>
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

	$('.openEditmodal').on('click',function(){
		//get valus 
		var id              = $(this).data('id')
		var image           = $(this).data('image')
		var end_at          = $(this).data('endat')
		var title            = $(this).data('title')
		var notes           = $(this).data('notes')
		//set values in modal inputs
		$("input[name='id']")            .val(id)
		$("input[name='edit_end_at']")   .val(end_at)
		$("input[name='edit_title']")      .val(title)
		$("textarea[name='edit_notes']") .val(notes)
		var url = "{{asset('img/offers/')}}" +'/'+ image;
		$(".photo").attr('src',url);
});

	$('.generalDelete').on('click',function(e){ 
		var result = confirm('هل تريد استمرار عملية الحذف ؟ ');
		if(result == false){
			e.preventDefault();
		}
	});
</script>
<script type="text/javascript">
	function ChooseFile(){$("input[name='edit_image']").click()}
	function addChooseFile(){$("input[name='image']").click()}
</script>
@endsection