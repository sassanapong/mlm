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
                            <li class="breadcrumb-item active" aria-current="page">MDK Learning</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <ul class="nav nav-pills mb-3">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="{{ route('Learning') }}">เรียนรู้</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('ct') }}">CT</a>
                        </li>
                    </ul>
                    <div class="card card-box borderR10 mb-3">
                        <div class="card-body">
                            <h4 class="card-title">ค้นหา</h4>
                            <hr>
                            <form action="{{ route('search_lrn') }}" method="GET">
                                <div class="row g-3">
                                    <div class="col-md-5 col-lg-3">
                                        <label for="" class="form-label">เรียงโดย</label>
                                        <select class="form-select" id="">
                                            <option>รายการล่าสุด</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 col-lg-8">
                                        <label for="" class="form-label">คำค้นหา</label>
                                        <input type="text" class="form-control" name="search_lrn" required>
                                    </div>
                                    <div class="col-md-1 col-lg-1">
                                        <label for="" class="form-label d-none d-md-block">&nbsp;</label>
                                        <button type="submit" class="btn btn-dark rounded-circle btn-icon"><i
                                                class="bx bx-search"></i></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card card-box borderR10 mb-2 mb-md-0">
                        <div class="card-body">
                            <h4 class="card-title">รายการเรียนรู้</h4>
                            <hr>
                            <div class="row">
                                @if ($posts_lrn->isNotEmpty())
                                    @foreach ($posts_lrn as $post => $value)
                                        @if (isset($Lrn))
                                            @php
                                                $date = new DateTime();
                                                $date->setTimezone(new DateTimeZone('Asia/Bangkok'));
                                            @endphp
                                            @if ($value->end_date_lrn >= $date->format('Y-m-d'))
                                                <div class="col-md-6">
                                                    <div class="card cardNewsH mb-3">
                                                        <div class="row g-0">
                                                            <div class="col-md-5">
                                                                <div class="box-imageNews">
                                                                    <img src="{{ isset($value->image_lrn) ? asset('local/public/upload/lrn/image/' . $value->image_lrn) : '' }}"
                                                                        class="img-fluid rounded-start" alt="">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-7">
                                                                <div class="card-body">
                                                                    <span
                                                                        class="badge rounded-pill bg-purple2 bg-opacity-20 text-p1 fw-light mb-1">
                                                                        {{ $value->start_date_lrn }} to
                                                                        {{ $value->end_date_lrn }}
                                                                    </span>
                                                                    <h5 class="card-title">{{ $value->title_lrn }}</h5>
                                                                    <p class="card-text">
                                                                        {{ isset($value->detail_lrn) ? $value->detail_lrn : '' }}
                                                                    </p>
                                                                    <a href="{{ url('learning_detail') }}/{{ $value->id }}"
                                                                        class="linkNews stretched-link"><span>อ่านเพิ่มเติม</span><i
                                                                            class='bx bxs-right-arrow-circle'></i></a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endif
                                    @endforeach
                                @else
                                    <div>
                                        <h2>No posts found</h2>
                                    </div>
                                @endif
                                <div class="row">
                                    <div class="col-12">
                                        <nav aria-label="...">
                                            <ul class="pagination justify-content-end">
                                                {{-- <li class="page-item disabled">
                                                <a class="page-link">Previous</a>
                                            </li>
                                            <li class="page-item"><a class="page-link" href="#">1</a></li>
                                            <li class="page-item active" aria-current="page">
                                                <a class="page-link" href="#">2</a>
                                            </li>
                                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                                            <li class="page-item">
                                                <a class="page-link" href="#">Next</a>
                                            </li> --}}
                                                {{ $Lrn->links() }}
                                            </ul>
                                        </nav>
                                    </div>
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
