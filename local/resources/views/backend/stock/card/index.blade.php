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


            <h2 class="text-lg font-medium mr-auto mt-2">รายงานสต็อกสินค้า สาขา {{ $stock_movement[0]['b_code'] }}
                {{ $stock_movement[0]['b_name'] }} / {{ $stock_movement[0]['w_code'] }}
                {{ $stock_movement[0]['w_name'] }}
            </h2>

            <h2 class="text-right text-lg"> <button class="btn btn-primary">จำนวนคงเหลือ {{ $max_amt }} ชิ้น</button>
            </h2>

            <div class="grid grid-cols-12 gap-5">


                <div class="col-span-12 ">
                    <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-4">

                        <div class=" box p-3">
                            <div class="form-inline ">


                                <label for="" class="mr-1 ml-2 text-slate-500 ">ประเภท : </label>
                                <select id="in_out" class="js-example-basic-single w-56  myWhere" name="in_out">
                                    <option value="0">ทั้งหมด</option>
                                    <option value="1">รับเข้า</option>
                                    <option value="2">จ่ายออก</option>
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
                    <table id="table_stock_card" class="table table-report">
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

            $('.js-example-basic-single').select2();
        });

        function reset_filter() {
            location.reload();

        }
    </script>


    {{-- BEGIN data_table_branch --}}
    @include('backend.stock.card.data_table_card')
    {{-- END data_table_branch --}}
@endsection
