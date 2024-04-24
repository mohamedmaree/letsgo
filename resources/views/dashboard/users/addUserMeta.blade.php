@extends('dashboard.layout.master')
    @section('title')
	اضافة طلب العمل كقائد
	@endsection
@section('content')
<div class="panel panel-flat">
	<div class="panel-heading">
		<h5 class="panel-title">اضافة طلب</h5>
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
                <form action="{{route('storeUserMeta')}}" method="POST" enctype="multipart/form-data">
					{{csrf_field()}}
						<table class="table table-bordered table-strapped">
							<tbody>
								<tr>
		                           <td>اسم العميل </td>
		                           <td><input type="text" name="name" class="form-control" required></td>
								</tr>
								<tr>
		                           <td>الدولة</td>
		                           <td>
		                           	    <select name="country_id" class="form-control" required>
		                           	    @foreach($countries as $country)
		                           	        <option value="{{$country->id}}" >{{$country->name_ar}}</option>
		                                @endforeach
		                                </select>
		                           </td>
								</tr>
								<tr>
		                           <td>المدينة</td>
		                           <td>
		                                <select name="city_id" id="city_id" class="form-control" required>
		                                    @foreach($cities as $city)
		                                       <option value="{{$city->id}}" >{{$city->name_ar}}</option>
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
		                           <td><input type="text" name="phone" class="form-control" required></td>
								</tr>
								<tr>
		                           <td>رقم الهوية / الاقامة </td>
		                           <td><input type="text" name="identity_number"  class="form-control" required></td>
								</tr>
								<tr>
		                           <td>البريد الإلكتروني</td>
		                           <td><input type="email" name="email"class="form-control" required></td>
								</tr>
								<tr>
		                           <td>تاريخ الميلاد (ميلادي)</td>
		                           <td><input type="date" name="birthdate"class="form-control" placeholder="تاريخ الميلاد (1990-01-01)" required /></td>
								</tr>
								<!-- <tr>
		                           <td>تاريخ الميلاد (هجري)</td>
		                           <td><input type="date" name="birthdate_hijri"  class="form-control" placeholder="تاريخ الميلاد (1410-01-01)" /></td>
								</tr> -->
								<tr>
		                           <td>الجنس</td>
		                           <td>
		                           	<select name="gender" class="form-control" required>
	                                    <option value="male" >ذكر</option>
	                                    <option value="female" >أنثي</option>
                                	</select>
		                           </td>
								</tr>
								<!-- <tr>
		                           <td>نوع الكابتن</td>
		                           <td>
		                           	<select name="captain_type" class="form-control">
	                                    <option value="saudi" >سعودى</option>
	                                    <option value="driver" >جنسية آخري</option>
                                	</select>
		                           </td>
								</tr> -->
								
                                <tr>
		                           <td> الهوية الوطنية أو صورة الأقامة</td>
		                           <td>
					                    <input type="file" name="identity_card" class="image-uploader" required/>
		                           </td>
								</tr>

								<tr>
		                           <td> رخصة القيادة</td>
		                           <td>
					                    <input type="file" name="driving_license" class="image-uploader" required/>
		                           </td>
								</tr>

								<tr>
		                           <td>  الرقم التسلسلي</td>
		                           <td>
					                    <input type="file" name="car_form" class="image-uploader" required/>
		                           </td>
								</tr>
								<tr>
									<td> صورة التفويض</td>
									<td>
										 <input type="file" name="authorization_image" class="image-uploader" /required>
									</td>
								 </tr>

								{{-- <tr>
		                           <td> حساب IBAN</td>
		                           <td>
					                    <input type="file" name="iban" class="image-uploader" />
		                           </td>
								</tr>
								<tr>
		                           <td> التأمين علي السيارة</td>
		                           <td>
					                    <input type="file" name="car_insurance" class="image-uploader" />
		                           </td>
								</tr> --}}

								<tr>
		                           <td>الصورة الشخصية </td>
		                           <td>
					                    <input type="file" name="personal_image" class="image-uploader" required/>
		                           </td>
								</tr>

								<tr>
		                           <td> صورة للسيارة</td>
		                           <td>
					                    <input type="file" name="car_image" class="image-uploader" required/>
		                           </td>
								</tr>

								<tr>
		                           <td> ماركة السيارة (تويوتا)</td>
		                           <td>
		                            <input type="text" name="car_type"  class="form-control" required>
		                           </td>
								</tr>

								<tr>
		                           <td> موديل السيارة (كورولا)</td>
		                           <td>
		                           		<input type="text" name="car_model"  class="form-control" required></td>
		                           </td>
								</tr>
								<tr>
		                           <td> لون السيارة</td>
		                           <td>
		                           		<input type="text" name="car_color" class="form-control" required></td>
		                           </td>
								</tr>
								<tr>
		                           <td> سنة الصنع (2024)</td>
		                           <td>
		                           		<input type="text" name="manufacturing_year" class="form-control" required></td>
		                           </td>
								</tr>
								<tr>
		                           <td> رقم استمارة السيارة</td>
		                           <td>
		                           		<input type="text" name="sequenceNumber" class="form-control" required></td>
		                           </td>
								</tr>

								<tr>
		                           <td> الحروف بلوحة السيارة</td>
		                           <td>
		                           		<input type="text" name="car_letters" class="form-control" required></td>
		                           </td>
								</tr>

								<tr>
		                           <td> الأرقام بلوحة السيارة</td>
		                           <td>
		                           		<input type="text" name="car_numbers" class="form-control" required></td>
		                           </td>
								</tr>
								<tr>
									<td>نوع لوحة السيارة</td>
									<td>
										<select name="plateType" class="form-control" required>
		                                    @foreach($plateTypes as $key => $value)
		                                       <option value="{{$key}}" >{{$value}}</option>
		                                    @endforeach
                                		</select>

									</td>

								</tr>
								<tr>
									<td>اسم البنك </td>
									<td>
											<input type="text" name="bank_name" class="form-control" required></td>
									</td>
								</tr>
								<tr>
									<td>رقم الايبان </td>
									<td>
											<input type="text" name="iban"  class="form-control" required></td>
									</td>
								</tr>
								<tr>
									<td>رقم stc </td>
									<td>
											<input type="text" name="stc_number" class="form-control" required></td>
									</td>
								</tr>

		                    </tbody>
						</table>
					    <button type="submit" class="btn btn-success col-sm-12">حفظ<i style="color: #fff" class="glyphicon glyphicon-saved"></i> </button>

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