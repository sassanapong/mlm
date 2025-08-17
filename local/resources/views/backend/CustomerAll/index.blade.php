@extends('layouts.backend.app_new')
@section('head_text')
<nav aria-label="breadcrumb" class="-intro-x mr-auto hidden sm:flex">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Customer Service</a></li>
        <li class="breadcrumb-item active" aria-current="page">ระบบบริการสมาชิก</li>
    </ol>
</nav>
@endsection
@section('content')
            <div class="intro-y box p-5 mt-5">
                <div class="flex flex-col sm:flex-row sm:items-end xl:items-start mb-2">
                    <form id="tabulator-html-filter-form" class="xl:flex sm:mr-auto">

                        <div class="sm:flex items-center sm:mr-4">
                            <div class="col-span-12 sm:col-span-6">
                                <label for="modal-datepicker-1" class="form-label">ตำแหน่ง</label>
                            <select id="type"  class="form-control sm:w-40 2xl:w-full mt-2 sm:mt-0 form-select">
                                <option value="">ทั้งหมด</option>
                                @foreach ($position as $item)
                                <option value="{{$item->code}}">{{$item->business_qualifications}}</option>
                                @endforeach

                            </select> </div>
                        </div>
                        <div class="sm:flex items-center sm:mr-4">


                            <div class="col-span-12 sm:col-span-6"> <label for="modal-datepicker-1" class="form-label">รหัสสมาชิก</label> <input type="text"  id="user_name" class="form-control sm:w-40 2xl:w-full mt-2 sm:mt-0" placeholder="รหัสสมาชิก"> </div>
                            <div class="col-span-12 sm:col-span-6"> <label for="modal-datepicker-1" class="form-label">ID Card</label> <input type="text"  id="id_card" class="form-control sm:w-40 2xl:w-full mt-2 sm:mt-0" placeholder="ID Card"> </div>
                            <div class="col-span-12 sm:col-span-6"> <label for="modal-datepicker-1" class="form-label">Phone</label> <input type="text"  id="phone" class="form-control sm:w-40 2xl:w-full mt-2 sm:mt-0" placeholder="เบอร์โทรศัพ"> </div>

                        </div>

                        {{-- <div class="sm:flex items-center sm:mr-4">


                                <div class="col-span-12 sm:col-span-6"> <label for="modal-datepicker-1" class="form-label">วันที่ทำรายการ</label> <input type="date" id="s_date" class="form-control" value="{{date('Y-m-d')}}"> </div>
                                <div class="col-span-12 sm:col-span-6"> <label for="modal-datepicker-2" class="form-label">ถึง</label> <input type="date" id="e_date" class="form-control"  value="{{date('Y-m-d')}}"> </div>
                        </div> --}}


                        <div class="mt-2 xl:mt-0">
                            <div class="col-span-12 sm:col-span-6 mt-6"><button id="search-form" type="button"
                                class="btn btn-primary w-full sm:w-16">ค้นหา</button>
                            </div>
                            {{-- <button id="tabulator-html-filter-reset" type="button"
                                class="btn btn-secondary w-full sm:w-16 mt-2 sm:mt-0 sm:ml-1">Reset</button> --}}
                        </div>
                    </form>
                    {{-- <div class="flex mt-5 sm:mt-0">

                        <div class="dropdown w-1/2 sm:w-auto">
                            <button class="dropdown-toggle btn btn-outline-secondary w-full sm:w-auto" aria-expanded="false"
                                data-tw-toggle="dropdown"> <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                    height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" icon-name="file-text"
                                    data-lucide="file-text" class="lucide lucide-file-text w-4 h-4 mr-2">
                                    <path d="M14.5 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V7.5L14.5 2z"></path>
                                    <polyline points="14 2 14 8 20 8"></polyline>
                                    <line x1="16" y1="13" x2="8" y2="13"></line>
                                    <line x1="16" y1="17" x2="8" y2="17"></line>
                                    <line x1="10" y1="9" x2="8" y2="9"></line>
                                </svg> Export <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" icon-name="chevron-down"
                                    data-lucide="chevron-down" class="lucide lucide-chevron-down w-4 h-4 ml-auto sm:ml-2">
                                    <polyline points="6 9 12 15 18 9"></polyline>
                                </svg> </button>
                            <div class="dropdown-menu w-40">
                                <ul class="dropdown-content">


                                    <li>
                                        <a id="tabulator-export-xlsx" href="javascript:;" class="dropdown-item"> <svg
                                                xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                icon-name="file-text" data-lucide="file-text"
                                                class="lucide lucide-file-text w-4 h-4 mr-2">
                                                <path d="M14.5 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V7.5L14.5 2z">
                                                </path>
                                                <polyline points="14 2 14 8 20 8"></polyline>
                                                <line x1="16" y1="13" x2="8" y2="13">
                                                </line>
                                                <line x1="16" y1="17" x2="8" y2="17">
                                                </line>
                                                <line x1="10" y1="9" x2="8" y2="9">
                                                </line>
                                            </svg> Export XLSX </a>
                                    </li>

                                </ul>
                            </div>
                        </div>
                    </div> --}}
                </div>
                <div class="overflow-x-auto">
                <div class="table-responsive">
                    <table id="workL" class="table table-striped table-hover dt-responsive display nowrap"
                        cellspacing="0">


                    </table>

                </div>
                </div>

                {{-- <table id="workL" class="table table-bordered nowrap mt-2">

                </table> --}}
            </div>




 <div id="edit_position" class="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('update_position') }}" method="POST">
                @csrf
            <!-- BEGIN: Modal Header -->
            <div class="modal-header">
                <h2 class="font-medium text-base mr-auto">ปรับตำแหน่ง</h2>
                <div class="dropdown sm:hidden"> <a class="dropdown-toggle w-5 h-5 block" href="javascript:;" aria-expanded="false" data-tw-toggle="dropdown"> <i data-lucide="more-horizontal" class="w-5 h-5 text-slate-500"></i> </a>

                </div>
            </div> <!-- END: Modal Header -->
            <!-- BEGIN: Modal Body -->
            <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                <div class="col-span-12 sm:col-span-12">
                    <h3 id="full_name">  </h3>
                </div>
                <input type="hidden" name="user_name_upgrad" id="user_name_upgrad">

                <div class="col-span-12 sm:col-span-12"> <label for="modal-form-6" class="form-label">ปรับ PV อัพตำแหน่ง</label>
                    <input  type="number" class="form-control" name="pv" placeholder="Pv Upgrad" value="0">
                </div>
                <div class="col-span-12 sm:col-span-12"> <label for="modal-form-6" class="form-label">ตำแหน่ง</label>
                    <select id="modal-form-6" class="form-select"  name="position" required>
                        @foreach ($position as $item)
                        <option value="{{$item->code}}">{{$item->business_qualifications}}</option>
                        @endforeach
                    </select>
                </div>

            </div> <!-- END: Modal Body -->
            <!-- BEGIN: Modal Footer -->
            <div class="modal-footer"> <button type="button" data-twPerro-dismiss="modal" class="btn btn-outline-secondary w-20 mr-1">ยกเลิก</button>
                 <button type="submit" class="btn btn-primary w-20">ยืนยัน</button> </div> <!-- END: Modal Footer -->
            </form>
        </div>
    </div>
