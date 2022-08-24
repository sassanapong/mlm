@extends('layouts.frontend.app')
@section('conten')
    <div class="bg-whiteLight page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-6">
                    <div class="card card-box borderR10 mb-2 mb-lg-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-4 col-xxl-3">
                                    <div class="ratio ratio-1x1">
                                        <div class="rounded-circle">
                                            <img src="{{ asset('frontend/images/man.png') }}" class="mw-100"
                                                alt="" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-8 col-xxl-9">
                                    <div class="row">
                                        <div class="col-6">
                                            <span
                                                class="badge rounded-pill bg-success bg-opacity-20 text-success fw-light ps-1">
                                                <i class="fas fa-circle text-success"></i> Active
                                            </span>
                                        </div>
                                        <div class="col-6 text-end">
                                            <a type="button" class="btn btn-warning px-2"
                                                href="{{ route('editprofile') }}"><i class="bx bxs-edit"></i></a>
                                        </div>
                                    </div>


                                    <p class="fs-12 text-secondary">รหัสสมาชิก : MDK1023456789 (ตำแหน่ง)</p>
                                    <h5>ชยพัทธ์ ศรีสดุดี</h5>
                                    <p class="fs-12">
                                        รักษาสภาพสมาชิกมาแล้ว
                                        <span class="badge rounded-pill bg-light text-dark fw-light">
                                            56 วัน
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent">
                            <span class="label-xs">ผู้มอบโอกาสทางธุรกิจ</span>
                            <span class="badge bg-light text-dark fw-light">รหัส MDK000000001 | ณธายุ วงศ์เจริญ</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="row gx-2 gx-md-3">
                        <div class="col-4 col-lg-6">
                            <a href="{{ route('upgradePosition') }}">
                                <div class="card cardL card-body borderR10 bg-warning bg-opacity-20 mb-2 mb-md-3">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0">
                                            <div class="bg-warning borderR8 iconFlex">
                                                <i class='bx bx-slider-alt'></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h5>ทำตำแหน่งสูงขึ้น</h5>
                                            <p class="fs-12 text-warning">การจัดการปรับตำแหน่ง</p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-4 col-lg-6 d-none d-lg-block">
                            <a href="" data-bs-toggle="modal" data-bs-target="#changePassModal">
                                <div class="card cardL card-body borderR10 bg-danger bg-opacity-20 mb-2 mb-md-3">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0">
                                            <div class="bg-danger borderR8 iconFlex">
                                                <i class='bx bx-lock'></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h5>เปลี่ยนรหัสผ่าน</h5>
                                            <p class="fs-12 text-danger">การจัดการเปลี่ยนรหัสผ่าน</p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-4 col-lg-6">
                            <a href="{{ route('Workline') }}">
                                <div class="card cardL card-body borderR10 bg-primary bg-opacity-20 mb-2 mb-md-3">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0">
                                            <div class="bg-primary borderR8 iconFlex">
                                                <i class='bx bx-group'></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h5>สายงาน</h5>
                                            <p class="fs-12 text-primary">การจัดการสายงาน</p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-4 col-lg-6">
                            <a href="{{ route('register') }}">
                                <div class="card cardL card-body borderR10 bg-success bg-opacity-20 mb-2 mb-md-3">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0">
                                            <div class="bg-success borderR8 iconFlex">
                                                <i class='bx bx-plus'></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h5>สมัคร</h5>
                                            <p class="fs-12 text-success">การจัดการเพิ่มสมาชิก</p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-4 col-lg-6 d-block d-lg-none">
                            <a href="order.php">
                                <div class="card cardL card-body borderR10 bg-info bg-opacity-20 mb-2 mb-md-3">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0">
                                            <div class="bg-info borderR8 iconFlex">
                                                <i class='bx bx-cart'></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h5>สั่งซื้อสินค้า</h5>
                                            <p class="fs-12 text-info">การสั่งซื้อสินค้าออนไลน์</p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-4 col-lg-6 d-block d-lg-none">
                            <a href="learning.php">
                                <div class="card cardL card-body borderR10 bg-pink bg-opacity-20 mb-2 mb-md-3">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0">
                                            <div class="bg-pink bg-opacity-100 borderR8 iconFlex">
                                                <i class='bx bx-book-open'></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h5>MDK Learning</h5>
                                            <p class="fs-12 text-pink">การเรียนรู้/CT</p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-4 col-lg-6 d-block d-lg-none">
                            <a href="contact.php">
                                <div class="card cardL card-body borderR10 bg-danger bg-opacity-20 mb-2 mb-md-3">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0">
                                            <div class="bg-danger borderR8 iconFlex">
                                                <i class='bx bx-buildings'></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h5>ติดต่อบริษัท</h5>
                                            <p class="fs-12 text-danger">แจ้งปัญหา/ช่วยเหลือ</p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-xl-3">
                    <div class="dropdown mb-3">
                        <button class="card card-boxDrp" href="#">
                            <div class="d-flex w-100">
                                <div class="flex-shrink-0">
                                    <div class="bg-purple1 bg-opacity-20 borderR8 iconFlex">
                                        <i class='bx bx-box text-purple1 bg-opacity-100'></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3 text-end">
                                    <h4 class="mb-0 text-purple1 bg-opacity-100 fw-bold">100</h4>
                                    <p class="fs-12 text-secondary mb-0">JP. สะสม</p>
                                </div>
                            </div>
                        </button>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3">
                    <div class="dropdown mb-3">
                        <button class="card card-boxDrp dropdown-toggle" href="#" role="button"
                            id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="d-flex w-100 pe-4">
                                <div class="flex-shrink-0">
                                    <div class="bg-purple2 bg-opacity-20 borderR8 iconFlex">
                                        <i class='bx bx-plus text-purple2 bg-opacity-100'></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3 text-start">
                                    <div class="d-flex justify-content-between">
                                        <h5 class="mb-0">JP. ใช้ได้</h5>
                                        <h5 class="text-p1 text-end mb-0 fw-bold">3,500</h5>
                                    </div>
                                    <p class="fs-12 text-secondary mb-0">การจัดการJP</p>
                                </div>
                            </div>
                        </button>

                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                            <li><a class="dropdown-item" href="{{ route('jp_clarify') }}">แจง JP.</a></li>
                            <li><a class="dropdown-item" href="{{ route('jp_transfer') }}">รับ-โอน JP.</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3">
                    <div class="dropdown mb-3">
                        <button class="card card-boxDrp dropdown-toggle" href="#" role="button"
                            id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="d-flex w-100 pe-4">
                                <div class="flex-shrink-0">
                                    <div class="bg-purple2 bg-opacity-20 borderR8 iconFlex">
                                        <i class='bx bxs-wallet text-purple2 bg-opacity-100'></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3 text-start">
                                    <div class="d-flex justify-content-between">
                                        <h5 class="mb-0">eWallet</h5>
                                        <h5 class="text-p1 text-end mb-0 fw-bold">369,200</h5>
                                    </div>
                                    <p class="fs-12 text-secondary mb-0">การจัดการ Wallet</p>
                                </div>
                            </div>
                        </button>

                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                            <li><a class="dropdown-item" type="button" data-bs-toggle="modal"
                                    data-bs-target="#depositModal">ฝากเงิน eWallet</a></li>
                            <li><a class="dropdown-item" type="button" data-bs-toggle="modal"
                                    data-bs-target="#transferModal">โอนเงิน eWallet</a></li>
                            <li><a class="dropdown-item" type="button" data-bs-toggle="modal"
                                    data-bs-target="#withdrawModal">ถอนเงิน eWallet</a></li>
                            <li><a class="dropdown-item" href="{{ route('eWallet_history') }}">ประวัติ eWallet</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3">
                    <div class="dropdown mb-3">
                        <button class="card card-boxDrp dropdown-toggle" href="#" role="button"
                            id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="d-flex w-100 pe-4">
                                <div class="flex-shrink-0">
                                    <div class="bg-purple2 bg-opacity-20 borderR8 iconFlex">
                                        <i class='bx bxs-coin-stack text-purple2 bg-opacity-100'></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3 text-start">
                                    <div class="d-flex justify-content-between">
                                        <h5 class="mb-0">รายได้สะสม</h5>
                                        <h5 class="text-p1 text-end mb-0 fw-bold">205,200</h5>
                                    </div>
                                    <p class="fs-12 text-secondary mb-0">การจัดการโบนัส</p>
                                </div>
                            </div>
                        </button>

                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                            <li><a class="dropdown-item" href="{{ route('bonus_all') }}">โบนัสรวมทั้งหมด</a>
                            </li>
                            <li><a class="dropdown-item" href="{{ route('bonus_fastStart') }}">โบนัส Fast Start</a></li>
                            <li><a class="dropdown-item" href="{{ route('bonus_team') }}">โบนัสบริหารทีม</a></li>
                            <li><a class="dropdown-item" href="{{ route('bonus_discount') }}">โบนัสส่วนลด</a></li>
                            <li><a class="dropdown-item" href="{{ route('bonus_matching') }}">โบนัส Matching</a></li>
                            <li><a class="dropdown-item" href="{{ route('bonus_history') }}">ประวัติการโอนโบนัส</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-box borderR10 mb-2 mb-md-0">
                        <div class="card-body">
                            <h4 class="card-title">ประกาศข่าวสารต่างๆ</h4>
                            <hr>
                            <div class="row">
                                <div class="col-md-6 col-xl-4">
                                    <div class="card cardNewsH mb-3">
                                        <div class="row g-0">
                                            <div class="col-md-4">
                                                <div class="box-imageNews">
                                                    <img src="{{ asset('frontend/images/money-2724241_1920.jpg') }}"
                                                        class="img-fluid rounded-start" alt="">
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="card-body">
                                                    <span
                                                        class="badge rounded-pill bg-purple2 bg-opacity-20 text-p1 fw-light mb-1">
                                                        dd/mm/yyyy
                                                    </span>
                                                    <h5 class="card-title">Lorem ipsum dolor sit amet, consectetur
                                                        adipiscing elit, sed do eiusmod tempor incididunt ut labore et
                                                        dolore magna aliqua. </h5>
                                                    <p class="card-text">This is a wider card with supporting text below as
                                                        a natural lead-in to additional content. This content is a little
                                                        bit longer.</p>
                                                    <a href="{{ route('news_detail') }}"
                                                        class="linkNews stretched-link"><span>อ่านเพิ่มเติม</span><i
                                                            class='bx bxs-right-arrow-circle'></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl-4">
                                    <div class="card cardNewsH mb-3">
                                        <div class="row g-0">
                                            <div class="col-md-4">
                                                <div class="box-imageNews">
                                                    <img src="{{ asset('frontend/images/money-2724241_1920.jpg') }}"
                                                        class="img-fluid rounded-start" alt="">
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="card-body">
                                                    <span
                                                        class="badge rounded-pill bg-purple2 bg-opacity-20 text-p1 fw-light mb-1">
                                                        dd/mm/yyyy
                                                    </span>
                                                    <h5 class="card-title">Lorem ipsum dolor sit amet, consectetur
                                                        adipiscing elit, sed do eiusmod tempor incididunt ut labore et
                                                        dolore magna aliqua. </h5>
                                                    <p class="card-text">This is a wider card with supporting text below as
                                                        a natural lead-in to additional content. This content is a little
                                                        bit longer.</p>
                                                    <a href="{{ route('news_detail') }}"
                                                        class="linkNews stretched-link"><span>อ่านเพิ่มเติม</span><i
                                                            class='bx bxs-right-arrow-circle'></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl-4">
                                    <div class="card cardNewsH mb-3">
                                        <div class="row g-0">
                                            <div class="col-md-4">
                                                <div class="box-imageNews">
                                                    <img src="{{ asset('frontend/images/money-2724241_1920.jpg') }}"
                                                        class="img-fluid rounded-start" alt="">
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="card-body">
                                                    <span
                                                        class="badge rounded-pill bg-purple2 bg-opacity-20 text-p1 fw-light mb-1">
                                                        dd/mm/yyyy
                                                    </span>
                                                    <h5 class="card-title">Lorem ipsum dolor sit amet, consectetur
                                                        adipiscing elit, sed do eiusmod tempor incididunt ut labore et
                                                        dolore magna aliqua. </h5>
                                                    <p class="card-text">This is a wider card with supporting text below as
                                                        a natural lead-in to additional content. This content is a little
                                                        bit longer.</p>
                                                    <a href="{{ route('news_detail') }}"
                                                        class="linkNews stretched-link"><span>อ่านเพิ่มเติม</span><i
                                                            class='bx bxs-right-arrow-circle'></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl-4">
                                    <div class="card cardNewsH mb-3">
                                        <div class="row g-0">
                                            <div class="col-md-4">
                                                <div class="box-imageNews">
                                                    <img src="{{ asset('frontend/images/money-2724241_1920.jpg') }}"
                                                        class="img-fluid rounded-start" alt="">
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="card-body">
                                                    <span
                                                        class="badge rounded-pill bg-purple2 bg-opacity-20 text-p1 fw-light mb-1">
                                                        dd/mm/yyyy
                                                    </span>
                                                    <h5 class="card-title">Lorem ipsum dolor sit amet, consectetur
                                                        adipiscing elit, sed do eiusmod tempor incididunt ut labore et
                                                        dolore magna aliqua. </h5>
                                                    <p class="card-text">This is a wider card with supporting text below as
                                                        a natural lead-in to additional content. This content is a little
                                                        bit longer.</p>
                                                    <a href="{{ route('news_detail') }}"
                                                        class="linkNews stretched-link"><span>อ่านเพิ่มเติม</span><i
                                                            class='bx bxs-right-arrow-circle'></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl-4">
                                    <div class="card cardNewsH mb-3">
                                        <div class="row g-0">
                                            <div class="col-md-4">
                                                <div class="box-imageNews">
                                                    <img src="{{ asset('frontend/images/money-2724241_1920.jpg') }}"
                                                        class="img-fluid rounded-start" alt="">
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="card-body">
                                                    <span
                                                        class="badge rounded-pill bg-purple2 bg-opacity-20 text-p1 fw-light mb-1">
                                                        dd/mm/yyyy
                                                    </span>
                                                    <h5 class="card-title">Lorem ipsum dolor sit amet, consectetur
                                                        adipiscing elit, sed do eiusmod tempor incididunt ut labore et
                                                        dolore magna aliqua. </h5>
                                                    <p class="card-text">This is a wider card with supporting text below as
                                                        a natural lead-in to additional content. This content is a little
                                                        bit longer.</p>
                                                    <a href="{{ route('news_detail') }}"
                                                        class="linkNews stretched-link"><span>อ่านเพิ่มเติม</span><i
                                                            class='bx bxs-right-arrow-circle'></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl-4">
                                    <div class="card cardNewsH mb-3">
                                        <div class="row g-0">
                                            <div class="col-md-4">
                                                <div class="box-imageNews">
                                                    <img src="{{ asset('frontend/images/money-2724241_1920.jpg') }}"
                                                        class="img-fluid rounded-start" alt="">
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="card-body">
                                                    <span
                                                        class="badge rounded-pill bg-purple2 bg-opacity-20 text-p1 fw-light mb-1">
                                                        dd/mm/yyyy
                                                    </span>
                                                    <h5 class="card-title">Lorem ipsum dolor sit amet, consectetur
                                                        adipiscing elit, sed do eiusmod tempor incididunt ut labore et
                                                        dolore magna aliqua. </h5>
                                                    <p class="card-text">This is a wider card with supporting text below as
                                                        a natural lead-in to additional content. This content is a little
                                                        bit longer.</p>
                                                    <a href="{{ route('news_detail') }}"
                                                        class="linkNews stretched-link"><span>อ่านเพิ่มเติม</span><i
                                                            class='bx bxs-right-arrow-circle'></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <nav aria-label="...">
                                        <ul class="pagination justify-content-end">
                                            <li class="page-item disabled">
                                                <a class="page-link">Previous</a>
                                            </li>
                                            <li class="page-item"><a class="page-link" href="#">1</a></li>
                                            <li class="page-item active" aria-current="page">
                                                <a class="page-link" href="#">2</a>
                                            </li>
                                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                                            <li class="page-item">
                                                <a class="page-link" href="#">Next</a>
                                            </li>
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('frontend.modal.modal-deposit')
    @include('frontend.modal.modal-changePassword')
@endsection
