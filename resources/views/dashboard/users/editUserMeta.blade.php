@extends('dashboard.layout.master')
    @section('title')
	تعديل طلب العمل كقائد
	@endsection
@section('content')
<div class="panel panel-flat">
	<div class="panel-heading">
		<h5 class="panel-title">تعديل طلب :{{$usermeta->name}}</h5>
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
                <form action="{{route('updateUserMeta')}}" method="POST" enctype="multipart/form-data">
					{{csrf_field()}}
					<input type="hidden" name="id" value="{{$usermeta->id}}">
						<table class="table table-bordered table-strapped">
							<tbody>
								<tr>
		                           <td>اسم العميل </td>
		                           <td><input type="text" name="name" value="{{$usermeta->name}}" class="form-control"></td>
								</tr>
								<tr>
		                           <td>الدولة</td>
		                           <td>
		                           	    <select name="country_id" class="form-control">
		                           	    @foreach($countries as $country)
		                           	        <option value="{{$country->id}}" {{($country->id == $usermeta->country_id)? 'selected' : ''}}>{{$country->name_ar}}</option>
		                                @endforeach
		                                </select>
		                           </td>
								</tr>
								<tr>
		                           <td>المدينة</td>
		                           <td>
		                                <select name="city_id" id="city_id" class="form-control">
		                                    @foreach($cities as $city)
		                                       <option value="{{$city->id}}" {{($city->id == $usermeta->city_id)? 'selected' : ''}}>{{$city->name_ar}}</option>
		                                    @endforeach
		                                </select>
		                                @if($errors->has('city_id'))
                                            <div class="alert alert-danger" role="alert">
                                            {{$errors->first('city_id')}}
                                            </div>
                                        @endif  
		                           </td>
								</tr>
								<tr>
		                           <td>الهاتف</td>
		                           <td><input type="text" name="phone" value="{{$usermeta->phone}}" class="form-control"></td>
								</tr>
								<tr>
		                           <td>رقم الهوية / الاقامة </td>
		                           <td><input type="text" name="identity_number" value="{{$usermeta->identity_number}}" class="form-control"></td>
								</tr>
								<tr>
		                           <td>البريد الإلكتروني</td>
		                           <td><input type="email" name="email" value="{{$usermeta->email}}" class="form-control"></td>
								</tr>
								<tr>
		                           <td>تاريخ الميلاد (ميلادي)</td>
		                           <td><input type="text" name="birthdate" value="{{$usermeta->birthdate}}" class="form-control" placeholder="تاريخ الميلاد (1990-01-01)" /></td>
								</tr>
								<!-- <tr>
		                           <td>تاريخ الميلاد (هجري)</td>
		                           <td><input type="date" name="birthdate_hijri" value="{{$usermeta->birthdate_hijri}}" class="form-control" placeholder="تاريخ الميلاد (1410-01-01)" /></td>
								</tr> -->
								<tr>
		                           <td>الجنس</td>
		                           <td>
		                           	<select name="gender" class="form-control">
	                                    <option value="male" {{($usermeta->gender == 'male')? 'selected' : ''}}>ذكر</option>
	                                    <option value="female" {{($usermeta->gender == 'female')? 'selected' : ''}}>أنثي</option>
                                	</select>
		                           </td>
								</tr>
								<!-- <tr>
		                           <td>نوع الكابتن</td>
		                           <td>
		                           	<select name="captain_type" class="form-control">
	                                    <option value="saudi" {{($usermeta->captain_type == 'saudi')? 'selected' : ''}}>سعودى</option>
	                                    <option value="driver" {{($usermeta->captain_type == 'driver')? 'selected' : ''}}>جنسية آخري</option>
                                	</select>
		                           </td>
								</tr> -->
								
                                <tr>
		                           <td> الهوية الوطنية أو صورة الأقامة</td>
		                           <td>
			                            @if($usermeta->identity_card)
			                           	  <a href="{{url('img/user/usermeta/'.$usermeta->identity_card)}}"><img class="usermetaimg" src="{{asset('img/user/usermeta/'.$usermeta->identity_card)}}" > </a>
									    @else
			                              لا يوجد
									    @endif
					                    <input type="file" name="identity_card" class="image-uploader" />
		                           </td>
								</tr>

								<tr>
		                           <td> رخصة القيادة</td>
		                           <td>
			                            @if($usermeta->driving_license)
			                           	  <a href="{{url('img/user/usermeta/'.$usermeta->driving_license)}}"><img class="usermetaimg" src="{{asset('img/user/usermeta/'.$usermeta->driving_license)}}" > </a>
									    @else
			                              لا يوجد
									    @endif
					                    <input type="file" name="driving_license" class="image-uploader" />
		                           </td>
								</tr>

								<tr>
		                           <td>  الرقم التسلسلي</td>
		                           <td>
			                            @if($usermeta->car_form)
			                           	<a href="{{url('img/user/usermeta/'.$usermeta->car_form)}}"><img class="usermetaimg" src="{{asset('img/user/usermeta/'.$usermeta->car_form)}}" > </a>
									    @else
			                              لا يوجد
			                            @endif
					                    <input type="file" name="car_form" class="image-uploader" />
		                           </td>
								</tr>
								<tr>
									<td> صورة التفويض</td>
									<td>
										 @if($usermeta->authorization_image)
											<a href="{{url('img/user/usermeta/'.$usermeta->authorization_image)}}"><img class="usermetaimg" src="{{asset('img/user/usermeta/'.$usermeta->authorization_image)}}" > </a>
										 @else
										   لا يوجد
										 @endif
										 <input type="file" name="authorization_image" class="image-uploader" />
									</td>
								 </tr>

								{{-- <tr>
		                           <td> حساب IBAN</td>
		                           <td>
			                            @if($usermeta->iban)
			                           	<a href="{{url('img/user/usermeta/'.$usermeta->iban)}}"><img class="usermetaimg" src="{{asset('img/user/usermeta/'.$usermeta->iban)}}" > </a>
									    @else
			                             لا يوجد
									    @endif
					                    <input type="file" name="iban" class="image-uploader" />
		                           </td>
								</tr>
								<tr>
		                           <td> التأمين علي السيارة</td>
		                           <td>
			                           	@if($usermeta->car_insurance)
			                           	<a href="{{url('img/user/usermeta/'.$usermeta->car_insurance)}}"><img class="usermetaimg" src="{{asset('img/user/usermeta/'.$usermeta->car_insurance)}}" > </a>
									    @else
									    لا يوجد
									    @endif
					                    <input type="file" name="car_insurance" class="image-uploader" />
		                           </td>
								</tr> --}}

								<tr>
		                           <td>الصورة الشخصية </td>
		                           <td>
		                           		@if($usermeta->personal_image)
			                           	<a href="{{url('img/user/usermeta/'.$usermeta->personal_image)}}"><img class="usermetaimg" src="{{asset('img/user/usermeta/'.$usermeta->personal_image)}}"> </a>
			                            @else
			                              لا يوجد
			                            @endif
					                    <input type="file" name="personal_image" class="image-uploader" />
		                           </td>
								</tr>

								<tr>
		                           <td> صورة للسيارة</td>
		                           <td>
		                           	 	@if($usermeta->car_image)
			                           	<a href="{{url('img/user/usermeta/'.$usermeta->car_image)}}"><img class="usermetaimg" src="{{asset('img/user/usermeta/'.$usermeta->car_image)}}" > </a>
									    @else
			                             لا يوجد
									    @endif
					                    <input type="file" name="car_image" class="image-uploader" />
		                           </td>
								</tr>

								<tr>
		                           <td> ماركة السيارة (تويوتا)</td>
		                           <td>
		                            <input type="text" name="car_type" value="{{$usermeta->car_type}}" class="form-control">
		                           </td>
								</tr>

								<tr>
		                           <td> موديل السيارة (كورولا)</td>
		                           <td>
		                           		<input type="text" name="car_model" value="{{$usermeta->car_model}}" class="form-control"></td>
		                           </td>
								</tr>
								<tr>
		                           <td> لون السيارة</td>
		                           <td>
		                           		<input type="text" name="car_color" value="{{$usermeta->car_color}}" class="form-control"></td>
		                           </td>
								</tr>
								<tr>
		                           <td> سنة الصنع (2024)</td>
		                           <td>
		                           		<input type="text" name="manufacturing_year" value="{{$usermeta->manufacturing_year}}" class="form-control"></td>
		                           </td>
								</tr>
								<tr>
		                           <td> رقم استمارة السيارة</td>
		                           <td>
		                           		<input type="text" name="sequenceNumber" value="{{$usermeta->sequenceNumber}}" class="form-control"></td>
		                           </td>
								</tr>

								<tr>
		                           <td> الحروف بلوحة السيارة</td>
		                           <td>
		                           		<input type="text" name="car_letters" value="{{$usermeta->car_letters}}" class="form-control"></td>
		                           </td>
								</tr>

								<tr>
		                           <td> الأرقام بلوحة السيارة</td>
		                           <td>
		                           		<input type="text" name="car_numbers" value="{{$usermeta->car_numbers}}" class="form-control"></td>
		                           </td>
								</tr>
								<tr>
									<td>نوع لوحة السيارة</td>
									<td>
										<select name="plateType" class="form-control">
		                                    @foreach($plateTypes as $key => $value)
		                                       <option value="{{$key}}" {{($usermeta->plateType == $key)? 'selected' : ''}}>{{$value}}</option>
		                                    @endforeach
                                		</select>

									</td>

								</tr>
								<tr>
									<td>اسم البنك </td>
									<td>
											<input type="text" name="bank_name" value="{{$usermeta->bank_name}}" class="form-control"></td>
									</td>
								</tr>
								<tr>
									<td>رقم الايبان </td>
									<td>
											<input type="text" name="iban" value="{{$usermeta->iban}}" class="form-control"></td>
									</td>
								</tr>
								<tr>
									<td>رقم stc </td>
									<td>
											<input type="text" name="stc_number" value="{{'0'.$usermeta->stc_number}}" class="form-control"></td>
									</td>
								</tr>

		                    </tbody>
						</table>
					    <button type="submit" class="btn btn-success col-sm-6">حفظ<i style="color: #fff" class="glyphicon glyphicon-saved"></i> </button>
					    <a class="btn btn-warning col-sm-6" href="{{route('userMeta',$usermeta->id)}}">العودة <i style="color: #fff" class="icon-enter5"></i> </a>

					</form>							


			</div>
		</div>
	</div>
</div>


	
<!-- javascript -->
	@section('script')
<script type="text/javascript">
$(document).ready(function(){

        $('#country_id').on('change' ,function(e) { //any select change on the dropdown with id country trigger this code
        e.preventDefault();
        var country_id = $('#country_id').val();
        $.getJSON( "<?=url('getCitiesByCountry/');?>"+"/"+ country_id, function(data) {
            var html = '';
            var len = data.length;
            for (var i = 0; i < len; i++) {
                 html += '<option value="'+data[i].id+'">'+data[i].name_ar+'</option>';
            }
            console.log(html);
            $('#city_id').html("");
            $('#city_id').append(html);


        });
    });
        
});
</script> 
	@endsection

@endsection