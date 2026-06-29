@extends('layouts.frontend.app')

@php
    $positions = [
        [
            'code' => 'MC',
            'name' => 'MC',
            'pv' => 'สมัครสมาชิก',
            'bonus' => 'ยังไม่ได้รับโบนัส',
            'detail' => 'สมัครแล้วได้รับตำแหน่งนี้ทันที',
            'accent' => '#8c96a8',
            'tier' => 'เริ่มต้น',
        ],
        [
            'code' => 'MB',
            'name' => 'MB',
            'pv' => '30 PV',
            'bonus' => 'ปลดตำแหน่งอัตโนมัติ',
            'detail' => 'ทำการแจง PV อัพตำแหน่งถึง 30 PV จะปรับเป็นตำแหน่งนี้ทันที',
            'accent' => '#16a085',
            'tier' => 'Foundation',
        ],
        [
            'code' => 'MO',
            'name' => 'MO',
            'pv' => '1,000 PV',
            'bonus' => 'ปลดตำแหน่งอัตโนมัติ',
            'detail' => 'ทำการแจง PV อัพตำแหน่งถึง 1,000 PV จะปรับเป็นตำแหน่งนี้ทันที',
            'accent' => '#1f8efa',
            'tier' => 'Growth',
        ],
        [
            'code' => 'VIP',
            'name' => 'VIP',
            'pv' => '2,000 PV',
            'bonus' => 'ปลดตำแหน่งอัตโนมัติ',
            'detail' => 'ทำการแจง PV อัพตำแหน่งถึง 2,000 PV จะปรับเป็นตำแหน่งนี้ทันที',
            'accent' => '#845ef7',
            'tier' => 'Elite',
        ],
        [
            'code' => 'VVIP',
            'name' => 'VVIP',
            'pv' => '3,000 PV',
            'bonus' => 'ปลดตำแหน่งอัตโนมัติ',
            'detail' => 'ทำการแจง PV อัพตำแหน่งถึง 3,000 PV จะปรับเป็นตำแหน่งนี้ทันที',
            'accent' => '#b45cff',
            'tier' => 'Elite',
        ],
        [
            'code' => 'STAR',
            'name' => 'STAR',
            'pv' => '3,000 PV',
            'bonus' => 'ขาอ่อน 1,000,000 PV',
            'detail' => 'ทำการแจง PV อัพตำแหน่งถึง 3,000 PV และมีคะแนนฝั่งขาอ่อน 1,000,000 PV',
            'accent' => '#f4a100',
            'tier' => 'Leadership',
        ],
        [
            'code' => 'MDK STAR',
            'name' => 'MDK STAR',
            'pv' => '3,000 PV',
            'bonus' => 'STAR ซ้าย 1 ขวา 1',
            'detail' => 'ทำการแจง PV อัพตำแหน่งถึง 3,000 PV สร้าง STAR ซ้าย 1 ขวา 1 และมีคะแนนฝั่งขาอ่อน 2,000,000 PV',
            'accent' => '#ff7a45',
            'tier' => 'Leadership',
        ],
        [
            'code' => 'MG',
            'name' => 'MG',
            'pv' => '3,500 PV',
            'bonus' => 'All Sale 25,000',
            'detail' => 'ทำการแจง PV อัพตำแหน่งถึง 3,500 PV และมีโบนัส All Sale 25,000',
            'accent' => '#d4a017',
            'tier' => 'Executive',
        ],
        [
            'code' => 'ME',
            'name' => 'ME',
            'pv' => '4,000 PV',
            'bonus' => 'All Sale 62,000',
            'detail' => 'ทำการแจง PV อัพตำแหน่งถึง 4,000 PV และมีโบนัส All Sale 62,000',
            'accent' => '#14b886',
            'tier' => 'Executive',
        ],
        [
            'code' => 'MR',
            'name' => 'MR',
            'pv' => '5,000 PV',
            'bonus' => 'All Sale 292,000',
            'detail' => 'ทำการแจง PV อัพตำแหน่งถึง 5,000 PV และมีโบนัส All Sale 292,000',
            'accent' => '#e03131',
            'tier' => 'Executive',
        ],
        [
            'code' => 'MD',
            'name' => 'MD',
            'pv' => '6,000 PV',
            'bonus' => 'All Sale 552,000',
            'detail' => 'ทำการแจง PV อัพตำแหน่งถึง 6,000 PV และมีโบนัส All Sale 552,000',
            'accent' => '#364fc7',
            'tier' => 'Director',
        ],
        [
            'code' => 'MDD.',
            'name' => 'MDD.',
            'pv' => '6,000 PV',
            'bonus' => 'All Sale 902,000',
            'detail' => 'ทำการแจง PV อัพตำแหน่งถึง 6,000 PV และมีโบนัส All Sale 902,000',
            'accent' => '#5f3dc4',
            'tier' => 'Director',
        ],
        [
            'code' => 'MCD.',
            'name' => 'MCD.',
            'pv' => '6,000 PV',
            'bonus' => 'All Sale 1,502,000',
            'detail' => 'ทำการแจง PV อัพตำแหน่งถึง 6,000 PV และมีโบนัส All Sale 1,502,000',
            'accent' => '#9c36b5',
            'tier' => 'Crown',
        ],
        [
            'code' => 'MCK.',
            'name' => 'MCK.',
            'pv' => '6,000 PV',
            'bonus' => 'All Sale 2,412,000',
            'detail' => 'ทำการแจง PV อัพตำแหน่งถึง 6,000 PV และมีโบนัส All Sale 2,412,000',
            'accent' => '#c92a2a',
            'tier' => 'Crown',
        ],
    ];

    $topPosition = end($positions);
    $currentUser = Auth::guard('c_user')->user();
    $currentQualificationCode = $currentUser->qualification_id ?? '-';
    $currentQualificationName = optional(optional($currentUser)->qualification)->business_qualifications ?? $currentQualificationCode;
    $currentQualificationDisplay = in_array($currentQualificationName, ['MDD', 'MCD', 'MCK'])
        ? $currentQualificationName . '.'
        : str_replace('_', ' ', $currentQualificationName);
    $currentPositionKey = rtrim(str_replace('_', ' ', $currentQualificationCode), '.');
