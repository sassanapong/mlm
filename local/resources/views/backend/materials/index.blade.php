@extends('layouts.backend.app')

@section('head')
@endsection

@section('css')
    <!-- dropify -->
    <link rel="stylesheet" href="{{ asset('backend/dist/css/dropify.min.css') }}">
    <!-- summernote -->
    <link rel="stylesheet" href="{{ asset('backend/dist/summernote-0.8.18-dist/summernote-lite.min.css') }}">
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
                        data-tw-target="#add_product_materials">
                        เพิ่มวัตถุดิบ
                    </button>
                </div>
            </div>

            <div class="card-block" style="margin-top:10px;">
                <div class="row">
                    <div class="dt-responsive table-responsive">
                        <table id="table_product_materials" style="width:100%;"
                            class="table table-striped table-bordered nowrap">
                            <thead class="thead_txt_center">
                                <tr style="width:100%;">
                                    <th style="width: 10%; text-align:center;">#</th>
                                    <th style="width: 55%; text-align:center;">Material</th>
                                    <th style="width: 55%; text-align:center;">Status</th>
                                    <th style="width: 15%;">Action</th>
                                </tr>
                            </thead>
                            <tbody class="tbody_txt_center">
                                @foreach ($materials as $key => $item)
                                    <tr>

                                        <td class="text-center">{{ $key + 1 }}</td>
                                        <td>{{ $item->materials_name }}</td>

                                        <td style="text-align:center;">

                                            @if ($item->status == 1)
                                                <button class="btn btn-sm btn-warning mr-2 text-success">เปิดการใช้งาน
                                                </button>
                                            @else
                                                <button class="btn btn-sm btn-warning mr-2 text-danger">ปิดการใช้งาน
                                                </button>
                                            @endif

                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-warning mr-2" data-tw-toggle="modal"
                                                data-tw-target="#edit_product_materials"
                                                onclick="edit_product_materials({{ $item->id }})"><i
                                                    class="fa-solid fa-pen-to-square"></i></button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            {{-- END TABLE --}}
        </div>
    </div>


    <!-- BEGIN: Modal Content -->
    <div id="add_product_materials" class="modal" data-tw-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog ">
            <div class="modal-content">
                <form id="add_materials" method="post">

                    <!-- BEGIN: Modal Header -->
                    <div class="modal-header">
                        <h2 class="font-medium text-base mr-auto">เพิ่ม หมวดวัตถุดิบ</h2>
                        <a data-tw-dismiss="modal" href="javascript:;"> <i data-lucide="x"
                                class="w-8 h-8 text-slate-400"></i>
                        </a>
                    </div> <!-- END: Modal Header -->
                    <!-- BEGIN: Modal Body -->

                    <div class="modal-body grid grid-cols-12 gap-4 gap-y-3 bg-slate-100/50">
                        @csrf
                        <div class="col-span-12">
                            <div>
                                <label for="regular-form-1" class="form-label">Materials Name :
                                    <span class="text-danger materials_name_err _err"></span>
                                </label>
                                <input name="materials_name" id="materials_name" type="text" class="form-control">
                            </div>
                        </div>
                        <div class="col-span-6">
                        </div>
                        <div class="col-span-6">
                            <div>
                                <select type="text" class="rounded" name="status" id="status"
                                    style="width:100%; padding: 4px; font-size:14px;">
                                    <option value="" selected>เลือกสถานะ</option>
                                    <option value="0">ปิดการใช้งาน</option>
                                    <option selected value="1">เปิดการใช้งาน</option>
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
                </form>

            </div>
        </div>
    </div> <!-- END: Modal Content -->

    <!-- BEGIN: Modal Content -->
    <div id="edit_product_materials" class="modal" data-tw-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog ">
            <div class="modal-content">
                <form id="edit_materials" method="post">

                    <!-- BEGIN: Modal Header -->
                    <div class="modal-header">
                        <h2 class="font-medium text-base mr-auto">แก้ไข หมวดวัตถุดิบ</h2>
                        <a data-tw-dismiss="modal" href="javascript:;"> <i data-lucide="x"
                                class="w-8 h-8 text-slate-400"></i>
                        </a>
                    </div> <!-- END: Modal Header -->
                    <!-- BEGIN: Modal Body -->

                    <div class="modal-body grid grid-cols-12 gap-4 gap-y-3 bg-slate-100/50">
                        @csrf
                        <div class="col-span-12">
                            <div>
                                <label for="regular-form-1" class="form-label">Materials Name :
                                    <span class="text-danger materials_name_err _err"></span>
                                </label>
                                <input name="materials_name" id="materials_name" type="text" class="form-control">
                                <input type="hidden" class="form-control" id="materials_id" name="materials_id"
                                    value="">
                            </div>
                        </div>
                        <div class="col-span-6">
                        </div>
                        <div class="col-span-6">
                            <div id="selected_status">
                                <select type="text" class="rounded" name="status" id="status"
                                    style="width:100%; padding: 4px; font-size:14px;">
                                    <option value="" selected>เลือกสถานะ</option>
                                    <option value="0">ปิดการใช้งาน</option>
                                    <option selected value="1">เปิดการใช้งาน</option>
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
                </form>

            </div>
        </div>
    </div> <!-- END: Modal Content -->
