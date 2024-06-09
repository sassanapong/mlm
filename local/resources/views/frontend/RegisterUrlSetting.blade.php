<title>VRich</title>

@extends('layouts.frontend.app')
@section('conten')
    <div class="bg-whiteLight page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Register Url</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">

                    <div class="card card-box borderR10 mb-2 mb-md-0">
                        <div class="card-body">
                            {{-- <h4 class="card-title">Register Url</h4>
                            <hr> --}}

                            <div class="col-lg-6">
                                <div class="card card-box borderR10 mb-2 mb-lg-0">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-4 col-xxl-3 text-center">

                                                    <div class="rounded-circle">

                                                        @if(Auth::guard('c_user')->user()->profile_img)

                                                        <img src="{{asset('local/public/profile_customer/'.Auth::guard('c_user')->user()->profile_img)}}"  class="img-thumbnail"
                                                        alt="" />
                                                        @else
                                                        <img src="{{ asset('frontend/images/man.png') }}" class="mw-100"
                                                        alt="" />
                                                        @endif

                                                    </div>

                                                {{-- <a href="http://localhost/vrich/editprofileimg" type="button"
                                                    class="btn btn-outline-primary btn-sm mt-2 rounded-pill">
                                                    แก้ไขรูปโปรไฟล์ </a> --}}
                                            </div>
                                            <div class="col-8 col-xxl-9">
                                                <div class="row">
                                                    <div class="col-6">
                                                        {{--
                                                        <span
                                                            class="badge rounded-pill bg-danger bg-opacity-20 text-danger fw-light ps-1">
                                                            <i class="fas fa-circle text-danger"></i> Not Active
                                                        </span> --}}



                                                    </div>
                                                    <div class="col-6 text-end">
                                                        {{-- <a type="button" class="btn btn-warning px-2"
                                                            href="http://localhost/vrich/editprofile"><i
                                                                class="bx bxs-edit"></i></a> --}}
                                                    </div>
                                                </div>


                                                <h5>USERNAME :
                                                    {{ Auth::guard('c_user')->user()->user_name }}
                                                    
                                                    ({{  Auth::guard('c_user')->user()->qualification_id }})</h5>
                                                <h5> {{ Auth::guard('c_user')->user()->name }}
                                                    {{ Auth::guard('c_user')->user()->last_name }}</h5>
                                                    <hr>


                                                <div class="row">
                                                    <h5> Register Url</h5>
                                                    <?php $url_registers = Auth::guard('c_user')->user()->user_name; ?>
                                                    <div class="col-sm-12">
                                                        <div class="input-group input-group-button">
                                                            <input type="text" class="form-control"
                                                                value="{{ url('RegisterUrl/' . $url_registers) }}">
                                                            <span class="input-group-addon btn btn-primary"
                                                                id="basic-addon10">
                                                                <span class="copy-to-clipboard"
                                                                    data-url="{{ url('RegisterUrl/' . $url_registers) }}">Copy
                                                                    Url</span>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer bg-transparent">
                                        <span class="label-xs">
                                            Sponser</span>

                                        <span class="badge bg-light text-dark fw-light"> First Member (NO Sponser)


                                        </span>
                                    </div>
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
            $('.copy-to-clipboard').each(function() {
            $(this).on('click', function() {
                const el = document.createElement('textarea');
                el.value = $(this).attr('data-url')
                document.body.appendChild(el);
                el.select();
                document.execCommand('copy');
                document.body.removeChild(el);

                Swal.fire('Copy Url', el.value, 'success');
            })
        })
</script>
@endsection
