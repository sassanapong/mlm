@extends('layouts.backend.app_new')



@section('head')
    {{-- select2 --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection
@section('head_text')
<nav aria-label="breadcrumb" class="-intro-x mr-auto hidden sm:flex">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">จัดการการขาย</a></li>
        <li class="breadcrumb-item active" aria-current="page"> รายการ คำสั่งซื้อ</li>
    </ol>
</nav>
@endsection
@section('css')
@endsection

@section('content')


            <h2 class="text-lg font-medium mr-auto mt-2">รายการ คำสั่งซื้อ</h2>
            <div class="grid grid-cols-12 gap-5">
                <div class="col-span-12 ">
                    <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-4">

                    </div>
                    <div class="overflow-x-auto">
                        <div class="table-responsive">
                    <table id="table_orders" class="table table-report">
                    </table>
                        </div>
                    </div>
                </div>

            </div>


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
                            <input type="hidden" name="page" value="success">
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
                                        <option value="Kerry">Kerry</option>
                                        <option value="Flash">Flash</option>
                                        <option value="Ems">Ems</option>
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


@endsection



@section('script')
<script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.2/js/dataTables.buttons.min.js"></script>
    {{-- BEGIN data_table_branch --}}
    @include('backend.orders_list.data_table_orders_success')
    {{-- END data_table_branch --}}

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

@endsection
