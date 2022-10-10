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

                            <div class=" relative text-slate-500">
                                <div class="form-inline">
                                    <label for="" class="mr-2">ออกใบปะหน้า</label>
                                    <a href="{{ route('report_order_pdf') }}" class="btn  btn-warning"> <i
                                            class="fa-solid fa-print"></i> </a>
                                </div>
                            </div>

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
