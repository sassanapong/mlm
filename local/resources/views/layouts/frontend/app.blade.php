<!DOCTYPE html>
<html lang="th">

<head>
    <title>บริษัท มารวยด้วยกัน จำกัด</title>
    @include('layouts.frontend.inc_header')
    @yield('css')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
</head>

<body>
    @include('layouts.frontend.inc_navbar')
    @yield('conten')
    @include('layouts.frontend.inc_footer')
    <script>
        $('#linkMenuTop .nav-item').eq(0).addClass('active');
    </script>
</body>

@yield('script')
@include('layouts.frontend.flash-message')

</html>
