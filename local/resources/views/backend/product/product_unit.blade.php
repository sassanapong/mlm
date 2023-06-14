@extends('layouts.backend.app_new')

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

@section('head_text')
<nav aria-label="breadcrumb" class="-intro-x mr-auto hidden sm:flex">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">จัดการสินค้า</a></li>
        <li class="breadcrumb-item active" aria-current="page">เพิ่มหน่วยสินค้า</li>
    </ol>
</nav>
@endsection

@section('content')


            {{-- BEGIN TABLE --}}
            <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-4">
                <div class="">
                    <button class="btn btn-primary shadow-md mr-2" data-tw-toggle="modal"
                        data-tw-target="#add_product_unit" onclick="product_unit_add()">เพิ่มหน่วยสินค้า</button>
                </div>
            </div>
            <div class="card-block" style="margin-top:10px;">
                <div class="row">
                    <div class="dt-responsive table-responsive">
                        <table id="table_product_unit" style="width:100%;"
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
                                @if (isset($Product_unit))
                                    @foreach ($Product_unit as $item => $value)
                                        <tr>
                                            <td style="text-align:center;">{{ $item + 1 }}</td>
                                            <td style="text-align:center;">
                                                <p> {{ isset($value) ? $value->product_unit : '' }}</p>
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
                                                            data-tw-target="#edit_product_unit"
                                                            onclick="editProduct_Unit({{ $value->id }})"><i
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
            {{-- <table id="table_product_unit" class="table table-report">
            </table> --}}
            {{-- END TABLE --}}


    <!-- BEGIN: Modal Content -->
    <div id="add_product_unit" class="modal" data-tw-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog ">
            <div class="modal-content">
                {{ Form::open(['url' => ['/admin/product_unit/store'], 'id' => 'productUnit-upload', 'autocomplete' => 'off', 'enctype' => 'multipart/form-data']) }}
                <!-- BEGIN: Modal Header -->
                <div class="modal-header">
                    <h2 class="font-medium text-base mr-auto">เพิ่ม หน่วยสินค้า</h2>
                    <a data-tw-dismiss="modal" href="javascript:;"> <i data-lucide="x" class="w-8 h-8 text-slate-400"></i>
                    </a>
                </div> <!-- END: Modal Header -->
                <!-- BEGIN: Modal Body -->
                <div class="modal-body grid grid-cols-12 gap-4 gap-y-3 bg-slate-100/50">
                    <div class="col-span-12">
                        <div>
                            <label for="regular-form-1" class="form-label">Unit Name :
                                <span class="text-danger name_err _err"></span>
                            </label>
                            <input id="regular-form-1" name="product_unit" id="product_unit" type="text"
                                class="form-control" required>
                        </div>
                    </div>

                    <div class="col-span-6">
                        <div>
                            <select type="text" class="rounded" name="select_pro_unit_lang" id="select_pro_unit_lang"
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


    @include('backend.product.product_unit_edit')
@endsection

@section('script')
    <!-- dropify -->
    <script src="{{ asset('backend/dist/js/dropify.min.js') }}"></script>
    <!-- summernote -->
    <script src="{{ asset('backend/dist/summernote-0.8.18-dist/summernote-lite.min.js') }}"></script>

<script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.2/js/dataTables.buttons.min.js"></script>

    {{-- BEGIN DataTable --}}
    <script>
        $(document).ready(function() {
            $('#table_product_unit').DataTable({});
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

        function product_unit_add() {
            $('#productUnit-upload').attr('action', "{!! url('/admin/product_unit/store') !!}");
            $('#submit').text('Save');
            // $('#add_product_unit').modal('show');
        }

        function editProduct_Unit(id) {
            $(`input[name="id"]`).val(id);
            $('#product_unit-edit').attr('action', "{!! url('/admin/product_unit/edit') !!}");
            $.ajax({
                type: 'GET',
                url: '{{ url('/admin/product_unit/edit_data') }}',
                data: {
                    id: id
                },
                success: function(result) {
                    console.log(result);
                    $('input[name^=product_unit_update').val(result['product_unit'])
                    $('#select_pro_unit_lang_update').val(result['lang_id'])
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
                        url: "{!! url('/admin/product_unit/delete/" + id + "') !!}",
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
