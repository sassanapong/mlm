@extends('layouts.backend.app_new')

@section('head')
<meta charset="UTF-8">
@endsection

@section('head_text')
<nav aria-label="breadcrumb" class="-intro-x mr-auto hidden sm:flex">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">ระบบบริการสมาชิก</a></li>
        <li class="breadcrumb-item active" aria-current="page">ตรวจสอบยืนยันตัวตน 1 Code 1 World</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="intro-y box p-5 mt-5">
    @if (session('success')) <div class="alert alert-success mb-3">{{ session('success') }}</div> @endif
    @if (session('error')) <div class="alert alert-danger mb-3">{{ session('error') }}</div> @endif
    @if ($errors->any()) <div class="alert alert-danger mb-3">{{ $errors->first() }}</div> @endif

    <form method="GET" class="mb-4 flex items-center gap-2">
        <select name="status" class="form-select w-60" onchange="this.form.submit()">
            <option value="" {{ $status === '' ? 'selected' : '' }}>ทุกสถานะ</option>
            @foreach (['0' => 'รอตรวจสอบ', '1' => 'ผ่าน', '2' => 'ไม่ผ่าน'] as $k => $v)
                <option value="{{ $k }}" {{ (string) $status === (string) $k ? 'selected' : '' }}>{{ $v }}</option>
            @endforeach
        </select>
        <input type="text" name="q" value="{{ $q }}" class="form-control w-72" placeholder="รหัส / เลขบัตร / เบอร์ / เลขบัญชี">
        <button class="btn btn-primary">ค้นหา</button>
    </form>

    <div class="overflow-x-auto">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>วันที่ส่ง</th><th>สมาชิก</th><th>เลขบัตร</th><th>ธนาคาร / เลขบัญชี</th>
                    <th>เบอร์โทร</th><th>รูป</th><th>สถานะ</th><th>จัดการ</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($rows as $r)
                    <tr>
                        <td>{{ $r->updated_at }}</td>
                        <td>{{ $r->name }} {{ $r->last_name }}<br><small>{{ $r->user_name }}</small></td>
                        <td>{{ $r->id_card }}</td>
                        <td>{{ $r->bank_name }}<br>{{ $r->bank_no }}</td>
                        <td>{{ $r->phone }}</td>
                        <td>
                            <a href="javascript:;" data-tw-toggle="modal" data-tw-target="#kycImgModal" class="kyc-img" data-src="{{ asset($r->img_card) }}" data-title="รูปบัตร" data-lines="{{ e(json_encode(['สมาชิก: ' . trim($r->name . ' ' . $r->last_name) . ' (' . $r->user_name . ')', 'เลขบัตรประชาชน: ' . $r->id_card, 'เบอร์โทร: ' . $r->phone, 'ส่งเมื่อ: ' . ($r->submitted_at ?? $r->created_at)], JSON_UNESCAPED_UNICODE)) }}" title="รูปบัตร" style="display:inline-block; margin-right:8px;"><i data-lucide="credit-card" class="w-6 h-6" style="color:#2563eb"></i></a>
                            <a href="javascript:;" data-tw-toggle="modal" data-tw-target="#kycImgModal" class="kyc-img" data-src="{{ asset($r->img_bank) }}" data-title="รูปบัญชี" data-lines="{{ e(json_encode(['สมาชิก: ' . trim($r->name . ' ' . $r->last_name) . ' (' . $r->user_name . ')', 'ธนาคาร: ' . $r->bank_name, 'เลขบัญชี: ' . $r->bank_no, 'ส่งเมื่อ: ' . ($r->submitted_at ?? $r->created_at)], JSON_UNESCAPED_UNICODE)) }}" title="รูปบัญชี" style="display:inline-block;"><i data-lucide="book-open" class="w-6 h-6" style="color:#16a34a"></i></a>
                            @if ($r->history->count())
                                <details class="mt-1">
                                    <summary class="text-xs">ประวัติเก่า ({{ $r->history->count() }})</summary>
                                    @foreach ($r->history as $h)
                                        <div class="text-xs mt-1">
                                            {{ $h->submitted_at }}: <a href="javascript:;" data-tw-toggle="modal" data-tw-target="#kycImgModal" class="kyc-img" data-src="{{ asset($h->img_card) }}" data-title="รูปบัตร (ประวัติเก่า)" data-lines="{{ e(json_encode(['สมาชิก: ' . trim($r->name . ' ' . $r->last_name) . ' (' . $r->user_name . ')', 'เลขบัตรประชาชน: ' . $h->id_card, 'เบอร์โทร: ' . $h->phone, 'ส่งเมื่อ: ' . ($h->submitted_at ?? $r->created_at)], JSON_UNESCAPED_UNICODE)) }}" title="รูปบัตร"><i data-lucide="credit-card" class="w-4 h-4 inline" style="color:#2563eb"></i></a>,
                                            <a href="javascript:;" data-tw-toggle="modal" data-tw-target="#kycImgModal" class="kyc-img" data-src="{{ asset($h->img_bank) }}" data-title="รูปบัญชี (ประวัติเก่า)" data-lines="{{ e(json_encode(['สมาชิก: ' . trim($r->name . ' ' . $r->last_name) . ' (' . $r->user_name . ')', 'ธนาคาร: ' . $h->bank_name, 'เลขบัญชี: ' . $h->bank_no, 'ส่งเมื่อ: ' . ($h->submitted_at ?? $r->created_at)], JSON_UNESCAPED_UNICODE)) }}" title="รูปบัญชี"><i data-lucide="book-open" class="w-4 h-4 inline" style="color:#16a34a"></i></a>
                                            @if ($h->note) <br><span class="text-danger">{{ $h->note }}</span> @endif
                                        </div>
                                    @endforeach
                                </details>
                            @endif
                        </td>
                        <td>
                            @if ($r->status == 0) <span class="text-warning">รอตรวจสอบ</span>
                            @elseif ($r->status == 1) <span class="text-success">ผ่าน</span>
                            @else <span class="text-danger">ไม่ผ่าน</span> @endif
                            @if ($r->note) <br><small class="text-danger">{{ $r->note }}</small> @endif
                            @if ($r->dup) <br><small class="text-danger">ซ้ำกับรหัสอื่น: {{ implode(', ', $r->dup) }}</small> @endif
                        </td>
                        <td>
                            @if ($r->status == 0)
                                <form method="POST" action="{{ route('admin.kyc.approve', $r->id) }}" class="inline" onsubmit="return confirm('ยืนยันอนุมัติ?')">
                                    @csrf
                                    <button class="btn btn-success btn-sm" {{ $r->dup ? 'disabled' : '' }}>อนุมัติ</button>
                                </form>
                                <form method="POST" action="{{ route('admin.kyc.reject', $r->id) }}" class="inline" onsubmit="this.note.value = prompt('เหตุผลที่ไม่อนุมัติ') || ''; return this.note.value !== '';">
                                    @csrf
                                    <input type="hidden" name="note">
                                    <button class="btn btn-danger btn-sm">ไม่อนุมัติ</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center">ไม่พบข้อมูล</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $rows->links() }}
