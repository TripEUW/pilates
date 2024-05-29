<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>


<html lang="es">

<!-- begin::Head -->

<head>
    <meta charset="utf-8" />
    <title>Login</title>
    <meta name="description" content="Login page example">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="{{ url("assets/$theme/demo/default/base/style.bundle.css") }}" rel="stylesheet" type="text/css" />
    <!--begin:: Global Mandatory Vendors -->
    <script src="{{ asset("assets/$theme") }}/vendors/general/jquery/dist/jquery.js" type="text/javascript"></script>
    <script src="{{ asset("assets/$theme") }}/vendors/general/popper.js/dist/umd/popper.js" type="text/javascript"></script>
    <script src="{{ asset("assets/$theme") }}/vendors/general/bootstrap/dist/js/bootstrap.min.js" type="text/javascript">
    </script>
    <script src="{{ asset("assets/$theme") }}/vendors/general/js-cookie/src/js.cookie.js" type="text/javascript"></script>
    <script src="{{ asset("assets/$theme") }}/vendors/general/tooltip.js/dist/umd/tooltip.min.js" type="text/javascript">
    </script>
    <script src="{{ asset("assets/$theme") }}/vendors/general/perfect-scrollbar/dist/perfect-scrollbar.js"
        type="text/javascript"></script>
    <script src="{{ asset("assets/$theme") }}/vendors/general/sticky-js/dist/sticky.min.js" type="text/javascript">
    </script>
    <script src="{{ asset("assets/$theme") }}/vendors/general/wnumb/wNumb.js" type="text/javascript"></script>

    <script src="{{ asset("assets/$theme") }}/vendors/general/jquery-validation/dist/jquery.validate.js"
        type="text/javascript"></script>
    <script src="{{ asset("assets/$theme") }}/vendors/custom/components/vendors/jquery-validation/init.js"
        type="text/javascript"></script>
    <script src="{{ asset("assets/$theme") }}/vendors/general/jquery-validation/dist/localization/messages_es.js"
        type="text/javascript"></script>
    <script src="{{ asset('assets/js') }}/vendors/general/moment/min/moment.min.js" type="text/javascript"></script>

    <!--end:: Global Mandatory Vendors -->
    <!--begin::Fonts -->
    <script src="{{ asset("assets/$theme") }}/vendors/general/webfont/1.6.16/webfont.js"></script>

    <!--end::Fonts -->

    <!--begin::Page Custom Styles(used by this page) -->
    <link href="{{ asset("assets/$theme") }}/app/custom/login/login-v4.default.css" rel="stylesheet" type="text/css" />

    <!--end::Page Custom Styles -->

    <!--begin:: Global Mandatory Vendors -->
    <link href="{{ url("assets/$theme/vendors/general/perfect-scrollbar/css/perfect-scrollbar.css") }}"
        rel="stylesheet" type="text/css" />

    <!--end:: Global Mandatory Vendors -->

    <!--begin:: Global Optional Vendors -->
    <link href="{{ url("assets/$theme/vendors/general/animate.css/animate.css") }}" rel="stylesheet" type="text/css" />
    <link href="{{ url("assets/$theme/vendors/general/toastr/build/toastr.css") }}" rel="stylesheet" type="text/css" />
    <link href="{{ url("assets/$theme/vendors/general/morris.js/morris.css") }}" rel="stylesheet" type="text/css" />
    <link href="{{ url("assets/$theme/vendors/general/sweetalert2/dist/sweetalert2.css") }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ url("assets/$theme/vendors/general/socicon/css/socicon.css") }}" rel="stylesheet" type="text/css" />
    <link href="{{ url("assets/$theme/vendors/custom/vendors/line-awesome/css/line-awesome.css") }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ url("assets/$theme/vendors/custom/vendors/flaticon/flaticon.css") }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ url("assets/$theme/vendors/custom/vendors/flaticon2/flaticon.css") }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ url("assets/$theme/vendors/custom/vendors/fontawesome5/css/all.min.css") }}" rel="stylesheet"
        type="text/css" />
    @yield('styles_optional_vendors')
    <!--end:: Global Optional Vendors -->

    <!--begin::Global Theme Styles(used by all pages) -->

    <!--end::Global Theme Styles -->

    <!--begin::Layout Skins(used by all pages) -->
    <link href="{{ url("assets/$theme/demo/default/skins/header/base/light.css") }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ url("assets/$theme/demo/default/skins/header/menu/light.css") }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ url("assets/$theme/demo/default/skins/aside/dark.css") }}" rel="stylesheet" type="text/css" />
    <link href="{{ url("assets/$theme/demo/default/skins/brand/dark.css") }}" rel="stylesheet" type="text/css" />
    <link href="{{ url("assets/$theme/demo/default/skins/aside/dark.css") }}" rel="stylesheet" type="text/css" />
    <!--end::Layout Skins -->
    <link rel="shortcut icon" href="{{ asset('assets') }}/images/favicon.ico" />

    <!--begin::Custom Global Theme Styles(used by all pages) -->
    <link href="{{ url('assets/css/custom.style.css') }}" rel="stylesheet" type="text/css" />
    <!--begin::Custom Global Theme Styles(used by all pages) -->
</head>

<body
    class="kt-header--fixed kt-header-mobile--fixed kt-subheader--fixed kt-subheader--enabled kt-subheader--solid kt-aside--enabled kt-aside--fixed kt-page--loading">

    @yield('content')
    <script>
        var KTAppOptions = {
            "colors": {
                "state": {
                    "brand": "#5d78ff",
                    "dark": "#282a3c",
                    "light": "#ffffff",
                    "primary": "#5867dd",
                    "success": "#34bfa3",
                    "info": "#36a3f7",
                    "warning": "#ffb822",
                    "danger": "#fd3995"
                },
                "base": {
                    "label": ["#c5cbe3", "#a1a8c3", "#3d4465", "#3e4466"],
                    "shape": ["#f0f3ff", "#d9dffa", "#afb4d4", "#646c9a"]
                }
            }
        };
    </script>

    <!-- end::Global Config -->





    <!--begin::Global Theme Bundle(used by all pages) -->
    <script src="{{ asset("assets/$theme") }}/demo/default/base/scripts.bundle.js" type="text/javascript"></script>

    <!--end::Global Theme Bundle -->

    <!--begin::Page Scripts(used by this page) -->
    <script src="{{ asset('assets') }}/js/login.js" type="text/javascript"></script>

    <!--end::Page Scripts -->

    <!--begin::Global App Bundle(used by all pages) -->
    <script src="{{ asset("assets/$theme") }}/app/bundle/app.bundle.js" type="text/javascript"></script>

    <!--end::Global App Bundle -->
</body>

</html>
