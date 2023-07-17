     <title>บริษัท มารวยด้วยกัน จำกัด</title>


     @extends('layouts.frontend.app')
     @section('css')
         <link href='https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css' rel='stylesheet'>
     @endsection

     @section('conten')
         <div class="bg-whiteLight page-content">
             <div class="container-fluid">
                 <div class="row">
                     <div class="col-lg-12">
                         <nav aria-label="breadcrumb">
                             <ol class="breadcrumb">
                                 <li class="breadcrumb-item"><a href="{{ route('home') }}">หน้าแรก</a></li>
                                 <li class="breadcrumb-item active text-truncate" aria-current="page">สายงาน</li>
                             </ol>
                         </nav>
                     </div>
                 </div>

                 <div class="card card-box borderR10 mb-2 mb-md-0">
                    <div class="card-body">

                        <div class=" table-responsive">

                            <table id="workL" class="table table-bordered nowrap">


                            </table>
                        </div>
                    </div>
                </div>

             </div>
         </div>

     @endsection

     @section('script')

         <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
         <script>
             $('.page-content').css({
                 'min-height': $(window).height() - $('.navbar').height()
             });
         </script>
         <script>
             $(document).ready(function() {
            $(function() {
                 oTable = $('#workL').DataTable({
                     processing: true,
                     serverSide: true,
                     searching: true,
                     pageLength: 25,
                     ajax: {
                         url: '{{ route('eWallet_TranferHistory_table') }}',
                         data: function(d) {

                             d.startDate = $('#startDate').val();
                             d.endDate = $('#endDate').val();
                         },
                         method: 'get'
                     },


                     columns:  [
                        {
                            data: "created_at",
                            title: "วันที่ทำรายการ",
                            className: "w-10 text-center whitespace-nowrap",
                        },
                        {
                            data: "transaction_code",
                            title: "รหัสรายการ",
                            className: "w-10 ",
                        },
                        {
                            data: "date_mark",
                            title: "วันที่อนุมัติ",
                            className: "w-10 text-center whitespace-nowrap",
                        },
                        {
                            data: "customer_username",
                            title: "ชื่อสมาชิก",
                            className: "w-24 whitespace-nowrap text-center",
                        },
                        // {
                        //     data: "bonus_full",
                        //     title: "ยอดที่ได้รับ",
                        //     className: "w-10 text-end",
                        // },

                        {
                            data: "amt",
                            title: "สุทธิ",
                            className: "w-10 text-end",
                        },


                        // {
                        //     data: "balance",
                        //     title: "eWallet คงเหลือ",
                        //     className: "w-12 text-end",
                        // },

                        // {
                        //     data: "customers_name_receive",
                        //     title: "ชื่อผู้รับ",
                        //     className: "w-12 text-center",
                        // },
                        {
                            data: "note_orther",
                            title: "รายละเอียด",
                            className: "w-10 text-center",
                        },
                        {
                            data: "type",
                            title: "ประเภท",
                            className: "w-10 text-center",
                        },
                        {
                            data: "status",
                            title: "สถานะ",
                            className: "w-10 text-center whitespace-nowrap",
                        },

                    ],
                 });
                 $('.myWhere,.myLike,.myCustom,#onlyTrashed').on('change', function(e) {
                     oTable.draw();
                 });

                 $('#search-form').on('click', function(e) {
                     oTable.draw();
                     e.preventDefault();
                 });
             });

            });

         </script>
     @endsection
