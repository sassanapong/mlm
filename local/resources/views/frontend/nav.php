<!doctype html>
<html lang="th">
<head>      
    <title></title>
    <?php require('inc_header.php'); ?>  
</head>
<body>
    <?php require('inc_navbar.php'); ?>
    <div class="">
        <!-- 
<!-- 
1 - Navbar with brand left, centered links and right aligned links.
    The center links collapse into the mobile menu and rightside links
    show underneath
-->
<nav class="navbar navbar-expand-md navbar-dark bg-primary">
    <div class="d-flex w-50 order-0">
        <a class="navbar-brand mr-1" href="#">Navbar1</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsingNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>
    </div>
    <div class="navbar-collapse collapse justify-content-center order-2" id="collapsingNavbar">
        <ul class="navbar-nav">
            <li class="nav-item active">
                <a class="nav-link" href="#">Link <span class="sr-only">Home</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="//codeply.com">Codeply</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Link</a>
            </li>
        </ul>
    </div>
    <span class="navbar-text small text-truncate mt-1 w-50 text-right order-1 order-md-last">always show</span>
</nav>
<!-- 
2 - Navbar with brand left, links on center and right that collapse in the vertical mobile
    menu
-->
<nav class="navbar navbar-light navbar-expand-md bg-light justify-content-center">
    <a href="/" class="navbar-brand mr-0">Navbar2</a>
    <button class="navbar-toggler ml-1" type="button" data-toggle="collapse" data-target="#collapsingNavbar2">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="navbar-collapse collapse justify-content-between align-items-center w-100" id="collapsingNavbar2">
        <ul class="navbar-nav mx-auto text-center">
            <li class="nav-item active">
                <a class="nav-link" href="#">Link <span class="sr-only">Home</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="//codeply.com">Codeply</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Link</a>
            </li>
        </ul>
        <ul class="nav navbar-nav flex-row justify-content-center flex-nowrap">
            <li class="nav-item"><a class="nav-link" href=""><i class="fa fa-facebook mr-1"></i></a> </li>
            <li class="nav-item"><a class="nav-link" href=""><i class="fa fa-twitter"></i></a> </li>
        </ul>
    </div>
</nav>
<!-- 
3 - Navbar with brand center, links on left and right that all collapse into the vertical mobile
    menu
-->
<nav class="navbar navbar-dark navbar-expand-md bg-success justify-content-between">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target=".dual-nav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="navbar-collapse collapse dual-nav w-50 order-1 order-md-0">
            <ul class="navbar-nav">
                <li class="nav-item active">
                    <a class="nav-link pl-0" href="#">Home <span class="sr-only">Home</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Link</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="//codeply.com">Codeply</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Link</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Link</a>
                </li>
            </ul>
        </div>
        <a href="/" class="navbar-brand mx-auto d-block text-center order-0 order-md-1 w-25">Navbar3</a>
        <div class="navbar-collapse collapse dual-nav w-50 order-2">
            <ul class="nav navbar-nav ml-auto">
                <li class="nav-item"><a class="nav-link" href=""><i class="fa fa-twitter"></i></a></li>
                <li class="nav-item"><a class="nav-link" href=""><i class="fa fa-github"></i></a></li>
            </ul>
        </div>
    </div>
</nav>
<!-- 
4 - Navbar with brand and links left and toggler right
    The links collapse into the mobile menu
-->
<nav class="navbar navbar-expand-md navbar-dark bg-info">
    <a href="/" class="navbar-brand">Navbar4</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar4">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="navbar-collapse collapse" id="navbar4">
        <ul class="navbar-nav">
            <li class="nav-item active">
                <a class="nav-link" href="#">Link <span class="sr-only">Home</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="//codeply.com">Codeply</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Link</a>
            </li>
        </ul>
    </div>
</nav>
<!-- 
5 - Navbar with brand and links left, full width search form and toggler right
    The links collapse into the mobile menu
