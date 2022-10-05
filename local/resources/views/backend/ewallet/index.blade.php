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

            <h2 class="text-lg font-medium mr-auto mt-2">รายการ eWallet</h2>
            <div class="grid grid-cols-12 gap-5">
                <div class="col-span-12 ">
                    <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-4">


                        <div class="">
                            <div class="form-inline ">
                                <label for="" class="mr-1 ml- text-slate-500 ">คลัง : </label>

                                <select id="branch_select_filter" class="js-example-basic-single w-56 branch_select myWhere"
                                    name="branch_id_fk">
                                    <option value="0">ทั้งหมด</option>

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
                    <table id="table_ewallet" class="table table-report">
                    </table>
                </div>

            </div>

        </div>
    </div>




    <!-- BEGIN: Modal info_branch -->
    <div id="info_ewallet" class="modal" data-tw-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <form id="form_edit_branch" method="post">
                    @csrf
                    <input type="hidden" id="id" name="id">
                    <!-- BEGIN: Modal Header -->
                    <div class="modal-header">
                        <h2 class="font-medium text-base mr-auto">รายละเอียดสาขา</h2>
                        <a data-tw-dismiss="modal" href="javascript:;"> <i data-lucide="x"
                                class="w-8 h-8 text-slate-400"></i>
                        </a>
                    </div> <!-- END: Modal Header -->
                    <!-- BEGIN: Modal Body -->
                    <div class="modal-body grid grid-cols-12 gap-4 gap-y-3 bg-slate-100/50">





                    </div> <!-- END: Modal Body -->
                    <!-- BEGIN: Modal Footer -->
                    <div class="modal-footer">
                        <button type="button" data-tw-dismiss="modal" class="btn btn-outline-danger w-20 mr-1">ปิด</button>
                    </div> <!-- END: Modal Footer -->
            </div>
            </form>
        </div>
    </div> <!-- END: Modal info_branch -->
@endsection



@section('script')
    {{-- BEGIN data_table_branch --}}
    @include('backend.ewallet.data_table_ewallet')
    {{-- END data_table_branch --}}




    <script>
        function get_data_info_ewallet(id) {
            $.ajax({
                url: '{{ route('get_info_ewallet') }}',
                method: 'POST',
                data: {
                    '_token': '{{ csrf_token() }}',
                    'id': id
                },
                success: function(data) {


                    create_info_modal(data)
                }
            });
        }


        function create_info_modal(data) {

            $('#info_ewallet').find('.modal-body').empty();
            data.forEach((val, key) => {

                if (val.type == 1) {
                    $('#info_ewallet').find('.modal-body').append(`
                    <div class="col-span-12 ">
                                    <h2 class="text-lg font-medium mr-auto mt-2"> รายการ ฝากเงิน</h2>
                                </div>
                        <div class=" col-span-12 box">
                            
                            <div class="grid grid-cols-12 p-3">
                

                                <div class="col-span-12 mt-2">
                                    <P> รหัสรายการ ${val.transaction_code} วันที่ทำรายการ ${val.ewallet_created_at}</P>
                                </div>
                                <div class="col-span-12 mt-2">
                                    <P> สมาชิก ${val.user_name} ${val.name} </P>
                                </div>
                                <div class="col-span-12 mt-2">
                                    <P> จำนวนเงินที่ถอน ${val.amt}</P>
                                </div>

                          
                            </div>
                        </div>
                        <div class=" col-span-12 box">
                            <div class="col-span-12 mt-2 p-3">
                                    <P> ธนาคาร ${val.bank_name}</P>
                                    <P> สาขา ${val.bank_branch}</P>
                                    <P> ชื่อเจ้าของบัญชี ${val.account_name}</P>
                                    <P> เลขที่บัญชี ${val.bank_no}</P>
                                </div>
                        </div>
                        <div class=" col-span-12 box">
                            <div class="col-span-12 mt-2 p-3">
                                <img class="img_doc_info mt-2 mx-auto" src='{{ asset('${val.url}/${val.file_ewllet}') }}' >
                                </div>
                        </div>
                        <div class=" col-span-12 box">
                            <div class="col-span-12 mt-2 p-3 text-center">
    
                                    <button type="button" onclick="ewallet_action('${val.ewallet_id}','1')" data-tw-dismiss="modal" class="btn btn-outline-success w-20 mr-1">อนุมัติ</button>
                          
                                    <button type="button" onclick="ewallet_action('${val.ewallet_id}','3')" data-tw-dismiss="modal" class="btn btn-outline-danger w-20 mr-1">ไม่อนุมัติ</button>
                          
                                </div>
                        </div>
                            `);
                }

            });



        }

        function ewallet_action(ewallet_id, action) {
            $.ajax({
                url: '{{ route('ewallet_action') }}',
                method: 'POST',
                data: {
                    '_token': '{{ csrf_token() }}',
                    'ewallet_id': ewallet_id,
                    'action': action
                },
                success: function(data) {

                }
            });
        }
    </script>
@endsection
