@extends('layouts.backend.app')

@section('head')
@endsection

@section('css')
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
                    <button class="btn btn-primary shadow-md mr-2" data-tw-toggle="modal" data-tw-target="#add_member"
                        onclick="resetForm()">เพิ่ม
                        สมาชิก</button>
                </div>
                <div class="">
                    <div class="form-inline ">
                        <label for="" class="mr-2 text-slate-500 ">สถานะ : </label>
                        <select name="status" class="w-32 form-select box mt-3 sm:mt-0 myWhere">
                            <option value="">ทั้งหมด</option>
                            <option value="1">เปิดใช้งาน</option>
                            <option value="2">ระงับการใช้งาน</option>
                        </select>
                    </div>
                </div>
                <div class="hidden md:block mx-auto text-slate-500"></div>
                <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">

                    <div class=" relative text-slate-500">
                        <div class="form-inline">
                            <label for="" class="mr-2">ค้นหา</label>
                            <input type="text" name="name" class="form-control w-56 box pr-10 myLike"
                                placeholder="ค้นหา...">
                            <i class="w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0" data-lucide="search"></i>
                        </div>
                    </div>

                </div>
            </div>
            <table id="table_Member" class="table table-report">
            </table>
            {{-- END TABLE --}}
        </div>
    </div>



    <!-- BEGIN: Modal Content -->
    <div id="add_member" class="modal" data-tw-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog ">
            <div class="modal-content">
                <form id="form_add_member" method="post">
                    @csrf
                    <!-- BEGIN: Modal Header -->
                    <div class="modal-header">
                        <h2 class="font-medium text-base mr-auto">เพิ่มสมาชิก</h2>
                        <a data-tw-dismiss="modal" href="javascript:;"> <i data-lucide="x"
                                class="w-8 h-8 text-slate-400"></i>
                        </a>
                    </div> <!-- END: Modal Header -->
                    <!-- BEGIN: Modal Body -->
                    <div class="modal-body grid grid-cols-12 gap-4 gap-y-3 bg-slate-100/50">
                        <div class="col-span-6">
                            <div>
                                <label for="regular-form-1" class="form-label">username
                                    <span class="text-danger username_err _err"></span>
                                </label>
                                <input id="regular-form-1" name="username" type="text" class="form-control">
                            </div>
                        </div>
                        <div class="col-span-6">

                            <div>
                                <label for="regular-form-1" class="form-label">password
                                    <span class="text-danger password_err _err"></span>
                                </label>
                                <div class="input-group"> <input type="password" id="password" name="password"
                                        class="form-control" placeholder="">
                                    <div id="input-group-price" class="input-group-text toggle-password">
                                        <i id="icon_eye" class="fa-solid fa-eye "></i>
                                        <i id="icon_eye_hide" class="fa-solid fa-eye-slash"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-span-6">
                            <div>
                                <label for="regular-form-1" class="form-label">ชื่อ
                                    <span class="text-danger name_err _err"></span>
                                </label>
                                <input id="regular-form-1" name="name" type="text" class="form-control">
                            </div>
                        </div>
                        <div class="col-span-6">
                            <div>
                                <label for="regular-form-1" class="form-label">นามสกุล
                                    <span class="text-danger last_name_err _err"></span>
                                </label>
                                <input id="regular-form-1" name="last_name" type="text" class="form-control">
                            </div>
                        </div>
                        <div class="col-span-6">
                            <div>
                                <label for="regular-form-1" class="form-label">เบอร์โทรศัพท์
                                    <span class="text-danger phone_err _err"></span>
                                </label>
                                <input id="regular-form-1" name="phone" type="text" class="form-control">
                            </div>
                        </div>
                        <div class="col-span-12">
                            <span class="text-danger role_err _err"></span>
                            <div class="form-inline">
                                <label for="" class="mr-2 ">ตำแหน่ง : </label>
                                <select name="role" class="w-32 form-select box  sm:mt-0">
                                    <option selected disabled>ตำแหน่ง</option>
                                    <option value="MB">MB</option>
                                    <option value="MO">MO</option>
                                    <option value="MG">MG</option>
                                </select>
                            </div>
                        </div>
                    </div> <!-- END: Modal Body -->
                    <!-- BEGIN: Modal Footer -->
                    <div class="modal-footer">
                        <button type="button" data-tw-dismiss="modal"
                            class="btn btn-outline-danger w-20 mr-1">ยกเลิก</button>
                        <button type="submit" class="btn btn-outline-success  w-20">ตกลง</button>
                    </div> <!-- END: Modal Footer -->
            </div>
            </form>
        </div>
    </div> <!-- END: Modal Content -->

    <!-- BEGIN: Modal edit_password -->
    <div id="edit_password" class="modal" data-tw-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog ">
            <div class="modal-content">
                <form id="form_change_password" method="post">
                    @csrf
                    <input type="hidden" id="id" name="id">
                    <!-- BEGIN: Modal Header -->
                    <div class="modal-header">
                        <h2 class="font-medium text-base mr-auto">เพิ่มสมาชิก</h2>
                        <a data-tw-dismiss="modal" href="javascript:;"> <i data-lucide="x"
                                class="w-8 h-8 text-slate-400"></i>
                        </a>
                    </div> <!-- END: Modal Header -->
                    <!-- BEGIN: Modal Body -->
                    <div class="modal-body grid grid-cols-12 gap-4 gap-y-3 bg-slate-100/50">


                        <div class="col-span-6">
                            <div>
                                <label for="regular-form-1" class="form-label">ชื่อ
                                    <span class="text-danger name_err _err"></span>
                                </label>
                                <input id="name" name="name" type="text" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="col-span-6">
                            <div>
                                <label for="regular-form-1" class="form-label">นามสกุล
                                    <span class="text-danger last_name_err _err"></span>
                                </label>
                                <input id="last_name" name="last_name" type="text" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="col-span-6">
                            <div>
                                <label for="regular-form-1" class="form-label">รหัสผ่านเดิม
                                    <small class="text-danger password_err _err"></small>
                                </label>
                                <div class="input-group"> <input type="password" id="password_edit" name="password"
                                        class="form-control" placeholder="">
                                    <div id="input-group-price" class="input-group-text toggle_edit_password">
                                        <i id="icon_eye_edit_password" class="fa-solid fa-eye "></i>
                                        <i id="icon_eye_edit_password_hide" class="fa-solid fa-eye-slash"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-span-6">
                        </div>
                        <div class="col-span-6">
                            <div>
                                <label for="regular-form-1" class="form-label">รหัสผ่านใหม่ ที่ต้องการเปลี่ยน
                                    <small class="text-danger password_new_err _err"></small>
                                </label>
                                <div class="input-group"> <input type="password" id="password_new" name="password_new"
                                        class="form-control" placeholder="">
                                    <div id="input-group-price" class="input-group-text toggle_edit_password_new">
                                        <i id="icon_eye_edit_password_new" class="fa-solid fa-eye "></i>
                                        <i id="icon_eye_edit_password_new_hide" class="fa-solid fa-eye-slash"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-span-6">
                        </div>
                        <div class="col-span-6">
                            <div>
                                <label for="regular-form-1" class="form-label">ยืนยันรหัสผ่าน ที่ต้องการเปลี่ยน
                                    <small class="text-danger password_new_comfirm_err _err"></small>
                                </label>
                                <div class="input-group"> <input type="password" name="password_new_comfirm"
                                        id="password_new_con" class="form-control" placeholder="">
                                    <div id="input-group-price" class="input-group-text toggle_edit_password_new_con">
                                        <i id="icon_eye_edit_password_new_con" class="fa-solid fa-eye "></i>
                                        <i id="icon_eye_edit_password_new_con_hide" class="fa-solid fa-eye-slash"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> <!-- END: Modal Body -->
                    <!-- BEGIN: Modal Footer -->
                    <div class="modal-footer">
                        <button type="button" data-tw-dismiss="modal"
                            class="btn btn-outline-danger w-20 mr-1">ยกเลิก</button>
                        <button type="submit" class="btn btn-outline-success  w-20">ตกลง</button>
                    </div> <!-- END: Modal Footer -->
            </div>
            </form>
        </div>
    </div> <!-- END: Modal edit_password -->