-->
<nav class="navbar navbar-expand-md navbar-light bg-light">
    <a href="/" class="navbar-brand">Navbar5</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar5">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="navbar-collapse collapse justify-content-stretch" id="navbar5">
        <ul class="navbar-nav">
            <li class="nav-item active">
                <a class="nav-link" href="#">Link <span class="sr-only">Home</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="//codeply.com">Codeply</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Link</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Link</a>
            </li>
        </ul>
        <form class="ml-3 my-auto d-inline w-100">
            <div class="input-group">
                <input type="text" class="form-control border-right-0" placeholder="...">
                <div class="input-group-append">
                    <button class="btn btn-outline-primary border-left-0" type="button">GO</button>
                </div>
            </div>
        </form>
    </div>
</nav>
<!-- 
6 - Navbar with brand left, links left and right, toggler right
    The links collapse into the mobile menu
-->
<nav class="navbar navbar-expand-md navbar-dark bg-danger">
    <a href="/" class="navbar-brand">Navbar6</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar6">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="navbar-collapse collapse justify-content-stretch" id="navbar6">
        <ul class="navbar-nav">
            <li class="nav-item active">
                <a class="nav-link" href="#">Link <span class="sr-only">Home</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Link</a>
            </li>
        </ul>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="#">Link</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="//codeply.com">Codeply</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Link</a>
            </li>
        </ul>
    </div>
</nav>
<!-- 
7 - Navbar with brand left, links right, toggler right
    The links collapse into the mobile menu
-->
<nav class="navbar navbar-expand-md navbar-dark bg-dark">
    <a href="/" class="navbar-brand">Navbar7</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar7">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="navbar-collapse collapse justify-content-stretch" id="navbar7">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="//codeply.com">Codeply</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Link</a>
            </li>
        </ul>
    </div>
</nav>
<!-- 
8 - Navbar with links center and right, toggler right
    The links collapse into the mobile menu
-->
<nav class="navbar navbar-expand-sm navbar-dark bg-primary">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar8">
        <span class="navbar-toggler-icon"></span>
    </button>
    <span class="navbar-text">&nbsp;</span>
    <div class="navbar-collapse collapse" id="navbar8">
        <ul class="navbar-nav abs-center-x">
            <li class="nav-item">
                <a class="nav-link" href="#">Center</a>
            </li>
        </ul>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="#">Link</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="//codeply.com">Codeply</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Link</a>
            </li>
        </ul>
    </div>
</nav>
<!-- 
9 - Navbar in container with links center and right, toggler right
    The links collapse into the mobile menu
-->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a href="/" class="navbar-brand">Navbar9</a>
        <button class="navbar-toggler float-right" type="button" data-toggle="collapse" data-target="#navbar9">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="navbar-collapse collapse" id="navbar9">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="#">Link</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="//codeply.com">Codeply</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Link</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<!-- 
10 - Navbar justified centered links full width
-->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar10">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="navbar-collapse collapse" id="navbar10">
            <ul class="navbar-nav nav-fill w-100">
                <li class="nav-item">
                    <a class="nav-link" href="#">Link</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="//codeply.com">Codeply</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Link</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Link</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Link</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<!-- 11 - contained in center example -->
<nav class="navbar navbar-expand-sm navbar-light bg-light" data-toggle="affix">
    <div class="mx-auto d-sm-flex d-block flex-sm-nowrap">
        <a class="navbar-brand" href="#">Navbar11</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExample11" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse text-center" id="navbarsExample11">
            <ul class="navbar-nav">
                <li class="nav-item active">
                    <a class="nav-link" href="#">Link</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Link</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Link</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Link</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Link</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<!-- 11 - contained in center example -->
