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
            <table id="table_repost_issue" class="table table-report">
            </table>
            {{-- END TABLE --}}

        </div>

    </div>






    <!-- BEGIN: Modal info_issue  -->
    <div id="info_issue" class="modal" data-tw-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
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
                            <h2 id="head_info" class="font-medium text-base mr-auto mt-2"> </h2>

                            <h5 class="font-medium text-base mr-auto mt-2">รายละเอียด</h5>
                            <p id="text_info_issue" class="mt-2"></p>
                        </div>

                        <div id="info_doc" class="col-span-12 mx-auto">

                        </div>

                    </div>
                    <!-- END: Modal Body -->
                    <!-- BEGIN: Modal Footer -->
                    <div class="modal-footer">
                        <p data-tw-dismiss="modal" id="btn_action_false" class="btn btn-outline-danger  mr-1">
                            ไม่รับเรื่อง</p>
                        <p id="btn_action_true" class=" btn btn-outline-success ">รับเรื่อง</p>
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
            table_Member = $('#table_repost_issue').DataTable({
                searching: false,
                ordering: false,
                lengthChange: false,
                pageLength: 10,
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
                    url: '{{ route('get_repost_issue') }}',
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
                        data: "head_info",
                        title: "เรื่อง",
                        className: "table-report__action w-26 ",
                    },
                    {
                        data: "username",
                        title: "รหัส",
                        className: "table-report__action w-10 ",
                    },
                    {
                        data: "id",
                        title: "ชื่อ - สกุล",
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
                    var info = table_Member.page.info();
                    var page = info.page;
                    var length = info.length;
                    var index = (page * length + (dataIndex + 1));

                    var id = aData['id'];

                    //BEGIN แสดงเลขลำดับ
                    $('td:nth-child(1)', nRow).html(`${index}`);
                    //END แสดงเลขลำดับ

                    //BEGIN หัวเรื่อง
                    var head_info = aData['head_info'];
                    var text_other = aData['text_other']
                    if (head_info == "อื่นๆ") {
                        head_info += " " + "(" + text_other + ")";
                    }
                    $('td:nth-child(2)', nRow).html(`${head_info}`);
                    //END หัวเรื่อง



                    //BEGIN ชื่อ - สกุล
                    var name = aData['name']
                    var last_name = aData['last_name']
                    $('td:nth-child(4)', nRow).html(`${name} ${last_name}`);
                    //END ชื่อ - สกุล


                    // BEGIN  status
                    var status = aData['status']
                    var text_status = '';
                    var bg_text_status = '';

                    if (status == 1) {
                        text_status = "รอตรวจสอบ";
                        bg_text_status = 'text-warning'
                    }
                    if (status == 2) {
                        text_status = "กำลังดำเนินการ";
                        bg_text_status = 'text-warning'
                    }
                    if (status == 3) {
                        text_status = "สำเร็จ";
                        bg_text_status = 'text-success'
                    }
                    if (status == 4) {
                        text_status = "ดำเนินการไม่สำเร็จ";
                        bg_text_status = 'text-danger'
                    }
                    if (status == 99) {
                        text_status = "ไม่รับเรื่อง";
                        bg_text_status = 'text-warning'
                    }


                    $('td:nth-child(5)', nRow).html(
                        ` <p class="${bg_text_status}">${text_status}</p>  `);
                    // END  status


                    //BEGIN กดดูรายละเอียด
                    $('td:nth-child(6)', nRow).html(`
                    <a data-tw-toggle="modal"
                        data-tw-target="#info_issue"
                        onclick="get_data_info_issue(${id})"
                        class="btn btn-sm btn-warning mr-2 ">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </a>
                    `);
                    //END กดดูรายละเอียด
                },
            });
            $('.myWhere,.myLike,.datepicker,.iSort,.myCustom').on('change', function(e) {
                table_Member.draw();
            });
        });
    </script>
    {{-- BEGIN DataTable --}}



    {{-- BEGIN get data_issue --}}
    <script>
        function get_data_info_issue(id) {
            $.ajax({
                url: '{{ route('get_data_info_issue') }}',
                method: 'post',
                data: {
                    '_token': '{{ csrf_token() }}',
                    'id': id,
                },

                success: function(data) {
                    info_modal_issue(data);
                }
            });
        }
    </script>
    {{-- END get data_issue --}}

    <script>
        function action_data_isseu(id, action) {
            const myModal = tailwind.Modal.getInstance(document.querySelector("#info_issue"));
            $.ajax({
                url: '{{ route('action_data_isseu') }}',
                method: 'post',
                data: {
                    '_token': '{{ csrf_token() }}',
                    'data': {
                        id: id,
                        action: action
                    },
                },
                success: function(data) {
                    if (data.status == "success") {
                        myModal.hide();
                        Swal.fire({
                            icon: 'success',
                            title: `ทำรายการสำเร็จ`,
                            confirmButtonColor: '#84CC18',
                            confirmButtonText: 'ยืนยัน',
                            timer: 3000,
                        }).then((result) => {

                            table_Member.draw();

                        });
                    }
                }
            });
        }
    </script>

    {{-- BEGIN สรา้งข้อมูล info_modal_issue --}}
    <script>
        function info_modal_issue(data) {
            $('#info_doc').empty();
            data.forEach((val, key) => {
                var head_info = val.head_info;
                var text_other = val.text_other;
                if (head_info == "อื่นๆ") {
                    head_info += " " + "(" + text_other + ")";
                }
                $('#head_info').text("เรื่อง : " + head_info);
                $('#text_info_issue').text(val.info_issue);


                if (val.status == 1) {
                    $("#btn_action_true").bind("click", function() {
                        action_data_isseu(val.id, 2);
                    });

                    $("#btn_action_false").bind("click", function() {
                        action_data_isseu(val.id, 99);
                    });
                }

                if (val.status == 99 || val.status == 3 || val.status == 4) {
                    $('#info_issue').find('.modal-footer').hide();
                }

                if (val.status == 2) {
                    $("#btn_action_true").text('ดำเนินการสำเร็จ')
                    $("#btn_action_false").text('ดำเนินการไม่สำเร็จ')

                    $("#btn_action_true").bind("click", function() {
                        action_data_isseu(val.id, 3);
                    });
                    $("#btn_action_false").bind("click", function() {
                        action_data_isseu(val.id, 4);
                    });
                }


                val.doc_issue.forEach((val, key) => {
                    $('#info_doc').append(`
                    <div class="mt-2">
                        <img class="img_doc_info" src='{{ asset('${val.url}/${val.doc_name}') }}' >
                    </div>
                `)
                });
            });
        }
    </script>
    {{-- END สรา้งข้อมูล info_modal_issue --}}
@endsection
