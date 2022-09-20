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

            <h2 class="text-lg font-medium mr-auto mt-2">รับสินค้าเข้า</h2>
            <form id="form_add_product" method="post">
                @csrf
                <div class="grid grid-cols-12">
                    <div class="col-start-3  col-span-8 box p-5 ">
                        <div class="">
                            <label for="">สาขา</label>
                            <span class="form-label text-danger branch_id_fk_err _err"></span>
                            <select id="branch_select" class="js-example-basic-single w-full" name="branch_id_fk">
                                <option selected disabled>==== เลือกสาขา ====</option>
                                @foreach ($branch as $val)
                                    <option value="{{ $val->id }}">{{ $val->b_code }}::{{ $val->b_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mt-2">
                            <label for="">คลัง</label>
                            <span class="form-label text-danger warehouse_id_fk_err _err"></span>
                            <select id="warehouse_select" class="js-example-basic-single w-full" name="warehouse_id_fk"
                                disabled>
                                <option selected disabled>==== เลือกคลัง ====</option>
                            </select>
                        </div>
                        <div class="mt-2">
                            <label for="">สินค้า</label>
                            <span class="form-label text-danger product_id_fk_err _err"></span>
                            <select id="product_select" class="js-example-basic-single w-full" name="product_id_fk"
                                disabled>
                                <option selected disabled>==== เลือกสินค้า ====</option>
                                @foreach ($product as $key => $val)
                                    <option value="{{ $val->product_code }}">{{ $key + 1 }} . {{ $val->product_name }}
                                        ({{ $val->title }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mt-4">
                            <div class="grid grid-cols-12 gap-5">
                                <div class=" col-span-6">
                                    <label for="lot_number" class="form-label">หมายเลขล็อตสินค้า</label>
                                    <span class="form-label text-danger lot_number_err _err"></span>
                                    <input id="lot_number" type="text" class="form-control " name="lot_number"
                                        placeholder="หมายเลขล็อตสินค้า">
                                </div>
                                <div class=" col-span-6">
                                    <label for="lot_expired_date" class="form-label">วันหมดอายุ</label>
                                    <span class="form-label text-danger lot_expired_date_err _err"></span>
                                    <input id="lot_expired_date" type="date" class="form-control "
                                        name="lot_expired_date" placeholder="วันหมดอายุ">
                                </div>
                                <div class="col-span-6">
                                    <label for="amt" class="form-label">จำนวน</label>
                                    <span class="form-label text-danger amt_err _err"></span>
                                    <input id="amt" type="number" min="1" class="form-control " name="amt"
                                        placeholder="จำนวน">
                                </div>
                                <div class="col-span-6">
                                    <label for="product_unit_id_fk" class="form-label">หน่วยนับ </label>
                                    <span class="form-label text-danger product_unit_id_fk_err _err"></span>
                                    <select id="product_unit_id_fk" class="form-select w-full" name="product_unit_id_fk">
                                        <option selected disabled>==== เลือกหน่วยนับ ====</option>
                                        @foreach ($product_unit as $key => $val)
                                            <option value="{{ $val->id }}">
                                                {{ $val->product_unit }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-span-12 mt-4 flex justify-between ">
                            <a href="" class=" btn btn-sm btn-secondary ">
                                ยกเลิก</a>
                            <button type="submit" class=" btn btn-sm btn-primary">บันทึก</button>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>
@endsection



@section('script')
    {{-- select2 --}}
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


    <script>
        $(document).ready(function() {

            $('#product_unit_id_fk').prop('disabled', false);
            $('#branch_select').select2();
            $('#warehouse_select').select2();
            $('#product_select').select2();
        });
    </script>

    <script>
        $('#branch_select').change(function() {
            $('#warehouse_select').prop('disabled', false);
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
            $('#warehouse_select').empty();
            $('#warehouse_select').append(`
                <option disabled selected value="">==== เลือกสาขา ====</option>
                `);
            data.forEach((val, key) => {

                $('#warehouse_select').append(`
                <option value="${val.id}">${val.w_code}::${val.w_name}</option>
                `);
            });
        }

        $('#warehouse_select').change(function() {
            $('#product_select').prop('disabled', false);
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
        }
    </script>
    {{-- END print err input --}}

    {{-- //BEGIN form_warehoues --}}
    <script>
        $('#form_add_product').submit(function(e) {

            e.preventDefault();
            var formData = new FormData($(this)[0]);
            $.ajax({
                url: '{{ route('store_product') }}',
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
                            location.reload();
                        })
                    } else {
                        printErrorMsg(data.error);
                    }
                }
            });
        });
    </script>
    {{-- //END form_warehoues --}}
@endsection
