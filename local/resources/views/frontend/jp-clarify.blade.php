@extends('layouts.frontend.app')
@section('conten')
    <title>บริษัท มารวยด้วยกัน จำกัด</title>

    <div class="bg-whiteLight page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php">หน้าแรก</a></li>
                            <li class="breadcrumb-item active text-truncate" aria-current="page">แจง JP</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-box borderR10 mb-3">
                        <div class="card-body">
                            <h4 class="card-title">ค้นหา</h4>
                            <hr>
                            <div class="row g-3">
                                <div class="col-lg-3">
                                    <label for="" class="form-label">ประเภทการแจง JP.</label>
                                    <select class="form-select" id="">
                                        <option>ทั้งหมด</option>
                                        <option>แจงยืนยันสิทธิ</option>
                                        <option>แจงรับส่วนลด</option>
                                        <option>แจงปรับตำแหน่ง</option>
                                    </select>
                                </div>
                                <div class="col-md-6 col-lg-3">
                                    <label for="" class="form-label">วันที่เริ่มต้น</label>
                                    <input type="date" class="form-control">
                                </div>
                                <div class="col-md-6 col-lg-3">
                                    <label for="" class="form-label">วันที่สิ้นสุด</label>
                                    <input type="date" class="form-control">
                                </div>
                                <div class="col-md-3 col-lg-3">
                                    <label for="" class="form-label">ตำแหน่ง</label>
                                    <select class="form-select" id="">
                                        <option>ทั้งหมด</option>
                                        <option>MB</option>
                                        <option>MO</option>
                                        <option>VIP</option>
                                        <option>VVIP</option>
                                        <option>SVVIP</option>
                                        <option>MG</option>
                                        <option>MR</option>
                                        <option>ME</option>
                                        <option>MD</option>
                                    </select>
                                </div>
                                <div class="col-md-4 col-lg-3">
                                    <label for="" class="form-label">รหัสสมาชิก</label>
                                    <input type="text" class="form-control">
                                </div>
                                <div class="col-md-4 col-lg-3">
                                    <label for="" class="form-label">ชื่อ-นามสกุล</label>
                                    <input type="text" class="form-control">
                                </div>
                                <div class="col-md-1 col-lg-1">
                                    <label for="" class="form-label d-none d-md-block">&nbsp;</label>
                                    <button type="button" class="btn btn-dark rounded-circle btn-icon"><i
                                            class="bx bx-search"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-p1 rounded-pill mb-3" data-bs-toggle="modal"
                        data-bs-target="#addClarifyJPModal"><i class='bx bx-plus me-1'></i> ทำรายการแจงJP.</button>
                    <div class="card card-box borderR10 mb-2 mb-md-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    <h4 class="card-title mb-0">ประวัติการแจง JP.</h4>
                                </div>
                                <div class="col-sm-6 text-md-end">
                                    <button type="button" class="btn btn-info rounded-pill"><i
                                            class='bx bxs-file me-1'></i> ออกรายงาน</button>
                                </div>
                            </div>
                            <hr>
                            <table id="workL" class="table table-bordered nowrap">
                                <thead class="bg-light">
                                    <tr>
                                        <th>เลขที่รายการ</th>
                                        <th>วันที่ทำรายการ</th>
                                        <th>ประเภทรายการ</th>
                                        <th>รหัสสมาชิก</th>
                                        <th>ชื่อ-นามสกุล</th>
                                        <th>ตำแหน่ง</th>
                                        <th>จำนวนJP</th>
                                        <th>จำนวนJPคงเหลือ</th>
                                        <th>การจัดการ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>jpc012345</td>
                                        <td>28/04/2022</td>
                                        <td>แจงยืนยันสิทธิ</td>
                                        <td>mlm1150987</td>
                                        <td>สัจพร นันทวัฒน์</td>
                                        <td>VIP</td>
                                        <td class="text-end">150</td>
                                        <td class="text-end">780</td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#editJPModal">
                                                <i class='bx bx-link-external'></i>
                                            </button>
                                            <button type="button" class="btn btn-danger btn-sm">
                                                <i class='bx bxs-trash'></i>
                                            </button>
                                            <button type="button" class="btn btn-info btn-sm">
                                                <i class='bx bxs-printer'></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>jpc012345</td>
                                        <td>28/04/2022</td>
                                        <td>แจงรับส่วนลด</td>
                                        <td>mlm1150987</td>
                                        <td>สัจพร นันทวัฒน์</td>
                                        <td>VIP</td>
                                        <td class="text-end">150</td>
                                        <td class="text-end">780</td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#editJPModal">
                                                <i class='bx bx-link-external'></i>
                                            </button>
                                            <button type="button" class="btn btn-danger btn-sm">
                                                <i class='bx bxs-trash'></i>
                                            </button>
                                            <button type="button" class="btn btn-info btn-sm">
                                                <i class='bx bxs-printer'></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>jpc012345</td>
                                        <td>28/04/2022</td>
                                        <td>แจงปรับตำแหน่ง</td>
                                        <td>mlm1150987</td>
                                        <td>สัจพร นันทวัฒน์</td>
                                        <td>VIP</td>
                                        <td class="text-end">150</td>
                                        <td class="text-end">780</td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#editJPModal">
                                                <i class='bx bx-link-external'></i>
                                            </button>
                                            <button type="button" class="btn btn-danger btn-sm">
                                                <i class='bx bxs-trash'></i>
                                            </button>
                                            <button type="button" class="btn btn-info btn-sm">
                                                <i class='bx bxs-printer'></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="addClarifyJPModal" tabindex="-1" aria-labelledby="addClarifyJPModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
            <div class="modal-content borderR25">
                <div class="modal-header">
                    <h5 class="modal-title" id="addClarifyJPModalLabel">เพิ่มรายการแจง JP</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="clarifyJP" id="cJPinlineRadio1"
                            value="cJP1" checked>
                        <label class="form-check-label" for="cJPinlineRadio1">แจงยืนยันสิทธิ</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="clarifyJP" id="cJPinlineRadio2"
                            value="cJP2">
                        <label class="form-check-label" for="cJPinlineRadio2">แจงรับส่วนลด</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="clarifyJP" id="cJPinlineRadio3"
                            value="cJP3">
                        <label class="form-check-label" for="cJPinlineRadio3">แจงปรับตำแหน่ง</label>
                    </div>
                    <div class="cJP1 boxJPC mt-3">
                        <div class="card borderR10 p-2">
                            <h5 class="text-center">แจงยืนยันสิทธิ</h5>
                            <div class="row gx-2">
                                <div class="col-md-6">
                                    <label for="" class="form-label">รหัสสมาชิก <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control mb-3" id="">
                                </div>
                                <div class="col-md-6">
                                    <label for="" class="form-label">ชื่อ-นามสกุล</label>
                                    <input type="text" class="form-control mb-3" id="">
                                </div>
                                <div class="col-sm-6">
                                    <div class="alert alert-white p-2 h-82 borderR10">
                                        <div class="d-flex">
                                            <div class="flex-shrink-0">
                                                <img src="{{ asset('frontend/images/man.png') }}" alt="..."
                                                    width="30px">
                                            </div>
                                            <div class="flex-grow-1 ms-2">
                                                <p class="small mb-0">MLM0534768</p>
                                                <h6>ชัยพัทธ์ ศรีสดุดี</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="alert alert-purple p-2 h-82 borderR10">
                                        <p class="small">JP คงเหลือ</p>
                                        <p class="text-end mb-0"><span
                                                class="h5 text-purple1 bg-opacity-100">1000</span>JP.</p>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <label for="" class="form-label">ตำแหน่ง</label>
                                    <input type="text" class="form-control" id="">
                                </div>
                                <div class="col-9 col-md-5">
                                    <label for="" class="form-label">จำนวนJP <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="" disabled value="500">
                                </div>
                                <div class="col-3 col-md-1">
                                    <label for="" class="form-label d-block">&nbsp;</label>
                                    <p readonly class="form-control-plaintext" id="">JP.</p>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between border-0">
                            <button type="button" class="btn btn-outline-dark rounded-pill"
                                data-bs-dismiss="modal">ยกเลิก</button>
                            <button type="button" class="btn btn-p1 rounded-pill d-flex align-items-center"
                                data-bs-target="#addClarifyJPModalC1" data-bs-toggle="modal"><i
                                    class='bx bxs-check-circle me-2'></i>ทำรายการ</button>
                        </div>
                    </div>
                    <!--ตัวเลือก2-->
                    <div class="cJP2 boxJPC mt-3">
                        <div class="card borderR10 p-2">
                            <h5 class="text-center">แจงรับส่วนลด</h5>
                            <div class="row gx-2">
                                <div class="col-md-6">
                                    <label for="" class="form-label">รหัสสมาชิก <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control mb-3" id="">
                                </div>
                                <div class="col-md-6">
                                    <label for="" class="form-label">ชื่อ-นามสกุล</label>
                                    <input type="text" class="form-control mb-3" id="">
                                </div>
                                <div class="col-sm-6">
                                    <div class="alert alert-white p-2 h-82 borderR10">
                                        <div class="d-flex">
                                            <div class="flex-shrink-0">
                                                <img src="{{ asset('frontend/images/man.png') }}" alt="..."
                                                    width="30px">
                                            </div>
                                            <div class="flex-grow-1 ms-2">
                                                <p class="small mb-0">MLM0534768</p>
                                                <h6>ชัยพัทธ์ ศรีสดุดี</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="alert alert-purple p-2 h-82 borderR10">
                                        <p class="small">JP คงเหลือ</p>
                                        <p class="text-end mb-0"><span
                                                class="h5 text-purple1 bg-opacity-100">1000</span>JP.</p>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <label for="" class="form-label">ตำแหน่ง</label>
                                    <input type="text" class="form-control" id="">
                                </div>
                                <div class="col-9 col-md-5">
                                    <label for="" class="form-label">จำนวนJP <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="">
                                    <p class="small text-danger mb-0">***1 JP. = xxx บาท</p>
                                </div>
                                <div class="col-3 col-md-1">
                                    <label for="" class="form-label d-block">&nbsp;</label>
                                    <p readonly class="form-control-plaintext" id="">JP.</p>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between border-0">
                            <button type="button" class="btn btn-outline-dark rounded-pill"
                                data-bs-dismiss="modal">ยกเลิก</button>
                            <button type="button" class="btn btn-p1 rounded-pill d-flex align-items-center"
                                data-bs-target="#addClarifyJPModalC2" data-bs-toggle="modal"><i
                                    class='bx bxs-check-circle me-2'></i>ทำรายการ</button>
                        </div>
                    </div>
                    <!--ตัวเลือก3-->
                    <div class="cJP3 boxJPC mt-3">
                        <div class="card borderR10 p-2">
                            <h5 class="text-center">แจงปรับตำแหน่ง</h5>
                            <div class="row gx-2">
                                <div class="col-md-6">
                                    <label for="" class="form-label">รหัสสมาชิก <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control mb-3" id="">
                                </div>
                                <div class="col-md-6">
                                    <label for="" class="form-label">ชื่อ-นามสกุล</label>
                                    <input type="text" class="form-control mb-3" id="">
                                </div>
                                <div class="col-sm-6">
                                    <div class="alert alert-white p-2 h-82 borderR10">
                                        <div class="d-flex">
                                            <div class="flex-shrink-0">
                                                <img src="{{ asset('frontend/images/man.png') }}" alt="..."
                                                    width="30px">
                                            </div>
                                            <div class="flex-grow-1 ms-2">
                                                <p class="small mb-0">MLM0534768</p>
                                                <h6>ชัยพัทธ์ ศรีสดุดี</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="alert alert-purple p-2 h-82 borderR10">
                                        <p class="small">JP คงเหลือ</p>
                                        <p class="text-end mb-0"><span
                                                class="h5 text-purple1 bg-opacity-100">1000</span>JP.</p>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <label for="" class="form-label">ตำแหน่ง</label>
                                    <input type="text" class="form-control" id="">
                                </div>
                                <div class="col-9 col-md-5">
                                    <label for="" class="form-label">จำนวนJP <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="">
                                </div>
                                <div class="col-3 col-md-1">
                                    <label for="" class="form-label d-block">&nbsp;</label>
                                    <p readonly class="form-control-plaintext" id="">JP.</p>
                                </div>
                            </div>
                        </div>
                        <div class="alert alert-warning d-flex mt-2" role="alert">
                            <i class='bx bxs-info-circle me-2 bx-sm'></i>
                            <div>
                                กรณีตำแหน่งผู้ทำรายการสูงกว่า VVIP. ไม่สามารถแจ้งปรับตำแหน่งได้
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between border-0">
                            <button type="button" class="btn btn-outline-dark rounded-pill"
                                data-bs-dismiss="modal">ยกเลิก</button>
                            <button type="button" class="btn btn-p1 rounded-pill d-flex align-items-center"
                                data-bs-target="#addClarifyJPModalC3" data-bs-toggle="modal"><i
                                    class='bx bxs-check-circle me-2'></i>ทำรายการ</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal confirm C1-->
    <div class="modal fade" id="addClarifyJPModalC1" tabindex="-1" aria-labelledby="addClarifyJPModalC1Label"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
            <div class="modal-content borderR25">
                <div class="modal-header justify-content-center">
                    <h5 class="modal-title" id="addClarifyJPModalC1Label">ทำรายการแจงยืนยันสิทธิรักษาสภาพ</h5>
                </div>
                <div class="modal-body">
                    <div class="card borderR10 p-2 mb-3">
                        <div class="row mb-3">
                            <label for="" class="col-sm-4 col-form-label fw-bold">รหัสสมาชิก</label>
                            <div class="col-sm-8">
                                <p readonly class="form-control-plaintext" id="">MLM0534768</p>
                            </div>
                            <label for="" class="col-sm-4 col-form-label fw-bold">ชื่อ-นามสกุล</label>
                            <div class="col-sm-8">
                                <p readonly class="form-control-plaintext" id="">สัจพร นันทวัฒน์</p>
                            </div>
                            <label for="" class="col-sm-4 col-form-label fw-bold">ตำแหน่ง</label>
                            <div class="col-sm-8">
                                <p readonly class="form-control-plaintext" id="">VIP</p>
                            </div>
                            <label for="" class="col-sm-4 col-form-label fw-bold">จำนวน</label>
                            <div class="col-9 col-sm-6">
                                <p readonly class="form-control-plaintext" id="">500</p>
                            </div>
                            <div class="col-3 col-sm-2">
                                <p readonly class="form-control-plaintext" id="">JP.</p>
                            </div>
                            <label for="" class="col-sm-4 col-form-label fw-bold">วันที่ทำรายการ</label>
                            <div class="col-sm-8">
                                <p readonly class="form-control-plaintext" id="">28/04/2022</p>
                            </div>
                            <label for="" class="col-sm-4 col-form-label fw-bold">เวลาที่ทำรายการ</label>
                            <div class="col-sm-8">
                                <p readonly class="form-control-plaintext" id="">14:38</p>
                            </div>
                        </div>
                    </div>
                    <div class="alert alert-danger d-flex" role="alert">
                        <i class='bx bxs-error me-2 bx-sm'></i>
                        <div>
                            กรุณาแคปหน้าจอการทำรายการเพื่อใช้ตรวจสอบกรณีมีปัญหาในการทำรายการ
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between border-0">
                    <button type="button" class="btn btn-outline-dark rounded-pill" data-bs-target="#addClarifyJPModal"
                        data-bs-toggle="modal">ยกเลิก</button>
                    <button type="button" class="btn btn-p1 rounded-pill d-flex align-items-center"
                        data-bs-dismiss="modal"><i class='bx bxs-check-circle me-2'></i>ยืนยัน</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Confirm C2-->
    <div class="modal fade" id="addClarifyJPModalC2" tabindex="-1" aria-labelledby="addClarifyJPModalC2Label"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
            <div class="modal-content borderR25">
                <div class="modal-header justify-content-center">
                    <h5 class="modal-title" id="addClarifyJPModalC2Label">ทำรายการแจงรับส่วนลด</h5>
                </div>
                <div class="modal-body">
                    <div class="card borderR10 p-2 mb-3">
                        <div class="row mb-3">
                            <label for="" class="col-sm-4 col-form-label fw-bold">รหัสสมาชิก</label>
                            <div class="col-sm-8">
                                <p readonly class="form-control-plaintext" id="">MLM0534768</p>
                            </div>
                            <label for="" class="col-sm-4 col-form-label fw-bold">ชื่อ-นามสกุล</label>
                            <div class="col-sm-8">
                                <p readonly class="form-control-plaintext" id="">สัจพร นันทวัฒน์</p>
                            </div>
                            <label for="" class="col-sm-4 col-form-label fw-bold">ตำแหน่ง</label>
                            <div class="col-sm-8">
                                <p readonly class="form-control-plaintext" id="">VIP</p>
                            </div>
                            <label for="" class="col-sm-4 col-form-label fw-bold">จำนวน</label>
                            <div class="col-9 col-sm-6">
                                <p readonly class="form-control-plaintext" id="">xxx</p>
                                <p readonly class="form-control-plaintext small text-danger" id="">*** 1JP. = xxx
                                    บาท</p>
                            </div>
                            <div class="col-3 col-sm-2">
                                <p readonly class="form-control-plaintext" id="">JP.</p>
                            </div>
                            <label for="" class="col-sm-4 col-form-label fw-bold">วันที่ทำรายการ</label>
                            <div class="col-sm-8">
                                <p readonly class="form-control-plaintext" id="">28/04/2022</p>
                            </div>
                            <label for="" class="col-sm-4 col-form-label fw-bold">เวลาที่ทำรายการ</label>
                            <div class="col-sm-8">
                                <p readonly class="form-control-plaintext" id="">14:38</p>
                            </div>
                        </div>
                    </div>
                    <div class="alert alert-danger d-flex" role="alert">
                        <i class='bx bxs-error me-2 bx-sm'></i>
                        <div>
                            กรุณาแคปหน้าจอการทำรายการเพื่อใช้ตรวจสอบกรณีมีปัญหาในการทำรายการ
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between border-0">
                    <button type="button" class="btn btn-outline-dark rounded-pill" data-bs-target="#addClarifyJPModal"
                        data-bs-toggle="modal">ยกเลิก</button>
                    <button type="button" class="btn btn-p1 rounded-pill d-flex align-items-center"
                        data-bs-dismiss="modal"><i class='bx bxs-check-circle me-2'></i>ยืนยัน</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Confirm C3-->
    <div class="modal fade" id="addClarifyJPModalC3" tabindex="-1" aria-labelledby="addClarifyJPModalC3Label"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
            <div class="modal-content borderR25">
                <div class="modal-header justify-content-center">
                    <h5 class="modal-title" id="addClarifyJPModalC3Label">ทำรายการแจงปรับตำแหน่ง</h5>
                </div>
                <div class="modal-body">
                    <div class="card borderR10 p-2 mb-3">
                        <div class="row mb-3">
                            <label for="" class="col-sm-4 col-form-label fw-bold">รหัสสมาชิก</label>
                            <div class="col-sm-8">
                                <p readonly class="form-control-plaintext" id="">MLM0534768</p>
                            </div>
                            <label for="" class="col-sm-4 col-form-label fw-bold">ชื่อ-นามสกุล</label>
                            <div class="col-sm-8">
                                <p readonly class="form-control-plaintext" id="">สัจพร นันทวัฒน์</p>
                            </div>
                            <label for="" class="col-sm-4 col-form-label fw-bold">ตำแหน่ง</label>
                            <div class="col-sm-8">
                                <p readonly class="form-control-plaintext" id="">VIP</p>
                            </div>
                            <label for="" class="col-sm-4 col-form-label fw-bold">จำนวน</label>
                            <div class="col-9 col-sm-6">
                                <p readonly class="form-control-plaintext" id="">xxx</p>
                            </div>
                            <div class="col-3 col-sm-2">
                                <p readonly class="form-control-plaintext" id="">JP.</p>
                            </div>
                            <label for="" class="col-sm-4 col-form-label fw-bold">วันที่ทำรายการ</label>
                            <div class="col-sm-8">
                                <p readonly class="form-control-plaintext" id="">28/04/2022</p>
                            </div>
                            <label for="" class="col-sm-4 col-form-label fw-bold">เวลาที่ทำรายการ</label>
                            <div class="col-sm-8">
                                <p readonly class="form-control-plaintext" id="">14:38</p>
                            </div>
                        </div>
                    </div>
                    <div class="alert alert-danger d-flex" role="alert">
                        <i class='bx bxs-error me-2 bx-sm'></i>
                        <div>
                            กรุณาแคปหน้าจอการทำรายการเพื่อใช้ตรวจสอบกรณีมีปัญหาในการทำรายการ
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between border-0">
                    <button type="button" class="btn btn-outline-dark rounded-pill" data-bs-target="#addClarifyJPModal"
                        data-bs-toggle="modal">ยกเลิก</button>
                    <button type="button" class="btn btn-p1 rounded-pill d-flex align-items-center"
                        data-bs-dismiss="modal"><i class='bx bxs-check-circle me-2'></i>ยืนยัน</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $('.page-content').css({
            'min-height': $(window).height() - $('.navbar').height()
        });
    </script>
    <script>
        $(document).ready(function() {
            var table = $('#workL').DataTable({
                responsive: true
            });

            new $.fn.dataTable.FixedHeader(table);
        });
    </script>
    <script type="text/javascript">
        $('.cJP2, .cJP3').addClass('d-none')
        $(document).ready(function() {
            $('#cJPinlineRadio1').click(function() {
                $('.cJP1').addClass('d-block').removeClass('d-none');
                $('.cJP2').addClass('d-none').removeClass('d-block');
                $('.cJP3').addClass('d-none').removeClass('d-block');
            });
            $('#cJPinlineRadio2').click(function() {
                $('.cJP1').addClass('d-none').removeClass('d-block');
                $('.cJP2').addClass('d-block').removeClass('d-none');
                $('.cJP3').addClass('d-none').removeClass('d-block');
            });
            $('#cJPinlineRadio3').click(function() {
                $('.cJP1').addClass('d-none').removeClass('d-block');
                $('.cJP2').addClass('d-none').removeClass('d-block');
                $('.cJP3').addClass('d-block').removeClass('d-none');
            });
        });
    </script>
@endsection
