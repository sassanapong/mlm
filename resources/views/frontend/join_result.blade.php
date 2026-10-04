@extends('layouts.frontend.app_registerurl')

@section('css')
    <style>
        body { background: #f5f3fa; }
        .result-wrap { max-width: 520px; margin: 0 auto; padding: 100px 12px 40px; }
        .result-card { background: #fff; border-radius: 12px; padding: 28px 22px; box-shadow: 0 2px 10px rgba(0, 0, 0, .06); text-align: center; }
        .result-card .icon { font-size: 3rem; }
        .kv { text-align: left; margin-top: 14px; }
        .kv div { display: flex; justify-content: space-between; border-bottom: 1px solid #eee; padding: 6px 0; }
    </style>
@endsection

@section('conten')
    <div class="result-wrap">
        <div class="result-card" id="box">
            @if (!$app)
                <div class="icon">ℹ️</div>
                <h5 class="mt-2">ได้รับข้อมูลแล้ว</h5>
                <p class="text-muted">หากชำระเงินสำเร็จ ระบบจะสร้างรหัสสมาชิกให้ภายในไม่กี่นาที<br>กรุณาติดต่อผู้แนะนำเพื่อตรวจสอบสถานะ</p>
                <a class="btn btn-primary" href="{{ url('/') }}">ไปหน้าเข้าสู่ระบบ</a>
            @else
                <div id="state_wait">
                    <div class="spinner-border text-primary" role="status"></div>
                    <h5 class="mt-3">กำลังตรวจสอบการชำระเงิน...</h5>
                    <p class="text-muted small">กรุณาอย่าปิดหน้านี้</p>
                </div>
                <div id="state_ok" class="d-none">
                    <div class="icon">✅</div>
                    <h5 class="mt-2">สมัครสมาชิกสำเร็จ</h5>
                    <div class="kv">
                        <div><span>ชื่อ-สกุล</span><b id="r_name"></b></div>
                        <div><span>ชื่อทางธุรกิจ</span><b id="r_biz"></b></div>
                        <div><span>รหัสสมาชิก (Username)</span><b id="r_user"></b></div>
                        <div><span>รหัสผ่าน</span><b>เลข 4 ตัวท้ายของบัตรประชาชน</b></div>
                        <div><span>ตำแหน่ง</span><b id="r_pos"></b></div>
                        <div><span>PV ที่ได้รับ</span><b id="r_pv"></b></div>
                        <div><span>เลขที่ออเดอร์</span><b id="r_order"></b></div>
                    </div>
                    <a class="btn btn-primary mt-4" href="{{ url('/') }}">เข้าสู่ระบบ</a>
                </div>
                <div id="state_slip" class="d-none">
                    <div class="icon">🕒</div>
                    <h5 class="mt-2">ส่งใบสมัครและสลิปเรียบร้อยแล้ว</h5>
                    <p class="text-muted">เจ้าหน้าที่จะตรวจสอบสลิป เมื่ออนุมัติแล้วระบบจะสร้างรหัสสมาชิกให้<br>เปิดหน้านี้อีกครั้งเพื่อตรวจสอบสถานะ</p>
                    <a class="btn btn-outline-primary" href="">ตรวจสอบสถานะ</a>
                </div>
                <div id="state_fail" class="d-none">
                    <div class="icon">❌</div>
                    <h5 class="mt-2">ชำระเงินไม่สำเร็จ</h5>
                    <p class="text-muted">ยังไม่ได้สร้างสมาชิก กรุณาทำรายการสมัครใหม่อีกครั้ง</p>
                    <a class="btn btn-primary" href="{{ url()->previous() }}">สมัครอีกครั้ง</a>
                </div>
                <div id="state_error" class="d-none">
                    <div class="icon">⚠️</div>
                    <h5 class="mt-2">ได้รับชำระเงินแล้ว แต่ระบบสร้างสมาชิกไม่สำเร็จ</h5>
                    <p class="text-muted">เจ้าหน้าที่จะตรวจสอบและติดต่อกลับ กรุณาเก็บหลักฐานการชำระเงินไว้</p>
                </div>
            @endif
        </div>
    </div>
@endsection

@section('script')
    @if ($app)
        <script>
            var tries = 0;
            function poll() {
                $.getJSON('{{ route('join.status', ['token' => $app->token]) }}', function(r) {
                    if (r.status === 'paid') {
                        $('#r_name').text(r.full_name);
                        $('#r_biz').text(r.business_name);
                        $('#r_user').text(r.user_name);
                        $('#r_pos').text(r.position);
                        $('#r_pv').text(Number(r.pv_total).toLocaleString());
                        $('#r_order').text(r.code_order);
                        $('#state_wait').addClass('d-none');
                        $('#state_ok').removeClass('d-none');
                        return;
                    }
                    if (r.status === 'slip_pending') {
                        $('#state_wait').addClass('d-none');
                        $('#state_slip').removeClass('d-none');
                        return;
                    }
                    if (r.status === 'failed') {
                        $('#state_wait').addClass('d-none');
                        $('#state_fail').removeClass('d-none');
                        return;
                    }
                    if (r.status === 'error') {
                        $('#state_wait').addClass('d-none');
                        $('#state_error').removeClass('d-none');
                        return;
                    }
                    if (++tries < 60) setTimeout(poll, 3000);
                    else $('#state_wait').html('<h5>ยังไม่ได้รับการยืนยันการชำระเงิน</h5><p class="text-muted small">หากชำระแล้ว รหัสสมาชิกจะถูกสร้างภายในไม่กี่นาที กรุณาติดต่อผู้แนะนำ</p>');
                });
            }
            poll();
        </script>
    @endif
@endsection
