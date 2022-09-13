@extends('layouts.backend.app')

@section('head')
@endsection

@section('css')
    <style>
        .img_doc_info {
            width: 300px;
            height: auto;
        }
    </style>
@endsection

@section('conten')
    @include('backend.navbar.navbar_mobile')
    <div class="flex overflow-hidden">

        @include('backend.navbar.navbar')
        <div class="content ">
            @include('backend.navbar.top_bar')

            {{-- BEGIN TABLE --}}
            <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-4">

                <div class="">
                    <div class="form-inline ">
                        <label for="" class="mr-2 text-slate-500 ">เรื่อง : </label>
                        <select name="head_info" class="w-32 form-select box mt-3 sm:mt-0 myWhere">
                            <option value="">ทั้งหมด</option>
                            <option value="การเงิน">การเงิน</option>
                            <option value="ปัญหาระบบ">ปัญหาระบบ</option>
                            <option value="แก้ไขข้อมูลพื้นฐาน">แก้ไขข้อมูลพื้นฐาน</option>
                            <option value="สินค้า+การจัดส่ง">สินค้า+การจัดส่ง</option>
                            <option value="อื่นๆ">อื่นๆ</option>
                        </select>
                    </div>
                </div>
                <div class="ml-2">
                    <div class="form-inline ">
                        <label for="" class="mr-2 text-slate-500 ">สถานะ : </label>
                        <select name="status" class="w-32 form-select box mt-3 sm:mt-0 myWhere">
                            <option value="">ทั้งหมด</option>
                            <option value="1">รอตรวจสอบ</option>
                            <option value="2">กำลังดำเนินการ</option>
                            <option value="3">สำเร็จ</option>
                        </select>
                    </div>
                </div>

            </div>
            <table id="table_promotion_help" class="table table-report">
            </table>
            {{-- END TABLE --}}

        </div>

    </div>






    <!-- BEGIN: Modal info_issue  -->
    <div id="promotion_help" class="modal" data-tw-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog  modal-xl">
            <div class="modal-content">
                <form id="form_change_password" method="post">
                    @csrf
                    <input type="hidden" id="id" name="id">
                    <!-- BEGIN: Modal Header -->
                    <div class="modal-header">
                        <h2 class="font-medium text-base mr-auto">รายละเอียด</h2>
                        <a data-tw-dismiss="modal" href="javascript:;"> <i data-lucide="x"
                                class="w-8 h-8 text-slate-400"></i>
                        </a>
                    </div> <!-- END: Modal Header -->
                    <!-- BEGIN: Modal Body -->
                    <div class="modal-body grid grid-cols-12 gap-4 gap-y-3 bg-slate-100/50">

                        <div class="col-span-12">
                            <h2 class="font-medium text-base">ประเภท : <span id="type_help"></span> </h2>
                        </div>

                        <div class="col-span-4">
                            <p class="text-base">รหัสสมาชิก : <span id="username"></span>
                            </p>
                        </div>
                        <div class="col-span-4">
                            <p class="text-base">ชื่อ-นามสกุล : <span id="name"></span> <span id="last_name"></span>
                            </p>
                        </div>
                        <div class="col-span-4">
                            <p class="text-base">เบอร์มือถือ : <span id="phone"></span>
                            </p>
                        </div>
                        <div class="col-span-4">
                            <p class="text-base">ตำแหน่งงาน : <span id="role"></span>
                            </p>
                        </div>
                        <div class="col-span-4">
                            <p class="text-base">
                                รักษาสภาพสมาชิกมาแล้ว : <span id="day_member"></span>
                            </p>
                        </div>
                        <div class="col-span-12">
                            <p class="text-base">
                                รายละเอียด :
                            </p>
                            <span id="info_promotion"></span>
                        </div>

                        <div id="info_doc" class="col-span-12">

                        </div>

                    </div>
                    <!-- END: Modal Body -->
                    <!-- BEGIN: Modal Footer -->
                    <div class="modal-footer">
                        <button type="button" data-tw-dismiss="modal" class="btn btn-outline-danger w-20 mr-1">ปิด</button>
                        <button type="submit" class="btn btn-outline-success ">ดำเนินการ</button>
                    </div> <!-- END: Modal Footer -->
            </div>
            </form>
        </div>

    </div> <!-- END: Modal info_issue -->
@endsection


