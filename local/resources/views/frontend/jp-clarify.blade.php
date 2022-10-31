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
                    </div> --}}
                    <button type="button" class="btn btn-p1 rounded-pill mb-3" data-bs-toggle="modal"
                        data-bs-target="#addClarifyJPModal"><i class='bx bx-plus me-1'></i> ทำรายการแจง PV.</button>
                    <div class="card card-box borderR10 mb-2 mb-md-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    <h4 class="card-title mb-0">ประวัติการแจง PV.</h4>
                                </div>
                                <div class="col-sm-6 text-md-end">
                                    <button type="button" class="btn btn-info rounded-pill"><i
                                            class='bx bxs-file me-1'></i> ออกรายงาน</button>
                                </div>
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
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
            <div class="modal-content borderR25">
                <div class="modal-header">
                    <h5 class="modal-title" id="addClarifyJPModalLabel">เพิ่มรายการแจง </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="clarifyJP" id="cJPinlineRadio1"
                            value="cJP1" checked>
                        <label class="form-check-label" for="cJPinlineRadio1">สนับสนุนสินค้า</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="clarifyJP" id="cJPinlineRadio2" value="cJP2">
                        <label class="form-check-label" for="cJPinlineRadio2">แจงลูกค้าประจำ</label>
                    </div>
                    {{-- <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="clarifyJP" id="cJPinlineRadio3"
                            value="cJP3">
                        <label class="form-check-label" for="cJPinlineRadio3">แจงปรับตำแหน่ง</label>
                    </div> --}}
                    <div class="cJP1 boxJPC mt-3">
                        <div class="card borderR10 p-2">
                            <h5 class="text-center">สนับสนุนสินค้า</h5>
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
                                    <input type="text" class="form-control mb-3" id="user_name_active"
                                        value="{{ Auth::guard('c_user')->user()->user_name }}" >

                                </div>
                                <div class="col-md-6">
                                    <label for="" class="form-label">ชื่อ-นามสกุล</label>
                                    <input type="text" class="form-control mb-3" id="name_active" value="{{ Auth::guard('c_user')->user()->name }} {{ Auth::guard('c_user')->user()->last_name }}"
                                        disabled readonly>
                                </div>

                                <div class="col-md-6 mb-3 mb-md-0">
                                    <label for="" class="form-label">ตำแหน่ง</label>
                                    <input type="text" class="form-control" id="position_active"
                                        value="{{ Auth::guard('c_user')->user()->qualification_id }}" disabled>
                                </div>

                                <div class="col-md-6">
                                    <label for="" class="form-label">วันที่สินสุด</label>
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
                                        $date_mt_active = 'Active ' . date('d/m/Y', strtotime(Auth::guard('c_user')->user()->expire_date));
                                        $status = 'success';
                                    }
                                @endphp

                                {{-- <span
                                    class="badge rounded-pill bg-{{ $status }} bg-opacity-20 text-{{ $status }} fw-light ps-1">
                                    <i class="fas fa-circle text-{{ $status }}"></i> {{ $date_mt_active }}
                                </span> --}}

                                    <input type="text" class="form-control mb-3" id="date_active"
                                        value="{{$date_mt_active}}"disabled>
                                </div>
                                <div class="col-9 col-md-5">
                                    <label for="" class="form-label">จำนวน PV <span
                                            class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="pv_active" value="{{number_format($data['rs']->pv_active)}}" disabled>
                                    <p class="small text-danger mb-0"> ได้รับ 33 วัน</p>
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
                                <a href="javascript:void(0);" onclick="check_type_1()"
                                class="btn btn-p1 rounded-pill d-flex align-items-center" id="button_confirm_type1"><i
                                    class='bx bxs-check-circle me-2'></i>ทำรายการ</a>
                        </div>
                    </div>
                    <!--ตัวเลือก2-->
                    <div class="cJP2 boxJPC mt-3">
                        <div class="card borderR10 p-2">
                            <h5 class="text-center">แจงรับส่วนลด</h5>
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
                    {{-- <div class="cJP3 boxJPC mt-3">
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
                    </div> --}}
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
                    <h5 class="modal-title" id="addClarifyJPModalC1Label">ทำรายการแจงยืนยันสนับสนุนสินค้า</h5>
                </div>
                <form action="{{ route('jang_pv_active') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="card borderR10 p-2 mb-3">
                            <div class="row mb-3">
                                <label for="" class="col-sm-4 col-4 col-form-label fw-bold">รหัสสมาชิก</label>
                                <div class="col-sm-8 col-8">
                                    <p readonly class="form-control-plaintext" id="c_user_name_active">
                                        {{ Auth::guard('c_user')->user()->user_name }}</p>
                                 <input type="hidden" name="input_user_name_active" id="input_user_name_active" value="{{ Auth::guard('c_user')->user()->user_name }}">
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
                                    <p readonly class="form-control-plaintext" id="c_pv_active">{{number_format($data['rs']->pv_active)}}</p>
                                    {{-- <input type="hidden" name="pv_active"  value="0"> --}}
                                    <p readonly class="form-control-plaintext small text-danger" id=""> ได้รับเพิ่ม 33 วัน
                                    </p>
                                </div>
                                <div class="col-2 col-sm-2">
                                    <p readonly class="form-control-plaintext" id="">PV.</p>
                                </div>
                                <label for="" class="col-4 col-sm-4 col-form-label fw-bold">วันที่ทำรายการ</label>
                                <div class="col-sm-8 col-8">
                                    <p readonly class="form-control-plaintext" id="c_date_active">{{ date('d/m/Y') }}</p>
                                </div>
                                <label for=""
                                    class="col-sm-4 col-4 col-form-label fw-bold">เวลาที่ทำรายการ</label>
                                <div class="col-sm-8 col-4">
                                    <p readonly class="form-control-plaintext" id="">{{ date('H:i:s') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between border-0">
                            <button type="button" class="btn btn-outline-dark rounded-pill"
                                data-bs-target="#addClarifyJPModal" data-bs-toggle="modal">ยกเลิก</button>
                            <button type="submit" class="btn btn-p1 rounded-pill d-flex align-items-center"><i
                                    class='bx bxs-check-circle me-2'></i>ยืนยัน</button>
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
                    <h5 class="modal-title" id="addClarifyJPModalC2Label">ทำรายการแจงรับส่วนลด</h5>
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
                                    <p readonly class="form-control-plaintext" id="">{{ date('d/m/Y') }}</p>
                                </div>
                                <label for=""
                                    class="col-sm-4 col-4 col-form-label fw-bold">เวลาที่ทำรายการ</label>
                                <div class="col-sm-8 col-4">
                                    <p readonly class="form-control-plaintext" id="">{{ date('H:i:s') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between border-0">
                            <button type="button" class="btn btn-outline-dark rounded-pill"
                                data-bs-target="#addClarifyJPModal" data-bs-toggle="modal">ยกเลิก</button>
                            <button type="submit" class="btn btn-p1 rounded-pill d-flex align-items-center"><i
                                    class='bx bxs-check-circle me-2'></i>ยืนยัน</button>
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
    {{-- <div class="modal fade" id="addClarifyJPModalC3" tabindex="-1" aria-labelledby="addClarifyJPModalC3Label"
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
    </div> --}}
@endsection

@section('script')
    <script>
        $('.page-content').css({
            'min-height': $(window).height() - $('.navbar').height()
        });
    </script>

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
                    order: [
                        [0, 'DESC']
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
            }else if (pv <= 0) {
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
                $("#pv_input").val(pv);
                $("#c_pv").html(pv);
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
            }else if (pv <= 0) {
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

        $('#user_name_active').change(function() {
            user_use = '{{ Auth::guard('c_user')->user()->user_name }}';
            user_name_active =  $('#user_name_active').val();
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
                    user_name_active:user_name_active,
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
                        $('#pv_active').val(" ");
                        $('#button_confirm_type1').addClass('d-none')


                    } else {

                        // $('#user_name_active').val(data['name'])
                        $('#input_user_name_active').val(data['user_name']);
                        $('#name_active').val(data['name']);
                        $('#position_active').val(data['position']);
                        $('#date_active').val(data['date_active']);
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
    </script>
@endsection
