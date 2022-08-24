<nav class="navbar navbar-expand-lg navbar-dark bg-Gpurple fixed-top">
    <div class="container-fluid">
        <div class="order-0">
            <a class="navbar-brand" href="index.php">
                <img src="./images/logo.png" alt="">
            </a>
        </div>
        <div class="order-2 order-md-3 d-inline-flex align-items-center">
            <input type="radio" class="btn-check" name="options" id="option1" autocomplete="off" checked>
            <label class="btn btnFlag" for="option1"><img src="images/thailand.png" class="flagS" alt="" /></label>

            <input type="radio" class="btn-check" name="options" id="option2" autocomplete="off">
            <label class="btn btnFlag" for="option2"><img src="images/united-kingdom.png" class="flagS" alt="" /></label>
            <button class="btn btn-wp" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">HI! <span class="d-inline-flex d-md-none"><i class='bx bxs-user-circle'></i></span><span class="d-none d-md-inline-flex">ตำแหน่ง - ชื่อ
                    สกุล</span></button>
            <!--
            <div class="dropdown">
                <button class="btn btn-wp dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                    HI! <span class="d-inline-flex d-md-none"><i class='bx bxs-user-circle'></i></span><span class="d-none d-md-inline-flex">ตำแหน่ง - ชื่อ สกุล</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-start" id="dropUser" aria-labelledby="dropdownMenuButton1">
                    <li><a class="dropdown-item" href="editprofile.php">แก้ไขข้อมูล</a></li>
                    <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#changePassModal">เปลี่ยนรหัสผ่าน</a></li>
                    <li><a class="dropdown-item" href="login.php"><i class="fas fa-power-off me-1 text-danger"></i>ออกจากระบบ</a></li>
                </ul>
            </div>
-->
        </div>
        <div class="order-3 order-md-2 collapse navbar-collapse" id="linkMenuTop">
            <ul class="navbar-nav me-auto my-2 my-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">หน้าหลัก</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        ซื้อสินค้า
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="order.php">ซื้อสินค้า</a></li>
                        <li><a class="dropdown-item" href="order-history.php">ประวัติการสั่งซื้อ</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="learning.php">MDK. Learning</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="contact.php">ติดต่อบริษัท</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
    <div class="offcanvas-header border-bottom">
        <div class="d-flex">
            <div class="flex-shrink-0">
                <i class='bx bxs-user-circle bx-lg text-p1'></i>
            </div>
            <div class="flex-grow-1 ms-3">
                ตำแหน่ง<br>
                <h5>ชยพัทธ์ ศรีสดุดี</h5>
            </div>
        </div>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="editprofile.php">แก้ไขข้อมูล</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="modal" data-bs-target="#changePassModal">เปลี่ยนรหัสผ่าน</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="login.php"><i class="fas fa-power-off me-1 text-danger"></i>ออกจากระบบ</a>
            </li>
        </ul>
    </div>
</div>
<?php require 'modal-changePassword.php'; ?>