@extends('layouts.app')

@section('content')
@section('breadcrumb')                 
                <div class="col-12 col-sm-6">
                    <ol class="breadcrumb wow fadeInUp">
                        <li class="breadcrumb-item"><a href="{{url('/')}}">   الرئيسية  </a></li>
                        <li class="breadcrumb-item active" aria-current="page"> تسجيل الدخول </li>
                    </ol>
                </div>
@endsection

    <div class="login-form wow fadeInUp">
        <div class="container">
            <div class="box">
                <div class="log-head">
                    <div class="basic-head">
                        <h4>  تسجيل الدخول  </h4>
                    </div>
                </div>

                <div class="content">
                    <div class="row">
                        <div class="col-12  col-lg-6">
                            <div class="fields">
        <main class="py-4">               
            @if (session('error'))
                <div class="alert alert-danger" role="alert">
                {{ session('error') }}
                </div>         
            @endif     
        </main>                                
                                <form method="POST" action="{{ action('Auth\LoginController@authenticate') }}">
                                    @csrf
                                    <input type="text" name="phone" value="{{old('phone')}}" class="form-control"  placeholder="رقم الجوال ">
                                    <input type="password" name="password" class="form-control"  placeholder=" كلمة المرور  ">
                                    <div class="block">
                                        <a href="{{route('password.request')}}">  نسيت كلمة المرور؟ </a>
                                        <button class="btn btn-green btn-animate" type="submit"> <span> دخول  </span></button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="col-12 col-lg-6">
                            <div class="link">
                                <h6>  لا تمتلك حساب ! </h6>
                                <a href="{{route('register')}}">  إضغط هنا </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
