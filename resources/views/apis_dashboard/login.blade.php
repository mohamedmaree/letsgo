<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>تسجيل الدخول</title>

    <!-- Global stylesheets -->

    <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
    <link href="{{asset('dashboard/css/icons/icomoon/styles.css')}}"  rel="stylesheet" type="text/css">
    <link href="{{asset('dashboard/css/bootstrap.css')}}"          rel="stylesheet" type="text/css">
    <link href="{{asset('dashboard/css/core.css')}}"               rel="stylesheet" type="text/css">
    <link href="{{asset('dashboard/css/components.css')}}"         rel="stylesheet" type="text/css">
    <link href="{{asset('dashboard/css/colors.css')}}"             rel="stylesheet" type="text/css">
    <link href="{{asset('dashboard/css/extras/animate.min.css')}}" rel="stylesheet" type="text/css">
    @yield('style')
    <!-- /global stylesheets -->

    <!-- Core JS files -->
    <script type="text/javascript" src="{{asset('dashboard/js/plugins/loaders/pace.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('dashboard/js/core/libraries/jquery.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('dashboard/js/core/libraries/bootstrap.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('dashboard/js/plugins/loaders/blockui.min.js')}}"></script>
    <!-- /core JS files -->

    <!-- Theme JS files -->
    <script type="text/javascript" src="{{asset('dashboard/js/plugins/forms/validation/validate.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('dashboard/js/plugins/forms/styling/uniform.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('dashboard/js/core/app.js')}}"></script>
    <script type="text/javascript" src="{{asset('dashboard/js/pages/login_validation.js')}}"></script>

</head>

<body class="login-cover">
    <!-- Page container -->
    <div class="page-container login-container">
        <!-- Page content -->
        <div class="page-content">
            <!-- Main content -->
            <div class="content-wrapper">
                <!-- Content area -->
                <div class="content">

                    <!-- Form with validation -->
                    <form action="{{route('apisLogin')}}" method="POST" role="form" class="form-validate">
                        {{csrf_field()}}
                        <div class="panel panel-body login-form">
                            <div class="text-center">
                                <div class="icon-object border-slate-300 text-slate-300"><i class="icon-reading"></i></div>
                                <h5 class="content-group"> تسجيل دخول <small class="display-block"> لوحة تحكم APIs </small></h5>
                            </div>

                            <div class="form-group has-feedback has-feedback-left">
                                <input type="text" class="form-control" placeholder="البريد الالكترونى" name="email" required="required">
                                <div class="form-control-feedback">
                                    <i class="icon-user text-muted"></i>
                                </div>
                            </div>

                            <div class="form-group has-feedback has-feedback-left">
                                <input type="password" class="form-control" placeholder="الرقم السرى" name="password" required="required">
                                <div class="form-control-feedback">
                                    <i class="icon-lock2 text-muted"></i>
                                </div>
                            </div>

                            <div class="form-group login-options">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <label class="checkbox-inline">
                                            <input type="checkbox" class="styled" checked="checked">
                                            تذكرنى
                                        </label>
                                    </div>

                                </div>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn bg-blue btn-block">دخول <i class="icon-arrow-left13 position-right"></i></button>
                            </div>

                        </div>
                    </form>
                    <!-- /form with validation -->

                    <!-- Footer -->
                    <div class="footer text-white">
                        <a href="#" class="text-white">جميع الحقوق محفوظه {{setting('site_title')}} &copy; {{date('Y')}}. </a>
                    </div>
                    <!-- /footer -->
                </div>
                <!-- /content area -->
            </div>
            <!-- /main content -->
        </div>
        <!-- /page content -->
    </div>
    <!-- /page container -->
</body>
</html>