<nav class="navbar navbar-expand-sm bg-dark navbar-dark justify-content-end">
    <a class="navbar-brand" href="#">Navbar12</a>
    <button class="btn btn-success ml-auto mr-2">Always Show</button>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse flex-grow-0" id="navbarSupportedContent">
        <ul class="navbar-nav text-right">
            <li class="nav-item active">
                <a class="nav-link" href="#">Right Link</a>
            </li>
            <li class="nav-item active">
                <a class="nav-link" href="#">Right Link</a>
            </li>
        </ul>
    </div>
</nav>

<!-- 
12 - Navbar left right text and icons
-->
<nav class="navbar navbar-dark navbar-expand bg-primary justify-content-between">
    <div class="container-fluid">
        <ul class="navbar-nav">
            <li class="nav-item text-center">
                <a href="#" class="nav-link" data-toggle="modal" data-target="#"><span class="fa fa-map"></span><span class="d-none d-sm-inline d-xl-block px-1">Map</span></a>
            </li>
            <li class="nav-item dropdown text-center">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="fa fa-list"></span><span class="d-xl-block d-none"></span><span class="d-none d-sm-inline px-1">Dropdown</span> </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="#">Action</a>
                    <a class="dropdown-item" href="#">Another action</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#">Something else here</a>
                </div>
            </li>
        </ul>
        <ul class="nav navbar-nav">
            <li class="nav-item text-center" id="signup-btn">
                <a href="#" class="nav-link" data-toggle="modal" data-target="#"><span class="fa fa-user"></span><span class="d-none d-sm-inline d-xl-block px-1">Sign Up</span></a>
            </li>
            <li class="nav-item text-center" id="login-btn">
                <a href="#" class="nav-link" data-toggle="modal" data-target="#"><span class="fa fa-sign-in"></span><span class="d-none d-sm-inline d-xl-block px-1">Log In</span></a>
            </li>
        </ul>
    </div>
</nav>
<!-- example 1 - using absolute position for center -->
<nav class="navbar navbar-expand-md navbar-dark bg-danger">
    <a class="navbar-brand abs" href="#">Navbar14</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsingNavbar">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="navbar-collapse collapse" id="collapsingNavbar">
        <ul class="navbar-nav">
            <li class="nav-item active">
                <a class="nav-link" href="#">Link</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="//codeply.com">Codeply</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#myAlert" data-toggle="collapse">Link</a>
            </li>
        </ul>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="" data-target="#myModal" data-toggle="modal">About</a>
            </li>
        </ul>
    </div>
</nav>

<!-- example 2 - using auto margins -->
<nav class="navbar navbar-expand-md navbar-dark bg-dark">
    <div class="navbar-collapse collapse w-100 order-1 order-md-0 dual-collapse2">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link" href="#">Left</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="//codeply.com">Codeply</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Link</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Link</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Link</a>
            </li>
        </ul>
    </div>
    <div class="mx-auto order-0">
        <a class="navbar-brand mx-auto" href="#">Navbar15</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target=".dual-collapse2">
            <span class="navbar-toggler-icon"></span>
        </button>
    </div>
    <div class="navbar-collapse collapse w-100 order-3 dual-collapse2">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="#">Right</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Link</a>
            </li>
        </ul>
    </div>
</nav>

<!-- 
example 3 - Navbar with brand left, links on center and right that collapse into the vert mobile menu
-->
<nav class="navbar navbar-light navbar-expand-md bg-faded justify-content-center">
    <a href="/" class="navbar-brand d-flex w-50 mr-auto">Navbar16</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsingNavbar3">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="navbar-collapse collapse w-100" id="collapsingNavbar3">
        <ul class="navbar-nav w-100 justify-content-center">
            <li class="nav-item active">
                <a class="nav-link" href="#">Link</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="//codeply.com">Codeply</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Link</a>
            </li>
        </ul>
        <ul class="nav navbar-nav ml-auto w-100 justify-content-end">
            <li class="nav-item">
                <a class="nav-link" href="#">Right</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Right</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Right</a>
            </li>
        </ul>
    </div>
