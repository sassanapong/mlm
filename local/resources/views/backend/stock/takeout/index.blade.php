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
        <li class="breadcrumb-item"><a href="#">คลังสินค้า</a></li>
        <li class="breadcrumb-item active" aria-current="page">ออกสินค้า</li>
    </ol>
</nav>
@endsection
@section('content')


            <h2 class="text-lg font-medium mr-auto mt-2">ออกสินค้า</h2>
            <div class="grid grid-cols-12 gap-5">
                <div class="col-span-12 ">
                    <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-4">

                        <div class="">
                            <button onclick="resetForm()" class="btn btn-primary shadow-md mr-2" data-tw-toggle="modal"
                                data-tw-target="#add_product">
                                ออกสินค้า</button>
                        </div>
                        <div class="">
                            <div class="form-inline ">
                                <label for="" class="mr-1 ml- text-slate-500 ">คลัง : </label>

                                <select id="branch_select_filter" class="js-example-basic-single w-56 branch_select myWhere"
                                    name="branch_id_fk">
                                    <option value="0">ทั้งหมด</option>
                                    @foreach ($branch as $val)
                                        <option value="{{ $val->id }}">{{ $val->b_code }}::{{ $val->b_name }}
                                        </option>
                                    @endforeach
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
                    <table id="table_takeout" class="table table-report">
                    </table>
                </div>

            </div>






    <!-- BEGIN: Modal add_product -->
    <div id="add_product" class="modal" data-tw-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <form id="form_add_product" method="post">
                    @csrf
                    <!-- BEGIN: Modal Header -->
                    <div class="modal-header">
                        <h2 class="font-medium text-base mr-auto">ออกสินค้า</h2>
                        <a data-tw-dismiss="modal" href="javascript:;"> <i data-lucide="x"
                                class="w-8 h-8 text-slate-400"></i>
                        </a>
                    </div> <!-- END: Modal Header -->
                    <!-- BEGIN: Modal Body -->
                    <div class="modal-body grid grid-cols-12 gap-4 gap-y-3 bg-slate-100/50">
                        <div class="col-span-12 ">
                            <div class="">
                                <label for="">สาขา</label>
                                <span class="form-label text-danger branch_id_fk_err _err"></span>
                                <select class="js-example-basic-single w-full branch_select" name="branch_id_fk">
                                    <option selected disabled>==== เลือกสาขา ====</option>
                                    @foreach ($branch as $val)
                                        <option value="{{ $val->id }}">{{ $val->b_code }}::{{ $val->b_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mt-2">
                                <label for="">คลัง</label>
                                <span class="form-label text-danger warehouse_id_fk_err _err"></span>
                                <select class="js-example-basic-single w-full warehouse_select" name="warehouse_id_fk"
                                    disabled>
                                    <option selected disabled>==== เลือกคลัง ====</option>
                                </select>
                            </div>
                            <div class="mt-2">
                                <label for="">สินค้า</label>
                                <span class="form-label text-danger materials_id_fk_err _err"></span>
                                <select id="product_select" class="js-example-basic-single w-full" name="materials_id_fk"
                                    disabled>
                                    <option selected disabled>==== เลือกสินค้า ====</option>
                                </select>
                            </div>

                            <div class="mt-4">
                                <div class="grid grid-cols-12 gap-5">
                                    <div class=" col-span-6">
                                        <label for="doc_no" class="form-label">เลขที่เอกสาร</label>
                                        <span class="form-label text-danger doc_no_err _err"></span>
                                        <input id="doc_no" type="text" class="form-control " name="doc_no"
                                            placeholder="เลขที่เอกสาร">
                                    </div>
                                    <div class=" col-span-4">
                                        <label for="doc_date" class="form-label">วันที่เอกสาร</label>
                                        <span class="form-label text-danger doc_date_err _err"></span>
                                        <input id="doc_date" type="date" class="form-control " name="doc_date"
                                            placeholder="วันที่เอกสาร">
                                    </div>
                                    <div class=" col-span-6">
                                        <label for="lot_number" class="form-label">หมายเลขล็อตสินค้า</label>
                                        <span class="form-label text-danger lot_number_err _err"></span>

                                        <select id="lot_number" name="lot_number" class="form-select mt-2 sm:mr-2"
                                            aria-label="Default select example">
                                            <option disabled selected>==== เลือกล็อตสินค้า ==== </option>

                                        </select>
                                    </div>
                                    <div class=" col-span-6">
                                        <label for="lot_expired_date" class="form-label">วันหมดอายุ</label>
                                        <span class="form-label text-danger lot_expired_date_err _err"></span>

                                        <select id="lot_expired_date" name="lot_expired_date"
                                            class="form-select mt-2 sm:mr-2" aria-label="Default select example">
                                            <option disabled selected>==== เลือกวันหมดอายุ ==== </option>
                                        </select>
                                    </div>
                                    <div class="col-span-6">
                                        <label for="amt" class="form-label">จำนวน</label>
                                        <span class="form-label text-danger amt_err _err"></span>

                                        <input id="amt" type="number" class="form-control " name="amt"
                                            placeholder="จำนวน">
                                    </div>
                                    {{-- <div class="col-span-2">
                                        <label for="product_unit_id_fk" class="form-label">หน่วยนับ </label>
                                        <span class="form-label text-danger product_unit_id_fk_err _err"></span>
                                        <input id="product_unit_id_fk" type="hidden" class="form-control " readonly
                                            value="กล่อง" name="product_unit_id_fk">
                                        <input id="text_product_unit" type="text" class="form-control " readonly
                                            value="">
                                    </div> --}}
                                </div>
                            </div>

                        </div>
                    </div> <!-- END: Modal Body -->
                    <!-- BEGIN: Modal Footer -->
                    <div class="modal-footer">
                        <button type="button" data-tw-dismiss="modal"
                            class="btn btn-outline-danger w-20 mr-1">ยกเลิก</button>
                        <button type="submit" class="btn btn-outline-success  w-20">ตกลง</button>
                    </div> <!-- END: Modal Footer -->
            </div>
            </form>
        </div>
    </div>
    <!-- END: Modal add_product-->
@endsection



@section('script')
    {{-- select2 --}}
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


    <script>
        $(document).ready(function() {
            // $('#product_unit_id_fk').prop('disabled', false);

            $('.branch_select').select2({
                dropdownParent: $('#add_product')
            });
            $('#branch_select_filter').select2();

            $('.warehouse_select').select2({
                dropdownParent: $('#add_product')
            });
            $('#warehouse_select_filter').select2();
            $('#product_select').select2({
                dropdownParent: $('#add_product')
            });


        });
    </script>

    <script>
        $('.branch_select').change(function() {
            $('.warehouse_select').prop('disabled', false);

            const id = $(this).val();
            $.ajax({
                url: '{{ route('get_data_warehouse_select') }}',
                type: 'GET',
                dataType: 'json',
                async: false,
                data: {
                    id: id,
                },
                success: function(data) {
                    append_warehouse_select(data);
                },
            });
        });

        function append_warehouse_select(data) {
            $('.warehouse_select').empty();
            $('.warehouse_select').append(`
                <option disabled selected value="">==== เลือกสาขา ====</option>
                `);
            data.forEach((val, key) => {
                $('.warehouse_select').append(`
                <option value="${val.id}">${val.w_code}::${val.w_name}</option>
                `);
            });
        }

        $('.warehouse_select').change(function() {
            $('#lot_number').empty();
            $('#product_select').prop('disabled', false);
            let warehouse_id_fk = $(this).val();

            $.ajax({
                url: '{{ route('get_data_matereials') }}',
                method: 'post',
                data: {
                    '_token': '{{ csrf_token() }}',
                    'warehouse_id_fk': warehouse_id_fk
                },
                success: function(data) {
                    $('#product_select').empty();

                    $('#product_select').append(
                        ` <option selected disabled>==== เลือกสินค้า ====</option>`
                    )
                    data.forEach((val, key) => {
                        $('#product_select').append(
                            `<option data-amt="${val.amt}" value='${val.id}'>${val.materials_name}</option>`
                        )
                    });
                    // $('#product_unit_id_fk').val(data.id);
                },
            });

        });


        $('#product_select').change(function() {

            let materials_id = $('#product_select').val();
            $.ajax({
                url: '{{ route('get_lot_number_takeout') }}',
                type: 'post',
                dataType: 'json',
                data: {
                    '_token': '{{ csrf_token() }}',
                    'materials_id': materials_id
                },
                success: function(data) {
                    $('#lot_number').append(`
                    <option disabled selected>==== เลือกล็อตสินค้า ==== </option>
                        `);
                    data.forEach((val, key) => {
                        $('#lot_number').append(`
                        <option value='${val.lot_number}'>${val.lot_number}</option>
                        `);
                    });

                }

            });
        });


        $('#lot_number').change(function() {
            $('#lot_expired_date').empty();
            let lot_number = $('#lot_number').val();

            $.ajax({
                url: '{{ route('get_lot_expired_date') }}',
                type: 'post',
                dataType: 'json',
                data: {
                    '_token': '{{ csrf_token() }}',
                    'lot_number': lot_number,
                },
                success: function(data) {
                    $('#lot_expired_date').append(`
                <option disabled selected>==== เลือกวันหมดอายุ==== </option>
                    `);
                    data.forEach((val, key) => {
                        $('#lot_expired_date').append(`
                    <option value='${val.lot_expired_date}'>${val.lot_expired_date}</option>
                    `);
                    });
                }

            });



        });

        $('#lot_number,#lot_expired_date').change(function() {

            let lot_number = $('#lot_number').val();
            let lot_expired_date = $('#lot_expired_date').val();
            let materials_id = $('#product_select').val();



            if (lot_number != '' && lot_expired_date != null) {
                $.ajax({
                    url: '{{ route('get_max_input_atm_takeout') }}',
                    type: 'post',
                    dataType: 'json',
                    data: {
                        '_token': '{{ csrf_token() }}',
                        'lot_number': lot_number,
                        'lot_expired_date': lot_expired_date,
                        'materials_id': materials_id
                    },
                    success: function(data) {
                        $("#amt").attr("max", data.amt);
                    }

                });
            }


        });
    </script>

    {{-- BEGIN print err input --}}
    <script>
        function printErrorMsg(msg) {
            $('._err').text('');
            $.each(msg, function(key, value) {
                $('.' + key + '_err').text(`*${value}*`);
            });
        }

        function resetForm() {
            $('#form_add_product')[0].reset();
            $('._err').text('');
            $('.branch_select').select2({
                dropdownParent: $('#add_product')
            });
            $('#branch_select_filter').select2();

            $('.warehouse_select').select2({
                dropdownParent: $('#add_product')
            });
            $('#warehouse_select_filter').select2();
            $('#product_select').select2({
                dropdownParent: $('#add_product')
            });
            $('.warehouse_select').prop('disabled', true);
            $('#product_select').prop('disabled', true);
        }
    </script>
    {{-- END print err input --}}

    {{-- //BEGIN form_warehoues --}}
    <script>
        $('#form_add_product').submit(function(e) {
            const myModal = tailwind.Modal.getInstance(document.querySelector("#add_product"));
            e.preventDefault();
            var formData = new FormData($(this)[0]);
            $.ajax({
                url: '{{ route('takeout_product') }}',
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
                            $('.warehouse_select').prop('disabled', true);
                            resetForm();

                            table_takeout.draw();
                        })
                    } else {
                        printErrorMsg(data.error);
                    }
                }
            });
        });


        $('#amt').keyup(function() {

            let number = parseInt($(this).val());
            let max_amt = $(this).attr('max');

            if (number > max_amt) {
                $('.amt_err').text('จำนวนในสต็อกไม่เพียงพอ');
            } else {
                $('.amt_err').text('');
            }
        });
    </script>
    {{-- //END form_warehoues --}}

    {{-- BEGIN data_table_branch --}}
    @include('backend.stock.takeout.data_table_takeout')
    {{-- END data_table_branch --}}
@endsection
