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
                        onclick="news_add()">เพิ่ม
                        ข่าวสาร</button>
                </div>
            </div>
            <div class="card-block" style="margin-top:10px;">
                <div class="row">
                    <div class="dt-responsive table-responsive">
                        <table id="table_news" style="width:100%;" class="table table-striped table-bordered nowrap">
                            <thead class="thead_txt_center">
                                <tr style="width:100%;">
                                    <th style="width: 15%; text-align:center;">#</th>
                                    <th style="width: 55%; text-align:center;">Title</th>
                                    <th style="width: 15%; text-align:center;">Status</th>
                                    <th style="width: 15%;">Action</th>
                                </tr>
                            </thead>
                            <tbody class="tbody_txt_center">
                                @if (isset($News))
                                    @foreach ($News as $item => $value)
                                        <tr>
                                            <td style="text-align:center;">{{ $item + 1 }}</td>
                                            <td style="text-align:center;">
                                                <p> {{ isset($value) ? $value->title_news : '' }}</p>
                                            </td>
                                            <td style="text-align:center;">
                                                @php
                                                    $date = new DateTime();
                                                    $date->setTimezone(new DateTimeZone('Asia/Bangkok'));
                                                @endphp
                                                @if (isset($value->start_date_news))
                                                    @if ($value->end_date_news >= $date->format('Y-m-d'))
                                                        <button
                                                            class="btn btn-sm btn-warning mr-2 text-success">เปิดการใช้งาน
                                                        </button>
                                                    @else
                                                        <button class="btn btn-sm btn-warning mr-2 text-danger">ปิดการใช้งาน
                                                        </button>
                                                    @endif
                                                @endif
                                            </td>
                                            <td>
                                                <div class="row">
                                                    <div class="offset-4 col-1">
                                                        {{-- <button onclick="edit_modal({{ $value->id }})" class="btn btn-info btn-round btn-mini">แก้ไข</button> --}}
                                                        <button class="btn btn-sm btn-warning mr-2" data-tw-toggle="modal"
                                                            data-tw-target="#edit_news"
                                                            onclick="editNews({{ $value->id }})"><i
                                                                class="fa-solid fa-pen-to-square"></i></button>
                                                        <button onclick="del_user({{ $value->id }})"
                                                            class="btn btn-sm btn-warning mr-2"><i
                                                                class="fa-solid fa-square-minus"></i></button>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
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
                            <input id="regular-form-1" name="title_news" id="title_news" type="text" class="form-control"
                                required>
                        </div>
                    </div>

                    <div class="col-span-12">
                        <div>
                            <label for="regular-form-1" class="form-label">Detail :
                                <span class="text-danger name_err _err"></span>
                            </label>
                            <input id="regular-form-1" name="detail_news" id="detail_news" type="text" value=""
                                class="form-control">
                        </div>
                    </div>

                    <div class="col-span-12">
                        <div>
                            <label for="regular-form-1" class="form-label">Image <span style="color: red"> (jpeg, jpg,
                                    png) ขนาด 1920 × 1297 px</span> :
                                <span class="text-danger name_err _err"></span>
                            </label>
                            <input type="file" name="image_news" id="input-file-now" class="dropify"
                                data-default-file="DROP IMAGE (jpeg, jpg, png)" required />
                        </div>
                    </div>

                    <div class="col-span-12">
                        <div>
                            <label for="regular-form-1" class="form-label">Detail All :
                            </label>
                            <textarea class="summernote_ed1" rows="5" name="detail_news_all" id="regular-form-1" placeholder="Text"></textarea>
                        </div>
                    </div>

                    <div class="col-span-12">
                        <span class="text-danger role_err _err"></span>
                        <label class="col-md-2 col-form-label text-right">Upload Video :</label>
                        <div class="col-span-12">
                            <div class="form-group row">
                                <div class="col-md-12 dz-default dz-massage">
                                    <div class="fallback">
                                        <input name="check_type1" type="radio" value="Upload" checked />
                                        <label for="">Upload</label>&nbsp;&nbsp;
                                        <input name="check_type1" type="radio" value="Link" />
                                        <label for="">Link</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-span-12" id="input2_file1">
                        <label for="regular-form-1" class="form-label"> Video <span style="color: red"> (mp4) :
                        </label>
                        <div class="fallback">
                            <input name="upload_video" type="file" />
                        </div>
                    </div>

                    <div class="col-span-12" id="input2_file2" style="display: none;">
                        <label for="regular-form-1" class="form-label"> Link :
                        </label>
                        <label for="regular-form-1" class="form-label"><span style="color:red">** Link Youtube เช่น
                                https://www.youtube.com<span style="color:blue">/embed/</span>zthvZvw-yJE <span
                                    style="color:blue">ต้องใช้ /embed/ เพราะเป็นข้อจำกัดของ Youtube </span>**</span>
                        </label>
                        <input name="link_news" type="text" class="form-control" />
                    </div>

                    <div class="col-span-6">
                        <div>
                            <label for="regular-form-1" class="form-label">Start-Date :
                            </label>
                            <input id="regular-form-1" name="start_date_news" type="date" class="form-control"
                                required>
                        </div>
                    </div>

                    <div class="col-span-6">
                        <div>
                            <label for="regular-form-1" class="form-label">End-Date :
                            </label>
                            <input id="regular-form-1" name="end_date_news" type="date" class="form-control"
                                required>
                        </div>
                    </div>
                </div> <!-- END: Modal Body -->
                <!-- BEGIN: Modal Footer -->
                {{-- <input type="hidden" name="id" id="id"> --}}
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


    @include('backend.news.news_manage_edit')
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
                Swal.fire({
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

        function news_add() {
            $('#news-upload').attr('action', "{!! url('/admin/news_manage/store') !!}");
            $('#submit').text('Save');
            // $('#add_news').modal('show');
        }

        function editNews(id) {
            $(`input[name="id"]`).val(id);
            $('#news-edit').attr('action', "{!! url('/admin/news_manage/edit') !!}");
            $.ajax({
                type: 'GET',
                url: '{{ url('/admin/news_manage/edit_data') }}',
                data: {
                    id: id
                },
                success: function(result) {
                    // console.log(result);
                    $('input[name^=title_news_update').val(result['title_news'])
                    $('input[name^=detail_news_update').val(result['detail_news'])
                    $('#summernote_news').summernote('code', result['detail_news_all']);
                    $('input[name^=link_news2').val(result['link_video'])
                    $('input[name^=start_date_news2').val(result['start_date_news'])
                    $('input[name^=end_date_news2').val(result['end_date_news'])

                    // $('#exampleModal').modal('show');
                }
            });
            $('#submit').text('Save');
        }

        function del_user(id) {
            Swal.fire({
                title: "ต้องการลบข้อมูลหรือไม่ ?",
                text: "การลบข้อมูลจะทำให้ข้อมูลหายไปอย่างถาวร",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Delete'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type: "GET",
                        url: "{!! url('/admin/news_manage/delete/" + id + "') !!}",
                        success: function(data) {
                            Swal.fire({
                                title: "Sucess!",
                                text: "ลบข้อมูลสำเร็จ",
                                type: "success",
                            }).then(() => {
                                location.reload();
                            })
                        }
                    });
                }
            })
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
                    $("#input2_file1").css("display", "inline");
                    $("input[name='upload_video']").prop("disabled", false);
                    $("#input2_file2").css("display", "none");
                    $("input[name='link_news']").prop("disabled", true);
                } else if (radioValue == "Link") {
                    $("#input2_file1").css("display", "none");
                    $("input[name='upload_video']").prop("disabled", true);
                    $("#input2_file2").css("display", "inline");
                    $("input[name='link_news']").prop("disabled", false);
                }
            })


            $("input[type='radio']").change(function() {
                var radioValue = $("input[name='check_type2']:checked").val();
                if (radioValue == "Upload") {
                    $("#input3_file1").css("display", "inline");
                    $("input[name='upload_video2']").prop("disabled", false);
                    $("#input3_file2").css("display", "none");
                    $("input[name='link_news2']").prop("disabled", true);
                } else if (radioValue == "Link") {
                    $("#input3_file1").css("display", "none");
                    $("input[name='upload_video2']").prop("disabled", true);
                    $("#input3_file2").css("display", "inline");
                    $("input[name='link_news2']").prop("disabled", false);
                }
            })
        });
    </script>

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
