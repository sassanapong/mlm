@section('css')
<link href='https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css' rel='stylesheet'>
@endsection
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
                            <li class="breadcrumb-item active text-truncate" aria-current="page">ประวัติการสั่งซื้อ</li>
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
                    </div> --}}
                    <div class="card card-box borderR10 mb-2 mb-md-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    <h4 class="card-title mb-0">ประวัติการสั่งซื้อ</h4>
                                </div>
                                <div class="col-sm-6 text-md-end">
                                    {{-- <button type="button" class="btn btn-info rounded-pill mb-2"><i
                                            class='bx bxs-file me-1'></i> ออกรายงาน</button> --}}
                                </div>
                            </div>
                            <div class="row justify-content-md-end">
                                <div class="col-lg-4 col-xl-3 ">
                                    <div class="alert alert-success p-2 mb-0" role="alert">
                                        <p class="mb-0 text-lg-end">PV สั่งซื้อสินค้าสะสม <b>{{number_format(Auth::guard('c_user')->user()->pv_all)}}</b> PV</p>
                                    </div>
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
@endsection

@section('script')
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
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
        // เมื่อคลิกที่ลิงก์ที่มีคลาส track-btn
        $('body').on('click', '.track-btn', function(e) {
            e.preventDefault();
            
            // ดึงข้อมูล tracking_no และ URL
            var trackingNo = $(this).data('tracking-no');
            var trackingUrl = $(this).data('url');
            
            // คัดลอก tracking_no ไปยังคลิปบอร์ด
            navigator.clipboard.writeText(trackingNo).then(function() {
                // แสดงข้อความแจ้งว่า "บันทึกเลขจัดส่งแล้ว" โดยใช้ SweetAlert
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    html: "บันทึกเลขจัดส่งแล้ว <br>" + trackingNo,
                });
            }).catch(function(error) {
                // ถ้ามีข้อผิดพลาดในการคัดลอก
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'เกิดข้อผิดพลาดในการคัดลอกหมายเลข',
                });
            });
    
            // เปิดลิงก์การติดตามในแท็บใหม่
            window.open(trackingUrl, '_blank');
        });
    });
</script>

<script type="text/javascript">




    $(function() {
        table_ewallet = $('#workL').DataTable({
            // searching: false,
            ordering: false,
            lengthChange: false,
            responsive: true,
            pageLength: 25,
            processing: true,
            serverSide: true,
            "language": {
                "lengthMenu": "แสดง _MENU_ แถว",
                "zeroRecords": "ไม่พบข้อมูล",
                "info": "แสดงหน้า _PAGE_ จาก _PAGES_ หน้า",
                "search": "ค้นหา",
                "infoEmpty": "",
                "infoFiltered": "",
                "paginate": {
                    "first": "หน้าแรก",
                    "previous": "ย้อนกลับ",
                    "next": "ถัดไป",
                    "last": "หน้าสุดท้าย"
                },
                'processing': "กำลังโหลดข้อมูล",
            },
            ajax: {
                url: '{{ route('history_datable') }}',
                data: function(d) {
                    d.Where = {};

                    $('.myWhere').each(function() {
                        if ($.trim($(this).val()) && $.trim($(this).val()) != '0') {
                            d.Where[$(this).attr('name')] = $.trim($(this).val());
                            if ($('#Search').val() == '') $('#btn-Excel').css("display",
                                "initial");
                        }
                    });
                    d.Like = {};
                    $('.myLike').each(function() {
                        if ($.trim($(this).val()) && $.trim($(this).val()) != '0') {
                            d.Like[$(this).attr('name')] = $.trim($(this).val());
                        }
                    });
                    d.Custom = {};
                    $('.myCustom').each(function() {
                        if ($.trim($(this).val()) && $.trim($(this).val()) != '0' && $(this)
                            .attr('type') != 'checkbox') {
                            d.Custom[$(this).attr('name')] = $.trim($(this).val());
                        }
                        if ($.trim($(this).val()) && $.trim($(this).val()) != '0' && $(this)
                            .is(':checked')) {
                            d.Custom[$(this).attr('name')] = $.trim($(this).val());
                        }
                    });
                },
            },


            columns: [
                // {
                //     data: "id",
                //     title: "ลำดับ",
                //     className: "w-10 text-center",
                // },
                {
                    data: "date",
                    title: "วันที่ทำรายการ",
                    className: "w-10 text-center",
                },
                {
                    data: "code_order",
                    title: "เลขที่ออเดอร์",
                    className: "w-10 text-center",

                },
                {
                    data: "user",
                    title: "ผู้รับสินค้า",
                    className: "w-10 text-center",

                },

                // {
                //     data: "type",
                //     title: "ประเภท",
                //     className: "w-10 text-center",

                // },
 
                
                {
                    data: "quantity",
                    title: "จำนวน",
                    className: "w-10 text-center",

                },
                {
                    data: "pv_total",
                    title: "PV",
                    className: "w-10 text-end",

                },
                {
                    data: "total_price",
                    title: "ราคา",
                    className: "w-10 text-end",

                },
                {
                    data: "detail",
                    title: "Status",
                    className: "w-10 text-center",

                },
                {
                    data: "tracking",
                    title: "เช็คสถานะการจัดส่ง",
                    className: "w-10 text-center",

                    // className: "table-report__action w-10 text-center",
                },


            ],

        });
        $('.myWhere,.myLike,.datepicker,.iSort,.myCustom').on('change', function(e) {
            table_ewallet.draw();
        });
    });

</script>
@endsection
