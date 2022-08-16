 <!DOCTYPE html>
<html lang="th">
<head>      
    <title>บริษัท มารวยด้วยกัน จำกัด</title>
    <?php require('inc_header.php'); ?>  
</head>
<body>
    <?php require('inc_navbar.php'); ?>
    <div class="bg-whiteLight page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <nav aria-label="breadcrumb">
                      <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php">หน้าแรก</a></li>
                        <li class="breadcrumb-item active" aria-current="page">แก้ไขข้อมูลส่วนตัว</li>
                      </ol>
                    </nav>
                </div>    
            </div>
            <div class="row">
                <div class="col-md-12">
                    <form>
                    <div class="card card-box borderR10 mb-2 mb-md-0">
                        <div class="card-body">
                            <h4 class="card-title">แก้ไขข้อมูล</h4>
                            <hr>
                            <div class="borderR10 py-2 px-3 bg-purple3 bg-opacity-50 h5 mb-3">ผู้แนะนำ</div>
                            <div class="row g-3">
                                <div class="col-md-6 col-lg-4 col-xxl-3">
                                    <label for="" class="form-label">รหัสผู้แนะนำ <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="">
                                </div>
                                <div class="col-md-6 col-lg-2 col-xxl-1">
                                    <label for="" class="form-label d-none d-md-block">&nbsp;</label>
                                    <button class="btn btn-p1 rounded-pill">ตรวจ</button>
                                    <button class="btn btn-outline-dark rounded-circle btn-icon"><i class="bx bx-x"></i></button>
                                </div>
                                <div class="col-md-6 col-lg-6 col-xxl-8 mb-3">
                                    <label for="" class="form-label">ชื่อผู้แนะนำ <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="" disabled>
                                </div>
                            </div>
                            <div class="borderR10 py-2 px-3 bg-purple3 bg-opacity-50 h5 mb-3">ข้อมูลส่วนตัว</div>
                            <div class="row g-3">
                                <div class="col-md-6 col-xl-3">
                                    <label for="" class="form-label">คำนำหน้า <span class="text-danger">*</span></label>
                                    <select class="form-select" id="">
                                        <option>เลือกคำนำหน้า</option>
                                        <option>นาย</option>
                                        <option>นาง</option>
                                        <option>นางสาว</option>
                                    </select>
                                </div>
                                <div class="col-md-6 col-xl-6">
                                    <label for="" class="form-label">ชื่อ - นามสกุล <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="">
                                </div>
                                <div class="col-md-6 col-xl-3">
                                    <label for="" class="form-label">เพศ <span class="text-danger">*</span></label>
                                    <select class="form-select" id="">
                                        <option>เลือกเพศ</option>
                                        <option>ชาย</option>
                                        <option>หญิง</option>
                                        <option>ไม่ระบุ</option>
                                    </select>
                                </div>
                                <div class="col-md-6 col-xl-6">
                                    <label for="" class="form-label">ชื่อทางธุรกิจ <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="">
                                </div>
                                <div class="col-md-6 col-xl-2">
                                    <label for="" class="form-label">วันเกิด <span class="text-danger">*</span></label>
                                    <select class="form-select" id="">
                                        <option>วัน</option>
                                        <option></option>
                                        <option></option>
                                        <option></option>
                                    </select>
                                </div>
                                <div class="col-md-6 col-xl-2">
                                    <label for="" class="form-label d-none d-md-block">&nbsp;</label>
                                    <select class="form-select" id="">
                                        <option>เดือน</option>
                                        <option>มกราคม</option>
                                        <option>กุมภาพันธ์</option>
                                        <option>มีนาคม</option>
                                        <option>เมษายน</option>
                                        <option>พฤษภาคม</option>
                                        <option>มิถุนายน</option>
                                        <option>กรกฎาคม</option>
                                        <option>สิงหาคม</option>
                                        <option>กันยายน</option>
                                        <option>ตุลาคม</option>
                                        <option>พฤศจิกายน</option>
                                        <option>ธันวาคม</option>
                                    </select>
                                </div>
                                <div class="col-md-6 col-xl-2">
                                    <label for="" class="form-label d-none d-md-block">&nbsp;</label>
                                    <select class="form-select" id="">
                                        <option>ปี</option>
                                        <option></option>
                                        <option></option>
                                        <option></option>
                                    </select>
                                </div>
                                <div class="col-md-6 col-xl-2">
                                    <label for="" class="form-label">สัญชาติ <span class="text-danger">*</span></label>
                                    <select class="form-select" id="">
                                        <option>เลือกสัญชาติ</option>
                                        <option></option>
                                        <option></option>
                                        <option></option>
                                    </select>
                                </div>
                                <div class="col-md-6 col-xl-5">
                                    <label for="" class="form-label">เลขบัตรประชาชน <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="">
                                </div>
                                <div class="col-md-6 col-xl-5">
                                    <label for="" class="form-label">โทรศัพท์ <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="">
                                </div>
                                <div class="col-md-6 col-xl-4">
                                    <label for="" class="form-label">E-mail</label>
                                    <input type="text" class="form-control" id="">
                                </div>
                                <div class="col-md-6 col-xl-4">
                                    <label for="" class="form-label">Line ID</label>
                                    <input type="text" class="form-control" id="">
                                </div>
                                <div class="col-md-6 col-xl-4 mb-3">
                                    <label for="" class="form-label">Facebook</label>
                                    <input type="text" class="form-control" id="">
                                </div>
                            </div>
                            <div class="borderR10 py-2 px-3 bg-purple3 bg-opacity-50 h5 mb-3">ที่อยู่ตามบัตรประชาชน</div>
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <div class="file-upload">
                                        <label for="upload" class="file-upload__label"><i class='bx bx-upload' ></i> อัพโหลดเอกสาร</label>
                                        <input id="upload" class="file-upload__input" type="file" name="file-upload">
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl-5">
                                    <label for="" class="form-label">ที่อยู่ <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="">
                                </div>
                                <div class="col-md-6 col-xl-3">
                                    <label for="" class="form-label">หมู่ที่ <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="">
                                </div>
                                <div class="col-md-6 col-xl-4">
                                    <label for="" class="form-label">ซอย <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="">
                                </div>
                                <div class="col-md-6 col-xl-4">
                                    <label for="" class="form-label">ถนน <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="">
                                </div>
                                <div class="col-md-6 col-xl-4">
                                    <label for="" class="form-label">ตำบล/แขวง <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="">
                                </div>
                                <div class="col-md-6 col-xl-4">
                                    <label for="" class="form-label">อำเภอ/เขต <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="">
                                </div>
                                <div class="col-md-6 col-xl-4">
                                    <label for="" class="form-label">จังหวัด <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="">
                                </div>
                                <div class="col-md-6 col-xl-4">
                                    <label for="" class="form-label">รหัสไปรษณีย์ <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="">
                                </div>
                                <div class="col-md-6 col-xl-4 mb-3">
                                    <label for="" class="form-label">เบอร์มือถือ</label>
                                    <input type="text" class="form-control" id="">
                                </div>
                            </div>
                            <div class="borderR10 py-2 px-3 bg-purple3 bg-opacity-50 h5 mb-3">
                                ที่อยู่จัดส่ง 
                                <div class="form-check form-check-inline h6 fw-normal">
                                  <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                  <label class="form-check-label" for="flexCheckDefault">
                                    ใช้ที่อยู่เดียวกันบัตรประชาชน
                                  </label>
                                </div>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6 col-xl-5">
                                    <label for="" class="form-label">ที่อยู่ <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="">
                                </div>
                                <div class="col-md-6 col-xl-3">
                                    <label for="" class="form-label">หมู่ที่ <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="">
                                </div>
                                <div class="col-md-6 col-xl-4">
                                    <label for="" class="form-label">ซอย <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="">
                                </div>
                                <div class="col-md-6 col-xl-4">
                                    <label for="" class="form-label">ถนน <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="">
                                </div>
                                <div class="col-md-6 col-xl-4">
                                    <label for="" class="form-label">ตำบล/แขวง <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="">
                                </div>
                                <div class="col-md-6 col-xl-4">
                                    <label for="" class="form-label">อำเภอ/เขต <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="">
                                </div>
                                <div class="col-md-6 col-xl-4">
                                    <label for="" class="form-label">จังหวัด <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="">
                                </div>
                                <div class="col-md-6 col-xl-4">
                                    <label for="" class="form-label">รหัสไปรษณีย์ <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="">
                                </div>
                                <div class="col-md-6 col-xl-4 mb-3">
                                    <label for="" class="form-label">เบอร์มือถือ</label>
                                    <input type="text" class="form-control" id="">
                                </div>
                            </div>
                            <div class="borderR10 py-2 px-3 bg-purple3 bg-opacity-50 h5 mb-3">ข้อมูลบัญชีธนาคารเพื่อรับรายได้</div>
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <div class="alert alert-danger d-flex align-items-center" role="alert">
                                      <i class='bx bxs-error me-2'></i>
                                      <div>
                                        สมาชิกจะใส่หรือไม่ใส่ก็ได้ หากไม่ได้ใส่จะมีผลกับการโอนเงินให้สมาชิก
                                      </div>
                                    </div>
                                    <div class="file-upload">
                                        <label for="upload" class="file-upload__label"><i class='bx bx-upload' ></i> อัพโหลดเอกสาร</label>
                                        <input id="upload" class="file-upload__input" type="file" name="file-upload">
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl-4">
                                    <label for="" class="form-label">ธนาคาร</label>
                                    <select class="form-select" id="">
                                        <option>เลือกธนาคาร</option>
                                        <option></option>
                                        <option></option>
                                        <option></option>
                                    </select>
                                </div>
                                <div class="col-md-6 col-xl-4">
                                    <label for="" class="form-label">สาขา</label>
                                    <input type="text" class="form-control" id="">
                                </div>
                                <div class="col-md-6 col-xl-4">
                                    <label for="" class="form-label">เลขที่บัญชี <span class="text-danger small">* (ใส่เฉพาะตัวเลขเท่านั้น)</span></label>
                                    <input type="text" class="form-control" id="">
                                </div>
                                <div class="col-md-6 col-xl-12 mb-3">
                                    <label for="" class="form-label">ชื่อบัญชี</label>
                                    <input type="text" class="form-control" id="">
                                </div>
                            </div>
                            <div class="borderR10 py-2 px-3 bg-purple3 bg-opacity-50 h5 mb-3">ผู้รับผลประโยชน์</div>
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <div class="alert alert-danger d-flex align-items-center mb-0" role="alert">
                                      <i class='bx bxs-error me-2'></i>
                                      <div>
                                        ถ้าไม่กรอกถือว่าผู้รับผลประโยชน์จะเป็นผู้รับผลประโยชน์ตามกฎหมาย
                                      </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl-4">
                                    <label for="" class="form-label">ชื่อ</label>
                                    <input type="text" class="form-control" id="">
                                </div>
                                <div class="col-md-6 col-xl-4">
                                    <label for="" class="form-label">นามสกุล</label>
                                    <input type="text" class="form-control" id="">
                                </div>
                                <div class="col-md-6 col-xl-4 mb-3">
                                    <label for="" class="form-label">เกี่ยวข้องเป็น</label>
                                    <input type="text" class="form-control" id="">
                                </div>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-12 text-center">
                                    <hr>
                                    <button type="submit" class="btn btn-success rounded-pill">บันทึกข้อมูล</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    </form>    
                </div>
            </div>
        </div>
    </div>
    <?php require('inc_footer.php'); ?>
    <script>
        $('#linkMenuTop .nav-item').eq(0).addClass('active');
    </script>
</body>

</html>