@extends('layouts.welcomePage')
@section('head')
    <style>
        .about-img::before{
            background-color: {{ $welcomePageSettings['color_about']??'' }};
        }
    </style>
@stop

@section('content')
    <section id="about" class="about-us">
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-12 overflow-hidden">
                    <div class="about-img wow fadeInRight">
                        <img src="{{  asset('assets/uploads/welcomePageSettings/') . '/' . $welcomePageSettings['img_about_msg']??'' }}">
                    </div>
                </div>
                <div class="col-md-6 col-12 d-flex align-items-center overflow-hidden">
                    <div class="about-text wow fadeInLeft">
                        <h3>عن التطبيق</h3>
                        <p>
                            {!! $welcomePageSettings['about'] !!}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section id="how" class="about-app" style="background-color: {{ $welcomePageSettings['color_advantage']??'' }}">
        <div class="container">
            <h3 class="titl">مميزات التطبيق</h3>
            <p class="textInfo">يقم التطبيق مجموعة من المميزات و الخدمات لعملائنا</p>
            <div class="row">
                @foreach($advantages as $advantage)
                    <div class="col-lg-4 col-md-6 col-sm-12" style="margin-bottom: 20px">
                        <div class="howItem wow flipInY">
                            <div class="icon">
                                <img src="{{  asset('assets/uploads/advantages/') . '/' . $advantage['image'] }}" width="85" height="85" alt="">
                            </div>
                            <div class="txt">
                                <h4 class="mt-3">{{ $advantage->title }}</h4>
                                <p class="gray">{{ $advantage->content }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <section id="slider" class="appPage mt-5 mb-5">
        <div class="container">
            <h3 class="titl">صفحات التطبيق</h3>
            <p class="textInfo">يقم التطبيق مجموعة من المميزات و الخدمات لعملائنا</p>
            <div class="firstcarousel owl-carousel owl-theme">
                @foreach($imagesApp as $image)
                    <div class="item">
                        <img src="{{  asset('assets/uploads/imageApp/' . '/' . $image['image']??'') }}">
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    {{--    <section class="videos">--}}
    {{--        <div class="container overflow-hidden">--}}
    {{--            <a data-fancybox="gallery" href="{{ setting()['video'] }}">--}}
    {{--                <div class="video-display wow fadeInDown">--}}
    {{--                    <img src="{{ url('site') }}/images/Design.png">--}}
    {{--                    <div class="playIcon">--}}
    {{--                        <i class="fas fa-play"></i>--}}
    {{--                    </div>--}}
    {{--                    <source src="{{ setting()['video'] }}" type="video/mp4">--}}
    {{--                </div>--}}
    {{--            </a>--}}
    {{--        </div>--}}
    {{--    </section>--}}
    <section class="opinion mt-5 mb-5">
        <div class="container pt-2 pb-4">
            <h3 class="titl">اراء العملاء</h3>
            <p class="textInfo">نهتم بجمع تقييمات وآراء عملائنا حيث أننا نعمل على تقديم خدماتنا بأفضل جودة ممكنة</p>
            <div class="secondcarousel owl-carousel owl-theme mt-5 overflow-hidden">
                @foreach($customersReviews as $value)
                    <div class="item wow fadeInDown">
                        <p class="gray">
                            {{ $value['comment'] }}
                        </p>
                        <div class="client-info">
                            <img src="{{ asset('assets/uploads/customer_reviews') . '/' . $value['image'] }}" alt="">
                            <div class="info">
                                <p class="name">{{ $value['name'] }}</p>
                                <ul class="rate">
                                    <li class="{{ $value['rate'] >= 1 ? 'rated' : '' }}">
                                        <i class="fas fa-star gold"></i>
                                    </li>
                                    <li class="{{ $value['rate'] >= 2 ? 'rated' : '' }}">
                                        <i class="fas fa-star gold"></i>
                                    </li>
                                    <li class="{{ $value['rate'] >= 3 ? 'rated' : '' }}">
                                        <i class="fas fa-star gold"></i>
                                    </li>
                                    <li class="{{ $value['rate'] >= 4 ? 'rated' : '' }}">
                                        <i class="fas fa-star gold"></i>
                                    </li>
                                    <li class="{{ $value['rate'] >= 5 ? 'rated' : '' }}">
                                        <i class="fas fa-star"></i>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

@stop

@section('footer')
@stop
