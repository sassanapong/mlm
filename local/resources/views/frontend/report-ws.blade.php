   <title>บริษัท มารวยด้วยกัน จำกัด</title>

   @section('css')
   <link href='https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css' rel='stylesheet'>
@endsection
   @extends('layouts.frontend.app')
   @section('conten')
       <div class="bg-whiteLight page-content">
           <div class="container-fluid">
               <div class="row">
                   <div class="col-lg-12">
                       <nav aria-label="breadcrumb">
                           <ol class="breadcrumb">
                               <li class="breadcrumb-item"><a href="{{route('home')}}">หน้าแรก</a></li>
                               <li class="breadcrumb-item active text-truncate" aria-current="page"> PV เคลื่อนไหวรายวัน </li>
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
                                       <label for="" class="form-label">วันที่เริ่มต้น</label>
                                       <input type="date" class="form-control" id="startDate">
                                   </div>
                                   <div class="col-md-6 col-lg-2">
                                       <label for="" class="form-label">วันที่สิ้นสุด</label>
                                       <input type="date" class="form-control" id="endDate">
                                   </div>
                                    
                                 
                                   {{-- <div class="col-md-10 col-lg-3">
                                       <label for="" class="form-label"> ค้นหา</label>
                                       <input type="text" class="form-control">
                                   </div> --}}
                                   <div class="col-md-2 col-lg-1">
                                       <label for="" class="form-label d-none d-md-block">&nbsp;</label>
                                       <button type="button" id="search-form" class="btn btn-dark rounded-circle btn-icon"><i
                                               class="bx bx-search"></i></button>
                                   </div>
                               </div>
                           </div>
                       </div>
                       <div class="card card-box borderR10 mb-2 mb-md-0">
                           <div class="card-body">
                               <div class="row">
                                   <div class="col-sm-6">
                                       <h4 class="card-title mb-0">PV เคลื่อนไหวรายวัน</h4>
                                   </div>
                                   {{-- <div class="col-sm-6 text-md-end">
                                       <button type="button" class="btn btn-info rounded-pill"><i
                                               class='bx bxs-file me-1'></i> ออกรายงาน</button>
                                   </div> --}}
                               </div>
                               <hr>
                               <table id="report" class="table table-bordered nowrap">
                                  
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
     
         <script>
             $(document).ready(function() {
            $(function() {
                 oTable = $('#report').DataTable({
                     processing: true,
                     serverSide: true,
                     searching: true,
                     pageLength: 25,
                     ajax: {
                         url: '{{ route('reportsws_datatable') }}',
                         data: function(d) {
                             
                             d.startDate = $('#startDate').val();
                             d.endDate = $('#endDate').val();
                         },
                         method: 'get'
                     },


                     columns: [
                        {
                             data: 'date_action',
                             title: '<center>วันที่</center> ',
                             className: 'text-center'
                         },   
                        {
                             data: 'user_name',
                             title: '<center>UserName</center>',
                             className: 'text-center'
                         },

                         {
                             data: 'qualification_id',
                             title: '<center>ตำแหน่ง</center>',
                             className: 'text-center'
                         },
                         {
                             data: 'pv_today',
                             title: '<center> Pv ส่วนตัว</center>',
                             className: 'text-end'
                         },


                         {
                             data: 'pv_a_new',
                             title: '<center>Pv สายงานฝั่งซ้าย</center>',
                             className: 'text-end'
                         },
                         {
                             data: 'pv_b_new',
                             title: '<center>Pv สายงานฝั่งขวา</center>',
                             className: 'text-end'
                         },
                         {
                             data: 'balance',
                             title: '<center>Pv Balance</center>',
                             className: 'text-end'
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
