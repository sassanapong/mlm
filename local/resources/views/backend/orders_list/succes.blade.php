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
                    <table id="table_orders" class="table table-report">
                    </table>
                </div>

            </div>


@endsection



@section('script')
<script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.2/js/dataTables.buttons.min.js"></script>
    {{-- BEGIN data_table_branch --}}
    @include('backend.orders_list.data_table_orders_success')
    {{-- END data_table_branch --}}
@endsection
