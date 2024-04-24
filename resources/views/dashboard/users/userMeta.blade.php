@extends('dashboard.layout.master')
    @section('title')
	طلب العمل كقائد
	@endsection
@section('content')
<div class="panel panel-flat">
	<div class="panel-heading">
		<h5 class="panel-title">عرض طلب :{{$usermeta->name}}</h5>
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
					<div class="col-sm-3">اسم العميل : {{$usermeta->name}} </div>
					<div class="col-sm-3">الهاتف : 0{{$usermeta->phone}}</div>
					<div class="col-sm-3">الدولة : {{($usermeta->country)?$usermeta->country->name_ar:''}}</div>
					<div class="col-sm-3">التاريخ : {{date('Y-m-d H:i',strtotime($usermeta->created_at))}}</div>
				</div>
				
				<br>
				<table class="table table-bordered table-strapped">
					<tbody>
						<tr>
                           <td> الصورة الشخصة </td>
                           <td>
                           	@if($usermeta->personal_image)
                           	<a href="{{url('img/user/usermeta/'.$usermeta->personal_image)}}"><img class="usermetaimg" src="{{asset('img/user/usermeta/'.$usermeta->personal_image)}}"> </a>
                            @else
                              لا يوجد
                            @endif
                           </td>
						</tr>
						<tr>
                           <td> البريد الالكتروني </td>
                           <td>{{$usermeta->email}}</td>
						</tr>
	
						<tr>
                           <td> الجنس </td>
                           <td>{{($usermeta->gender == 'female')? 'انثي': 'ذكر'}}</td>
						</tr>	
						<tr>
							<td>الدولة</td>
							<td>
								{{($usermeta->country)? $usermeta->country->name_ar:''}}
							</td>
						 </tr>										
						<tr>
                           <td> المدينة </td>
                           <td>{{($usermeta->city)? $usermeta->city->name_ar:''}}</td>
						</tr>
						<!-- <tr>
                           <td> نوع القائد </td>
                           <td>{{($usermeta->captain_type=='saudi')? 'سعودي':'قائد سيارات ليموزين'}}</td>
						</tr> -->																		
						<tr>
                           <td>  تحقيق الشخصية </td>
                           <td>
                            @if($usermeta->identity_card)
                           	  <a href="{{url('img/user/usermeta/'.$usermeta->identity_card)}}"><img class="usermetaimg" src="{{asset('img/user/usermeta/'.$usermeta->identity_card)}}" > </a>
						    @else
                              لا يوجد
						    @endif
						    </td>
						</tr>	
						<tr>
							<td> رقم الهوية / الأقامة </td>
							<td>{{$usermeta->identity_number}}</td>
						 </tr>	
						 <tr>
							<td> تاريخ الميلاد </td>
							<td>{{$usermeta->birthdate}}</td>
						 </tr>
						<tr>
                           <td>  رخصة القيادة </td>
                           <td>
                            @if($usermeta->driving_license)
                           	  <a href="{{url('img/user/usermeta/'.$usermeta->driving_license)}}"><img class="usermetaimg" src="{{asset('img/user/usermeta/'.$usermeta->driving_license)}}" > </a>
						    @else
                              لا يوجد
						    @endif
						    </td>
						</tr>
						<tr>
                           <td>  استمارة السيارة </td>
                           <td>
                            @if($usermeta->car_form)
                           	<a href="{{url('img/user/usermeta/'.$usermeta->car_form)}}"><img class="usermetaimg" src="{{asset('img/user/usermeta/'.$usermeta->car_form)}}" > </a>
						    @else
                              لا يوجد
                            @endif
                            </td>
						</tr>
						<tr>
							<td> صورة التفويض </td>
							<td>
							 @if($usermeta->authorization_image)
								<a href="{{url('img/user/usermeta/'.$usermeta->authorization_image)}}"><img class="usermetaimg" src="{{asset('img/user/usermeta/'.$usermeta->authorization_image)}}" > </a>
							 @else
							   لا يوجد
							 @endif
							 </td>
						 </tr>	
						<tr>
							<td> الرقم التسلسلي للسيارة </td>
							<td>{{$usermeta->sequenceNumber}}</td>
						 </tr>
						 <tr>
							<td> حروف لوحة السيارة </td>
							<td>{{$usermeta->car_letters}}</td>
						 </tr>
						 <tr>
							<td> أرقام لوحة السيارة </td>
							<td>{{$usermeta->car_numbers}}</td>
						 </tr>
						<tr>
                           <td> iban </td>
                           <td>
                              {{$usermeta->iban}}
						   {{-- <a href="{{url('img/user/usermeta/'.$usermeta->iban)}}"><img class="usermetaimg" src="{{asset('img/user/usermeta/'.$usermeta->iban)}}" > </a> --}}
						    </td>
						</tr>
						<tr>
							<td> البنك </td>
							<td>
							   {{$usermeta->bank_name}}
							 </td>
						</tr>
						<tr>
							<td> رقم حساب STC PAY </td>
							<td>
							   {{$usermeta->stc_number}}
							 </td>
						</tr>
						<tr>
                           <td> التأمين علي السيارة </td>
                           <td>
                            @if($usermeta->car_insurance)
                           	<a href="{{url('img/user/usermeta/'.$usermeta->car_insurance)}}"><img class="usermetaimg" src="{{asset('img/user/usermeta/'.$usermeta->car_insurance)}}" > </a>
						    @else
						    لا يوجد
						    @endif
						    </td>
						</tr>
						<tr>
                           <td> صورة السيارة </td>
                           <td>
                            @if($usermeta->car_image)
                           	<a href="{{url('img/user/usermeta/'.$usermeta->car_image)}}"><img class="usermetaimg" src="{{asset('img/user/usermeta/'.$usermeta->car_image)}}" > </a>
						    @else
                             لا يوجد
						    @endif
						    </td>
						</tr>																														
						<tr>
                           <td> نوع السيارة </td>
                           <td>{{$usermeta->car_type}}</td>
						</tr>
						<tr>
                           <td> موديل السيارة </td>
                           <td>{{$usermeta->car_model}}</td>
						</tr>
						<tr>
                           <td> لون السيارة </td>
                           <td>{{$usermeta->car_color}}</td>
						</tr>
						<tr>
                           <td> سنة الصنع </td>
                           <td>{{$usermeta->manufacturing_year}}</td>
						</tr>


						<tr>
                           <td> نوع لوحة السيارة </td>
                           <td>{{$usermeta->plateType_txt}}</td>
						</tr>	
						<tr>
							<td> تعديل الطلب </td>
							<td>
							   <div class="btn btn-success col-sm-6"><a style="color: #fff" href="{{route('editUserMeta',[$usermeta->id])}}">تعديل طلب العميل <i class="glyphicon glyphicon-edit"></i> </a></div>						    
							</td>
						 </tr>
                        <tr> 
                        	<td>تفاصيل خاصة بالربط مع وصل</td>
                        	<td style="text-align: left;">
                                wasl status : {{$usermeta->elm_status}} <br/>
                                resultCode : {{$usermeta->resultCode}} <br/>
                                resultMsg : {{$usermeta->resultMsg}} <br/>
                                driverEligibility : {{$usermeta->driverEligibility}} <br/>
                                eligibilityExpiryDate : {{$usermeta->eligibilityExpiryDate}} <br/>
                                vehicleEligibility : {{$usermeta->vehicleEligibility}} <br/>
                                rejectionReasons : {{$usermeta->rejectionReasons}} <br/>
                        	</td>
                        </tr>
                        
						<tr>
                           <td> تصنيف السيارة </td>
                           <td> 
                           	@if($usermeta->status == 'pending')
                           	   <select id="type_id" multiple>
                               @foreach($cartypes as $type)
                                 <option value="{{$type->id}}"> {{$type->name_ar}}</option>
                               @endforeach 
                               </select>
                            @elseif($usermeta->status == 'agree')
                              <?php $car_type_ids = explode(',', $usermeta->car_type_id);?>
                              @if($car_type_ids)
                                  @foreach($car_type_ids as $car_type_id)
                                      @if($cartype = getCarType($car_type_id))
                                           {{$cartype->name_ar.' '}}
                                      @endif
                                  @endforeach
                              @endif
                           	@endif
                           </td>
						</tr>	
						@if($usermeta->status == 'refused')
						<tr>
							<td> سبب الرفض </td>
							<td> {{$usermeta->refuse_reason}}</td>
						</tr>	
						@endif
                    </tbody>
				</table>
				<div class="col-sm-12" >
					    @if($usermeta->status == 'pending')
							<form action="{{route('agreeUserMeta')}}" method="POST" >
								{{csrf_field()}}
								<input type="hidden" name="id" value="{{$usermeta->id}}">
								<input type="hidden" name="car_type_id" id="car_type_id" value="{{isset($cartypes[0]->id)??''}}">
					            <button type="submit" class="btn btn-success col-sm-6">الموافقة <i style="color: #fff" class="glyphicon glyphicon-saved"></i> </button>
							</form>		
							<div class="btn btn-danger col-sm-6" data-toggle="modal" 
							data-target="#refuseModal" 
							>رفض <i style="color: #fff" class="icon-lock"></i> </div>
							{{-- <form action="#" method="POST" id="deleteUserMeta">
								{{csrf_field()}}
								<input type="hidden" name="id" value="{{$usermeta->id}}">
							</form>					 --}}
							{{-- <div class="btn btn-danger col-sm-3" onclick="deleteUserMeta()">حذف <i style="color: #fff" class=" icon-trash"></i> </div>
									<form action="{{route('deleteUserMeta')}}" method="POST" id="deleteUserMeta">
										{{csrf_field()}}
										<input type="hidden" name="id" value="{{$usermeta->id}}">
									</form> --}}
{{-- 
							<div class="btn btn-primary col-sm-3 SMS" 
								data-toggle="modal" 
								data-target="#exampleModalSMS" 
								data-phone="0{{$usermeta->phone}}" 
								data-name="{{$usermeta->name}}">
								رد برساله SMS <i class="icon-mobile2"></i>
							</div> --}}

						{{-- @else
						<div class="btn btn-danger col-sm-12" onclick="">رفض <i style="color: #fff" class="icon-trash"></i> </div>
						<form action="#" method="POST" id="">
							{{csrf_field()}}
							<input type="hidden" name="id" value="{{$usermeta->id}}">
						</form> --}}

							{{-- <div class="btn btn-danger col-sm-4" onclick="deleteUserMeta()">حذف <i style="color: #fff" class=" icon-trash"></i> </div>
									<form action="{{route('deleteUserMeta')}}" method="POST" id="deleteUserMeta">
										{{csrf_field()}}
										<input type="hidden" name="id" value="{{$usermeta->id}}">
									</form> --}}

							{{-- <div class="btn btn-primary col-sm-4 SMS" 
								data-toggle="modal" 
								data-target="#exampleModalSMS" 
								data-phone="0{{$usermeta->phone}}" 
								data-name="{{$usermeta->name}}">
								رد برساله SMS <i class="icon-mobile2"></i>
							</div> --}}

                        @endif

				</div>
			</div>
		</div>
	</div>
