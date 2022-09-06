<!DOCTYPE html>
<html lang="th">

<head>
    <title>บริษัท มารวยด้วยกัน จำกัด</title>
    {{-- css --}}
    <link rel="stylesheet" href="{{ asset('backend/dist/css/app.css') }}">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.css">

    {{-- font_awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" />
    @yield('head')
    @yield('css')
</head>

<body class="">
    @yield('conten')







    <script src="https://cdn.tailwindcss.com"></script>

    <script src="{{ asset('backend/dist/js/app.js') }}"></script>

    <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.js"></script>
    @yield('script')
</body>




</html>
