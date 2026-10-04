@extends('layouts.backend.app_new')

@section('head')
<meta charset="UTF-8">
@endsection

@section('head_text')
<nav aria-label="breadcrumb" class="-intro-x mr-auto hidden sm:flex">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">ระบบบริการสมาชิก</a></li>
        <li class="breadcrumb-item active" aria-current="page">ใบสมัครผ่านลิงก์</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="intro-y box p-5 mt-5">
    <form method="GET" class="mb-4 flex items-center gap-2">
        <select name="status" class="form-select w-60" onchange="this.form.submit()">
            <option value="">ทุกสถานะ</option>
            @foreach (['slip_pending' => 'รอตรวจสลิป', 'pending' => 'รอชำระ PaySo', 'paid' => 'สร้างสมาชิกแล้ว', 'failed' => 'ไม่สำเร็จ/ปฏิเสธ', 'error' => 'จ่ายแล้วแต่สร้างไม่สำเร็จ'] as $k => $v)
                <option value="{{ $k }}" {{ $status == $k ? 'selected' : '' }}>{{ $v }}</option>
            @endforeach
        </select>
    </form>

    <div class="overflow-x-auto">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>#</th><th>วันที่</th><th>ผู้สมัคร</th><th>ผู้แนะนำ</th><th>ทีมงาน/ขา</th>
                    <th class="text-right">ยอดชำระ</th><th class="text-right">PV</th><th>ตำแหน่ง</th>
                    <th>ช่องทาง</th><th>สถานะ</th><th>สลิป</th><th>จัดการ</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($apps as $a)
                    @php $f = $a->formData(); $files = $a->fileList(); @endphp
                    <tr>
                        <td>{{ $a->id }}</td>
                        <td>{{ $a->created_at }}</td>
                        <td>{{ ($f['prefix_name'] ?? '') . ($f['name'] ?? '') . ' ' . ($f['last_name'] ?? '') }}<br><small>{{ $a->id_card }}</small></td>
                        <td>{{ $a->sponsor_user_name }}</td>
                        <td>{{ $a->upline_user_name ? $a->upline_user_name . ' / ' . $a->type_upline : 'ขา ' . $a->type_upline . ' (วางสุดสาย)' }}</td>
                        <td class="text-right">{{ number_format($a->total_price, 2) }}</td>
                        <td class="text-right">{{ number_format($a->pv_total, 2) }}</td>
                        <td>{{ $a->position }}</td>
                        <td>{{ $a->pay_method == 'slip' ? 'แนบสลิป' : 'PaySo' }}</td>
                        <td>
                            {{ $a->status }}
                            @if ($a->customer_user_name) <br><b>{{ $a->customer_user_name }}</b> @endif
                            @if ($a->note) <br><small class="text-danger">{{ $a->note }}</small> @endif
                        </td>
                        <td>
                            @if (!empty($files['file_slip']))
                                <a href="{{ asset($files['file_slip']) }}" target="_blank">ดูสลิป</a>
                            @endif
                        </td>
                        <td>
                            @if ($a->status == 'slip_pending')
                                <form method="POST" action="{{ route('admin.register_applications.approve', $a->id) }}" class="inline" onsubmit="return confirm('ยืนยันอนุมัติและสร้างสมาชิก?')">
                                    @csrf
                                    <button class="btn btn-success btn-sm">อนุมัติ</button>
                                </form>
                                <form method="POST" action="{{ route('admin.register_applications.reject', $a->id) }}" class="inline" onsubmit="this.note.value = prompt('เหตุผลที่ปฏิเสธ'); return this.note.value !== null;">
                                    @csrf
                                    <input type="hidden" name="note">
                                    <button class="btn btn-danger btn-sm">ปฏิเสธ</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
