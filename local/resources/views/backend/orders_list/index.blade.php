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

                    <div class="grid grid-cols-12 gap-20 mt-4">
                        <div class="col-span-2">
                            <label for="">วันที่เริ่มต้น</label>
                            <input type="date" name="date_start" class="form-control w-56 myCustom">
                        </div>

                        <div class="col-span-2">
                            <label for="">วันที่สิ้นสุด</label>
                            <input type="date" name="date_end" class="form-control w-56 myCustom">
                        </div>

                        <div class="col-span-3 mt-4">
                            <div class="form-inline ">
                                <button type="submit" data-tw-toggle="modal" data-tw-target="#import_excel"
                                    class="btn btn-outline-primary w-35 inline-block ml-1 mr-2">Import
                                    Order
                                </button>


                                <button class="btn btn-outline-pending  w-35 inline-block ml-1 mr-2"
                                    href="{{ route('orderexport') }}" target="_blank">
                                    Export Order </button>
                                <button type="submit" class="btn btn-warning  mr-2"> <i class="fa-solid fa-print"></i>
                                </button>
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
@endsection
