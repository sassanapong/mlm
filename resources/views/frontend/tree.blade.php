<?php
use App\Http\Controllers\Frontend\TreeController;
?>
<title>
    บริษัท มารวยด้วยกัน จำกัด</title>

@extends('layouts.frontend.app')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('frontend/css/tree_new.css?v=1') }}">

    <style>
        .tree-page-modern { background: linear-gradient(180deg, #f4f7fb 0%, #ffffff 62%); min-height: 100vh; }
        .tree-shell { border: 0; border-radius: 24px; overflow: hidden; box-shadow: 0 18px 45px rgba(15, 23, 42, .08); }
        .tree-hero { position: relative; overflow: hidden; border-radius: 22px; padding: 22px; background: linear-gradient(135deg, #0f766e 0%, #16a34a 48%, #2563eb 100%); color: #fff; }
        .tree-hero:before { content: ""; position: absolute; inset: -90px -40px auto auto; width: 260px; height: 260px; border-radius: 50%; background: rgba(255,255,255,.14); }
        .tree-title { position: relative; margin: 0; font-weight: 800; letter-spacing: .2px; }
        .tree-subtitle { position: relative; margin: 6px 0 0; opacity: .9; }
        .tree-toolbar { position: relative; margin-top: 18px; display: grid; grid-template-columns: minmax(240px, 380px) 1fr; gap: 14px; align-items: center; }
        .tree-search { background: rgba(255,255,255,.16); border: 1px solid rgba(255,255,255,.26); border-radius: 16px; padding: 6px; display: flex; gap: 6px; backdrop-filter: blur(8px); }
        .tree-search .form-control { height: 42px; border: 0; border-radius: 12px !important; text-transform: uppercase; font-weight: 700; }
        .tree-search .btn { border-radius: 12px; min-width: 108px; font-weight: 800; }
        .tree-actions { display: flex; flex-wrap: wrap; gap: 8px; justify-content: flex-end; }
        .tree-actions .btn { border-radius: 999px; padding: 9px 14px; font-weight: 800; box-shadow: 0 8px 18px rgba(15,23,42,.12); }
        .tree-actions .btn.disabled, .tree-actions .btn:disabled { opacity: .55; cursor: not-allowed; box-shadow: none; }
        .info-grid { display: grid; grid-template-columns: minmax(0, 1.25fr) minmax(330px, .75fr); gap: 16px; margin-top: 16px; }
        .glass-card { border-radius: 20px; padding: 16px; background: #fff; border: 1px solid #eef2f7; box-shadow: 0 10px 30px rgba(15,23,42,.06); }
        .section-head { display:flex; align-items:center; justify-content:space-between; gap:10px; margin-bottom:12px; }
        .section-head h5 { margin:0; font-weight:800; color:#111827; }
        .section-head small { color:#64748b; }
        .status-legend-grid { display:grid; grid-template-columns: repeat(4, minmax(0,1fr)); gap:10px; }
        .status-item { display:flex; align-items:center; gap:10px; padding:12px; border-radius:18px; border:1px solid transparent; background:#fff; transition: all .18s ease; min-height:76px; }
        .status-item:hover { transform: translateY(-2px); box-shadow: 0 12px 24px rgba(15,23,42,.10); }
        .status-icon { width:42px; height:42px; min-width:42px; border-radius:14px; display:flex; align-items:center; justify-content:center; font-size:24px; }
        .status-name { font-weight:800; color:#111827; line-height:1.1; }
        .status-desc { color:#64748b; font-size:12px; margin-top:4px; }
        .status-active { background:linear-gradient(135deg,#ecfdf5,#fff); border-color:#bbf7d0; } .status-active .status-icon { background:#16a34a; color:#fff; }
        .status-warning { background:linear-gradient(135deg,#fff7ed,#fff); border-color:#fed7aa; } .status-warning .status-icon { background:#f59e0b; color:#111827; }
        .status-danger { background:linear-gradient(135deg,#fef2f2,#fff); border-color:#fecaca; } .status-danger .status-icon { background:#dc2626; color:#fff; }
        .status-cancel { background:linear-gradient(135deg,#f8fafc,#fff); border-color:#cbd5e1; } .status-cancel .status-icon { background:#64748b; color:#fff; }
        .pv-card { height:100%; background: linear-gradient(135deg, #111827 0%, #1e293b 100%); color:#fff; border-radius:20px; padding:16px; box-shadow:0 14px 32px rgba(15,23,42,.18); }
        .pv-card-header { display:flex; justify-content:space-between; align-items:flex-start; gap:10px; margin-bottom:12px; }
        .pv-card h5 { margin:0; font-weight:800; color:#fff; }
        .pv-date { color:#cbd5e1; font-size:13px; margin-top:4px; }
        .pv-history-btn { border-radius:999px; font-weight:800; white-space:nowrap; }
        .pv-grid { display:grid; grid-template-columns:1fr 1fr; gap:10px; }
        .pv-side { border:1px solid rgba(255,255,255,.14); border-radius:18px; padding:12px; background:rgba(255,255,255,.07); }
        .pv-side-title { display:flex; align-items:center; gap:7px; font-weight:800; margin-bottom:10px; }
        .pv-row { display:flex; justify-content:space-between; align-items:center; padding:8px 0; border-top:1px dashed rgba(255,255,255,.14); color:#e5e7eb; }
        .pv-row:first-of-type { border-top:0; }
        .pv-value { font-weight:900; font-size:16px; color:#fff; }
        .pv-total { margin-top:8px; padding:10px; border-radius:14px; background:rgba(34,197,94,.18); }
        .tree-board { margin-top:18px; border-radius:22px; padding:18px; background:linear-gradient(180deg,#ffffff,#f8fafc); border:1px solid #eef2f7; box-shadow: inset 0 1px 0 rgba(255,255,255,.9); }
        .tree-board-head { display:flex; align-items:center; justify-content:space-between; gap:12px; margin-bottom:14px; }
        .tree-board-head h5 { margin:0; font-weight:900; color:#0f172a; }
        .tree-hint { color:#64748b; font-size:13px; }
        .genealogy-scroll { overflow:auto; padding-bottom:15px; }
        .genealogy-tree { min-width:920px; padding:8px 0 16px; }
        .member-view-box { transition: transform .18s ease, filter .18s ease; }
        .member-view-box:hover { transform: translateY(-3px); filter: drop-shadow(0 10px 16px rgba(15,23,42,.14)); }
        .member-detailsr h6 { margin-bottom:3px; }

        .member-avatar { position:relative; width:92px; height:92px; margin:0 auto 8px; border-radius:50%; padding:5px; display:flex; align-items:center; justify-content:center; transition: all .2s ease; }
        .member-avatar img { width:100%; height:100%; object-fit:cover; border-radius:50%; border:4px solid #fff; background:#fff; cursor:pointer; box-shadow:0 8px 20px rgba(15,23,42,.16); }
        .member-avatar-active { background:linear-gradient(135deg,#16a34a,#86efac); box-shadow:0 0 0 4px rgba(22,163,74,.10), 0 0 22px rgba(22,163,74,.38); }
        .member-avatar-warning { background:linear-gradient(135deg,#f59e0b,#fde68a); box-shadow:0 0 0 4px rgba(245,158,11,.12), 0 0 22px rgba(245,158,11,.42); animation: memberPulseWarning 1.8s infinite; }
        .member-avatar-danger { background:linear-gradient(135deg,#dc2626,#fca5a5); box-shadow:0 0 0 4px rgba(220,38,38,.10), 0 0 22px rgba(220,38,38,.34); }
        .member-avatar-cancel { background:linear-gradient(135deg,#64748b,#cbd5e1); box-shadow:0 0 0 4px rgba(100,116,139,.12), 0 0 20px rgba(100,116,139,.30); }
        .member-avatar-danger img, .member-avatar-cancel img { filter:grayscale(.45); }
        .member-avatar-empty { background:linear-gradient(135deg,#e2e8f0,#f8fafc); box-shadow:none; }
        .status-dot { position:absolute; right:2px; bottom:8px; width:28px; height:28px; border-radius:50%; border:3px solid #fff; display:flex; align-items:center; justify-content:center; color:#fff; font-size:15px; box-shadow:0 7px 14px rgba(15,23,42,.22); }
        .member-avatar-active .status-dot { background:#16a34a; }
        .member-avatar-warning .status-dot { background:#f59e0b; color:#111827; }
        .member-avatar-danger .status-dot { background:#dc2626; }
        .member-avatar-cancel .status-dot { background:#64748b; }
        @keyframes memberPulseWarning { 0% { box-shadow:0 0 0 4px rgba(245,158,11,.12), 0 0 18px rgba(245,158,11,.32); } 50% { box-shadow:0 0 0 7px rgba(245,158,11,.08), 0 0 28px rgba(245,158,11,.55); } 100% { box-shadow:0 0 0 4px rgba(245,158,11,.12), 0 0 18px rgba(245,158,11,.32); } }
        .tree-modal .modal-content { border:0; border-radius:24px; overflow:hidden; box-shadow:0 24px 60px rgba(15,23,42,.18); }
        .tree-modal-header { padding:22px; background:linear-gradient(135deg,#2563eb,#16a34a); color:#fff; }
        .tree-modal-header h4 { margin:0; font-weight:900; color:#fff; }
        .tree-modal-body { padding:22px; }
        .tree-modal-footer { border:0; padding:0 22px 22px; display:flex; justify-content:space-between; gap:10px; }
        @media (max-width: 991px) { .tree-toolbar, .info-grid { grid-template-columns:1fr; } .tree-actions { justify-content:flex-start; } .status-legend-grid { grid-template-columns:repeat(2,minmax(0,1fr)); } }
        @media (max-width: 575px) { .tree-hero { padding:16px; } .tree-search { flex-direction:column; } .tree-search .btn { width:100%; } .status-legend-grid, .pv-grid { grid-template-columns:1fr; } .glass-card, .pv-card { padding:13px; } }
    </style>
@endsection
@section('conten')
    @php
        $treeMemberStatus = function ($member) {
            if (empty($member)) {
                return 'empty';
            }

            if (($member->status_customer ?? null) != 'normal') {
                return 'cancel';
            }

            if (empty($member->expire_date)) {
                return 'danger';
            }

            $today = strtotime(date('Y-m-d'));
            $expireAt = strtotime($member->expire_date);

            if (!$expireAt || $expireAt < $today) {
                return 'danger';
            }

            $daysLeft = floor(($expireAt - $today) / 86400);

            return $daysLeft <= 5 ? 'warning' : 'active';
        };

        $treeMemberStatusText = function ($member) use ($treeMemberStatus) {
            $status = $treeMemberStatus($member);

            if ($status == 'active') {
                return 'Active';
            }

            if ($status == 'warning') {
                return 'วันใกล้หมด';
            }

            if ($status == 'danger') {
                return 'Non Active';
            }

            if ($status == 'cancel') {
                return 'ตัดออกจากระบบ';
            }

            return 'ช่องว่าง';
        };

        $treeMemberStatusIcon = function ($member) use ($treeMemberStatus) {
            $status = $treeMemberStatus($member);

            if ($status == 'active') {
                return 'las la-check';
            }

            if ($status == 'warning') {
                return 'las la-clock';
            }

            if ($status == 'danger') {
                return 'las la-times';
            }

            if ($status == 'cancel') {
                return 'las la-ban';
            }

            return 'las la-plus';
        };

        $treeMemberAvatar = function ($member) use ($treeMemberStatus) {
            if (!empty($member->profile_img)) {
                return asset('profile_customer/' . $member->profile_img);
            }

            $status = $treeMemberStatus($member);

            if ($status == 'cancel') {
                return asset('frontend/images/profile_cancel.png');
            }

            if ($status == 'danger') {
                return asset('frontend/images/profile_NotActive.png');
            }

            if ($status == 'warning') {
                return asset('frontend/images/profile_wanning.png');
            }

            return asset('frontend/images/profile_Active.png');
        };
    @endphp
    <div class="bg-whiteLight page-content tree-page-modern">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">หน้าแรก</a></li>
                            <li class="breadcrumb-item active text-truncate" aria-current="page"> ข้อมูลสายงาน Upline</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-box borderR10 mb-2 mb-md-0 tree-shell">
                        <div class="card-body">



                            <div class="tree-hero">
                                <h3 class="tree-title"><i class="las la-project-diagram"></i> ข้อมูลสายงาน Upline</h3>
                                <p class="tree-subtitle">ค้นหา ดูสายงาน ตรวจสอบ PV และเพิ่มสมาชิกได้ในหน้าเดียว</p>

                                <div class="tree-toolbar">
                                    <div class="tree-search">
                                        <input type="text" class="form-control" id="search_username" name="search_username"
                                            placeholder="ค้นหา ID สมาชิก" value="{{ old('search_username') }}" autocomplete="off">
                                        <button type="button" class="btn btn-light text-success" onclick="search_user()">
                                            <i class="las la-search"></i> Search
                                        </button>
                                    </div>

                                    <div class="tree-actions">
                                        <a class="btn btn-light text-success" href="{{ route('tree') }}">
                                            <i class="las la-user-circle"></i> You
                                        </a>

                                        @if ($data['lv1']->user_name == Auth::guard('c_user')->user()->user_name || $data['lv1']->upline_id == 'AA')
                                            <button type="button" class="btn btn-light disabled" disabled>
                                                <i class="las la-level-up-alt"></i> Up one step
                                            </button>
                                        @else
                                            <a href="#" onclick="event.preventDefault(); document.getElementById('upline_id').submit();"
                                                class="btn btn-light text-success">
                                                <i class="las la-level-up-alt"></i> Up one step
                                            </a>
                                            <form id="upline_id" action="{{ route('tree') }}" method="POST" class="d-none">
                                                <input type="hidden" name="user_name" value="{{ $data['lv1']->upline_id }}">
                                                @csrf
                                            </form>
                                        @endif

                                        @if (empty($data['lv2_a']) || empty($data['lv3_a_a']))
                                            <button type="button" class="btn btn-light disabled" disabled>
                                                <i class="las la-arrow-down"></i> ดิ่งขาซ้าย
                                            </button>
                                        @else
                                            <a href="#" onclick="event.preventDefault(); document.getElementById('under_a').submit();"
                                                class="btn btn-light text-success">
                                                <i class="las la-arrow-down"></i> ดิ่งขาซ้าย
                                            </a>
                                            <form id="under_a" action="{{ route('under_a') }}" method="POST" class="d-none">
                                                <input type="hidden" name="username" value="{{ $data['lv3_a_a']->user_name }}">
                                                @csrf
                                            </form>
                                        @endif

                                        @if (empty($data['lv2_b']) || empty($data['lv3_b_b']))
                                            <button type="button" class="btn btn-light disabled" disabled>
                                                <i class="las la-arrow-down"></i> ดิ่งขาขวา
                                            </button>
                                        @else
                                            <a href="#" onclick="event.preventDefault(); document.getElementById('under_b').submit();"
                                                class="btn btn-light text-success">
                                                <i class="las la-arrow-down"></i> ดิ่งขาขวา
                                            </a>
                                            <form id="under_b" action="{{ route('under_b') }}" method="POST" class="d-none">
                                                <input type="hidden" name="username" value="{{ $data['lv3_b_b']->user_name }}">
                                                @csrf
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="info-grid">
                                <div class="glass-card">
                                    <div class="section-head">
                                        <div>
                                            <h5><i class="las la-users"></i> สถานะสมาชิก</h5>
                                            <small>ใช้สีและไอคอนเพื่อดูสถานะของสมาชิกในผังได้เร็วขึ้น</small>
                                        </div>
                                    </div>

                                    <div class="status-legend-grid">
                                        <div class="status-item status-active">
                                            <span class="status-icon"><i class="las la-user-check"></i></span>
                                            <div>
                                                <div class="status-name">Active</div>
                                                <div class="status-desc">สมาชิกใช้งานอยู่</div>
                                            </div>
                                        </div>

                                        <div class="status-item status-warning">
                                            <span class="status-icon"><i class="las la-user-clock"></i></span>
                                            <div>
                                                <div class="status-name">วันใกล้หมด</div>
                                                <div class="status-desc">ใกล้หมดอายุ</div>
                                            </div>
                                        </div>

                                        <div class="status-item status-danger">
                                            <span class="status-icon"><i class="las la-user-times"></i></span>
                                            <div>
                                                <div class="status-name">Non Active</div>
                                                <div class="status-desc">หมดอายุแล้ว</div>
                                            </div>
                                        </div>

                                        {{-- <div class="status-item status-cancel">
                                            <span class="status-icon"><i class="las la-user-slash"></i></span>
                                            <div>
                                                <div class="status-name">ตัดออกจากระบบ</div>
                                                <div class="status-desc">ถูกยกเลิก</div>
                                            </div>
                                        </div> --}}
                                    </div>
                                </div>
 
                                @if (!empty($log_pv_per_day_ab_balance_all_now) || !empty($log_pv_per_day_ab_balance_all_old))
                                    @php
                                       if($log_pv_per_day_ab_balance_all_now){
                                        $pvLeftOld = $log_pv_per_day_ab_balance_all_now->pv_a ?? 0;
                                        $pvLeftNew = $log_pv_per_day_ab_balance_all_now->pv_a_old ?? 0;
                                        $pvRightOld = $log_pv_per_day_ab_balance_all_now->pv_b ?? 0;
                                        $pvRightNew = $log_pv_per_day_ab_balance_all_now->pv_b_old ?? 0;

                                       }else{
                                        $pvLeftOld = 0;
                                        $pvLeftNew = $log_pv_per_day_ab_balance_all_old->pv_a_new ?? 0;
                                        $pvRightOld = 0;
                                        $pvRightNew = $log_pv_per_day_ab_balance_all_old->pv_b_new ?? 0;
                                       }
                                       
                                    @endphp

                                    <div class="pv-card">
                                        <div class="pv-card-header">
                                            <div>
                                                {{-- <h5><i class="las la-chart-bar"></i> PV เคลื่อนไหวรายวัน</h5> --}}
                                                <div class="pv-date">ข้อมูล Pv วันที่ {{ date('d/m/Y') }}</div>
                                            </div>
                                            <a class="btn btn-light btn-sm pv-history-btn" href="{{ route('reportsws') }}">
                                                <i class="las la-history"></i> ประวัติ
                                            </a>
                                        </div>



                                        <div class="pv-grid">
                                            <div class="pv-side">
                                                <div class="pv-side-title"><i class="las la-arrow-left"></i> PV ซ้าย</div>
                                                <div class="pv-row"><span>PV ใหม่(วันนี้)</span><span class="pv-value">{{ number_format($pvLeftOld) }}</span></div>
                                                <div class="pv-row"><span>PV สะสม</span><span class="pv-value">{{ number_format($pvLeftNew) }}</span></div>
                                                <div class="pv-row pv-total"><span>PV รวม</span><span class="pv-value">{{ number_format($pvLeftOld + $pvLeftNew) }}</span></div>
                                            </div>

                                            <div class="pv-side">
                                                <div class="pv-side-title"><i class="las la-arrow-right"></i> PV ขวา</div>
                                                <div class="pv-row"><span>PV ใหม่(วันนี้)</span><span class="pv-value">{{ number_format($pvRightOld) }}</span></div>
                                                <div class="pv-row"><span>PV สะสม</span><span class="pv-value">{{ number_format($pvRightNew) }}</span></div>
                                                <div class="pv-row pv-total"><span>PV รวม</span><span class="pv-value">{{ number_format($pvRightOld + $pvRightNew) }}</span></div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="glass-card d-flex align-items-center justify-content-center text-muted">
                                        <div class="text-center">
                                            <i class="las la-chart-line" style="font-size:38px"></i>
                                            <div class="font-weight-bold">ยังไม่มีข้อมูล PV วันนี้</div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="tree-board">
                                <div class="tree-board-head">
                                    <div>
                                        <h5><i class="las la-sitemap"></i> แผนผังสายงาน</h5>
                                        <div class="tree-hint">กดรูปสมาชิกเพื่อดูรายละเอียด หรือกดช่องว่างเพื่อสมัครสมาชิกใต้สายงาน</div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="body genealogy-body genealogy-scroll">
                                    <div class="genealogy-tree">
                                        <ul>
                                            <li>
                                                <?php
                                                
                                                ?>
                                                @if ($data['lv1'])
                                                    <a href="javascript:void(0);">
                                                        <div class="member-view-box">
                                                            <div class="member-image">

                                                                <div class="member-avatar member-avatar-{{ $treeMemberStatus($data['lv1']) }}" title="{{ $treeMemberStatusText($data['lv1']) }}">
                                                                    <img onclick="modal_tree('{{ $data['lv1']->user_name }}')"
                                                                        src="{{ $treeMemberAvatar($data['lv1']) }}"
                                                                        alt="{{ $data['lv1']->user_name }}">
                                                                    <span class="status-dot"><i class="{{ $treeMemberStatusIcon($data['lv1']) }}"></i></span>
                                                                </div>


                                                                <div class="member-detailsr">
                                                                    <h6 class="mt-2">{{ $data['lv1']->user_name }}</h6>
                                                                    <h6 class="text-primary">

                                                                        {{ @$data['lv1']->name . ' ' . @$data['lv1']->last_name }}

                                                                    </h6>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </a>
                                                @else
                                                    <a href="javascript:void(0);">
                                                        <div class="member-view-box">
                                                            <div class="member-image">
                                                                <img
                                                                    src="{{ asset('frontend/images/icon/add_user.png') }}">
                                                            </div>
                                                        </div>
                                                    </a>
                                                @endif


                                                <ul class="active">

                                                    @for ($i = 1; $i <= 2; $i++)
                                                        <?php
                                                        if ($i == 1) {
                                                            $data_lv2 = $data['lv2_a'];
                                                            $model_lv2 = 'lv2_a';
                                                            $type = 'a';
                                                            $line_lv2 = 'ซ้าย';
                                                            $line_lv2_add = 'A';
                                                        } elseif ($i == 2) {
                                                            $data_lv2 = $data['lv2_b'];
                                                            $model_lv2 = 'lv2_b';
                                                            $type = 'b';
                                                            $line_lv2 = 'ขวา';
                                                            $line_lv2_add = 'B';
                                                        } else {
                                                            $data_lv2 = null;
                                                            $model_lv2 = null;
                                                            $line_lv2 = null;
                                                            $line_lv2_add = null;
                                                        }
                                                        ?>

                                                        @if ($data_lv2)
                                                            <li>
                                                                <a href="javascript:void(0);">
                                                                    <div class="member-view-box">
                                                                        <div class="member-image">

                                                                            <div class="member-avatar member-avatar-{{ $treeMemberStatus($data_lv2) }}" title="{{ $treeMemberStatusText($data_lv2) }}">
                                                                                <img onclick="modal_tree('{{ $data_lv2->user_name }}')"
                                                                                    src="{{ $treeMemberAvatar($data_lv2) }}"
                                                                                    alt="{{ $data_lv2->user_name }}">
                                                                                <span class="status-dot"><i class="{{ $treeMemberStatusIcon($data_lv2) }}"></i></span>
                                                                            </div>



                                                                            <div class="member-detailsr">
                                                                                <h6 class="mt-2">ขา{{ $line_lv2 }} :
                                                                                    {{ $data_lv2->user_name }}</h6>
                                                                                <h6 class="text-primary">
                                                                                    {{ $data_lv2->name . ' ' . $data_lv2->last_name }}
                                                                                </h6>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </a>


                                                                <ul class="active">

                                                                    @for ($j = 1; $j <= 2; $j++)
                                                                        <?php
                                                                        if ($j == 1) {
                                                                            $data_lv3 = $data['lv3_' . $type . '_a'];
                                                                            $model_lv3 = 'lv3_' . $type . '_a';
                                                                            $line_lv3 = 'ซ้าย';
                                                                            $type_v3 = 'a';
                                                                            $line_lv3_add = 'A';
                                                                        } elseif ($j == 2) {
                                                                            $data_lv3 = $data['lv3_' . $type . '_b'];
                                                                            $model_lv3 = 'lv3_' . $type . '_b';
                                                                            $line_lv3 = 'ขวา';
                                                                            $type_v3 = 'b';
                                                                            $line_lv3_add = 'B';
                                                                        } else {
                                                                            $data_lv3 = null;
                                                                            $model_lv3 = null;
                                                                            $line_lv3 = null;
                                                                            $line_lv3_add = null;
                                                                        }
                                                                        
                                                                        ?>
                                                                        @if ($data_lv3)
                                                                            <li>
                                                                                <a href="javascript:void(0);">
                                                                                    <div class="member-view-box">
                                                                                        <div class="member-image">

                                                                                            <div class="member-avatar member-avatar-{{ $treeMemberStatus($data_lv3) }}" title="{{ $treeMemberStatusText($data_lv3) }}">
                                                                                                <img onclick="modal_tree('{{ $data_lv3->user_name }}')"
                                                                                                    src="{{ $treeMemberAvatar($data_lv3) }}"
                                                                                                    alt="{{ $data_lv3->user_name }}">
                                                                                                <span class="status-dot"><i class="{{ $treeMemberStatusIcon($data_lv3) }}"></i></span>
                                                                                            </div>



                                                                                            <div class="member-detailsr">
                                                                                                <h6 class="mt-2">
                                                                                                    ขา{{ $line_lv3 }} :
                                                                                                    {{ $data_lv3->user_name }}
                                                                                                </h6>
                                                                                                <h6 class="text-primary">
                                                                                                    {{ $data_lv3->name . ' ' . $data_lv3->last_name }}
                                                                                                </h6>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </a>

                                                                                <ul class="active">

                                                                                    @for ($k = 1; $k <= 2; $k++)
                                                                                        <?php
                                                                                        
                                                                                        if ($k == 1) {
                                                                                            $data_lv4 = $data['lv4_' . $type . '_' . $type_v3 . '_a'];
                                                                                            $model_lv4 = 'lv4_' . $type . '_' . $type_v3 . '_a';
                                                                                            $line_lv4 = 'ซ้าย';
                                                                                            $line_lv4_add = 'A';
                                                                                        } elseif ($k == 2) {
                                                                                            $data_lv4 = $data['lv4_' . $type . '_' . $type_v3 . '_b'];
                                                                                            $model_lv4 = 'lv4_' . $type . '_' . $type_v3 . '_b';
                                                                                            $line_lv4 = 'ขวา';
                                                                                            $line_lv4_add = 'B';
                                                                                        } else {
                                                                                            $data_lv4 = null;
                                                                                            $model_lv4 = null;
                                                                                            $line_lv4 = null;
                                                                                            $line_lv4_add = null;
                                                                                        }
                                                                                        
                                                                                        ?>
                                                                                        @if ($data_lv4)
                                                                                            <li>
                                                                                                <a
                                                                                                    href="javascript:void(0);">
                                                                                                    <div
                                                                                                        class="member-view-box">
                                                                                                        <div
                                                                                                            class="member-image">

                                                                                                            <div class="member-avatar member-avatar-{{ $treeMemberStatus($data_lv4) }}" title="{{ $treeMemberStatusText($data_lv4) }}">
                                                                                                                <img onclick="modal_tree('{{ $data_lv4->user_name }}')"
                                                                                                                    src="{{ $treeMemberAvatar($data_lv4) }}"
                                                                                                                    alt="{{ $data_lv4->user_name }}">
                                                                                                                <span class="status-dot"><i class="{{ $treeMemberStatusIcon($data_lv4) }}"></i></span>
                                                                                                            </div>



                                                                                                            <div
                                                                                                                class="member-detailsr last">
                                                                                                                <h6
                                                                                                                    class="mt-2">
                                                                                                                    <span
                                                                                                                        class="d-none d-md-inline-block">ขา{{ $line_lv4 }}
                                                                                                                        :</span>
                                                                                                                    {{ $data_lv4->user_name }}
                                                                                                                </h6>
                                                                                                                <h6
                                                                                                                    class="text-primary">
                                                                                                                    <span
                                                                                                                        class="d-none d-md-block">{{ $data_lv4->name . ' ' . $data_lv4->last_name }}</span>
                                                                                                                    <span
                                                                                                                        class="d-block d-md-none">
                                                                                                                        {{ $data_lv4->name }}
                                                                                                                    </span>
                                                                                                                </h6>
                                                                                                            </div>

                                                                                                        </div>
                                                                                                    </div>
                                                                                                </a>
                                                                                            </li>
                                                                                        @else
                                                                                            <li>
                                                                                                <a
                                                                                                    href="javascript:void(0);">
                                                                                                    <div
                                                                                                        class="member-view-box">
                                                                                                        <div
                                                                                                            class="member-image">
                                                                                                            <img onclick="modal_add('{{ $data_lv3->user_name }}','{{ $line_lv4_add }}')"
                                                                                                                src="{{ asset('frontend/images/icon/add_user.png') }}">
                                                                                                            {{-- <img src="{{asset('frontend/images/icon/add_user.png')}}"> --}}
                                                                                                            <div
                                                                                                                class="member-detailsr">
                                                                                                                <h6
                                                                                                                    class="f-w-600 mt-2 text-success">
                                                                                                                    ขา{{ $line_lv4 }}
                                                                                                                </h6>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </a>

                                                                                            </li>
                                                                                        @endif
                                                                                    @endfor


                                                                                </ul>
                                                                            </li>
                                                                        @else
                                                                            @if ($data_lv2)
                                                                                <li>
                                                                                    <a href="javascript:void(0);">
                                                                                        <div class="member-view-box">
                                                                                            <div class="member-image">
                                                                                                <img onclick="modal_add('{{ $data_lv2->user_name }}','{{ $line_lv3_add }}')"
                                                                                                    src="{{ asset('frontend/images/icon/add_user.png') }}">
                                                                                                {{-- <img src="{{asset('frontend/images/icon/add_user.png')}}"> --}}
                                                                                                <div
                                                                                                    class="member-detailsr">
                                                                                                    <h6
                                                                                                        class="mt-2 text-success">
                                                                                                        ขา{{ $line_lv3 }}
                                                                                                    </h6>
                                                                                                    @if ($data['lv3_a_a'] || $data['lv3_b_a'])
                                                                                                        <h6
                                                                                                            class="invisible">
                                                                                                            fake</h6>
                                                                                                    @endif
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </a>
                                                                                    <ul class="active">
                                                                                        <li>
                                                                                            <a href="javascript:void(0);">
                                                                                                <div
                                                                                                    class="member-view-box">
                                                                                                    <div
                                                                                                        class="member-image">
                                                                                                        <img
                                                                                                            src="{{ asset('frontend/images/icon/add_user_not.png') }}">
                                                                                                        <div
                                                                                                            class="member-detailsr">
                                                                                                            <h6
                                                                                                                class="f-w-600 mt-2 text-success">
                                                                                                                ขาซ้าย</h6>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </a>
                                                                                        </li>
                                                                                        <li>
                                                                                            <a href="javascript:void(0);">
                                                                                                <div
                                                                                                    class="member-view-box">
                                                                                                    <div
                                                                                                        class="member-image">
                                                                                                        <img
                                                                                                            src="{{ asset('frontend/images/icon/add_user_not.png') }}">
                                                                                                        <div
                                                                                                            class="member-detailsr">
                                                                                                            <h6
                                                                                                                class="f-w-600 mt-2 text-success">
                                                                                                                ขาขวา</h6>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </a>
                                                                                        </li>
                                                                                    </ul>
                                                                                </li>
                                                                            @else
                                                                                <li>
                                                                                    <a href="javascript:void(0);">
                                                                                        <div class="member-view-box">
                                                                                            <div class="member-image">
                                                                                                <img
                                                                                                    src="{{ asset('frontend/images/icon/add_user_not.png') }}">
                                                                                                <div
                                                                                                    class="member-detailsr">
                                                                                                    <h6
                                                                                                        class="f-w-600 mt-2 text-success">
                                                                                                        ขา{{ $line_lv3 }}
                                                                                                    </h6>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </a>
                                                                                </li>
                                                                            @endif
                                                                        @endif
                                                                    @endfor



                                                                </ul>
                                                            </li>
                                                        @else
                                                            <li>
                                                                <a href="javascript:void(0);">
                                                                    <div class="member-view-box">
                                                                        <div class="member-image">
                                                                            <img onclick="modal_add('{{ $data['lv1']->user_name }}','{{ $line_lv2_add }}')"
                                                                                src="{{ asset('frontend/images/icon/add_user.png') }}">
                                                                            {{-- <img src="{{asset('frontend/images/icon/add_user.png')}}"> --}}
                                                                            <div class="member-detailsr">
                                                                                <h6 class="f-w-600 mt-2 text-success">
                                                                                    ขา{{ $line_lv2 }}</h6>
                                                                                <h6 class="text-muted">ภายใต้ :
                                                                                    {{ @$data['lv1']->name . ' ' . @$data['lv1']->last_name }}
                                                                                </h6>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </a>

                                                                <ul class="active">
                                                                    <li>
                                                                        <a href="javascript:void(0);">
                                                                            <div class="member-view-box">
                                                                                <div class="member-image">
                                                                                    <img
                                                                                        src="{{ asset('frontend/images/icon/add_user_not.png') }}">
                                                                                    <div class="member-detailsr">
                                                                                        <h6
                                                                                            class="f-w-600 mt-2 text-success">
                                                                                            ขาซ้าย</h6>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </a>
                                                                        <ul class="active">
                                                                            <li>
                                                                                <a href="javascript:void(0);">
                                                                                    <div class="member-view-box">
                                                                                        <div class="member-image">
                                                                                            <img
                                                                                                src="{{ asset('frontend/images/icon/add_user_not.png') }}">
                                                                                            <div class="member-detailsr">
                                                                                                <h6
                                                                                                    class="f-w-600 mt-2 text-success">
                                                                                                    ขาซ้าย</h6>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </a>
                                                                            </li>
                                                                            <li>
                                                                                <a href="javascript:void(0);">
                                                                                    <div class="member-view-box">
                                                                                        <div class="member-image">
                                                                                            <img
                                                                                                src="{{ asset('frontend/images/icon/add_user_not.png') }}">
                                                                                            <div class="member-detailsr">
                                                                                                <h6
                                                                                                    class="f-w-600 mt-2 text-success">
                                                                                                    ขาขวา</h6>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </a>
                                                                            </li>
                                                                        </ul>
                                                                    </li>
                                                                    <li>
                                                                        <a href="javascript:void(0);">
                                                                            <div class="member-view-box">
                                                                                <div class="member-image">
                                                                                    <img
                                                                                        src="{{ asset('frontend/images/icon/add_user_not.png') }}">
                                                                                    <div class="member-detailsr">
                                                                                        <h6
                                                                                            class="f-w-600 mt-2 text-success">
                                                                                            ขาขวา</h6>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </a>

                                                                        <ul class="active">
                                                                            <li>
                                                                                <a href="javascript:void(0);">
                                                                                    <div class="member-view-box">
                                                                                        <div class="member-image">
                                                                                            <img
                                                                                                src="{{ asset('frontend/images/icon/add_user_not.png') }}">
                                                                                            <div class="member-detailsr">
                                                                                                <h6
                                                                                                    class="f-w-600 mt-2 text-success">
                                                                                                    ขาซ้าย</h6>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </a>

                                                                            </li>
                                                                            <li>
                                                                                <a href="javascript:void(0);">
                                                                                    <div class="member-view-box">
                                                                                        <div class="member-image">
                                                                                            <img
                                                                                                src="{{ asset('frontend/images/icon/add_user_not.png') }}">
                                                                                            <div class="member-detailsr">
                                                                                                <h6
                                                                                                    class="f-w-600 mt-2 text-success">
                                                                                                    ขาขวา</h6>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </a>
                                                                            </li>
                                                                        </ul>
                                                                    </li>
                                                                </ul>
                                                            </li>
                                                        @endif
                                                    @endfor

                                                </ul>
                                            </li>
                                        </ul>
                                    </div>
                                </div>


                            </div>

                            </div>

                            <div id="modal_tree"></div>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>

    <div class="modal fade tree-modal" id="modal_add_show" tabindex="-1" aria-labelledby="withdrawModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="tree-modal-header">
                    <h4 class="modal-title"><i class="las la-user-plus"></i> สมัครสมาชิกใหม่</h4>
                    <div class="mt-1" id="text_add">กรุณายืนยันตำแหน่งสมาชิก</div>
                </div>
                <div class="tree-modal-body">
                    <div class="alert alert-info mb-0">
                        <i class="las la-info-circle"></i>
                        ตรวจสอบสายงานและฝั่งที่ต้องการสมัครให้ถูกต้องก่อนกดยืนยัน
                    </div>
                </div>
                <div class="tree-modal-footer">
                    <button type="button" class="btn btn-outline-dark rounded-pill" data-bs-dismiss="modal">
                        ปิด
                    </button>
                    <a href="" id="pid_link" type="button" class="btn btn-primary rounded-pill waves-effect waves-light">
                        <i class="las la-check-circle"></i> ยืนยันสมัครสมาชิก
                    </a>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('search') }}" method="post" id="home_search">
        @csrf
        <input type="hidden" id="home_search_id" name="home_search_id" value="">
    </form>



@endsection

@section('script')
    <script>
        function modal_tree(user_name) {
            if (!user_name) {
                return;
            }

            $.ajax({
                url: '{{ route('modal_tree') }}',
                type: 'GET',
                data: { user_name: user_name },
                beforeSend: function () {
                    $('#modal_tree').html('');
                }
            })
            .done(function(data) {
                $('#modal_tree').html(data);
                $('#tree').modal('show');
            })
            .fail(function() {
                Swal.fire({
                    icon: 'error',
                    title: 'ไม่สามารถโหลดข้อมูลสมาชิกได้',
                    text: 'กรุณาลองใหม่อีกครั้ง'
                });
            });
        }

        function modal_add(pid, type) {
            if (!pid || !type) {
                return;
            }

            var url_value = '{{ route('register') }}' + '/' + pid + '/' + type;
            var sideName = type === 'A' ? 'ขาซ้าย' : 'ขาขวา';

            $('#pid_link').attr('href', url_value);
            $('#text_add').html('สมัครสมาชิกใต้ <b>' + pid + '</b> ฝั่ง <b>' + sideName + '</b>');
            $('#modal_add_show').modal('show');
        }

        function search_user() {
            var username = $.trim($('#search_username').val()).toUpperCase();

            if (!username) {
                Swal.fire({
                    icon: 'warning',
                    title: 'กรุณากรอก ID สมาชิก',
                });
                return;
            }

            $.ajax({
                url: '{{ route('home_check_customer_id') }}',
                type: 'GET',
                data: { username: username },
                beforeSend: function () {
                    $('.tree-search .btn').prop('disabled', true).html('<i class="las la-spinner la-spin"></i> กำลังค้นหา');
                }
            })
            .done(function(data) {
                if (data['status'] === 'success') {
                    $('#home_search_id').val(data['username']);
                    document.getElementById('home_search').submit();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: data['data'] && data['data']['message'] ? data['data']['message'] : 'ไม่พบข้อมูลสมาชิก',
                    });
                }
            })
            .fail(function() {
                Swal.fire({
                    icon: 'error',
                    title: 'ค้นหาไม่สำเร็จ',
                    text: 'กรุณาลองใหม่อีกครั้ง'
                });
            })
            .always(function() {
                $('.tree-search .btn').prop('disabled', false).html('<i class="las la-search"></i> Search');
            });
        }

        $('#search_username').on('keypress', function(e) {
            if (e.which === 13) {
                e.preventDefault();
                search_user();
            }
        });
    </script>
@endsection
