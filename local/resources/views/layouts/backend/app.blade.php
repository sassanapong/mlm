<!DOCTYPE html>
<html lang="th">

<head>
    <title>บริษัท มารวยด้วยกัน จำกัด</title>
    @include('layouts.backend.header')
    @yield('head')
    @yield('css')
</head>

<body class="">
    @yield('conten')







    @yield('script')

    <script src="https://cdn.tailwindcss.com"></script>

    <script type="module" src="{{ asset('backend/dist/js/app.js') }}"></script>

    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.js"></script>

</body>




</html>
