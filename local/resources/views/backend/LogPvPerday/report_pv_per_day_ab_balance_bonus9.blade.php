@extends('layouts.backend.app_new')
@section('head')
@endsection

@section('head_text')
    <nav aria-label="breadcrumb" class="-intro-x mr-auto hidden sm:flex">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">รายงาน</a></li>
            <li class="breadcrumb-item active" aria-current="page"> โบนัสข้อ 9 Matching </li>
        </ol>
    </nav>
@endsection
@section('content')
<div class="grid grid-cols-12 gap-6 mt-5">
    @foreach ($data as $item)
    <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
      
        <div class="report-box zoom-in">
            <div class="box p-5">
                <div class="flex">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="credit-card" data-lucide="credit-card" class="lucide lucide-credit-card report-box__icon text-pending"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg> 
                    
                </div>
                <div class="text-3xl font-medium leading-8 mt-6">
                                        {{number_format($item->el,2)}}
                                      </div>
                                      <?php
                                      $date = date('d/m/Y', strtotime($item->date_action));
                                      ?>
                <div class="text-base text-slate-500 mt-1">รอชำระ {{$date }}
                                    </div>
            </div>
        </div>

    
    </div>
    @endforeach
    
</div>
<div class="intro-y box p-5 mt-5">
    <div class="flex flex-col sm:flex-row sm:items-end xl:items-start mb-2">
        <form id="tabulator-html-filter-form" class="xl:flex sm:mr-auto" action="{{ route('report_pv_per_day_ab_balance_bonus9_excel') }}"  method="POST" >
            @csrf

            <div class="sm:flex items-center sm:mr-4">
                <div class="col-span-12 sm:col-span-6"> <label for="modal-datepicker-1"
                        class="form-label">รหัสสมาชิก</label><br> <input type="text" id="user_name"
                        class="form-control sm:w-40 2xl:w-full mt-2 sm:mt-0" placeholder="รหัสสมาชิก"> </div>
            </div>

            <div class="col-span-4 sm:col-span-4 p-2"> <label for="modal-datepicker-1" class="form-label">วันที่ทำรายการ</label>
                <input type="date" id="s_date" name="s_date" class="form-control" value="{{ \Carbon\Carbon::yesterday()->format('Y-m-d') }}">
            </div>
    
            <div class="col-span-4 sm:col-span-4 p-2"> <label for="modal-datepicker-2" class="form-label">ถึง</label> <input
                    type="date" id="e_date" name="e_date" class="form-control" value="{{ \Carbon\Carbon::yesterday()->format('Y-m-d') }}"> </div>

  
            <div class="mt-2 xl:mt-2">
                <div class="col-span-12 sm:col-span-6 mt-6"><button id="search-form" type="button"
                        class="btn btn-primary w-full sm:w-16">ค้นหา</button>
                        <button  type="submit"
                        class="btn btn-warning w-full sm:w-16 mt-2 sm:mt-0 sm:ml-1">Excel</button>
                </div>
               
            </div>
        </form>

    </div>
    <div class="overflow-x-auto">
        <div class="table-responsive">
            <table id="workL" class="table table-striped table-hover dt-responsive display nowrap" cellspacing="0">


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
        function numberWithCommas(x) {
            // x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            rs = parseFloat(x).toFixed(2);
            return rs;
 
        }
        $(function() {
            table_order = $('#workL').DataTable({
                // dom: 'Bfrtip',
                // buttons: ['excel'],
                searching: true,
                ordering: true,
                lengthChange: false,
                responsive: true,
                paging: true,
                pageLength: 50,
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
                    url: '{{ route('report_pv_per_day_ab_balance_bonus9_datable') }}',
                    data: function(d) {
                        d.user_name = $('#user_name').val();
                        d.s_date = $('#s_date').val();
                        d.e_date = $('#e_date').val();

                    },
                },


                columns: [
                 

                    {
                data: 'DT_RowIndex',
                name: 'DT_RowIndex',
                className: 'w-10 text-center',
                title: "ลำดับ",
                orderable: false,
                searchable: false
            },
                    {
                        data: "date_action",
                        title: "วันที่",
                        className: "w-10",
                    },
                    {
                        data: "user_name",
                        title: "รหัสสมาชิก",
                        className: "w-10",
                    },
 

                    {
                        data: "qualification",
                        title: "ตำแหน่ง",
                        className: "w-10",
                    },

                    {
                        data: "rate",
                        title: "rate",
                        className: "w-10",
                    },

                 

                    {
                        data: "recive_user_name",
                        title: "recive_user_name",
                        className: "w-10",

                    },
                      
                    {
                        data: "recive_introduce_id",
                        title: "recive_introduce_id",
                        className: "w-10",

                    },

                    {
                        data: "recive_qualification",
                        title: "recive_qualification",
                        className: "w-10",

                    },
                  
                    {
                        data: "recive_expire_date",
                        title: "recive_expire_date",
                        className: "w-5 text-end",
                    },


                    {
                        data: "bonus_full_8",
                        title: "bonus_full_8",
                        className: "w-5 text-end",
                    },

                    {
                        data: "g",
                        title: "g",
                        className: "w-5 text-end",
                    },

                    {
                        data: "percen",
                        title: "percen",
                        className: "w-5 text-end",
                    },
 
                    {
                        data: "tax_percen",
                        title: "tax_percen",
                        className: "w-5 text-end",
                    },
  
                    {
                        data: "tax_total",
                        title: "tax_total",
                        className: "w-5 text-end",
                    },
 
                    {
                        data: "bonus_full",
                        title: "bonus_full",
                        className: "w-5 text-end",
                    },

                    {
                        data: "bonus",
                        title: "bonus",
                        className: "w-5 text-end",
                    },
                  
                    {
                        data: "status",
                        title: "status",
                        className: "w-5 text-end",
                    },

                ],
                // order: [
                //     [4, 'DESC']
                // ],



            });
            $('#search-form').on('click', function(e) {
                table_order.draw();
                e.preventDefault();
            });

        });
    </script>
@endsection
