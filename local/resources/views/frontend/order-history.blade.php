<title>บริษัท มารวยด้วยกัน จำกัด</title>





@extends('layouts.frontend.app')
@section('conten')
    <div class="bg-whiteLight page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php">หน้าแรก</a></li>
                            <li class="breadcrumb-item active text-truncate" aria-current="page">ประวัติการสั่งซื้อ</li>
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
                                    <label for="" class="form-label">เลขที่ออเดอร์</label>
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
                                    <h4 class="card-title mb-0">ประวัติการสั่งซื้อ</h4>
                                </div>
                                <div class="col-sm-6 text-md-end">
                                    <button type="button" class="btn btn-info rounded-pill mb-2"><i
                                            class='bx bxs-file me-1'></i> ออกรายงาน</button>
                                </div>
                            </div>
                            <div class="row justify-content-md-end">
                                <div class="col-lg-4 col-xl-3 ">
                                    <div class="alert alert-success p-2 mb-0" role="alert">
                                        <p class="mb-0 text-lg-end">PVสั่งซื้อสินค้าสะสม <b>925</b> PV</p>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <table id="workL" class="table table-bordered nowrap">
                                <thead class="bg-light">
                                    <tr class="text-center">
                                        <th>ลำดับ</th>
                                        <th>วันที่ทำรายการ</th>
                                        <th>เลขที่ออเดอร์</th>
                                        <th>รายการ</th>
                                        <th>จำนวน</th>
                                        <th>ราคา</th>
                                        <th>PV</th>
                                        <th>เช็คสถานะการจัดส่ง</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-center">1</td>
                                        <td class="text-center">21/6/2565 15:00</td>
                                        <td class="text-center"><a href="{{ route('order_detail') }}">mdk65-060001</a></td>
                                        <td>XL1 กาแฟ20+1</td>
                                        <td class="text-end">1</td>
                                        <td class="text-end">5,000.00</td>
                                        <td class="text-end">250</td>
                                        <td class="text-center"><a
                                                href="https://th.kerryexpress.com/th/track/">KERRY12345678</a></td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">2</td>
                                        <td class="text-center">23/6/2565 18:00</td>
                                        <td class="text-center"><a href="{{ route('order_detail') }}">mdk65-060002</a></td>
                                        <td>XLM1 กาแฟ20+1</td>
                                        <td class="text-end">2</td>
                                        <td class="text-end">10,000.00</td>
                                        <td class="text-end">500</td>
                                        <td class="text-center"><a
                                                href="https://th.kerryexpress.com/th/track/">KERRY12345680</a></td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">2</td>
                                        <td class="text-center"></td>
                                        <td class="text-center"></td>
                                        <td>L4 แอปวี่ 10ห่อ</td>
                                        <td class="text-end">1</td>
                                        <td class="text-end">3,500.00</td>
                                        <td class="text-end">175</td>
                                        <td class="text-center"></td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">3</td>
                                        <td class="text-center">28/6/2565 09:00</td>
                                        <td class="text-center"><a href="{{ route('order_detail') }}">mdk65-060003</a></td>
                                        <td>XL1 กาแฟ20+1</td>
                                        <td class="text-end">1</td>
                                        <td class="text-end">5,000.00</td>
                                        <td class="text-end">250</td>
                                        <td class="text-center"><a
                                                href="https://th.kerryexpress.com/th/track/">KERRY12345682</a></td>
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
        $('#linkMenuTop .nav-item').eq(1).addClass('active');
    </script>
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
