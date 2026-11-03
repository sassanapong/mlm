@extends('layouts.frontend.app')

@section('conten')
    <div class="bg-whiteLight page-content">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">หน้าแรก</a></li>
                    <li class="breadcrumb-item active" aria-current="page">คัดกรองสมาชิก 1 Code 1 World</li>
                </ol>
            </nav>
            <div class="row justify-content-center">
                <div class="col-lg-7">
                    <div class="card borderR25 p-4 mb-5">
                        @if ($status === 'approved')
                            <h5>คัดกรองสมาชิก 1 Code 1 World</h5>
                            <div class="alert alert-success mt-3 mb-0">ยืนยันตัวตนเรียบร้อยแล้ว</div>
                        @elseif ($status === 'pending')
                            <h5>คัดกรองสมาชิก 1 Code 1 World</h5>
                            <div class="alert alert-info mt-3 mb-0">ส่งข้อมูลแล้ว อยู่ระหว่างรอเจ้าหน้าที่ตรวจสอบ</div>
                        @else
                            <div class="alert alert-warning fs-14">
                                ตามข้อกำหนด สคบ. และ ปปง. กรุณายืนยันตัวตนภายใน {{ config('kyc.grace_days') }} วัน
                                @if ($deadline)
                                    (ภายใน {{ $deadline->format('d/m/Y H:i') }})
                                @endif
                                หากไม่ดำเนินการ ระบบจะระงับรหัสสมาชิก โบนัสจะไม่จ่ายเข้าระบบ และคะแนน PV ที่เกิดขึ้นจะไม่ไหลเข้ารหัส
                            </div>
                            @if ($note)
                                <div class="alert alert-danger fs-14">ข้อมูลก่อนหน้าไม่ผ่านการตรวจสอบ: {{ $note }}</div>
                            @endif
                            <form id="form_kyc" method="post" enctype="multipart/form-data">
                    @csrf
                    <div>
                        <small class="text-danger kyc_all_err _kyc_err"></small>

                        <div class="mb-3">
                            <label class="form-label">เลขบัตรประชาชน 13 หลัก</label>
                            <small class="fs-12 text-danger kyc_id_card_err _kyc_err"></small>
                            <input type="text" class="form-control" name="kyc_id_card" maxlength="13" inputmode="numeric">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">แนบรูปบัตรประชาชน</label>
                            <small class="fs-12 text-danger kyc_file_card_err _kyc_err"></small>
                            <input type="file" class="form-control" name="kyc_file_card" accept="image/jpeg,image/png">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">ธนาคารรับโบนัส</label>
                            <small class="fs-12 text-danger kyc_bank_name_err _kyc_err"></small>
                            <select class="form-select" name="kyc_bank_name">
                                <option value="">เลือกธนาคาร</option>
                                @foreach ($banks as $b)
                                    <option value="{{ $b->id }}">{{ $b->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">หมายเลขบัญชีธนาคาร</label>
                            <small class="fs-12 text-danger kyc_bank_no_err _kyc_err"></small>
                            <input type="text" class="form-control" name="kyc_bank_no" inputmode="numeric">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">แนบรูปหน้าบัญชี</label>
                            <small class="fs-12 text-danger kyc_file_bank_err _kyc_err"></small>
                            <input type="file" class="form-control" name="kyc_file_bank" accept="image/jpeg,image/png">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">เบอร์โทรศัพท์</label>
                            <small class="fs-12 text-danger kyc_phone_err _kyc_err"></small>
                            <input type="text" class="form-control" name="kyc_phone" maxlength="10" inputmode="numeric"
                                value="{{ $user->phone ?? '' }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">OTP ยืนยันเบอร์โทร</label>
                            <small class="fs-12 text-danger kyc_otp_err _kyc_err"></small>
                            <div class="input-group">
                                <input type="text" class="form-control" name="kyc_otp" maxlength="6" inputmode="numeric"
                                    placeholder="เลข 6 หลัก">
                                <button type="button" class="btn btn-outline-dark" id="btn_kyc_otp">ขอ OTP</button>
                            </div>
                            <small class="text-success" id="kyc_otp_mock"></small>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="kyc_accept" value="1" id="kycAccept">
                            <label class="form-check-label" for="kycAccept">
                                ข้าพเจ้ายอมรับข้อตกลงและยืนยันว่าข้อมูลข้างต้นเป็นความจริง
                            </label>
                            <small class="fs-12 text-danger kyc_accept_err _kyc_err d-block"></small>
                        </div>
                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-p1 rounded-pill">ยืนยันตัวตน</button>
                    </div>
                </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    @if ($status === 'none' || $status === 'rejected')
    <script>
        // OTP (mock): แสดงเลขที่สุ่มได้บนหน้าจอ รอต่อ SMS จริง
        $('#btn_kyc_otp').click(function() {
            var $b = $(this).prop('disabled', true);
            $.post('{{ route('kyc_send_otp') }}', {
                _token: '{{ csrf_token() }}',
                kyc_phone: $('[name=kyc_phone]').val()
            }).done(function(data) {
                $('.kyc_phone_err').text('');
                if (data.status == 'success') {
                    $('#kyc_otp_mock').text('OTP (ทดสอบ): ' + data.mock_otp + ' (หมดอายุใน 5 นาที)');
                } else {
                    $.each(data.error, function(k, v) { $('.' + k + '_err').text(v[0]); });
                }
            }).always(function() {
                setTimeout(function() { $b.prop('disabled', false); }, 3000);
            });
        });
        $('#form_kyc').submit(function(e) {
            e.preventDefault();
            var $btn = $(this).find('button[type=submit]').prop('disabled', true);
            $.ajax({
                url: '{{ route('kyc_submit') }}',
                method: 'POST',
                data: new FormData(this),
                processData: false,
                contentType: false,
                success: function(data) {
                    $('._kyc_err').text('');
                    if (data.status == 'success') {
                        alert('ส่งข้อมูลยืนยันตัวตนเรียบร้อย สามารถใช้งานระบบได้ตามปกติ');
                        window.location.href = '{{ route('home') }}';
                        return;
                    }
                    $.each(data.error, function(k, v) {
                        $('.' + k + '_err').text(v[0]);
                    });
                    $btn.prop('disabled', false);
                },
                error: function() {
                    alert('เกิดข้อผิดพลาด กรุณาลองใหม่');
                    $btn.prop('disabled', false);
                }
            });
        });
    </script>
    @endif
@endsection
