@extends('layouts.backend.app')



@section('head')
    {{-- select2 --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('css')
    <style>
        .btn-warning {
            color: #000 !important;
            background: #FAD02A !important;
        }
    </style>
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
                        <div class="">
                            <div class="form-inline ">
                                <label for="" class="mr-1  text-slate-500 ">ประเภท : </label>
                                <select class="form-select w-56  myWhere" name="type">
                                    <option value="0">ทั้งหมด</option>
                                    <option value="1">ฝากเงิน</option>
                                    <option value="2">โอนเงิน</option>
                                    <option value="3">ถอนเงิน</option>
                                </select>
                                <label for="" class="ml-2  text-slate-500 ">สถานะ : </label>
                                <select class="form-select w-56  myWhere" name="status">
                                    <option value="0">ทั้งหมด</option>
                                    <option value="1">รออนุมัติ</option>
                                    <option value="2">อนุมัติ</option>
                                    <option value="3">ไม่อนุมัติ</option>
                                </select>
                            </div>
                        </div>
                        <div class="hidden md:block mx-auto text-slate-500"></div>
                        <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">

                            <form action="{{ route('report_order_pdf') }}" target="_blank" method="post">
                                @csrf
                                <div class="grid grid-cols-12 gap-5">
                                    <div class="col-span-6 ">
                                        <div class="form-inline">
                                            <label for="" class="text-slate-500  ">ออกใบปะหน้า : </label>
                                            <div class="relative  mx-auto ml-2">
                                                <div
                                                    class="absolute rounded-l w-10 h-full flex items-center justify-center bg-slate-100 border text-slate-500 dark:bg-darkmode-700 dark:border-darkmode-800 dark:text-slate-400">
                                                    <i data-lucide="calendar" class="w-4 h-4"></i>
                                                </div>
                                                @php
                                                    $data_now = date('Y-m-d');
                                                @endphp
                                                <input name="date" type="date" class="form-control pl-12"
                                                    value="{{ $data_now }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-span-6 ">
                                        <div class=" relative text-slate-500">
                                            <div class="form-inline">
                                                <button type="submit" class="btn btn-warning mt-1"> <i
                                                        class="fa-solid fa-print"></i> </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>





                        </div>
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
    @include('backend.orders_list.data_table_orders')
    {{-- END data_table_branch --}}
@endsection
