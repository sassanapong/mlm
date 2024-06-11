 <title>
     บริษัท มารวยด้วยกัน จำกัด</title>



 @extends('layouts.frontend.app')
 @section('css')
 @section('css')
     <style>
         /*CSS*/

         #tree {
             font-family: 'Poppins', sans-serif;
             width: 100%;
             height: 100%;
             background-color: #EEEEF1;
         }

         /*partial*/
         .node.QA rect {
             fill: #F57C00;
         }

         .node.Dev rect {
             fill: #039BE5;
         }

         .node.Marketing rect {
             fill: #FFCA28;
         }



         /* .boc-search {
                display: none;
            } */
     </style>
     <script src="{{ asset('frontend/js/OrgChart.js') }}"></script>
 @endsection
 @section('conten')
     <div class="bg-whiteLight page-content">
         <div class="container-fluid">
             <div class="row">
                 <div class="col-lg-12">
                     <nav aria-label="breadcrumb">
                         <ol class="breadcrumb">
                             <li class="breadcrumb-item"><a href="{{ route('home') }}">หน้าแรก</a></li>
                             <li class="breadcrumb-item active text-truncate" aria-current="page"> ข้อมูลสายงาน Upline</li>
                         </ol>
                     </nav>
                 </div>
             </div>


             <div class="row">
                 <div class="col-md-12">
                     <div class="card card-box borderR10 mb-2 mb-md-0">
                         <div class="card-body">

                             <div class="row">


                                 <div class="col-sm-4">

                                     <div class="row g-3">


                                         <div class="col-md-5 col-lg-5">
                                             <input type="text" class="form-control" placeholder="คำค้นหารหัส">
                                         </div>
                                         <div class="col-md-1 col-lg-1">

                                             <button type="button" class="btn btn-dark rounded-circle btn-icon"
                                                 fdprocessedid="40zsgc"><i class="bx bx-search"></i></button>
                                         </div>
                                     </div>
                                 </div> 
                                 <div class="col-sm-8 text-md-end">
                                    @if($upstap)
                                    <button type="button"  onclick="event.preventDefault();
                                    document.getElementById('UpStep').submit();" class="btn btn-primary px-2"><i
                                        class="fa fa-arrow-up m-2"></i> Up Step</button>
                                    <form id="UpStep" action="{{route('tree')}}" method="POST" style="display: none;">
                                      <input type="hidden" name="user_name" value="{{$upstap}}">
                                      @csrf
                                    </form> 
                                    @endif
                                   
                                 
                                     <a type="button" class="btn btn-primary px-2" href="{{ route('tree') }}"><i
                                             class="fa fa-sitemap m-2"></i> กลับสู่รหัสผู้ใช้ </a>
                                 </div>
                             </div>
                             <hr>

                             <div class="row " style="overflow-y: hidden; align-content: center">

                                 <div class="col-md-12 mb-4">

                                     <div id="tree_view"></div>


                                 </div>

                             </div>

                             <div id="modal_tree"></div>
                         </div>
                     </div>
                 </div>
             </div>


         </div>
     </div>

     <div class="modal fade" id="modal_add_show" tabindex="-1" aria-labelledby="withdrawModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
            <div class="modal-content borderR25">
               
                <div class="modal-body"   >
                    <h4 class="modal-title" id="text_add">สมัครสมาชิก </h4>

                </div>
                <div class="modal-footer justify-content-between border-0">
                    <button type="button" class="btn btn-outline-dark rounded-pill"
                        data-bs-dismiss="modal">Class</button>
                    <a href="" id="pid_link" type="button"
                        class="btn btn-primary waves-effect waves-light ">ยืนยันสมัครสมาชิก</a>

                         
                </div>
            </div>

        </div>
    </div>
   


 @endsection

 @section('script')

     <script>
         function modal_tree(user_name) {

             $.ajax({
                     url: '{{ route('modal_tree') }}',
                     type: 'GET',
                     data: {
                         user_name: user_name
                     },
                 })
                 .done(function(data) {
                     console.log("success");
                     $('#modal_tree').html(data);
                     $('#tree').modal('show');
                 })
                 .fail(function() {
                     console.log("error");
                 })
                 .always(function() {
                     console.log("complete");
                 });

         }


         $('.page-content').css({
             'min-height': $(window).height() - $('.navbar').height()
         });

         //JavaScript

         OrgChart.templates.cool = Object.assign({}, OrgChart.templates.ana);
         OrgChart.templates.cool.defs =
             '<filter x="-50%" y="-50%" width="200%" height="200%" filterUnits="objectBoundingBox" id="cool-shadow"><feOffset dx="0" dy="4" in="SourceAlpha" result="shadowOffsetOuter1" /><feGaussianBlur stdDeviation="10" in="shadowOffsetOuter1" result="shadowBlurOuter1" /><feColorMatrix values="0 0 0 0 0   0 0 0 0 0   0 0 0 0 0  0 0 0 0.1 0" in="shadowBlurOuter1" type="matrix" result="shadowMatrixOuter1" /><feMerge><feMergeNode in="shadowMatrixOuter1" /><feMergeNode in="SourceGraphic" /></feMerge></filter>';

         OrgChart.templates.cool.size = [310, 190];
         OrgChart.templates.cool.node =
             '<rect filter="url(#cool-shadow)"  x="0" y="0" height="190" width="310" fill="#ffffff" stroke-width="1" stroke="#eeeeee" rx="10" ry="10"></rect><rect fill="#ffffff" x="100" y="10" width="200" height="100" rx="10" ry="10" filter="url(#cool-shadow)"></rect><rect stroke="#eeeeee" stroke-width="1" x="10" y="120" width="290" fill="#ffffff" rx="10" ry="10" height="60"></rect> <text  style="font-size: 15px;" fill="#000" x="110" y="60">ตำแหน่ง</text>';

         OrgChart.templates.cool.img =
             '<clipPath id="{randId}"><rect  fill="#ffffff" stroke="#039BE5" stroke-width="5" x="10" y="10" rx="10" ry="10" width="80" height="100"></rect></clipPath><image preserveAspectRatio="xMidYMid slice" clip-path="url(#{randId})" xlink:href="{val}" x="10" y="10"  width="80" height="100"></image><rect fill="none" stroke="" stroke-width="2" x="10" y="10" rx="10" ry="10" width="80" height="100"></rect>';
            
         OrgChart.templates.cool.name = '<text  style="font-size: 20px;font-family: prompt" fill="#1a4518" x="110" y="30">{val}</text>';
         OrgChart.templates.cool.title =
             '<text  style="font-size: 20px;font-family: prompt" fill="#1a4518" x="20" y="145">รหัสสมาชิก : {val}</text>';
         OrgChart.templates.cool.title2 =
             '<text style="font-size: 20px;font-family: prompt" fill="#1a4518" x="20" y="165">รหัสผู้แนะนำ : {val}</text>';
         OrgChart.templates.cool.type_upline =
             '<text style="font-size: 24px;font-family: prompt" fill="#ae00ff" x="270" y="165" text-anchor="middle">{val}</text>';
         OrgChart.templates.cool.performance = '<text style="font-size: 24px;" fill="#1a4518" x="110" y="90" >{val}</text>';
         OrgChart.templates.cool.svg =
             '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="background-color:#F2F2F2;display:block;" width="{w}" height="{h}" viewBox="{viewBox}">{content}</svg>';
         OrgChart.templates.cool.nodeMenuButton =
             '<g style="cursor:pointer;" transform="matrix(1,0,0,1,270,20)" data-ctrl-n-menu-id="{id}">' +
             '<rect x="-4" y="-10" fill="#000000" fill-opacity="0" width="22" height="22"></rect>' +
             '<circle cx="0" cy="0" r="2" fill="#1a4518"></circle>' +
             '<circle cx="7" cy="0" r="2" fill="#1a4518"></circle><circle cx="14" cy="0" r="2" fill="#1a4518"></circle>' +
             '</g>';


         var chart;
         chart = new OrgChart(document.getElementById('tree_view'), {
             mouseScrool: OrgChart.none,
             enableSearch: false,
             template: 'cool',

             layout: OrgChart.normal,
             align: OrgChart.ORIENTATION,
             scaleInitial: OrgChart.match.boundary,
             nodeBinding: {
                 name: 'name',
                 title: 'title',
                 title2: 'title2',
                 type_upline: 'type_upline',
                 performance: 'performance',
                 img: 'img'
             },
             toolbar: {
                 layout: true,
                 zoom: true,
                 fit: true,
                 expandAll: true
             },
             // nodeMenu: {
             //     details: { text: "Details" },
             //     edit: { text: "Edit" },
             //     add: { text: "Add" },
             //     remove: { text: "Remove" }
             // },
         });

         // chart.load([
         //     { id: 1, performance: 11, points: 50, name: 'ชื่อ ccc cccc', title: 'Gold', title2: 'รหัส : 0000001', img: 'https://cdn.balkan.app/shared/1.jpg' },
         //     { id: 2, performance: 33, points: 99, name: 'Fran Parsons', title: 'Developer', pid: 1, title2: 'รหัส : 0000001', img: 'https://cdn.balkan.app/shared/2.jpg' },
         //     { id: 3, performance: 99, points: 34, name: 'Lynn Hussain', title: 'Sales', pid: 1, title2: 'รหัส : 0000001', img: 'https://cdn.balkan.app/shared/3.jpg' }
         // ]);
         var nodes = {!! $myArray !!};
         chart.load(nodes);


         chart.on('nodes-initialized', function(sender, args) {

             var departmentNodes = [];
             for (var id in args.nodes) {
                 var node = args.nodes[id];
                 var data = sender.get(id);
                 //console.log(data.status);
                 node.type_upline = data.type_upline;
                 node.status_data = data.status;
             }
         })

         chart.onNodeClick(function(args) {
             username = args.node.id;
             pid = args.node.pid;
             type = args.node.type_upline;
            //  console.log(type);
             if (args.node.status_data == 'add') {
                 modal_add(pid,type);
             } else {
                 modal_tree(username);
             }

             return false;


         })



         function modal_add(pid,type) {
             url_value = '{{ route('register') }}' + '/' + pid + '/' + type;
             $('#pid_link').attr('href', url_value);
             $('#text_add').html('สมัครสมาชิกใต้ '+pid+' ฝั่งขา '+type );
              
             $('#modal_add_show').modal('show');

         }
     </script>


 @endsection
