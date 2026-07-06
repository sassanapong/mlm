<title>บริษัท มารวยด้วยกัน จำกัด</title>

@extends('layouts.frontend.app')

@section('conten')
@php
    $order = $orders_detail[0];
    $address = count($order->address) > 0 ? $order->address[0] : null;
    $isPaySo = ($order->payment_gateway ?? null) === 'payso' || ($order->pay_type ?? null) === 'payso';
    $paymentName = $isPaySo ? 'ชำระผ่าน Payment' : 'หักเงิน eWallet';
    $paymentIcon = $isPaySo ? 'fas fa-credit-card' : 'fas fa-wallet';
    $paymentStatus = $isPaySo ? ($order->gateway_status ?: 'pending') : ($order->order_status_id_fk == 5 ? 'paid' : 'pending');
    $paymentStatusText = in_array(strtolower((string) $paymentStatus), ['cp', 'ps', 'paid', 'success', 'complete', 'completed'], true)
        ? 'ชำระสำเร็จ'
        : ($order->order_status_id_fk == 5 ? 'ชำระสำเร็จ' : 'รอตรวจสอบ');
    $paymentStatusClass = $paymentStatusText === 'ชำระสำเร็จ' ? 'success' : 'warning';
    $paymentRef = $isPaySo ? ($order->gateway_transaction_id ?: $order->payso_refno) : $order->code_order;
    $paidAt = $order->paid_at ?: $order->approve_date;
    $typeOrder = $order->type_order === 'pv' ? 'สะสมส่วนตัว' : ($order->type_order === 'hold' ? 'Hold PV' : '-');
@endphp

