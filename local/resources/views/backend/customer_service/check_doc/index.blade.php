@extends('layouts.backend.app')


@section('head')
@endsection

@section('css')
    <style>
        .icon_size {
            font-size: 28px;
        }
    </style>
@endsection

@section('conten')
    @include('backend.navbar.navbar_mobile')
    <div class="flex overflow-hidden">

        @include('backend.navbar.navbar')
        <div class="content">
            @include('backend.navbar.top_bar')


            <div class="grid grid-cols-12 gap-6 mt-5">
                <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
                    <div class="">
                        <div class="form-inline ">
                            <label for="" class="mr-2 text-slate-500 ">สถานะ : </label>
                            <select class="w-32 form-select box mt-3 sm:mt-0">
                                <option>ทั้งหมด</option>
                                <option>ผ่าน</option>
                                <option>ไม่ผ่าน</option>
                            </select>
                        </div>
                    </div>
                    <div class="hidden md:block mx-auto text-slate-500"></div>
                    <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">

                        <div class=" relative text-slate-500">
                            <div class="form-inline">
                                <label for="" class="mr-2">ค้นหาจากรหัสผู้ใช้</label>
                                <input type="text" class="form-control w-56 box pr-10" placeholder="Search...">
                                <i class="w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0" data-lucide="search"></i>
                            </div>
                        </div>

                    </div>

                </div>
                <!-- BEGIN: Data List -->
                <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
                    <table id="check_doc" class="table table-report -mt-2">
                    </table>
                </div>
                <!-- END: Data List -->
            </div>

        </div>
    </div>




    <!-- BEGIN: Modal Content -->
    <div id="info_card" class="modal" data-tw-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <!-- BEGIN: Modal Header -->
                <div class="modal-header">
                    <h2 class="font-medium text-base mr-auto">ตรวจสอบเอกสาร</h2>
                    <a data-tw-dismiss="modal" href="javascript:;"> <i data-lucide="x" class="w-8 h-8 text-slate-400"></i>
                    </a>
                </div> <!-- END: Modal Header -->
                <!-- BEGIN: Modal Body -->
                <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                    <div class="col-span-5 my-auto">
                        <img id="img_crad" src="https://via.placeholder.com/300x300.png?text=card" alt="">
                    </div>
                    <div class="col-span-7 ">
                        <div class="grid grid-cols-12 gap-3  mx-auto">
                            <div class="col-span-12">
                                <div> <label for="address" class="form-label">ที่อยู่</label> <input id="address"
                                        type="text" class="form-control" value="" readonly>
                                </div>
                            </div>
                            <div class="col-span-4">
                                <div> <label for="moo" class="form-label">หมู่</label>
                                    <input id="moo" type="text" class="form-control" value="" readonly>
                                </div>
                            </div>
                            <div class="col-span-4">
                                <div> <label for="soi" class="form-label">ซอย</label>
                                    <input id="soi" type="text" class="form-control" value="" readonly>
                                </div>
                            </div>
                            <div class="col-span-4">
                                <div> <label for="road" class="form-label">ถนน</label> <input id="road"
                                        type="text" class="form-control" value="" readonly>
                                </div>
                            </div>
                            <div class="col-span-4">
                                <div> <label for="province" class="form-label">จังหวัด</label> <input id="province"
                                        type="text" class="form-control" value="" readonly>
                                </div>
                            </div>
                            <div class="col-span-4">
                                <div> <label for="district" class="form-label">อำเภอ/เขต</label> <input id="district"
                                        type="text" class="form-control" value="" readonly>
                                </div>
                            </div>
                            <div class="col-span-4">
                                <div> <label for="rtambon" class="form-label">ตำบล</label>
                                    <input id="tambon" type="text" class="form-control" value="" readonly>
                                </div>
                            </div>
                            <div class="col-span-4">
                                <div> <label for="zipcode" class="form-label">รหัสไปรษณีย์</label> <input id="zipcode"
                                        type="text" class="form-control" value="54313" readonly>
                                </div>
                            </div>
                            <div class="col-span-4">
                                <div> <label for="phone" class="form-label">เบอร์มือถือ</label> <input id="phone"
                                        type="text" class="form-control" value="" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- END: Modal Body -->
                <!-- BEGIN: Modal Footer -->
                <div class="modal-footer"> <button type="button" data-tw-dismiss="modal"
                        class="btn btn-outline-danger w-20 mr-1">ไม่ผ่าน</button>
                    <button type="button" class="btn btn-outline-success  w-20">ผ่าน</button>
                </div> <!-- END: Modal Footer -->
            </div>
        </div>
    </div> <!-- END: Modal Content -->
@endsection




@section('script')
    {{-- BEGIN data_table_branch --}}
    @include('backend.customer_service.check_doc.data_tabel_check_doc')
    {{-- END data_table_branch --}}

    <script>
        function admin_login_user(id) {
            window.open(`admin_login_user/${id}`);
        }
    </script>

    <script>
        function action_info_card(id, status) {
            window.open(`admin_login_user/${id}`);
        }
    </script>
@endsection
