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
                            <a href="{{ asset($r->img_card) }}" target="_blank">รูปบัตร</a><br>
                            <a href="{{ asset($r->img_bank) }}" target="_blank">รูปบัญชี</a>
                            @if ($r->history->count())
                                <details class="mt-1">
                                    <summary class="text-xs">ประวัติเก่า ({{ $r->history->count() }})</summary>
                                    @foreach ($r->history as $h)
                                        <div class="text-xs mt-1">
                                            {{ $h->submitted_at }}: <a href="{{ asset($h->img_card) }}" target="_blank">บัตร</a>,
                                            <a href="{{ asset($h->img_bank) }}" target="_blank">บัญชี</a>
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
