@extends('layouts.backend.app_new')

@section('head')
    <meta charset="UTF-8">
@endsection

@section('css')
@endsection

@section('head_text')
    <nav aria-label="breadcrumb" class="-intro-x mr-auto hidden sm:flex">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">รายงาน</a></li>
            <li class="breadcrumb-item active" aria-current="page"> รายงานโบนัส STAR ReCash</li>
        </ol>
    </nav>
@endsection

@section('content')

    <div class="intro-y box p-5 mt-5">
        <div class="flex flex-col sm:flex-row sm:items-end xl:items-start mb-2">
            <form id="tabulator-html-filter-form" class="xl:flex sm:mr-auto">

                <div class="sm:flex items-center sm:mr-4">
                    <div class="col-span-12 sm:col-span-6">
                        <label class="form-label">ประเภท</label>
                        <select id="type" class="form-control sm:w-40 2xl:w-full mt-2 sm:mt-0 form-select">
                            <option value="">ทั้งหมด</option>
                            <option value="register">สมัครสมาชิก</option>
                            <option value="register_url">สมัครผ่านลิงก์</option>
                            <option value="jangpv">อัพตำแหน่ง</option>
                            <option value="active">ยืนยันสิทธิ์</option>
                        </select>
                    </div>
                </div>

                <div class="sm:flex items-center sm:mr-4">
                    <div class="col-span-12 sm:col-span-6">
                        <label class="form-label">สถานะ</label>
                        <select id="status" class="form-control sm:w-40 2xl:w-full mt-2 sm:mt-0 form-select">
                            <option value="success" selected>จ่ายแล้ว</option>
                            <option value="">ทั้งหมด</option>
                            <option value="notfound">ไม่มีผู้รับ</option>
                            <option value="cancel">ยกเลิก</option>
                        </select>
                    </div>
                </div>

                <div class="sm:flex items-center sm:mr-4">
                    <div class="col-span-12 sm:col-span-6">
                        <label class="form-label">รหัสผู้รับโบนัส</label>
                        <input type="text" id="user_name_g" class="form-control sm:w-40 2xl:w-full mt-2 sm:mt-0"
                            placeholder="รหัสสมาชิก">
                    </div>
                </div>

                <div class="sm:flex items-center sm:mr-4">
                    <div class="col-span-12 sm:col-span-6">
                        <label class="form-label">รหัสเจ้าของรายการ</label>
                        <input type="text" id="regis_user_name" class="form-control sm:w-40 2xl:w-full mt-2 sm:mt-0"
                            placeholder="รหัสสมาชิก">
                    </div>
                </div>

                <div class="sm:flex items-center sm:mr-4">
                    <div class="col-span-12 sm:col-span-6">
                        <label class="form-label">วันที่ทำรายการ</label>
                        <input type="date" id="s_date" class="form-control" value="{{ date('Y-m-01') }}">
                    </div>
                    <div class="col-span-12 sm:col-span-6">
                        <label class="form-label">ถึง</label>
                        <input type="date" id="e_date" class="form-control" value="{{ date('Y-m-d') }}">
                    </div>
                </div>

                <div class="mt-2 xl:mt-0">
                    <div class="col-span-12 sm:col-span-6 mt-6">
                        <button id="search-form" type="button" class="btn btn-primary w-full sm:w-16">ค้นหา</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="overflow-x-auto">
            <div class="table-responsive">
                <table id="workL" class="table table-striped table-hover dt-responsive display nowrap" cellspacing="0">
                    <tfoot>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td style="text-align: end;">รวม</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

    @endsection

    @section('script')

        <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.3.2/js/dataTables.buttons.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.3.2/js/buttons.html5.min.js"></script>

        <script>
            $('#linkMenuTop .nav-item').eq(1).addClass('active');
        </script>
        <script>
            $('.page-content').css({
                'min-height': $(window).height() - $('.navbar').height()
            });
        </script>

        <script type="text/javascript">
            function numberWithCommas(x) {
                return parseFloat(x).toFixed(2);
            }

            $(function() {
                table_order = $('#workL').DataTable({
                    dom: 'Bfrtip',
                    buttons: [{
                        extend: 'excel',
                        text: 'Export Excel',
                        title: 'รายงานโบนัส STAR ReCash',
                        className: 'btn btn-outline-secondary'
                    }],
                    searching: false,
                    ordering: false,
                    lengthChange: false,
                    responsive: true,
                    paging: false,
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
                        url: '{{ route('report_star_recash_datable') }}',
                        data: function(d) {
                            d.type = $('#type').val();
                            d.status = $('#status').val();
                            d.user_name_g = $('#user_name_g').val();
                            d.regis_user_name = $('#regis_user_name').val();
                            d.s_date = $('#s_date').val();
                            d.e_date = $('#e_date').val();
                        },
                    },

                    columns: [{
                            data: "created_at",
                            title: "วันที่ทำรายการ",
                            className: "w-10",
                        },
                        {
                            data: "type",
                            title: "ประเภท",
                            className: "w-10",
                        },
                        {
                            data: "regis_user_name",
                            title: "รหัสเจ้าของรายการ",
                            className: "w-10",
                        },
                        {
                            data: "regis_name",
                            title: "ชื่อเจ้าของรายการ",
                            className: "w-10",
                        },
                        {
                            data: "pv",
                            title: "PV",
                            className: "w-10 text-end",
                        },
                        {
                            data: "g1_user_name",
                            title: "รหัสชั้นที่ 1",
                            className: "w-10",
                        },
                        {
                            data: "g1_qualification",
                            title: "ตำแหน่งชั้นที่ 1",
                            className: "w-10",
                        },
                        {
                            data: "g1_percen",
                            title: "ชั้นที่ 1 ได้",
                            className: "w-10 text-end",
                        },
                        {
                            data: "percen",
                            title: "ส่วนต่าง",
                            className: "w-10 text-end",
                        },
                        {
                            data: "user_name_g",
                            title: "รหัสผู้รับโบนัส",
                            className: "w-10",
                        },
                        {
                            data: "name_g",
                            title: "ชื่อผู้รับโบนัส",
                            className: "w-10",
                        },
                        {
                            data: "qualification",
                            title: "ตำแหน่งผู้รับ",
                            className: "w-10",
                        },
                        {
                            data: "bonus_full",
                            title: "ยอดได้รับ",
                            className: "w-10 text-end",
                        },
                        {
                            data: "tax_total",
                            title: "ภาษี 3%",
                            className: "w-10 text-end",
                        },
                        {
                            data: "bonus",
                            title: "สุทธิ",
                            className: "w-10 text-end",
                        },
                        {
                            data: "status",
                            title: "สถานะ",
                            className: "w-10",
                        },
                    ],

                    "footerCallback": function(row, data, start, end, display) {
                        var api = this.api(),
                            data;

                        var intVal = function(i) {
                            return typeof i === 'string' ?
                                i.replace(/[\$,]/g, '') * 1 :
                                typeof i === 'number' ?
                                i : 0;
                        };

                        full = api
                            .column(12, {
                                page: 'current'
                            })
                            .data()
                            .reduce(function(a, b) {
                                return intVal(a) + intVal(b);
                            }, 0);
                        tax = api
                            .column(13, {
                                page: 'current'
                            })
                            .data()
                            .reduce(function(a, b) {
                                return intVal(a) + intVal(b);
                            }, 0);
                        total = api
                            .column(14, {
                                page: 'current'
                            })
                            .data()
                            .reduce(function(a, b) {
                                return intVal(a) + intVal(b);
                            }, 0);

                        $(api.column(12).footer()).html(numberWithCommas(full));
                        $(api.column(13).footer()).html(numberWithCommas(tax));
                        $(api.column(14).footer()).html(numberWithCommas(total));
                    }

                });

                $('#search-form').on('click', function(e) {
                    table_order.draw();
                    e.preventDefault();
                });
            });
        </script>
    @endsection
