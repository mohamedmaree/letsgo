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
                    <span>1</span>
                    <h6>أنواع القائد</h6>
                </li>

                <li>
                    <span>2</span>
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
                <form  action="{{route('captainSignup2')}}" id="captainSignupForm2" class="form" method="POST" enctype="multipart/form-data">
                {{csrf_field()}}
                    <div class="sign-radio">
                        <h6>تقديم خدماتك</h6>
                        <label class="radio-label">
                            داخل مدينتك فقط
                            <input type="radio" name="service_in" value="mycity" />
                            <span class="checkmark"></span>
                        </label>

                        <label class="radio-label">
                            بين المدن
                            <input type="radio" name="service_in" value="between_cities" />
                            <span class="checkmark"></span>
                        </label>

                        <label class="radio-label">
                            داخل مدينتك وبين المدن معاً
                            <input type="radio" name="service_in" value="all" checked="checked">
                            <span class="checkmark"></span>
                        </label>
                    </div>
                            @if($errors->has('service_in'))
                                <div class="alert alert-danger" role="alert">
                                {{$errors->first('service_in')}}
                                </div>
                            @endif                    
                    <div class="sign-radio">
                        <h6>نوع الخدمة</h6>
                        <label class="radio-label">
                            توصيل ركاب
                            <input type="radio" name="service_type" value="people" />
                            <span class="checkmark"></span>
                        </label>

                        <label class="radio-label">
                            توصيل أي شئ 
                            <input type="radio" name="service_type" value="goods" />
                            <span class="checkmark"></span>
                        </label>

                        <label class="radio-label">
                            الجميع
                            <input type="radio" name="service_type" value="all" checked="checked" />
                            <span class="checkmark"></span>
                        </label>
                    </div>
                            @if($errors->has('service_type'))
                                <div class="alert alert-danger" role="alert">
                                {{$errors->first('service_type')}}
                                </div>
                            @endif   
                    <div class="sign-radio">
                        <h6>اختار ما يناسبك</h6>
                        <div class="go-details">
                            <label class="radio-label">
                                <span>سعودي</span>
                                <input type="radio" name="captain_type" value="saudi" checked="checked" />
                                <span class="checkmark"></span>
                            </label>
                            <p>
                                الحد الأدني للعمر 20 سنة حاصل علي رخصة قيادة وتأمين علي المركبة المستخدمة 
                            </p>
                            <!-- <i class="fas fa-chevron-left"></i> -->
                        </div>

                        <div class="go-details">
                            <label class="radio-label">
                                <span>قائد سيارات ليموزين</span>
                                <input type="radio" name="captain_type" value="driver" />
                                <span class="checkmark"></span>
                            </label>
                            <p>
                                قائد مع رخصة قيادة واقامة يعمل مع شركة ليموزين خاصة 
                            </p>
                            <!-- <i class="fas fa-chevron-left"></i> -->
                        </div>
                    </div>
                            @if($errors->has('captain_type'))
                                <div class="alert alert-danger" role="alert">
                                {{$errors->first('captain_type')}}
                                </div>
                            @endif  
                    <button type="submit" class="btn-submit">التالي</button>
                </form>
            </div>
        </div>
    </section>

<script type="text/javascript">
    $('#captainSignupForm2').on('submit',function(){ 
        $('.img-loads').show();
    })  
</script>     
@endsection