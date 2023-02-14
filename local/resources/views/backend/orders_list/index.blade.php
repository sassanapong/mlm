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
                            <div class="form-inline ">
                                {{-- <label for="" class="mr-1  text-slate-500 ">ประเภท : </label>
                                <select class="form-select w-56  myWhere" name="type">
                                    <option value="0">ทั้งหมด</option>
                                    <option value="1">ฝากเงิน</option>
                                    <option value="2">โอนเงิน</option>
                                    <option value="3">ถอนเงิน</option>
                                </select>
                                <label for="" class="ml-2  text-slate-500 ">สถานะ : </label>
                                <select class="form-select w-56  myWhere" name="status">
                                    <option value="0">ทั้งหมด</option>
                                    <option value="1">รออนุมัติ</option>
                                    <option value="2">อนุมัติ</option>
                                    <option value="3">ไม่อนุมัติ</option>
                                </select> --}}
                            </div>
                        </div>
                        <div class="">
                            <form action="{{ route('importorder') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="form-inline ">
                                    <input name="excel" type="file" required
                                        class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100">
                                </div>
                        </div>
                        <div class="">
                            <div class="form-inline ">
                                <button type="submit" class="btn btn-outline-primary w-35 inline-block ml-1">Import Order
                                    Status </button>
                            </div>
                            </form>
                        </div>
                        <div class="">
                            <div class="form-inline ">
                                <a class="btn btn-outline-pending  w-35 inline-block ml-1" href="{{ route('orderexport') }}"
                                    target="_blank">
                                    Export Order Status </a>
                            </div>
                        </div>
                        <div class="hidden md:block mx-auto text-slate-500"></div>
                        <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">


                            <div class="grid grid-cols-12 gap-5">
                                <div class="col-span-6 ">
                                    <div class="form-inline">
                                        <label for="" class="text-slate-500  ">ออกใบปะหน้า : </label>
                                        <div class="relative  mx-auto ml-2">
                                            <div
                                                class="absolute rounded-l w-10 h-full flex items-center justify-center bg-slate-100 border text-slate-500 dark:bg-darkmode-700 dark:border-darkmode-800 dark:text-slate-400">
                                                <i data-lucide="calendar" class="w-4 h-4"></i>
                                            </div>
                                            @php
                                                $data_now = date('Y-m-d');
                                            @endphp
                                            <input name="date" type="date" class="form-control pl-12 date_pdf"
                                                value="{{ $data_now }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-span-6 ">
                                    <div class="dropdown">
                                        <p class="dropdown-toggle btn btn-primary" aria-expanded="false"
                                            data-tw-toggle="dropdown">ออกใบปะหน้า</p>
                                        <div class="dropdown-menu w-40">
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
                                </div>
                            </div>

                        </div>
                    </div>



                    <div class="grid grid-cols-12 gap-20 mt-4">
                        <div class="col-span-2">
                            <label for="">วันที่เริ่มต้น</label>
                            <input type="date" name="date_start" class="form-control w-56 myCustom">
                        </div>

                        <div class="col-span-2">
                            <label for="">วันที่สิ้นสุด</label>
                            <input type="date" name="date_end" class="form-control w-56 myCustom">
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
@endsection



@section('script')
    {{-- BEGIN data_table_branch --}}
    @include('backend.orders_list.data_table_orders')
    {{-- END data_table_branch --}}




    <script>
        $('.report_pdf').click(function() {
            let type = $(this).data('type');
            let data = $('.date_pdf').val();


            // บน serve ใช้อันนี้
            // let path = `/admin/orders/report_order_pdf/${type}/${data}`

            // local
            let path = `/mlm/admin/orders/report_order_pdf/${type}/${data}`
            let full_url = location.protocol + '//' + location.host + path;

            window.open(`${full_url}`);
        });
    </script>
@endsection
