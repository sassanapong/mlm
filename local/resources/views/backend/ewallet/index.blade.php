@extends('layouts.backend.app')



@section('head')
    {{-- select2 --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('css')
@endsection

@section('conten')
    @include('backend.navbar.navbar_mobile')
    <div class="flex overflow-hidden">

        @include('backend.navbar.navbar')
        <div class="content">
            @include('backend.navbar.top_bar')

            <h2 class="text-lg font-medium mr-auto mt-2">รายการ eWallet</h2>
            <div class="grid grid-cols-12 gap-5">
                <div class="col-span-12 ">
                    <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-4">


                        <div class="">
                            <div class="form-inline ">
                                <label for="" class="mr-1 ml- text-slate-500 ">คลัง : </label>

                                <select id="branch_select_filter" class="js-example-basic-single w-56 branch_select myWhere"
                                    name="branch_id_fk">
                                    <option value="0">ทั้งหมด</option>

                                </select>
                                <label for="" class="mr-1 ml-2 text-slate-500 ">สาขา : </label>

                                <select id="warehouse_select_filter"
                                    class="js-example-basic-single w-56 warehouse_select myWhere" name="warehouse_id_fk"
                                    disabled>
                                    <option value="0">ทั้งหมด</option>
                                </select>

                            </div>
                        </div>
                        <div class="hidden md:block mx-auto text-slate-500"></div>
                        <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">

                            <div class=" relative text-slate-500">
                                <div class="form-inline">
                                    <label for="" class="mr-2">ค้นหารหัสสาขา</label>
                                    <input type="text" name="b_code" class="form-control w-56 box pr-10 myLike "
                                        placeholder="ค้นหา...">
                                    <i class="w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0" data-lucide="search"></i>
                                </div>
                            </div>

                        </div>
                    </div>
                    <table id="table_ewallet" class="table table-report">
                    </table>
                </div>

            </div>

        </div>
    </div>





    <!-- BEGIN: Modal info_branch -->
    <div id="info_ewallet" class="modal" data-tw-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">

                <!-- BEGIN: Modal Header -->
                <div class="modal-header">
                    <h2 class="font-medium text-base mr-auto">รายละเอียดสาขา</h2>
                    <a data-tw-dismiss="modal" href="javascript:;"> <i data-lucide="x" class="w-8 h-8 text-slate-400"></i>
                    </a>
                </div> <!-- END: Modal Header -->
                <!-- BEGIN: Modal Body -->
                <div class="modal-body grid grid-cols-12 gap-4 gap-y-3 bg-slate-100/50">
                    <div class="col-span-12 ">
                        <h2 class="text-lg font-medium mr-auto mt-2"> รายการ ฝากเงิน</h2>
                    </div>
                    <div class=" col-span-12 ">
                        <div class="grid grid-cols-12 gap-5 ">

                            <div class="col-span-6 box p-3 text-center">
                                <img class="img_doc_info mt-2 mx-auto" src=''>

                            </div>
                            <div class="col-span-6  p-3 text-center">

                                <div class="grid grid-cols-12 gap-5 ">
                                    <div class="col-span-12 box p-3">
                                        <p class="mt-2 text-left">รหัสรายการ <span id="transaction_code"></span> </p>
                                        <p class="mt-2 text-left">วันที่ทำรายการ <span id="ewallet_created_at"></span> </p>
                                        <P class="mt-2 text-left">สมาชิก <span id="name"></span> </P>
                                        <p class="text-xl mt-5"> จำนวนเงินฝาก <span class="text-danger amt"></span>
                                            บาท</p>
                                    </div>

                                    <div class="col-span-12 box p-3">
                                        <form id="form_approve" method='POST'>
                                            @csrf
                                            <input type="hidden" name="ewallet_id" value="">
                                            <input type="hidden" name="amt" value="">
                                            <input type="hidden" name="customers_id_fk" value="">
                                            <div class="form-inline">
                                                <label class="form-label sm:w-20">วันที่โอน <span
                                                        class="text-danger date_err _err"></span> </label>
                                                <input class="form-control"type="date" name="date">
                                            </div>
                                            <div class="form-inline mt-2">
                                                <label class="form-label sm:w-20">เวลาโอน <span
                                                        class="text-danger time_err _err"></span></label>
                                                <input class="form-control" type="time" name="time">
                                            </div>
                                            <div class="form-inline mt-2">
                                                <label class="form-label sm:w-20">เลขที่อ้างอิง <span
                                                        class="text-danger code_refer_err _err"></span></label>
                                                <input class="form-control" type="text" placeholder="เลขที่อ้างอิง"
                                                    name="code_refer">
                                            </div>
                                            <div class="form-inline mt-2">
                                                <label class="form-label sm:w-20">แก้ไขยอดเงิน</label>
                                                <input class="form-control" type="text" placeholder="แก้ไขยอดเงิน"
                                                    name="edit_amt">
                                            </div>
                                            <button type="submit" class="btn btn-primary mt-2  mr-1">อนุมัติ
                                                eWallet</button>
                                        </form>
                                    </div>
                                    <div class="col-span-12 box p-3">
                                        <div>
                                            <label>ยกเลิกรายการ</label>
                                            <div class="form-check mt-2">
                                                <input id="radio_1" class="form-check-input" type="radio"
                                                    name="vertical_radio_button" value="1">
                                                <label class="form-check-label"
                                                    for="radio_1">ยอดเงินไม่ตรงกับที่แจ้ง</label>
                                            </div>
                                            <div class="form-check mt-2">
                                                <input id="radio_2" class="form-check-input" type="radio"
                                                    name="vertical_radio_button" value="2">
                                                <label class="form-check-label" for="radio_2">ใช้ Slip ซ้ำ</label>
                                            </div>
                                            <div class="form-check mt-2">
                                                <input id="radio_3" class="form-check-input" type="radio"
                                                    name="vertical_radio_button" value="3">
                                                <label class="form-check-label" for="radio_3">ไม่ใช่บัญชีบริษัท</label>
                                            </div>
                                            <div class="form-check mt-2">
                                                <input id="radio_3" class="form-check-input" type="radio"
                                                    name="vertical_radio_button" value="4">
                                                <label class="form-check-label" for="radio_3">ภาพไม่ชัด</label>
                                            </div>
                                            <div class="form-check mt-2">
                                                <input id="radio_3" class="form-check-input" type="radio"
                                                    name="vertical_radio_button" value="5">
                                                <label class="form-check-label" for="radio_3">ไม่ใช้ภาพ Slip</label>
                                            </div>
                                            <div class="form-check mt-2">
                                                <input id="radio_3" class="form-check-input" type="radio"
                                                    name="vertical_radio_button" value="6">
                                                <label class="form-check-label" for="radio_3">อื่น ๆ </label>
                                            </div>
                                            <div>
                                                <textarea name="info_other" class="form-control p-2 mt-2" placeholder="รายละเอียด..." rows="5"
                                                    cols="40"></textarea>
                                            </div>
                                            <button type="button" data-tw-dismiss="modal"
                                                class="btn btn-outline-danger mt-2 mr-1">ไม่อนุมัติ</button>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- END: Modal Body -->
                <!-- BEGIN: Modal Footer -->
                <div class="modal-footer">
                    <button type="button" data-tw-dismiss="modal" class="btn btn-outline-danger w-20 mr-1">ปิด</button>
                </div> <!-- END: Modal Footer -->
            </div>

        </div>
    </div> <!-- END: Modal info_branch -->
@endsection



@section('script')
    {{-- BEGIN data_table_branch --}}
    @include('backend.ewallet.data_table_ewallet')
    {{-- END data_table_branch --}}




    <script>
        function get_data_info_ewallet(id) {
            $.ajax({
                url: '{{ route('get_info_ewallet') }}',
                method: 'POST',
                data: {
                    '_token': '{{ csrf_token() }}',
                    'id': id
                },
                success: function(data) {


                    create_info_modal(data)
                }
            });
        }


        function create_info_modal(data) {


            data.forEach((val, key) => {

                let amt_bath = new Intl.NumberFormat('en-US').format(val.amt);
                if (val.type == 1) {

                    $('#transaction_code').text(val.transaction_code);
                    $('#ewallet_created_at').text(val.ewallet_created_at);
                    $('#name').text(val.name);
                    $('.amt').text(amt_bath);
                }
            });

        }
    </script>



    <script>
        function printErrorMsg(msg) {

            $('._err').text('');
            $.each(msg, function(key, value) {
                $('.' + key + '_err').text(`*${value}*`);
            });
        }
    </script>

    <script>
        $('#form_approve').submit(function(e) {
            e.preventDefault();
            var formData = new FormData($(this)[0]);
            $.ajax({
                url: '{{ route('approve_update_ewallet') }}',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(data) {
                    if ($.isEmptyObject(data.error) || data.status == "success") {

                        Swal.fire({
                            icon: 'success',
                            title: 'บันทึกสำเร็จ',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'ปิด',

                        }).then((result) => {
                            table_warehouse.draw();
                        })
                    } else if ('duplicate_code_refer') {
                        Swal.fire({
                            icon: 'error',
                            title: 'ข้อมูลซ้ำ',
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
@endsection
