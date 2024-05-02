<!DOCTYPE html>
<html lang="th">

<head>
    <title>บริษัท มารวยด้วยกัน จำกัด</title>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet"  crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="{{ asset('frontend/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/animate.css') }}">

    <!--JS-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
 
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.8/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.min.js"
        integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous">
    </script>
    <!--FONT-->
    <link href="https://fonts.googleapis.com/css?family=prompt:200,300,400,500,600" rel="stylesheet">
    <!--ICON-->
    <link rel="stylesheet" href="{{ asset('frontend/fontawesome/all.min.css') }}">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>
    <!--FANCY APP-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"></script>
    <!--Data table-->
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/v/bs5/dt-1.12.1/b-2.2.3/b-colvis-2.2.3/kt-2.7.0/r-2.3.0/sb-1.3.3/sl-1.4.0/datatables.min.css" />
    <script type="text/javascript"
        src="https://cdn.datatables.net/v/bs5/dt-1.12.1/b-2.2.3/b-colvis-2.2.3/kt-2.7.0/r-2.3.0/sb-1.3.3/sl-1.4.0/datatables.min.js">
    </script>
    <!--Owl Carousel-->
    <link rel="stylesheet" href="{{ asset('frontend/owlcarousel/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/owlcarousel/css/owl.theme.default.min.css') }}">
    <script src="{{ asset('frontend/owlcarousel/js/owl.carousel.min.js') }}"></script>

    <!--Animation-->
    <script src="{{ asset('frontend/js/wow.min.js') }}"></script>
    <script>
        new WOW().init();
    </script>


    @yield('css')

</head>

<body class="bg-login-admin" style="background-color: #064e3b;">

    @yield('conten')

    <script>
        $('.bg-login-admin, .container.position-relative').css({
            'min-height': $(window).height()
        });
    </script>
    @yield('js')
</body>

</html>
