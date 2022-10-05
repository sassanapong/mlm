<title>บริษัท มารวยด้วยกัน จำกัด</title>



@extends('layouts.frontend.app')
@section('conten')
    <div class="bg-whiteLight page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">หน้าแรก</a></li>
                            <li class="breadcrumb-item active text-truncate" aria-current="page">รับ-โอนJP</li>
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
                                <div class="col-md-6 col-lg-3">
                                    <label for="" class="form-label">ประเภทการรับ-โอน JP.</label>
                                    <select class="form-select" id="">
                                        <option>ทั้งหมด</option>
                                        <option>โอนJP</option>
                                        <option>รับJP</option>
                                    </select>
                                </div>
                                <div class="col-md-6 col-lg-3">
                                    <label for="" class="form-label">จำนวนJP</label>
                                    <input type="text" class="form-control">
                                </div>
                                <div class="col-md-6 col-lg-3">
                                    <label for="" class="form-label">วันที่เริ่มต้น</label>
                                    <input type="date" class="form-control">
                                </div>
                                <div class="col-md-6 col-lg-3">
                                    <label for="" class="form-label">วันที่สิ้นสุด</label>
                                    <input type="date" class="form-control">
                                </div>
                                <div class="col-md-5 col-lg-2">
                                    <label for="" class="form-label">ผู้โอน</label>
                                    <input type="text" class="form-control" placeholder="รหัสสมาชิก">
                                </div>
                                <div class="col-md-5 col-lg-3">
                                    <label for="" class="form-label d-none d-md-block">&nbsp;</label>
                                    <input type="text" class="form-control" placeholder="ชื่อ-นามสกุล">
                                </div>
                                <div class="col-md-5 col-lg-2">
                                    <label for="" class="form-label">ผู้รับโอน</label>
                                    <input type="text" class="form-control" placeholder="รหัสสมาชิก">
                                </div>
                                <div class="col-md-5 col-lg-3">
                                    <label for="" class="form-label d-none d-md-block">&nbsp;</label>
                                    <input type="text" class="form-control" placeholder="ชื่อ-นามสกุล">
                                </div>
                                <div class="col-md-2 col-lg-1">
                                    <label for="" class="form-label d-none d-md-block">&nbsp;</label>
                                    <button type="button" class="btn btn-dark rounded-circle btn-icon"><i
                                            class="bx bx-search"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-p1 rounded-pill mb-3" data-bs-toggle="modal"
                        data-bs-target="#addTransferJPModal"><i class='bx bx-plus me-1'></i> ทำรายการโอนJP.</button>
                    <div class="card card-box borderR10 mb-2 mb-md-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    <h4 class="card-title mb-0">ประวัติการรับ-โอน JP.</h4>
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
                                        <th>ผู้โอน</th>
                                        <th>ผู้รับ</th>
                                        <th>จำนวนJP</th>
                                        <th>จำนวนJPคงเหลือ</th>
                                        <th>การจัดการ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>jpc012345</td>
                                        <td>28/04/2022</td>
                                        <td>โอนJP</td>
                                        <td>mlm1150987<br>สัจพร นันทวัฒน์</td>
                                        <td>mlm1150988<br>ภูดิศ ชัยภูมิ</td>
                                        <td class="text-end">150</td>
                                        <td class="text-end">780</td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#editJPTModal">
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
                                        <td>jpc012344</td>
                                        <td>12/02/2022</td>
                                        <td>รับJP</td>
                                        <td>mlm1150987<br>สัจพร นันทวัฒน์</td>
                                        <td>mlm1150988<br>ภูดิศ ชัยภูมิ</td>
                                        <td class="text-end">150</td>
                                        <td class="text-end">780</td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#editJPTModal">
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
    <div class="modal fade" id="addTransferJPModal" tabindex="-1" aria-labelledby="addTransferJPModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
            <div class="modal-content borderR25">
                <div class="modal-header">
                    <h5 class="modal-title" id="addTransferJPModalLabel">เพิ่มรายการโอน JP</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="cJP1 boxJPC mt-3">
                        <div class="card borderR10 p-2 mb-3">
                            <h5 class="text-center">ผู้โอน</h5>
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
                            </div>
                        </div>
                        <div class="card borderR10 p-2 mb-3">
                            <h5 class="text-center">ผู้รับโอน</h5>
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
                                <div class="col-9 col-md-5">
                                    <label for="" class="form-label">จำนวนJP ที่ต้องการโอน <span
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
                                data-bs-target="#addTransferCJPModal" data-bs-toggle="modal"><i
                                    class='bx bxs-check-circle me-2'></i>ทำรายการ</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal-->
    <div class="modal fade" id="addTransferCJPModal" tabindex="-1" aria-labelledby="addTransferCJPModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
            <div class="modal-content borderR25">
                <div class="modal-header justify-content-center">
                    <h5 class="modal-title" id="addTransferCJPModalLabel">ทำรายการโอน JP.</h5>
                </div>
                <div class="modal-body">
                    <div class="card borderR10 p-2">
                        <div class="row mb-3">
                            <div class="col-sm-12">
                                <h5 class="text-purple1 bg-opacity-100 mb-0">จาก</h5>
                            </div>
                            <label for="" class="col-sm-4 col-form-label fw-bold">รหัสสมาชิก</label>
                            <div class="col-sm-8">
                                <p readonly class="form-control-plaintext" id="">MLM0534768</p>
                            </div>
                            <label for="" class="col-sm-4 col-form-label fw-bold">ชื่อ-นามสกุล</label>
                            <div class="col-sm-8">
                                <p readonly class="form-control-plaintext" id="">สัจพร นันทวัฒน์</p>
                            </div>
                            <hr>
                            <div class="col-sm-12">
                                <h5 class="text-purple1 bg-opacity-100 mb-0">ไปยัง</h5>
                            </div>
                            <label for="" class="col-sm-4 col-form-label fw-bold">รหัสสมาชิก</label>
                            <div class="col-sm-8">
                                <p readonly class="form-control-plaintext" id="">MLM0534767</p>
                            </div>
                            <label for="" class="col-sm-4 col-form-label fw-bold">ชื่อ-นามสกุล</label>
                            <div class="col-sm-8">
                                <p readonly class="form-control-plaintext" id="">ภูดิส ชัยภูมิ</p>
                            </div>
                            <hr>
                            <label for="" class="col-sm-4 col-form-label fw-bold">จำนวนโอน</label>
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
                    <div class="alert alert-danger d-flex mt-3" role="alert">
                        <i class='bx bxs-error me-2 bx-sm'></i>
                        <div>
                            กรุณาแคปหน้าจอการทำรายการเพื่อใช้ตรวจสิบกรณีมีปัญหาในการทำรายการ
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between border-0">
                    <button type="button" class="btn btn-outline-dark rounded-pill" data-bs-target="#addTransferJPModal"
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
@endsection
