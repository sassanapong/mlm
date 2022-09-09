@extends('layouts.backend.app')

@section('head')
@endsection

@section('css')
    <!-- dropify -->
    <link rel="stylesheet" href="{{ asset('backend/dist/css/dropify.min.css') }}">
    <!-- summernote -->
    <link rel="stylesheet" href="{{ asset('backend/dist/summernote-0.8.18-dist/summernote-lite.min.css') }}">

    <style>
        .modal .modal-dialog {
            width: 700px;
        }

        .dataTables_wrapper .dataTables_length select {
            border: 1px solid #aaa;
            border-radius: 3px;
            padding: 5px;
            background-color: transparent;
            padding: 4px;
            width: 50px;
        }

        .dataTables_wrapper .dataTables_filter input {
            border: 1px solid #aaa;
            border-radius: 3px;
            padding: 5px;
            background-color: transparent;
            margin-left: 3px;
            margin-bottom: 10px;
        }

        .dataTables_wrapper .dataTables_paginate {
            float: right;
            text-align: right;
            padding-top: 0.75em;
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
                    <button class="btn btn-primary shadow-md mr-2" data-tw-toggle="modal" data-tw-target="#add_news"
                        onclick="modal()">เพิ่ม
                        ข่าวสาร</button>
                </div>

                {{-- <div class="hidden md:block mx-auto text-slate-500"></div>
                <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">

                    <div class=" relative text-slate-500">
                        <div class="form-inline">
                            <label for="" class="mr-2">ค้นหา</label>
                            <input type="text" name="name" class="form-control w-56 box pr-10 myLike"
                                placeholder="ค้นหา...">
                            <i class="w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0" data-lucide="search"></i>
                        </div>
                    </div>

                </div> --}}
            </div>
            <div class="card-block" style="margin-top:10px;">
                <div class="row">
                    <div class="dt-responsive table-responsive">
                        <table id="table_news" style="width:100%;" class="table table-striped table-bordered nowrap">
                            <thead class="thead_txt_center">
                                <tr style="width:100%;">
                                    <th style="width: 15%; text-align:center;">#</th>
                                    <th style="width: 70%; text-align:center;">Title</th>
                                    <th style="width: 15%;">Action</th>
                                </tr>
                            </thead>
                            <tbody class="tbody_txt_center">
                                @foreach ($News as $item => $value)
                                    <tr>
                                        <td style="text-align:center;">{{ $item + 1 }}</td>
                                        <td style="text-align:center;">
                                            <p> {{ isset($value) ? $value->title_news : '' }}</p>
                                        </td>
                                        <td>
                                            <div class="row">
                                                <div class="offset-4 col-1">
                                                    {{-- <button onclick="edit_modal({{ $value->id }})" class="btn btn-info btn-round btn-mini">แก้ไข</button> --}}
                                                    <a href="{{ url('news_manage/edit/' . $value->id) }}">
                                                        <button class="btn btn-sm btn-warning mr-2"><i
                                                                class="fa-solid fa-pen-to-square"></i></button>
                                                    </a>
                                                    <button onclick="del_user({{ $value->id }})"
                                                        class="btn btn-sm btn-warning mr-2"><i
                                                            class="fa-solid fa-square-minus"></i></button>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            {{-- <table id="table_News" class="table table-report">
            </table> --}}
            {{-- END TABLE --}}
        </div>
    </div>

    <!-- BEGIN: Modal Content -->
    <div id="add_news" class="modal" data-tw-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog ">
            <div class="modal-content">
                {{ Form::open(['url' => ['/admin/news_manage/store'], 'id' => 'news-upload', 'autocomplete' => 'off', 'enctype' => 'multipart/form-data']) }}
                <!-- BEGIN: Modal Header -->
                <div class="modal-header">
                    <h2 class="font-medium text-base mr-auto">เพิ่มข่าวสาร</h2>
                    <a data-tw-dismiss="modal" href="javascript:;"> <i data-lucide="x" class="w-8 h-8 text-slate-400"></i>
                    </a>
                </div> <!-- END: Modal Header -->
                <!-- BEGIN: Modal Body -->
                <div class="modal-body grid grid-cols-12 gap-4 gap-y-3 bg-slate-100/50">
                    <div class="col-span-12">
                        <div>
                            <label for="regular-form-1" class="form-label">Title :
                                <span class="text-danger name_err _err"></span>
                            </label>
                            <input id="regular-form-1" name="title_news" id="title_news" type="text"
                                class="form-control">
                        </div>
                    </div>

                    <div class="col-span-12">
                        <div>
                            <label for="regular-form-1" class="form-label">Image <span style="color: red"> (jpeg, jpg, png)</span> :
                                <span class="text-danger name_err _err"></span>
                            </label>
                            <input type="file" name="image_news" id="input-file-now" class="dropify"
                                data-default-file="DROP IMAGE (jpeg, jpg, png)" />
                        </div>
                    </div>

                    <div class="col-span-12">
                        <div>
                            <label for="regular-form-1" class="form-label">Detail :
                            </label>
                            <textarea class="summernote_ed1" rows="5" name="detail_news" id="regular-form-1" placeholder="Text"></textarea>
                        </div>
                    </div>

                    <div class="col-span-12">
                        <span class="text-danger role_err _err"></span>
                        <label class="col-md-2 col-form-label text-right">Upload Video :</label>
                        <div class="col-span-12">
                            <div class="form-group row">
                                <div class="col-md-12 dz-default dz-massage">
                                    <div class="fallback">
                                        <input name="check_type2" type="radio" value="Upload" />
                                        <label for="">Upload</label>&nbsp;&nbsp;
                                        <input name="check_type2" type="radio" value="Link" />
                                        <label for="">Link</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-span-12" id="input2_file1" style="display: none;">
                        <div>
                            <label for="regular-form-1" class="form-label"> Video <span style="color: red"> (mp4) :
                            </label>
                            <div class="fallback">
                                <input name="upload_video" type="file" multiple />
                            </div>
                        </div>
                    </div>

                    <div class="col-span-12" id="input2_file2" style="display: none;">
                        <div>
                            <label for="regular-form-1" class="form-label"> Link :
                            </label>
                            <input name="link_news" type="text" class="form-control" />
                        </div>
                    </div>

                    <div class="col-span-12">
                        <div>
                            <label for="regular-form-1" class="form-label">Date :
                            </label>
                            <input id="regular-form-1" name="date_news" type="date" class="form-control">
                        </div>
                    </div>
                </div> <!-- END: Modal Body -->
                <!-- BEGIN: Modal Footer -->
                <input type="hidden" name="id" id="id">
                <div class="modal-footer">
                    {{-- <button type="button" data-tw-dismiss="modal"
                            class="btn btn-outline-danger w-20 mr-1">ไม่ผ่าน</button> --}}
                    {{-- <button type="submit" class="btn btn-outline-success  w-20">ตกลง</button> --}}
                    <button type="submit" id="submit" class="btn btn-outline-success  w-20">ตกลง</button>
                </div>
                <!-- END: Modal Footer -->
                {{ Form::close() }}
            </div>
        </div>
    </div> <!-- END: Modal Content -->

    <!-- BEGIN: Modal edit_password -->
    {{-- <div id="edit_password" class="modal" data-tw-backdrop="static" tabindex="-1" aria-hidden="true">
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
                            class="btn btn-outline-danger w-20 mr-1">ไม่ผ่าน</button>
                        <button type="submit" class="btn btn-outline-success  w-20">ผ่าน</button>
                    </div> <!-- END: Modal Footer -->
            </div>
            </form>
        </div>
    </div> --}}
    <!-- END: Modal edit_password -->
@endsection


@section('script')
    <!-- dropify -->
    <script src="{{ asset('backend/dist/js/dropify.min.js') }}"></script>
    <!-- summernote -->
    <script src="{{ asset('backend/dist/summernote-0.8.18-dist/summernote-lite.min.js') }}"></script>

    {{-- BEGIN DataTable --}}
    <script>
        $(document).ready(function() {
            $('#table_news').DataTable({});
            @if (!empty(Session::get('error')) and Session::get('error') == 'error')
                swal({
                    title: 'Duplicate Mission name',
                    type: 'warning',
                    confirmButtonColor: '#999999',
                    confirmButtonText: 'Close'
                }).then((result) => {
                    {
                        {
                            Session::put('error', '-')
                        }
                    }
                })
            @endif
        });

        function modal() {
            $('#news-upload').attr('action', "{!! url('/admin/news_manage/store') !!}");
            $('#title_news').val('');
            $('#submit').text('Save');
            $('#exampleModal').modal('toggle');
        }
    </script>
    {{-- BEGIN DataTable --}}

    <script type="text/javascript">
        $(document).ready(function() {
            // Basic
            $('.dropify').dropify();
            // Used events
            var drEvent = $('.dropify-event').dropify();
            drEvent.on('dropify.beforeClear', function(event, element) {
                return confirm("Do you really want to delete \"" + element.filename + "\" ?");
            });
            drEvent.on('dropify.afterClear', function(event, element) {
                alert('File deleted');
            });

            $("input[type='radio']").change(function() {
                var radioValue = $("input[name='check_type1']:checked").val();
                if (radioValue == "Upload") {
                    $("#input1_file1").css("display", "inline");
                    $("input[name='file1']").prop("disabled", false);
                    $("#input1_file2").css("display", "none");
                    $("input[name='file1_1']").prop("disabled", true);
                } else if (radioValue == "Link") {
                    $("#input1_file1").css("display", "none");
                    $("input[name='file1']").prop("disabled", true);
                    $("#input1_file2").css("display", "inline");
                    $("input[name='file1_1']").prop("disabled", false);
                }
            })

            $("input[type='radio']").change(function() {
                var radioValue = $("input[name='check_type2']:checked").val();
                if (radioValue == "Upload") {
                    $("#input2_file1").css("display", "inline");
                    $("input[name='file2']").prop("disabled", false);
                    $("#input2_file2").css("display", "none");
                    $("input[name='file2_1']").prop("disabled", true);
                } else if (radioValue == "Link") {
                    $("#input2_file1").css("display", "none");
                    $("input[name='file2']").prop("disabled", true);
                    $("#input2_file2").css("display", "inline");
                    $("input[name='file2_1']").prop("disabled", false);
                }
            })
        });
    </script>

    {{-- BEGIN Form --}}
    {{-- <script>
        // -- Start msg err ที่ถูกส่งมา --
        function printErrorMsg(msg) {
            console.log(msg);
            $('._err').text('');
            $.each(msg, function(key, value) {

                $('.' + key + '_err').text(`*${value}*`);
            });
        }

        function resetForm() {
            $('#form_news')[0].reset();
            $('#form_change_password')[0].reset();
            $('._err').text('');
        }

        const myModal = tailwind.Modal.getInstance(document.querySelector("#add_news"));
        $('#form_new').submit(function(e) {
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
                            table_News.draw();
                        });

                    } else {
                        printErrorMsg(data.error);
                    }
                }
            });
        });
    </script> --}}
    {{-- END Form --}}


    {{-- BEGIN Get data edit password --}}
    {{-- <script>
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
    </script> --}}

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
    {{-- <script>
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
                            table_News.draw();
                        });
                    } else {
                        printErrorMsg(data.error);
                    }
                }
            });
        });
    </script> --}}
    {{-- END form_change_password --}}




    {{-- BEGIN  ระงับการใช้งาน Member --}}
    <script>
        // function checkDelete(id, name, text_status, status) {
        //     Swal.fire({
        //         icon: 'warning',
        //         title: `คุณต้องการ${text_status} ?`,
        //         text: `${name}`,
        //         showCancelButton: true,
        //         confirmButtonColor: '#84CC18',
        //         cancelButtonColor: '#d33',
        //         confirmButtonText: 'ยืนยัน',
        //         cancelButtonText: 'ยกเลิก'
        //     }).then((result) => {
        //         if (result.isConfirmed) {
        //             $.ajax({
        //                 url: `{{ route('delete_member') }}`,
        //                 type: 'POST',
        //                 data: {
        //                     "id": id,
        //                     "status": status,
        //                     "_token": '{{ csrf_token() }}',
        //                 },
        //                 success: function(data) {
        //                     Swal.fire({
        //                         icon: 'success',
        //                         title: `ระงับการใช้งานสำเร็จ`,
        //                         confirmButtonColor: '#84CC18',
        //                         confirmButtonText: 'ยืนยัน',
        //                         timer: 3000,
        //                     }).then((result) => {
        //                         table_News.draw();
        //                     });
        //                 }
        //             });
        //         }
        //     });
        // }
    </script>
    {{-- END  ระงับการใช้งาน Member --}}

    <!-- summernote -->
    <script type="text/javascript">
        $('.summernote_ed1').summernote({
            fontSizes: ['6', '8', '9', '10', '11', '12', '14', '16', '18', '20', '22', '24', '30', '36', '48', '64',
                '72'
            ],
            height: 150,
            popover: {
                table: [
                    ['custom', ['imageAttributes']],
                    ['add', ['addRowDown', 'addRowUp', 'addColLeft', 'addColRight']],
                    ['delete', ['deleteRow', 'deleteCol', 'deleteTable']],
                ],
                image: [
                    ['resize', ['resizeFull', 'resizeHalf', 'resizeQuarter', 'resizeNone']],
                    ['float', ['floatLeft', 'floatRight', 'floatNone']],
                    ['remove', ['removeMedia']],
                ],
                link: [
                    ['link', ['linkDialogShow', 'unlink']],
                ],
                air: [
                    ['color', ['color']],
                    ['font', ['bold', 'italic', 'underline', 'clear']],
                    ['para', ['ul', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture']],
                ],
            },
            imageAttributes: {
                icon: '<i class="note-icon-pencil"/>',
                removeEmpty: false, // true = remove attributes | false = leave empty if present
                disableUpload: false // true = don't display Upload Options | Display Upload Options
            },
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough', 'superscript', 'subscript']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
            ]
        });

        //แก้ปัญหา copy paste ไม่ได้
        $('.summernote_ed1').on('summernote.paste', function(e, ne) {
            //get the text
            let inputText = ((ne.originalEvent || ne).clipboardData || window.clipboardData).getData('Text');
            // block default behavior
            ne.preventDefault();
            //modify paste text as plain text
            let modifiedText = inputText.replace(/\r?\n/g, '<br>');
            //put it back in editor
            document.execCommand('insertHtml', false, modifiedText);

        })
    </script>
@endsection
