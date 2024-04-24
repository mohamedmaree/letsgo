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
                    </li> --}}
   
                    <li class="active">
                         {{-- <span>3</span>  --}}
                        <h6>تواصل معنا </h6>
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
                        {{-- <span>تواصل معنا </span> --}}

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

                        
                    </div>
                    
                    <a href="#" class="back" onclick="goBack()">رجوع</a>
                </div>
            </div>
        </div>
    </section>
@endsection