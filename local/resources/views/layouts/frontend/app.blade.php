<!DOCTYPE html>
<html lang="th">

<head>
    <title>บริษัท มารวยด้วยกัน จำกัด</title>
    @include('layouts.frontend.inc_header')
    @yield('css')
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <style>
        .agreement {
            max-height: 400px;  /* กำหนดความสูงสูงสุดของกล่อง */
            overflow-y: auto;  /* เพิ่มสกรอลล์แนวตั้งเมื่อเนื้อหายาวเกิน */
            border: 1px solid #ccc; /* ขอบกล่อง */
            padding: 10px;  /* ขอบเขตภายในกล่อง */
            background-color: #f9f9f9;  /* สีพื้นหลัง */
        }
    </style>
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
