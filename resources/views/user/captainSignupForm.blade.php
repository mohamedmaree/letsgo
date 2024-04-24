@extends('layouts.master')
@section('header')
    <header>
        <div class="header">
            <div class="container">
                <a href="#">
                    <i class="fas fa-times"></i>
                </a>
                <img src="{{asset('dashboard/uploads/setting/site_logo/'.setting('site_logo'))}}" class="img-responsive logo" />
            </div>
        </div>
    </header>
@endsection

@section('content')
    <section>
        <div class="container">
            <div class="leader-register">
                <div class="text-center">
                    <h6>سجل كقائد {{setting('site_title')}}</h6>
                </div>
                <div class="col-lg-4 col-md-6 col-12">
                    <div class="footer-item">
                        {{-- <h4>تواصل معنا </h4> --}}
                        <ul class="p-0">
                            <li>
                                <i class="fas fa-map-marker-alt"></i>
                                <span> {{$welcomePageSettings['address']}}</span>
                            </li>
                            <li>
                                <a href="tel:">
                                    <i class="fas fa-phone"></i>
                                    <span>{{$welcomePageSettings['phone']}}</span>
                                </a>
                            </li>
                            <li>
                                <a href="mailTo:">
                                    <i class="fas fa-envelope"></i>
                                    <span>{{$welcomePageSettings['email']}}</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                {{-- <form  action="{{route('captainSignup')}}" class="form" method="POST" enctype="multipart/form-data" id="captainSignupForm">
                {{csrf_field()}}
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <input type="text" name="name" value="{{old('name')}}" class="form-control" placeholder="الاسم كامل" required/>
                            </div>
                            @if($errors->has('name'))
                                <div class="alert alert-danger" role="alert">
                                {{$errors->first('name')}}
                                </div>
                            @endif
                        </div>
                        <div class="col-12">
                            <div class="country-select">
                                <div class="row">
                                    <div class="form-group col-4">
                                        <select name="country_id" id="country_id" class="form-control" >
                                            @foreach($countries as $country)
                                                <option value="{{$country->id}}" {{($current_country == $country->iso2)? 'selected':''}}>{{$country->name_ar}} ({{$country->phonekey}})</option>
                                            @endforeach
                                        </select>                                      
                                    </div>
                                    <div class="form-group col-8">
                                        <input type="number" name="phone" value="{{old('phone')}}" class="form-control" placeholder="رقم الجوال المسجل في ابشر" required/>
                                    </div>                                   
                                </div>                                 
                            </div>
                                        @if($errors->has('country_id'))
                                            <div class="alert alert-danger" role="alert">
                                            {{$errors->first('country_id')}}
                                            </div>
                                        @endif  
                                        @if($errors->has('phone'))
                                            <div class="alert alert-danger" role="alert">
                                            {{$errors->first('phone')}}
                                            </div>
                                        @endif                              
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <input type="password" name="password"  class="form-control" placeholder="كلمة المرور" required/>
                            </div>
                            @if($errors->has('password'))
                                <div class="alert alert-danger" role="alert">
                                {{$errors->first('password')}}
                                </div>
                            @endif
                        </div>
                        
                        <div class="col-12">
                            <div class="form-group">
                                <input type="text" name="identity_number" value="{{old('identity_number')}}" class="form-control" placeholder="رقم الهوية / الأقامة" required/>
                                        @if($errors->has('identity_number'))
                                            <div class="alert alert-danger" role="alert">
                                            {{$errors->first('identity_number')}}
                                            </div>
                                        @endif  
                            </div>
                        </div>   

                        <div class="col-12">
                            <div class="form-group">
                                <select name="gender" class="form-control">
                                    <option value="male">ذكر</option>
                                    <option value="female">أنثي</option>
                                </select>
                                <i class="fas fa-chevron-down"></i>
                            </div>
                                @if($errors->has('gender'))
                                    <div class="alert alert-danger" role="alert">
                                    {{$errors->first('gender')}}
                                    </div>
                                @endif                              
                        </div>
                                         
                        <div class="col-12">
                            <div class="form-group">
                                <input type="email" name="email" value="{{old('email')}}" class="form-control" placeholder="البريد الإلكتروني" required/>
                                        @if($errors->has('email'))
                                            <div class="alert alert-danger" role="alert">
                                            {{$errors->first('email')}}
                                            </div>
                                        @endif  
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <input type="text"  name="birthdate" value="{{old('birthdate')}}" class="form-control" placeholder="تاريخ الميلاد بالصيغة الميلادية  (01-10-1990)" required/>
                                        @if($errors->has('birthdate'))
                                            <div class="alert alert-danger" role="alert">
                                            {{$errors->first('birthdate')}}
                                            </div>
                                        @endif  
                            </div>
                        </div>                        
                        <div class="col-12">
                            <div class="form-group">
                                <select name="city_id" id="city_id" class="form-control">
                                    @foreach($cities as $city)
                                       <option value="{{$city->id}}">{{$city->name_ar}}</option>
                                    @endforeach
                                </select>
                                <i class="fas fa-chevron-down"></i>
                            </div>
                                        @if($errors->has('city_id'))
                                            <div class="alert alert-danger" role="alert">
                                            {{$errors->first('city_id')}}
                                            </div>
                                        @endif                              
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <input type="text" name="friend_code" value="{{$friend_code}}" class="form-control" placeholder="كود الدعوة لحصول صديقك علي {{setting('invite_captain_balance')}} {{setting('site_currency_ar')}} هدية" />
                            </div>
                        </div>

                        <div class="col-12">
                            <button type="submit" class="btn-submit">سجل الآن</button>
                        </div>
                    </div>
                </form> --}}
            </div>
        </div>
    </section>

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

    $('#captainSignupForm').on('submit',function(){ 
        $('.img-loads').show();
    })    
});
</script> 
@endsection