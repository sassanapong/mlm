@extends('layouts.backend.app')


@section('css')
@endsection

@section('conten')
    @include('backend.navbar.navbar_mobile')
    <div class="flex overflow-hidden">

        @include('backend.navbar.navbar')
        <div class="content">
            @include('backend.navbar.top_bar')

            <h2 class="intro-y text-lg font-medium mt-5 mb-5">
                ข้อมูลสมาชิก
            </h2>
            <hr>
            {{-- BRGIN  ผู้แนะนำ --}}
            <div class="grid grid-cols-12 gap-4 mt-5">
                <div class="col-span-12 bg-green-700/75 rounded-full">
                    <h2 class="intro-y text-lg font-medium text-black ml-5">
                        ผู้แนะนำ
                    </h2>
                </div>
                <div class="col-span-4">
                    <label for="regular-form-1" class="form-label">รหัสผู้แนะนำ</label>
                    <div class="form-inline">

                        <input id="regular-form-1" type="text" class="form-control">
                        <button class="btn bg-green-700 text-white ml-3 mr-1 rounded-full">ตรวจ</button>
                        <button class="btn btn-outline-danger rounded-full  ">
                            <p class="my-auto"> <i class="fa-solid fa-xmark w-4 h-4"></i> </p>
                        </button>
                    </div>
                </div>
                <div class="col-span-6">
                    <label for="regular-form-1" class="form-label">ชื่อผู้แนะนำ</label>
                    <div class="form-inline">
                        <input id="regular-form-1" type="text" class="form-control">
                    </div>
                </div>
            </div>
            {{-- END  ผู้แนะนำ --}}


            {{-- BEGIN ข้อมูลส่วนตัว --}}
            <div class="grid grid-cols-12 gap-4 mt-5">

                <div class="col-span-3">
                    <label for="regular-form-1" class="form-label">คำนำหน้า <span class="text-danger">*</span> </label>

                    <input id="regular-form-1" type="text" class="form-control">

                </div>
                <div class="col-span-3">
                    <label for="regular-form-1" class="form-label">ชื่อ <span class="text-danger">*</span> </label>

                    <input id="regular-form-1" type="text" class="form-control">

                </div>
                <div class="col-span-3">
                    <label for="regular-form-1" class="form-label">นามสกุล <span class="text-danger">*</span> </label>
                    <input id="regular-form-1" type="text" class="form-control">
                </div>
                <div class="col-span-3">
                    <label for="regular-form-1" class="form-label">เพศ <span class="text-danger">*</span> </label>
                    <select class="form-select " aria-label="Default select example">
                        <option>เลือกเพศ</option>
                        <option>ชาย</option>
                        <option>หญิง</option>
                        <option>ไม่ระบุ</option>
                    </select>
                </div>
                <div class="col-span-6">
                    <label for="regular-form-1" class="form-label">ชื่อทางธุรกิจ <span class="text-danger">*</span> </label>
                    <input id="regular-form-1" type="text" class="form-control">
                </div>
                <div class="col-span-2">
                    <label for="" class="form-label">วันเกิด <span class="text-danger">*</span> </label>
                    <select class="form-select " aria-label="Default select example">
                        <option selected disabled>วัน</option>

                        <option value="">1</option>
                        <option value="">2</option>
                        <option value="">3</option>

                    </select>
                </div>
                <div class="col-span-2">
                    <label for="" class="form-label"></label>
                    <select class="form-select mt-[7px]" aria-label="Default select example">
                        <option selected disabled>เดือน</option>
                        <option value="01">มกราคม</option>
                        <option value="02">กุมภาพันธ์</option>
                        <option value="03">มีนาคม</option>
                        <option value="04">เมษายน</option>
                        <option value="05">พฤษภาคม</option>
                        <option value="06">มิถุนายน</option>
                        <option value="07">กรกฎาคม</option>
                        <option value="08">สิงหาคม</option>
                        <option value="09">กันยายน</option>
                        <option value="10">ตุลาคม</option>
                        <option value="11">พฤศจิกายน</option>
                        <option value="12">ธันวาคม</option>
                    </select>
                </div>
                <div class="col-span-2">
                    <label for="" class="form-label"></label>
                    <select class="form-select mt-[7px]" aria-label="Default select example">
                        <option value="">2545</option>
                        <option value="">2546</option>
                        <option value="">2547</option>
                    </select>
                </div>
                <div class="col-span-2">
                    <label for="regular-form-1" class="form-label">สัญชาติ <span class="text-danger">*</span> </label>
                    <select class="form-select " aria-label="Default select example">
                        <option value="">เลือกสัญชาติ</option>
                        <option value="">ไทย</option>
                    </select>
                </div>
                <div class="col-span-5">
                    <label for="regular-form-1" class="form-label">เลขบัตรประชาชน <span class="text-danger">*</span>
                    </label>
                    <input id="regular-form-1" type="text" class="form-control">
                </div>
                <div class="col-span-5">
                    <label for="regular-form-1" class="form-label">โทรศัพท์ <span class="text-danger">*</span>
                    </label>
                    <input id="regular-form-1" type="text" class="form-control">
                </div>
                <div class="col-span-4">
                    <label for="regular-form-1" class="form-label">E-mail
                    </label>
                    <input id="regular-form-1" type="text" class="form-control">
                </div>
                <div class="col-span-4">
                    <label for="regular-form-1" class="form-label">
                        Line ID
                    </label>
                    <input id="regular-form-1" type="text" class="form-control">
                </div>
                <div class="col-span-4">
                    <label for="regular-form-1" class="form-label">
                        Fackbook
                    </label>
                    <input id="regular-form-1" type="text" class="form-control">
                </div>

            </div>
            {{-- BEGIN ข้อมูลส่วนตัว --}}
        </div>
    </div>
@endsection



@section('script')
@endsection
