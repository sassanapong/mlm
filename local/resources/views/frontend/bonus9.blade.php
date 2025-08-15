<title>บริษัท มารวยด้วยกัน จำกัด</title>
@section('css')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

@endsection
@extends('layouts.frontend.app')
@section('conten')
    <div class="bg-whiteLight page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">หน้าแรก</a></li>
                            <li class="breadcrumb-item active text-truncate" aria-current="page"> โบนัส Matching </li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-box borderR10 mb-3">
                        <div class="card-body">
                            <h4 class="card-title">ค้นหา</h4>
                            <hr>

                            <div class="row g-3">
                                <div class="col-md-6 col-lg-2">
                                    <label for="" class="form-label">วันที่</label>
                                    <input type="date" class="form-control"
                                        value="{{ date('Y-m-d', strtotime('-1 day')) }}" id="startDate">
                                </div>
                                {{-- <div class="col-md-6 col-lg-2">
                                    <label for="" class="form-label">วันที่สิ้นสุด</label>
                                    <input type="date" class="form-control" value="{{date('Y-m-d')}}"  id="endDate">
                                </div>
                                  --}}

                                {{-- <div class="col-md-10 col-lg-3">
                                    <label for="" class="form-label"> ค้นหา</label>
                                    <input type="text" class="form-control">
                                </div> --}}
                                <div class="col-md-2 col-lg-1">
                                    <label for="" class="form-label d-none d-md-block">&nbsp;</label>
                                    <button type="button" id="search-form"
                                        class="btn btn-dark rounded-circle btn-icon"><i
                                            class="bx bx-search"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card card-box borderR10 mb-2 mb-md-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    <h4 class="card-title mb-0">  Matching</h4>
                                </div>
                                {{-- <div class="col-sm-6 text-md-end">
                                    <button type="button" class="btn btn-info rounded-pill"><i
                                            class='bx bxs-file me-1'></i> ออกรายงาน</button>
                                </div> --}}
                            </div>
                            <hr>
                            <table id="report" class="table table-bordered nowrap">
                                <tfoot>
                                    <tr>

                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                      
                                        <td style="text-align: end;">รวม</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>

                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $('.page-content').css({
            'min-height': $(window).height() - $('.navbar').height()
        });
    </script>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>

    <script src="https://cdn.datatables.net/buttons/2.3.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.2/js/buttons.html5.min.js"></script>

    <script>
        $(document).ready(function() {
            $(function() {
                oTable = $('#report').DataTable({
                    buttons: ['excel'],
                       processing: true,
                       serverSide: true,
                       searching: true,
                       //    pageLength: 25,
                       paging: false,
                    ajax: {
                        url: '{{ route('bonus99_datatable') }}',
                        data: function(d) {

                            d.startDate = $('#startDate').val();
                            d.endDate = $('#endDate').val();
                        },
                        method: 'get'
                    },


                    columns: [{
                            data: 'date_action',
                            title: '<center>วันที่</center> ',
                            className: 'text-center'
                        },



                        {
                            data: "user_name",
                            title: "รหัส",
                            className: "w-5",
                        },
                        {
                            data: "qualification_id",
                            title: "ตำแหน่ง",
                            className: "w-5",
                        },
                        {
                            data: "introduce_id",
                            title: "ผู้แนะนำ",
                            className: "w-5",
                        },


                
                        {
                            data: "g",
                            title: "ช้น",
                            className: "w-5 text-end",
                        },

                        {
                            data: "percen",
                            title: "(%)",
                            className: "w-5 text-end",
                        },



                        {
                            data: "bonus_full",
                            title: "ยอดได้รับ",
                            className: "w-5 text-end",
                        },

                        {
                            data: "tax_total",
                            title: "ภาษี 3%",
                            className: "w-5 text-end",
                        },

                        {
                            data: "bonus",
                            title: "สุทธิ",
                            className: "w-5 text-end",
                        },

                        {
                            data: "status",
                            title: "สถานะการจ่าย",
                            className: "w-5 text-end",
                        },

                    ],
                    "footerCallback": function(row, data, start, end, display) {
                           var api = this.api(),
                               data;

                           // Remove the formatting to get integer data for summation
                           var intVal = function(i) {
                               return typeof i === 'string' ?
                                   i.replace(/[\$,]/g, '') * 1 :
                                   typeof i === 'number' ?
                                   i : 0;
                           };

                           full = api
                               .column(6, {
                                   page: 'current'
                               })
                               .data()
                               .reduce(function(a, b) {
                                   return intVal(a) + intVal(b);
                               }, 0);
                           tax = api
                               .column(7, {
                                   page: 'current'
                               })
                               .data()
                               .reduce(function(a, b) {
                                   return intVal(a) + intVal(b);
                               }, 0);

                           total = api
                               .column(8, {
                                   page: 'current'
                               })
                               .data()
                               .reduce(function(a, b) {
                                   return intVal(a) + intVal(b);
                               }, 0); 
                           // Update footer

                           $(api.column(6).footer()).html(full.toFixed(2));
                           $(api.column(7).footer()).html(tax.toFixed(2));
                           $(api.column(8).footer()).html(total.toFixed(2));

                       }
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
