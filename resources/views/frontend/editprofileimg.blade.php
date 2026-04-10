@extends('layouts.frontend.app')

@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('frontend/css/croppie.css')}}">
@endsection

@section('conten')


<div class="bg-whiteLight page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">หน้าแรก</a></li>
                        <li class="breadcrumb-item active text-truncate" aria-current="page"> อัพโหลดรูปโปรไฟล์ </li>
                    </ol>
                </nav>
            </div>
        </div>


        <div class="row">
            <div class="col-md-12">
                <div class="card card-box borderR10 mb-2 mb-md-0">
                    <div class="card-body">
                        <h4 class="card-title">อัพโหลดรูปโปรไฟล์</h4>
                        <hr>

                        <div class="row ">

                            <div class="col-md-5 mb-4">
                                <form action="{{route('update_img_profile')}}" method="POST" enctype="multipart/form-data" id="addemployee">
                                    @csrf
                                  <div class="card text-center">
                                    <div class="card-header p-5">
                                        @if(Auth::guard('c_user')->user()->profile_img)

                                        <img class="img-fluid" width="300" id="div-image" src="{{asset('local/public/profile_customer/'.Auth::guard('c_user')->user()->profile_img)}}" alt="card-img">
                                        @else

                                         <img class="img-fluid" width="300" id="div-image" src="{{ asset('frontend/images/man.png') }}" alt="card-img">
                                        @endif
                                    </div>
                                    <div class="card-body">
                                      <h5 class="card-title">อัพโหลดรูปโปรไฟล์</h5>
                                      <input type="file" name="img" class="input-image" id="img" style="display: none;">
                                      <input type="hidden" name="imgBase64" value="">

                                      <button type="button" class="btn btn-success rounded-pill"  onclick="document.getElementById('img').click()" >เลือกรูปภาพ</button>

                                      <button type="submit" id="upload" class="btn btn-primary rounded-pill">อัพโหลดรูปภาพ</button>
                                    </div>

                                  </div>
                                </form>

                            </div>


                            <div class="col-md-6 mb-4">
                                <div class="card text-center" style="display: none;" id="div-crop">
                                    <div class="card-header">
                                        <div id="image_demo" class="col-md-12"></div>

                                    </div>



                                   <div class="card-body">

                                    <button type="button"  class="btn btn-success crop_image rounded-pill"><i class="fa fa-image m-r-5"></i> Upload</button>
                                  </div>
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
<script  src="{{asset('frontend/js/croppie.js')}}"></script>
<script>
    // $( ".datepicker" ).datepicker({ dateFormat: 'dd/mm/yy' });
    // Add Img Profile preview
    document.getElementById("upload").disabled = true;

    function addImgProfilePreview() {
        var $preview = $('#addpreviewProfile').empty();
        if (this.files) $.each(this.files, readAndPreview);
        function readAndPreview(i, file) {
            if (!/\.(jpe?g|png|gif)$/i.test(file.name)){
                return alert(file.name +" is not an image");
            }
            var reader = new FileReader();
            $(reader).on("load", function() {
                $preview.append($("<img class='img-fluid'>", {src:this.result, height:150}));
            });
            reader.readAsDataURL(file);
        }
    }
    $('#addimgprofile').on("change", addImgProfilePreview);

    // crop รูปภาพ
    $image_crop = $('#image_demo').croppie({
        enableExif: true,
        viewport: {
            width: 300,
            height: 300,
            type: 'circle' //circle
        },
        boundary: {
            width: 300,
            height:300
        }
    });
    $(document).on('change', '.input-image', function () {
        var reader = new FileReader();
        reader.onload = function (event) {
            $image_crop.croppie('bind', {
                url: event.target.result
            }).then(function () {
                console.log('jQuery bind complete');
            });
        }
        reader.readAsDataURL(this.files[0]);
        $('#div-crop').show();
    });

    $('.crop_image').click(function (event) {
        $image_crop.croppie('result', {
            type: 'canvas',
            size: 'viewport'
        }).then(function (response) {
            //$('#div-image').css('background-image','url("' + response + '")');
            $('#div-image').attr('src',response);
            $('input[name="imgBase64"]').val(response);
            $('#div-crop').hide();
            document.getElementById("upload").disabled = false;
        })
    });
</script>

@endsection
