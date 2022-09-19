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

            <div class="grid grid-cols-12">
                <div class="col-start-3  col-span-8 box p-5 ">
                    <div class="">
                        <label for="">สาขา</label>
                        <select id="branch_select" class="js-example-basic-single w-full" name="branch_select">
                            @foreach ($branch as $val)
                                <option value="{{ $val->id }}">{{ $val->b_code }}::{{ $val->b_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mt-2">
                        <label for="">สาขา</label>
                        <select id="warehouse_select" class="js-example-basic-single w-full" name="warehouse_select">

                        </select>
                    </div>
                    <div class="mt-2">
                        <label for="">สินค้า</label>
                        <select id="product_select" class="js-example-basic-single w-full" name="product_select">
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
                                <input id="lot_number" type="text" class="form-control " name="หมายเลขล็อตสินค้า"
                                    placeholder="หมายเลขล็อตสินค้า">
                            </div>
                            <div class=" col-span-6">
                                <label for="lot_expired_date" class="form-label">วันหมดอายุ</label>
                                <span class="form-label text-danger lot_expired_date_err _err"></span>
                                <input id="lot_expired_date" type="date" class="form-control " name="lot_expired_date"
                                    placeholder="วันหมดอายุ">
                            </div>
                            <div class="col-span-6">
                                <label for="amt" class="form-label">จำนวน</label>
                                <span class="form-label text-danger amt_err _err"></span>
                                <input id="amt" type="number" class="form-control " name="จำนวน"
                                    placeholder="จำนวน">
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection



@section('script')
    {{-- select2 --}}
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


    <script>
        $(document).ready(function() {

            $('#branch_select').change();
            $('#branch_select').select2();
            $('#warehouse_select').select2();
            $('#product_select').select2({
                placeholder: "เลือกสินค้า",
            });
        });
    </script>

    <script>
        $('#branch_select').change(function() {
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
            data.forEach((val, key) => {
                console.log(val);
                $('#warehouse_select').append(`
                <option value="${val.id}">${val.w_code}::${val.w_name}</option>
                `);
            });
        }
    </script>
@endsection