@endsection

@section('script')
    <!-- dropify -->
    <script src="{{ asset('backend/dist/js/dropify.min.js') }}"></script>
    <!-- summernote -->
    <script src="{{ asset('backend/dist/summernote-0.8.18-dist/summernote-lite.min.js') }}"></script>

    {{-- BEGIN DataTable --}}
    <script>
        $(document).ready(function() {
            $('#table_product_materials').DataTable({});
        });
    </script>



    <script>
        function printErrorMsg(msg) {
            console.log(msg);
            $('._err').text('');
            $.each(msg, function(key, value) {
                $('.' + key + '_err').text(`*${value}*`);
            });
        }
        $('#add_materials').submit(function(e) {
            e.preventDefault();
            const myModal = tailwind.Modal.getInstance(document.querySelector("#add_product_materials"));
            var formData = new FormData($(this)[0]);
            $.ajax({
                url: '{{ route('store_materials') }}',
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,

                success: function(data) {
                    if ($.isEmptyObject(data.error) || data.status == "success") {

                        myModal.hide();
                        Swal.fire({
                            icon: 'success',
                            title: `บันทึกข้อมูลเรียบร้อย`,
                            confirmButtonColor: '#84CC18',
                            confirmButtonText: 'ยืนยัน',
                            timer: 3000,
                        }).then((result) => {
                            if (result.value) {
                                window.location.reload();
                            }
                        });
                    } else {
                        printErrorMsg(data.error);
                    }
                }
            })
        });


        $('#edit_materials').submit(function(e) {
            e.preventDefault();
            const myModal = tailwind.Modal.getInstance(document.querySelector("#edit_product_materials"));
            var formData = new FormData($(this)[0]);
            $.ajax({
                url: '{{ route('update_materials') }}',
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,

                success: function(data) {
                    if ($.isEmptyObject(data.error) || data.status == "success") {

                        myModal.hide();
                        Swal.fire({
                            icon: 'success',
                            title: `บันทึกข้อมูลเรียบร้อย`,
                            confirmButtonColor: '#84CC18',
                            confirmButtonText: 'ยืนยัน',
                            timer: 3000,
                        }).then((result) => {
                            if (result.value) {
                                window.location.reload();
                            }
                        });
                    } else {
                        printErrorMsg(data.error);
                    }
                }
            })
        });


        function edit_product_materials(id) {
            console.log(id);

            $.ajax({
                url: '{{ route('get_materials') }}',
                type: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    id: id
                },
                success: function(data) {

                    console.log(data);

                    $('#edit_product_materials').find('#materials_name').val(data.materials_name);
                    $('#edit_product_materials').find('#materials_id').val(data.id);

                    $('#edit_product_materials').find("#selected_status select").val(data.status);

                }

            })

        }
    </script>
    {{-- BEGIN DataTable --}}
@endsection
