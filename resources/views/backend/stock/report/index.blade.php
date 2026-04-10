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
        <li class="breadcrumb-item active" aria-current="page">รายงานสต็อกสินค้า</li>
    </ol>
</nav>
@endsection

@section('content')

            <h2 class="text-lg font-medium mr-auto mt-2">รายงานสต็อกสินค้า</h2>
            <div class="grid grid-cols-12 gap-5">
                <div class="col-span-12 ">
                    <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-4">

                        <div class=" box p-3">
                            <h2 class="text-md mr-auto mb-2">กรองข้อมูล</h2>
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

                                <label for="" class="mr-1 ml-2 text-slate-500 ">สินค้า : </label>
                                <select class="js-example-basic-single w-56 product_select myWhere" name="materials_id_fk"
                                    disabled>
                                    <option value="0">ทั้งหมด</option>
                                </select>
                                <button onclick="reset_filter()" class="btn btn-sm btn-outline-pending  ml-2"> <i
                                        class="fa-solid fa-rotate-right mr-2"></i> ล้างการค้นหา </button>
                            </div>

                        </div>
                        <div class="">

                        </div>
                        <div class="hidden md:block mx-auto text-slate-500"></div>
                        <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">

                            <div class=" relative text-slate-500">

                            </div>

                        </div>
                    </div>
                    <table id="table_stock_report" class="table table-report">
                    </table>
                </div>

            </div>


@endsection



@section('script')
    {{-- select2 --}}
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>




    <script>
        $(document).ready(function() {

            $('.branch_select').select2();
            $('.warehouse_select').select2();
            $('.product_select').select2();


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
            $('.product_select').prop('disabled', false);
            const id = $(this).val();
            $.ajax({
                url: '{{ route('get_data_product_select') }}',
                type: 'GET',
                dataType: 'json',
                async: false,
                data: {
                    id: id,
                },
                success: function(data) {

                    append_product_select(data)
                },
            });
        });

        function append_product_select(data) {
            $('.product_select').empty();
            $('.product_select').append(`
            <option disabled selected value="">==== เลือกสาขา ====</option>
            `);
            data.forEach((val, key) => {

                $('.product_select').append(`
            <option value="${val.id}">${val.materials_name}</option>
            `);
            });
        }

        function reset_filter() {
            location.reload();

        }
    </script>

    {{-- BEGIN data_table_branch --}}
    @include('backend.stock.report.data_table_stock_report')
    {{-- END data_table_branch --}}
@endsection
