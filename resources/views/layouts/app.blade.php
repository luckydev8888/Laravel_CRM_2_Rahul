<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <!--begin::Head-->
    <head>
        <base href="">
        <title>Norden Mosse CRM</title>
        <meta name="description" content="Northen Moose" />
        <meta name="keywords" content="Northen Moose, CRM, Customer" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta charset="utf-8" />
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
        <!--end::Fonts-->
        <!--begin::Page Vendor Stylesheets(used by this page)-->
        <link href="assets/plugins/custom/fullcalendar/fullcalendar.bundle.css" rel="stylesheet" type="text/css" />
        <!--end::Page Vendor Stylesheets-->
        <!--begin::Global Stylesheets Bundle(used by all pages)-->
        <link href="assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
        <link rel="dns-prefetch" href="//fonts.gstatic.com">

        <style>
            .container-xxl {
                max-width: none;
                padding: 0px;
            }
        </style>
        @yield('custom_styles')
    </head>
    <!-- Fonts -->

    <body id="kt_body" style="background-image: url(assets/media/logo_wallpaper.jpg); background-size:auto;" class="header-fixed header-tablet-and-mobile-fixed toolbar-enabled aside-enabled">
        <!--begin::Main-->
        <!--begin::Root-->
        <div class="d-flex flex-column flex-root">
            <!--begin::Page-->
            <div class="page d-flex flex-row flex-column-fluid">
                <!--begin::Wrapper-->
                <div class="wrapper d-flex flex-column flex-row-fluid" id="kt_wrapper">
                    <!--begin::Header-->
                    <div id="kt_header" class="header align-items-stretch ps-5 pe-5" data-kt-sticky="true" data-kt-sticky-name="header" data-kt-sticky-offset="{default: '200px', lg: '300px'}">
                        <!--begin::Container-->
                        <div class="container-xxl d-flex align-items-center">
                            <!--begin::Heaeder menu toggle-->
                            <div class="d-flex align-items-center d-lg-none ms-n2 me-3" title="Show aside menu">
                                <div class="btn btn-icon btn-custom w-30px h-30px w-md-40px h-md-40px" id="kt_header_menu_mobile_toggle">
                                    <!--begin::Svg Icon | path: icons/duotune/abstract/abs015.svg-->
                                    <span class="svg-icon svg-icon-2x">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <path d="M21 7H3C2.4 7 2 6.6 2 6V4C2 3.4 2.4 3 3 3H21C21.6 3 22 3.4 22 4V6C22 6.6 21.6 7 21 7Z" fill="black" />
                                            <path opacity="0.3" d="M21 14H3C2.4 14 2 13.6 2 13V11C2 10.4 2.4 10 3 10H21C21.6 10 22 10.4 22 11V13C22 13.6 21.6 14 21 14ZM22 20V18C22 17.4 21.6 17 21 17H3C2.4 17 2 17.4 2 18V20C2 20.6 2.4 21 3 21H21C21.6 21 22 20.6 22 20Z" fill="black" />
                                        </svg>
                                    </span>
                                    <!--end::Svg Icon-->
                                </div>
                            </div>
                            <!--end::Heaeder menu toggle-->
                            <!--begin::Header Logo-->
                            <div class="header-logo me-5 me-md-10 flex-grow-1 flex-lg-grow-0">
                                <a href="/home" style="color: white; font-size: 18px">
                                    <span class="h-15px h-lg-20px logo-default" > Norden Mosse CRM </span>
                                    <span class="h-15px h-lg-20px logo-sticky" > Norden Mosse CRM </span>
                                </a>
                            </div>
                            <!--end::Header Logo-->
                            <!--begin::Wrapper-->
                            <div class="d-flex align-items-stretch justify-content-between flex-lg-grow-1">
                                <!--begin::Navbar-->
                                <div class="d-flex align-items-stretch" id="kt_header_nav">
                                    <!--begin::Menu wrapper-->
                                    <div class="header-menu align-items-stretch" data-kt-drawer="true" data-kt-drawer-name="header-menu" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'200px', '300px': '250px'}" data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_header_menu_mobile_toggle" data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_body', lg: '#kt_header_nav'}">
                                        <!--begin::Menu-->
                                        <div class="menu menu-lg-rounded menu-column menu-lg-row menu-state-bg menu-title-gray-700 menu-state-icon-primary menu-state-bullet-primary menu-arrow-gray-400 fw-bold my-5 my-lg-0 align-items-stretch" id="#kt_header_menu" data-kt-menu="true">
                                            <div class="menu-item {{ (strpos(Route::currentRouteName(), 'home') === 0) ? 'here show' : '' }} me-lg-1">
                                                <span class="menu-link py-3">
                                                    <a class="menu-title" href='{{url('/home')}}'>Home</a>
                                                </span>
                                            </div>
                                            <div class="menu-item {{ (strpos(Route::currentRouteName(), 'contacts') === 0) ? 'here show' : '' }} me-lg-1">
                                                <span class="menu-link py-3">
                                                    <a class="menu-title" href='{{url('/contacts')}}'>Contacts</a>
                                                </span>
                                            </div>
                                            <div class="menu-item {{ (strpos(Route::currentRouteName(), 'pipeline') === 0) ? 'here show' : '' }} me-lg-1">
                                                <span class="menu-link py-3">
                                                    <a class="menu-title" href='{{url('/pipeline')}}'>Pipeline</a>
                                                </span>
                                            </div>
                                            @if (Auth::user()->is_admin)
                                            <div class="menu-item {{ (strpos(Route::currentRouteName(), 'users') === 0) ? 'here show' : '' }} me-lg-1">
                                                <span class="menu-link py-3">
                                                    <a class="menu-title" href='{{url('/users')}}'>Users</a>
                                                </span>
                                            </div>
                                            @endif
                                        </div>
                                        <!--end::Menu-->
                                    </div>
                                    <!--end::Menu wrapper-->
                                </div>
                                <!--end::Navbar-->
                                <!--begin::Topbar-->
                                <!--begin::Topbar-->
                                    <div class="d-flex align-items-stretch flex-shrink-0">
                                        <!--begin::Toolbar wrapper-->
                                        <div class="topbar d-flex align-items-stretch flex-shrink-0">
                                            <!--begin::Quick links-->
                                            @guest
                                                @if (Route::has('login'))
                                                    <div class="d-flex align-items-center ms-1 ms-lg-3">
                                                        <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                                    </div>
                                                @endif

                                                @if (Route::has('register'))
                                                    <div class="d-flex align-items-center ms-1 ms-lg-3">
                                                        <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                                    </div>
                                                @endif
                                            @else
                                                <div class="d-flex align-items-center ms-1 ms-lg-3 text-light">
                                                    {{ Auth::user()->name }}
                                                </div>
                                                <div class="d-flex align-items-center ms-1 ms-lg-10">
                                                    <a class="text-light" href="{{ route('logout') }}"
                                                    onclick="event.preventDefault();
                                                                    document.getElementById('logout-form').submit();">
                                                        {{ __('Logout') }}
                                                    </a>
                                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                                        @csrf
                                                    </form>
                                                </div>
                                            @endguest
                                            <!--end::Quick links-->
                                        </div>
                                        <!--end::Toolbar wrapper-->
                                    </div>
                                <!--end::Topbar-->
                            </div>
                            <!--end::Wrapper-->
                        </div>
                        <!--end::Container-->
                    </div>
                    <!--end::Header-->
                    <!--begin::Toolbar-->
                    <!--end::Toolbar-->
                    <!--begin::Container-->
                    <div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
                        <!--begin::Post-->
                        @yield('content')
                        <!--end::Post-->
                    </div>
                    <!--end::Container-->
                    <!--begin::Footer-->
                    <div class="footer py-4 d-flex flex-lg-column" id="kt_footer">
                        <!--begin::Container-->
                        <div class="container-xxl d-flex flex-column flex-md-row align-items-center justify-content-between">
                            <!--begin::Menu-->
                            <ul class="menu menu-gray-600 menu-hover-primary fw-bold order-1">
                                <li class="menu-item">
                                    <a target="_blank" class="menu-link px-2">Version 2.0</a>
                                </li>
                            </ul>
                            <!--end::Menu-->
                        </div>
                        <!--end::Container-->
                    </div>
                    <!--end::Footer-->
                </div>
                <!--end::Wrapper-->
            </div>
            <!--end::Page-->
        </div>
        <!--end::Root-->
        
        <!--begin::Modal - New Target-->
        <!--begin::Scrolltop-->
        <div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
            <!--begin::Svg Icon | path: icons/duotune/arrows/arr066.svg-->
            <span class="svg-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <rect opacity="0.5" x="13" y="6" width="13" height="2" rx="1" transform="rotate(90 13 6)" fill="black" />
                    <path d="M12.5657 8.56569L16.75 12.75C17.1642 13.1642 17.8358 13.1642 18.25 12.75C18.6642 12.3358 18.6642 11.6642 18.25 11.25L12.7071 5.70711C12.3166 5.31658 11.6834 5.31658 11.2929 5.70711L5.75 11.25C5.33579 11.6642 5.33579 12.3358 5.75 12.75C6.16421 13.1642 6.83579 13.1642 7.25 12.75L11.4343 8.56569C11.7467 8.25327 12.2533 8.25327 12.5657 8.56569Z" fill="black" />
                </svg>
            </span>
            <!--end::Svg Icon-->
        </div>
        <!--end::Scrolltop-->
        <!--end::Main-->
        <script>var hostUrl = "assets/";</script>
        <!--begin::Javascript-->
        <!--begin::Global Javascript Bundle(used by all pages)-->
        <script src="assets/plugins/global/plugins.bundle.js"></script>
        <script src="assets/js/scripts.bundle.js"></script>
        <!--end::Global Javascript Bundle-->
        <!--begin::Page Vendors Javascript(used by this page)-->
        <script src="assets/plugins/custom/fullcalendar/fullcalendar.bundle.js"></script>
        <!--end::Page Vendors Javascript-->
        <!--begin::Page Custom Javascript(used by this page)-->
        <script src="assets/js/custom/widgets.js"></script>
        <!--end::Page Custom Javascript-->
        <!--end::Javascript-->

        @yield('custom_scripts')
    </body>
    <!--end::Body-->
</html>