</div>
@endsection

@section('script')
<div id="kycImgModal" class="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="font-medium text-base mr-auto" id="kycImgTitle"></h2>
                <a href="javascript:;" data-tw-dismiss="modal" class="btn btn-outline-secondary btn-sm">ปิด</a>
            </div>
            <div class="modal-body text-center">
                <img id="kycImg" src="" alt="" style="max-width:100%; max-height:75vh; margin:0 auto;"
                    onerror="document.getElementById('kycImgMsg').style.display='block'; this.style.display='none';"
                    onload="document.getElementById('kycImgMsg').style.display='none'; this.style.display='block';">
                <div id="kycImgMsg" class="text-danger" style="display:none;">ไม่พบไฟล์รูป</div>
                <div id="kycImgInfo" class="text-left mt-4 border rounded p-3" style="display:none; font-size:14px; line-height:1.8;"></div>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('click', function(e) {
        var a = e.target.closest('.kyc-img');
        if (!a) return;
        document.getElementById('kycImgTitle').textContent = a.dataset.title;
        document.getElementById('kycImg').src = a.dataset.src;
        var info = document.getElementById('kycImgInfo');
        info.innerHTML = '';
        var lines = [];
        try { lines = JSON.parse(a.dataset.lines || '[]'); } catch (err) {}
        lines.forEach(function(t) {
            var d = document.createElement('div');
            d.textContent = t;
            info.appendChild(d);
        });
        info.style.display = lines.length ? 'block' : 'none';
    });
</script>
@endsection
