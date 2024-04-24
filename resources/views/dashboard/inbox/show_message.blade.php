@extends('dashboard.layout.master')
@section('title','عرض رساله '.$message->name)
@section('content')
<div class="panel panel-flat">
	<div class="panel-heading">
		<h5 class="panel-title">عرض رساله :{{$message->name}}</h5>
		<div class="heading-elements">
			<ul class="icons-list">
        		<li><a data-action="collapse"></a></li>
        		<li><a data-action="reload"></a></li>
        	</ul>
    	</div>
	</div>
	<div class="panel panel-flat">
		<div class="panel-body">
			<div class="row text-center">
				<div class="col-sm-12 alert alert-success">
					<div class="col-sm-4">اسم الراسل : {{$message->name}} </div>
					<div class="col-sm-4">الهاتف : 0{{$message->phone}}</div>
					<div class="col-sm-4">التاريخ : {{$message->created_at->diffForHumans()}}</div>
				</div>
				
				<br>
				<div class="col-sm-12" >
                    @if($message->image)
                    <p><img src="{{url('img/complaint/'.$message->image)}}" style="max-width: 50%;"></p>
                    @endif
					<p>{{$message->message}}</p>
					<hr/>
					<p>{{$message->answer}}</p>
				</div>
					<div class="col-sm-12">
				@if($message->order_id)
						<div class="btn btn-success col-sm-3"><a style="color: #fff" href="{{url('admin/showOrder/'.$message->order_id)}}"> مشاهدة الطلب <i class="glyphicon glyphicon-shopping-cart"></i> </a></div>
						<div class="btn btn-warning col-sm-3"><a style="color: #fff" href="{{url('admin/chat/'.$message->conversation_id)}}"> مشاهدة المحادثة <i class="glyphicon glyphicon-comment"></i> </a></div>
						<div class="btn btn-primary col-sm-3 SMS" 
							data-toggle="modal" 
							data-target="#exampleModalSMS" 
							data-userid="{{$message->user_id}}" 
							data-phone="{{$message->phone}}" 
							data-msgid="{{$message->id}}"
							data-name="{{$message->name}}">
							رد برساله <i class="icon-mobile2"></i>
						</div>

						<div class="btn btn-danger col-sm-3" onclick="deleteMessage()">حذف <i style="color: #fff" class=" icon-trash"></i> </div>
								<form action="{{route('deletemessage')}}" method="POST" id="deletemessage">
									{{csrf_field()}}
									<input type="hidden" name="id" value="{{$message->id}}">
								</form>
				@else
						<div class="btn btn-danger col-sm-4" onclick="deleteMessage()">حذف <i style="color: #fff" class=" icon-trash"></i> </div>
								<form action="{{route('deletemessage')}}" method="POST" id="deletemessage">
									{{csrf_field()}}
									<input type="hidden" name="id" value="{{$message->id}}">
								</form>

						<div class="btn btn-primary col-sm-4 SMS" 
							data-toggle="modal" 
							data-target="#exampleModalSMS" 
							data-userid="{{$message->user_id}}" 
							data-phone="{{$message->phone}}" 
							data-email="{{$message->email}}" 
							data-msgid="{{$message->id}}"
							data-name="{{$message->name}}">
							رد برساله <i class="icon-mobile2"></i>
						</div>

						<div class="btn btn-warning col-sm-4"><a style="color: #fff" href="{{route('inbox')}}">عوده لصندوق الوارد <i class="icon-enter5"></i> </a></div>
				@endif
			</div>
		</div>
	</div>
</div>

