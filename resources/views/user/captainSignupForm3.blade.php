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
                    <span>2</span>
                    <h6>تحميل المستندات</h6>
                </li>

                <li>
                    <span>3</span>
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
                    <h5>تستطيع تصوير المستندات بكاميرا الجوال ورفعها مباشرة </h5>
                </div>

                <form  action="{{route('captainSignup3')}}" id="captainSignupForm3" class="form" method="POST" enctype="multipart/form-data">
                {{csrf_field()}}
                    <div class="form-group">
                        <label class="upload-img">
                            <i class="far fa-address-card"></i>
                            <span>اضافة الهوية الوطنية أو صورة الأقامة</span>
                            <input type="file" name="identity_card" class="image-uploader" required/>
                            <i class="fas fa-camera"></i>
                        </label>
                        @if($errors->has('identity_card'))
                            <div class="alert alert-danger" role="alert">
                            {{$errors->first('identity_card')}}
                            </div>
                        @endif 
                    </div>

                    <div class="form-group">
                        <label class="upload-img">
                            <i class="far fa-address-card"></i>
                            <span>اضافة رخصة القيادة</span>
                            <input type="file" name="driving_license" class="image-uploader" required/>
                            <i class="fas fa-camera"></i>
                        </label>
                        @if($errors->has('driving_license'))
                            <div class="alert alert-danger" role="alert">
                            {{$errors->first('driving_license')}}
                            </div>
                        @endif
                    </div>
                    
                    <div class="form-group">
                        <label class="upload-img">
                            <i class="far fa-address-card"></i>
                            <span>اضافة استمارة السيارة</span>
                            <input type="file" name="car_form" class="image-uploader" required/>
                            <i class="fas fa-camera"></i>
                        </label>
                        @if($errors->has('car_form'))
                            <div class="alert alert-danger" role="alert">
                            {{$errors->first('car_form')}}
                            </div>
                        @endif
                    </div>
                    <div class="form-group">
                        <label class="upload-img">
                            <i class="far fa-address-card"></i>
                            <span>اضافة صورة التفويض ان وجد</span>
                            <input type="file" name="authorization_image" class="image-uploader" required/>
                            <i class="fas fa-camera"></i>
                        </label>
                        @if($errors->has('authorization_image'))
                            <div class="alert alert-danger" role="alert">
                            {{$errors->first('authorization_image')}}
                            </div>
                        @endif
                    </div>
                    
                    
                    <div class="form-group">
                        <label class="upload-img">
                            <i class="far fa-address-card"></i>
                            <span>اضافة حساب IBAN</span>
                            <input type="file" name="iban" class="image-uploader" required/>
                            <i class="fas fa-camera"></i>
                        </label>
                        @if($errors->has('iban'))
                            <div class="alert alert-danger" role="alert">
                            {{$errors->first('iban')}}
                            </div>
                        @endif
                    </div>
                    
                    <div class="form-group">
                        <label class="upload-img">
                            <i class="far fa-address-card"></i>
                            <span>اضافة التأمين علي السيارة</span>
                            <input type="file" name="car_insurance" class="image-uploader" required/>
                            <i class="fas fa-camera"></i>
                        </label>
                        @if($errors->has('car_insurance'))
                            <div class="alert alert-danger" role="alert">
                            {{$errors->first('car_insurance')}}
                            </div>
                        @endif
                    </div>
                    
                    <div class="form-group">
                        <label class="upload-img">
                            <i class="far fa-address-card"></i>
                            <span>اضافة الصورة الشخصية</span>
                            <input type="file" name="personal_image" class="image-uploader" required/>
                            <i class="fas fa-camera"></i>
                        </label>
                        @if($errors->has('personal_image'))
                            <div class="alert alert-danger" role="alert">
                            {{$errors->first('personal_image')}}
                            </div>
                        @endif
                    </div>

                    <button type="submit" class="btn-submit">التالي</button>
                </form>
            </div>
        </div>
    </section>

<script type="text/javascript">
    $('#captainSignupForm3').on('submit',function(){ 
        $('.img-loads').show();
    })  
</script>        
@endsection