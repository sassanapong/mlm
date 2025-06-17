@extends('layouts.frontend.app')
@section('css')
    <link href='https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css' rel='stylesheet'>
@endsection
@section('conten')
    <title>บริษัท มารวยด้วยกัน จำกัด</title>

    <div class="bg-whiteLight page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">หน้าแรก</a></li>
                            <li class="breadcrumb-item active text-truncate" aria-current="page">แจง PV</li>
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
                                <div class="col-lg-3">
                                    <label for="" class="form-label">ประเภทการแจง JP.</label>
                                    <select class="form-select" id="">
                                        <option>ทั้งหมด</option>
                                        <option>แจงยืนยันสิทธิ</option>
                                        <option>แจง Cashback หรือส่วนลดซื้อซ้ำ</option>
                                        <option>แจงปรับตำแหน่งให้สูงขึ้น</option>
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
                    <?php
                      $current_time = date('H:i');
                    ?>

                     {{-- @if(1) --}}
                    @if($current_time >= '00:00' && $current_time <= '06:00') 
                     <p> ระบบคำนวณ PV จะปิดให้ใช้งานในเวลา 00:00 - 06:00  </p> - 
                    @else 
                    @if(Auth::guard('c_user')->user()->qualification_id != 'MC')
                    <button type="button" class="btn btn-p1 rounded-pill mb-3" data-bs-toggle="modal"
                    data-bs-target="#addClarifyJPModal"><i class='bx bx-plus me-1'></i> ทำรายการแจง PV.</button>

                    <button type="button" class="btn btn-p1 rounded-pill mb-3" data-bs-toggle="modal"
                    data-bs-target="#addTransferJPModal"><i class='bx bx-plus me-1'></i> ทำรายการโอน PV.</button>
                    @endif
                    @endif

                    <div class="card card-box borderR10 mb-2 mb-md-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    <h4 class="card-title mb-0">ประวัติการแจง PV.</h4>
                                </div>
                                {{-- <div class="col-sm-6 text-md-end">
                                    <button type="button" class="btn btn-info rounded-pill"><i
                                            class='bx bxs-file me-1'></i> ออกรายงาน</button>
                                </div> --}}
                            </div>
                            <hr>
                            <div class=" table-responsive">

                                <table id="workL" class="table table-bordered nowrap">
                                    {{-- <thead class="bg-light">
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
                                        <td>แจง Cashback หรือส่วนลดซื้อซ้ำ</td>
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
                                        <td>แจงปรับตำแหน่งให้สูงขึ้น</td>
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
                                </tbody> --}}
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="addClarifyJPModal" tabindex="-1" aria-labelledby="addClarifyJPModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg">
            <div class="modal-content borderR25">
                <div class="modal-header">
                    <h5 class="modal-title" id="addClarifyJPModalLabel">แจงเพื่อรับโบนัส </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="clarifyJP" id="cJPinlineRadio1" value="cJP1"
                            checked>
                        <label class="form-check-label" for="cJPinlineRadio1">แจงยืนยันสิทธิ์หรือรักษาสภาพ</label>
                    </div>
                    {{-- <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="clarifyJP" id="cJPinlineRadio2" value="cJP2">
                        <label class="form-check-label" for="cJPinlineRadio2">แจง Cashback หรือส่วนลดซื้อซ้ำ</label>
                    </div> --}}
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="clarifyJP" id="cJPinlineRadio3" value="cJP3">
                        <label class="form-check-label" for="cJPinlineRadio3">แจงปรับตำแหน่งให้สูงขึ้น</label>
                    </div>
                    <div class="cJP1 boxJPC mt-3">
                        <div class="card borderR10 p-2">
                            <h5 class="text-center">แจงยืนยันสิทธิ์หรือรักษาสภาพ</h5>
                            <div class="row gx-2">
                                <div class="col-sm-6">
                                    <div class="alert alert-white p-2 h-82 borderR10">
                                        <div class="d-flex">
                                            <div class="flex-shrink-0">
                                                <img src="{{ asset('frontend/images/man.png') }}" alt="..."
                                                    width="30px">
                                            </div>
                                            <div class="flex-grow-1 ms-2">
                                                <p class="small mb-0"> {{ Auth::guard('c_user')->user()->user_name }} </p>
                                                <h6>{{ Auth::guard('c_user')->user()->name }}
                                                    {{ Auth::guard('c_user')->user()->last_name }}</h6>
                                                <p class="small mb-0">ตำแหน่ง
                                                    {{ Auth::guard('c_user')->user()->qualification_id }} </p>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="alert alert-purple p-2 h-82 borderR10">
                                        <p class="small">PV คงเหลือ</p>
                                        <p class="text-end mb-0"><span
                                                class="h5 text-purple1 bg-opacity-100">{{ number_format(Auth::guard('c_user')->user()->pv, 2) }}</span>
                                            PV.</p>
                                    </div>
                                </div>
                                <div class="col-md-6 col-6">
                                    <label for="" class="form-label">รหัสสมาชิก <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control mb-3" id="user_name_active"
                                        value="{{ Auth::guard('c_user')->user()->user_name }}">

                                </div>
                                <div class="col-md-6 col-6 mb-3 mb-md-0">
                                    <label for="" class="form-label">ตำแหน่ง</label>
                                    <input type="text" class="form-control" id="position_active"
                                        value="{{ Auth::guard('c_user')->user()->qualification_id }}" disabled>
                                </div>

                                <div class="col-md-6">
                                    <label for="" class="form-label">ชื่อ-นามสกุล</label>
                                    <input type="text" class="form-control mb-3" id="name_active"
                                        value="{{ Auth::guard('c_user')->user()->name }} {{ Auth::guard('c_user')->user()->last_name }}"
                                        disabled readonly>
                                </div>
                                <div class="col-md-6"></div>
                              
                           
                                <div class="col-md-6 "> 
                                    <label for="" class="form-label">สิทธิ์การรับโบนัสขัอ 7 และข้อ 13 เพื่อนช่วยเพื่อน  </label>
                                    @php

                                        if (empty(Auth::guard('c_user')->user()->expire_date) || strtotime(Auth::guard('c_user')->user()->expire_date) < strtotime(date('Ymd'))) {
                                            if (empty(Auth::guard('c_user')->user()->expire_date)) {
                                                $date_mt_active = 'Not Active';
                                            } else {
                                                //$date_mt_active= date('d/m/Y',strtotime(Auth::guard('c_user')->user()->expire_date));
                                                $date_mt_active = 'Not Active';
                                            }
                                            $status = 'danger';
                                        } else {
                                            $date_mt_active = 'สิ้นสุด ' . date('d/m/Y', strtotime(Auth::guard('c_user')->user()->expire_date));
                                            $status = 'success';
                                        }
                                    @endphp

                                    {{-- <span
                                    class="badge rounded-pill bg-{{ $status }} bg-opacity-20 text-{{ $status }} fw-light ps-1">
                                    <i class="fas fa-circle text-{{ $status }}"></i> {{ $date_mt_active }}
                                </span> --}}

                                    <input type="text" class="form-control mb-3" id="date_active"
                                        value="{{ $date_mt_active }}"disabled>
                                </div>

                             
                                <div class="col-md-6">
                                  
                                    <label for="" class="form-label">สิทธิ์การรับโบนัสทุกข้อ</label>
                                    @php

                                        if (empty(Auth::guard('c_user')->user()->expire_date_bonus) || strtotime(Auth::guard('c_user')->user()->expire_date_bonus) < strtotime(date('Ymd'))) {
                                            if (empty(Auth::guard('c_user')->user()->expire_date_bonus)) {
                                                $date_mt_active_bonus = 'Not Active';
                                            } else {
                                                //$date_mt_active= date('d/m/Y',strtotime(Auth::guard('c_user')->user()->expire_date));
                                                $date_mt_active_bonus = 'Not Active';
                                            }
                                            $status = 'danger';
                                        } else {
                                            $date_mt_active_bonus = 'สิ้นสุด ' . date('d/m/Y', strtotime(Auth::guard('c_user')->user()->expire_date_bonus));
                                            $status = 'success';
                                        }
                                    @endphp
  
                                    <input type="text" class="form-control mb-3" id="date_active_bonus"
                                        value="{{ $date_mt_active_bonus }}"disabled>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="" class="form-label">จำนวน PV <span
                                            class="text-danger">*</span></label>
                                    {{-- <input type="number" class="form-control" id="pv_active"
                                        value="{{ number_format($data['rs']->pv_active) }}" disabled> --}}

                                        <select class="form-select" id="pv_active">
                                            <option value="{{ number_format($data['rs']->pv_active) }}"> แจง {{ number_format($data['rs']->pv_active) }} PV รับโบนัสข้อ7,ข้อ13 เพิ่ม / รับ33วัน</option>
                                            <option  value="80"> แจง 80 PV รับโบนัสทุกข้อ / รับ33วัน</option>
                                         
                                           
 
                                        </select>
                                    {{-- <p class="small text-danger mb-0"> ได้รับ 33 วัน</p> --}}
                                   

                                 
                                </div>
                                <div class="col-12 col-md-12 mt-2">
                                    <p class="small text-danger mb-0"> *** โบนัสข้อ 1-6 ไม่ต้องรักษาสมาชิกก็เกิดรายได้
                                        สามารถแจงเพื่อรับโบนัสเพิ่มได้แบบ (20PV/80PV)</p>
                                </div>
                                {{-- <div class="col-3 col-md-1">
                                    <label for="" class="form-label d-block">&nbsp;</label>
                                    <p readonly class="form-control-plaintext" id="">pv.</p>
                                </div> --}}
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between border-0">
                            <button type="button" class="btn btn-outline-dark rounded-pill"
                                data-bs-dismiss="modal">ยกเลิก</button>
                            <a href="javascript:void(0);" onclick="check_type_1()"
                                class="btn btn-p1 rounded-pill d-flex align-items-center" id="button_confirm_type1"><i
                                    class='bx bxs-check-circle me-2'></i>ทำรายการ</a>
                        </div>
                    </div>
                    <!--ตัวเลือก2-->
                    <div class="cJP2 boxJPC mt-3">
                        <div class="card borderR10 p-2">
                            <h5 class="text-center">แจง Cashback หรือส่วนลดซื้อซ้ำ</h5>
                            <div class="row gx-2">
                                <div class="col-sm-6">
                                    <div class="alert alert-white p-2 h-82 borderR10">
                                        <div class="d-flex">
                                            <div class="flex-shrink-0">
                                                <img src="{{ asset('frontend/images/man.png') }}" alt="..."
                                                    width="30px">
                                            </div>
                                            <div class="flex-grow-1 ms-2">
                                                <p class="small mb-0"> {{ Auth::guard('c_user')->user()->user_name }} </p>
                                                <h6>{{ Auth::guard('c_user')->user()->name }}
                                                    {{ Auth::guard('c_user')->user()->last_name }}</h6>
                                                <p class="small mb-0">ตำแหน่ง
                                                    {{ Auth::guard('c_user')->user()->qualification_id }} </p>


                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="alert alert-purple p-2 h-82 borderR10">
                                        <p class="small">PV คงเหลือ</p>
                                        <p class="text-end mb-0"><span
                                                class="h5 text-purple1 bg-opacity-100">{{ number_format(Auth::guard('c_user')->user()->pv, 2) }}</span>
                                            PV.</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="" class="form-label">รหัสสมาชิก <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control mb-3" id="user_name"
                                        value="{{ Auth::guard('c_user')->user()->user_name }}" disabled>
                                </div>
                                <div class="col-md-6">
                                    <label for="" class="form-label">ชื่อ-นามสกุล</label>
                                    <input type="text" class="form-control mb-3" id=""
                                        value="{{ Auth::guard('c_user')->user()->name }} {{ Auth::guard('c_user')->user()->last_name }}"
                                        disabled>
                                </div>


                                <div class="col-md-6 mb-3 mb-md-0">
                                    <label for="" class="form-label">ตำแหน่ง</label>
                                    <input type="text" class="form-control" id="qualification_id"
                                        value="{{ Auth::guard('c_user')->user()->qualification_id }}" disabled>
                                </div>
                                <div class="col-9 col-md-5">
                                    <label for="" class="form-label">จำนวน PV <span
                                            class="text-danger">*</span></label>
                                    <input type="number" value="0" min="0" class="form-control"
                                        id="pv">
                                    <p class="small text-danger mb-0">***1 PV. = {{ $data['pv_to_price'] }} บาท</p>
                                </div>
                                <div class="col-3 col-md-1">
                                    <label for="" class="form-label d-block">&nbsp;</label>
                                    <p readonly class="form-control-plaintext" id="">pv.</p>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between border-0">
                            <button type="button" class="btn btn-outline-dark rounded-pill"
                                data-bs-dismiss="modal">ยกเลิก</button>
                            {{--
                            <a href="javascript:void(0);" onclick=""  class="btn btn-p1 rounded-pill d-flex align-items-center"
                                data-bs-target="#addClarifyJPModalC2" data-bs-toggle="modal"><i
                                    class='bx bxs-check-circle me-2'></i>ทำรายการ</a> --}}
                            <a href="javascript:void(0);" onclick="check_type_2()"
                                class="btn btn-p1 rounded-pill d-flex align-items-center"><i
                                    class='bx bxs-check-circle me-2'></i>ทำรายการ</a>
                        </div>
                    </div>
                    <!--ตัวเลือก3-->


                    <div class="cJP3 boxJPC mt-3">
                        <div class="card borderR10 p-2">
                            <h5 class="text-center">แจงปรับตำแหน่งให้สูงขึ้น</h5>
                            <div class="row gx-2">
                                <div class="col-sm-6">
                                    <div class="alert alert-white p-2 h-82 borderR10">
                                        <div class="d-flex">
                                            <div class="flex-shrink-0">
                                                <img src="{{ asset('frontend/images/man.png') }}" alt="..."
                                                    width="30px">
                                            </div>
                                            <div class="flex-grow-1 ms-2">
                                                <p class="small mb-0"> {{ Auth::guard('c_user')->user()->user_name }} </p>
                                                <h6>{{ Auth::guard('c_user')->user()->name }}
                                                    {{ Auth::guard('c_user')->user()->last_name }}</h6>
                                                <p class="small mb-0">ตำแหน่ง
                                                    {{ Auth::guard('c_user')->user()->qualification_id }} </p>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="alert alert-purple p-2 h-82 borderR10">
                                        <p class="small">PV คงเหลือ</p>
                                        <p class="text-end mb-0"><span
                                                class="h5 text-purple1 bg-opacity-100">{{ number_format(Auth::guard('c_user')->user()->pv, 2) }}</span>
                                            PV.</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="" class="form-label">รหัสสมาชิก <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control mb-3" id="user_name_upgrad">

                                </div>
                                <div class="col-md-6">
                                    <label for="" class="form-label">ชื่อ-นามสกุล</label>
                                    <input type="text" class="form-control mb-3" id="name_upgrad" disabled readonly>
                                </div>

                                <div class="col-md-6 mb-3 mb-md-0">
                                    <label for="" class="form-label">ตำแหน่ง</label>
                                    <input type="text" class="form-control" id="position_upgrad" disabled>
                                </div>


                                <div class="col-6 col-md-5">
                                    <label for="" class="form-label">PV ปรับตำแหน่ง<span
                                            class="text-danger">*</span></label>


                                    <input type="number" value="0" min="0" class="form-control" id="pv_upgrad">
                                    <div id="pv_upgrad_text"></div>


                                </div>
                                <div class="col-3 col-md-1">
                                    <label for="" class="form-label d-block">&nbsp;</label>
                                    {{-- <p readonly class="form-control-plaintext" id="">pv.</p> --}}
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer justify-content-between border-0">
                            <button type="button" class="btn btn-outline-dark rounded-pill"
                                data-bs-dismiss="modal">ยกเลิก</button>
                            <a href="javascript:void(0);" onclick="check_type_3()"
                                class="btn btn-p1 rounded-pill d-flex align-items-center d-none" id="button_confirm_type3"><i
                                    class='bx bxs-check-circle me-2'></i>ทำรายการ</a>
                        </div>
                        <div class="alert alert-warning d-flex mt-2" role="alert">
                            <i class='bx bxs-info-circle me-2 bx-sm'></i>
                            <div>
                                กรณีตำแหน่งผู้ทำรายการสูงกว่า VVIP. ไม่สามารถแจ้งปรับตำแหน่งได้
                            </div>
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
                    <h5 class="modal-title" id="addClarifyJPModalC1Label">ทำรายการแจงยืนยันแจงยืนยันสิทธิ์หรือรักษาสภาพ</h5>
                </div>
                <form action="{{ route('jang_pv_active') }}" id="jang_pv_active" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="card borderR10 p-2 mb-3">
                            <div class="row mb-3">
                                <label for="" class="col-sm-4 col-4 col-form-label fw-bold">รหัสสมาชิก</label>
                                <div class="col-sm-8 col-8">
                                    <p readonly class="form-control-plaintext" id="c_user_name_active">
                                        {{ Auth::guard('c_user')->user()->user_name }}</p>
                                    <input type="hidden" name="input_user_name_active" id="input_user_name_active"
                                        value="{{ Auth::guard('c_user')->user()->user_name }}">
                                    <input type="hidden" name="type" value="1">
                                </div>
                                <label for="" class="col-sm-4 col-4 col-form-label fw-bold">ชื่อ-นามสกุล</label>


                                <div class="col-sm-8 col-8">
                                    <p readonly class="form-control-plaintext" id="c_name_active">
                                        {{ Auth::guard('c_user')->user()->name }}
                                        {{ Auth::guard('c_user')->user()->last_name }}</p>
                                </div>
                                <label for="" class="col-sm-4 col-4 col-form-label fw-bold">ตำแหน่ง</label>
                                <div class="col-sm-8 col-8">
                                    <p readonly class="form-control-plaintext" id="c_position_active">
                                        {{ Auth::guard('c_user')->user()->qualification_id }}</p>
                                </div>
                                <label for="" class="col-sm-4 col-4 col-form-label fw-bold">จำนวน</label>
                                <div class="col-6">
                                    <p readonly class="form-control-plaintext" id="c_pv_active"> </p>
                                    
                                    <input type="hidden" name="pv_active" id="c_pv_active_hidden"  value="0">
                                    <p readonly class="form-control-plaintext small text-danger" id="">
                                        ได้รับเพิ่ม 33 วัน
                                    </p>
                                </div>
                                <div class="col-2 col-sm-2">
                                    <p readonly class="form-control-plaintext" id="">PV.</p>
                                </div>
                                <label for="" class="col-4 col-sm-4 col-form-label fw-bold">วันที่ทำรายการ</label>
                                <div class="col-sm-8 col-8">
                                    <p readonly class="form-control-plaintext" id="c_date_active">
                                        {{ date('d/m/Y H:i:s') }}</p>
                                </div>
                                {{-- <label for=""
                                    class="col-sm-4 col-4 col-form-label fw-bold">เวลาที่ทำรายการ</label>
                                <div class="col-sm-8 col-4">
                                    <p readonly class="form-control-plaintext" id="">{{ date('H:i:s') }}</p>
                                </div> --}}
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between border-0">
                            <button type="button" class="btn btn-outline-dark rounded-pill"
                                data-bs-target="#addClarifyJPModal" data-bs-toggle="modal">ยกเลิก</button>
                            <button type="button" onclick="jang_summit();" class="btn btn-p1 rounded-pill d-flex align-items-center"
                                data-bs-dismiss="modal"><i class='bx bxs-check-circle me-2'></i>ยืนยัน</button>
                        </div>
                        <div class="alert alert-danger d-flex" role="alert">
                            <i class='bx bxs-error me-2 bx-sm'></i>
                            <div>
                                กรุณาแคปหน้าจอการทำรายการเพื่อใช้ตรวจสอบกรณีมีปัญหาในการทำรายการ
                            </div>
                        </div>
                    </div>

                </form>
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
                    <h5 class="modal-title" id="addClarifyJPModalC2Label">ทำรายการแจง Cashback หรือส่วนลดซื้อซ้ำ</h5>
                </div>
                <form action="{{ route('jang_pv_cash_back') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="card borderR10 p-2 mb-3">
                            <div class="row mb-3">
                                <label for="" class="col-sm-4 col-4 col-form-label fw-bold">รหัสสมาชิก</label>
                                <div class="col-sm-8 col-8">
                                    <p readonly class="form-control-plaintext" id="">
                                        {{ Auth::guard('c_user')->user()->user_name }}</p>
                                    <input type="hidden" name="user_name"
                                        value="{{ Auth::guard('c_user')->user()->user_name }}">
                                    <input type="hidden" name="type" value="2">
                                </div>
                                <label for="" class="col-sm-4 col-4 col-form-label fw-bold">ชื่อ-นามสกุล</label>
                                <div class="col-sm-8 col-8">
                                    <p readonly class="form-control-plaintext" id="">
                                        {{ Auth::guard('c_user')->user()->name }}
                                        {{ Auth::guard('c_user')->user()->last_name }}</p>
                                </div>
                                <label for="" class="col-sm-4 col-4 col-form-label fw-bold">ตำแหน่ง</label>
                                <div class="col-sm-8 col-8">
                                    <p readonly class="form-control-plaintext" id="">
                                        {{ Auth::guard('c_user')->user()->qualification_id }}</p>
                                </div>
                                <label for="" class="col-sm-4 col-4 col-form-label fw-bold">จำนวน</label>
                                <div class="col-6">
                                    <p readonly class="form-control-plaintext" id="c_pv">0</p>
                                    <input type="hidden" name="pv" id="pv_input" value="0">
                                    <p readonly class="form-control-plaintext small text-danger" id="">***1 PV. =
                                        {{ $data['pv_to_price'] }} บาท
                                    </p>
                                </div>
                                <div class="col-2 col-sm-2">
                                    <p readonly class="form-control-plaintext" id="">PV.</p>
                                </div>
                                <label for="" class="col-4 col-sm-4 col-form-label fw-bold">วันที่ทำรายการ</label>
                                <div class="col-sm-8 col-8">
                                    <p readonly class="form-control-plaintext" id="">{{ date('d/m/Y H:i:s') }}
                                    </p>
                                </div>
                                {{-- <label for=""
                                    class="col-sm-4 col-4 col-form-label fw-bold">เวลาที่ทำรายการ</label>
                                <div class="col-sm-8 col-4">
                                    <p readonly class="form-control-plaintext" id="">{{ date('H:i:s') }}</p>
                                </div> --}}
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between border-0">
                            <button type="button" class="btn btn-outline-dark rounded-pill"
                                data-bs-target="#addClarifyJPModal" data-bs-toggle="modal">ยกเลิก</button>
                            <button type="submit" class="btn btn-p1 rounded-pill d-flex align-items-center"
                                data-bs-dismiss="modal"><i class='bx bxs-check-circle me-2'></i>ยืนยัน</button>
                        </div>
                        <div class="alert alert-danger d-flex" role="alert">
                            <i class='bx bxs-error me-2 bx-sm'></i>
                            <div>
                                กรุณาแคปหน้าจอการทำรายการเพื่อใช้ตรวจสอบกรณีมีปัญหาในการทำรายการ
                            </div>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
    <!-- Modal Confirm C3-->
    <div class="modal fade" id="addClarifyJPModalC3" tabindex="-1" aria-labelledby="addClarifyJPModalC3Label"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
            <div class="modal-content borderR25">
                <div class="modal-header justify-content-center">
                    <h5 class="modal-title" id="addClarifyJPModalC3Label">ทำรายการแจงปรับตำแหน่งให้สูงขึ้น</h5>
                </div>
                <div class="modal-body">
                    <form action="{{ route('jang_pv_upgrad') }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="card borderR10 p-2 mb-3">
                                <div class="row mb-3">
                                    <label for="" class="col-sm-4 col-4 col-form-label fw-bold">รหัสสมาชิก</label>
                                    <div class="col-sm-8 col-8">
                                        <p readonly class="form-control-plaintext" id="c_user_name_upgrad"></p>
                                        <input type="hidden" name="input_user_name_upgrad" id="input_user_name_upgrad">
                                        <input type="hidden" name="type" value="3">
                                    </div>
                                    <label for=""
                                        class="col-sm-4 col-4 col-form-label fw-bold">ชื่อ-นามสกุล</label>


                                    <div class="col-sm-8 col-8">
                                        <p readonly class="form-control-plaintext" id="c_name_upgrad"></p>
                                    </div>
                                    <label for="" class="col-sm-4 col-4 col-form-label fw-bold">ตำแหน่ง</label>
                                    <div class="col-sm-8 col-8">
                                        <p readonly class="form-control-plaintext" id="c_position_upgrad"></p>
                                    </div>
                                    <label for="" class="col-sm-4 col-4 col-form-label fw-bold">แจงอัพตำแหน่ง</label>
                                    <div class="col-6">
                                        <p readonly class="form-control-plaintext" id="c_pv_upgrad"> 0 pv </p>
                                        <input type="hidden" name="pv_upgrad_input" id="pv_upgrad_input">
                                    </div>
                                    <div class="col-2 col-sm-2">
                                        <p readonly class="form-control-plaintext" id="">PV.</p>
                                    </div>
                                    <label for=""
                                        class="col-4 col-sm-4 col-form-label fw-bold">วันที่ทำรายการ</label>
                                    <div class="col-sm-8 col-8">
                                        <p readonly class="form-control-plaintext">{{ date('d/m/Y H:i:s') }}</p>
                                    </div>
                                    {{-- <label for=""
                                        class="col-sm-4 col-4 col-form-label fw-bold">เวลาที่ทำรายการ</label>
                                    <div class="col-sm-8 col-4">
                                        <p readonly class="form-control-plaintext" id="">{{ date('H:i:s') }}</p>
                                    </div> --}}
                                </div>
                            </div>
                            <div class="modal-footer justify-content-between border-0">
                                <button type="button" class="btn btn-outline-dark rounded-pill"
                                    data-bs-target="#addClarifyJPModal" data-bs-toggle="modal">ยกเลิก</button>
                                <button type="submit" class="btn btn-p1 rounded-pill d-flex align-items-center"
                                    data-bs-dismiss="modal"><i class='bx bxs-check-circle me-2'></i>ยืนยัน</button>
                            </div>
                            <div class="alert alert-danger d-flex" role="alert">
                                <i class='bx bxs-error me-2 bx-sm'></i>
                                <div>
                                    กรุณาแคปหน้าจอการทำรายการเพื่อใช้ตรวจสอบกรณีมีปัญหาในการทำรายการ
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addTransferJPModal" tabindex="-1" aria-labelledby="addTransferJPModalLabel"
        aria-hidden="true">

        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
            <div class="modal-content borderR25">
                <div class="modal-header">
                    <h5 class="modal-title" id="addTransferJPModalLabel">เพิ่มรายการโอน PV</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="cJP1 boxJPC mt-3">
                        <div class="card borderR10 p-2 mb-3">
                            <h5 class="text-center">ผู้โอน</h5>
                            <div class="row gx-2">
                                {{-- <div class="col-md-6">
                                <label for="" class="form-label">รหัสสมาชิก <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control mb-3" id="">
                            </div>
                            <div class="col-md-6">
                                <label for="" class="form-label">ชื่อ-นามสกุล</label>
                                <input type="text" class="form-control mb-3" id="">
                            </div> --}}
                                <div class="col-sm-6">
                                    <div class="alert alert-white p-2 h-82 borderR10">
                                        <div class="d-flex">
                                            <div class="flex-shrink-0">
                                                <img src="{{ asset('frontend/images/man.png') }}" alt="..."
                                                    width="30px">
                                            </div>
                                            <div class="flex-grow-1 ms-2">
                                                <p class="small mb-0">{{ Auth::guard('c_user')->user()->user_name }}</p>
                                                <h6>{{ Auth::guard('c_user')->user()->name }}
                                                    {{ Auth::guard('c_user')->user()->last_name }}</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="alert alert-purple p-2 h-82 borderR10">
                                        <p class="small">PV คงเหลือ</p>
                                        <p class="text-end mb-0"><span
                                                class="h5 text-purple1 bg-opacity-100">{{ Auth::guard('c_user')->user()->pv }}</span>
                                            PV.</p>
                                    </div>
                                </div>
                            </div>
                        </div>



                        <div class="col-sm-12">
                            <div class="card p-2 borderR10 mb-3">
                                <h5 class="text-center">รหัสรับโอน PV</h5>
                                <div class="row gx-3">
                                    <div class="col-md-6 mb-3">
                                        <label for="" class="form-label">รหัสสมาชิก <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="customers_user_recive_pv">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="" class="form-label">ชื่อสมาชิก</label>
                                        <input type="text" class="form-control" id="name_recive_pv" disabled>
                                    </div>
                                </div>
                                <div class="row gx-3 mb-3">
                                    <label for="" class="col-sm-3 col-form-label">ยอดโอน <span
                                            class="text-danger">*</span></label>
                                    <div class="col-sm-9">
                                        <input type="number" min="300" step="1" required
                                            class="form-control text-purple1 bg-opacity-100" value="0"
                                            id="tranfer_pv">
                                        <p class="small text-muted mb-0">**ไม่สามารถโอนได้มากกว่ายอด PV คงเหลือที่มีอยู่
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between border-0">
                            <button type="button" class="btn btn-outline-dark rounded-pill"
                                data-bs-dismiss="modal">ยกเลิก</button>
                            <a href="javascript:void(0);" id="button_confirm_recive_pv" onclick="check_tranfer_pv()"
                                class="btn btn-p1 rounded-pill d-flex align-items-center d-block"><i
                                    class='bx bxs-check-circle me-2'></i>ทำรายการ</a>


                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="addTransferCJPModal" tabindex="-1" aria-labelledby="addTransferCJPModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
            <div class="modal-content borderR25">
                <div class="modal-header justify-content-center">
                    <h5 class="modal-title" id="addTransferCJPModalLabel">ทำรายการโอน PV.</h5>
                </div>
                <form action="{{ route('tranfer_pv') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="card borderR10 p-2">
                            <div class="row mb-3">
                                <div class="col-sm-12">
                                    <h5 class="text-purple1 bg-opacity-100 mb-0">จาก</h5>
                                </div>

                                <label for="" class="col-sm-12 col-form-label fw-bold">ชื่อ-นามสกุล</label>
                                <div class="col-sm-12">
                                    <p readonly class="form-control-plaintext" id="">
                                        {{ Auth::guard('c_user')->user()->name }}
                                        {{ Auth::guard('c_user')->user()->last_name }}
                                        ({{ Auth::guard('c_user')->user()->user_name }})</p>
                                </div>
                                <hr>
                                <div class="col-sm-12">
                                    <h5 class="text-purple1 bg-opacity-100 mb-0">ไปยัง</h5>
                                </div>

                                <label for="" class="col-sm-12 col-form-label fw-bold">ชื่อ-นามสกุล</label>
                                <div class="col-sm-12">
                                    <p readonly class="form-control-plaintext" id="c_name_tranfer_pv">ภูดิส ชัยภูมิ</p>
                                    <input type="hidden" name="username_pv_tranfer_recive"
                                        id="username_pv_tranfer_recive">
                                </div>
                                <hr>
                                <label for="" class="col-sm-4 col-form-label fw-bold">จำนวนโอน</label>
                                <div class="col-9 col-sm-6">


                                    <p readonly class="form-control-plaintext" id="c_pv_tranfer">500</p>
                                    <input type="hidden" name="pv_tranfer" id="pv_input_tranfer">
                                </div>
                                <div class="col-3 col-sm-2">
                                    <p readonly class="form-control-plaintext" id="">PV.</p>
                                </div>
                                <label for="" class="col-sm-4 col-form-label fw-bold">วันที่ทำรายการ</label>
                                <div class="col-sm-8">
                                    <p readonly class="form-control-plaintext" id="">{{ date('d/m/Y H:i:s') }}
                                    </p>
                                </div>
                                {{-- <label for="" class="col-sm-4 col-form-label fw-bold">เวลาที่ทำรายการ</label>
                    <div class="col-sm-8">
                        <p readonly class="form-control-plaintext" id="">{{ date('H:i:s') }}</p>
                    </div> --}}
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer justify-content-between border-0">
                        <button type="button" class="btn btn-outline-dark rounded-pill"
                            data-bs-target="#addTransferJPModal" data-bs-toggle="modal">ยกเลิก</button>
                        <button type="submit" data-bs-dismiss="modal"
                            class="btn btn-p1 rounded-pill d-flex align-items-center"><i
                                class='bx bxs-check-circle me-2'></i>ยืนยัน</button>
                    </div>

                    <div class="alert alert-danger d-flex mt-3" role="alert">
                        <i class='bx bxs-error me-2 bx-sm'></i>
                        <div>
                            กรุณาแคปหน้าจอการทำรายการเพื่อใช้ตรวจสิบกรณีมีปัญหาในการทำรายการ
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $('.page-content').css({
            'min-height': $(window).height() - $('.navbar').height()
        });


        function jang_summit() {
            
        Swal.fire({
            title: 'รอสักครู่...',
            html: 'ระบบกำลังทำรายการกรุณาอย่าปิดหน้านี้จนกว่าระบบจะทำรายการเสร็จ...',
            didOpen: () => {
                Swal.showLoading()
            },
            allowOutsideClick: false,
            allowEscapeKey: false,
        })
        
        $('#jang_pv_active').submit();
    }

    </script>


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
                        url: '{{ route('jangpv_datatable') }}',
                        data: function(d) {

                            d.user_name = '{{ Auth::guard('c_user')->user()->user_name }}';
                            d.startDate = $('#startDate').val();
                            d.endDate = $('#endDate').val();
                        },
                        method: 'get'
                    },

                    columns: [{
                            data: 'code',
                            title: '<center>เลขที่รายการ</center>',
                            className: 'text-center'
                        },

                        {
                            data: 'code_order',
                            title: '<center>เลขรายการสินค้า</center>',
                            className: 'text-center'
                        },



                        {
                            data: 'created_at',
                            title: '<center>วันที่ทำรายการ</center> ',
                            className: 'text-center'
                        },
                        {
                            data: 'type',
                            title: '<center>ประเภทรายการ</center>',
                            className: 'text-center'
                        },
                        {
                            data: 'name_use',
                            title: '<center>ผู้ทำรายการ</center>',
                            className: 'text-center'
                        },
                        {
                            data: 'name',
                            title: '<center>ผู้รับ</center>',
                            className: 'text-center'
                        },

                        {
                            data: 'date_active',
                            title: '<center>Active</center>',
                            className: 'text-center'
                        },


                        {
                            data: 'qualification_id',
                            title: '<center>ตำแหน่ง</center>',
                            className: 'text-center'
                        },
                        {
                            data: 'pv',
                            title: '<center>จำนวน PV</center>',
                            className: 'text-end'
                        },
                        {
                            data: 'pv_balance',
                            title: '<center>PV คงเหลือ</center>',
                            className: 'text-end'
                        },
                        {
                            data: 'wallet',
                            title: '<center>Ewallet</center>',
                            className: 'text-end'
                        },
                        {
                            data: 'status',
                            title: '<center>สถานะ</center>',
                            className: 'text-center'
                        },


                    ],
                    // order: [
                    //     [0, 'DESC']
                    // ],
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
        });;

        function check_type_1() {
            pv = $("#pv_active").val();
            $("#c_pv_active").html(pv);
            $("#c_pv_active_hidden").val(pv);
            
            amount = {{ Auth::guard('c_user')->user()->pv }};
            pv2 = parseInt(pv)
            if (amount < pv) {
                $('#addClarifyJPModal').modal('hide')
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด',
                    text: 'PV ของท่านไม่เพียงพอ!',
                }).then((result) => {
                    //location.reload();
                });
                return;
            } else if (pv <= 0) {
                $('#addClarifyJPModal').modal('hide')
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด',
                    text: 'ไม่สามารถแจง PV น้อยกว่า 0 ได้',
                }).then((result) => {
                    //location.reload();
                });
                return;
            } else {
                // var withdraw = $('#withdraw').val();
                $('#addClarifyJPModal').modal('hide')
                $('#addClarifyJPModalC1').modal('toggle');
                $("#pv_input_uprgad").val(pv);
                $("#c_pv_upgrad").html(pv);
                // $('#withdraw_text_confirm').text(withdraw + " บาท");
            }
        }



        function check_type_2() {
            pv = $("#pv").val();
            amount = {{ Auth::guard('c_user')->user()->pv }};
            pv2 = parseInt(pv)
            if (amount < pv) {
                $('#addClarifyJPModal').modal('hide')
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด',
                    text: 'PV ของท่านไม่เพียงพอ!',
                }).then((result) => {
                    //location.reload();
                });
                return;
            } else if (pv <= 0) {
                $('#addClarifyJPModal').modal('hide')
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด',
                    text: 'ไม่สามารถแจง PV น้อยกว่า 0 ได้',
                }).then((result) => {
                    //location.reload();
                });
                return;
            } else {
                // var withdraw = $('#withdraw').val();
                $('#addClarifyJPModal').modal('hide')
                $('#addClarifyJPModalC2').modal('toggle');
                $("#pv_input").val(pv);
                $("#c_pv").html(pv);
                // $('#withdraw_text_confirm').text(withdraw + " บาท");
            }
        }

        function check_type_3() {
            pv = $("#pv_upgrad").val();
            amount = {{ Auth::guard('c_user')->user()->pv }};
            pv2 = parseInt(pv)
            if (amount < pv) {
                $('#addClarifyJPModal').modal('hide')
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด',
                    text: 'PV ของท่านไม่เพียงพอ!',
                }).then((result) => {
                    //location.reload();
                });
                return;
            } else if (pv <= 0) {
                $('#addClarifyJPModal').modal('hide')
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด',
                    text: 'ไม่สามารถแจง PV น้อยกว่า 0 ได้',
                }).then((result) => {
                    //location.reload();
                });
                return;
            } else {
                // var withdraw = $('#withdraw').val();
                $('#addClarifyJPModal').modal('hide')
                $('#addClarifyJPModalC3').modal('toggle');


                $("#pv_upgrad_input").val(pv);
                $("#c_pv_upgrad").html(pv);
                // $('#withdraw_text_confirm').text(withdraw + " บาท");
            }
        }

        $('#user_name_active').change(function() {
            user_use = '{{ Auth::guard('c_user')->user()->user_name }}';
            user_name_active = $('#user_name_active').val();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            // id = $(this).val();
            $.ajax({
                type: "get",
                url: '{{ route('checkcustomer_upline') }}',
                data: {
                    _token: "{{ csrf_token() }}",
                    user_use: user_use,
                    user_name_active: user_name_active,
                },
                success: function(data) {
                    console.log(data)
                    if (data['status'] == "fail") {
                        Swal.fire({
                            icon: 'error',
                            title: 'เกิดข้อผิดพลาด',
                            text: data['ms'],
                        })
                        $('#user_name_active').val(" ")
                        $('#input_user_name_active').val(" ");
                        $('#name_active').val("");
                        $('#position_active').val(" ");
                        $('#date_active').val(" ");
                        $('#date_active_bonus').val(" ");
                        $('#pv_active').val(" ");
                        $('#button_confirm_type1').addClass('d-none')


                    } else {

                        // $('#user_name_active').val(data['name'])
                        $('#input_user_name_active').val(data['user_name']);
                        $('#name_active').val(data['name']);
                        $('#position_active').val(data['position']);
                        $('#date_active').val(data['date_active']);
                        $('#date_active_bonus').val(data['date_active_bonus']);
                        $('#pv_active').val(data['pv_active']);

                        $('#c_user_name_active').html(data['user_name']);

                        $('#c_name_active').html(data['name']);
                        $('#c_position_active').html(data['position']);
                        $('#c_date_active').html(data['date_active']);
                        $('#c_pv_active').html(data['pv_active']);
                        $('#button_confirm_type1').addClass('d-block').removeClass('d-none');

                    }
                }
            });
        });


        $('#user_name_upgrad').change(function() {
            user_use = '{{ Auth::guard('c_user')->user()->user_name }}';
            user_name_upgrad = $('#user_name_upgrad').val();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            // id = $(this).val();
            $.ajax({
                type: "get",
                url: '{{ route('checkcustomer_upline_upgrad') }}',
                data: {
                    _token: "{{ csrf_token() }}",
                    user_use: user_use,
                    user_name_upgrad: user_name_upgrad,
                },
                success: function(data) {
                    console.log(data)
                    if (data['status'] == "fail") {
                        Swal.fire({
                            icon: 'error',
                            title: 'เกิดข้อผิดพลาด',
                            text: data['ms'],
                        })
                        $('#pv_upgrad_text').html("");
                        $('#user_name_upgrad').val("")
                        $('#input_user_name_upgrad').val("");
                        $('#name_upgrad').val("");
                        $('#position_upgrad').val("");
                        // $('#date_upgrad').val(" ");
                        // $('#pv_upgrad').val(" ");
                        $('#button_confirm_type3').addClass('d-none')



                    } else {
                        $('#pv_upgrad_text').html(data['html']);

                        // $('#user_name_active').val(data['name'])
                        $('#input_user_name_upgrad').val(data['user_name']);
                        $('#name_upgrad').val(data['name']);
                        $('#position_upgrad').val(data['position']);
                        // $('#date_active').val(data['date_active']);
                        // $('#pv_upgrad').val(data['pv_active']);

                        $('#c_user_name_upgrad').html(data['user_name']);
                        $('#c_name_upgrad').html(data['name']);
                        $('#c_position_upgrad').html(data['position']);
                        // $('#c_pv_upgrad').html('300');
                        // $('#c_date_active').html(data['date_active']);

                        $('#button_confirm_type3').addClass('d-block').removeClass('d-none');


                    }
                }
            });
        });



        $('#customers_user_recive_pv').change(function() {
            user_use = '{{ Auth::guard('c_user')->user()->user_name }}';
            user_name_active = $('#customers_user_recive_pv').val();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            // id = $(this).val();
            $.ajax({
                type: "get",
                url: '{{ route('checkcustomer_upline_tranfer_pv') }}',
                data: {
                    _token: "{{ csrf_token() }}",
                    user_use: user_use,
                    user_name_active: user_name_active,
                },
                success: function(data) {
                    console.log(data)
                    if (data['status'] == "fail") {
                        Swal.fire({
                            icon: 'error',
                            title: 'เกิดข้อผิดพลาด',
                            text: data['ms'],
                        })
                        $('#customers_user_recive_pv').val(" ")
                        $('#name_recive_pv').val("");
                        $('#button_confirm_recive_pv').addClass('d-none')


                    } else {

                        // $('#user_name_active').val(data['name'])
                        $('#customers_user_recive_pv').val(data['user_name']);
                        $('#name_recive_pv').val(data['name']);
                        $('#button_confirm_recive_pv').addClass('d-block').removeClass('d-none');


                        // $('#position_active').val(data['position']);
                        // $('#date_active').val(data['date_active']);
                        // $('#pv_active').val(data['pv_active']);

                        //$('#tranfer_pv_username').html(data['user_name']+'('+data['user_name']+')');
                        $('#c_name_tranfer_pv').html(data['name'] + ' (' + data['user_name'] + ')');
                        $('#username_pv_tranfer_recive').val(data['user_name']);

                        // $('#c_position_active').html(data['position']);
                        // $('#c_date_active').html(data['date_active']);
                        // $('#c_pv_active').html(data['pv_active']);


                    }
                }
            });
        });

        function check_tranfer_pv() {
            pv = $("#tranfer_pv").val();
            amount = {{ Auth::guard('c_user')->user()->pv }};
            pv2 = parseInt(pv)
            if (amount < pv) {
                $('#addTransferJPModal').modal('hide')
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด',
                    text: 'PV ของท่านไม่เพียงพอ!',
                }).then((result) => {
                    //location.reload();
                });
                return;
            } else if (pv <= 0) {
                $('#addTransferJPModal').modal('hide')
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด',
                    text: 'ไม่สามารถแจง PV น้อยกว่า 0 ได้',
                }).then((result) => {
                    //location.reload();
                });
                return;
            } else {

                $('#addTransferJPModal').modal('hide')
                $('#addTransferCJPModal').modal('toggle');
                $("#pv_input_tranfer").val(pv);
                $("#c_pv_tranfer").html(pv);

            }
        }
    </script>
@endsection
