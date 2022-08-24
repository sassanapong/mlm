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
                            <li class="breadcrumb-item active text-truncate" aria-current="page">ประวัติ eWallet</li>
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
                                    <label for="" class="form-label">รหัสการดำเนินการ</label>
                                    <input type="text" class="form-control">
                                </div>
                                <div class="col-md-6 col-lg-2">
                                    <label for="" class="form-label">สถานะ</label>
                                    <select class="form-select" id="">
                                        <option>ทั้งหมด</option>
                                        <option>รออนุมัติ</option>
                                        <option>อนุมัติ</option>
                                    </select>
                                </div>
                                <div class="col-md-6 col-lg-2">
                                    <label for="" class="form-label">วันที่เริ่มต้น</label>
                                    <input type="date" class="form-control">
                                </div>
                                <div class="col-md-6 col-lg-2">
                                    <label for="" class="form-label">วันที่สิ้นสุด</label>
                                    <input type="date" class="form-control">
                                </div>
                                <div class="col-md-6 col-lg-2">
                                    <label for="" class="form-label">ประเถทการดำเนินการ</label>
                                    <select class="form-select" id="">
                                        <option>ทั้งหมด</option>
                                        <option>ฝากเงิน</option>
                                        <option>โอนเงิน</option>
                                        <option>ถอนเงิน</option>
                                    </select>
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
                                    <h4 class="card-title mb-0">ประวัติ eWallet</h4>
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
                                        <th>รหัสการดำเนินการ</th>
                                        <th>วันที่ดำเนินการ</th>
                                        <th>ประเภทการดำเนินการ</th>
                                        <th>จำนวนเงิน</th>
                                        <th>คงเหลือ</th>
                                        <th>รหัสผู้รับ</th>
                                        <th>ชื่อผู้รับ</th>
                                        <th>สถานะ</th>
                                        <th>การจัดการ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-center">ew106789</td>
                                        <td>28/04/2022</td>
                                        <td class="text-center">ฝากเงิน</td>
                                        <td class="text-end">4,000.00</td>
                                        <td class="text-end">1,532,087.00</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td class="text-center"><span class="badge bg-danger fw-light">รออนุมัติ</span>
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#depositModal">
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
                                        <td class="text-center">ew106788</td>
                                        <td>28/04/2022</td>
                                        <td class="text-center">ถอนเงิน</td>
                                        <td class="text-end">3,000.00</td>
                                        <td class="text-end">1,528,087.00</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td class="text-center"><span class="badge bg-success fw-light">อนุมัติ</span>
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#withdrawModal">
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
                                        <td class="text-center">ew106787</td>
                                        <td>28/04/2022</td>
                                        <td class="text-center">โอนเงิน</td>
                                        <td class="text-end">4,000.00</td>
                                        <td class="text-end">1,524,087.00</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td class="text-center"><span class="badge bg-success fw-light">อนุมัติ</span></td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#transferModal">
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
