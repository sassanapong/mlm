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
                              <li class="breadcrumb-item"><a href="learning.php">เรียนรู้</a></li>
                              <li class="breadcrumb-item active text-truncate" aria-current="page">
                                  {{ isset($Lrn->title_lrn) ? $Lrn->title_lrn : '' }}</li>
                          </ol>
                      </nav>
                  </div>
              </div>
              <div class="row">
                  <div class="col-md-12">
                      <div class="card card-box borderR10 mb-3">
                          <div class="card-body">
                              <h4 class="card-title">{{ isset($Lrn->title_lrn) ? $Lrn->title_lrn : '' }}</h4>
                              <span class="badge rounded-pill bg-purple2 bg-opacity-20 text-p1 fw-light mb-1">
                                  {{ isset($Lrn->start_date_lrn) ? $Lrn->start_date_lrn : '' }} to
                                  {{ isset($Lrn->end_date_lrn) ? $Lrn->end_date_lrn : '' }}
                              </span>
                              <hr>
                              <div class="row">
                                  <div class="col-md-8 offset-md-2 mb-3 mb-md-5">
                                      <div class="text-center">
                                          @php
                                              if (!empty($Lrn->upload_video_lrn)) {
                                                  $type_check1 = explode('.', $Lrn->upload_video_lrn);
                                              } else {
                                                  $type_check1[1] = '';
                                              }
                                          @endphp
                                          @if ($Lrn->video_type_lrn == 1)
                                              @if ($type_check1[1] == 'mp4')
                                                  <div class="ratio ratio-16x9">
                                                      <video class="videoIframe js-videoIframe" controls
                                                          allowTransparency="true" allowfullscreen>
                                                          <source
                                                              src="{{ isset($Lrn->upload_video_lrn) ? asset('local/public/upload/lrn/video/' . $Lrn->upload_video_lrn) : '' }}">
                                                      </video>
                                                  </div>
                                              @else
                                                  <div style="padding-top: 0px;"></div>
                                              @endif
                                          @else
                                              @if (isset($Lrn->link_video_lrn))
                                                  <div class="ratio ratio-16x9">
                                                      <iframe class="videoIframe js-videoIframe" height="100%"
                                                          src="{{ $Lrn->link_video_lrn }}" frameborder="0"
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
                                  @if (isset($Lrn->detail_lrn_all))
                                      {!! $Lrn->detail_lrn_all !!}
                                  @endif
                              </div>
                              <hr>
                              <h6>เอกสารไฟล์แนบ:</h6>
                              <div class="list-group mb-5" style="width:400px;max-width:100%;">
                                  <a href="{{ isset($Lrn->uploadfile_lrn) ? asset('local/public/upload/lrn/video/' . $Lrn->uploadfile_lrn) : '' }}"
                                      class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                      {{ isset($Lrn->uploadfile_lrn) ? $Lrn->uploadfile_lrn : '' }}
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
          $('#linkMenuTop .nav-item').eq(2).addClass('active');
      </script>

      <script>
          $('.page-content').css({
              'min-height': $(window).height() - $('.navbar').height()
          });
      </script>
  @endsection