</div> <!-- END: Modal Content -->

    @endsection

    @section('script')


        <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.2/js/buttons.html5.min.js"></script>


        <script>
            $('#linkMenuTop .nav-item').eq(1).addClass('active');
        </script>
        <script>
            $('.page-content').css({
                'min-height': $(window).height() - $('.navbar').height()
            });
        </script>


        <script type="text/javascript">

        function numberWithCommas(x) {
        // x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        rs = parseFloat(x).toFixed(2);
        return rs;

        }
            $(function() {
                table_order = $('#workL').DataTable({
                    // dom: 'Bfrtip',
                    // buttons: ['excel'],
                    searching: false,
                    ordering: false,
                    lengthChange: false,
                    responsive: true,
                    // paging: false,
                    pageLength: 20,
                    processing: true,
                    serverSide: true,
                    "language": {
                        "lengthMenu": "แสดง _MENU_ แถว",
                        "zeroRecords": "ไม่พบข้อมูล",
                        "info": "แสดงหน้า _PAGE_ จาก _PAGES_ หน้า",
                        "search": "ค้นหา",
                        "infoEmpty": "",
                        "infoFiltered": "",
                        "paginate": {
                            "first": "หน้าแรก",
                            "previous": "ย้อนกลับ",
                            "next": "ถัดไป",
                            "last": "หน้าสุดท้าย"
                        },
                        'processing': "กำลังโหลดข้อมูล",
                    },
                    ajax: {
                        url: '{{ route('customer_all_datable') }}',
                        data: function(d) {
                        d.user_name = $('#user_name').val();
                        d.position = $('#type').val();
                        d.id_card = $('#id_card').val();
                        d.phone = $('#phone').val();


                        },
                    },


                    columns: [
                        // {
                        //     data: "id",
                        //     title: "ลำดับ",
                        //     className: "w-10 text-center",
                        // },
                        {
                            data: "user_name",
                            title: "รหัสสมาชิก",
                            className: "w-10",
                        },
                        {
                            data: "name",
                            title: "ชื่อนามสกุล",
                            className: "w-10",
                        },
                        {
                            data: "phone",
                            title: "Phone",
                            className: "w-10",
                        },

                        {
                            data: "id_card",
                            title: "IDCARD",
                            className: "w-10",
                        },
                        {
                            data: "upline_id",
                            title: "UplineId",
                            className: "w-10",
                        },
                        {
                            data: "type_upline",
                            title: "ขา",
                            className: "w-10",
                        },


                        {
                            data: "qualification_id",
                            title: "ตำแหน่ง",
                            className: "w-10",

                        },

                        {
                            data: "expire_date",
                            title: "วันหมดอายุ",
                            className: "w-10",

                        },


                        {
                            data: "introduce_id",
                            title: "ผู้แนะนำ",
                            className: "w-10",
                        },

                        {
                            data: "pv_upgrad",
                            title: "PV อัพตำแหน่ง",
                            className: "w-10",
                        },

                        // {
                        //     data: "bonus_total",
                        //     title: "โบนัสสะสม",
                        //     className: "w-10",
                        // },

                        // {
                        //     data: "bonus_xvvip",
                        //     title: "PV สร้างทีม XVVIP",
                        //     className: "w-10 text-end",
                        // },


                        {
                            data: "action",
                            title: "Action",
                            className: "w-10",
                        },



                    ],



                });
                $('#search-form').on('click', function(e) {
                table_order.draw();
                e.preventDefault();
            });

            });



            function modal_logtranfer(user_name,full_name) {
                $('#user_name_upgrad').val(user_name);
                $('#full_name').html(full_name);

                // $('#edit_position').modal('show');

            }

        </script>
    @endsection
