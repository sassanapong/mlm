@extends('layouts.backend.app_new')



@section('head')
@endsection

@section('css')
@endsection

@section('content')

            @include('backend.navbar.top_bar')
            <h2 class="text-lg font-medium mr-auto mt-2">จัดการคลัง สาขา {{ $branch[0]['b_code'] }} :
                {{ $branch[0]['b_name'] }}
            </h2>



            <div class="box p-2 mt-2">
                <form id="form_warehoues" method="post">
                    @csrf
                    <input type="hidden" name="branch_id_fk" value="{{ $branch[0]['id'] }}">
                    <div class="grid grid-cols-12">

                        <div class="col-span-12 ">
                            <label for="regular-form-1" class="form-label">สาขา</label>
                            <span class="form-label text-danger w_code_err _err"></span>
                            <input id="regular-form-1" type="text"
                                value="{{ $branch[0]['b_code'] }} : {{ $branch[0]['b_name'] }}" class="form-control"
                                name="w_code" readonly>
                        </div>
                        <div class="col-span-12 ">
                            <label for="regular-form-1" class="form-label">รหัสคลัง</label>
                            <span class="form-label text-danger w_code_err _err"></span>
                            <input id="regular-form-1" type="text" class="form-control" name="w_code"
                                placeholder="ชื่อคลัง">
                        </div>
                        <div class="col-span-12">
                            <label for="regular-form-1" class="form-label">ชื่อคลัง</label>
                            <span class="form-label text-danger w_name_err _err"></span>
                            <input id="regular-form-1" type="text" class="form-control" name="w_name"
                                placeholder="ชื่อคลัง">
                        </div>

                        <div class="col-span-12 ">
                            <label for="regular-form-1" class="form-label">รายละเอียด</label>
                            <span class="form-label text-danger w_details_err _err"></span>
                            <textarea class="form-control  p-2" name="w_details" id="" cols="150" rows="5"
                                placeholder="รายละเอียด..."></textarea>
                        </div>

                        <div class="col-span-12">
                            <label>สถานะ ปิด/เปิด</label>
                            <div class="form-switch mt-2">
                                <input type="checkbox" name="status" class="form-check-input" value="1">
                            </div>
                        </div>
                        <div class="col-span-12 mt-2 flex justify-between ">
                            <a href="{{ route('branch') }}" class=" btn btn-sm btn-secondary "> <i
                                    class="fa-solid fa-arrow-left mr-2"></i> ย้อนกลับ</a>
                            <button type="submit" class=" btn btn-sm btn-primary">บันทึก</button>
                        </div>

                    </div>
                </form>
            </div>

            <div class="grid grid-cols-12">
                <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-4">

                    <div class="">
                        <div class="form-inline ">
                            <label for="" class="mr-2 text-slate-500 ">สถานะ : </label>
                            <select name="status" class="w-32 form-select box mt-3 sm:mt-0 myWhere ">
                                <option value="">ทั้งหมด</option>
                                <option value="1">เปิดใช้งาน</option>
                                <option value="99">ไม่เปิดการใช้งาน</option>
                            </select>
                        </div>
                    </div>
                    <div class="hidden md:block mx-auto text-slate-500"></div>
                    <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">

                        <div class=" relative text-slate-500">
                            <div class="form-inline">
                                <label for="" class="mr-2">ค้นหารหัสคลัง</label>
                                <input type="text" name="w_code" class="form-control w-56 box pr-10 myLike "
                                    placeholder="ค้นหา...">
                                <i class="w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0" data-lucide="search"></i>
                            </div>
                        </div>

                    </div>

                </div>

                <div class="col-span-12">

                    <table id="table_warehouse" class="table table-report">
                    </table>
                </div>
            </div>


    <!-- BEGIN: Modal info_warehouse -->
    <div id="info_warehouse" class="modal" data-tw-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <form id="form_edit_warehoues" method="post">
                    @csrf
                    <input type="hidden" id="id" name="id">
                    <input type="hidden" id="branch_id_fk" name="branch_id_fk">
                    <!-- BEGIN: Modal Header -->
                    <div class="modal-header">
                        <h2 class="font-medium text-base mr-auto">รายละเอียดคลัง</h2>
                        <a data-tw-dismiss="modal" href="javascript:;"> <i data-lucide="x"
                                class="w-8 h-8 text-slate-400"></i>
                        </a>
                    </div> <!-- END: Modal Header -->
                    <!-- BEGIN: Modal Body -->
                    <div class="modal-body grid grid-cols-12 gap-4 gap-y-3 bg-slate-100/50">
                        <div class="col-span-12 ">
                            <label for="w_code" class="form-label">รหัสคลัง</label>
                            <span class="form-label text-danger w_code_err _err"></span>
                            <input id="w_code" type="text" class="form-control" name="w_code"
                                placeholder="ชื่อคลัง" readonly>
                        </div>
                        <div class="col-span-12">
                            <label for="w_name" class="form-label">ชื่อคลัง</label>
                            <span class="form-label text-danger w_name_err _err"></span>
                            <input id="w_name" type="text" class="form-control" name="w_name"
                                placeholder="ชื่อคลัง">
                        </div>

                        <div class="col-span-12 ">
                            <label for="w_details" class="form-label">รายละเอียด</label>
                            <span class="form-label text-danger w_details_err _err"></span>
                            <textarea class="form-control  p-2" name="w_details" id="w_details" cols="150" rows="5"
                                placeholder="รายละเอียด..."></textarea>
                        </div>

                        <div class="col-span-12">
                            <label>สถานะ ปิด/เปิด</label>
                            <div class="form-switch mt-2">
                                <input type="checkbox" id="status" name="status" class="form-check-input"
                                    value="1">
                            </div>
                        </div>

                    </div> <!-- END: Modal Body -->
                    <!-- BEGIN: Modal Footer -->
                    <div class="modal-footer">
                        <button type="button" data-tw-dismiss="modal"
                            class="btn btn-outline-danger w-20 mr-1">ยกเลิก</button>
                        <button type="submit" class="btn btn-primary  w-20">ตกลง</button>
                    </div> <!-- END: Modal Footer -->
            </div>
            </form>
        </div>
    </div> <!-- END: Modal info_warehouse -->
