   <title>บริษัท มารวยด้วยกัน จำกัด</title>







   @extends('layouts.frontend.app')
   @section('conten')
       <div class="bg-whiteLight page-content">
           <div class="container-fluid">
               <div class="row">
                   <div class="col-lg-12">
                       <nav aria-label="breadcrumb">
                           <ol class="breadcrumb">
                               <li class="breadcrumb-item"><a href="{{ route('home') }}">หน้าแรก</a></li>
                               <li class="breadcrumb-item"><a href="{{ route('ct') }}">CT</a></li>
                               <li class="breadcrumb-item active text-truncate" aria-current="page">
                                   {{ isset($Ct->title_ct) ? $Ct->title_ct : '' }}</li>
                           </ol>
                       </nav>
                   </div>
               </div>
               <div class="row">
                   <div class="col-md-12">
                       <div class="card card-box borderR10 mb-3">
                           <div class="card-body">
                               <h4 class="card-title">{{ isset($Ct->title_ct) ? $Ct->title_ct : '' }}</h4>
                               <span class="badge rounded-pill bg-purple2 bg-opacity-20 text-p1 fw-light mb-1">
                                   {{ isset($Ct->start_date_ct) ? $Ct->start_date_ct : '' }} to
                                   {{ isset($Ct->end_date_ct) ? $Ct->end_date_ct : '' }}
                               </span>
                               <hr>
                               <div class="row">
                                   <div class="col-md-8 offset-md-2 mb-3 mb-md-5">
                                       <div class="text-center">
                                           @php
                                               if (!empty($Ct->upload_video_ct)) {
                                                   $type_check1 = explode('.', $Ct->upload_video_ct);
                                               } else {
                                                   $type_check1[1] = '';
                                               }
                                           @endphp
                                           @if ($Ct->video_type_ct == 1)
                                               @if ($type_check1[1] == 'mp4')
                                                   <div class="ratio ratio-16x9">
                                                       <video class="videoIframe js-videoIframe" controls
                                                           allowTransparency="true" allowfullscreen>
                                                           <source
                                                               src="{{ isset($Ct->upload_video_ct) ? asset('local/public/upload/ct/video/' . $Ct->upload_video_ct) : '' }}">
                                                       </video>
                                                   </div>
                                               @else
                                                   <div style="padding-top: 0px;"></div>
                                               @endif
                                           @else
                                               @if (isset($Ct->link_video_ct))
                                                   <div class="ratio ratio-16x9">
                                                       <iframe class="videoIframe js-videoIframe" height="100%"
                                                           src="{{ $Ct->link_video_ct }}" frameborder="0"
                                                           allowTransparency="true" allowfullscreen data-src=""></iframe>
                                                   </div>
                                               @else
                                                   <div style="padding-top: 0px;"></div>
                                               @endif
                                           @endif
                                       </div>
                                   </div>
                               </div>
                               <div class="detail" style="text-align: center;">
                                   @if (isset($Ct->detail_ct_all))
                                       {!! $Ct->detail_ct_all !!}
                                   @endif
                               </div>
                               <hr>
                               <h6>เอกสารไฟล์แนบ:</h6>
                               <div class="list-group mb-5" style="width:400px;max-width:100%;">
                                   <a href="{{ isset($Ct->uploadfile_ct) ? asset('local/public/upload/ct/video/' . $Ct->uploadfile_ct) : '' }}"
                                       class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                       {{ isset($Ct->uploadfile_ct) ? $Ct->uploadfile_ct : '' }}
                                       <span class=""><i class="bx bx-download bx-sm"></i></span>
                                   </a>
                               </div>
                           </div>
                       </div>
                   </div>
               </div>
           </div>
       </div>
   @endsection

   @section('script')
       <script>
           $('#linkMenuTop .nav-item').eq(3).addClass('active');
       </script>

       <script>
           $('.page-content').css({
               'min-height': $(window).height() - $('.navbar').height()
           });
       </script>
   @endsection