@endsection


@section('script')
    {{-- BEGIN DataTable --}}
    <script>
        $(function() {
            table_Member = $('#table_Member').DataTable({
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
                    url: '{{ route('get_data_member') }}',
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
                        data: "id",
                        title: "ชื่อ - สกุล",
                        className: "table-report__action",
                    },
                    {
                        data: "username",
                        title: "username",
                        className: "table-report__action",
                    },
                    {
                        data: "phone",
                        title: "เบอร์โทรศัพท์",
                        className: "table-report__action",
                    },
                    {
                        data: "credit",
                        title: "เครดิต",
                        className: "table-report__action",
                    },
                    {
                        data: "role",
                        title: "ตำแหน่ง",
                        className: "table-report__action",
                    },
                    {
                        data: "status",
                        title: "สถานะ",
                        className: "table-report__action",
                    },
                    {
                        data: "id",
                        title: "",
                        className: "table-report__action w-24",
                    },
                ],
                rowCallback: function(nRow, aData, dataIndex) {

                    //คำนวนลำดับของ รายการที่แสดง
                    var info = table_Member.page.info();
                    var page = info.page;
                    var length = info.length;
                    var index = (page * length + (dataIndex + 1));

                    var id = aData['id'];

                    //แสดงเลขลำดับ
                    $('td:nth-child(1)', nRow).html(`${index}`);


                    var name = aData['name']
                    var last_name = aData['last_name']

                    // ชื่อ-สกุล
                    $('td:nth-child(2)', nRow).html(`${name} ${last_name}`);

                    // สถานะ
                    var status = aData['status'];
                    var text_status = "";
                    var status_bg = "";
                    var edit_status = "";
                    var edit_text_status = "";
                    var icon = ""
                    if (status == 1) {
                        text_status = "เปิดใช้งาน"
                        status_bg = "text-success"
                        edit_status = 2
                        edit_text_status = 'ระงับการใช้งาน'
                        icon = '<i class="fa-solid fa-user-large-slash"></i>'
                        icon_bg = 'bg-danger'
                    } else {
                        text_status = "ระงับการใช้งาน"
                        status_bg = "text-danger"
                        edit_status = 1
                        edit_text_status = "เปิดใช้งาน"
                        icon = '<i class="fa-solid fa-user-large"></i>'
                        icon_bg = 'bg-success'
                    }
                    $('td:nth-child(7)', nRow).html(
                        ` <div class="${status_bg}"> ${text_status} </div> `)

                    // Action
                    $('td:nth-child(8)', nRow).html(
                        `
                    <a data-tw-toggle="modal" data-tw-target="#edit_password" onclick="get_data_edit(${id})" class="btn btn-sm btn-warning mr-2 "><i class="fa-solid fa-pen-to-square"></i></a>
                     <a onclick="checkDelete('${id}','${name}','${edit_text_status}' ,'${edit_status}')" class="btn btn-sm ${icon_bg} text-white"> ${icon}</a>`
                    );


                },
            });
            $('.myWhere,.myLike,.datepicker,.iSort,.myCustom').on('change', function(e) {
                table_Member.draw();
            });
        });
    </script>
    {{-- BEGIN DataTable --}}




    {{-- BEGIN Form --}}
    <script>
        // -- Start msg err ที่ถูกส่งมา --
        function printErrorMsg(msg) {
            console.log(msg);
            $('._err').text('');
            $.each(msg, function(key, value) {

                $('.' + key + '_err').text(`*${value}*`);
            });
        }

        function resetForm() {
            $('#form_add_member')[0].reset();
            $('#form_change_password')[0].reset();
            $('._err').text('');
        }

        const myModal = tailwind.Modal.getInstance(document.querySelector("#add_member"));
        $('#form_add_member').submit(function(e) {
            e.preventDefault();
            var formData = new FormData($(this)[0]);
            $.ajax({
                url: '{{ route('store_member') }}',
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(data) {
                    if ($.isEmptyObject(data.error) || data.status == "success") {
                        myModal.hide();
                        resetForm();
                        Swal.fire({
                            icon: 'success',
                            title: `บันทึกข้อมูลเรียบร้อย`,
                            confirmButtonColor: '#84CC18',
                            confirmButtonText: 'ยืนยัน',
                            timer: 3000,
                        }).then((result) => {
                            table_Member.draw();
                        });

                    } else {
                        printErrorMsg(data.error);
                    }
                }
            });
        });
    </script>
    {{-- END Form --}}


    {{-- BEGIN Get data edit password --}}
    <script>
        function get_data_edit(id) {
            $.ajax({
                url: '{{ route('data_edit_password') }}',
                method: 'post',
                data: {
                    '_token': '{{ csrf_token() }}',
                    'id': id,
                },

                success: function(data) {
                    resetForm();
                    for (const [key, value] of Object.entries(data)) {
                        $('#edit_password').find('#' + key).val(value);
                    }
                }
            });
        }
    </script>

    {{-- END Get data edit password --}}


    {{-- BEGIN เปิด - ปิด รหัสผ่าน --}}
    <script>
        $('#icon_eye_hide').hide();
        $(".toggle-password").click(function() {
            $(this).toggleClass("fa-solid fa-eye-slash");
            var input = $('#password');
            if (input.attr("type") == "password") {
                input.attr("type", "text");
                $('#icon_eye').hide();
                $('#icon_eye_hide').hide();
            } else {
                input.attr("type", "password");
                $('#icon_eye').show();
            }
        });

        $('#icon_eye_edit_password_hide').hide();
        $(".toggle_edit_password").click(function() {
            $(this).toggleClass("fa-solid fa-eye-slash");
            var input = $('#password_edit');
            if (input.attr("type") == "password") {
                input.attr("type", "text");
                $('#icon_eye_edit_password').hide();
                $('#icon_eye_edit_password_hide').hide();
            } else {
                input.attr("type", "password");
                $('#icon_eye_edit_password').show();
            }
        });

        $('#icon_eye_edit_password_new_hide').hide();
        $(".toggle_edit_password_new").click(function() {
            $(this).toggleClass("fa-solid fa-eye-slash");
            var input = $('#password_new');
            if (input.attr("type") == "password") {
                input.attr("type", "text");
                $('#icon_eye_edit_password_new').hide();
                $('#icon_eye_edit_password_new_hide').hide();
            } else {
                input.attr("type", "password");
                $('#icon_eye_edit_password_new').show();
            }
        });


        $('#icon_eye_edit_password_new_con_hide').hide();
        $(".toggle_edit_password_new_con").click(function() {
            $(this).toggleClass("fa-solid fa-eye-slash");
            var input = $('#password_new_con');
            if (input.attr("type") == "password") {
                input.attr("type", "text");
                $('#icon_eye_edit_password_new_con').hide();
                $('#icon_eye_edit_password_new_con_hide').hide();
            } else {
                input.attr("type", "password");
                $('#icon_eye_edit_password_new_con').show();
            }
        });
    </script>
    {{-- BEGIN เปิด - ปิด รหัสผ่าน --}}


    {{-- BEGIN form_change_password --}}
    <script>
        $('#form_change_password').submit(function(e) {
            const myModal = tailwind.Modal.getInstance(document.querySelector("#edit_password"));
            e.preventDefault();
            var formData = new FormData($(this)[0]);
            $.ajax({
                url: '{{ route('change_password_member') }}',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(data) {
                    if ($.isEmptyObject(data.error) || data.status == "success") {
                        myModal.hide();
                        resetForm();
                        Swal.fire({
                            icon: 'success',
                            title: `บันทึกข้อมูลเรียบร้อย`,
                            confirmButtonColor: '#84CC18',
                            confirmButtonText: 'ยืนยัน',
                            timer: 3000,
                        }).then((result) => {
                            table_Member.draw();
                        });
                    } else {
                        printErrorMsg(data.error);
                    }
                }
            });
        });
    </script>
    {{-- END form_change_password --}}




    {{-- BEGIN  ระงับการใช้งาน Member --}}
    <script>
        function checkDelete(id, name, text_status, status) {
            Swal.fire({
                icon: 'warning',
                title: `คุณต้องการ${text_status} ?`,
                text: `${name}`,
                showCancelButton: true,
                confirmButtonColor: '#84CC18',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ยืนยัน',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `{{ route('delete_member') }}`,
                        type: 'POST',
                        data: {
                            "id": id,
                            "status": status,
                            "_token": '{{ csrf_token() }}',
                        },
                        success: function(data) {
                            Swal.fire({
                                icon: 'success',
                                title: `ระงับการใช้งานสำเร็จ`,
                                confirmButtonColor: '#84CC18',
                                confirmButtonText: 'ยืนยัน',
                                timer: 3000,
                            }).then((result) => {
                                table_Member.draw();
                            });
                        }
                    });
                }
            });
        }
    </script>
    {{-- END  ระงับการใช้งาน Member --}}
@endsection
