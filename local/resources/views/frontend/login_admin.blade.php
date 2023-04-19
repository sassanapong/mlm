@extends('layouts.frontend.app_login_admin')
@section('conten')
    <div class="container position-relative">
        <div class="card cardLogin wow fadeIn" wow-data-duration="300ms">
            <div class="card-body">

                <div class="text-center">
                    <img src="{{ asset('frontend/images/logo.png') }}" class="mw-100 mb-4" width="150px">
                </div>
                <form action="{{route('admin_login')}}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <input type="text" class="form-control" name="username" id="exampleInputEmail1"
                            placeholder="เบอร์โทรศัพท์หรืออีเมล์">
                    </div>
                    <div class="mb-3">
                        <input type="password" class="form-control" name="password" id="exampleInputPassword1"
                            placeholder="รหัสผ่าน">
                        {{-- <div class="text-center">
                            <button type="button" class="btn btn-link text-black" data-bs-toggle="modal"
                                data-bs-target="#forgotModal">ลืมรหัสผ่าน?</button>
                        </div> --}}
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-p1 rounded-pill px-4">เข้าสู่ระบบ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="forgotModal" tabindex="-1" aria-labelledby="forgotModalLabel" aria-hidden="true">
        <div class="modal-dialog  modal-dialog-centered">
            <div class="modal-content borderR25">
                <div class="modal-header">
                    <h5 class="modal-title text-center" id="forgotModalLabel">ลืมรหัสผ่านเข้าระบบ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <p class="text-secondary">กรอกเบอร์โทรศัพท์ ที่ทำการลงทะเบียนไว้<br>เพื่อรับรหัสผ่านใหม่</p>
                    <input type="text" class="form-control text-center my-5" placeholder="กรอกเบอร์โทรศัพท์">
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-p1 rounded-pill">ตรวจสอบข้อมูล</button>
                </div>
            </div>
        </div>
    </div>
@endsection
