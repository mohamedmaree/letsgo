<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('welcomePage/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('welcomePage/css/animate.css') }}">
    <link rel="stylesheet" href="{{ asset('welcomePage/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('welcomePage/css/jquery.fancybox.min.css') }}">
    <link rel="stylesheet" href="{{ asset('welcomePage/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('welcomePage/css/owl.theme.default.min.css') }}">
    <link rel="stylesheet" href="{{ asset('welcomePage/css/style.css') }}">
    <link rel="apple-touch-icon" href="{{ !empty(setting('site_logo'))?asset('dashboard/uploads/setting/site_logo/' . setting('site_logo') ):asset('dashboard/uploads/setting/site_logo/logo.png') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ !empty(setting('site_logo'))?asset('dashboard/uploads/setting/site_logo/' . setting('site_logo') ):asset('dashboard/uploads/setting/site_logo/logo.png') }}">
    <!-- <link rel="stylesheet" href="css/styleEn.css"> -->
    <title>{{ setting('site_title') }}</title>
    @yield('head')
    <style>
        .error-help-block{
            color: red !important;
        }
        body{
            background-color: {{ $welcomePageSettings['color_background']??'' }};
        }
    </style>
</head>
<body>
<div class="navbar-layer d-lg-none d-block"></div>
<div class="nav-list respon">
    <div class="logodiv respon">
        <a href="{{ url('/') }}">
            <img src="{{ !empty(setting('site_logo'))?asset('dashboard/uploads/setting/site_logo/' . setting('site_logo')) :asset('dashboard/uploads/setting/site_logo/logo.png') }}">
        </a>
    </div>
    <ul class="navBar respon">
        <li><a href="{{ url('/') }}">الرئيسية</a></li>
        <li class="about"><a>من نحن</a></li>
        <li class="how"><a>مميزاتنا</a></li>
        <li class="slider"><a>صفحات التطبيق</a></li>
        @if(auth()->check() && auth()->user()['role_id'] > 0)
            <li class=""><a href="{{ url('/dashboard') }}">عن التطبيق</a></li>
        @endif
    </ul>
</div>
<section id="header" class="header" style="background-color: {{ $welcomePageSettings['color_header']??'' }}">
    <div id="fixedHead" style="background: {{ $welcomePageSettings['color_navbar']??'' }}">
        <div class="container">
            <div class="top-head">
                <div class="logodiv">
                    <a href="{{ url('/') }}">
                        <img src="{{ !empty(setting('site_logo'))?asset('dashboard/uploads/setting/site_logo/' . setting('site_logo')) :asset('dashboard/uploads/setting/site_logo/logo.png') }}">
                    </a>
                </div>
                <div class="bars d-lg-none d-block mb-2 ml-3 mr-3">
                    <span class="bar-1"></span>
                    <span class="bar-2"></span>
                    <span class="bar-3"></span>
                </div>
                <div class="nav-list">
                    <div class="container">
                        <ul class="navBar">
                            <li><a href="{{ url('/') }}">الرئيسية</a></li>
                            <li class="about"><a>من نحن</a></li>
                            <li class="how"><a>مميزاتنا</a></li>
                            <li class="slider"><a>صفحات التطبيق</a></li>
                            @if(auth()->check() && auth()->user()['role_id '] > 0)
                                <li class=""><a href="{{ url('/dashboard') }}">لوحة التحكم</a></li>
                            @endif

                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container d-flex align-items-center bottomH justify-content-between">
        <div class="head-wellcom wow fadeIn">
            <h2>مرحبا بكم في تطبيق {{ setting('site_title')}}</h2>
            <p class="w-75">
                {{ $welcomePageSettings['welcome_msg']??'' }}
            </p>
            <div class="appLink">
                <a href="{{ $welcomePageSettings['google_play']??'' }}" target="'_blank">
                    <img src="{{ asset('welcomePage/images/android.png') }}" alt="">
                </a>
                <a href="{{ $welcomePageSettings['apple_store']??'' }}" target="_blank">
                    <img src="{{ asset('welcomePage/images/apple.png') }}" alt="">
                </a>
            </div>

        </div>
        <div class="headImg wow fadeIn" style="">
            <!-- <img style="z-index: 2;position: absolute;width: 155px!important;" src="{{ !empty(setting('site_logo'))?asset('dashboard/uploads/setting/site_logo/' . setting('site_logo')) :asset('dashboard/uploads/setting/site_logo/logo.png') }}"> -->
            <img class="floatimg" src="{{ asset('assets/uploads/welcomePageSettings') . '/' . $welcomePageSettings['img_welcome_msg']??'' }}">
        </div>
    </div>
</section>
@yield('content')
<footer class="arselfooter" style="background-color: {{ $welcomePageSettings['color_footer']??'' }}">
    <div class="container overflow-hidden">
        <div class="row wow fadeInDown">
            <div class="col-lg-4 col-md-6 col-12">
                <div class="footer-item">
                    <a href="{{ url('/') }}">
                        <img src="{{ !empty(setting('site_logo'))?asset('dashboard/uploads/setting/site_logo/' . setting('site_logo')) :asset('dashboard/uploads/setting/site_logo/logo.png') }}" alt="" width="100" height="100">
                    </a>
                    <p>
                        {!! \Illuminate\Support\Str::limit($welcomePageSettings['about'], 500)??'' !!}
                    </p>

                    <ul class="social p-0">
                        @foreach($socials as $social)
                            <li>
                                <a href="{{ $social['link'] }}" target="_blank">
                                    <img src="{{ asset('dashboard/uploads/socialicon') . '/' . $social['logo'] }}" alt="" width="40px" style="border-radius: 50%">
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-12">
                <div class="footer-item">
                    <h4>روابط سريعة</h4>
                    <ul class="p-0">
                        <li class=""><a href="{{ url('/') }}" aria-current="page">الرئيسية</a></li>
                        <li class=""><a class="about" href="#about">من نحن</a></li>
                        <li class=""><a class="how" href="#how">مميزاتنا</a></li>
                        <li class=""><a class="slider" href="#slider">صفحات التطبيق</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-12">
                <div class="footer-item">
                    <h4>تواصل معنا </h4>
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
    </div>
    <img class="maskfooter" src="{{ asset('welcomePage/images/footermask.png') }}" alt="">
    <div class="footer-bottom overflow-hidden" style="background-color: {{ $welcomePageSettings['color_footer_end']??'' }}">
        <div class="container wow fadeInDown">
            <p><a href="https://aait.sa/" target="_blank" style="color: white">تصميم وبرمجة  - أوامر الشبكة لتقنيه المعلومات</a></p>
{{--            <img src="{{ asset('welcomePage/images/download-252x111.png') }}" alt="">--}}
            <ul>

            </ul>
        </div>
    </div>
</footer>
<script src="{{ asset('welcomePage/js/jquery-3.2.1.min.js') }}"></script>
<script src="{{ asset('welcomePage/js/popper.min.js') }}"></script>
<script src="{{ asset('welcomePage/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('welcomePage/js/wow.min.js') }}"></script>
<script src="{{ asset('welcomePage/js/owl.carousel.min.js') }}"></script>
<script src="{{ asset('welcomePage/js/jquery.fancybox.min.js') }}"></script>
<script src="{{ asset('welcomePage/js/main.js') }}"></script>
<script type="text/javascript">  new WOW().init(); </script>
<script>
    $('.owl-carousel.firstcarousel').owlCarousel({
        loop:true,
        margin:10,
        rtl: true,
        center:true,
        autoplay:true,
        animateOut: 'fadeOut',
        smartSpeed :1000,
        responsive:{
            0:{
                items:2
            },
            600:{
                items:3
            },
            1000:{
                items:5
            }
        }
    })

    $('.owl-carousel.secondcarousel').owlCarousel({
        loop:true,
        margin:10,
        rtl: true,
        center:true,
        autoplay:true,
        animateOut: 'fadeOut',
        smartSpeed :1000,
        responsive:{
            0:{
                items:1
            },
            600:{
                items:2
            },
            1000:{
                items:3
            }
        }
    })
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" integrity="sha512-vKMx8UnXk60zUwyUnUPM3HbQo8QfmNx7+ltw8Pm5zLusl1XIfwcxo8DbWCqMGKaWeNxWA8yrx5v3SaVpMvR3CA==" crossorigin="anonymous" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous"></script>
@if(session()->has('error'))
    <script>
        $(document).ready(function (){
            Swal.fire({
                text: '{{ session()->get('error') }}',
                icon: 'error',
                confirmButtonText: 'حسنا'
            })
        });
    </script>
@endif
@if(session()->has('success'))
    <script>
        $(document).ready(function () {
            Swal.fire({
                text: '{{ session()->get('success') }}',
                icon: 'success',
                confirmButtonText: 'حسنا'
            })
        });
    </script>
@endif
@yield('footer')
</body>
</html>
