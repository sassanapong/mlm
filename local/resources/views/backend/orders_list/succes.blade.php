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

            <h2 class="text-lg font-medium mr-auto mt-2">รายการ คำสั่งซื้อ</h2>
            <div class="grid grid-cols-12 gap-5">
                <div class="col-span-12 ">
                    <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-4">
                        
                    </div>
                    <table id="table_orders" class="table table-report">
                    </table>
                </div>

            </div>

        </div>
    </div>
@endsection



@section('script')
    {{-- BEGIN data_table_branch --}}
    @include('backend.orders_list.data_table_orders_success')
    {{-- END data_table_branch --}}
@endsection
