<!DOCTYPE html>
<html
    lang="{{app()->getLocale()}}"
    class="light-style layout-menu-fixed"
    dir="{{in_array(app()->getLocale(),['ar','he',])?'rtl':'ltr'}}"
    data-theme="theme-default"
    data-assets-path="/assets/"
    data-template="horizontal-menu-template"
    data-textdirection="{{in_array(app()->getLocale(),['ar','he',])?'rtl':'ltr'}}">
<head>
    @include('layouts.admin.header')
</head>

<body>
<!-- Layout wrapper -->
<div class="layout-wrapper layout-navbar-full layout-horizontal layout-without-menu">
    <div class="layout-container">
        <div class="preloader" style="display: none">
            <img src="{{ asset('/assets/loader.gif') }}" height="100%" width="100%" alt="">
        </div>
        <!-- Navbar -->
        @include('layouts.admin.navbar')
        <!-- / Navbar -->

        <!-- Layout container -->
        <div class="layout-page">
            <!-- Content wrapper -->
            <div class="content-wrapper">
                <!-- Menu -->
                @include('layouts.admin.sidebar')
                <!-- / Menu -->

                <x-alerts/>

                @yield('content')
                <!--/ Content -->

                <!-- Footer -->
                @include('layouts.admin.footer')
                <!-- / Footer -->

                <div class="content-backdrop fade"></div>
            </div>
            <!--/ Content wrapper -->
        </div>

        <!--/ Layout container -->
    </div>
</div>

<!-- Overlay -->
<div class="layout-overlay layout-menu-toggle"></div>

<!-- Drag Target Area To SlideIn Menu On Small Screens -->
<div class="drag-target"></div>

<!--/ Layout wrapper -->

<!-- Core JS -->
@include('layouts.admin.js')

</body>
</html>
