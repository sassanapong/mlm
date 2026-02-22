@extends('layouts.backend.app_new')

            @section('head')
                <meta charset="UTF-8">
            @endsection

            @section('css')

            @endsection

            @section('content')

                        <div class="intro-y flex items-center mt-8">
                            <h2 class="text-lg font-medium mr-auto">
                                ReCashback Report
                            </h2>
                        </div>


                        <div class="intro-y box p-5 mt-5">
                            <div class="flex flex-col sm:flex-row sm:items-end xl:items-start mb-2">
                                <form id="tabulator-html-filter-form" class="xl:flex sm:mr-auto">

                                    <div class="sm:flex items-center sm:mr-4">
                                        <div class="col-span-12 sm:col-span-6">
                                            <label for="user_name" class="form-label">รหัสสมาชิก</label>
                                            <input type="text" id="user_name"
                                                class="form-control sm:w-40 2xl:w-full mt-2 sm:mt-0"
                                                placeholder="รหัสสมาชิก">
                                        </div>
                                    </div>


                                    <div class="sm:flex items-center sm:mr-4">
                                        <div class="col-span-12 sm:col-span-6">
                                            <label for="start_date" class="form-label">วันที่</label>
                                            <input type="date" id="start_date"
                                                class="form-control sm:w-40 2xl:w-full mt-2 sm:mt-0">
                                        </div>
                                   
                                    </div>


                                    <div class="mt-2 xl:mt-0">
                                        <div class="col-span-12 sm:col-span-6 mt-6">
                                            <button id="search-form" type="button"
                                                class="btn btn-primary w-full sm:w-16">ค้นหา</button>
                                        </div>
                                        {{-- <button id="tabulator-html-filter-reset" type="button"
                                            class="btn btn-secondary w-full sm:w-16 mt-2 sm:mt-0 sm:ml-1">Reset</button> --}}
                                    </div>
                                </form>

                            </div>
                            <div class="overflow-x-auto">
                                <div class="table-responsive">
                                    <table id="workL"
                                        class="table table-striped table-hover dt-responsive display nowrap"
                                        cellspacing="0">


                                    </table>

                                </div>
                            </div>
                        </div>

                            {{-- <table id="workL" class="table table-bordered nowrap mt-2">

                            </table> --}}

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
                        function numberWithCommas(x) {
                            // x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                            rs = parseFloat(x).toFixed(2);
                            return rs;

                        }
                        $(function() {
                            table_order = $('#workL').DataTable({
                                dom: 'Bfrtip',
                                buttons: ['excel'],
                                searching: false,
                                ordering: true,
                                lengthChange: false,
                                responsive: true,
                                paging: false,
                                pageLength: 100,
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
                                    url: '{{ route('cashback_report_datableold') }}',
                                    data: function(d) {
                                        d.user_name = $('#user_name').val();
                                        d.s_date = $('#s_date').val();
                                        // d.e_date = $('#e_date').val();

                                    },
                                },


                                columns: [
                                       {
                            data: "created_at",
                            title: "วันที่ทำรายการ",
                            className: "w-10",
                        },

                        {
                            data: "code",
                            title: "code",
                            className: "w-10",
                        },
                        {
                            data: "code_bonus",
                            title: "code_bonus",
                            className: "w-10",
                        },
                        {
                            data: "user_name",
                            title: "รหัสสมาชิกผู้ทำรายการ",
                            className: "w-10",
                        },

                        {
                            data: "name",
                            title: "ชื่อผู้ทำรายการ",
                            className: "w-10",
                        },


                        // {
                        //     data: "introduce_id",
                        //     title: "รหัสผู้แนะนำ",
                        //     className: "w-10",
                        // },




                        {
                            data: "user_name_g",
                            title: "รหัสที่ได้รับโบนัส",
                            className: "w-10",
                        },
                        {
                            data: "name_g",
                            title: "ชื่อผู้ได้รับโบนัส",
                            className: "w-10",
                        },


                        {
                            data: "qualification",
                            title: "ตำแหน่ง",
                            className: "w-10",

                        },

                        {
                            data: "g",
                            title: "ชั้น",
                            className: "w-10",

                        },


                        {
                            data: "percen",
                            title: "(%)",
                            className: "w-10",

                        },
                        {
                            data: "pv",
                            title: "PV",
                            className: "w-10",

                        },

                        {
                            data: "bonus",
                            title: "bonus",
                            className: "w-10",

                        },

                        {
                            data: "status",
                            title: "status",
                            className: "w-10",

                        },

                                    
                                ],



                            });
                            $('#search-form').on('click', function(e) {
                                table_order.draw();
                                e.preventDefault();
                            });

                        });
                    </script>
                @endsection
