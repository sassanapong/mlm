@extends('layouts.frontend.app_login')
@section('conten')
    <div class="container position-relative">
        <div class="card cardLogin wow fadeIn" wow-data-duration="300ms">
            <div class="card-body">

                    <div class="text-center row">
                        <div class=" col-md-6">
                            <img src="https://cdn.pixabay.com/photo/2017/03/09/12/31/error-2129569__340.jpg" alt=""
                                class="img-fluid">
                        </div>
                        <div class=" col-md-6 mt-5">
                            <p class="fs-3"> <span class="text-danger">Opps!</span> Page not found.</p>
                            <p class="lead">
                                The page you’re looking for doesn’t exist.
                            </p>
                            {{-- <a href="index.html" class="btn btn-primary">Go Home</a> --}}
                        </div>

                    </div>

            </div>
        </div>
    </div>
    <!-- Modal -->

@endsection
