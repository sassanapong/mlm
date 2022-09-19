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
                        <select id="warehouse_select" class="js-example-basic-single w-full" name="branch_select">

                        </select>
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
