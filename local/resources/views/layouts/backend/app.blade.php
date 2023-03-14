<!DOCTYPE html>
<html lang="th">

<head>
    <title>บริษัท มารวยด้วยกัน จำกัด</title>
    {{-- css --}}
    <link rel="stylesheet" href="{{ asset('backend/dist/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/dist/css/style.css') }}">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.css">

    {{-- font_awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" />

    @yield('head')
    @yield('css')
</head>

<body class="">
    @yield('conten')






    {{-- css tailwindcss ถ้าไม่ใส่จะใช้ css tailwindcss ได้ไม่ครบทุกอัน ได้แค่ของ template --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- ่js template --}}
    <script src="{{ asset('backend/dist/js/app.js') }}"></script>

    {{-- jquery --}}
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
    {{-- datatables --}}
    <script type="text/javascript" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.js"></script>

    {{-- sweetalert2 --}}
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            $('a').removeClass('side-menu--active')
            $('ul').removeClass('side-menu__sub-open')
            $('a').each(function() {
                let url = window.location.href;
                let a = $(this).attr('href')
                if (a == url) {
                    $(this).addClass('side-menu--active')
                    $(this).parent().parent().addClass('side-menu__sub-open')
                }
            });

        });
    </script>

    @yield('script')
</body>




</html>
