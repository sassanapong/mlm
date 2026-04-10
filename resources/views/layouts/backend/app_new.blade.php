<!DOCTYPE html>

<html lang="en" class="light">
    <!-- BEGIN: Head -->
    <head>
        <meta charset="utf-8">
        <link href="{{ asset('frontend/images/fav-icon.png')}}" rel="shortcut icon">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Tinker admin is super flexible, powerful, clean & modern responsive tailwind admin template with unlimited possibilities.">
        <meta name="keywords" content="admin template, Tinker Admin Template, dashboard template, flat admin template, responsive admin template, web app">
        <meta name="author" content="LEFT4CODE">
        <title>บริษัท มารวยด้วยกัน จำกัด </title>
        <link rel="stylesheet" href="{{ asset('backend/dist/css/app.css') }}">
        <link rel="stylesheet" href="{{ asset('backend/dist/css/style.css') }}">
        {{-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.css"> --}}
        {{-- font_awesome --}}
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" />

        <link href='https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css' rel='stylesheet'>
        <link href='https://cdn.datatables.net/buttons/2.3.2/css/buttons.dataTables.min.css' rel='stylesheet'>

        @yield('head')
        @yield('css')
        <!-- END: CSS Assets-->
    </head>
    <!-- END: Head -->
    <body class="py-5 md:py-0 bg-black/[0.15] dark:bg-transparent">
        <!-- BEGIN: Mobile Menu -->
        @include('backend/navbar/navbar_mobile')
        <!-- END: Mobile Menu -->
        <div class="flex overflow-hidden">
            <!-- BEGIN: Side Menu -->
          @include('backend/navbar/navbar')
            <!-- END: Side Menu -->
            <!-- BEGIN: Content -->
            <div class="content">
                <!-- BEGIN: Top Bar -->
                <div class="top-bar -mx-4 px-4 md:mx-0 md:px-0">
                    <!-- BEGIN: Breadcrumb -->
                    @yield('head_text')
                    <!-- END: Breadcrumb -->
                    <!-- BEGIN: Search -->
                    {{-- <div class="intro-x relative mr-3 sm:mr-6">

                        <a class="notification sm:hidden" href=""> <i data-lucide="search" class="notification__icon dark:text-slate-500"></i> </a>

                    </div> --}}
                    <!-- END: Search -->
                    <!-- BEGIN: Notifications -->

                    @include('backend.navbar.top_bar')

                    <!-- END: Account Menu -->
                </div>
                <!-- END: Top Bar -->
                <div class="relative">
                    {{-- <div class="grid grid-cols-12 gap-5">
                    </div> --}}
                    @yield('content')
                </div>

            </div>
            <!-- END: Content -->
        </div>
        <!-- BEGIN: Dark Mode Switcher-->
        {{-- <div data-url="side-menu-dark-dashboard-overview-3.html" class="dark-mode-switcher cursor-pointer shadow-md fixed bottom-0 right-0 box dark:bg-dark-2 border rounded-full w-40 h-12 flex items-center justify-center z-50 mb-10 mr-10">
            <div class="mr-4 text-gray-700 dark:text-gray-300">Dark Mode</div>
            <div class="dark-mode-switcher__toggle border"></div>
        </div> --}}
        <!-- END: Dark Mode Switcher-->

        <!-- BEGIN: JS Assets-->
        {{-- <script src="https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js"></script>
        <script src="https://maps.googleapis.com/maps/api/js?key=["AIzaSyB_slfgl_6UYdxnnC5mXxyGL5651Ln55o8"]&libraries=places"></script> --}}
        <script src="{{ asset('backend/dist/js/app.js') }}"></script>


        {{-- jquery --}}
        {{-- <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
        {{-- datatables --}}
        {{-- <script type="text/javascript" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.js"></script> --}} --}}

        {{-- sweetalert2 --}}
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
        <script>
            $(document).ready(function() {
                $('a').removeClass('side-menu--active');
                $('ul').removeClass('side-menu__sub-open');
                $('a').each(function() {
                    let url = window.location.href;
                    let a = $(this).attr('href');
                    if (a === url) {
                        $(this).addClass('side-menu--active');
                        $(this).parent().parent().addClass('side-menu__sub-open');
                    }
                });
            });
        </script>
        @yield('script')




    </body>
</html>