@endphp

@section('css')
    <style>
        .position-page {
            min-height: calc(100vh - 72px);
            background:
                radial-gradient(circle at 12% 8%, rgba(255, 255, 255, .95) 0, rgba(255, 255, 255, 0) 22rem),
                linear-gradient(135deg, #f7f2e8 0%, #f7f9fd 45%, #eef4ff 100%);
            color: #1d2433;
            padding: 24px 0 48px;
        }

        .position-page .breadcrumb {
            background: transparent;
            padding-left: 0;
            margin-bottom: 18px;
            font-size: 14px;
        }

        .position-page .breadcrumb a {
            color: #7a4f14;
            font-weight: 600;
        }

        .position-hero {
            position: relative;
            overflow: hidden;
            border-radius: 8px;
            background:
                linear-gradient(135deg, rgba(21, 28, 43, .96), rgba(61, 39, 15, .9)),
                url('{{ asset('frontend/images/money-2724241_1920.jpg') }}') center/cover;
            color: #fff;
            padding: clamp(28px, 5vw, 56px);
            box-shadow: 0 24px 60px rgba(31, 26, 17, .18);
        }

        .position-hero::after {
            content: "";
            position: absolute;
            right: -110px;
            top: -150px;
            width: 360px;
            height: 360px;
            border: 1px solid rgba(255, 255, 255, .24);
            transform: rotate(22deg);
        }

        .position-hero__content {
            position: relative;
            z-index: 1;
            max-width: 760px;
        }

        .position-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 7px 12px;
            border: 1px solid rgba(255, 255, 255, .22);
            background: rgba(255, 255, 255, .11);
            border-radius: 999px;
            color: #f8ddb4;
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 0;
            margin-bottom: 16px;
        }

        .position-title {
            margin: 0;
            font-size: clamp(32px, 5vw, 58px);
            line-height: 1.08;
            font-weight: 800;
            letter-spacing: 0;
        }

        .position-lead {
            margin: 18px 0 0;
            max-width: 640px;
            color: rgba(255, 255, 255, .82);
            font-size: clamp(16px, 2vw, 19px);
            line-height: 1.8;
        }

        .hero-stats {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 14px;
            margin-top: 30px;
            max-width: 760px;
        }

        .hero-stat {
            border: 1px solid rgba(255, 255, 255, .16);
            background: rgba(255, 255, 255, .1);
            border-radius: 8px;
            padding: 16px;
            backdrop-filter: blur(10px);
        }

        .hero-stat strong {
            display: block;
            color: #f8ddb4;
            font-size: clamp(24px, 3vw, 34px);
            line-height: 1;
            margin-bottom: 7px;
        }

        .hero-stat span {
            display: block;
            color: rgba(255, 255, 255, .78);
            font-size: 13px;
        }

        .current-position-panel {
            display: grid;
            grid-template-columns: minmax(0, 1.25fr) minmax(220px, .75fr);
            gap: 16px;
            margin-top: 18px;
            border: 1px solid rgba(31, 36, 51, .08);
            background: rgba(255, 255, 255, .92);
            border-radius: 8px;
            box-shadow: 0 18px 42px rgba(41, 34, 22, .08);
            padding: clamp(18px, 3vw, 26px);
        }

        .current-position-copy {
            display: flex;
            gap: 16px;
            align-items: center;
        }

        .current-position-icon {
            display: inline-flex;
            width: 58px;
            height: 58px;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            background: #1f2635;
            color: #f8ddb4;
            font-size: 30px;
            flex: 0 0 58px;
        }

        .current-position-label {
            margin: 0 0 4px;
            color: #788296;
            font-size: 13px;
            font-weight: 700;
        }

        .current-position-title {
            margin: 0;
            color: #1e2533;
            font-size: clamp(24px, 4vw, 40px);
            font-weight: 800;
            line-height: 1.1;
        }

        .current-position-user {
            margin: 8px 0 0;
            color: #596476;
            line-height: 1.6;
        }

        .current-position-stats {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 10px;
        }

        .current-position-stat {
            border-radius: 8px;
            background: #f6f8fb;
            padding: 14px;
        }

        .current-position-stat span {
            display: block;
            color: #788296;
            font-size: 12px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .current-position-stat strong {
            display: block;
            color: #1e2533;
            font-size: 18px;
            line-height: 1.25;
            word-break: break-word;
        }

        .position-section {
            margin-top: 28px;
        }

        .section-heading {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            gap: 16px;
            margin-bottom: 16px;
        }

        .section-heading h2 {
            margin: 0;
            font-size: clamp(22px, 3vw, 32px);
            font-weight: 800;
            color: #1e2533;
        }

        .section-heading p {
            margin: 6px 0 0;
            color: #697386;
            line-height: 1.7;
        }

        .position-map {
            display: grid;
            grid-template-columns: repeat(7, minmax(120px, 1fr));
            gap: 10px;
            overflow-x: auto;
            padding-bottom: 8px;
        }

        .position-node {
            min-width: 120px;
            background: rgba(255, 255, 255, .78);
            border: 1px solid rgba(31, 36, 51, .08);
            border-radius: 8px;
            padding: 14px;
            box-shadow: 0 12px 32px rgba(31, 36, 51, .08);
        }

        .position-node.is-current {
            border-color: var(--rank-color);
            background: #ffffff;
            box-shadow: 0 16px 36px color-mix(in srgb, var(--rank-color) 18%, rgba(31, 36, 51, .08));
        }

        .position-node span {
            display: block;
            width: 34px;
            height: 4px;
            background: var(--rank-color);
            border-radius: 999px;
            margin-bottom: 10px;
        }

        .position-node strong {
            display: block;
            color: #1e2533;
            font-size: 18px;
            white-space: nowrap;
        }

        .position-node small {
            color: #788296;
            font-weight: 600;
        }

        .rank-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 16px;
        }

        .rank-card {
            position: relative;
            overflow: hidden;
            min-height: 228px;
            border: 1px solid rgba(31, 36, 51, .08);
            background: rgba(255, 255, 255, .9);
            border-radius: 8px;
            box-shadow: 0 18px 42px rgba(41, 34, 22, .09);
            padding: 20px;
        }

        .rank-card::before {
            content: "";
            position: absolute;
            inset: 0 0 auto 0;
            height: 5px;
            background: var(--rank-color);
        }

        .rank-card__top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 12px;
            margin-bottom: 20px;
        }

        .rank-badge {
            display: inline-flex;
            min-width: 58px;
            min-height: 42px;
            align-items: center;
            justify-content: center;
            padding: 9px 12px;
            border-radius: 8px;
            background: color-mix(in srgb, var(--rank-color) 14%, white);
            color: var(--rank-color);
            font-size: 18px;
            font-weight: 800;
            line-height: 1.1;
        }

        .rank-tier {
            border-radius: 999px;
            background: #f4f6f9;
            color: #6d7688;
            padding: 6px 10px;
            font-size: 12px;
            font-weight: 700;
            white-space: nowrap;
        }

        .rank-card h3 {
            margin: 0 0 14px;
            color: #1e2533;
            font-size: 20px;
            font-weight: 800;
            line-height: 1.25;
        }

        .rank-metrics {
            display: grid;
            grid-template-columns: 1fr;
            gap: 8px;
            margin-bottom: 16px;
        }

        .rank-metric {
            display: flex;
            align-items: center;
            gap: 9px;
            color: #3b4353;
            font-size: 14px;
            line-height: 1.45;
        }

        .rank-metric i {
            color: var(--rank-color);
            font-size: 18px;
        }

        .rank-card p {
            margin: 0;
            color: #697386;
            font-size: 14px;
            line-height: 1.75;
        }

        .comparison-wrap {
            overflow: hidden;
            border: 1px solid rgba(31, 36, 51, .08);
            background: rgba(255, 255, 255, .92);
            border-radius: 8px;
            box-shadow: 0 18px 42px rgba(41, 34, 22, .08);
        }

        .comparison-table {
            margin: 0;
            min-width: 850px;
        }

        .comparison-table thead th {
            background: #1f2635;
            color: #fff;
            border: 0;
            padding: 16px 18px;
            font-size: 14px;
            white-space: nowrap;
        }

        .comparison-table tbody td {
            border-color: #edf0f4;
            padding: 16px 18px;
            vertical-align: middle;
            color: #4b5567;
            line-height: 1.6;
        }

        .comparison-table tbody tr:hover {
            background: #fff8ed;
        }

        .comparison-code {
            display: inline-flex;
            align-items: center;
            gap: 9px;
            color: #1e2533;
            font-weight: 800;
            white-space: nowrap;
        }

        .comparison-code::before {
            content: "";
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: var(--rank-color);
            box-shadow: 0 0 0 5px color-mix(in srgb, var(--rank-color) 16%, transparent);
        }

        @media (max-width: 767.98px) {
            .comparison-wrap {
                background: transparent;
                border: 0;
                box-shadow: none;
                overflow: visible;
            }

            .comparison-table {
                min-width: 0;
                border-collapse: separate;
                border-spacing: 0 12px;
            }

            .comparison-table thead {
                display: none;
            }

            .comparison-table,
            .comparison-table tbody,
            .comparison-table tr,
            .comparison-table td {
                display: block;
                width: 100%;
            }

            .comparison-table tbody tr {
                border: 1px solid rgba(31, 36, 51, .08);
                border-radius: 8px;
                background: rgba(255, 255, 255, .94);
                box-shadow: 0 14px 32px rgba(41, 34, 22, .08);
                overflow: hidden;
            }

            .comparison-table tbody tr:hover {
                background: rgba(255, 255, 255, .94);
            }

            .comparison-table tbody td {
                display: grid;
                grid-template-columns: 118px minmax(0, 1fr);
                gap: 12px;
                border: 0;
                border-bottom: 1px solid #edf0f4;
                padding: 13px 15px;
            }

            .comparison-table tbody td:last-child {
                border-bottom: 0;
            }

            .comparison-table tbody td::before {
                content: attr(data-label);
                color: #788296;
                font-size: 12px;
                font-weight: 800;
                line-height: 1.6;
            }
        }

        .note-band {
            display: flex;
            align-items: center;
            gap: 14px;
            border: 1px solid rgba(126, 87, 32, .16);
            border-radius: 8px;
            background: linear-gradient(135deg, #fff9ec, #ffffff);
            padding: 18px 20px;
            color: #5b4522;
            line-height: 1.75;
        }

        .note-band i {
            color: #b87514;
            font-size: 28px;
        }

        @media (max-width: 1199.98px) {
            .rank-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }

        @media (max-width: 991.98px) {
            .current-position-panel,
            .hero-stats,
            .rank-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .current-position-copy {
                grid-column: 1 / -1;
            }

            .position-map {
                grid-template-columns: repeat(14, 124px);
            }
        }

        @media (max-width: 575.98px) {
            .position-page {
                padding-top: 14px;
            }

            .position-hero {
                padding: 26px 20px;
            }

            .current-position-panel,
            .current-position-stats,
            .hero-stats,
            .rank-grid {
                grid-template-columns: 1fr;
            }

            .current-position-copy {
                align-items: flex-start;
            }

            .section-heading {
                display: block;
            }

            .rank-card {
                min-height: auto;
            }

            .note-band {
                align-items: flex-start;
            }
        }
    </style>
@endsection

@section('conten')
    <main class="position-page page-content">
        <div class="container-fluid">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">หน้าแรก</a></li>
                    <li class="breadcrumb-item active text-truncate" aria-current="page">เลื่อนตำแหน่ง</li>
                </ol>
            </nav>

            <section class="position-hero">
                <div class="position-hero__content">
                    <div class="position-eyebrow">
                        <i class='bx bxs-crown'></i>
                        Upgrade Position
                    </div>
                    <h1 class="position-title">เส้นทางการเติบโตตำแหน่งสมาชิก</h1>
                    <p class="position-lead">
                        รวมเงื่อนไขการขึ้นตำแหน่งตั้งแต่ MC ถึง {{ $topPosition['code'] }} เพื่อให้เห็นเป้าหมาย PV,
                        คะแนนทีม และโบนัส All Sale ที่ต้องทำให้ครบในแต่ละระดับอย่างชัดเจน
                    </p>

                    <div class="hero-stats">
                        <div class="hero-stat">
                            <strong>{{ count($positions) }}</strong>
                            <span>ระดับตำแหน่งทั้งหมด</span>
                        </div>
                        <div class="hero-stat">
                            <strong>6,000</strong>
                            <span>PV สูงสุดสำหรับสาย Director</span>
                        </div>
                        <div class="hero-stat">
                            <strong>2.41M</strong>
                            <span>โบนัส All Sale สูงสุด</span>
                        </div>
                    </div>
                </div>
            </section>

            <section class="current-position-panel" aria-label="ตำแหน่งปัจจุบันของสมาชิก">
                <div class="current-position-copy">
                    <div class="current-position-icon">
                        <i class='bx bxs-user-badge'></i>
                    </div>
                    <div>
                        <p class="current-position-label">ตำแหน่งปัจจุบันของคุณ</p>
                        <h2 class="current-position-title">{{ $currentQualificationDisplay }}</h2>
                        <p class="current-position-user">
                            {{ $currentUser->user_name ?? '-' }}
                            @if (!empty($currentUser->name) || !empty($currentUser->last_name))
                                | {{ $currentUser->name }} {{ $currentUser->last_name }}
                            @endif
                        </p>
                    </div>
                </div>
                <div class="current-position-stats">
                    <div class="current-position-stat">
                        <span>PV อัพตำแหน่ง</span>
                        <strong>{{ number_format($currentUser->pv_upgrad ?? 0) }} PV</strong>
                    </div>
                    <div class="current-position-stat">
                        <span>รหัสตำแหน่งในระบบ</span>
                        <strong>{{ $currentQualificationCode }}</strong>
                    </div>
                </div>
            </section>

            <section class="position-section">
                <div class="section-heading">
                    <div>
                        <h2>ลำดับตำแหน่ง</h2>
                        <p>ดูภาพรวมการไต่ระดับแบบรวดเร็ว เรียงตามขั้นของตำแหน่งสมาชิก</p>
                    </div>
                </div>

                <div class="position-map">
                    @foreach ($positions as $position)
                        <div class="position-node {{ rtrim($position['code'], '.') == $currentPositionKey ? 'is-current' : '' }}" style="--rank-color: {{ $position['accent'] }}">
                            <span></span>
                            <strong>{{ $position['code'] }}</strong>
                            <small>{{ $position['pv'] }}</small>
                        </div>
                    @endforeach
                </div>
            </section>

            <section class="position-section">
                <div class="section-heading">
                    <div>
                        <h2>เงื่อนไขรายตำแหน่ง</h2>
                        <p>แยกข้อมูลสำคัญของแต่ละระดับ พร้อมเป้าหมาย PV และเงื่อนไขเสริมที่ต้องทำให้ครบ</p>
                    </div>
                </div>

                <div class="rank-grid">
                    @foreach ($positions as $position)
                        <article class="rank-card" style="--rank-color: {{ $position['accent'] }}">
                            <div class="rank-card__top">
                                <div class="rank-badge">{{ $position['code'] }}</div>
                                <div class="rank-tier">{{ $position['tier'] }}</div>
                            </div>
                            <h3>{{ $position['name'] }}</h3>
                            <div class="rank-metrics">
                                <div class="rank-metric">
                                    <i class='bx bx-trending-up'></i>
                                    <span>{{ $position['pv'] }}</span>
                                </div>
                                <div class="rank-metric">
                                    <i class='bx bx-medal'></i>
                                    <span>{{ $position['bonus'] }}</span>
                                </div>
                            </div>
                            <p>{{ $position['detail'] }}</p>
                        </article>
                    @endforeach
                </div>
            </section>

            <section class="position-section">
                <div class="section-heading">
                    <div>
                        <h2>ตารางเทียบเงื่อนไข</h2>
                        <p>เหมาะสำหรับตรวจสอบตัวเลขและรายละเอียดทั้งหมดในมุมมองเดียว</p>
                    </div>
                </div>

                <div class="comparison-wrap table-responsive">
                    <table class="table comparison-table">
                        <thead>
                            <tr>
                                <th>ตำแหน่ง</th>
                                <th>PV / การสมัคร</th>
                                <th>เงื่อนไขสำคัญ</th>
                                <th>รายละเอียด</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($positions as $position)
                                <tr style="--rank-color: {{ $position['accent'] }}">
                                    <td data-label="ตำแหน่ง"><span class="comparison-code">{{ $position['code'] }}</span></td>
                                    <td data-label="PV / การสมัคร">{{ $position['pv'] }}</td>
                                    <td data-label="เงื่อนไขสำคัญ">{{ $position['bonus'] }}</td>
                                    <td data-label="รายละเอียด">{{ $position['detail'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>

            <section class="position-section">
                <div class="note-band">
                    <i class='bx bxs-info-circle'></i>
                    <div>
                        <strong>หมายเหตุ:</strong>
                        หน้านี้เป็นสรุปเงื่อนไขการขึ้นตำแหน่งจากข้อมูลที่กำหนดไว้
                        หากมีการเปลี่ยนแปลงกติกาในระบบ ควรตรวจสอบตัวเลข PV และโบนัส All Sale ให้ตรงกับประกาศล่าสุดก่อนใช้งานจริง
                    </div>
                </div>
            </section>
        </div>
    </main>
@endsection

@section('script')
    <script>
        $('.page-content').css({
            'min-height': $(window).height() - $('.navbar').height()
        });
    </script>
@endsection
