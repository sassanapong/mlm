@extends('layouts.backend.app')



@section('head')
@endsection

@section('css')
@endsection

@section('conten')
    @include('backend.navbar.navbar_mobile')
    <div class="flex overflow-hidden">

        @include('backend.navbar.navbar')
        <div class="content">
            @include('backend.navbar.top_bar')
        </div>
    </div>
@endsection



@section('script')
@endsection
