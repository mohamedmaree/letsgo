<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="{{setting('site_description')}}" />
    <meta name="keywords" content="{{setting('site_tagged')}}" />
    <meta name="author" content="mohamed maree m7mdmaree26@gmail.com" />
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Bootstrap CSS -->
    
    <link rel="shortcut icon" href="{{asset('dashboard/uploads/setting/site_logo/'.setting('site_logo'))}}">
    <link href="{{asset('css/icofont.css')}}" rel="stylesheet" />
    <link rel="stylesheet" href="{{asset('css/font-awesome-5all.css')}}"><!-- font awsome 5.4 -->
    <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
    <link href="{{asset('css/flags.css')}}" rel="stylesheet" />
    <link href="{{asset('css/style.css')}}" rel="stylesheet" />
    <script src="{{asset('js/jquery-3.2.1.min.js')}}"></script>
    <title>{{ setting('site_title') }}</title>

</head>
<body>
    <!-- Start Loader -->
    <div class="img-loads">
        <div class="lds-spinner">
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
        </div>
    </div>
    <!-- End Loader -->
    <!-- Start Header -->
    @yield('header')
    <!-- End Header -->

<!-- Start Content -->
<div class="col-12">
@if (session('successmsg'))
    <div class="alert alert-success" role="alert">
    {{ session('successmsg') }}
    </div>
@elseif(session('msg'))
    <div class="alert alert-danger" role="alert">
    {{ session('msg') }}
    </div>            
@endif 
</div>
@yield('content')

<!-- End Content -->
    <script src="{{asset('js/popper.min.js')}}"></script>
    <script src="{{asset('js/bootstrap.min.js')}}"></script>
    <script src="{{asset('js/jquery.flagstrap.js')}}"></script>
    <script src="{{asset('js/scripts.js')}}"></script>


</body>
</html>