</nav>

<!-- 4 - contained in center example -->
<nav class="navbar navbar-expand-sm navbar-light bg-info">
    <div class="mx-auto d-sm-flex d-block flex-sm-nowrap">
        <a class="navbar-brand" href="#">Navbar17</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExample11" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse text-center" id="navbarsExample11">
            <ul class="navbar-nav">
                <li class="nav-item active">
                    <a class="nav-link" href="#">Link</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Link</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Link</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Link</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Link</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- example 5 left and center only with empty space right -->
<nav class="navbar navbar-expand-sm navbar-dark bg-primary flex-nowrap">
    <button class="navbar-toggler mr-2" type="button" data-toggle="collapse" data-target="#navbar5">
        <span class="navbar-toggler-icon"></span>
    </button>
    <span class="navbar-brand w-100">Navbar18</span>
    <div class="navbar-collapse collapse w-100 justify-content-center" id="navbar5">
        <ul class="navbar-nav mx-auto">
            <li class="nav-item">
                <a class="nav-link" href="#">Link</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Codeply</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Link</a>
            </li>
        </ul>
    </div>
    <div class="w-100"><!--spacer--></div>
</nav>


<!-- example 6 - center on mobile -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="d-flex flex-grow-1">
        <span class="w-100 d-lg-none d-block"><!-- hidden spacer to center brand on mobile --></span>
        <a class="navbar-brand d-none d-lg-inline-block" href="#">
            Navbar19
        </a>
        <a class="navbar-brand-two mx-auto d-lg-none d-inline-block" href="#">
            <img src="//placehold.it/40?text=LOGO" alt="logo">
        </a>
        <div class="w-100 text-right">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#myNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </div>
    <div class="collapse navbar-collapse flex-grow-1 text-right" id="myNavbar">
        <ul class="navbar-nav ml-auto flex-nowrap">
            <li class="nav-item">
                <a href="#" class="nav-link m-2 menu-item nav-active">Our Solution</a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link m-2 menu-item">How We Help</a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link m-2 menu-item">Blog</a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link m-2 menu-item">Contact</a>
            </li>
        </ul>
    </div>
</nav>

<!-- example 7 - center on mobile 2-->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="d-flex flex-grow-1">
        <span class="w-100 d-lg-none d-block"><!-- hidden spacer to center brand on mobile --></span>
        <a class="navbar-brand" href="#">
            Navbar20
        </a>
        <div class="w-100 text-right">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#myNavbar7">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </div>
    <div class="collapse navbar-collapse flex-grow-1 text-right" id="myNavbar7">
        <ul class="navbar-nav ml-auto flex-nowrap">
            <li class="nav-item">
                <a href="#" class="nav-link">Link</a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">Link</a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">Link</a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">Link</a>
            </li>
        </ul>
    </div>
</nav>
<nav class="navbar navbar-light navbar-expand-lg navbar-template">
    <a class="navbar-brand" href="//codeply.com">Navbar21</a>
    <div class="d-flex flex-row order-2 order-lg-3">
        <ul class="navbar-nav flex-row">
            <li class="nav-item"><a class="nav-link px-2" href="#"><span class="fab fa-facebook"></span></a></li>
            <li class="nav-item"><a class="nav-link px-2" href="#"><span class="fab fa-twitter"></span></a></li>
            <li class="nav-item"><a class="nav-link px-2" href="#"><span class="fab fa-youtube"></span></a></li>
            <li class="nav-item"><a class="nav-link px-2" href="#"><span class="fab fa-linkedin"></span></a></li>
        </ul>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown">
            <span class="navbar-toggler-icon"></span>
        </button>
    </div>
    <div class="collapse navbar-collapse order-3 order-lg-2" id="navbarNavDropdown">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item"><a class="nav-link" href="#">Home</a></li>
            <li class="nav-item"><a class="nav-link" href="#">Features</a></li>
            <li class="nav-item"><a class="nav-link" href="#">Pricing</a></li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="http://example.com" id="navbarDropdownMenuLink" data-toggle="dropdown">Dropdown link</a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="#">Action</a>
                    <a class="dropdown-item" href="#">Another action</a>
                    <a class="dropdown-item" href="#">Something else here</a>
                </div>
            </li>
        </ul>
    </div>
