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
                    <button class="btn btn-primary shadow-md mr-2" data-tw-toggle="modal"
                        data-tw-target="#add_product_size" onclick="product_size_add()">เพิ่ม
                        ขนาดสินค้า</button>
                </div>
            </div>
            <div class="card-block" style="margin-top:10px;">
                <div class="row">
                    <div class="dt-responsive table-responsive">
                        <table id="table_product_size" style="width:100%;"
                            class="table table-striped table-bordered nowrap">
                            <thead class="thead_txt_center">
                                <tr style="width:100%;">
                                    <th style="width: 15%; text-align:center;">#</th>
                                    <th style="width: 55%; text-align:center;">Title</th>
                                    <th style="width: 15%; text-align:center;">Status</th>
                                    <th style="width: 15%;">Action</th>
                                </tr>
                            </thead>
                            <tbody class="tbody_txt_center">
                                @if (isset($Product_size))
                                    @foreach ($Product_size as $item => $value)
                                        <tr>
                                            <td style="text-align:center;">{{ $item + 1 }}</td>
                                            <td style="text-align:center;">
                                                <p> {{ isset($value) ? $value->size : '' }}</p>
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
                                                            data-tw-target="#edit_product_size"
                                                            onclick="editProduct_Size({{ $value->id }})"><i
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
            {{-- <table id="table_product_size" class="table table-report">
            </table> --}}
            {{-- END TABLE --}}
        </div>
    </div>

    <!-- BEGIN: Modal Content -->
    <div id="add_product_size" class="modal" data-tw-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog ">
            <div class="modal-content">
                {{ Form::open(['url' => ['/admin/product_size/store'], 'id' => 'productSize-upload', 'autocomplete' => 'off', 'enctype' => 'multipart/form-data']) }}
                <!-- BEGIN: Modal Header -->
                <div class="modal-header">
                    <h2 class="font-medium text-base mr-auto">เพิ่ม ขนาดสินค้า</h2>
                    <a data-tw-dismiss="modal" href="javascript:;"> <i data-lucide="x" class="w-8 h-8 text-slate-400"></i>
                    </a>
                </div> <!-- END: Modal Header -->
                <!-- BEGIN: Modal Body -->
                <div class="modal-body grid grid-cols-12 gap-4 gap-y-3 bg-slate-100/50">
                    <div class="col-span-12">
                        <div>
                            <label for="regular-form-1" class="form-label">Size Name :
                                <span class="text-danger name_err _err"></span>
                            </label>
                            <input id="regular-form-1" name="size_name" id="size_name" type="text"
                                class="form-control" required>
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


    @include('backend.product.product_size_edit')
@endsection

@section('script')
    <!-- dropify -->
    <script src="{{ asset('backend/dist/js/dropify.min.js') }}"></script>
    <!-- summernote -->
    <script src="{{ asset('backend/dist/summernote-0.8.18-dist/summernote-lite.min.js') }}"></script>

    {{-- BEGIN DataTable --}}
    <script>
        $(document).ready(function() {
            $('#table_product_size').DataTable({});
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

        function product_size_add() {
            $('#productSize-upload').attr('action', "{!! url('/admin/product_size/store') !!}");
            $('#submit').text('Save');
            // $('#add_product_size').modal('show');
        }

        function editProduct_Size(id) {
            $(`input[name="id"]`).val(id);
            $('#product_size-edit').attr('action', "{!! url('/admin/product_size/edit') !!}");
            $.ajax({
                type: 'GET',
                url: '{{ url('/admin/product_size/edit_data') }}',
                data: {
                    id: id
                },
                success: function(result) {
                    console.log(result);
                    $('input[name^=size_name_update').val(result['size'])
                    $('#status_update').val(result['status'])
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
                        url: "{!! url('/admin/product_size/delete/" + id + "') !!}",
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
@endsection
