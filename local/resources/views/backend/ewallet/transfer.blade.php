@extends('layouts.backend.app_new')



@section('head')
    {{-- select2 --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('css')
@endsection
@section('head_text')
<nav aria-label="breadcrumb" class="-intro-x mr-auto hidden sm:flex">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">กระเป๋าเงิน</a></li>
        <li class="breadcrumb-item active" aria-current="page">รายการ โอนเงิน</li>
    </ol>
</nav>
@endsection
@section('content')


            <h2 class="text-lg font-medium mr-auto mt-2">รายการ โอนเงิน</h2>

            <div class="row intro-y col-span-12 flex flex-wrap xl:flex-nowrap items-center mt-2">
                <div class="col-md-4 col-lg-4  items-center sm:mr-4">


                        <div class="col-span-4 sm:col-span-4">
                            <label for="modal-datepicker-1" class="form-label">รหัสรายการ</label>

                            <div class=" relative text-slate-500">
                                <div class="form-inline">

                                    <input type="text" name="transaction_code"
                                        class="form-control sm:w-40 2xl:w-full mt-2 sm:mt-0" placeholder="รหัสรายการ...">
                                    <i class="w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0" data-lucide="search"></i>
                                </div>
                            </div>
                         </div>

                    </div>
                    <div class="col-md-4 col-lg-4  items-center sm:mr-4">
                    <div class="col-span-4 sm:col-span-4">

                        <label for="modal-datepicker-1" class="form-label">รหัสสมาชิก</label>
                        <div class=" relative text-slate-500">
                        <div class="form-inline">

                            <input type="text" name="user_name" class="form-control w-56 box pr-10 myLike "
                                placeholder="รหัสสมาชิก...">
                            <i class="w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0" data-lucide="search"></i>
                        </div>
                        </div>
                     </div>
                    </div>

                    <div class="col-md-4 col-lg-4  items-center sm:mr-4">
                        <div class="col-span-4 sm:col-span-4">

                            <label for="modal-datepicker-1" class="form-label">ชื่อสมาชิก</label>
                            <div class=" relative text-slate-500">
                                <div class="form-inline">

                                    <input type="text" name="customers.name" class="form-control w-56 box pr-10 myLike "
                                        placeholder="ชื่อสมาชิก...">
                                    <i class="w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0" data-lucide="search"></i>
                                </div>
                            </div>
                         </div>
                        </div>

                    <div class="col-md-4 col-lg-4  items-center sm:mr-4">
                        <div class="col-span-4 sm:col-span-4">

                            <label for="modal-datepicker-1" class="form-label">สถานะ</label>
                            <div class=" relative text-slate-500">

                                <div class="form-inline ">
                                <select class="form-select w-56  myWhere" name="status">
                                    <option value="0">ทั้งหมด</option>
                                    <option selected value="1">รออนุมัติ</option>
                                    <option value="2">อนุมัติ</option>
                                    <option value="3">ไม่อนุมัติ</option>
                                </select>
                                </div>
                            </div>
                         </div>
                        </div>
            </div>
            <div class="grid grid-cols-12 gap-5">
                <div class="col-span-12 ">

                    <div class="overflow-x-auto">
                        <div class="table-responsive">
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
                        <h2 class="text-lg font-medium mr-auto mt-2 h2"> รายการ ฝากเงิน</h2>
                    </div>
                    <div class="col-span-12 ">
                        <div class="grid grid-cols-12 gap-5 ">

                            <div class="col-span-6 box p-3 text-center" id="img_doc">
                                <img class="img_doc_info mt-2 mx-auto" src=''>
                            </div>
                            <div class="col-span-6  p-3 text-center">

                                <div class="grid grid-cols-12 gap-5 ">
                                    <div class="col-span-12 box p-3">
                                        <p class="mt-2 text-left">รหัสรายการ <span id="transaction_code"></span> </p>
                                        <p class="mt-2 text-left">วันที่ทำรายการ <span id="ewallet_created_at"></span> </p>
                                        <P class="mt-2 text-left">สมาชิก <span id="name"></span> </P>
                                        <p class="text-xl mt-5 type"> จำนวนเงินฝาก <span class="text-danger amt"></span>
                                            บาท</p>
                                    </div>

                                    <div class="box_info col-span-12 box p-3">
                                        <form id="form_approve" method='POST'>
                                            @csrf
                                            <input type="hidden" class="ewallet_id" name="ewallet_id" value="">
                                            <input type="hidden" id="amt" name="amt" value="">
                                            <input type="hidden" id="customers_id_fk" name="customers_id_fk"
                                                value="">
                                            <span class="text-danger date_err _err"></span>
                                            <div class="form-inline">
                                                <label class="form-label sm:w-20">วันที่โอน </label>
                                                <input class="form-control"type="date" name="date">
                                            </div>
                                            <span class="text-danger time_err _err"></span>
                                            <div class="form-inline mt-2">
                                                <label class="form-label sm:w-20">เวลาโอน </label>
                                                <input class="form-control" type="time" name="time">
                                            </div>
                                            <span class="text-danger code_refer_err _err"></span>
                                            <div class="form-inline mt-2">
                                                <label class="form-label sm:w-20">เลขที่อ้างอิง </label>
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

                                    <div class="box_info col-span-12 box p-3">
                                        <form id="form_disapproved" method='POST'>
                                            @csrf
                                            <input type="hidden" class="ewallet_id" name="ewallet_id" value="">
                                            <div>
                                                <label>ยกเลิกรายการ</label>

                                                <div class="form-check mt-2">
                                                    <input id="radio_1" class="form-check-input" type="radio"
                                                        name="vertical_radio_button" value="ใช้ Slip ซ้ำ">
                                                    <label class="form-check-label" for="radio_1">ใช้ Slip
                                                        ซ้ำ</label>
                                                </div>
                                                <div class="form-check mt-2">
                                                    <input id="radio_2" class="form-check-input" type="radio"
                                                        name="vertical_radio_button" value="ไม่ใช่บัญชีบริษัท">
                                                    <label class="form-check-label"
                                                        for="radio_2">ไม่ใช่บัญชีบริษัท</label>
                                                </div>
                                                <div class="form-check mt-2">
                                                    <input id="radio_3" class="form-check-input" type="radio"
                                                        name="vertical_radio_button" value="ภาพไม่ชัด">
                                                    <label class="form-check-label" for="radio_3">ภาพไม่ชัด</label>
                                                </div>
                                                <div class="form-check mt-2">
                                                    <input id="radio_4" class="form-check-input" type="radio"
                                                        name="vertical_radio_button" value="ไม่ใช้ภาพ Slip">
                                                    <label class="form-check-label" for="radio_4">ไม่ใช้ภาพ
                                                        Slip</label>
                                                </div>
                                                <div class="form-check mt-2">
                                                    <input id="radio_5" class="form-check-input" type="radio"
                                                        name="vertical_radio_button" value="อื่นๆ">
                                                    <label class="form-check-label" for="radio_5">อื่น ๆ </label>
                                                </div>
                                                <div>
                                                    <p class="text-danger info_other_err _err"></p>
                                                    <textarea name="info_other" id="info_other" class="form-control p-2 mt-2" placeholder="รายละเอียด..."
                                                        rows="5" cols="40"></textarea>
                                                </div>
                                                <button type="submit"
                                                    class="btn btn-outline-danger mt-2 mr-1">ไม่อนุมัติ</button>
                                            </div>
                                        </form>
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
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.2/js/dataTables.buttons.min.js"></script>
    @include('backend.ewallet.data_table_transfer')
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
            $('#info_ewallet').find('.box_info').show();
            data.data.forEach((val, key) => {

                if (val.type == 1) {
                    $('#img_doc').show()
                    $('.h2').html('รายการ ฝากเงิน')
                    $('.type').html('จำนวนเงินฝาก <span class="text-danger amt"></span> บาท')
                    $('.ewallet_id').val(val.ewallet_id);
                    $('#customers_id_fk').val(val.customers_id_fk);
                    $('#amt').val(val.amt);

                    $('#transaction_code').text(val.transaction_code);
                    $('#ewallet_created_at').text(val.ewallet_created_at);
                    $('#name').text(val.name);
                    $('.amt').text(data.data_amt);
                    $(".img_doc_info").attr("src", `{{ asset('') }}/${val.url}/${val.file_ewllet}`);
                    if (val.status != 1) {
                        $('#info_ewallet').find('.box_info').hide();
                    }
                } else if (val.type == 2) {
                    $('#img_doc').hide()
                    $('.h2').html('รายการ โอนเงิน')
                    $('.type').html('จำนวนเงินโอน <span class="text-danger amt"></span> บาท')
                    $('.ewallet_id').val(val.ewallet_id);
                    $('#customers_id_fk').val(val.customers_id_fk);
                    $('#amt').val(val.amt);

                    $('#transaction_code').text(val.transaction_code);
                    $('#ewallet_created_at').text(val.ewallet_created_at);
                    $('#name').text(val.name);
                    $('.amt').text(data.data_amt);
                    $(".img_doc_info").attr("src", `{{ asset('') }}/${val.url}/${val.file_ewllet}`);
                    if (val.status != 1) {
                        $('#info_ewallet').find('.box_info').hide();
                    }
                } else if (val.type == 3) {
                    $('#img_doc').hide()
                    $('.h2').html('รายการ ถอนเงิน')
                    $('.type').html('จำนวนเงินถอน <span class="text-danger amt"></span> บาท')
                    $('.ewallet_id').val(val.ewallet_id);
                    $('#customers_id_fk').val(val.customers_id_fk);
                    $('#amt').val(val.amt);

                    $('#transaction_code').text(val.transaction_code);
                    $('#ewallet_created_at').text(val.ewallet_created_at);
                    $('#name').text(val.name);
                    $('.amt').text(data.data_amt);
                    $(".img_doc_info").attr("src", `{{ asset('') }}/${val.url}/${val.file_ewllet}`);
                    if (val.status != 1) {
                        $('#info_ewallet').find('.box_info').hide();
                    }
                }
            });
        }
    </script>



    <script>
        function printErrorMsg(msg) {

            $('._err').text('');
            $.each(msg, function(key, value) {
                let class_name = key.split(".").join("_");
                $('.' + class_name + '_err').text(`*${value}*`);
            });
        }
    </script>

    {{-- form_approve --}}
    <script>
        $('#form_approve').submit(function(e) {
            const myModal = tailwind.Modal.getInstance(document.querySelector("#info_ewallet"));
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
                        myModal.hide();
                        Swal.fire({
                            icon: 'success',
                            title: 'บันทึกสำเร็จ',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'ปิด',

                        }).then((result) => {
                            table_ewallet.draw();
                        })
                    } else {
                        printErrorMsg(data.error);
                    }
                }
            });
        });
    </script>
    {{-- form_approve --}}

    {{-- form_disapproved --}}
    <script>
        $('#form_disapproved').submit(function(e) {
            const myModal = tailwind.Modal.getInstance(document.querySelector("#info_ewallet"));
            e.preventDefault();
            var formData = new FormData($(this)[0]);
            $.ajax({
                url: '{{ route('disapproved_update_ewallet') }}',
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
                            table_ewallet.draw();
                        })
                    } else {
                        printErrorMsg(data.error);
                    }
                }
            });
        });
    </script>
    {{-- form_disapproved --}}


    {{-- info_other radio --}}
    <script>
        $('#info_other').hide();


        $('.form-check-input').change(function() {

            if ($(this).val() == 'อื่นๆ') {
                $('#info_other').show();
            } else {
                $('#info_other').hide();
            }
        });
    </script>
@endsection
