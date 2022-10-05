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
                             <li class="breadcrumb-item active text-truncate" aria-current="page"> เลื่อนตำแหน่ง</li>
                         </ol>
                     </nav>
                 </div>
             </div>
             <div class="row">
                 <div class="col-md-12">
                     <div class="card card-box borderR10 mb-2 mb-md-0">
                         <div class="card-body">
                             <div class="row">
                                 <div class="col-sm-12">
                                     <h4 class="card-title mb-0">การจัดการตำแหน่ง</h4>
                                     <div id="msform">
                                         <ul id="progressbar">
                                             <li class="l1 active">
                                                 <p class="d-none d-md-block">MB</p>
                                                 <button type="button" class="btn btn-position"></button>
                                                 <p class="d-block d-md-none">MB</p>
                                             </li>
                                             <li class="l1">
                                                 <div class="box-choose">
                                                     <ul>
                                                         <li>
                                                             <p class="d-none d-md-block">MO</p>
                                                             <button type="button" class="btn btn-choose"
                                                                 data-bs-toggle="modal" data-bs-target="#MOModal"></button>
                                                             <p class="d-block d-md-none">MO</p>
                                                         </li>
                                                         <li>
                                                             <p class="d-none d-md-block">VIP</p>
                                                             <button type="button" class="btn btn-choose"
                                                                 data-bs-toggle="modal" data-bs-target="#VIPModal"></button>
                                                             <p class="d-block d-md-none">VIP</p>
                                                         </li>
                                                         <li>
                                                             <p class="d-none d-md-block">VVIP</p>
                                                             <button type="button" class="btn btn-choose"
                                                                 data-bs-toggle="modal"
                                                                 data-bs-target="#VVIPModal"></button>
                                                             <p class="d-block d-md-none">VVIP</p>
                                                         </li>
                                                     </ul>
                                                     <p class="text-center mb-0 textChooseS">เลือกขนาดธุรกิจ</p>
                                                 </div>
                                             </li>
                                             <li class="l1">
                                                 <p class="d-none d-md-block">SVVIP</p>
                                                 <button type="button" class="btn btn-position" data-bs-toggle="modal"
                                                     data-bs-target="#SVVIPModal"></button>
                                                 <p class="d-block d-md-none">SVVIP</p>
                                             </li>
                                             <li class="l1">
                                                 <p class="d-none d-md-block">MG</p>
                                                 <button type="button" class="btn btn-position" data-bs-toggle="modal"
                                                     data-bs-target="#SVVIPModal"></button>
                                                 <p class="d-block d-md-none">MG</p>
                                             </li>
                                             <li class="l1">
                                                 <p class="d-none d-md-block">MR</p>
                                                 <button type="button" class="btn btn-position" data-bs-toggle="modal"
                                                     data-bs-target="#SVVIPModal"></button>
                                                 <p class="d-block d-md-none">MR</p>
                                             </li>
                                             <li class="l1">
                                                 <p class="d-none d-md-block">ME</p>
                                                 <button type="button" class="btn btn-position" data-bs-toggle="modal"
                                                     data-bs-target="#SVVIPModal"></button>
                                                 <p class="d-block d-md-none">ME</p>
                                             </li>
                                             <li class="l1">
                                                 <p class="d-none d-md-block">MD</p>
                                                 <button type="button" class="btn btn-position" data-bs-toggle="modal"
                                                     data-bs-target="#SVVIPModal"></button>
                                                 <p class="d-block d-md-none">MD</p>
                                             </li>
                                         </ul>
                                     </div>
                                 </div>
                             </div>
                         </div>
                     </div>
                 </div>
             </div>
         </div>
     </div>



     <!-- MO Modal -->
     <div class="modal fade" id="MOModal" tabindex="-1" aria-labelledby="MOModalLabel" aria-hidden="true">
         <div class="modal-dialog">
             <div class="modal-content borderR25">
                 <div class="modal-header">
                     <h5 class="modal-title" id="MOModalLabel">เลือกไลฟ์สไตล์ สู่ความสำเร็จ</h5>
                     <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                 </div>
                 <div class="modal-body">
                     <div class="text-center">
                         <img src="{{ asset('frontend/images/man_2.png') }}  " class="mw-100 mb-2" width="120px">
                         <p>แพ็คเกจผู้นำตำแหน่ง MO</p>
                     </div>
                     <div class="row">
                         <div class="col-sm-6 border-end">
                             <h6>เงื่อนไขตำแหน่ง MO</h6>
                             <ol>
                                 <li>แจงPoint 400 JP</li>
                             </ol>
                         </div>
                         <div class="col-sm-6">
                             <h6>สิทธิที่ได้รับ</h6>
                             <ol>
                                 <li>ประกันอุบัติเหตุ วงเงิน 100,000 บาท</li>
                                 <li>โบนัสMatching 2 ชั้น</li>
                                 <li>สิทธิการให้ความช่วยเหลือ 4 ด้าน</li>
                             </ol>
                         </div>
                     </div>
                 </div>
                 <div class="modal-footer justify-content-center">
                     <button type="button" class="btn btn-p1 bg-opacity-100 rounded-pill" data-bs-toggle="modal"
                         data-bs-target="#MO2Modal">ยืนยันสิทธิ</button>
                 </div>
             </div>
         </div>
     </div>
     <!-- MO2 Modal -->
     <div class="modal fade" id="MO2Modal" tabindex="-1" aria-labelledby="MO2ModalLabel" aria-hidden="true">
         <div class="modal-dialog">
             <div class="modal-content borderR25">
                 <div class="modal-header justify-content-center">
                     <h5 class="modal-title" id="MO2ModalLabel">แพ็คเกจ ผู้นำตำแหน่ง MO</h5>
                 </div>
                 <div class="modal-body">
                     <div class="card borderR10 p-2">
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
                                 <p readonly class="form-control-plaintext" id="">MO</p>
                             </div>
                             <label for="" class="col-sm-4 col-form-label fw-bold">จำนวน</label>
                             <div class="col-sm-4">
                                 <p readonly class="form-control-plaintext text-p1 h5" id="">400</p>
                             </div>
                             <div class="col-sm-4">
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
                     <div class="alert alert-danger d-flex mt-2" role="alert">
                         <i class='bx bxs-error me-2 bx-sm'></i>
                         <div>
                             กรุณาแคปหน้าจอการทำรายการเพื่อใช้ตรวจสิบกรณีมีปัญหาในการทำรายการ
                         </div>
                     </div>
                 </div>
                 <div class="modal-footer justify-content-between">
                     <button type="button" class="btn btn-outline-dark rounded-pill" data-bs-toggle="modal"
                         data-bs-target="#MOModal">ยกเลิก</button>
                     <button type="button" class="btn btn-p1 bg-opacity-100 rounded-pill"
                         data-bs-dismiss="modal">ยืนยัน</button>
                 </div>
             </div>
         </div>
     </div>
     <!-- VIP Modal -->
     <div class="modal fade" id="VIPModal" tabindex="-1" aria-labelledby="VIPModalLabel" aria-hidden="true">
         <div class="modal-dialog">
             <div class="modal-content borderR25">
                 <div class="modal-header">
                     <h5 class="modal-title" id="VIPModalLabel">เลือกไลฟ์สไตล์ สู่ความสำเร็จ</h5>
                     <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                 </div>
                 <div class="modal-body">
                     <div class="text-center">
                         <img src="{{ asset('frontend/images/man_2.png') }}" class="mw-100 mb-2" width="120px">
                         <p>แพ็คเกจผู้นำตำแหน่ง VIP</p>
                     </div>
                     <div class="row">
                         <div class="col-sm-6 border-end">
                             <h6>เงื่อนไขตำแหน่ง VIP</h6>
                             <ol>
                                 <li>แจงPoint 800 JP</li>
                             </ol>
                         </div>
                         <div class="col-sm-6">
                             <h6>สิทธิที่ได้รับ</h6>
                             <ol>
                                 <li>ประกันอุบัติเหตุ วงเงิน 200,000 บาท</li>
                                 <li>โบนัสMatching 3 ชั้น</li>
                                 <li>สิทธิการให้ความช่วยเหลือ 4 ด้าน</li>
                             </ol>
                         </div>
                     </div>
                 </div>
                 <div class="modal-footer justify-content-center">
                     <button type="button" class="btn btn-p1 bg-opacity-100 rounded-pill" data-bs-toggle="modal"
                         data-bs-target="#VIP2Modal">ยืนยันสิทธิ</button>
                 </div>
             </div>
         </div>
     </div>
     <!-- VIP2 Modal -->
     <div class="modal fade" id="VIP2Modal" tabindex="-1" aria-labelledby="VIP2ModalLabel" aria-hidden="true">
         <div class="modal-dialog">
             <div class="modal-content borderR25">
                 <div class="modal-header justify-content-center">
                     <h5 class="modal-title" id="VIP2ModalLabel">แพ็คเกจ ผู้นำตำแหน่ง VIP.</h5>
                 </div>
                 <div class="modal-body">
                     <div class="card borderR10 p-2">
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
                             <div class="col-sm-4">
                                 <p readonly class="form-control-plaintext text-p1 h5" id="">800</p>
                             </div>
                             <div class="col-sm-4">
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
                     <div class="alert alert-danger d-flex mt-2" role="alert">
                         <i class='bx bxs-error me-2 bx-sm'></i>
                         <div>
                             กรุณาแคปหน้าจอการทำรายการเพื่อใช้ตรวจสิบกรณีมีปัญหาในการทำรายการ
                         </div>
                     </div>
                 </div>
                 <div class="modal-footer">
                     <button type="button" class="btn btn-outline-dark rounded-pill" data-bs-toggle="modal"
                         data-bs-target="#VIPModal">ยกเลิก</button>
                     <button type="button" class="btn btn-p1 bg-opacity-100 rounded-pill"
                         data-bs-dismiss="modal">ยืนยัน</button>
                 </div>
             </div>
         </div>
     </div>
     <!-- VVIP Modal -->
     <div class="modal fade" id="VVIPModal" tabindex="-1" aria-labelledby="VVIPModalLabel" aria-hidden="true">
         <div class="modal-dialog">
             <div class="modal-content borderR25">
                 <div class="modal-header">
                     <h5 class="modal-title" id="VVIPModalLabel">เลือกไลฟ์สไตล์ สู่ความสำเร็จ</h5>
                     <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                 </div>
                 <div class="modal-body">
                     <div class="text-center">
                         <img src=" {{ asset('frontend/images/man_2.png') }}" class="mw-100 mb-2" width="120px">
                         <p>แพ็คเกจผู้นำตำแหน่ง VVIP</p>
                     </div>
                     <div class="row">
                         <div class="col-sm-6 border-end">
                             <h6>เงื่อนไขตำแหน่ง VVIP</h6>
                             <ol>
                                 <li>แจงPoint 1,200 JP</li>
                             </ol>
                         </div>
                         <div class="col-sm-6">
                             <h6>สิทธิที่ได้รับ</h6>
                             <ol>
                                 <li>ประกันอุบัติเหตุ วงเงิน 300,000 บาท</li>
                                 <li>โบนัสMatching 4 ชั้น</li>
                                 <li>สิทธิการให้ความช่วยเหลือ 4 ด้าน</li>
                             </ol>
                         </div>
                     </div>
                 </div>
                 <div class="modal-footer justify-content-center">
                     <button type="button" class="btn btn-p1 bg-opacity-100 rounded-pill" data-bs-toggle="modal"
                         data-bs-target="#VVIP2Modal">ยืนยันสิทธิ</button>
                 </div>
             </div>
         </div>
     </div>
     <!-- VVIP2 Modal -->
     <div class="modal fade" id="VVIP2Modal" tabindex="-1" aria-labelledby="VVIP2ModalLabel" aria-hidden="true">
         <div class="modal-dialog">
             <div class="modal-content borderR25">
                 <div class="modal-header justify-content-center">
                     <h5 class="modal-title" id="VVIP2ModalLabel">แพ็คเกจ ผู้นำตำแหน่ง VVIP.</h5>
                 </div>
                 <div class="modal-body">
                     <div class="card borderR10 p-2">
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
                                 <p readonly class="form-control-plaintext" id="">VVIP</p>
                             </div>
                             <label for="" class="col-sm-4 col-form-label fw-bold">จำนวน</label>
                             <div class="col-sm-4">
                                 <p readonly class="form-control-plaintext text-p1 h5" id="">1,200</p>
                             </div>
                             <div class="col-sm-4">
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
                     <div class="alert alert-danger d-flex mt-2" role="alert">
                         <i class='bx bxs-error me-2 bx-sm'></i>
                         <div>
                             กรุณาแคปหน้าจอการทำรายการเพื่อใช้ตรวจสิบกรณีมีปัญหาในการทำรายการ
                         </div>
                     </div>
                 </div>
                 <div class="modal-footer">
                     <button type="button" class="btn btn-outline-dark rounded-pill" data-bs-toggle="modal"
                         data-bs-target="#VVIPModal">ยกเลิก</button>
                     <button type="button" class="btn btn-p1 bg-opacity-100 rounded-pill"
                         data-bs-dismiss="modal">ยืนยัน</button>
                 </div>
             </div>
         </div>
     </div>
     <!-- SVVIP Modal -->
     <div class="modal fade" id="SVVIPModal" tabindex="-1" aria-labelledby="SVVIPModalLabel" aria-hidden="true">
         <div class="modal-dialog modal-lg">
             <div class="modal-content borderR25">
                 <div class="modal-header">
                     <h5 class="modal-title" id="SVVIPModalLabel">ทำคุณสมบัติขึ้นตำแหน่ง SVVIP</h5>
                     <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                 </div>
                 <div class="modal-body">
                     <div class="text-center">
                         <img src="{{ asset('frontend/images/man_2.png') }}" class="mw-100 mb-2" width="120px">
                     </div>
                     <div class="row">
                         <div class="col-sm-6 border-end">
                             <p>ขณะนี้ท่านอยู่ในตำแหน่ง <span class="text-p1">..(ตำแหน่งปัจจุบัน)..</span></p>
                             <h6>เงื่อนไขตามตำแหน่ง SVVIP.</h6>
                             <ol>
                                 <li>แนะนำตรง VVIP 20 รหัส</li>
                                 <li>มีรายได้ทุกด้านรวมสะสม 150,000 บาท</li>
                             </ol>
                         </div>
                         <div class="col-sm-6">
                             <h5>ทำคุณสมบัติขึ้นตำแหน่ง SVVIP.</h5>
                             <h6>คุณสมบัติที่ต้องทำเพิ่ม</h6>
                             <ol>
                                 <li>แนะนำตรง VVIP เพิ่ม <b>777</b> รหัส</li>
                                 <li>สร้างรายได้ทุกด้านรวมเพิ่ม <b>33333333</b> บาท</li>
                             </ol>
                         </div>
                     </div>
                 </div>
                 <div class="modal-footer justify-content-center">
                     <button type="button" class="btn btn-p1 bg-opacity-100 rounded-pill" data-bs-toggle="modal"
                         data-bs-target="#SVVIP2Modal">ยืนยันสิทธิ</button>
                 </div>
             </div>
         </div>
     </div>
     <!-- SVVIP2 Modal -->
     <div class="modal fade" id="SVVIP2Modal" tabindex="-1" aria-labelledby="SVVIP2ModalLabel" aria-hidden="true">
         <div class="modal-dialog">
             <div class="modal-content borderR25">
                 <div class="modal-header justify-content-center">
                     <h5 class="modal-title" id="SVVIP2ModalLabel">แพ็คเกจ ผู้นำตำแหน่ง SVVIP.</h5>
                 </div>
                 <div class="modal-body">
                     <div class="card borderR10 p-2">
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
                                 <p readonly class="form-control-plaintext" id="">SVVIP</p>
                             </div>
                             <label for="" class="col-sm-4 col-form-label fw-bold">จำนวน</label>
                             <div class="col-sm-4">
                                 <p readonly class="form-control-plaintext text-p1 h5" id="">1,200</p>
                             </div>
                             <div class="col-sm-4">
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
                     <div class="alert alert-danger d-flex mt-2" role="alert">
                         <i class='bx bxs-error me-2 bx-sm'></i>
                         <div>
                             กรุณาแคปหน้าจอการทำรายการเพื่อใช้ตรวจสิบกรณีมีปัญหาในการทำรายการ
                         </div>
                     </div>
                 </div>
                 <div class="modal-footer">
                     <button type="button" class="btn btn-outline-dark rounded-pill" data-bs-toggle="modal"
                         data-bs-target="#SVVIPModal">ยกเลิก</button>
                     <button type="button" class="btn btn-p1 bg-opacity-100 rounded-pill"
                         data-bs-dismiss="modal">ยืนยัน</button>
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
 @endsection
