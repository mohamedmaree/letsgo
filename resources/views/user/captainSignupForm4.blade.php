@extends('layouts.master')
@section('header')
    <header>
        <div class="header head">
            <div class="container">
                <div class="head-txt">
                    <i class="fas fa-chevron-right" onclick="goBack()"></i>
                    <img src="{{asset('dashboard/uploads/setting/site_logo/'.setting('site_logo'))}}" class="img-responsive logo" />
                </div>
            </div>
        </div>
    </header>

    <section>
        <div class="container">
            <ul class="arrange">
                <li class="active">
                    <span>
                        <i class="fas fa-check"></i>
                    </span>
                </li>

                <li class="active">
                    <span>
                        <i class="fas fa-check"></i>
                    </span>
                </li>

                <li class="active">
                    <span>3</span>
                    <h6>تحميل المستندات</h6>
                </li>
            </ul>
        </div>
    </section>
@endsection

@section('content')
    <section>
        <div class="sign">
            <div class="container">

                <div class="text-center">
                    <h5>سجل سياراتك معنا وأحصل علي دخل أضافي متي ماحبيت !</h5>
                </div>

                <form  action="{{route('captainSignup4')}}" id="captainSignup4" class="form" method="POST" enctype="multipart/form-data">
                {{csrf_field()}}
                    <div class="form-group">
                        <label class="upload-img">
                            <i class="far fa-address-card"></i>
                            <span>اضافة صورة للسيارة</span>
                            <input type="file" name="car_image" class="image-uploader" required/>
                            <i class="fas fa-camera"></i>
                        </label>
                                        @if($errors->has('car_image'))
                                            <div class="alert alert-danger" role="alert">
                                            {{$errors->first('car_image')}}
                                            </div>
                                        @endif                         
                    </div>

                    <div class="sign-radio">
                            <div class="form-group">
                                <input type="text" name="car_type" value="{{old('car_type')}}" class="form-control" placeholder="ماركة السيارة (تويوتا)" required/>
                                        @if($errors->has('car_type'))
                                            <div class="alert alert-danger" role="alert">
                                            {{$errors->first('car_type')}}
                                            </div>
                                        @endif  
                            </div>
                            
                            <div class="form-group">
                                <input type="text" name="car_model" value="{{old('car_model')}}" class="form-control" placeholder="موديل السيارة (كورولا)" required/>
                                        @if($errors->has('car_model'))
                                            <div class="alert alert-danger" role="alert">
                                            {{$errors->first('car_model')}}
                                            </div>
                                        @endif  
                            </div>
                            <div class="form-group">
                                <input type="text" name="car_color" value="{{old('car_color')}}" class="form-control" placeholder="لون السيارة" required/>
                                        @if($errors->has('car_color'))
                                            <div class="alert alert-danger" role="alert">
                                            {{$errors->first('car_color')}}
                                            </div>
                                        @endif  
                            </div>
                            <div class="form-group">
                                <input type="number" name="manufacturing_year" value="{{old('manufacturing_year')}}" class="form-control" placeholder="سنة الصنع (2019)" required/>
                                        @if($errors->has('manufacturing_year'))
                                            <div class="alert alert-danger" role="alert">
                                            {{$errors->first('manufacturing_year')}}
                                            </div>
                                        @endif  
                            </div>

                        <div class="form-group">
                            <input type="text" name="sequenceNumber" value="{{old('sequenceNumber')}}" class="form-control" placeholder="رقم استمارة السيارة" required/>
                                        @if($errors->has('sequenceNumber'))
                                            <div class="alert alert-danger" role="alert">
                                            {{$errors->first('sequenceNumber')}}
                                            </div>
                                        @endif  
                        </div>
                        <div class="form-group">
                            <input type="text" name="car_letters" value="{{old('car_letters')}}" class="form-control" placeholder="الحروف بلوحة السيارة" required/>
                                        @if($errors->has('car_letters'))
                                            <div class="alert alert-danger" role="alert">
                                            {{$errors->first('car_letters')}}
                                            </div>
                                        @endif  
                        </div>
                        <div class="form-group">
                            <input type="text" name="car_numbers" value="{{old('car_numbers')}}" class="form-control" placeholder="الأرقام بلوحة السيارة" required/>
                                        @if($errors->has('car_numbers'))
                                            <div class="alert alert-danger" role="alert">
                                            {{$errors->first('car_numbers')}}
                                            </div>
                                        @endif  
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <select name="plateType" class="form-control">
                                    <option disabled selected> نوع لوحة السيارة</option>
                                    @foreach($plateTypes as $key => $value)
                                       <option value="{{$key}}">{{$value}}</option>
                                    @endforeach
                                </select>
                                <i class="fas fa-chevron-down"></i>
                            </div>
                                        @if($errors->has('plateType'))
                                            <div class="alert alert-danger" role="alert">
                                            {{$errors->first('plateType')}}
                                            </div>
                                        @endif                              
                        </div>
                    </div>

                    <div class="sign-radio">
                        <h6>الاتفاقيات القانوية</h6>
                        <label class="radio-label">
                            أوافق على <a href="{{url('conditions')}}"> ( الشروط والاحكام ) </a>
                            <input type="checkbox" id="reading_terms" checked="checked" name="reading_terms">
                            <span class="checkmark"></span>
                        </label>
                    </div>

                    <button type="submit" class="btn-submit">التالي</button>
                </form>
            </div>
        </div>
    </section>

<script type="text/javascript">

    $('#captainSignup4').on('submit',function(){ 
        var c = $('#reading_terms').prop('checked');
        if(c == false){
           alert('يجب عليك الموافقة علي الشروط والأحكام.');
           return false;
        }
        $('.img-loads').show();
    });
       
</script>

@endsection