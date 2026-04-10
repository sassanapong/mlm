   <title>บริษัท มารวยด้วยกัน จำกัด</title>








   @extends('layouts.frontend.app')
   @section('conten')
       <div class="bg-whiteLight page-content">
           <div class="container-fluid">
               <div class="row">
                   <div class="col-lg-12">
                       <nav aria-label="breadcrumb">
                           <ol class="breadcrumb">
                               <li class="breadcrumb-item"><a href="{{route('home')}}">หน้าแรก</a></li>
                               <li class="breadcrumb-item active text-truncate" aria-current="page"> โบนัส Fast Start</li>
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
                                       <input type="date" class="form-control">
                                   </div>
                                   <div class="col-md-6 col-lg-2">
                                       <label for="" class="form-label">วันที่สิ้นสุด</label>
                                       <input type="date" class="form-control">
                                   </div>
                                   <div class="col-md-6 col-lg-2">
                                       <label for="" class="form-label">เวลาเริ่มต้น</label>
                                       <input type="time" class="form-control">
                                   </div>
                                   <div class="col-md-6 col-lg-2">
                                       <label for="" class="form-label">เวลาสิ้นสุด</label>
                                       <input type="time" class="form-control">
                                   </div>
                                   <div class="col-md-10 col-lg-3">
                                       <label for="" class="form-label">คำค้นหา</label>
                                       <input type="text" class="form-control">
                                   </div>
                                   <div class="col-md-2 col-lg-1">
                                       <label for="" class="form-label d-none d-md-block">&nbsp;</label>
                                       <button type="button" class="btn btn-dark rounded-circle btn-icon"><i
                                               class="bx bx-search"></i></button>
                                   </div>
                               </div>
                           </div>
                       </div>
                       <div class="card card-box borderR10 mb-2 mb-md-0">
                           <div class="card-body">
                               <div class="row">
                                   <div class="col-sm-6">
                                       <h4 class="card-title mb-0">รายการโบนัสFast Start</h4>
                                   </div>
                                   <div class="col-sm-6 text-md-end">
                                       <button type="button" class="btn btn-info rounded-pill"><i
                                               class='bx bxs-file me-1'></i> ออกรายงาน</button>
                                   </div>
                               </div>
                               <hr>
                               <table id="bonusAll" class="table table-bordered nowrap">
                                   <thead class="bg-light">
                                       <tr class="text-center">
                                           <th>วันที่</th>
                                           <th>เวลา</th>
                                           <th>จากรหัส</th>
                                           <th>ชื่อ-สกุล</th>
                                           <th>ยอดเงิน</th>
                                           <th>คงเหลือ</th>
                                           <th>อ้างอิง</th>
                                       </tr>
                                   </thead>
                                   <tbody>
                                       <tr class="text-center">
                                           <td>1/4/65</td>
                                           <td>12:30:05</td>
                                           <td>MLM123456</td>
                                           <td class="text-start">กันยา เผ่ารอด</td>
                                           <td class="text-end">1,600.00</td>
                                           <td class="text-end">5,250.00</td>
                                           <td></td>
                                       </tr>
                                       <tr class="text-center">
                                           <td>1/4/65</td>
                                           <td>12:20:30</td>
                                           <td>MLM123456</td>
                                           <td class="text-start">กันยา เผ่ารอด</td>
                                           <td class="text-end">500.00</td>
                                           <td class="text-end">3,650.00</td>
                                           <td></td>
                                       </tr>
                                       <tr class="text-center">
                                           <td>1/4/65</td>
                                           <td>12:20:01</td>
                                           <td>MLM123456</td>
                                           <td class="text-start">กันยา เผ่ารอด</td>
                                           <td class="text-end">1,000.00</td>
                                           <td class="text-end">3,150.00</td>
                                           <td></td>
                                       </tr>
                                       <tr class="text-center">
                                           <td>1/4/65</td>
                                           <td>12:15:32</td>
                                           <td>MLM123456</td>
                                           <td class="text-start">กันยา เผ่ารอด</td>
                                           <td class="text-end">1,600.00</td>
                                           <td class="text-end">2,150.00</td>
                                           <td></td>
                                       </tr>
                                       <tr class="text-center">
                                           <td>1/4/65</td>
                                           <td>12:10:33</td>
                                           <td>MLM123456</td>
                                           <td class="text-start">กันยา เผ่ารอด</td>
                                           <td class="text-end">500.00</td>
                                           <td class="text-end">550.00</td>
                                           <td></td>
                                       </tr>
                                       <tr class="text-center">
                                           <td>1/4/65</td>
                                           <td>12:10:33</td>
                                           <td>MLM123456</td>
                                           <td class="text-start">กันยา เผ่ารอด</td>
                                           <td class="text-end">50.00</td>
                                           <td class="text-end"></td>
                                           <td></td>
                                       </tr>
                                   </tbody>
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
       <script>
           $(document).ready(function() {
               var table = $('#bonusAll').DataTable({
                   responsive: true
               });

               new $.fn.dataTable.FixedHeader(table);
           });
       </script>
   @endsection