@section('script')
    {{-- BEGIN DataTable --}}
    <script>
        $(function() {
            table_promotion_help = $('#table_promotion_help').DataTable({
                searching: false,
                ordering: false,
                lengthChange: false,
                pageLength: 5,
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
                    url: '{{ route('get_promotion_help') }}',
                    data: function(d) {
                        d.Where = {};

                        $('.myWhere').each(function() {
                            if ($.trim($(this).val()) && $.trim($(this).val()) != '0') {
                                d.Where[$(this).attr('name')] = $.trim($(this).val());
                                if ($('#Search').val() == '') $('#btn-Excel').css("display",
                                    "initial");
                            }
                        });
                        d.Like = {};
                        $('.myLike').each(function() {
                            if ($.trim($(this).val()) && $.trim($(this).val()) != '0') {
                                d.Like[$(this).attr('name')] = $.trim($(this).val());
                            }
                        });
                        d.Custom = {};
                        $('.myCustom').each(function() {
                            if ($.trim($(this).val()) && $.trim($(this).val()) != '0' && $(this)
                                .attr('type') != 'checkbox') {
                                d.Custom[$(this).attr('name')] = $.trim($(this).val());
                            }
                            if ($.trim($(this).val()) && $.trim($(this).val()) != '0' && $(this)
                                .is(':checked')) {
                                d.Custom[$(this).attr('name')] = $.trim($(this).val());
                            }
                        });
                    },
                },
                columns: [{
                        data: "id",
                        title: "ลำดับ",
                        className: "table-report__action w-10 text-center",
                    },
                    {
                        data: "type_help",
                        title: "เรื่อง",
                        className: "table-report__action w-26 ",
                    },
                    {
                        data: "id",
                        title: "ชื่อ - สกุล",
                        className: "table-report__action  w-26",
                    },
                    {
                        data: "phone",
                        title: "เบอร์โทรศัพท์",
                        className: "table-report__action  w-26",
                    },
                    {
                        data: "status",
                        title: "สถานะ",
                        className: "table-report__action w-26 ",
                    },
                    {
                        data: "id",
                        title: "",
                        className: "table-report__action ",
                    },
                ],
                rowCallback: function(nRow, aData, dataIndex) {

                    //คำนวนลำดับของ รายการที่แสดง
                    var info = table_promotion_help.page.info();
                    var page = info.page;
                    var length = info.length;
                    var index = (page * length + (dataIndex + 1));

                    var id = aData['id'];

                    //BEGIN แสดงเลขลำดับ
                    $('td:nth-child(1)', nRow).html(`${index}`);
                    //END แสดงเลขลำดับ





                    //BEGIN ชื่อ - สกุล
                    var name = aData['name']
                    var last_name = aData['last_name']
                    $('td:nth-child(3)', nRow).html(`${name} ${last_name}`);
                    //END ชื่อ - สกุล


                    // BEGIN  status
                    var status = aData['status']
                    var text_status = '';
                    var bg_text_status = '';

                    if (status == 1) {
                        text_status = "รอตรวจสอบ";
                        bg_text_status = 'text-danger'
                    }
                    if (status == 2) {
                        text_status = "กำลังดำเนินการ";
                        bg_text_status = 'text-warning'
                    }
                    if (status == 3) {
                        text_status = "สำเร็จ";
                        bg_text_status = 'text-success'
                    }


                    $('td:nth-child(5)', nRow).html(
                        ` <p class="${bg_text_status}">${text_status}</p>  `);
                    // END  status


                    //BEGIN กดดูรายละเอียด
                    $('td:nth-child(6)', nRow).html(`
                    <a data-tw-toggle="modal"
                        data-tw-target="#promotion_help"
                        onclick="get_data_promotion_help(${id})"
                        class="btn btn-sm btn-warning mr-2 ">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </a>
                    `);
                    //END กดดูรายละเอียด
                },
            });
            $('.myWhere,.myLike,.datepicker,.iSort,.myCustom').on('change', function(e) {
                table_promotion_help.draw();
            });
        });
    </script>
    {{-- BEGIN DataTable --}}



    {{-- BEGIN get data_issue --}}
    <script>
        function get_data_promotion_help(id) {
            $.ajax({
                url: '{{ route('get_data_promotion_help') }}',
                method: 'post',
                data: {
                    '_token': '{{ csrf_token() }}',
                    'id': id,
                },

                success: function(data) {
                    promotion_doc(data);
                }
            });
        }
    </script>
    {{-- END get data_issue --}}


    {{-- BEGIN สรา้งข้อมูล info_modal_issue --}}
    <script>
        function promotion_doc(data) {
            $('#info_doc').empty();

            for (const [key, value] of Object.entries(data.promotion_help)) {
                $('#promotion_help').find('#' + key).text(value);
            }



            $.each(data.doc_help, function(index, value) {

                $('#info_doc').append(`
                    <h2 class="font-medium text-base mt-3">${index}</h2>
                    <div id="${index}"></div>
                    <hr class="m-3">
                `);


                $.each(value, function(key, items) {

                    $(`#${index}`).append(
                        `
                        <img class="img_doc_info mt-2 mx-auto" src='{{ asset('${items.url}/${items.file_name}') }}' > `
                    );
                });

            });

        }
    </script>
    {{-- END สรา้งข้อมูล info_modal_issue --}}
@endsection
