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
                            <li class="breadcrumb-item active text-truncate" aria-current="page">ประวัติการโอนโบนัส</li>
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
                                <div class="col-md-12 col-lg-3">
                                    <label for="" class="form-label">รหัสการดำเนินการ</label>
                                    <input type="text" class="form-control">
                                </div>
                                <div class="col-md-5 col-lg-2">
                                    <label for="" class="form-label">วันที่เริ่มต้น</label>
                                    <input type="date" class="form-control">
                                </div>
                                <div class="col-md-5 col-lg-2">
                                    <label for="" class="form-label">วันที่สิ้นสุด</label>
                                    <input type="date" class="form-control">
                                </div>
                                <div class="col-md-2 col-lg-1">
                                    <label for="" class="form-label d-none d-md-block">&nbsp;</label>
                                    <button type="button" class="btn btn-dark rounded-circle btn-icon"><i
                                            class="bx bx-search"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card card-box borderR10 mb-2 mb-md-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    <h4 class="card-title mb-0">ประวัติการโอนโบนัส</h4>
                                </div>
                                <div class="col-sm-6 text-md-end">
                                    <button type="button" class="btn btn-info rounded-pill mb-2"><i
                                            class='bx bxs-file me-1'></i> ออกรายงาน</button>
                                </div>
                            </div>
                            <div class="row justify-content-between">
                                <div class="col-lg-4 col-xl-3 ">
                                    <div class="alert alert-warning p-2 mb-1 mb-lg-0" role="alert">
                                        <p class="mb-0">หักภาษี ณ ที่จ่ายรวม <b>8888</b> บาท</p>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-xl-3 ">
                                    <div class="alert alert-success p-2 mb-0" role="alert">
                                        <p class="mb-0 text-lg-end">รายได้ผ่านการโอนรวม <b>8888</b> บาท</p>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <table id="workL" class="table table-bordered nowrap">
                                <thead class="bg-light">
                                    <tr>
                                        <th>รหัสการดำเนินการ</th>
                                        <th>วันที่ดำเนินการ</th>
                                        <th>ธนาคาร</th>
                                        <th>เลขบัญชี</th>
                                        <th>ยอดการถอน</th>
                                        <th>ภาษี ณ ที่จ่าย 3%</th>
                                        <th>ค่าธรรมเนียมการโอน</th>
                                        <th>ยอดสุทธิ</th>
                                        <th>วันที่รับเงินโอน</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-center">wew106789</td>
                                        <td class="text-center">6/1/2565</td>
                                        <td class="text-center">ไทยพาณิชย์</td>
                                        <td class="text-center">4032556635</td>
                                        <td class="text-end">1000.00</td>
                                        <td class="text-end">30.00</td>
                                        <td class="text-end">13.00</td>
                                        <td class="text-end">957.00</td>
                                        <td class="text-center">6/3/2565</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">wew106788</td>
                                        <td class="text-center">5/30/2565</td>
                                        <td class="text-center">ไทยพาณิชย์</td>
                                        <td class="text-center">4032556635</td>
                                        <td class="text-end">500.00</td>
                                        <td class="text-end">-</td>
                                        <td class="text-end">13.00</td>
                                        <td class="text-end">487.00</td>
                                        <td class="text-center">5/30/2565</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">wew106787</td>
                                        <td class="text-center">4/4/2565</td>
                                        <td class="text-center">ไทยพาณิชย์</td>
                                        <td class="text-center">4032556635</td>
                                        <td class="text-end">300.00</td>
                                        <td class="text-end">-</td>
                                        <td class="text-end">13.00</td>
                                        <td class="text-end">287.00</td>
                                        <td class="text-center">4/4/2565</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">wew106786</td>
                                        <td class="text-center">1/2/2565</td>
                                        <td class="text-center">ไทยพาณิชย์</td>
                                        <td class="text-center">4032556635</td>
                                        <td class="text-end">3500.00</td>
                                        <td class="text-end">105.00</td>
                                        <td class="text-end">13.00</td>
                                        <td class="text-end">#####</td>
                                        <td class="text-center">1/2/2565</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
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
    <script>
        $(document).ready(function() {
            var table = $('#workL').DataTable({
                responsive: true
            });

            new $.fn.dataTable.FixedHeader(table);
        });
    </script>
@endsection
