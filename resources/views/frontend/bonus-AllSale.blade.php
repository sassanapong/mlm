<title>บริษัท มารวยด้วยกัน จำกัด</title>

@extends('layouts.frontend.app')

@section('conten')
    <style>
        .all-sale-page {
            background: #f4f7fb;
            min-height: 100vh;
            padding-bottom: 35px;
        }

        .all-sale-hero {

            background: linear-gradient(135deg, #d96aef 0%, #d35df1 55%, #7d08b0 100%);
            border-radius: 18px;
            padding: 24px;
            color: #ffffff;
            box-shadow: 0 10px 28px rgba(15, 23, 42, .18);
            position: relative;
            overflow: hidden;
        }

        .all-sale-hero:after {
            content: "";
            position: absolute;
            right: -80px;
            top: -80px;
            width: 220px;
            height: 220px;
            background: rgba(255, 255, 255, .08);
            border-radius: 50%;
        }

        .all-sale-hero h3 {
            font-weight: 800;
            margin-bottom: 8px;
        }

        .all-sale-hero .desc {
            color: rgb(255, 255, 255);
            font-size: 15px;
        }

        .report-card {
            border: 0;
            border-radius: 18px;
            box-shadow: 0 8px 24px rgba(15, 23, 42, .07);
        }

        .summary-box {
            border: 0;
            border-radius: 18px;
            box-shadow: 0 8px 24px rgba(15, 23, 42, .07);
            height: 100%;
        }

        .summary-label {
            color: #64748b;
            font-size: 14px;
            margin-bottom: 6px;
        }

        .summary-value {
            font-size: 26px;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.15;
        }

        .summary-sub {
            font-size: 13px;
            color: #94a3b8;
            margin-top: 5px;
        }

        .section-title {
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 0;
        }

        .condition-strip {
            border: 1px dashed #cbd5e1;
            background: #f8fafc;
            border-radius: 14px;
            padding: 14px 16px;
            color: #0f172a;
        }

        .condition-strip strong {
            color: #dc2626;
        }

        .rate-table th {
            background: #f59e0b !important;
            color: #111827;
            font-weight: 800;
            font-size: 18px;
        }

        .rate-table td {
            font-weight: 700;
            font-size: 17px;
        }

        .rate-table tbody tr:nth-child(even) td {
            background: #e5e7eb;
        }

        .simple-box {
            border: 2px solid #1d4ed8;
            border-radius: 16px;
            overflow: hidden;
            background: #ffffff;
        }

        .simple-box-title {
            background: #1d4ed8;
            color: #ffffff;
            padding: 10px 16px;
            font-weight: 800;
            font-size: 18px;
        }

        .simple-box-body {
            padding: 16px;
        }

        .simple-item {
            display: flex;
            gap: 10px;
            margin-bottom: 12px;
            color: #0f172a;
        }

        .simple-icon {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: #16a34a;
            color: #ffffff;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            font-size: 14px;
            flex: 0 0 24px;
        }

        .flow-box {
            border-radius: 16px;
            border: 1px solid #dbeafe;
            background: linear-gradient(180deg, #eff6ff 0%, #ffffff 100%);
            padding: 16px;
        }

        .flow-step {
            text-align: center;
            color: #0f172a;
            font-size: 14px;
        }

        .flow-step .icon {
            width: 58px;
            height: 58px;
            border-radius: 50%;
            background: #dbeafe;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            color: #1d4ed8;
            margin-bottom: 8px;
        }

        .flow-arrow {
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            color: #2563eb;
            font-weight: 800;
        }

        .g1-table thead th {
            background: #0f172a;
            color: #ffffff;
            text-align: center;
            white-space: nowrap;
            vertical-align: middle;
        }

        .g1-table td {
            vertical-align: middle;
            white-space: nowrap;
        }

        .active-red {
            background: #ef4444;
            color: #ffffff;
            font-weight: 700;
            text-align: center;
        }

        .active-green {
            background: #8bd34f;
            color: #0f172a;
            font-weight: 700;
            text-align: center;
        }

        .pv-link {
            color: #0070c0;
            font-weight: 800;
            text-decoration: underline;
        }

        .total-highlight {
            background: #fff7ed;
            color: #c2410c;
            font-weight: 800;
        }

        .btn-rounded {
            border-radius: 999px;
            padding-left: 18px;
            padding-right: 18px;
        }

        .preview-status {
            border: 1px solid #dbeafe;
            background: #eff6ff;
            color: #1e3a8a;
            border-radius: 16px;
            padding: 14px 16px;
            font-size: 14px;
        }

        .preview-status.warning {
            border-color: #fde68a;
            background: #fffbeb;
            color: #92400e;
        }

        .preview-status.danger {
            border-color: #fecaca;
            background: #fef2f2;
            color: #991b1b;
        }

        @media (max-width: 767px) {
            .all-sale-hero {
                padding: 20px;
            }

            .summary-value {
                font-size: 22px;
            }

            .flow-arrow {
                transform: rotate(90deg);
                margin: 5px 0;
            }
        }

        .all-sale-status {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 20px;
            border-radius: 16px;
            border-left: 6px solid;
        }

        .all-sale-status .icon {
            font-size: 48px;
            line-height: 1;
        }

        .all-sale-status h5 {
            font-weight: 700;
            margin-bottom: 5px;
        }

        /* ผ่าน */
        .all-sale-status.success {
            background: #ecfdf5;
            border-color: #10b981;
            color: #065f46;
        }

        /* ไม่ผ่าน */
        .all-sale-status.danger {
            background: #fef2f2;
            border-color: #ef4444;
            color: #991b1b;
        }

        /* ใกล้ถึง */
        .all-sale-status.warning {
            background: #fffbeb;
            border-color: #f59e0b;
            color: #92400e;
        }


        .my-row {
            background: linear-gradient(90deg,
                    rgba(59, 130, 246, .08),
                    rgba(59, 130, 246, .02)) !important;
        }

        .my-row td {
            border-top: 2px solid #3b82f6 !important;
            border-bottom: 2px solid #3b82f6 !important;
            vertical-align: middle;
        }

        .my-row:hover {
            background: linear-gradient(90deg,
                    rgba(59, 130, 246, .12),
                    rgba(59, 130, 246, .04)) !important;
        }

        .my-row .pv-link {
            font-size: 18px;
            color: #2563eb;
        }

        .my-row td:first-child {
            border-left: 4px solid #2563eb !important;
        }
    </style>

    <div class="all-sale-page page-content">
        <div class="container-fluid">
            <div class="row pt-3">
                <div class="col-lg-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-3">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">หน้าแรก</a></li>
                            <li class="breadcrumb-item active text-truncate" aria-current="page">โบนัส All Sale</li>
                        </ol>
                    </nav>
                </div>
            </div>

            @php
                use Carbon\Carbon;

                $today = Carbon::now();
                $day = (int) $today->format('d');

                if ($day <= 15) {
                    $allSaleRoundNo = 1;
                    $allSaleRoundText = 'รอบที่ 1';
                    $allSalePeriodText = 'วันที่ 1 - 15';
                    $allSaleStartDate = $today->copy()->startOfMonth();
                    $allSaleEndDate = $today->copy()->startOfMonth()->addDays(14);
                } else {
                    $allSaleRoundNo = 2;
                    $allSaleRoundText = 'รอบที่ 2';
                    $allSalePeriodText = 'วันที่ 16 - สิ้นเดือน';
                    $allSaleStartDate = $today->copy()->startOfMonth()->addDays(15);
                    $allSaleEndDate = $today->copy()->endOfMonth();
                }

                $allSaleDateRangeText = $allSaleStartDate->format('d/m/Y') . ' - ' . $allSaleEndDate->format('d/m/Y');
            @endphp

            <div class="all-sale-hero mb-3">
    <div class="row align-items-center position-relative">
        <div class="col-lg-8">
            <h3>
                วางแผนสร้างรายได้ All Sale รับโบนัส 2 รอบต่อเดือน
            </h3>

            <div class="desc">
                ตอนนี้คุณอยู่ในรอบสะสมยอด All Sale {{ $allSaleRoundText }}
                ช่วง {{ $allSalePeriodText }} ของเดือน
                <br>
                ระยะเวลารอบนี้: {{ $allSaleDateRangeText }}
            </div>
        </div>
    </div>
</div>

            @if (!$previewTableReady)
                <div class="preview-status danger mb-3">
                    ยังไม่พบตารางคาดการณ์ All Sale กรุณารัน migration ก่อนใช้งาน cron preview
                </div>
            @elseif (!$previewRun)
                <div class="preview-status warning mb-3">
                    ยังไม่มีข้อมูลคาดการณ์ของรอบนี้ กรุณารัน cron ประมวลผล preview ก่อน
                </div>
            @elseif ($previewRun->status !== 'success')
                <div class="preview-status warning mb-3">
                    ระบบกำลังทยอยประมวลผลข้อมูลคาดการณ์รอบนี้ สถานะปัจจุบัน: {{ $previewRun->status }}
                    @if ($previewUpdatedAt)
                        | อัปเดตล่าสุด {{ $previewUpdatedAt }}
                    @endif
                </div>
            @else
                <div class="preview-status mb-3">
                    ข้อมูลคาดการณ์รอบนี้ประมวลผลสำเร็จแล้ว
                    @if ($previewUpdatedAt)
                        | อัปเดตล่าสุด {{ $previewUpdatedAt }}
                    @endif
                </div>
            @endif
            @php
                $bonusQualifications = ['VVIP', 'STAR', 'MDK_STAR', 'XVVIP', 'SVVIP', 'MG', 'MR', 'ME', 'MD'];

                $user = $upline ?? Auth::guard('c_user')->user();

                $qualificationName = $customers->qualification_name ?? '-';

                $hasBonusQualification = in_array($user->qualification_id ?? null, $bonusQualifications);

                $isActiveSuperBonus =
                    !empty($user->expire_date_bonus) && strtotime($user->expire_date_bonus) >= strtotime(date('Y-m-d'));

                $isPassAllSaleBonus = $hasBonusQualification && $isActiveSuperBonus;

                $missingConditions = [];

                if (!$hasBonusQualification) {
                    $missingConditions[] =
                        'ตำแหน่งปัจจุบันของคุณคือ ' .
                        $qualificationName .
                        ' ซึ่งยังไม่อยู่ในกลุ่มตำแหน่งที่มีสิทธิ์รับโบนัส All Sale';
                }

                if (!$isActiveSuperBonus) {
                    $missingConditions[] = 'สถานะ SuperBonus ของคุณยังไม่ Active หรือหมดอายุแล้ว';
                }

                $activeBonusText = !empty($user->expire_date_bonus)
                    ? date('d/m/Y', strtotime($user->expire_date_bonus))
                    : null;
            @endphp

            @if ($isPassAllSaleBonus)
                <div class="all-sale-status success mb-3">
                    <div class="icon">
                        <i class="bx bx-check-circle"></i>
                    </div>

                    <div class="content">
                        <h5>คุณได้รับสิทธิ์โบนัส All Sale แล้ว</h5>
                        <p class="mb-0">
                            ยอดเยี่ยมมาก! ตำแหน่งปัจจุบันของคุณคือ {{ $qualificationName }}
                            และสถานะ SuperBonus ยัง Active อยู่
                            @if ($activeBonusText)
                                ถึงวันที่ {{ $activeBonusText }}
                            @endif
                            คุณจึงมีสิทธิ์เข้าร่วมรับโบนัส All Sale องค์กรตามเงื่อนไขที่กำหนด
                        </p>
                    </div>
                </div>
            @else
                <div class="all-sale-status danger mb-3">
                    <div class="icon">
                        <i class="bx bx-x-circle"></i>
                    </div>

                    <div class="content">
                        <h5>ยังไม่ได้รับสิทธิ์โบนัส All Sale</h5>

                        <p class="mb-2">
                            ขณะนี้คุณยังไม่ผ่านเงื่อนไขการรับโบนัส All Sale องค์กร
                            โดยมีเงื่อนไขที่ยังขาดดังนี้
                        </p>

                        <ul class="mb-0 pl-3">
                            @foreach ($missingConditions as $condition)
                                <li>{{ $condition }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <div class="row g-3 mb-3">
                <div class="col-md-3">
                    <div class="card summary-box">
                        <div class="card-body">
                            <div class="summary-label">PV รวมองค์กร</div>
                            <div class="summary-value text-primary">{{ number_format($previewTotalPv, 2) }}</div>
                            <div class="summary-sub">ยอดสะสมปัจจุบัน</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card summary-box">
                        <div class="card-body">
                            <div class="summary-label">รับ All Sale ปัจจุบัน</div>
                            <div class="summary-value text-success">{{ $previewRate }}%</div>
                            <div class="summary-sub">ตามตารางโบนัส</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card summary-box">
                        <div class="card-body">
                            <div class="summary-label">ทำเพิ่มอีก</div>
                            <div class="summary-value text-danger">{{ number_format($previewPvToNextRate, 2) }} PV</div>
                            <div class="summary-sub">เพื่อไปขั้นถัดไป</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card summary-box">
                        <div class="card-body">
                            <div class="summary-label">เป้าหมายถัดไป</div>
                            <div class="summary-value text-warning">{{ $previewNextRate }}%</div>
                            <div class="summary-sub">ขั้นโบนัสถัดไป</div>
                        </div>
                    </div>
                </div>
            </div>



            <div class="card report-card mb-3">
                <div class="card-body">
                    <h5 class="section-title mb-3">เงื่อนไขการรับโบนัส All Sale</h5>

                    <div class="condition-strip mb-3">
                        คุณมี All Sale สะสม <strong>{{ number_format($AllSaleTotal, 0) }} บาท</strong>
                        สะสม PV เพิ่มอีก <strong>{{ number_format($previewPvToNextRate, 2) }} PV</strong>
                        เพื่อไปยังโบนัสขั้นถัดไป <strong>{{ $previewNextRate }}%</strong><br>
                        รับรางวัลความสำเร็จ + ประกาศเกียรติคุณ ONE TIME
                        <strong>5,000 + ฉลองทาสกินเนอร์หรู 2 ที่นั่ง</strong>
                    </div>

                    <div class="row g-3">
                        <div class="col-lg-5">
                            <div class="table-responsive">
                                <table class="table table-bordered text-center align-middle rate-table mb-0">
                                    <thead>
                                        <tr>
                                            <th>PV รวม</th>
                                            <th>รับโบนัส</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1,500</td>
                                            <td>3%</td>
                                        </tr>
                                        <tr>
                                            <td>10,000</td>
                                            <td>6%</td>
                                        </tr>
                                        <tr>
                                            <td>20,000</td>
                                            <td>9%</td>
                                        </tr>
                                        <tr>
                                            <td>30,000</td>
                                            <td>12%</td>
                                        </tr>
                                        <tr>
                                            <td>50,000</td>
                                            <td>15%</td>
                                        </tr>
                                        <tr>
                                            <td>80,000</td>
                                            <td>18%</td>
                                        </tr>
                                        <tr>
                                            <td>100,000</td>
                                            <td>21%</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="col-lg-7">
                            <div class="simple-box mb-3">
                                <div class="simple-box-title">สรุปง่ายๆ</div>
                                <div class="simple-box-body">
                                    <div class="simple-item">
                                        <span class="simple-icon"><i class="bx bx-check"></i></span>
                                        <span>ยอดรวมองค์กรยิ่งสูง % โบนัสยิ่งสูง</span>
                                    </div>
                                    <div class="simple-item">
                                        <span class="simple-icon"><i class="bx bx-check"></i></span>
                                        <span>ทุกคนจะได้รับโบนัสจาก <b class="text-danger">“ส่วนต่าง”</b></span>
                                    </div>
                                    <div class="simple-item">
                                        <span class="simple-icon"><i class="bx bx-check"></i></span>
                                        <span>สร้างทีม สอนทีม ช่วยกันเติบโต = รายได้ยั่งยืน</span>
                                    </div>
                                    <div class="alert alert-danger text-center fw-bold mb-0 mt-2">
                                        ช่วยคนอื่นสำเร็จ องค์กรเราจะโตไปด้วยกัน
                                    </div>
                                </div>
                            </div>

                            <div class="flow-box">
                                <div class="row g-2 align-items-center">
                                    <div class="col-md flow-step">
                                        <div class="icon"><i class="bx bx-globe"></i></div>
                                        <div>รวมยอด PV<br>ของทั้งองค์กร</div>
                                    </div>
                                    <div class="col-md-auto flow-arrow">→</div>
                                    <div class="col-md flow-step">
                                        <div class="icon"><i class="bx bx-coin-stack"></i></div>
                                        <div>เทียบ % รับโบนัส<br>ตามตาราง</div>
                                    </div>
                                    <div class="col-md-auto flow-arrow">→</div>
                                    <div class="col-md flow-step">
                                        <div class="icon"><i class="bx bx-network-chart"></i></div>
                                        <div>จ่ายโบนัสให้ภายใน<br>องค์กรก่อนเสมอ</div>
                                    </div>
                                    <div class="col-md-auto flow-arrow">→</div>
                                    <div class="col-md flow-step">
                                        <div class="icon"><i class="bx bx-money"></i></div>
                                        <div>จ่ายส่วนต่าง<br>ให้แก่ตัวเรา</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            {{-- <div class="card report-card mb-3">
                <div class="card-body">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-2">
                            <label class="form-label">ปี</label>
                            <select class="form-select" name="year">
                                <option value="">ทั้งหมด</option>
                                <option value="2026">2026</option>
                                <option value="2025">2025</option>
                                <option value="2024">2024</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">เดือน</label>
                            <select class="form-select" name="month">
                                <option value="">ทั้งหมด</option>
                                <option value="01">มกราคม</option>
                                <option value="02">กุมภาพันธ์</option>
                                <option value="03">มีนาคม</option>
                                <option value="04">เมษายน</option>
                                <option value="05">พฤษภาคม</option>
                                <option value="06">มิถุนายน</option>
                                <option value="07">กรกฎาคม</option>
                                <option value="08">สิงหาคม</option>
                                <option value="09">กันยายน</option>
                                <option value="10">ตุลาคม</option>
                                <option value="11">พฤศจิกายน</option>
                                <option value="12">ธันวาคม</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">คำค้นหา</label>
                            <input type="text" class="form-control" name="keyword" placeholder="รหัสสมาชิก / ชื่อ / ตำแหน่ง">
                        </div>
                        <div class="col-md-3 d-flex gap-2">
                            <button type="button" class="btn btn-dark btn-rounded">
                                <i class="bx bx-search me-1"></i> ค้นหา
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-rounded">รีเซต</button>
                        </div>
                    </div>
                </div>
            </div> --}}

            <div class="card report-card">
                <div class="card-body">
                    <div class="row align-items-center mb-3">
                        <div class="col-md-6">
                            <h5 class="section-title">แสดงยอด PV องค์กร ชั้น G1</h5>
                            <div class="text-muted small">ลูกแนะนำตรง</div>
                        </div>
                        <div class="col-md-6 text-md-end mt-2 mt-md-0">
                            <span class="badge bg-primary rounded-pill px-3 py-2">ข้อมูลวันที่ {{ $previewUpdatedAt }}</span>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover g1-table mb-0">
                            <thead>
                                <tr>
                                    <th>ลำดับ</th>
                                    <th>รหัสสมาชิก</th>
                                    <th>ตำแหน่ง</th>
                                    <th>ชื่อ</th>
                                    <th>แอคทีฟ</th>
                                    <th>PV รวม</th>
                                    <th>รับ All Sale</th>
                                    <th>ทำเพิ่ม PV อีก</th>
                                    <th>รับ All Sale เป็น</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="my-row">
                                    <td class="text-center">
                                        <span class="badge bg-primary px-3 py-2">
                                            <i class="bx bxs-user"></i> ฉัน
                                        </span>
                                    </td>

                                    <td>
                                        <strong>{{ Auth::guard('c_user')->user()->user_name }}</strong>
                                    </td>

                                    <td>
                                        <span class="badge bg-danger">
                                            {{$customers->qualification_name}}
                                        </span>
                                    </td>

                                    <td>
                                          {{ Auth::guard('c_user')->user()->name }}  {{ Auth::guard('c_user')->user()->last_name }}
                                    </td>

                                    @php
                                        $expireDateBonus = Auth::guard('c_user')->user()->expire_date_bonus;

                                        $isBonusExpired = empty($expireDateBonus) || strtotime($expireDateBonus) < strtotime(date('Y-m-d'));

                                        $bonusDateClass = $isBonusExpired ? 'badge bg-danger' : 'badge bg-success';

                                        $bonusDateText = !empty($expireDateBonus)
                                            ? date('d/m/Y', strtotime($expireDateBonus))
                                            : 'ยังไม่ได้ต่ออายุ';
                                    @endphp

                                    <td class="text-center">
                                        <span class="{{ $bonusDateClass }}">
                                            {{ $bonusDateText }}
                                        </span>
                                    </td>

                                    <td class="text-end">
                                        <span class="pv-link fw-bold">
                                            {{ number_format($previewSelf->organization_pv ?? 0, 2) }}
                                        </span>
                                    </td>

                                    <td class="text-center">
                                        <span class="badge bg-success">
                                            {{ $previewRate }}%
                                        </span>
                                    </td>

                                    <td class="text-end text-danger fw-bold">
                                        {{ number_format($previewPvToNextRate, 2) }}
                                    </td>

                                    <td class="text-center">
                                        <span class="badge bg-warning text-dark">
                                            เป้าหมาย {{ $previewNextRate }}%
                                        </span>
                                    </td>
                                </tr>
                                @forelse ($previewDetails as $index => $row)
                                    @php
                                        $g1IsExpired = empty($row->g1_expire_date_bonus) || strtotime($row->g1_expire_date_bonus) < strtotime(date('Y-m-d'));
                                        $g1BonusDateClass = $g1IsExpired ? 'badge bg-danger' : 'badge bg-success';
                                        $g1BonusDateText = !empty($row->g1_expire_date_bonus)
                                            ? date('d/m/Y', strtotime($row->g1_expire_date_bonus))
                                            : 'ยังไม่ได้ต่ออายุ';
                                    @endphp
                                    <tr>
                                        <td class="text-center">{{ $index + 2 }}</td>
                                        <td>{{ $row->g1_user_name }}</td>
                                        <td>{{ $row->g1_qualification }}</td>
                                        <td>{{ $row->g1_name }}</td>
                                        <td class="text-center">
                                            <span class="{{ $g1BonusDateClass }}">{{ $g1BonusDateText }}</span>
                                        </td>
                                        <td class="text-end"><span class="pv-link">{{ number_format($row->organization_pv, 2) }}</span></td>
                                        <td class="text-center"></td>
                                        <td class="text-end"></td>
                                        <td class="text-center"></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center text-muted py-4">
                                            ยังไม่มีข้อมูล G1 จาก snapshot รอบนี้
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr class="total-highlight">
                                    <td colspan="5" class="text-end">รวมทั้งหมด</td>
                                    <td class="text-end">{{ number_format($previewTotalPv, 2) }}</td>
                                    <td class="text-center">{{ $previewRate }}%</td>
                                    <td class="text-end">{{ number_format($previewPvToNextRate, 2) }}</td>
                                    <td class="text-center">{{ $previewNextRate }}%</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $('.page-content').css({
            'min-height': $(window).height() - $('.navbar').height()
        });
    </script>
@endsection
