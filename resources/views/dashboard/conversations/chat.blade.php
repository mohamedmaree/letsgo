@extends('dashboard.layout.master')
    @section('title')
	 تفاصيل المحادثة
	@endsection
@section('content')
<div class="panel panel-flat">
	<div class="panel-heading">
		<h5 class="panel-title">تفاصيل المحادثة </h5>
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
					<div class="col-sm-4">العميل : {{($conversation->firstuser)?$conversation->firstuser->name:''}} </div>
					<div class="col-sm-4">المندوب : {{($conversation->seconduser)?$conversation->seconduser->name:''}}</div>
					<div class="col-sm-4">التاريخ : {{$conversation->created_at->diffForHumans()}}</div>
				</div>
				
				<br>
				<table class="table table-bordered table-strapped">
					<tbody>
						@foreach($messages as $msg)
						<tr>
                           <td>{{($msg->user)?$msg->user->name:''}} </td>
                           <td>
                           	@if($msg->type=='image')
                              <img class="usermetaimg" src="{{asset('chatuploads/'.$msg->content)}}" >
                           	@elseif($msg->type=='sound')
								 <audio controls>
								  <source src="{{asset('chatuploads/'.$msg->content)}}" type="audio/ogg">
								  <source src="{{asset('chatuploads/'.$msg->content)}}" type="audio/mpeg">
								Your browser does not support the audio element.
								</audio> 
                           	@elseif($msg->type == 'map')
                               ارسل موقع
                           	@else
                           	{{$msg->content}}
                           	@endif
                           </td>
                           <td>{{date('Y-m-d H:i',strtotime($msg->created_at))}}</td>                           
						</tr>
						@endforeach
					
						</tr>												
                    </tbody>
				</table>
				<div class="col-sm-12" >
							<div class="btn btn-primary col-sm-3"><a style="color: #fff" href="{{url('admin/showOrder/'.$conversation->order_id)}}">تفاصيل الطلب <i class="glyphicon glyphicon-shopping-cart"></i> </a></div>						    
							<div class="btn btn-warning col-sm-3" onclick="closeOrder();">إغلاق الطلب <i style="color: #fff" class="glyphicon glyphicon-lock"></i> </div>
									<form action="{{route('AdmincloseOrder')}}" method="POST" id="closeOrder">
										{{csrf_field()}}
									    <input type="hidden" name="id" value="{{$conversation->order_id}}">
									</form>	
							<div class="btn btn-info col-sm-3" onclick="finishOrder();">إنهاء الطلب <i style="color: #fff" class="glyphicon glyphicon-saved"></i> </div>
							        <form action="{{route('AdminfinishOrder')}}" method="POST" id="finishOrderform">
										{{csrf_field()}}
									    <input type="hidden" name="id" value="{{$conversation->order_id}}">
									</form>										
							<div class="btn btn-danger col-sm-3" onclick="deleteConversation()">حذف المحادثة <i style="color: #fff" class="icon-trash"></i> </div>
									<form action="{{route('deleteConversation')}}" method="POST" id="deleteConversation">
										{{csrf_field()}}
									    <input type="hidden" name="id" value="{{$conversation->id}}">
									</form>

	
				</div>
			</div>
		</div>
	</div>
</div>

	
<!-- javascript -->
@section('script')
<script>

function deleteConversation(){
	var x = confirm("هل أنت متأكد؟");
    if(x == false){
       return false
    }
    $("#deleteConversation").submit();
}	

function closeOrder(){
	var x = confirm("هل أنت متأكد؟");
    if(x == false){
       return false
    }
    $("#closeOrder").submit();
}

function finishOrder(){
	var x = confirm("هل أنت متأكد؟");
    if(x == false){
       return false
    }
    $("#finishOrderform").submit();
}
</script>
@endsection


@endsection