</nav>
<nav class="navbar navbar-expand-md navbar-dark bg-dark">
    <div class="container">
        <h1 class="pb-2 ml-md-0 ml-sm-3 ml-0 mr-3"><a href="#"><i class="far fa-envelope fa-lg mt-3" aria-hidden="true">22</i></a></h1>
        <button class="navbar-toggler my-2" type="button" data-toggle="collapse" data-target="#navbarCollapse">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse flex-column align-items-start ml-lg-2 ml-0" id="navbarCollapse">
            <ul class="navbar-nav">
                <li class="nav-item active">
                    <a class="nav-link" href="#">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Product</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Shop</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Events</a>
                </li>
            </ul>
            <ul class="navbar-nav flex-row mb-md-1 mt-md-0 mb-3 mt-2">
                <li class="nav-item">
                    <a class="nav-link py-0 pr-3" href="#"><i class="fab fa-facebook"></i></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link py-0 pr-3" href="#"><i class="fab fa-instagram"></i></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link py-0 pr-3" href="#"><i class="fab fa-twitter"></i></a>
                </li>
            </ul>
        </div>
    </div>
</nav>  
<nav class="navbar navbar-expand-lg navbar-dark bg-success" role="navigation">
    <div class="container">
        <a class="navbar-brand" href="#">Navbar23</a>
        <button class="navbar-toggler border-0" type="button" data-toggle="collapse" data-target="#exCollapsingNavbar">
            &#9776;
        </button>
        <div class="collapse navbar-collapse" id="exCollapsingNavbar">
            <ul class="nav navbar-nav">
                <li class="nav-item"><a href="#" class="nav-link">About</a></li>
                <li class="nav-item"><a href="#" class="nav-link">Link</a></li>
                <li class="nav-item"><a href="#" class="nav-link">Service</a></li>
                <li class="nav-item"><a href="#" class="nav-link">More</a></li>
            </ul>
            <ul class="nav navbar-nav flex-row justify-content-between ml-auto">
                <li class="nav-item order-2 order-md-1"><a href="#" class="nav-link" title="settings"><i class="fa fa-cog fa-fw fa-lg"></i></a></li>
                <li class="dropdown order-1">
                    <button type="button" id="dropdownMenu1" data-toggle="dropdown" class="btn btn-outline-light dropdown-toggle">Login <span class="caret"></span></button>
                    <ul class="dropdown-menu dropdown-menu-right mt-2">
                       <li class="px-3 py-2">
                           <form class="form" role="form">
                                <div class="form-group">
                                    <input id="emailInput" placeholder="Email" class="form-control form-control-sm" type="text" required="">
                                </div>
                                <div class="form-group">
                                    <input id="passwordInput" placeholder="Password" class="form-control form-control-sm" type="text" required="">
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary btn-block">Login</button>
                                </div>
                                <div class="form-group text-center">
                                    <small><a href="#" data-toggle="modal" data-target="#modalPassword">Forgot password?</a></small>
                                </div>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div id="modalPassword" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Forgot password</h3>
                <button type="button" class="close font-weight-light" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <p>Reset your password..</p>
            </div>
            <div class="modal-footer">
                <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
                <button class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>
