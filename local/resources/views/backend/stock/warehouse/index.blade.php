@extends('layouts.backend.app')



@section('head')
    {{-- select2 --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('css')
@endsection

@section('conten')
    @include('backend.navbar.navbar_mobile')
    <div class="flex overflow-hidden">

        @include('backend.navbar.navbar')
        <div class="content">
            @include('backend.navbar.top_bar')
            <h2 class="text-lg font-medium mr-auto mt-2">คลัง</h2>


            <div class="grid grid-cols-12">
                <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-4">
                    <div class="">
                        <button class="btn btn-primary shadow-md mr-2" data-tw-toggle="modal" data-tw-target="#add_warehoues"
                            onclick="resetForm()">เพิ่มคลัง</button>
                    </div>
                    <div class="">
                        <div class="form-inline ">
                            <label for="" class="mr-2 text-slate-500 ">สถานะ : </label>
                            <select name="status" class="w-32 form-select box mt-3 sm:mt-0 myWhere ">
                                <option value="">ทั้งหมด</option>
                                <option value="1">เปิดใช้งาน</option>
                                <option value="99">ไม่เปิดการใช้งาน</option>
                            </select>
                        </div>
                    </div>
                    <div class="hidden md:block mx-auto text-slate-500"></div>
                    <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">

                        <div class=" relative text-slate-500">
                            <div class="form-inline">
                                <label for="" class="mr-2">ค้นหารหัสคลัง</label>
                                <input type="text" name="b_code" class="form-control w-56 box pr-10 myLike "
                                    placeholder="ค้นหา...">
                                <i class="w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0" data-lucide="search"></i>
                            </div>
                        </div>

                    </div>

                </div>

                <div class="col-span-12">
                    <table id="table_warehouse" class="table table-report">
                    </table>
                </div>
            </div>

        </div>

    </div>


    <!-- BEGIN: Modal add_branch -->
    <div id="add_warehoues" class="modal" data-tw-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="form_warehoues" method="post">
                    @csrf
                    <!-- BEGIN: Modal Header -->
                    <div class="modal-header">
                        <h2 class="font-medium text-base mr-auto">เพิ่มสาขา</h2>
                        <a data-tw-dismiss="modal" href="javascript:;"> <i data-lucide="x"
                                class="w-8 h-8 text-slate-400"></i>
                        </a>
                    </div> <!-- END: Modal Header -->
                    <!-- BEGIN: Modal Body -->
                    <div class="modal-body grid grid-cols-12 gap-4 gap-y-3 bg-slate-100/50">


                        <div class="col-span-12">
                            <label for="regular-form-1" class="form-label">ชื่อสาขา</label>
                            <span class="form-label text-danger branch_id_fk_err _err"></span>
                            <br>
                            <select id="select_branch" class="js-example-basic-single form-select w-full"
                                name="branch_id_fk">
                                @foreach ($branch as $key => $val)
                                    <option value="{{ $val->id }}">{{ $val->b_code }} : {{ $val->b_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-span-12">
                            <label for="regular-form-1" class="form-label">รหัสคลัง</label>
                            <span class="form-label text-danger w_code_err _err"></span>
                            <input id="regular-form-1" type="text" class="form-control" name="w_code"
                                placeholder="ชื่อคลัง">
                        </div>
                        <div class="col-span-12">
                            <label for="regular-form-1" class="form-label">ชื่อคลัง</label>
                            <span class="form-label text-danger w_name_err _err"></span>
                            <input id="regular-form-1" type="text" class="form-control" name="w_name"
                                placeholder="ชื่อคลัง">
                        </div>

                        <div class="col-span-12 mx-auto">
                            <label for="regular-form-1" class="form-label">รายละเอียด</label>
                            <span class="form-label text-danger w_details_err _err"></span>
                            <textarea class="form-control  p-2" name="w_details" id="" cols="150" rows="5"
                                placeholder="รายละเอียด..."></textarea>
                        </div>

                        <div class="col-span-12 mt-2">
                            <label>สถานะ ปิด/เปิด</label>
                            <div class="form-switch mt-2">
                                <input type="checkbox" name="status" class="form-check-input" value="1">
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
    </div> <!-- END: Modal add_branch -->
@endsection




@section('script')
    {{-- select2 --}}
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


    <script>
        // In your Javascript (external .js resource or <script> tag)
        $(document).ready(function() {
            $('#select_branch').select2({
                dropdownParent: $('#add_warehoues')
            });
        });
    </script>





    {{-- BEGIN print err input --}}
    <script>
        function printErrorMsg(msg) {

            $('._err').text('');
            $.each(msg, function(key, value) {
                $('.' + key + '_err').text(`*${value}*`);
            });
        }

        function resetForm() {
            $('#form_warehoues')[0].reset();
            $('._err').text('');
        }
    </script>
    {{-- END print err input --}}

    {{-- //BEGIN form_warehoues --}}
    <script>
        $('#form_warehoues').submit(function(e) {
            const myModal = tailwind.Modal.getInstance(document.querySelector("#add_warehouse"));
            e.preventDefault();
            var formData = new FormData($(this)[0]);
            $.ajax({
                url: '{{ route('store_warehoues') }}',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(data) {
                    if ($.isEmptyObject(data.error) || data.status == "success") {
                        myModal.hide();
                        Swal.fire({
                            icon: 'success',
                            title: 'บันทึกสำเร็จ',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'ปิด',

                        }).then((result) => {
                            table_branch.draw();
                        })
                    } else {
                        printErrorMsg(data.error);
                    }
                }
            });
        });
    </script>
    {{-- //END form_warehoues --}}


    {{-- BEGIN data_table_branch --}}
    @include('backend.stock.warehouse.data_table_warehouse')
    {{-- END data_table_branch --}}
@endsection
