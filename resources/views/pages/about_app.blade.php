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
                    {{-- <li class="active">
                        <span>
                            <i class="fas fa-check"></i>
                        </span>
                    </li>
    
                    <li class="active">
                        <span>
                            <i class="fas fa-check"></i>
                        </span>
                    </li>
     --}}
                    <li class="active">
                        {{-- <span>3</span> --}}
                        <h6>عن التطبيق</h6>
                    </li>
                </ul>
            </div>
        </section>
@endsection
@section('content')
    <section>
        <div class="details-section">
            <div class="container">
                <div class="details-txt">
                    <div class="text">
                        <span>عن التطبيق</span>
                        <p>
                            {!! $about_app !!}
                        </p>
                    </div>
                    
                    <a href="#" class="back" onclick="goBack()">رجوع</a>
                </div>
            </div>
        </div>
    </section>
@endsection