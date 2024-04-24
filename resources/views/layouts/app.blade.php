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

    <script src="{{asset('js/jquery-1.11.2.min.js')}}"></script>
    <title>{{ setting('site_title') }}</title>
            <style>
                #map {
                    height: 100%;
                }
                html, body {
                    height: 100%;
                    margin: 0;
                    padding: 0;
                }
            </style>
</head>
<body>



                @yield('content')



</body>
</html>
