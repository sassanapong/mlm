@extends('layouts.backend.app')



@section('head')
    {{-- select2 --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />


    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endsection

@section('css')
    <style>
        .btn-warning {
            color: #000 !important;
            background: #FAD02A !important;
        }

        .dropdown-item:hover {
            cursor: pointer;
        }

        .date_start,
        .date_end {
            width: 70%;
        }
    </style>
@endsection

@section('conten')
    @include('backend.navbar.navbar_mobile')
    <div class="flex overflow-hidden">

        @include('backend.navbar.navbar')
        <div class="content">
            @include('backend.navbar.top_bar')

            <h2 class="text-lg font-medium mr-auto mt-2">รายการ คำสั่งซื้อ</h2>

            <div class="grid grid-cols-12 gap-5">
                <div class="col-span-12 ">

                    <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-4">
                        <div class="">
                            <label for="">วันที่เริ่มต้น</label>
                            <input type="date" name="date_start" class="form-control  myCustom date_start">
                        </div>
                        <div class="ml-2">
                            <label for="">วันที่สิ้นสุด</label>
                            <input type="date" name="date_end" class="form-control myCustom mr-3 date_end">
                        </div>
                        <div class="">
                            <form id="importorder" method="post" enctype="multipart/form-data">
                                @csrf
                                <small class="text-danger excel_err _err"></small>
                                <div class="form-inline mt-2 ">
                                    <input name="excel" type="file"
                                        class=" block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100">
                                </div>
                        </div>
                        <div class="">
                            <div class="form-inline ">
                                <button type="submit" class="btn btn-outline-primary  btn-sm inline-block ml-1">Import
                                </button>
                            </div>

                        </div>
                        </form>
                        <div class="ml-2">
                            <div class="form-inline ">
                                <a class="btn btn-outline-pending orderexport  btn-sm  inline-block ">
                                    Export </a>
                            </div>
                        </div>
                        <div class="ml-2">
                            <div class="form-inline ">
                                <a class="btn  btn-sm btn-pending inline-block tracking_no_sort" target="_blank">
                                    เรียงลำดับขนส่ง
                                </a>
                            </div>
                        </div>

                        <div class="dropdown ml-2">
                            <p class="dropdown-toggle btn-sm btn btn-primary" aria-expanded="false"
                                data-tw-toggle="dropdown">
                                ออกใบปะหน้า</p>
                            <div class="dropdown-menu">
                                <ul class="dropdown-content">
                                    <li>
                                        <p class="dropdown-item report_pdf" data-type="all">
                                            <i data-lucide="truck" class="w-4 h-4 mr-2"></i>
                                            ทั้งหมด
                                        </p>
                                        @foreach ($Shipping_type as $val)
                                            <p class="dropdown-item report_pdf" data-type="{{ $val->name }}">
                                                <i data-lucide="truck" class="w-4 h-4 mr-2"></i>
                                                {{ $val->name }}
                                            </p>
                                        @endforeach
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="ml-2">
                            <div class="form-inline ">


                                <a class="btn btn-primary all_bill  btn-sm  inline-block " target="_blank">
                                    ใบรายละเอียดสินค้าหลายใบ </a>
                            </div>
                        </div>
                    </div>


                    <table id="table_orders" class="table table-report">
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- BEGIN: Modal Content -->
    <div id="tracking" class="modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <form action="{{ route('tracking_no') }}" method="post">
                @csrf
                <div class="modal-content">
                    <!-- BEGIN: Modal Header -->
                    <div class="modal-header">
                        <h2 class="font-medium text-base mr-auto">อัพเดทรหัสจัดส่งสินค้า</h2>
                    </div>
                    <!-- END: Modal Header -->
                    <!-- BEGIN: Modal Body -->
                    <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                        <div class="col-span-12 sm:col-span-4">
                            <label for="modal-form-1" class="form-label">รหัส</label>
                            <input id="code_order" name="code_order" readonly type="text" class="form-control">
                        </div>
                        <div class="col-span-12 sm:col-span-4">
                            <label for="modal-form-2" class="form-label">รหัสจัดส่งสินค้า</label>
                            <input id="tracking_no" name="tracking_no" type="text" required class="form-control">
                        </div>
                        <div class="col-span-12 sm:col-span-4">
                            <label for="modal-form-6" class="form-label">ขนส่ง</label>
                            <select id="type" name="tracking_type" class="form-select">
                                <option value="EMS">EMS</option>
                                <option value="Kerry">Kerry</option>
                            </select>
                        </div>

                    </div>
                    <!-- END: Modal Body -->
                    <!-- BEGIN: Modal Footer -->
                    <div class="modal-footer">
                        <button type="button" data-tw-dismiss="modal"
                            class="btn btn-outline-secondary w-20 mr-1">ยกเลิก</button>
                        <button type="submit" class="btn btn-primary w-20">บันทึก</button>
                    </div>
                    <!-- END: Modal Footer -->
                </div>
            </form>
        </div>
    </div>
    <!-- END: Modal Content -->


    <div id="import_excel" class="modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    Import Excel
                </div>
                <form action="" method="post">
                    <div class="modal-body p-10 text-center">
                        <input type="file" class="form-control" required>
                    </div>

                    <div class="modal-footer">
                        <button type="button" data-tw-dismiss="modal"
                            class="btn btn-outline-secondary w-20 mr-1">ยกเลิก</button>
                        <button type="submit" class="btn btn-primary w-20">ตกลง</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection



@section('script')
    {{-- BEGIN data_table_branch --}}
    @include('backend.orders_list.data_table_orders')
    {{-- END data_table_branch --}}

    <script>
        function printErrorMsg(msg) {
            console.log(msg);
            $('._err').text('');
            $.each(msg, function(key, value) {
                $('.' + key + '_err').text(`*${value}*`);
            });
        }
    </script>


    <script>
        $('.report_pdf').click(function() {
            let type = $(this).data('type');
            let date_start = $('.date_start').val();
            let date_end = $('.date_end').val();



            if (date_start == '' && date_end == '') {
                Swal.fire({
                    icon: 'warning',
                    title: 'กรุณาเลือก',
                    text: 'วันที่เริ่มต้น วันที่สิ้นสุด',
                    showCancelButton: false,
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'ปิด',
                })
            } else {



                // บน serve ใช้อันนี้
                // let path = `/demo/admin/orders/report_order_pdf/${type}/${date_start}/${date_end}`

                // local
                let path = `/admin/orders/report_order_pdf/${type}/${date_start}/${date_end}`
                let full_url = location.protocol + '//' + location.host + path;


                window.open(`${full_url}`);
            }



        });
    </script>




    <script>
        $('.tracking_no_sort').click(function() {
            let date_start = $('.date_start').val();
            let date_end = $('.date_end').val();

            if (date_start == '' && date_end == '') {
                Swal.fire({
                    icon: 'warning',
                    title: 'กรุณาเลือก',
                    text: 'วันที่เริ่มต้น วันที่สิ้นสุด',
                    showCancelButton: false,
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'ปิด',
                })
            } else {

                Swal.fire({
                        title: 'รอสักครู่...',
                        html: 'ระบบกำลังประมวลผล',
                        didOpen: () => {
                            Swal.showLoading()
                        },
                    }),

                    $.ajax({
                        url: "{{ route('tracking_no_sort') }}",
                        type: 'post',
                        data: {
                            '_token': '{{ csrf_token() }}',
                            'date_start': date_start,
                            'date_end': date_end
                        },
                        success: function(data) {
                            Swal.close();
                            Swal.fire({
                                icon: 'success',
                                title: 'ทำรายการสำเร็จ',
                                text: '',
                                showCancelButton: false,
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'ปิด',
                            })
                        }

                    });
            }
        });
    </script>


    <script>
        $('.all_bill').click(function() {


            let date_start = $('.date_start').val();
            let date_end = $('.date_end').val();

            if (date_start == '' && date_end == '') {
                Swal.fire({
                    icon: 'warning',
                    title: 'กรุณาเลือก',
                    text: 'วันที่เริ่มต้น วันที่สิ้นสุด',
                    showCancelButton: false,
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'ปิด',
                })
            } else {
                Swal.fire({
                        title: 'รอสักครู่...',
                        html: 'ระบบกำลังเตรียมไฟล์ PDF...',
                        didOpen: () => {
                            Swal.showLoading()
                        },
                    }),

                    $.ajax({
                        url: "{{ route('view_detail_oeder_pdf') }}",
                        type: 'post',
                        data: {
                            '_token': '{{ csrf_token() }}',
                            'date_start': date_start,
                            'date_end': date_end
                        },
                        success: function(data) {
                            Swal.close();
                            const path = '/local/public/pdf/result.pdf';
                            window.open(path, "_blank");


                        }
                    });
            }

        });
    </script>



    <script>
        $('.orderexport').click(function() {

            let date_start = $('.date_start').val();
            let date_end = $('.date_end').val();


            if (date_start == '' && date_end == '') {
                Swal.fire({
                    icon: 'warning',
                    title: 'กรุณาเลือก',
                    text: 'วันที่เริ่มต้น วันที่สิ้นสุด',
                    showCancelButton: false,
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'ปิด',
                })
            } else {
                // บน Demo
                const path = '/demo/admin/orderexport' + '/' + date_start + '/' + date_end;
                // const path = '/mlm/admin/orderexport' + '/' + date_start + '/' + date_end;
                window.open(path, "_blank");
            }

        });
    </script>


    <script>
        $('#importorder').submit(function(e) {
            e.preventDefault();
            var formData = new FormData($(this)[0]);

            Swal.fire({
                    title: 'รอสักครู่...',
                    html: 'ระบบกำลังประมวลผล',
                    didOpen: () => {
                        Swal.showLoading()
                    },
                }),

                $.ajax({
                    url: '{{ route('importorder') }}',
                    method: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(data) {

                        var error_excel = data.error_excel;
                        var error_msg = data.error;

                        if (data.status == "success") {
                            Swal.close();
                            Swal.fire({
                                icon: 'success',
                                title: `บันทึกข้อมูลเรียบร้อย`,
                                confirmButtonColor: '#84CC18',
                                confirmButtonText: 'ยืนยัน',
                                timer: 3000,
                            }).then((result) => {
                                if (result.value) {
                                    window.location.reload();
                                }
                            });
                        }

                        if (error_msg) {
                            Swal.close();
                            printErrorMsg(data.error);
                        }
                        if (error_excel) {
                            error_modal(error_excel);

                        }

                    }
                });
        });
    </script>



    <script>
        function error_modal(data) {
            var ms = [];
            data.forEach((val, key) => {
                ms += `<p class="text-left ml-5" >แถวที่ ${val.row} : ${val.error}</p>`
            });

            Swal.fire({
                icon: 'error',
                title: `ข้อมูลไม่ครบถ้วน`,
                html: `${ms}`,
                confirmButtonColor: '#84CC18',
                confirmButtonText: 'ยืนยัน',
            }).then((result) => {
                if (result.value) {
                    window.location.reload();
                }
            });
        }
    </script>
@endsection
