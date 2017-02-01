<!DOCTYPE html>
<!--[if IE 9]>         <html class="no-js lt-ie10"> <![endif]-->
<!--[if gt IE 9]><!--> <html class="no-js"> <!--<![endif]-->
<head>
    <meta charset="utf-8">

    <title>@yield('title', 'Dashboard') - Ayamonte</title>

    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="robots" content="noindex, nofollow">

    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1.0">

    @include('admin.partials.styles')
</head>
<body>
<!-- Page Wrapper -->
<!-- In the PHP version you can set the following options from inc/config file -->
<!--
    Available classes:

    'page-loading'      enables page preloader
-->
<div id="page-wrapper" class="page-loading">
    <!-- Preloader -->
    <div class="preloader">
        <div class="inner">
            <!-- Animation spinner for all modern browsers -->
            <div class="preloader-spinner themed-background hidden-lt-ie10"></div>

            <!-- Text for IE9 -->
            <h3 class="text-primary visible-lt-ie10"><strong>Cargando..</strong></h3>
        </div>
    </div>
    <!-- END Preloader -->

    <!-- Page Container -->
    <!-- In the PHP version you can set the following options from inc/config file -->
    <!--
        Available #page-container classes:

        'sidebar-light'                                 for a light main sidebar (You can add it along with any other class)

        'sidebar-visible-lg-mini'                       main sidebar condensed - Mini Navigation (> 991px)
        'sidebar-visible-lg-full'                       main sidebar full - Full Navigation (> 991px)

        'sidebar-alt-visible-lg'                        alternative sidebar visible by default (> 991px) (You can add it along with any other class)

        'header-fixed-top'                              has to be added only if the class 'navbar-fixed-top' was added on header.navbar
        'header-fixed-bottom'                           has to be added only if the class 'navbar-fixed-bottom' was added on header.navbar

        'fixed-width'                                   for a fixed width layout (can only be used with a static header/main sidebar layout)

        'enable-cookies'                                enables cookies for remembering active color theme when changed from the sidebar links (You can add it along with any other class)
    -->
    <div id="page-container" class="header-fixed-top sidebar-visible-lg-full sidebar-light">

        @include('admin.partials.sidebar-alt')


        @include('admin.partials.sidebar')

        <!-- Main Container -->
        <div id="main-container">


            @include('admin.partials.header')

            <!-- Page content -->
            <div id="page-content">

                @include('flash::message')

                @yield('content', '')

            </div>
            <!-- END Page Content -->
        </div>
        <!-- END Main Container -->
    </div>
    <!-- END Page Container -->
</div>
<!-- END Page Wrapper -->

@include('admin.partials.scripts')

</body>
</html>