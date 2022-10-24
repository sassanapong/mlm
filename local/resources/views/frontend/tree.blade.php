 <title>
     บริษัท มารวยด้วยกัน จำกัด</title>



 @extends('layouts.frontend.app')
 @section('css')
     <link rel="stylesheet" href="{{ asset('local/resources/css/tree.css') }}">
 @endsection
 @section('conten')
     <div class="bg-whiteLight page-content">
         <div class="container-fluid">
             <div class="row">
                 <div class="col-lg-12">
                     <nav aria-label="breadcrumb">
                         <ol class="breadcrumb">
                             <li class="breadcrumb-item"><a href="{{ route('home') }}">หน้าแรก</a></li>
                             <li class="breadcrumb-item active text-truncate" aria-current="page"> เลื่อนตำแหน่ง</li>
                         </ol>
                     </nav>
                 </div>
             </div>


             <div class="row">
                 <div class="col-md-12">
                     <div class="card card-box borderR10 mb-2 mb-md-0">
                         <div class="card-body">
                             <h4 class="card-title">การจัดการตำแหน่ง</h4>
                             <hr>

                             <div class="row ">

                                 <div class="col-md-12 mb-4">
                                     <div class="tree">
                                         <ul>
                                             <li>

                                                 @if ($data['lv1'])
                                                     <a href="javascript:void(0);"
                                                         onclick="modal_tree('{{ $data['lv1']->user_name }}')">
                                                         <p class="text-muted">
                                                             @if ($data['lv1']->business_name and $data['lv1']->business_name != '-')
                                                                 {{ $data['lv1']->business_name }}
                                                                 <br>({{ $data['lv1']->user_name }} )
                                                             @else
                                                                 {{ $data['lv1']->name . ' ' . $data['lv1']->last_name }}
                                                                 ({{ $data['lv1']->user_name }} )
                                                             @endif
                                                         </p>
                                                     </a>
                                                 @endif

                                                 <ul>
                                                     @for ($i = 1; $i <= 5; $i++)
                                                         @php
                                                             if ($i == 1) {
                                                                 $data_lv2 = $data['lv2_a'];
                                                                 $model_lv2 = 'lv2_a';
                                                                 $type = 'a';
                                                                 $line_lv2 = 'A';
                                                             } elseif ($i == 2) {
                                                                 $data_lv2 = $data['lv2_b'];
                                                                 $model_lv2 = 'lv2_b';
                                                                 $type = 'b';
                                                                 $line_lv2 = 'B';
                                                             } elseif ($i == 3) {
                                                                 $data_lv2 = $data['lv2_c'];
                                                                 $model_lv2 = 'lv2_c';
                                                                 $type = 'c';
                                                                 $line_lv2 = 'C';
                                                             } elseif ($i == 4) {
                                                                 $data_lv2 = $data['lv2_d'];
                                                                 $model_lv2 = 'lv2_d';
                                                                 $type = 'd';
                                                                 $line_lv2 = 'D';
                                                             } elseif ($i == 5) {
                                                                 $data_lv2 = $data['lv2_e'];
                                                                 $model_lv2 = 'lv2_e';
                                                                 $type = 'e';
                                                                 $line_lv2 = 'E';
                                                             } else {
                                                                 $data_lv2 = null;
                                                                 $model_lv2 = null;
                                                                 $line_lv2 = null;
                                                             }
                                                         @endphp
                                                         <li>
                                                             @if ($data_lv2)
                                                             <a href="javascript:void(0);"
                                                             onclick="modal_tree('{{ $data_lv2->user_name }}')">
                                                                     @if ($data_lv2->business_name and $data_lv2->business_name != '-')
                                                                         {{ $data_lv2->business_name }}
                                                                         ({{ $data_lv2->user_name }} )
                                                                     @else
                                                                         {{ $data_lv2->name . ' ' . $data_lv2->last_name }}
                                                                         ({{ $data_lv2->user_name }} )
                                                                     @endif
                                                                 </a>
                                                                 <ul class="vertical">
                                                                    @php $x=2; @endphp
                                                                     @for ($j = 1; $j <= 5; $j++)


                                                                         @php
                                                                         $data_lv = $x+$j;
                                                                             if ($j == 1) {
                                                                                 $data_lv3 = $data['lv'.$data_lv.'_' . $type . '_a'];
                                                                                 $model_lv3 = 'lv'.$data_lv.'_'.$type.'_a';
                                                                                 $line_lv3 = 'A';
                                                                             } elseif ($j == 2) {
                                                                                 $data_lv3 = $data['lv'.$data_lv.'_'.$type.'_b'];
                                                                                 $model_lv3 = 'lv'.$data_lv.'_'.$type.'_b';
                                                                                 $line_lv3 = 'B';
                                                                             } elseif ($j == 3) {
                                                                                 $data_lv3 = $data['lv'.$data_lv.'_'.$type.'_c'];
                                                                                 $model_lv3 = 'lv'.$data_lv.'_'.$type.'_c';
                                                                                 $line_lv3 = 'C';
                                                                             } elseif ($j == 4) {
                                                                                 $data_lv3 = $data['lv'.$data_lv.'_'.$type.'_d'];
                                                                                 $model_lv3 = 'lv'.$data_lv.'_'.$type.'_d';
                                                                                 $line_lv3 = 'd';
                                                                             } elseif ($j == 5) {
                                                                                 $data_lv3 = $data['lv'.$data_lv.'_'.$type.'_e'];
                                                                                 $model_lv3 = 'lv'.$data_lv.'_'.$type.'_e';
                                                                                 $line_lv3 = 'E';
                                                                             } else {
                                                                                 $data_lv3 = null;
                                                                                 $model_lv3 = null;
                                                                                 $line_lv3 = null;
                                                                             }
                                                                         @endphp
                                                                         @if ($data_lv3)
                                                                             <li> <a href="javascript:void(0);"
                                                                                onclick="modal_tree('{{ $data_lv3->user_name }}')">
                                                                                     @if ($data_lv3->business_name and $data_lv3->business_name != '-')
                                                                                         {{ $data_lv3->business_name }}
                                                                                         <br>({{ $data_lv3->user_name }})
                                                                                     @else
                                                                                         {{ $data_lv3->name . ' ' . $data_lv3->last_name }}
                                                                                         <br>({{ $data_lv3->user_name }})
                                                                                     @endif
                                                                                 </a></li>
                                                                         @else
                                                                             {{-- <li><a href="#"> + </a></li> --}}
                                                                         @endif
                                                                     @endfor


                                                                 </ul>
                                                             @else
                                                                 <a href="#"> + </a>
                                                             @endif
                                                         </li>
                                                     @endfor


                                                 </ul>
                                             </li>
                                         </ul>
                                     </div>
                                 </div>

                             </div>
                         </div>
                     </div>
                 </div>
             </div>


         </div>
     </div>

     <div id="modal_tree"></div>

 @endsection

 @section('script')
     <script>
         $('.page-content').css({
             'min-height': $(window).height() - $('.navbar').height()
         });

         function modal_tree(user_name){

            $.ajax({
            url: '{{ route('modal_tree') }}',
            type: 'GET',
            data: {user_name:user_name},
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
     </script>


 @endsection
