@extends('layouts.backend.app_new')


@section('head')
    <meta charset="UTF-8">
@endsection

@section('css')

@endsection
@section('head_text')
<nav aria-label="breadcrumb" class="-intro-x mr-auto hidden sm:flex">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">ตั่งค่าระบบ</a></li>
        <li class="breadcrumb-item active" aria-current="page">พื้นที่ห่างไกล</li>
    </ol>
</nav>
@endsection
@section('content')

            <div class="intro-y flex items-center mt-8">
                <h2 class="text-lg font-medium mr-auto">
                    พื้นที่ห่างไกล
                </h2>
            </div>


            <div class="intro-y box p-5 mt-5">
                <div class="flex flex-col sm:flex-row sm:items-end xl:items-start mb-2">

                        <div class="col-span-12 sm:col-span-6 mt-6"><button data-tw-toggle="modal"
                                data-tw-target="#large-modal-size-preview" type="button"
                                class="btn btn-primary w-full sm:w-16">เพิ่ม</button>
                        </div>
                        <div id="large-modal-size-preview" class="modal overflow-y-auto show" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <form action="{{ route('add_shipping_location') }}" method="POST">
                                        <div class="modal-body p-10 ">
                                            <div class="intro-y box mt-5 p-5">
                                                <div
                                                    class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                                                    <h2 class="font-medium text-base mr-auto">
                                                        เพิ่มพื้นที่หางไกล
                                                    </h2>
                                                    <div
                                                        class="form-check form-switch w-full sm:w-auto sm:ml-auto mt-3 sm:mt-0">
                                                        {{-- <label class="form-check-label ml-0" for="show-example-2">Show example code</label>
                                                    <input id="show-example-2" data-target="#input-sizing" class="show-code form-check-input mr-0 ml-3" type="checkbox"> --}}
                                                    </div>
                                                </div>

                                                <div class="mt-3">

                                                    @csrf
                                                    <div class="col-md-12 col-xl-12">
                                                        <label for="province" class="form-label"><b>จังหวัด</b></label>
                                                        <label
                                                            class="form-label text-danger same_province_err _err"></label>
                                                        <select class="form-select address_same_card select_same"
                                                            name="same_province" id="same_province" required>
                                                            <option value="">--กรุณาเลือก--</option>
                                                            @foreach ($province as $item)
                                                                <option value="{{ $item->province_id }}">
                                                                    {{ $item->province_name }}</option>
                                                            @endforeach
                                                        </select>


                                                    </div>
                                                    <div class="col-md-12 col-xl-12">

                                                        <label for="district" class="form-label"><b>อำเภอ/เขต</b> </label>
                                                        <label
                                                            class="form-label text-danger same_district_err _err"></label>
                                                        <select class="form-select address_same_card select_same"
                                                            name="same_district" id="same_district" disabled readonly
                                                            required>
                                                            <option value="">--กรุณาเลือก--</option>
                                                        </select>
                                                    </div>
                                                    <div class="text-end mt-2">
                                                        <button type="submit" class="btn btn-primary w-full sm:w-16">เพิ่ม</button>
                                                    </div>
                                                </div>


                                            </div>

                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                </div>
                <div class="overflow-x-auto">
                    <div class="table-responsive">
                        <table id="workL" class="table table-striped table-hover dt-responsive display nowrap"
                            cellspacing="0">
                        </table>

                    </div>
                </div>

                {{-- <table id="workL" class="table table-bordered nowrap mt-2">

                </table> --}}
            </div>

    @endsection

    @section('script')

        <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.3.2/js/dataTables.buttons.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.3.2/js/buttons.html5.min.js"></script>


        <script>
            $('#linkMenuTop .nav-item').eq(1).addClass('active');
        </script>
        <script>
            $('.page-content').css({
                'min-height': $(window).height() - $('.navbar').height()
            });
        </script>


        <script type="text/javascript">
            $(function() {
                table_order = $('#workL').DataTable({
                    // dom: 'Bfrtip',
                    // buttons: ['excel'],
                    searching: false,
                    ordering: false,
                    lengthChange: false,
                    responsive: true,
                    paging: false,
                    processing: true,
                    serverSide: true,
                    "language": {
                        "lengthMenu": "แสดง _MENU_ แถว",
                        "zeroRecords": "ไม่พบข้อมูล",
                        "info": "แสดงหน้า _PAGE_ จาก _PAGES_ หน้า",
                        "search": "ค้นหา",
                        "infoEmpty": "",
                        "infoFiltered": "",
                        "paginate": {
                            "first": "หน้าแรก",
                            "previous": "ย้อนกลับ",
                            "next": "ถัดไป",
                            "last": "หน้าสุดท้าย"
                        },
                        'processing': "กำลังโหลดข้อมูล",
                    },
                    ajax: {
                        url: '{{ route('shipping_location_datable') }}',
                        data: function(d) {
                            d.user_name = $('#user_name').val();
                            d.code_order = $('#code_order').val();
                            d.s_date = $('#s_date').val();
                            d.e_date = $('#e_date').val();


                        },
                    },


                    columns: [{
                            data: "id",
                            title: "ลำดับ",
                            className: "w-5",
                        },
                        {
                            data: "province_id_fk",
                            title: "จังหวัด",
                            className: "w-10 text-center",
                        },
                        {
                            data: "district_id_fk",
                            title: "อำเภอ",
                            className: "w-10 text-center",

                        },


                        {
                            data: "action",
                            title: "Action",
                            className: "w-10 text-center",

                        },



                    ],



                });
                $('#search-form').on('click', function(e) {
                    table_order.draw();
                    e.preventDefault();
                });

            });

            $("#same_province").change(function() {
                let province_id = $(this).val();
                $.ajax({
                    url: '{{ route('admin_getDistrict') }}',
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        province_id: province_id,
                    },
                    success: function(data) {
                        $("#same_district").children().remove();
                        $("#same_tambon").children().remove();
                        $("#same_district").append(` <option value="">--กรุณาเลือก--</option>`);
                        $("#same_tambon").append(` <option value="">--กรุณาเลือก--</option>`);
                        $("#same_zipcode").val("");
                        data.forEach((item) => {
                            $("#same_district").append(
                                `<option value="${item.district_id}">${item.district_name}</option>`
                            );
                        });
                        $("#same_district").attr('disabled', false);
                        $("#same_tambon").attr('disabled', true);

                    },
                    error: function() {}
                })
            });
        </script>
    @endsection