<nav class="navbar navbar-expand-md navbar-dark bg-dark">
    <div class="container">
        <h1 class="mb-0"><a href="#">Navbar24</a></h1>
        <button class="navbar-toggler mt-1" type="button" data-toggle="collapse" data-target="#navbarCollapse">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse flex-column align-items-start ml-lg-2 ml-0" id="navbarCollapse">
            <span class="navbar-text ml-auto py-0 px-lg-2">(00) 1234 5678</span>
            <ul class="navbar-nav mb-auto mt-0 ml-auto">
                <li class="nav-item active">
                    <a class="nav-link py-0" href="#">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link py-0" href="#">Product</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link py-0" href="#">Company</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link py-0" href="#">Blog</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">

  <a class="navbar-brand" href="#">Navbar25</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#main_nav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="main_nav">

<ul class="navbar-nav">
	<li class="nav-item active"> <a class="nav-link" href="#">Home </a> </li>
	<li class="nav-item"><a class="nav-link" href="#"> About </a></li>
	<li class="nav-item"><a class="nav-link" href="#"> Services </a></li>
	<li class="nav-item dropdown has-megamenu">
		<a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown"> Mega menu  </a>
	    <div class="dropdown-menu megamenu" role="menu">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="col-megamenu">
                            	<h6 class="title">Title Menu One</h6>
	                            <ul class="list-unstyled">
	                                <li><a href="#">Custom Menu</a></li>
	                                <li><a href="#">Custom Menu</a></li>
	                                <li><a href="#">Custom Menu</a></li>
	                                <li><a href="#">Custom Menu</a></li>
	                                <li><a href="#">Custom Menu</a></li>
	                                <li><a href="#">Custom Menu</a></li>
	                            </ul>
                            </div>  <!-- col-megamenu.// -->
                        </div><!-- end col-3 -->
                        <div class="col-md-3">
                        	<div class="col-megamenu">
                            <h6 class="title">Title Menu Two</h6>
                                <ul class="list-unstyled">
                                    <li><a href="#">Custom Menu</a></li>
                                    <li><a href="#">Custom Menu</a></li>
                                    <li><a href="#">Custom Menu</a></li>
                                    <li><a href="#">Custom Menu</a></li>
                                    <li><a href="#">Custom Menu</a></li>
                                    <li><a href="#">Custom Menu</a></li>
                                </ul>
                            </div>  <!-- col-megamenu.// -->
                        </div><!-- end col-3 -->
                        <div class="col-md-3">
                        	<div class="col-megamenu">
                            <h6 class="title">Title Menu Three</h6>
                                <ul class="list-unstyled">
                                    <li><a href="#">Custom Menu</a></li>
                                    <li><a href="#">Custom Menu</a></li>
                                    <li><a href="#">Custom Menu</a></li>
                                    <li><a href="#">Custom Menu</a></li>
                                    <li><a href="#">Custom Menu</a></li>
                                    <li><a href="#">Custom Menu</a></li>
                                </ul>
                            </div>  <!-- col-megamenu.// -->
                        </div>    
                        <div class="col-md-3">
                        	<div class="col-megamenu">
                            <h6 class="title">Title Menu Four</h6>
                                <ul class="list-unstyled">
                                    <li><a href="#">Custom Menu</a></li>
                                    <li><a href="#">Custom Menu</a></li>
                                    <li><a href="#">Custom Menu</a></li>
                                    <li><a href="#">Custom Menu</a></li>
                                    <li><a href="#">Custom Menu</a></li>
                                    <li><a href="#">Custom Menu</a></li>
                                </ul>
                            </div>  <!-- col-megamenu.// -->
                        </div><!-- end col-3 -->
                    </div><!-- end row --> 
        </div> <!-- dropdown-mega-menu.// -->
	</li>
</ul>


<ul class="navbar-nav ml-auto">
    <li class="nav-item"><a class="nav-link" href="#"> Menu item </a></li>
    <li class="nav-item dropdown">
        <a class="nav-link  dropdown-toggle" href="#" data-toggle="dropdown"> Dropdown </a>
        <ul class="dropdown-menu dropdown-menu-right">
          <li><a class="dropdown-item" href="#"> Submenu item 1</a></li>
          <li><a class="dropdown-item" href="#"> Submenu item 2 </a></li>
        </ul>
    </li>

