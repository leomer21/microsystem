@if( (!Session::has('AccountkitFullMobile')) or (Session::has('install_subdomain') and DB::table('customers')->where('database',session('install_subdomain')[0])->count() > 0) )
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title')</title>

    <!-- Global stylesheets -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
    <link href="{{ url('/assets/css/icons/icomoon/styles.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ url('/assets/css/bootstrap.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ url('/assets/css/core.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ url('/assets/css/components.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ url('/assets/css/colors.css') }}" rel="stylesheet" type="text/css">
    <!-- /global stylesheets -->
    <!-- Core JS files -->
    <script type="text/javascript" src="{{ url('/assets/js/plugins/loaders/pace.min.js') }}"></script>
    <script type="text/javascript" src="{{ url('/assets/js/core/libraries/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{ url('/assets/js/core/libraries/bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ url('/assets/js/plugins/loaders/blockui.min.js') }}"></script>
    <!-- /core JS files -->

    <script type="text/javascript" src="assets/js/plugins/forms/styling/uniform.min.js"></script>

    <!-- Theme JS files -->
    <script type="text/javascript" src="{{ url('/assets/js/core/app.js') }}"></script>
    <script type="text/javascript" src="{{ url('assets/js/pages/login_validation.js') }}"></script>
    <!-- /theme JS files XXX -->


</head>
<?php 
  $bg = array("/assets/admin_login_bg/login-1-high-prog.jpg", "/assets/admin_login_bg/login-2-high-prog.jpg", "/assets/admin_login_bg/login-3-high-prog.jpg", "/assets/admin_login_bg/login-4-high-prog.jpg", "/assets/admin_login_bg/login-5-high-prog.jpg", "/assets/admin_login_bg/login-6-high-prog.jpg", "/assets/admin_login_bg/login-8-high-prog.jpg", "/assets/admin_login_bg/login-9-high-prog.jpg"); // array of filenames

  $i = rand(0, count($bg)-1);
  $Images = "$bg[$i]";
?>
<style>
body{
      background:url(<?php echo $Images; ?>);
      background-repeat: no-repeat;
      background-position: center center;
      background-size: cover;
      background-attachment: fixed;
    }
</style>
<script>
    var loginStatus = {!! Auth::check() ? "'logged'" : "'not'" !!};
    var siteUrl = '{{ url("/") }}';
    var token = '{{ csrf_token() }}';
</script>
<body class="login-container">

    <!-- Main navbar -->
    <!--<div class="navbar navbar-inverse">
        <div class="navbar-header">
            <a class="navbar-brand" href="index.html"><img src="assets/images/logo_light.png" alt=""></a>

            <ul class="nav navbar-nav pull-right visible-xs-block">
                <li><a data-toggle="collapse" data-target="#navbar-mobile"><i class="icon-tree5"></i></a></li>
            </ul>
        </div>

        <div class="navbar-collapse collapse" id="navbar-mobile">
            <ul class="nav navbar-nav navbar-right">
                <li>
                    <a href="#">
                        <i class="icon-display4"></i> <span class="visible-xs-inline-block position-right"> Go to website</span>
                    </a>
                </li>

                <li>
                    <a href="#">
                        <i class="icon-user-tie"></i> <span class="visible-xs-inline-block position-right"> Contact admin</span>
                    </a>
                </li>

                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown">
                        <i class="icon-cog3"></i>
                        <span class="visible-xs-inline-block position-right"> Options</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>-->
    <!-- /main navbar -->


    <!-- Page container -->
    <div class="page-container">

        <!-- Page content -->
        <div class="page-content">

            <!-- Main content -->
            <div class="content-wrapper">

                <!-- Content area -->
                <div class="content">

                    @yield('content')

                    @include('..back-end.footer')
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
@endif