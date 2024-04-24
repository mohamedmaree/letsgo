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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet" />
    <link href="{{asset('css/bootstrap-select.min.css')}}" rel="stylesheet" />
    <link href="{{asset('css/paymentstyle.css')}}" rel="stylesheet" />
    <script src="{{asset('js/jquery-1.11.2.min.js')}}"></script>
    <title>{{ setting('site_title') }}</title>

</head>
<body>
    
    <!-- Start Header -->

    <header>
        <div class="logo">
            <img src="{{asset('dashboard/uploads/setting/site_logo/'.setting('site_logo'))}}" />
        </div>
    </header>

    <!-- End Header -->

    <!-- Start Content -->

    <section>
        <div class="content-login">
            <div class="container">
                <h3>شحن الرصيد وتسديد المديونيات في تطبيق {{setting('site_title')}}</h3>
            @if (session('successmsg'))
                <div class="alert alert-success" role="alert">
                {{ session('successmsg') }}
                </div>
            @elseif(session('msg'))
                <div class="alert alert-danger" role="alert">
                {{ session('msg') }}
                </div>            
            @endif          
                @yield('content')

            </div>
        </div>
    </section>

    <!-- End Content -->

    <script src="{{asset('js/bootstrap.min.js')}}"></script>
    <script src="{{asset('js/bootstrap-select.min.js')}}"></script>
    <script src="{{asset('js/scripts.js')}}"></script>

</body>
</html>