@endsection




@section('script')
<script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.2/js/dataTables.buttons.min.js"></script>
    {{-- BEGIN print err input --}}
    <script>
        function printErrorMsg(msg) {

            $('._err').text('');
            $.each(msg, function(key, value) {
                $('.' + key + '_err').text(`*${value}*`);
            });
        }

        function resetForm() {
            $('#form_warehoues')[0].reset();
            $('._err').text('');
        }
    </script>
    {{-- END print err input --}}

    {{-- //BEGIN form_warehoues --}}
    <script>
        $('#form_warehoues').submit(function(e) {

            e.preventDefault();
            var formData = new FormData($(this)[0]);
            $.ajax({
                url: '{{ route('store_warehoues') }}',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(data) {
                    if ($.isEmptyObject(data.error) || data.status == "success") {
                        resetForm();
                        Swal.fire({
                            icon: 'success',
                            title: 'บันทึกสำเร็จ',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'ปิด',

                        }).then((result) => {
                            table_warehouse.draw();
                        })
                    } else {
                        printErrorMsg(data.error);
                    }
                }
            });
        });
    </script>
    {{-- //END form_warehoues --}}


    {{-- BEGIN get_data_info_warehouse --}}
    <script>
        function get_data_info_warehouse(id) {
            $.ajax({
                url: `{{ route('get_data_info_warehouse') }}`,
                method: 'POST',
                data: {
                    '_token': '{{ csrf_token() }}',
                    'id': id
                },
                success: function(data) {
                    append_data_warehouse(data);
                }
            });
        }

        function append_data_warehouse(data) {
            for (const [key, value] of Object.entries(data)) {
                $('#info_warehouse').find('#' + key).val(value);
            }
            //BEGIN  checkbox_status
            if (data.status == 1) {
                $('#info_warehouse').find('#status').prop('checked', true);
            } else {
                $('#info_warehouse').find('#status').prop('checked', false);
            }
            //END  checkbox_status
        }
    </script>

    {{-- END get_data_info_warehouse --}}


    {{-- //BEGIN form_warehoues --}}
    <script>
        $('#form_edit_warehoues').submit(function(e) {
            const myModal = tailwind.Modal.getInstance(document.querySelector("#info_warehouse"));
            e.preventDefault();
            var formData = new FormData($(this)[0]);
            $.ajax({
                url: '{{ route('update_warehouse') }}',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(data) {
                    if ($.isEmptyObject(data.error) || data.status == "success") {

                        myModal.hide();
                        Swal.fire({
                            icon: 'success',
                            title: 'บันทึกสำเร็จ',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'ปิด',

                        }).then((result) => {
                            table_warehouse.draw();
                        })
                    } else {
                        printErrorMsg(data.error);
                    }
                }
            });
        });
    </script>
    {{-- //END form_warehoues --}}


    {{-- BEGIN data_table_branch --}}
    @include('backend.stock.warehouse.data_table_warehouse')
    {{-- END data_table_branch --}}
@endsection
