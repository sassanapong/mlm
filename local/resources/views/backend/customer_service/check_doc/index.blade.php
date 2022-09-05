@extends('layouts.backend.app')


@section('head')
@endsection

@section('css')
    <style>
        .icon_size {
            font-size: 28px;
        }
    </style>
@endsection

@section('conten')
    @include('backend.navbar.navbar_mobile')
    <div class="flex overflow-hidden">

        @include('backend.navbar.navbar')
        <div class="content">
            @include('backend.navbar.top_bar')


            <div class="grid grid-cols-12 gap-6 mt-5">
                <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
                    <div class="">
                        <div class="form-inline ">
                            <label for="" class="mr-2 text-slate-500 ">สถานะ : </label>
                            <select class="w-32 form-select box mt-3 sm:mt-0">
                                <option>ทั้งหมด</option>
                                <option>ผ่าน</option>
                                <option>ไม่ผ่าน</option>
                            </select>
                        </div>
                    </div>
                    <div class="hidden md:block mx-auto text-slate-500"></div>
                    <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">

                        <div class=" relative text-slate-500">
                            <div class="form-inline">
                                <label for="" class="mr-2">ค้นหาจากรหัสผู้ใช้</label>
                                <input type="text" class="form-control w-56 box pr-10" placeholder="Search...">
                                <i class="w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0" data-lucide="search"></i>
                            </div>
                        </div>

                    </div>

                </div>
                <!-- BEGIN: Data List -->
                <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
                    <table class="table table-report -mt-2">

                        <thead>
                            <tr>
                                <th class="whitespace-nowrap">ลำดับ</th>
                                <th class="whitespace-nowrap">รหัส</th>
                                <th class="whitespace-nowrap">ชื่อ - สกุล</th>
                                <th class="whitespace-nowrap">คะแนนสะสม</th>
                                <th class="whitespace-nowrap">รหัสผู้แนะนำ</th>
                                <th class="text-center whitespace-nowrap">เอกสาร</th>
                                <th class="text-center whitespace-nowrap">ผู้ตรวจสอบ</th>
                                <th class="text-center whitespace-nowrap">สถานะ</th>
                                <th class="text-center whitespace-nowrap"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="intro-x h-20">
                                <td class="table-report__action w-10 text-center ">
                                    <p>1</p>
                                </td>
                                <td class="table-report__action w-24">
                                    <p>MDK-01</p>
                                </td>
                                <td class="table-report__action">
                                    <a href="" class="font-medium whitespace-nowrap">Rania Barnes</a>
                                    <div class="text-slate-500 text-xs whitespace-nowrap mt-0.5">0987654321</div>
                                </td>
                                <td class="table-report__action w-24">186 แต้ม</td>
                                <td class="table-report__action w-24">MDK-0092</td>
                                <td class="table-report__action w-40 ">
                                    <div class="flex items-center justify-center ">
                                        <a href="#" data-tw-toggle="modal" data-tw-target="#info_card"> <i
                                                class="fa-regular fa-address-card icon_size mr-3 tooltip text-success"
                                                title="ข้อมูลบัตรประชาชน"></i></a>
                                        <a href="#" data-tw-toggle="modal" data-tw-target="#info_card">
                                            <i class="fa-solid fa-money-check-dollar icon_size mr-3 tooltip text-success"
                                                title="ข้อมูลธนาคาร"></i>
                                        </a>
                                    </div>
                                </td>
                                <td class="table-report__action  w-24">
                                    <p>Myron Goddard </p>
                                    <div class="text-slate-500 text-xs whitespace-nowrap mt-0.5">01/01/2022 | 13.00 น.</div>
                                </td>
                                <td class="table-report__action w-32 text-center">
                                    <p class="text-success">ผ่าน</p>
                                </td>
                                <td class="table-report__action w-32 text-center ">
                                    <a href="{{ route('info_customer') }}" class="btn btn-sm btn-warning mr-2 "><i
                                            class="fa-solid fa-pen-to-square"></i></a>
                                    <a class="btn btn-sm btn-primary "><i class="fa-solid fa-right-to-bracket"></i></a>
                                </td>

                            </tr>
                            <tr class="intro-x h-20">
                                <td class="table-report__action w-10 text-center ">
                                    <p>2</p>
                                </td>
                                <td class="table-report__action w-24">
                                    <p>MDK-02</p>
                                </td>
                                <td class="table-report__action">
                                    <a href="" class="font-medium whitespace-nowrap">Rania Barnes</a>
                                    <div class="text-slate-500 text-xs whitespace-nowrap mt-0.5">0987654321</div>
                                </td>
                                <td class="table-report__action w-24">186 แต้ม</td>
                                <td class="table-report__action w-24">MDK-0092</td>
                                <td class="table-report__action w-40 ">
                                    <div class="flex items-center justify-center">
                                        <a href="#" data-tw-toggle="modal" data-tw-target="#info_card"> <i
                                                class="fa-regular fa-address-card icon_size mr-3 tooltip  text-success"
                                                title="ข้อมูลบัตรประชาชน"></i></a>
                                        <a href="#" data-tw-toggle="modal" data-tw-target="#info_card">
                                            <i class="fa-solid fa-money-check-dollar icon_size mr-3 tooltip text-warning"
                                                title="ข้อมูลธนาคาร"></i>
                                        </a>
                                    </div>
                                </td>
                                <td class="table-report__action  w-24">

                                </td>
                                <td class="table-report__action w-32   text-center">
                                    <p class="text-warning">รอตรวจสอบ</p>
                                </td>
                                <td class="table-report__action w-32 text-center ">
                                    <a class="btn btn-sm btn-warning mr-2 "><i class="fa-solid fa-pen-to-square"></i></a>
                                    <a class="btn btn-sm btn-primary "><i class="fa-solid fa-right-to-bracket"></i></a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- END: Data List -->
                <!-- BEGIN: Pagination -->
                <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center text-rigth">
                    <nav class="w-full sm:w-auto sm:mr-auto ">
                        <ul class="pagination">
                            <li class="page-item">
                                <a class="page-link" href="#"> <i class="w-4 h-4" data-lucide="chevrons-left"></i>
                                </a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="#"> <i class="w-4 h-4" data-lucide="chevron-left"></i>
                                </a>
                            </li>
                            <li class="page-item"> <a class="page-link" href="#">...</a> </li>
                            <li class="page-item"> <a class="page-link" href="#">1</a> </li>
                            <li class="page-item active"> <a class="page-link" href="#">2</a> </li>
                            <li class="page-item"> <a class="page-link" href="#">3</a> </li>
                            <li class="page-item"> <a class="page-link" href="#">...</a> </li>
                            <li class="page-item">
                                <a class="page-link" href="#"> <i class="w-4 h-4" data-lucide="chevron-right"></i>
                                </a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="#"> <i class="w-4 h-4"
                                        data-lucide="chevrons-right"></i>
                                </a>
                            </li>
                        </ul>
                    </nav>
                    <div class="form-inline">
                        <label for="" class="mr-2">แสดง</label>
                        <select class="w-20 form-select box mt-3 sm:mt-0">
                            <option>10</option>
                            <option>25</option>
                            <option>35</option>
                            <option>50</option>
                        </select>
                        <label for="" class="ml-2">แถว</label>
                    </div>
                </div>
                <!-- END: Pagination -->
            </div>

        </div>
    </div>




    <!-- BEGIN: Modal Content -->
    <div id="info_card" class="modal" data-tw-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <!-- BEGIN: Modal Header -->
                <div class="modal-header">
                    <h2 class="font-medium text-base mr-auto">ตรวจสอบเอกสาร</h2>
                    <a data-tw-dismiss="modal" href="javascript:;"> <i data-lucide="x"
                            class="w-8 h-8 text-slate-400"></i> </a>
                </div> <!-- END: Modal Header -->
                <!-- BEGIN: Modal Body -->
                <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                    <div class="col-span-4 my-auto">
                        <img src="https://via.placeholder.com/300x300.png?text=card" alt="">
                    </div>
                    <div class="col-span-8 ">
                        <div class="grid grid-cols-12 gap-3  mx-auto">
                            <div class="col-span-12">
                                <div> <label for="regular-form-1" class="form-label">ที่อยู่</label> <input
                                        id="regular-form-1" type="text" class="form-control" value="Test" readonly>
                                </div>
                            </div>
                            <div class="col-span-4">
                                <div> <label for="regular-form-1" class="form-label">หมู่</label> <input
                                        id="regular-form-1" type="text" class="form-control" value="Test" readonly>
                                </div>
                            </div>
                            <div class="col-span-4">
                                <div> <label for="regular-form-1" class="form-label">ซอย</label> <input
                                        id="regular-form-1" type="text" class="form-control" value="Test" readonly>
                                </div>
                            </div>
                            <div class="col-span-4">
                                <div> <label for="regular-form-1" class="form-label">ถนน</label> <input
                                        id="regular-form-1" type="text" class="form-control" value="Test" readonly>
                                </div>
                            </div>
                            <div class="col-span-4">
                                <div> <label for="regular-form-1" class="form-label">จังหวัด</label> <input
                                        id="regular-form-1" type="text" class="form-control" value="Test" readonly>
                                </div>
                            </div>
                            <div class="col-span-4">
                                <div> <label for="regular-form-1" class="form-label">อำเภอ/เขต</label> <input
                                        id="regular-form-1" type="text" class="form-control" value="Test" readonly>
                                </div>
                            </div>
                            <div class="col-span-4">
                                <div> <label for="regular-form-1" class="form-label">ตำบล</label> <input
                                        id="regular-form-1" type="text" class="form-control" value="Test" readonly>
                                </div>
                            </div>
                            <div class="col-span-4">
                                <div> <label for="regular-form-1" class="form-label">รหัสไปรษณีย์</label> <input
                                        id="regular-form-1" type="text" class="form-control" value="54313" readonly>
                                </div>
                            </div>
                            <div class="col-span-4">
                                <div> <label for="regular-form-1" class="form-label">เบอร์มือถือ</label> <input
                                        id="phone" type="text" class="form-control" value="0987654321" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- END: Modal Body -->
                <!-- BEGIN: Modal Footer -->
                <div class="modal-footer"> <button type="button" data-tw-dismiss="modal"
                        class="btn btn-outline-danger w-20 mr-1">ไม่ผ่าน</button>
                    <button type="button" class="btn btn-outline-success  w-20">ผ่าน</button>
                </div> <!-- END: Modal Footer -->
            </div>
        </div>
    </div> <!-- END: Modal Content -->
@endsection



@section('script')
@endsection
