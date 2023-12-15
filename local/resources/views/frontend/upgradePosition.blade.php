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
                                     <h4 class="card-title">การจัดการตำแหน่ง ({{ Auth::guard('c_user')->user()->qualification_id }} )</h4>
                                     <h5 class="card-title mb-5">คุณมีโบนัสสะสม : {{ number_format(Auth::guard('c_user')->user()->bonus_total) }} บาท</h5>
                                     <div id="msform">
                                         <ul id="progressbar">
                                             <li class="l1 active">
                                                 <p class="d-none d-md-block">MB</p>
                                                 <button type="button" class="btn btn-position" ></button>
                                                 <p class="d-block d-md-none">MB</p>
                                             </li>
                                             <li class="l1">
                                                 <div class="box-choose">
                                                     <ul>
                                                         <li>
                                                             <p class="d-none d-md-block">MO</p>
                                                             <button type="button" class="btn btn-choose"  @if( $data_user->id  >= 2 ) @else style="background-color: #313131" @endif
                                                                 data-bs-toggle="modal" data-bs-target="#MOModal"></button>
                                                             <p class="d-block d-md-none">MO</p>
                                                         </li>
                                                         <li>
                                                             <p class="d-none d-md-block">VIP</p>
                                                             <button type="button" class="btn btn-choose"  @if(  $data_user->id  >= 3) @else style="background-color: #313131" @endif
                                                                 data-bs-toggle="modal" data-bs-target="#VIPModal"></button>
                                                             <p class="d-block d-md-none">VIP</p>
                                                         </li>
                                                         <li>
                                                             <p class="d-none d-md-block">VVIP</p>
                                                             <button type="button" class="btn btn-choose"  @if(  $data_user->id  >= 4 ) @else style="background-color: #313131" @endif
                                                                 data-bs-toggle="modal"
                                                                 data-bs-target="#VVIPModal"></button>
                                                             <p class="d-block d-md-none">VVIP</p>
                                                         </li>
                                                     </ul>
                                                     <p class="text-center mb-0 textChooseS">เลือกขนาดธุรกิจ</p>
                                                 </div>
                                             </li>
                                             <li class="l1">
                                                 <p class="d-none d-md-block">XVVIP</p>
                                                 <button type="button" class="btn btn-position" @if(  $data_user->id  >= 5) @else style="background-color: #313131" @endif data-bs-toggle="modal"
                                                     data-bs-target="#XVVIPModal"></button>
                                                 <p class="d-block d-md-none">XVVIP</p>
                                             </li>
                                             <li class="l1">
                                                <p class="d-none d-md-block">SVVIP</p>
                                                <button type="button" class="btn btn-position" @if(  $data_user->id  >= 6) @else style="background-color: #313131" @endif data-bs-toggle="modal"
                                                    data-bs-target="#SVVIPModal"></button>
                                                <p class="d-block d-md-none">SVVIP</p>
                                            </li>
                                             <li class="l1">
                                                 <p class="d-none d-md-block">MG</p>
                                                 <button type="button" class="btn btn-position" @if(  $data_user->id  >= 7) @else style="background-color: #313131" @endif data-bs-toggle="modal"
                                                     data-bs-target="#MGModal"></button>
                                                 <p class="d-block d-md-none">MG</p>
                                             </li>
                                             <li class="l1">
                                                 <p class="d-none d-md-block">MR</p>
                                                 <button type="button" class="btn btn-position" @if(  $data_user->id  >= 8) @else style="background-color: #313131" @endif data-bs-toggle="modal"
                                                     data-bs-target="#MRModal"></button>
                                                 <p class="d-block d-md-none">MR</p>
                                             </li>
                                             <li class="l1">
                                                 <p class="d-none d-md-block">ME</p>
                                                 <button type="button" class="btn btn-position" @if( $data_user->id  >= 9) @else style="background-color: #313131" @endif data-bs-toggle="modal"
                                                     data-bs-target="#MEModal"></button>
                                                 <p class="d-block d-md-none">ME</p>
                                             </li>
                                             <li class="l1">
                                                 <p class="d-none d-md-block">MD</p>
                                                 <button type="button" class="btn btn-position" @if( $data_user->id  >= 10) @else style="background-color: #313131" @endif  style="background-color: #313131" data-bs-toggle="modal"
                                                     data-bs-target="#MDModal"></button>
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
                                 <li>แจงPoint 400 PV</li>
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
                    @if( $data_user->id  >= 2 )
                    <button class="btn btn-p1 bg-opacity-100 rounded-pill" data-bs-dismiss="modal" >สำเร็จ</button>
                    @else
                    <a href="{{route('jp_clarify')}}"  class="btn btn-p1 bg-opacity-100 rounded-pill" >แจงอัพตำแหน่ง</a>
                    @endif

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
                                 <p readonly class="form-control-plaintext" id="">PV.</p>
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
                                 <li>แจงPoint 800 PV</li>
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
                    @if( $data_user->id  >= 3 )
                    <button class="btn btn-p1 bg-opacity-100 rounded-pill" data-bs-dismiss="modal" >สำเร็จ</button>
                    @else
                    <a href="{{route('jp_clarify')}}"  class="btn btn-p1 bg-opacity-100 rounded-pill" >แจงอัพตำแหน่ง</a>
                    @endif
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
                                 <p readonly class="form-control-plaintext" id="">PV.</p>
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
                                 <li>แจงPoint 1,200 PV</li>
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
                    @if( $data_user->id  >= 4 )
                    <button class="btn btn-p1 bg-opacity-100 rounded-pill" data-bs-dismiss="modal" >สำเร็จ</button>
                    @else
                    <a href="{{route('jp_clarify')}}"  class="btn btn-p1 bg-opacity-100 rounded-pill" >แจงอัพตำแหน่ง</a>
                    @endif
                 </div>
             </div>
         </div>
     </div>

          <!-- XVVIP Modal -->
          <div class="modal fade" id="XVVIPModal" tabindex="-1" aria-labelledby="XVVIPModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content borderR25">
                    <div class="modal-header">
                        <h5 class="modal-title" id="XVVIPModalLabel">ทำคุณสมบัติขึ้นตำแหน่ง XVVIP</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="text-center">
                            <img src="{{ asset('frontend/images/man_2.png') }}" class="mw-100 mb-2" width="120px">
                        </div>
                        <div class="row">
                            <div class="col-sm-6 border-end">
                                <p>ขณะนี้ท่านอยู่ในตำแหน่ง <span class="text-p1"> ({{ Auth::guard('c_user')->user()->qualification_id }}) </span></p>
                                <h6>เงื่อนไขตามตำแหน่ง XVVIP.</h6>
                                <ol>
                                    <li>แนะนำตรง VVIP.(แบบทันทีและแจงอัพตำแหน่ง 1,200 PV) 2 รหัส
                                        หรือ PV.สะสมจากโบนัสข้อที่ 4 2,400 PV.
                                        </li>

                                </ol>
                            </div>
                            <div class="col-sm-6">
                                <h5>ทำคุณสมบัติขึ้นตำแหน่ง XVVIP.</h5>
                                <h6>คุณสมบัติที่ต้องทำเพิ่ม</h6>
                                <ol>
                                    <?php
                                    $user_name = Auth::guard('c_user')->user()->user_name;

                                    $vvip = DB::table('customers')
                                    ->where('introduce_id', $user_name)
                                    ->wherein('qualification_id',['VVIP','SVVIP','MG','MR','ME','MD'])
                                    ->count();


                                    $VVIP_1200 = DB::table('report_bonus_register_xvvip')
                                    ->where('introduce_id', $user_name)
                                    ->count();

                                    $rs_xvvip1200 = $VVIP_1200*2400;

                                    $count_xvvip_1200 = 2400 - $rs_xvvip1200 ;

                                    if($count_xvvip_1200 < 0 ){
                                        $count_xvvip_1200 = 0;
                                    }

                                    $count_vvip = 2 - $vvip;
                                    if($count_vvip < 0){
                                        $count_vvip = 0;
                                    }


                                    ?>
                                    <li>แนะนำตรง VVIP เพิ่ม <b> {{ $vvip }} </b> รหัส
                                    <br>
                                    หรือ PV.สะสมจากโบนัสข้อที่ 4  <b>{{$count_xvvip_1200}}</b> PV
                                    </li>


                                </ol>
                            </div>
                        </div>
                    </div>
                   <div class="modal-footer justify-content-center">
                    @if( $data_user->id  >= 5 )
                    <button class="btn btn-p1 bg-opacity-100 rounded-pill" data-bs-dismiss="modal" >สำเร็จ</button>
                    @else
                    <a href="{{route('register')}}"  class="btn btn-p1 bg-opacity-100 rounded-pill" >สมัครสมาชิก</a>
                    @endif
                    </div>
                </div>
            </div>
        </div>

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
                                <p>ขณะนี้ท่านอยู่ในตำแหน่ง <span class="text-p1">({{ Auth::guard('c_user')->user()->qualification_id }})</span></p>
                                <h6>เงื่อนไขตามตำแหน่ง SVVIP.</h6>
                                <ol>
                                   <li>สะสมจากโบนัสข้อที่ 4 48,000 PV.
                                       </li>
                                       <li>มีรายได้ทุกด้านสะสมรวม 100,000 บาท</li>

                               </ol>
                           </div>
                           <div class="col-sm-6">
                               <h5>ทำคุณสมบัติขึ้นตำแหน่ง SVVIP.</h5>
                               <h6>คุณสมบัติที่ต้องทำเพิ่ม</h6>
                               <ol>
                                   <?php


                                   $rs_xvvip1200 = $VVIP_1200*2400;

                                   $count_svvip_4800 = 4800 - $rs_xvvip1200 ;

                                   if($count_svvip_4800 < 0 ){
                                       $count_svvip_4800 = 0;
                                   }


                                   $bonus_total = 100000 - Auth::guard('c_user')->user()->bonus_total;
                                   if($bonus_total < 0){
                                       $bonus_total = 0;
                                   }


                                   ?>
                                   <li>PV.สะสมจากโบนัสข้อที่ 4 เพิ่ม  <b>{{number_format($count_svvip_4800)}}</b> PV </li>
                                   <li>สร้างรายได้ทุกด้านสะสมเพิ่ม <b> {{ number_format($bonus_total) }} </b> บาท</li>

                               </ol>
                           </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-center">
                       <div class="modal-footer justify-content-center">
                           @if( $data_user->id  >= 6 )
                           <button class="btn btn-p1 bg-opacity-100 rounded-pill" data-bs-dismiss="modal" >สำเร็จ</button>
                           @else
                           <a href="{{route('register')}}"  class="btn btn-p1 bg-opacity-100 rounded-pill" >สมัครสมาชิก</a>
                           @endif
                           </div>
                    </div>
                </div>
            </div>
        </div>

     <!-- SVVIP2 Modal -->
     <div class="modal fade" id="MGModal" tabindex="-1" aria-labelledby="MGModalModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content borderR25">
                <div class="modal-header">
                    <h5 class="modal-title" id="MGModalModalLabel">ทำคุณสมบัติขึ้นตำแหน่ง MG </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        <img src="{{ asset('frontend/images/man_2.png') }}" class="mw-100 mb-2" width="120px">
                    </div>
                    <div class="row">
                        <div class="col-sm-6 border-end">
                            <p>ขณะนี้ท่านอยู่ในตำแหน่ง <span class="text-p1">({{ Auth::guard('c_user')->user()->qualification_id }})</span></p>
                            <h6>เงื่อนไขตามตำแหน่ง MG.</h6>
                            <ol>
                               <li>สะสมจากโบนัสข้อที่ 4 72,000 PV.</li>
                               <li>แนะนำตรง SVVIP 3 รหัส</li>
                                <li>มีรายได้ทุกด้านสะสมรวม 400,000 บาท</li>

                           </ol>
                       </div>
                       <div class="col-sm-6">
                           <h5>ทำคุณสมบัติขึ้นตำแหน่ง MG.</h5>
                           <h6>คุณสมบัติที่ต้องทำเพิ่ม</h6>
                           <ol>
                               <?php


                               $rs_xvvip_mg1200 = $VVIP_1200*2400;

                               $count_mg_72000 = 72000 - $rs_xvvip_mg1200 ;

                               if($count_mg_72000 < 0 ){
                                   $count_mg_72000 = 0;
                               }


                               $bonus_total = 400000 - Auth::guard('c_user')->user()->bonus_total;
                               if($bonus_total < 0){
                                   $bonus_total = 0;
                               }


                               $SVVIP = DB::table('customers')
                                    ->where('introduce_id', $user_name)
                                    ->wherein('qualification_id',['SVVIP','MG','MR','ME','MD'])
                                    ->count();

                                    $count_svvip = 3-$SVVIP;

                                if($count_svvip < 0){
                                   $count_svvip = 0;
                                }

                               ?>


                               <li>PV.สะสมจากโบนัสข้อที่ 4 เพิ่ม <b>{{number_format($count_mg_72000)}}</b> PV </li>
                               <li>แนะนำตรง SVVIP เพิ่ม <b>{{ number_format($count_svvip) }}</b> รหัส</li>
                               <li>สร้างรายได้ทุกด้านสะสมเพิ่ม <b> {{ number_format($bonus_total) }} </b> บาท</li>

                           </ol>
                       </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-center">
                   <div class="modal-footer justify-content-center">
                       @if( $data_user->id  >= 7 )
                       <button class="btn btn-p1 bg-opacity-100 rounded-pill" data-bs-dismiss="modal" >สำเร็จ</button>
                       @else
                       <a href="{{route('register')}}"  class="btn btn-p1 bg-opacity-100 rounded-pill" >สมัครสมาชิก</a>
                       @endif
                       </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="MRModal" tabindex="-1" aria-labelledby="MRModalModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content borderR25">
                <div class="modal-header">
                    <h5 class="modal-title" id="MRModalModalLabel">ทำคุณสมบัติขึ้นตำแหน่ง MR  </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        <img src="{{ asset('frontend/images/man_2.png') }}" class="mw-100 mb-2" width="120px">
                    </div>
                    <div class="row">
                        <div class="col-sm-6 border-end">
                            <p>ขณะนี้ท่านอยู่ในตำแหน่ง <span class="text-p1">({{ Auth::guard('c_user')->user()->qualification_id }})</span></p>
                            <h6>เงื่อนไขตามตำแหน่ง MR .</h6>
                            <ol>
                               <li>สะสมจากโบนัสข้อที่ 4 120,000 PV.</li>
                               <li>แนะนำตรง SVVIP 7 รหัส</li>
                                <li>มีรายได้ทุกด้านสะสมรวม 1,000,000 บาท</li>

                           </ol>
                       </div>
                       <div class="col-sm-6">
                           <h5>ทำคุณสมบัติขึ้นตำแหน่ง MR .</h5>
                           <h6>คุณสมบัติที่ต้องทำเพิ่ม</h6>
                           <ol>
                               <?php


                               $rs_xvvip_mr1200 = $VVIP_1200*2400;

                               $count_mr_120000 = 1000000 - $rs_xvvip_mr1200 ;

                               if($count_mr_120000 < 0 ){
                                   $count_mr_120000 = 0;
                               }


                               $bonus_total = 1000000 - Auth::guard('c_user')->user()->bonus_total;
                               if($bonus_total < 0){
                                   $bonus_total = 0;
                               }




                                    $count_svvip = 7-$SVVIP;

                                if($count_svvip < 0){
                                   $count_svvip = 0;
                                }


                               ?>


                               <li>PV.สะสมจากโบนัสข้อที่ 4 เพิ่ม <b>{{number_format($count_mg_72000)}}</b> PV </li>
                               <li>แนะนำตรง SVVIP เพิ่ม <b>{{ number_format($count_svvip) }}</b> รหัส</li>
                               <li>สร้างรายได้ทุกด้านสะสมเพิ่ม <b> {{ number_format($bonus_total) }} </b> บาท</li>

                           </ol>
                       </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-center">
                   <div class="modal-footer justify-content-center">
                       @if( $data_user->id  >= 7 )
                       <button class="btn btn-p1 bg-opacity-100 rounded-pill" data-bs-dismiss="modal" >สำเร็จ</button>
                       @else
                       <a href="{{route('register')}}"  class="btn btn-p1 bg-opacity-100 rounded-pill" >สมัครสมาชิก</a>
                       @endif
                       </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="MEModal" tabindex="-1" aria-labelledby="MEModalModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content borderR25">
                <div class="modal-header">
                    <h5 class="modal-title" id="MEModalModalLabel">ทำคุณสมบัติขึ้นตำแหน่ง ME  </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        <img src="{{ asset('frontend/images/man_2.png') }}" class="mw-100 mb-2" width="120px">
                    </div>
                    <div class="row">
                        <div class="col-sm-6 border-end">
                            <p>ขณะนี้ท่านอยู่ในตำแหน่ง <span class="text-p1">({{ Auth::guard('c_user')->user()->qualification_id }})</span></p>
                            <h6>เงื่อนไขตามตำแหน่ง ME .</h6>
                            <ol>
                               <li>สะสมจากโบนัสข้อที่ 4 180,000 PV.</li>
                               <li>แนะนำตรง SVVIP 13 รหัส</li>
                                <li>มีรายได้ทุกด้านสะสมรวม 2,000,000 บาท</li>

                           </ol>
                       </div>
                       <div class="col-sm-6">
                           <h5>ทำคุณสมบัติขึ้นตำแหน่ง ME .</h5>
                           <h6>คุณสมบัติที่ต้องทำเพิ่ม</h6>
                           <ol>
                               <?php


                               $rs_xvvip_me1200 = $VVIP_1200*2400;

                               $count_me_2000000 = 2000000 - $rs_xvvip_me1200 ;

                               if($count_me_2000000 < 0 ){
                                   $count_me_2000000 = 0;
                               }


                               $bonus_total = 2000000 - Auth::guard('c_user')->user()->bonus_total;
                               if($bonus_total < 0){
                                   $bonus_total = 0;
                               }




                                    $count_svvip = 13-$SVVIP;

                                if($count_svvip < 0){
                                   $count_svvip = 0;
                                }


                               ?>


                               <li>PV.สะสมจากโบนัสข้อที่ 4 เพิ่ม  <b>{{number_format($count_me_2000000)}}</b> PV </li>
                               <li>แนะนำตรง SVVIP เพิ่ม <b>{{ number_format($count_svvip) }}</b> รหัส</li>
                               <li>สร้างรายได้ทุกด้านสะสมเพิ่ม <b> {{ number_format($bonus_total) }} </b> บาท</li>

                           </ol>
                       </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-center">
                   <div class="modal-footer justify-content-center">
                       @if( $data_user->id  >= 8 )
                       <button class="btn btn-p1 bg-opacity-100 rounded-pill" data-bs-dismiss="modal" >สำเร็จ</button>
                       @else
                       <a href="{{route('register')}}"  class="btn btn-p1 bg-opacity-100 rounded-pill" >สมัครสมาชิก</a>
                       @endif
                       </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="MDModal" tabindex="-1" aria-labelledby="MDModalModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content borderR25">
                <div class="modal-header">
                    <h5 class="modal-title" id="MDModalModalLabel">ทำคุณสมบัติขึ้นตำแหน่ง MD  </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        <img src="{{ asset('frontend/images/man_2.png') }}" class="mw-100 mb-2" width="120px">
                    </div>
                    <div class="row">
                        <div class="col-sm-6 border-end">
                            <p>ขณะนี้ท่านอยู่ในตำแหน่ง <span class="text-p1">({{ Auth::guard('c_user')->user()->qualification_id }})</span></p>
                            <h6>เงื่อนไขตามตำแหน่ง MD .</h6>
                            <ol>
                               <li>สะสมจากโบนัสข้อที่ 4 180,000 PV.</li>
                               <li>แนะนำตรง SVVIP 21 รหัส</li>
                                <li>มีรายได้ทุกด้านสะสมรวม 3,000,000 บาท</li>

                           </ol>
                       </div>
                       <div class="col-sm-6">
                           <h5>ทำคุณสมบัติขึ้นตำแหน่ง MD .</h5>
                           <h6>คุณสมบัติที่ต้องทำเพิ่ม</h6>
                           <ol>
                               <?php


                               $rs_xvvip_md1200 = $VVIP_1200*2400;

                               $count_md_3000000 = 3000000 - $rs_xvvip_me1200 ;

                               if($count_md_3000000 < 0 ){
                                   $count_md_3000000 = 0;
                               }


                               $bonus_total = 3000000 - Auth::guard('c_user')->user()->bonus_total;
                               if($bonus_total < 0){
                                   $bonus_total = 0;
                               }



                                    $count_svvip = 21-$SVVIP;

                                if($count_svvip < 0){
                                   $count_svvip = 0;
                                }


                               ?>


                               <li>PV.สะสมจากโบนัสข้อที่ 4 เพิ่ม  <b>{{number_format($count_md_3000000)}}</b> PV </li>
                               <li>แนะนำตรง SVVIP เพิ่ม <b>{{ number_format($count_svvip) }}</b> รหัส</li>
                               <li>สร้างรายได้ทุกด้านสะสมเพิ่ม <b> {{ number_format($bonus_total) }} </b> บาท</li>

                           </ol>
                       </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-center">
                   <div class="modal-footer justify-content-center">
                       @if( $data_user->id  >= 9 )
                       <button class="btn btn-p1 bg-opacity-100 rounded-pill" data-bs-dismiss="modal" >สำเร็จ</button>
                       @else
                       <a href="{{route('register')}}"  class="btn btn-p1 bg-opacity-100 rounded-pill" >สมัครสมาชิก</a>
                       @endif
                       </div>
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
