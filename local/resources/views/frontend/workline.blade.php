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
                 <div class="row">

                     <div class="col-md-12">
                        <div class="card card-box borderR10 mb-3">
                             <div class="card-body">
                                 <h4 class="card-title">ค้นหา</h4>
                                 <hr>
                                 <div class="row g-3">
                                     <div class="col-md-6 col-lg-3">
                                         <label for="" class="form-label">การรักษาสภาพ</label>
                                         <select class="form-select" id="ex_date"  onchange="search_form()">
                                             <option value="">ทั้งหมด</option>
                                             <option value="1">รักษาสภาพ</option>
                                             <option value="2">ไม่รักษาสภาพ</option>
                                             {{-- <option>Not Active</option> --}}
                                         </select>
                                     </div>
                                     {{-- <div class="col-md-6 col-lg-3">
                                         <label for="" class="form-label">ชั้น</label>
                                         <select class="form-select" id="">
                                             <option>ทั้งหมด</option>
                                             <option>1</option>
                                             <option>2</option>
                                             <option>3</option>
                                         </select>
                                     </div> --}}
                                     {{-- <div class="col-md-6 col-lg-3">
                                         <label for="" class="form-label">วันที่เริ่มต้น</label>
                                         <input type="date" class="form-control">
                                     </div>
                                     <div class="col-md-6 col-lg-3">
                                         <label for="" class="form-label">วันที่สิ้นสุด</label>
                                         <input type="date" class="form-control">
                                     </div> --}}
                                     <div class="col-md-3 col-lg-3">
                                         <label for="" class="form-label">ตำแหน่ง</label>
                                         <select class="form-select" id="slv" onchange="search_form()">
                                             <option value="">ทั้งหมด</option>
                                             <option value="MC">MC</option>
                                             <option value="MB">MB</option>
                                             <option value="MO">MO</option>
                                             <option value="VIP">VIP</option>
                                             <option value="VVIP">VVIP</option>
                                             <option value="XVVIP">XVVIP</option>
                                             <option value="SVVIP">SVVIP</option>
                                             <option value="MG">MG</option>
                                             <option value="MR">MR</option>
                                             <option value="ME">ME</option>
                                             <option value="MD">MD</option>
                                         </select>
                                     </div>
                                     <div class="col-md-4 col-lg-3">
                                         <label for="" class="form-label">รหัสสมาชิก</label>
                                         <input type="text" class="form-control" id="susername" onchange="search_form()">
                                     </div>
                                     <div class="col-md-4 col-lg-3">
                                         <label for="" class="form-label">ชื่อ</label>
                                         <input type="text" class="form-control" id="name" onchange="search_form()">
                                     </div>
                                     {{-- <div class="col-md-1 col-lg-1">
                                         <label for="" class="form-label d-none d-md-block">&nbsp;</label>
                                         <button type="button" class="btn btn-dark rounded-circle btn-icon"><i
                                                 class="bx bx-search"></i></button>
                                     </div> --}}
                                 </div>
                             </div>
                         </div> 
                         <div class="card card-box borderR10 mb-2 mb-md-0">
                             <div class="card-body">
                                 <div class="row">
                                    <?php
                                    if($user_name){
                                        $text_user_name = $user_name;
                                    }else{
                                        $text_user_name = Auth::guard('c_user')->user()->user_name;
                                    }

                                    $usr_name = \App\Http\Controllers\Frontend\FC\AllFunctionController::get_upline($text_user_name);

                                    ?>


                                     <div class="col-sm-4">
                                         <h4 class="card-title mb-2">ข้อมูลสายงานแนะนำ</h4>
                                         <p class="mb-2">รหัสสมาชิก {{ @$usr_name->user_name }} | {{ @$usr_name->name }} {{ @$usr_name->last_name }}</ย>
                                     </div>
                                     <div class="col-sm-8 text-md-end">
                                         <div><span>สถานะการแจ้งเตือน: </span> <i class="fas fa-circle text-success"></i>
                                             Active <i class="fas fa-circle text-warning"></i> กรุณายืนยันสิทธิ <i
                                                 class="fas fa-circle text-danger"></i> Not Active</div>
                                     </div>
                                 </div>
                                 <hr>
                                 <div class=" table-responsive">

                                     <table id="workL" class="table table-bordered nowrap">


                                     </table>
                                 </div>
                             </div>
                         </div>
                     </div>
                 </div>
             </div>
         </div>

 
     @endsection

     @section('script')
         <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
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
                     searching: false,
                     pageLength: 50,
                     ajax: {
                         url: '{{ route('Workline_datatable') }}',
                         data: function(d) {

                            d.lv = '{{$lv}}';
                            d.user_name = '{{$user_name}}';
                             d.name = $('#name').val();
                             d.susername = $('#susername').val(); 
                             d.ex_date = $('#ex_date').val();
                             d.slv = $('#slv').val();
                         },
                         method: 'get'
                     },


                     columns: [{
                             data: 'status_active',
                             title: '<center>การรักษาสภาพ</center>',
                             className: 'text-center'
                         },

                         {
                             data: 'created_at',
                             title: '<center>วันที่สมัคร</center> ',
                             className: 'text-center'
                         },
                         {
                             data: 'user_name',
                             title: '<center>รหัสสมาชิก</center>',
                             className: 'text-center'
                         },
                         {
                             data: 'name',
                             title: '<center>ชื่อสมาชิก</center>',
                             className: 'text-center'
                         },


                         {
                             data: 'qualification_id',
                             title: '<center>ตำแหน่ง</center>',
                             className: 'text-center'
                         },
                         {
                             data: 'expire_date',
                             title: '<center>วัน Active(วัน)</center>',
                             className: 'text-center'
                         },

                         {
                             data: 'expire_date_bonus',
                             title: '<center>วัน ActiveBonus(วัน)</center>',
                             className: 'text-center'
                         },
                         {
                             data: 'introduce_id',
                             title: '<center>รหัสผู้แนะนำ</center>',
                             className: 'text-center'
                         },
                         {
                             data: 'introduce_name',
                             title: '<center>ชื่อผู้แนะนำ</center>',
                             className: 'text-center'
                         },
                         {
                             data: 'sponsor_lv',
                             title: '<center>ชั้นการแนะนำ<br>(Sponsor)</center>',
                             className: 'text-center'
                         },

                        //  {
                        //      data: 'phone',
                        //      title: '<center>เบอโทรศัพท์</center>',
                        //      className: 'text-center'
                        //  },
                         {
                             data: 'view',
                             title: '<center>ดูสายงาน</center>',
                             className: 'text-center'
                         },
                        //  {
                        //      data: 'action',
                        //      title: '<center>Action</center>',
                        //      className: 'text-center'
                        //  },

                     ],
                 });
                //  $('.myWhere,.myLike,.myCustom,#onlyTrashed').on('change', function(e) {
                //      oTable.draw();
                //  });

                //  $('#search-form').on('click', function(e) {
                //      oTable.draw();
                //      e.preventDefault();
                //  });
             });

            });
            function search_form(){
                oTable.draw();
                e.preventDefault();

            }
         </script>
     @endsection