</div>

<!-- SMS Modal -->
<div class="modal fade" id="exampleModalSMS" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
		    <h5 class="modal-title" id="exampleModalLabel">ارسال رساله SMS لـ<span class="reverName"></span></h5>
		  </div>
		  <div class="modal-body">
		    <div class="row">
		    	<form action="{{route('sendsms')}}" method="POST" enctype="multipart/form-data">
		    		{{csrf_field()}}
		    		<input type="hidden" name="phone" value="">
		    		<div class="col-sm-12">
		    			<textarea rows="15" name="sms_message" class="form-control" placeholder="نص الرساله "></textarea>
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
<!-- /SMS Modal -->
	
<!-- refuse Modal -->
<div class="modal fade" id="refuseModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document" style="width: 60%;">
		<div class="modal-content" style="border-radius: 12px!important;">
		  {{-- <div class="modal-header">
		    <h5 class="modal-title" id="exampleModalLabel">رفض طلب العمل<span class="reverName"></span></h5>
		  </div> --}}
		  <div class="modal-body">
		    <div class="row">
		    	<form action="{{route('refuseUserMeta')}}" method="POST" enctype="multipart/form-data">
					{{csrf_field()}}
					<input type="hidden" name="id" value="{{$usermeta->id}}">
		    		<div class="col-sm-12">
						<label>ماهو سبب الرفض؟</label>
		    		</div>
					<div class="col-sm-12">
						<textarea rows="15" cols="5" name="refuse_reason" class="form-control"  style="font-size: large;"
placeholder="1-....................................................................................................................................................................................................................
2-....................................................................................................................................................................................................................
3-....................................................................................................................................................................................................................
4-....................................................................................................................................................................................................................
5-....................................................................................................................................................................................................................
"></textarea>
		    		</div>
			        <div class="col-sm-12" style="margin-top: 10px">
				      	<button type="submit" class="btn btn-primary addCategory" style="border-radius: 9px!important;">تآكيد</button>
			        </div>

		    	</form>
		    </div>
		  </div>

		</div>
	</div>
</div>
<!-- /refuse Modal -->
<!-- javascript -->
@section('script')
<script>
	$(document).on('change','#type_id',function(){
		var car_type_id = $('#type_id').val();
	    $('#car_type_id').val(car_type_id);
	});

	//put phone in the modal
	$(document).on('click','.SMS',function(){
		$('input[name="phone"]').val($(this).data('phone'));
		$('.reverName').text($(this).data('name'))
	});

function deleteUserMeta(){
	var x = confirm("هل أنت متأكد؟");
    if(x == false){
       return false
    }
    $("#deleteUserMeta").submit();
}	

</script>
@endsection

@endsection