<style>
    .order-detail-page {
        background: #f6f7fb;
        min-height: 100vh;
    }

    .order-shell {
        max-width: 1180px;
        margin: 0 auto;
    }

    .order-hero {
        background: linear-gradient(135deg, #ffffff 0%, #f9f3ff 48%, #eef8ff 100%);
        border: 1px solid rgba(123, 74, 151, .12);
        border-radius: 18px;
        padding: 22px;
        box-shadow: 0 12px 30px rgba(24, 32, 56, .07);
    }

    .detail-panel {
        background: #fff;
        border: 1px solid rgba(33, 37, 41, .08);
        border-radius: 16px;
        box-shadow: 0 8px 24px rgba(24, 32, 56, .055);
    }

    .soft-label {
        color: #6c757d;
        font-size: 13px;
        margin-bottom: 4px;
    }

    .strong-value {
        color: #202124;
        font-weight: 700;
    }

    .metric-tile {
        border: 1px solid rgba(123, 74, 151, .12);
        border-radius: 14px;
        padding: 14px;
        background: rgba(255, 255, 255, .72);
        height: 100%;
    }

    .product-row {
        display: grid;
        grid-template-columns: 88px minmax(0, 1fr) auto;
        gap: 14px;
        align-items: center;
        padding: 14px 0;
        border-bottom: 1px solid rgba(33, 37, 41, .08);
    }

    .product-row:last-child {
        border-bottom: 0;
    }

    .product-image {
        width: 88px;
        height: 88px;
        border-radius: 14px;
        object-fit: cover;
        background: #f1f3f5;
    }

    .summary-line {
        display: flex;
        justify-content: space-between;
        gap: 16px;
        padding: 8px 0;
        color: #495057;
    }

    .summary-total {
        border-top: 1px solid rgba(33, 37, 41, .1);
        margin-top: 8px;
        padding-top: 14px;
        font-size: 18px;
        font-weight: 800;
    }

    .payment-box {
        border-radius: 16px;
        background: #fbfcff;
        border: 1px solid rgba(13, 110, 253, .12);
    }

    .icon-chip {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: rgba(123, 74, 151, .12);
        color: #7b4a97;
    }

    @media (max-width: 767.98px) {
        .order-hero {
            padding: 18px;
            border-radius: 14px;
        }

        .product-row {
            grid-template-columns: 72px minmax(0, 1fr);
        }

        .product-image {
            width: 72px;
            height: 72px;
        }

        .product-price {
            grid-column: 1 / -1;
            text-align: right;
        }
    }
</style>

<div class="order-detail-page page-content py-3 py-md-4" style="margin-top: 63px;">
    <div class="container-fluid order-shell ">
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">หน้าแรก</a></li>
                <li class="breadcrumb-item"><a href="{{ route('order_history') }}">ประวัติการสั่งซื้อ</a></li>
                <li class="breadcrumb-item active text-truncate" aria-current="page">{{ $order->code_order }}</li>
            </ol>
        </nav>

        <div class="mb-3">
            <a href="{{ route('order_history') }}" class="btn btn-sm rounded-pill btn-outline-dark">
                <i class="fas fa-angle-left me-2"></i>ย้อนกลับ
            </a>
        </div>

        <section class="order-hero mb-3">
            <div class="row g-3 align-items-center">
                <div class="col-lg-7">
                    <div class="soft-label">หมายเลขคำสั่งซื้อ</div>
                    <h3 class="mb-2 fw-bold text-break">{{ $order->code_order }}</h3>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="badge rounded-pill bg-{{ $order->css_class }} px-3 py-2">{{ $order->detail }}</span>
                        <span class="badge rounded-pill bg-light text-dark border px-3 py-2">{{ $typeOrder }}</span>
                        <span class="badge rounded-pill bg-{{ $paymentStatusClass }} bg-opacity-20 text-{{ $paymentStatusClass }} px-3 py-2">{{ $paymentStatusText }}</span>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="row g-2">
                        <div class="col-6">
                            <div class="metric-tile">
                                <div class="soft-label">ยอดสุทธิ</div>
                                <div class="strong-value">{{ number_format($order->total_price, 2) }} บาท</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="metric-tile">
                                <div class="soft-label">PV รวม</div>
                                <div class="strong-value">{{ number_format($order->pv_total, 2) }} PV</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <div class="row g-3">
            <div class="col-lg-8">
                <section class="detail-panel p-3 p-md-4 mb-3">
                    <div class="d-flex align-items-center justify-content-between gap-2 mb-3">
                        <h5 class="mb-0 fw-bold"><i class="fas fa-box-open me-2 text-p1"></i>รายการสินค้า</h5>
                        <span class="text-muted small">{{ count($order->product_detail) }} รายการ</span>
                    </div>

                    @foreach ($order->product_detail as $value)
                        <div class="product-row">
                            <img src="{{ asset($value->img_url . '' . $value->product_img) }}" class="product-image" alt="{{ $value->product_name }}">
                            <div class="min-w-0">
                                <h6 class="mb-1 fw-bold text-break">{{ $value->product_name }}</h6>
                                <p class="mb-2 small text-muted text-break">{{ $value->title }}</p>
                                <div class="d-flex flex-wrap gap-2">
                                    <span class="badge rounded-pill bg-light text-dark border">x {{ number_format($value->amt) }}</span>
                                    <span class="badge rounded-pill bg-primary bg-opacity-10 text-primary">{{ number_format($value->pv, 2) }} PV</span>
                                </div>
                            </div>
                            <div class="product-price text-end">
                                <div class="strong-value">{{ number_format($value->selling_price, 2) }}</div>
                                <div class="small text-muted">บาท/ชิ้น</div>
                            </div>
                        </div>
                    @endforeach
                </section>

                <section class="detail-panel p-3 p-md-4">
                    <h5 class="mb-3 fw-bold"><i class="fas fa-map-marker-alt me-2 text-p1"></i>ที่อยู่จัดส่ง</h5>
                    @if ($address)
                        <div class="strong-value mb-1">{{ $order->name }}</div>
                        @if ($address->tel)
                            <div class="text-muted mb-2">โทร. {{ $address->tel }}</div>
                        @endif
                        <p class="mb-0 text-break">
                            @if ($address->house_no) {{ $address->house_no }} @endif
                            @if ($address->moo != '-' && $address->moo != '') หมู่ {{ $address->moo }} @endif
                            @if ($address->house_name != '-' && $address->house_name != '') บ้าน {{ $address->house_name }} @endif
                            @if ($address->soi != '-' && $address->soi != '') ซอย {{ $address->soi }} @endif
                            @if ($address->road != '-' && $address->road != '') ถนน {{ $address->road }} @endif
                            @if ($address->tambon != '-' && $address->tambon != '') ต.{{ $address->tambon }} @endif
                            @if ($address->district != '-' && $address->district != '') อ.{{ $address->district }} @endif
                            @if ($address->province != '-' && $address->province != '') จ.{{ $address->province }} @endif
                            @if ($address->zipcode) {{ $address->zipcode }} @endif
                        </p>
                    @else
                        <p class="mb-0 text-muted">ยังไม่มีข้อมูลที่อยู่จัดส่ง</p>
                    @endif
                </section>
            </div>

            <div class="col-lg-4">
                <section class="detail-panel p-3 p-md-4 mb-3">
                    <h5 class="mb-3 fw-bold"><i class="fas fa-receipt me-2 text-p1"></i>สรุปยอด</h5>
                    <div class="summary-line">
                        <span>ราคาสินค้า</span>
                        <strong>{{ number_format($order->sum_price, 2) }} บาท</strong>
                    </div>
                    <div class="summary-line">
                        <span>ค่าจัดส่ง</span>
                        <strong>{{ number_format($order->shipping_price, 2) }} บาท</strong>
                    </div>
                    <div class="summary-line">
                        <span>ประเภทจัดส่ง</span>
                        <strong class="text-end">{{ $order->shipping_cost_name ?: '-' }}</strong>
                    </div>
                    {{-- <div class="summary-line">
                        <span>ส่วนลด {{ $order->position }} {{ number_format($order->bonus_percent, 2) }}%</span>
                        <strong>{{ number_format($order->discount, 2) }} บาท</strong>
                    </div> --}}
                    <div class="summary-line">
                        <span>PV รวม</span>
                        <strong>{{ number_format($order->pv_total, 2) }} PV</strong>
                    </div>
                    <div class="summary-line summary-total">
                        <span>ยอดสุทธิ</span>
                        <span class="text-p1">{{ number_format($order->total_price, 2) }} บาท</span>
                    </div>
                </section>

                <section class="detail-panel p-3 p-md-4 mb-3">
                    <h5 class="mb-3 fw-bold"><i class="{{ $paymentIcon }} me-2 text-p1"></i>วิธีการชำระเงิน</h5>
                    <div class="payment-box p-3">
                        <div class="d-flex align-items-start gap-3">
                            <span class="icon-chip"><i class="{{ $paymentIcon }}"></i></span>
                            <div class="min-w-0">
                                <div class="strong-value">{{ $paymentName }}</div>
                                <div class="small text-muted">สถานะ: {{ $paymentStatusText }}</div>
                                @if ($paymentRef)
                                    <div class="small text-muted text-break">อ้างอิง: {{ $paymentRef }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </section>

                <section class="detail-panel p-3 p-md-4">
                    <h5 class="mb-3 fw-bold"><i class="fas fa-info-circle me-2 text-p1"></i>ข้อมูลคำสั่งซื้อ</h5>
                    <div class="summary-line">
                        <span>ขนส่ง</span>
                        <strong>{{ $order->tracking_type ?: '-' }}</strong>
                    </div>
                    <div class="summary-line">
                        <span>เลขจัดส่ง</span>
                        <strong class="text-end text-break">{{ $order->tracking_no ?: '-' }}</strong>
                    </div>
                    <div class="summary-line">
                        <span>เวลาสั่งซื้อ</span>
                        <strong class="text-end">{{ $order->created_at ?: '-' }}</strong>
                    </div>
                    <div class="summary-line">
                        <span>เวลาชำระเงิน</span>
                        <strong class="text-end">{{ $paidAt ?: '-' }}</strong>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    $('#linkMenuTop .nav-item').eq(1).addClass('active');
    $('.page-content').css({
        'min-height': $(window).height() - $('.navbar').height()
    });
</script>
@endsection
