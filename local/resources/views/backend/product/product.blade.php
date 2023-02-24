@extends('layouts.backend.app')

@section('head')
    {{-- select2 --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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
                    <button class="btn btn-primary shadow-md mr-2" data-tw-toggle="modal" data-tw-target="#add_product"
                        onclick="product_add()">เพิ่ม
                        สินค้า</button>
                </div>
            </div>
            <div class="card-block" style="margin-top:10px;">
                <div class="row">
                    <div class="dt-responsive table-responsive">
                        <table id="table_product" style="width:100%;" class="table table-striped table-bordered nowrap">
                            <thead class="thead_txt_center">
                                <tr style="width:100%;">
                                    <th style="width: 15%; text-align:center;">#</th>
                                    <th style="width: 55%; text-align:center;">Title</th>
                                    <th>Slide No.</th>
                                    <th style="width: 15%; text-align:center;">Status</th>
                                    <th style="width: 15%;">Action</th>
                                </tr>
                            </thead>
                            <tbody class="tbody_txt_center">
                                @if (isset($Product))
                                    @foreach ($Product as $item => $value)
                                        <tr>
                                            <td style="text-align:center;">{{ $item + 1 }}</td>
                                            <td style="text-align:center;">
                                                <p> {{ isset($value) ? $value->product_name : '' }}</p>
                                            </td>
                                            <td>Slide No.
                                                {{ isset($value->orderby) ? $value->orderby : '' }}
                                                <br> <button onclick="slide_no({{ $value->id }})" data-tw-toggle="modal"
                                                    data-tw-target="#slideModal" class="btn btn-primary btn-round btn-mini">
                                                    แก้ไขลำดับสไลด์</button>
                                            </td>
                                            <td style="text-align:center;">
                                                @if (isset($value->status))
                                                    @if ($value->status == 1)
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
                                                            data-tw-target="#edit_product"
                                                            onclick="editProduct({{ $value->id }})"><i
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
            {{-- <table id="table_product" class="table table-report">
            </table> --}}
            {{-- END TABLE --}}
        </div>
    </div>

    <!-- BEGIN: Modal Content -->
    <div id="add_product" class="modal" data-tw-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog ">
            <div class="modal-content">
                {{ Form::open(['url' => ['/admin/product/store'], 'id' => 'product-upload', 'autocomplete' => 'off', 'enctype' => 'multipart/form-data']) }}
                <!-- BEGIN: Modal Header -->
                <div class="modal-header">
                    <h2 class="font-medium text-base mr-auto">เพิ่มสินค้า</h2>
                    <a data-tw-dismiss="modal" href="javascript:;"> <i data-lucide="x" class="w-8 h-8 text-slate-400"></i>
                    </a>
                </div> <!-- END: Modal Header -->
                <!-- BEGIN: Modal Body -->
                <div class="modal-body grid grid-cols-12 gap-4 gap-y-3 bg-slate-100/50">
                    <div class="col-span-12">
                        <div>
                            <label for="regular-form-1" class="form-label">Product Name :
                                <span class="text-danger name_err _err"></span>
                            </label>
                            <input id="regular-form-1" name="product_name" id="product_name" type="text"
                                class="form-control" required>
                        </div>
                    </div>

                    <div class="col-span-12">
                        <div>
                            <label for="regular-form-1" class="form-label">Image <span style="color: red"> (jpeg, jpg,
                                    png) ขนาด ... px</span> :
                                <span class="text-danger name_err _err"></span>
                            </label>
                            <input type="file" name="product_img" id="input-file-now" class="dropify"
                                data-default-file="DROP IMAGE (jpeg, jpg, png)" required />
                        </div>
                    </div>

                    <div class="col-span-12">
                        <div>
                            <label for="regular-form-1" class="form-label">Product Title :
                                <span class="text-danger name_err _err"></span>
                            </label>
                            <input id="regular-form-1" name="product_title" id="product_title" type="text"
                                class="form-control">
                        </div>
                    </div>

                    <div class="col-span-12">
                        <div>
                            <label for="regular-form-1" class="form-label">Descriptions :
                            </label>
                            <textarea class="summernote_ed1" rows="3" name="product_descrip" id="regular-form-1" placeholder="Text"></textarea>
                        </div>
                    </div>

                    <div class="col-span-12">
                        <div>
                            <label for="regular-form-1" class="form-label">Product Details :
                            </label>
                            <textarea class="summernote_ed1" rows="3" name="products_details" id="regular-form-1" placeholder="Text"></textarea>
                        </div>
                    </div>

                    <div class="col-span-4">
                        <div>
                            <label for="regular-form-1" class="form-label">Product Cost-Price :
                                <span class="text-danger name_err _err"></span>
                            </label>
                            <input id="regular-form-1" name="cost_price" id="cost_price" type="number" step='0.01'
                                placeholder='0.00' class="form-control">
                        </div>
                    </div>

                    <div class="col-span-4">
                        <div>
                            <label for="regular-form-1" class="form-label">Product Selling-Price :
                                <span class="text-danger name_err _err"></span>
                            </label>
                            <input id="regular-form-1" name="selling_price" id="selling_price" type="number"
                                step='0.01' placeholder='0.00' class="form-control">
                        </div>
                    </div>

                    <div class="col-span-4">
                        <div>
                            <label for="regular-form-1" class="form-label">Product Member-Price :
                                <span class="text-danger name_err _err"></span>
                            </label>
                            <input id="regular-form-1" name="member_price" id="member_price" type="number"
                                step='0.01' placeholder='0.00' class="form-control">
                        </div>
                    </div>

                    <div class="col-span-6">
                        <div>
                            <label for="regular-form-1" class="form-label">Product PV :
                                <span class="text-danger name_err _err"></span>
                            </label>
                            <input id="regular-form-1" name="product_pv" id="product_pv" type="number" placeholder='0'
                                class="form-control">
                        </div>
                    </div>
                    <div class="col-span-6">
                        <div>
                            <label for="regular-form-1" class="form-label">ค่าขนส่ง :
                                <span class="text-danger name_err _err"></span>
                            </label>
                            <select type="text" class="rounded" name="status_shipping" id="status_shipping"
                                style="width:100%; padding: 4px; font-size:14px;">
                                <option value="" selected>เลือกสถานะ</option>
                                <option value="Y"> คิดค่าส่ง </option>
                                <option value="N"> ไม่คิดค่าส่ง </option>
                            </select>
                        </div>
                    </div>

                    <div class="col-span-12">
                        <div>
                            <select type="text" class="rounded" name="select_category" id="select_category"
                                style="width:100%; padding: 4px; font-size:14px;">
                                <option value="" selected>กรุณาเลือกหมวดหมู่สินค้า</option>
                                @if (isset($Product_cate))
                                    @foreach ($Product_cate as $item => $value)
                                        <option value="{{ $value->id }}">{{ $value->category_name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>

                    <div class="col-span-6">
                        <div>
                            <select type="text" class="rounded" name="select_unit" id="select_unit"
                                style="width:100%; padding: 4px; font-size:14px;">
                                <option value="" selected>กรุณาเลือกหน่วยสินค้า</option>
                                @if (isset($Product_unit))
                                    @foreach ($Product_unit as $item => $value)
                                        <option value="{{ $value->id }}">{{ $value->product_unit }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>

                    <div class="col-span-6">
                        <div>
                            <select type="text" class="rounded" name="select_size" id="select_size"
                                style="width:100%; padding: 4px; font-size:14px;">
                                <option value="" selected>กรุณาเลือกขนาดสินค้า</option>
                                @if (isset($Product_size))
                                    @foreach ($Product_size as $item => $value)
                                        <option value="{{ $value->id }}">{{ $value->size }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>

                    <div class="col-span-12">

                    </div>

                    <div class="col-span-6">
                        <div>
                            <select type="text" class="rounded" name="select_product_lang" id="select_product_lang"
                                style="width:100%; padding: 4px; font-size:14px;">
                                {{-- <option value="" selected>เลือกภาษา</option>
                                <option value="0">EN</option> --}}
                                <option value="1" selected>TH</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-span-6">
                        <div>
                            <select type="text" class="rounded" name="status" id="status"
                                style="width:100%; padding: 4px; font-size:14px;">
                                <option value="" selected>เลือกสถานะ</option>
                                <option value="0">ปิดการใช้งาน</option>
                                <option value="1">เปิดการใช้งาน</option>
                            </select>
                        </div>
                    </div>


                    <div class="col-span-12 mt-3">
                        <div class="w-full flex justify-center border-t border-slate-200/60 dark:border-darkmode-400 mt-2">
                            <div class="bg-white dark:bg-darkmode-600 px-5 -mt-3 text-slate-500">วัตถุดิบ</div>
                        </div>
                    </div>


                    <div class="col-span-4">
                        <div>
                            <label for="">วัตถุดิ</label>
                            <select type="text" class="rounded " name="materials[1][id]"
                                style="width:100%; padding: 4px; font-size:14px;">
                                <option value="" selected>เลือกวัตถุดิบ</option>

                                @foreach ($materials as $key => $item)
                                    <option value="{{ $item->id }}">{{ $item->materials_name }}</option>
                                @endforeach

                            </select>
                        </div>
                    </div>

                    <div class="col-span-4">
                        <label for="">จำนวน</label>
                        <input type="number" name="materials[1][count]" class="form-control">
                    </div>

                    <div class="col-span-4 my-auto ">
                        <p class="btn btn-success btn-sm mt-4 add_materials">+</p>
                    </div>

                    <div class="col-span-12">
                        <div class="box_materials">

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

    <!--OPEN: Slide No. Modal -->
    <div id="slideModal" class="modal" data-tw-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog ">
            <div class="modal-content">
                {{ Form::open(['url' => ['admin/product/slide/update'], 'id' => 'gen_form', 'autocomplete' => 'off', 'enctype' => 'multipart/form-data']) }}
                <!-- BEGIN: Modal Header -->
                <div class="modal-header">
                    <h2 class="font-medium text-base mr-auto">Edit Slide No.</h2>
                    <a data-tw-dismiss="modal" href="javascript:;"> <i data-lucide="x"
                            class="w-8 h-8 text-slate-400"></i>
                    </a>
                </div> <!-- END: Modal Header -->
                {{-- <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Slide No.</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div> --}}
                <div class="modal-body grid grid-cols-12 gap-4 gap-y-3 bg-slate-100/50">
                    <div class="col-span-12">
                        <div>
                            <label for="regular-form-1" class="form-label">Slide :
                            </label>
                            <label id="show_slide_no">
                            </label>
                            <input type="hidden" name="show_id" id="show_id">
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    {{-- <button type="button" data-tw-dismiss="modal"
                            class="btn btn-outline-danger w-20 mr-1">ไม่ผ่าน</button> --}}
                    {{-- <button type="submit" class="btn btn-outline-success  w-20">ตกลง</button> --}}
                    <button type="submit" id="submit" class="btn btn-outline-success  w-20">ตกลง</button>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
    <!--END: Slide No. Modal -->


    @include('backend.product.product_edit')
@endsection

@section('script')
    <!-- dropify -->
    <script src="{{ asset('backend/dist/js/dropify.min.js') }}"></script>
    <!-- summernote -->
    <script src="{{ asset('backend/dist/summernote-0.8.18-dist/summernote-lite.min.js') }}"></script>

    {{-- select2 --}}
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    {{-- BEGIN DataTable --}}
    <script>
        $(document).ready(function() {
            $('#table_product').DataTable({});
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

        function product_add() {
            $('#product-upload').attr('action', "{!! url('/admin/product/store') !!}");
            $('#submit').text('Save');
            // $('#add_product').modal('show');
        }

        function editProduct(id) {

            $(`input[name="id"]`).val(id);
            $('#product-edit').attr('action', "{!! url('/admin/product/edit') !!}");
            $.ajax({
                type: 'GET',
                url: '{{ url('/admin/product/edit_data') }}',
                data: {
                    id: id
                },
                success: function(data) {


                    var result = data.sql_product;

                    $('input[name^=product_name_update').val(result['product_name'])
                    $('input[name^=product_title_update').val(result['title'])
                    $('#product_descrip_update').summernote('code', result['descriptions']);
                    $('#products_details_update').summernote('code', result['products_details']);
                    $('input[name^=cost_price_update').val(result['cost_price'])
                    $('input[name^=selling_price_update').val(result['selling_price'])
                    $('input[name^=member_price_update').val(result['member_price'])
                    $('input[name^=product_pv_update').val(result['pv'])
                    $('#select_category_update').val(result['category_id'])
                    $('#select_unit_update').val(result['unit_id'])
                    $('#select_size_update').val(result['size_id'])
                    $('#select_product_lang_update').val(result['lang_id'])
                    $('#status_update').val(result['status'])
                    $('#status_shipping_update').val(result['status_shipping'])

                    // $('#exampleModal').modal('show');

                    append_detail_materals(data.materials);



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
                        url: "{!! url('/admin/product/delete/" + id + "') !!}",
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

        function slide_no(id) {
            $.ajax({
                'type': 'post',
                'url': "{{ url('admin/product/get/slide') }}",
                'data': {
                    'id': id,
                    '_token': "{{ csrf_token() }}"
                },
                'dataType': 'json',
                'success': function(data) {
                    var option = '<select name="slide_no" class="form-control">';
                    for (var i = 0; i < data.count; i++) {
                        var slide = i + 1;
                        var selected = '';
                        if (data.Product.orderby == slide) {
                            selected = 'selected';
                        }
                        option += '<option value="' + slide + '" ' + selected + '> Slide No. ' + slide +
                            '</option>';
                    }
                    option += '</select>';
                    // $('#slideModal').modal();
                    $('#submit').text('Save');
                    $("#show_id").val(data.Product.id);
                    $('#show_slide_no').empty();
                    $('#show_slide_no').append(option);

                }
            });
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
        });
    </script>

    <!-- summernote -->
    <script type="text/javascript">
        $('.summernote_ed1').summernote({
            fontSizes: ['6', '8', '9', '10', '11', '12', '14', '16', '18', '20', '22', '24', '30', '36', '48', '64',
                '72'
            ],
            height: 80,
            popover: {
                // table: [
                //     ['custom', ['imageAttributes']],
                //     ['add', ['addRowDown', 'addRowUp', 'addColLeft', 'addColRight']],
                //     ['delete', ['deleteRow', 'deleteCol', 'deleteTable']],
                // ],
                // image: [
                //     ['resize', ['resizeFull', 'resizeHalf', 'resizeQuarter', 'resizeNone']],
                //     ['float', ['floatLeft', 'floatRight', 'floatNone']],
                //     ['remove', ['removeMedia']],
                // ],
                // link: [
                //     ['link', ['linkDialogShow', 'unlink']],
                // ],
                air: [
                    ['color', ['color']],
                    ['font', ['bold', 'italic', 'underline', 'clear']],
                    // ['para', ['ul', 'paragraph']],
                    // ['table', ['table']],
                    // ['insert', ['link', 'picture']],
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
                // ['color', ['color']],
                // ['para', ['ul', 'ol', 'paragraph']],
                // ['height', ['height']],
                // ['table', ['table']],
                // ['insert', ['link', 'picture', 'video']],
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

        var count_box_materials = 1;
        $(document).on("click", ".add_materials", function() {
            count_box_materials++;
            $('.box_materials').append(`
            <div id="" class="grid grid-cols-12 gap-4 gap-y-3 box_list_${count_box_materials}"  >
                <div class="col-span-4">
                        <div>
                            <label for="">วัตถุดิ</label>
                            <select type="text" class="rounded " name="materials[${count_box_materials}][id]" 
                                style="width:100%; padding: 4px; font-size:14px;">
                                <option value="" selected>เลือกวัตถุดิบ</option>

                                @foreach ($materials as $key => $item)
                                    <option value="{{ $item->id }}">{{ $item->materials_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-span-4">
                        <label for="">จำนวน</label>
                        <input type="number" name="materials[${count_box_materials}][count]"  class="form-control">
                    </div>

                    <div class="col-span-4 my-auto ">
                        <p onclick='del_box_list(${count_box_materials})' class="btn btn-danger btn-sm mt-4   ">-</p>
                    </div>
                </div>
            `);
        });







        function append_detail_materals(data) {

            // console.log(data);
            $('.box_materials').empty();
            if (data.length > 0) {
                $('.materials_null').hide();
                data.forEach((val, key) => {

                    let btn_action = ''
                    if (key == 0) {
                        btn_action = '<p  class="btn btn-success btn-sm mt-4 add_materials">+</p>';
                    } else {
                        btn_action = `<p onclick="del_box_list(${key})" class="btn btn-danger btn-sm mt-4">-</p>`;
                    }


                    $('.box_materials').append(`
            <div  class="grid grid-cols-12 gap-4 gap-y-3 box_list_${key}"  >
                <div class="col-span-4">
                        <div class="edit_product_materials_${key}">
                            <label for="">วัตถุดิ</label>
                            <select type="text" class="rounded  " name="materials[${key}][id]" 
                                style="width:100%; padding: 4px; font-size:14px;">
                                <option value="" selected>เลือกวัตถุดิบ</option>

                                @foreach ($materials as $key => $item)
                                    <option value="{{ $item->id }}">{{ $item->materials_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-span-4">
                        <label for="">จำนวน</label>
                        <input type="number" name="materials[${key}][count]"  class="form-control product_materials_number_${key}">
                    </div>

                    <div class="col-span-4 my-auto ">
                        ${btn_action}
                    </div>
                </div>
            `);
                    $(`.edit_product_materials_${key} select`).val(val.matreials_id);
                    $(`.product_materials_number_${key}`).val(val.matreials_count);
                });




            } else {
                $('.materials_null').show();
            }

        }


        function del_box_list(id) {
            console.log(id);
            $(`.box_list_${id}`).remove();
        }
    </script>
@endsection