</ul>

  </div> <!-- navbar-collapse.// -->

</nav>
<style>
	@media all and (min-width: 992px) {
		.navbar{ padding-top: 0; padding-bottom: 0; }
		.navbar .has-megamenu{position:static!important;}
		.navbar .megamenu{left:0; right:0; width:100%; padding:20px;  }
		.navbar .nav-link{ padding-top:1rem; padding-bottom:1rem;  }
	}
</style>
<nav class="mainNav navbar navbar-expand-md navbar-light flex-column ">
    <div class="w-100 d-flex">
        <a class="navbar-brand mx-md-auto" href="#">
            <img src="//placehold.it/80" alt="Logo" height="60" width="auto">
        </a>
        <button class="navbar-toggler align-self-center ml-auto" type="button" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="navbar-toggler-icon"></span>
        </button>
    </div>
    <div class="collapse navbar-collapse">
        <ul class="navbar-nav text-center">
            <li class="nav-item">
                <a class="nav-link" href="#">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">About</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Contact</a>
            </li>
        </ul>
    </div>
</nav>
<nav class="navbar navbar-light navbar-expand-xl border-bottom mainmenu sticky-top justify-content-start bg-light">
    <a class="navbar-brand text-capitalize text-blur" href="/">
        <img class="mr-1" src="//placehold.it/32" alt="Logo">
        <span class="">Navbar27</span>
    </a>
    <button class="navbar-toggler order-2 ml-1" type="button" data-toggle="collapse" data-target=".collapsable" aria-controls="collapsableTopMen" aria-expanded="false" aria-label="[Menu]">
        <span class="sr-only">[Menu]</span>
        <span class="navbar-toggler-icon" title="[Menu]"></span>
    </button>
    <div class="collapse navbar-collapse collapsable flex-grow-0 flex-xl-grow-1 order-last" id="collapsableTopMen">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link" href="/Schools/">
                    <i class="fa fa-motorcycle fa-rotate-315 text-danger" aria-hidden="true"></i>
                    <span class="text-blur-danger">PiP Reparto Corse</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/Tracks/">
                    <i class="fa fa-flag-checkered text-primary" aria-hidden="true"></i>
                    <span>Piste</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/Blog/le-guide-del-giovedi/">
                    <i class="fa fa-mortar-board text-primary" aria-hidden="true"></i>
                    <span>Le guide del gioved&#236;</span>
                </a>
            </li>
        </ul>
    </div>
    <ul class="navbar-nav order-1 order-xl-last ml-auto">
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownLang" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-flag it" title="Italiano"></i>
                <span class="d-inline d-xl-none">Italiano</span>
            </a>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownLang">
                <a class="dropdown-item" href="#">
                    <i class="fas fa-flag-usa gb" aria-hidden="true"></i>
                    <span class="">English</span>
                </a>
            </div>
        </li>
    </ul>
    <div class="collapse navbar-collapse collapsable flex-grow-0 order-last">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="/Account/Login"><i class="fa fa-sign-in"></i> Log in</a>
            </li>
        </ul>
    </div>
</nav>        
<nav class="navbar navbar-expand-sm navbar-light bg-warning">
    <a class="navbar-brand flex-grow-1" href="#">Navbar28</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar1">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse flex-shrink-1"  id="navbar1">
        <ul class="navbar-nav">
            <li class="nav-item active">
                <a class="nav-link" href="#">Link1</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Link2</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Link3</a>
            </li>
        </ul>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item active">
                <a class="nav-link" href="#">mini link1</a>
            </li>
            <li class="nav-item active">
                <a class="nav-link" href="#">mini link2</a>
            </li>
        </ul>
    </div>
</nav>        

    </div>
    <?php require('inc_footer.php'); ?> 
</body>
</html>    