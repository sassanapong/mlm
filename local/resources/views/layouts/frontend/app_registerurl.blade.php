<!DOCTYPE html>
<html lang="th">

<head>
    <title>VRich</title>
    @include('layouts.frontend.inc_header')
    @yield('css')

</head>

<body>


    <nav class="navbar navbar-expand-lg navbar-dark bg-Gpurple fixed-top">
        <div class="container-fluid">
            <div class="order-0">
                <a class="navbar-brand" href="{{ route('home') }}">
                    <img src="{{ asset('frontend/images/logo.png') }}" alt="">
                </a>
            </div>
            <div class="order-2 order-md-3 d-inline-flex align-items-center">

            </div>

        </div>
    </nav>

    @yield('conten')
    @include('layouts.frontend.inc_footer')

</body>

@yield('script')
@include('layouts.frontend.flash-message')

</html>
