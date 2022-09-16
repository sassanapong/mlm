@extends('layouts.backend.app')



@section('head')
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



            <div class="box p-2">
                <form id="form_warehoues" method="post">
                    @csrf
                    <input type="hidden" name="branch_id_fk" value="{{ $branch[0]['id'] }}">
                    <div class="grid grid-cols-12">

                        <div class="col-span-12 ">
                            <label for="regular-form-1" class="form-label">สาขา</label>
                            <span class="form-label text-danger w_code_err _err"></span>
                            <input id="regular-form-1" type="text"
                                value="{{ $branch[0]['b_code'] }} : {{ $branch[0]['b_name'] }}" class="form-control"
                                name="w_code" readonly>
                        </div>
                        <div class="col-span-12 ">
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

                        <div class="col-span-12 ">
                            <label for="regular-form-1" class="form-label">รายละเอียด</label>
                            <span class="form-label text-danger w_details_err _err"></span>
                            <textarea class="form-control  p-2" name="w_details" id="" cols="150" rows="5"
                                placeholder="รายละเอียด..."></textarea>
                        </div>

                        <div class="col-span-12">
                            <label>สถานะ ปิด/เปิด</label>
                            <div class="form-switch mt-2">
                                <input type="checkbox" name="status" class="form-check-input" value="1">
                            </div>
                        </div>
                        <div class="col-span-12 mt-2 flex justify-between ">
                            <a href="{{ route('branch') }}" class=" btn btn-sm btn-outline-warning">ย้อนกลับ</a>
                            <button type="submit" class=" btn btn-sm btn-primary">บันทึก</button>
                        </div>

                    </div>
                </form>
            </div>

            <div class="grid grid-cols-12">
                <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-4">

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
@endsection




@section('script')
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
                        resetForm();
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
