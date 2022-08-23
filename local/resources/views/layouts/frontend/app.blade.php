<!DOCTYPE html>
<html lang="th">
<head>
    <title>บริษัท มารวยด้วยกัน จำกัด</title>
    @include('layouts.frontend.inc_header')
    @yield('css')
</head>
<body>
   @include('layouts.frontend.inc_navbar')
   @yield('conten')
   @include('layouts.frontend.inc_footer')
   @include('layouts.frontend.modal-deposit')
   @include('layouts.frontend.modal-transfer')
   @include('layouts.frontend.modal-withdraw')
    <script>
        $('#linkMenuTop .nav-item').eq(0).addClass('active');
    </script>
</body>

</html>