<!-- SMS Modal -->
<div class="modal fade" id="exampleModalSMS" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
		    <h5 class="modal-title" id="exampleModalLabel">الرد برسالة<span class="reverName"></span></h5>
		  </div>
		  <div class="modal-body">
		    <div class="row">
		    	<div class="tabbable">
						<ul class="nav nav-tabs bg-slate nav-tabs-component nav-justified">
							<li><a href="#colored-rounded-justified-tab3" data-toggle="tab">ايميل</a></li>
							<li class="active"><a href="#colored-rounded-justified-tab1" data-toggle="tab">رساله SMS</a></li>
							<li><a href="#colored-rounded-justified-tab2" data-toggle="tab">اشعار</a></li>
						</ul>
						<div class="tab-content">
                            <!-- email -->
							<div class="tab-pane" id="colored-rounded-justified-tab3">
							    <div class="row">
							    	<form action="{{route('sendemail')}}" method="POST" enctype="multipart/form-data">
							    		{{csrf_field()}}
							    		<input type="hidden" name="email">
							    		<input type="hidden" name="name">
							    		<input type="hidden" name="msgid">
							    		<div class="col-sm-12">
							    			<textarea rows="15" name="email_message" class="form-control" placeholder="نص رسالة الايميل "></textarea>
							    		</div>

								        <div class="col-sm-12" style="margin-top: 10px">
									      	<button type="submit" class="btn btn-primary addCategory">ارسال</button>
									        <button type="button" class="btn btn-secondary" data-dismiss="modal">أغلاق</button>
								        </div>

							    	</form>
							    </div>								
							</div>
							<!-- sms -->
							<div class="tab-pane active" id="colored-rounded-justified-tab1">
							    <div class="row">
								    	<form action="{{route('sendsms')}}" method="POST" enctype="multipart/form-data">
								    		{{csrf_field()}}
								    		<input type="hidden" name="user_id" value="">
								    		<input type="hidden" name="phone" value="">
								    		<input type="hidden" name="msgid" value="">
								    		<div class="col-sm-12">
								    			<textarea rows="15" name="sms_message" class="form-control" placeholder="نص رساله SMS"></textarea>
								    		</div>
									        <div class="col-sm-12" style="margin-top: 10px">
										      	<button type="submit" class="btn btn-primary addCategory">ارسال</button>
										        <button type="button" class="btn btn-secondary" data-dismiss="modal">أغلاق</button>
									        </div>
								    	</form>
							     </div>
							</div>
							<!-- noification -->
							<div class="tab-pane" id="colored-rounded-justified-tab2">
							    <div class="row">
							    	<form action="{{route('sendnotification')}}" method="POST" enctype="multipart/form-data">
							    		{{csrf_field()}}
								    		<input type="hidden" name="user_id" value="">
								    		<input type="hidden" name="phone" value="">
								    		<input type="hidden" name="msgid" value="">							    		
							    		<div class="col-sm-12">
                                            <input type="text" name="notification_title" class="form-control" placeholder="عنوان الاشعار" />
							    		</div>
							    		<div class="col-sm-12">
							    			<textarea rows="15" name="notification_message" class="form-control" placeholder="نص رسالة الـ Notification "></textarea>
							    		</div>

								        <div class="col-sm-12" style="margin-top: 10px">
									      	<button type="submit" class="btn btn-primary addCategory">ارسال</button>
									        <button type="button" class="btn btn-secondary" data-dismiss="modal">أغلاق</button>
								        </div>
							    	</form>
							    </div>
							</div>							

						</div>
					</div>
			    </div>
			  </div>

			</div>
		</div>
	</div>
<!-- /SMS Modal -->

<!-- javascript -->
@section('script')
<script>
	//put phone in the modal
	$(document).on('click','.SMS',function(){
		$('input[name="phone"]').val($(this).data('phone'));
		$('input[name="email"]').val($(this).data('email'));
		$('input[name="user_id"]').val($(this).data('userid'));
		$('input[name="msgid"]').val($(this).data('msgid'));
		$('.reverName').text($(this).data('name'))
	});

function deleteMessage(){
	var x = confirm("هل أنت متأكد؟");
    if(x == false){
       return false
    }
    $("#deletemessage").submit();
}	
</script>
@endsection


@endsection