     <title>บริษัท มารวยด้วยกัน จำกัด</title>


     @extends('layouts.frontend.app')
     @section('css')
         <link href='https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css' rel='stylesheet'>
     @endsection

     @section('conten')
         <div class="bg-whiteLight page-content">
             <div class="container-fluid">
                 <div class="row">
                     <div class="col-lg-12">
                         <nav aria-label="breadcrumb">
                             <ol class="breadcrumb">
                                 <li class="breadcrumb-item"><a href="{{ route('home') }}">หน้าแรก</a></li>
                                 <li class="breadcrumb-item active text-truncate" aria-current="page">สายงาน</li>
                             </ol>
                         </nav>
                     </div>
                 </div>
                 <div class="row">

                     <div class="col-md-12">
                         {{-- <div class="card card-box borderR10 mb-3">
                             <div class="card-body">
                                 <h4 class="card-title">ค้นหา</h4>
                                 <hr>
                                 <div class="row g-3">
                                     <div class="col-md-6 col-lg-3">
                                         <label for="" class="form-label">การรักษาสภาพ</label>
                                         <select class="form-select" id="">
                                             <option>ทั้งหมด</option>
                                             <option>Active</option>
                                             <option>กรุณายืนยันสิทธิ</option>
                                             <option>Not Active</option>
                                         </select>
                                     </div>
                                     <div class="col-md-6 col-lg-3">
                                         <label for="" class="form-label">ชั้น</label>
                                         <select class="form-select" id="">
                                             <option>ทั้งหมด</option>
                                             <option>1</option>
                                             <option>2</option>
                                             <option>3</option>
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
                         </div> --}}
                         <div class="card card-box borderR10 mb-2 mb-md-0">
                             <div class="card-body">
                                 <div class="row">
                                    <?php
                                    if($user_name){
                                        $text_user_name = $user_name;
                                    }else{
                                        $text_user_name = Auth::guard('c_user')->user()->user_name;
                                    }

                                    $usr_name = \App\Http\Controllers\Frontend\FC\AllFunctionController::get_upline($text_user_name);

                                    ?>


                                     <div class="col-sm-4">
                                         <h4 class="card-title mb-2">ข้อมูลสายงานแนะนำ</h4>
                                         <p class="mb-2">รหัสสมาชิก {{ @$usr_name->user_name }} | {{ @$usr_name->name }} {{ @$usr_name->last_name }}</ย>
                                     </div>
                                     <div class="col-sm-8 text-md-end">
                                         <div><span>สถานะการแจ้งเตือน: </span> <i class="fas fa-circle text-success"></i>
                                             Active <i class="fas fa-circle text-warning"></i> กรุณายืนยันสิทธิ <i
                                                 class="fas fa-circle text-danger"></i> Not Active</div>
                                     </div>
                                 </div>
                                 <hr>
                                 <div class=" table-responsive">

                                     <table id="workL" class="table table-bordered nowrap">


                                     </table>
                                 </div>
                             </div>
                         </div>
                     </div>
                 </div>
             </div>
         </div>

         <!-- transfer Modal -->
         <div class="modal fade" id="addTransferJPModal" tabindex="-1" aria-labelledby="addTransferJPModalLabel"
             aria-hidden="true">
             <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
                 <div class="modal-content borderR25">
                     <div class="modal-header">
                         <h5 class="modal-title" id="addTransferJPModalLabel">โอน JP</h5>
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
                                         <input type="text" class="form-control mb-3" id=""
                                             value="MLM0534768" disabled>
                                     </div>
                                     <div class="col-md-6">
                                         <label for="" class="form-label">ชื่อ-นามสกุล</label>
                                         <input type="text" class="form-control mb-3" id=""
                                             value="ชัยพัทธ์ ศรีสดุดี" disabled>
                                     </div>
                                     <div class="col-sm-6">
                                         <div class="alert alert-white p-2 h-82 borderR10">
                                             <div class="d-flex">
                                                 <div class="flex-shrink-0">
                                                     <img src="{{ asset('frontend/images/man.png') }} " alt="..."
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
                                         <input type="text" class="form-control" id="" disabled
                                             value="500">
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
         <!-- transfer2 Modal-->
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
                         <button type="button" class="btn btn-outline-dark rounded-pill"
                             data-bs-target="#addTransferJPModal" data-bs-toggle="modal">ยกเลิก</button>
                         <button type="button" class="btn btn-p1 rounded-pill d-flex align-items-center"
                             data-bs-dismiss="modal"><i class='bx bxs-check-circle me-2'></i>ยืนยัน</button>
                     </div>
                 </div>
             </div>
         </div>
         <!-- confirm Modal -->
         <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel"
             aria-hidden="true">
             <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
                 <div class="modal-content borderR25">
                     <div class="modal-header">
                         <h5 class="modal-title" id="confirmModalLabel">แจงยืนยันสิทธิ</h5>
                         <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                     </div>
                     <div class="modal-body">
                         <div class="cJP1 boxJPC mt-3">
                             <div class="card borderR10 p-2">
                                 <h5 class="text-center">แจงยืนยันสิทธิ</h5>
                                 <div class="row gx-2">
                                     <div class="col-md-6">
                                         <label for="" class="form-label">รหัสสมาชิก <span
                                                 class="text-danger">*</span></label>
                                         <input type="text" class="form-control mb-3" id=""
                                             value="MLM0534768" disabled>
                                     </div>
                                     <div class="col-md-6">
                                         <label for="" class="form-label">ชื่อ-นามสกุล</label>
                                         <input type="text" class="form-control mb-3" id=""
                                             value="ชัยพัทธ์ ศรีสดุดี" disabled>
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
                                         <input type="text" class="form-control" id="" disabled
                                             value="500">
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
                         <button type="button" class="btn btn-outline-dark rounded-pill"
                             data-bs-target="#addClarifyJPModal" data-bs-toggle="modal">ยกเลิก</button>
                         <button type="button" class="btn btn-p1 rounded-pill d-flex align-items-center"
                             data-bs-dismiss="modal"><i class='bx bxs-check-circle me-2'></i>ยืนยัน</button>
                     </div>
                 </div>
             </div>
         </div>
         <!-- disacount Modal -->
         <div class="modal fade" id="discountModal" tabindex="-1" aria-labelledby="discountModalLabel"
             aria-hidden="true">
             <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
                 <div class="modal-content borderR25">
                     <div class="modal-header">
                         <h5 class="modal-title" id="discountModalLabel">แจงรับส่วนลด</h5>
                         <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                     </div>
                     <div class="modal-body">
                         <!--ตัวเลือก2-->
                         <div class="cJP2 boxJPC mt-3">
                             <div class="card borderR10 p-2">
                                 <h5 class="text-center">แจงรับส่วนลด</h5>
                                 <div class="row gx-2">
                                     <div class="col-md-6">
                                         <label for="" class="form-label">รหัสสมาชิก <span
                                                 class="text-danger">*</span></label>
                                         <input type="text" class="form-control mb-3" id=""
                                             value="MLM0534768" disabled>
                                     </div>
                                     <div class="col-md-6">
                                         <label for="" class="form-label">ชื่อ-นามสกุล</label>
                                         <input type="text" class="form-control mb-3" id=""
                                             value="ชัยพัทธ์ ศรีสดุดี" disabled>
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
                                     <p readonly class="form-control-plaintext small text-danger" id="">*** 1JP.
                                         = xxx บาท</p>
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
                         <button type="button" class="btn btn-outline-dark rounded-pill"
                             data-bs-target="#addClarifyJPModal" data-bs-toggle="modal">ยกเลิก</button>
                         <button type="button" class="btn btn-p1 rounded-pill d-flex align-items-center"
                             data-bs-dismiss="modal"><i class='bx bxs-check-circle me-2'></i>ยืนยัน</button>
                     </div>
                 </div>
             </div>
         </div>
     @endsection

     @section('script')
         <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
         <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
         <script>
             $('.page-content').css({
                 'min-height': $(window).height() - $('.navbar').height()
             });
         </script>
         <script>
             $(document).ready(function() {
            $(function() {
                 oTable = $('#workL').DataTable({
                     processing: true,
                     serverSide: true,
                     searching: true,
                     pageLength: 25,
                     ajax: {
                         url: '{{ route('Workline_datatable') }}',
                         data: function(d) {
                            d.lv = '{{$lv}}';
                            d.user_name = '{{$user_name}}';
                             d.startDate = $('#startDate').val();
                             d.endDate = $('#endDate').val();
                         },
                         method: 'get'
                     },


                     columns: [{
                             data: 'status_active',
                             title: '<center>การรักษาสภาพ</center>',
                             className: 'text-center'
                         },

                         {
                             data: 'created_at',
                             title: '<center>วันที่สมัคร</center> ',
                             className: 'text-center'
                         },
                         {
                             data: 'user_name',
                             title: '<center>รหัสสมาชิก</center>',
                             className: 'text-center'
                         },
                         {
                             data: 'name',
                             title: '<center>ชื่อสมาชิก</center>',
                             className: 'text-center'
                         },


                         {
                             data: 'qualification_id',
                             title: '<center>ตำแหน่ง</center>',
                             className: 'text-center'
                         },
                         {
                             data: 'expire_date',
                             title: '<center>วัน Active(วัน)</center>',
                             className: 'text-center'
                         },
                         {
                             data: 'introduce_id',
                             title: '<center>รหัสผู้แนะนำ</center>',
                             className: 'text-center'
                         },
                         {
                             data: 'introduce_name',
                             title: '<center>ชื่อผู้แนะนำ</center>',
                             className: 'text-center'
                         },
                         {
                             data: 'sponsor_lv',
                             title: '<center>ชั้นการแนะนำ<br>(Sponsor)</center>',
                             className: 'text-center'
                         },


                         {
                             data: 'view',
                             title: '<center>ดูสายงาน</center>',
                             className: 'text-center'
                         },
                         {
                             data: 'action',
                             title: '<center>Action</center>',
                             className: 'text-center'
                         },

                     ],
                 });
                 $('.myWhere,.myLike,.myCustom,#onlyTrashed').on('change', function(e) {
                     oTable.draw();
                 });

                 $('#search-form').on('click', function(e) {
                     oTable.draw();
                     e.preventDefault();
                 });
             });

            });

         </script>
     @endsection
