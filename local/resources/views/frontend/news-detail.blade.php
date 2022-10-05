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
                                 <li class="breadcrumb-item active text-truncate" aria-current="page">{{ $News->title_news }}
                                 </li>
                             </ol>
                         </nav>
                     </div>
                 </div>
                 <div class="row">
                     <div class="col-md-12">
                         <div class="card card-box borderR10 mb-3">
                             <div class="card-body">
                                 <h4 class="card-title">{{ $News->title_news }}</h4>
                                 <span class="badge rounded-pill bg-purple2 bg-opacity-20 text-p1 fw-light mb-1">
                                     {{ $News->start_date_news }} to
                                     {{ $News->end_date_news }}
                                 </span>
                                 <hr>
                                 <div class="row">
                                     <div class="col-md-8 offset-md-2 mb-3 mb-md-5">
                                         <div class="text-center">
                                             @php
                                                 if (!empty($News->upload_video)) {
                                                     $type_check1 = explode('.', $News->upload_video);
                                                 } else {
                                                     $type_check1[1] = '';
                                                 }
                                             @endphp
                                             @if ($News->video_type == 1)
                                                 @if ($type_check1[1] == 'mp4')
                                                     <div class="ratio ratio-16x9">
                                                         <video class="videoIframe js-videoIframe" controls
                                                             allowTransparency="true" allowfullscreen>
                                                             <source
                                                                 src="{{ isset($News->upload_video) ? asset('local/public/upload/news/video/' . $News->upload_video) : '' }}">
                                                         </video>
                                                     </div>
                                                 @else
                                                     <div style="padding-top: 0px;"></div>
                                                 @endif
                                             @else
                                                 @if (isset($News->link_video))
                                                     <div class="ratio ratio-16x9">
                                                         <iframe class="videoIframe js-videoIframe" height="100%"
                                                             src="{{ $News->link_video }}" frameborder="0"
                                                             allowTransparency="true" allowfullscreen
                                                             data-src=""></iframe>
                                                     </div>
                                                 @else
                                                     <div style="padding-top: 0px;"></div>
                                                 @endif
                                             @endif
                                         </div>
                                     </div>
                                 </div>
                                 <div class="detail" style="text-align: center">
                                     {!! $News->detail_news_all !!}
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
             $('.page-content').css({
                 'min-height': $(window).height() - $('.navbar').height()
             });
         </script>
     @endsection
