@extends('layouts.frontend.app_registerurl')
@section('conten')
    <div class="container">
        <div class="row text-center" style="margin-top: 100px">
            <div class="col-lg-3">

            </div><!-- /.col-lg-4 -->
            <div class="col-lg-6">

                    <div class="rounded-circle">
               <img src="{{ asset('frontend/images/man.png') }}" style="width: 35%">
                    </div>

              <h2 class="fw-normal mt-2" id="name">Heading</h2>
              <h5>Business Name : <span id="business_name"></span></h5>
              <h5>Username : <span id="Username"></span></h5>
              <h5>PassWord : <span id="PassWord"></span></h5>
              <p><a class="btn btn-success mt-2" href="{{route('login')}}">Go Login »</a></p>
            </div><!-- /.col-lg-4 -->
            <div class="col-lg-3">

            </div><!-- /.col-lg-4 -->
          </div>
    </div>
    <script>
        var urlParams = new URLSearchParams(window.location.search);
        var encodedArray = urlParams.get("data");

        // ถอดรหัสสตริงและแปลงเป็น Array
        var decodedArray = JSON.parse(decodeURIComponent(encodedArray));

// ตอนนี้คุณมีค่า Array ในตัวแปร decodedArray
        console.log(decodedArray);
         $('#name').html(decodedArray[0]);
         $('#business_name').html(decodedArray[1]);
         $('#Username').html(decodedArray[2]);
         $('#PassWord').html(decodedArray[3]);

    </script>
    @endsection



