<!DOCTYPE html>
<!--[if IE 9]>         <html class="no-js lt-ie10"> <![endif]-->
<!--[if gt IE 9]><!--> <html class="no-js"> <!--<![endif]-->
<head>
    <meta charset="utf-8">

    <title>@yield('title', 'Iniciar sesión - Ayamonte')</title>

    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="robots" content="noindex, nofollow">

    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1.0">

    @include('admin.partials.styles')
</head>
<body class="login-page">

    <!-- Login Container -->
    <div id="login-container">
        <!-- Login Header -->
        <h1 class="h2 text-light text-center push-top-bottom animation-slideDown login-title">
            {{ HTML::image('assets/admin/img/logo.jpg') }}
        </h1>
        <!-- END Login Header -->


        <div class="col-md-12 login-flash">
            @include('flash::message')
        </div>

        @yield('content')


        @include('admin.modules.sessions.footer')

    </div>

    @include('admin.partials.scripts')

</body>
</html>