@extends('dashboard.layout.master')
  @section('title','عرض الشكوي ')
@section('content')
<div class="panel panel-flat">
	<div class="panel-heading">
		<h5 class="panel-title">عرض الشكوي</h5>
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
					<div class="col-sm-4">اسم الراسل : {{($ticket->user)?$ticket->user->name.'('.$ticket->user->pin_code.')' : ''}} </div>
					<div class="col-sm-4">الهاتف : {{($ticket->user)? '0'.$ticket->user->phone:''}}</div>
					<div class="col-sm-4">التاريخ : {{date('Y-m-d h:i a',strtotime($ticket->created_at))}}</div>
				</div>
				
				<br>
				<div class="col-sm-12" >
					<p>{{$ticket->subject}}</p>
                    @if($ticket->src)
						 <audio controls>
						  <source src="{{asset('img/complaint/'.$ticket->src)}}" type="audio/ogg">
						  <source src="{{asset('img/complaint/'.$ticket->src)}}" type="audio/mp3">
						  <source src="{{asset('img/complaint/'.$ticket->src)}}" type="audio/mpeg">
					      your browser does not support the audio element.
						</audio> 
                    @endif
					<p>{{$ticket->text}}</p>
					<hr/>
					<p>{{$ticket->answer}}</p>

				</div>
					<div class="col-sm-12">
						<div class="btn btn-danger col-sm-4" onclick="deleteMessage()">حذف <i style="color: #fff" class=" icon-trash"></i> </div>
								<form action="{{route('deleteTicket')}}" method="POST" id="deletemessage">
									{{csrf_field()}}
									<input type="hidden" name="id" value="{{$ticket->id}}">
								</form>

						<div class="btn btn-primary col-sm-4 SMS" 
							data-toggle="modal" 
							data-target="#exampleModalSMS" 
							data-msgid="{{$ticket->id}}"
							data-userid="{{$ticket->user_id}}" 
							data-phone="{{($ticket->user)?$ticket->user->phone:''}}" 
							data-email="{{($ticket->user)?$ticket->user->email:''}}" 
							data-name="{{($ticket->user)?$ticket->user->name:''}}">
							رد برساله <i class="icon-mobile2"></i>
						</div>
						<div class="btn btn-warning col-sm-4"><a style="color: #fff" href="{{url('admin/showOrder/'.$ticket->order_id)}}">مشاهدة الطلب <i class="icon-enter5"></i> </a></div>
			</div>
		</div>
	</div>
</div>

<!-- SMS Modal -->
	<div class="modal fade" id="exampleModalSMS" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
			  <div class="modal-header">
			    <h5 class="modal-title" id="exampleModalLabel">مراسلة :  <span class="reverName"></span></h5>
			  </div>
			  <div class="modal-body">
			    <div class="row">
					<div class="tabbable">
						<ul class="nav nav-tabs bg-slate nav-tabs-component nav-justified">
							<!-- email -->
							<li><a href="#colored-rounded-justified-tab3" data-toggle="tab">ايميل</a></li>
							<!-- sms -->
							<li class="active"><a href="#colored-rounded-justified-tab20" data-toggle="tab">رساله SMS</a></li>
							<!-- notification -->
							<li><a href="#colored-rounded-justified-tab30" data-toggle="tab">اشعار</a></li>
						</ul>

						<div class="tab-content">
                            <!-- email -->
							<div class="tab-pane" id="colored-rounded-justified-tab3">
							    <div class="row">
							    	<form action="{{route('emailTicket')}}" method="POST" enctype="multipart/form-data">
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
							<div class="tab-pane active" id="colored-rounded-justified-tab20">
							    <div class="row">
							    	<form action="{{route('smsTicket')}}" method="POST">
							    		{{csrf_field()}}
							    		<input type="hidden" name="user_id" value="">
							    		<input type="hidden" name="phone" value="">
								    	<input type="hidden" name="msgid" value="">
							    		<div class="col-sm-12">
							    			<textarea rows="15" name="sms_message" class="form-control" placeholder="نص رسالة الـ SMS "></textarea>
							    		</div>

								        <div class="col-sm-12" style="margin-top: 10px">
									      	<button type="submit" class="btn btn-primary addCategory">ارسال</button>
									        <button type="button" class="btn btn-secondary" data-dismiss="modal">أغلاق</button>
								        </div>
							    	</form>
							    </div>
							</div>

							<!-- notification -->
							<div class="tab-pane" id="colored-rounded-justified-tab30">
							    <div class="row">
							    	<form action="{{route('notificationTicket')}}" method="POST">
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
		$('input[name="user_id"]').val($(this).data('userid'));
		$('input[name="msgid"]').val($(this).data('msgid'));
		$('input[name="email"]').val($(this).data('email'));
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