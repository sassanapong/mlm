@extends('layouts.backend.app_new')

@section('head_text')
    <nav aria-label="breadcrumb" class="-intro-x mr-auto hidden sm:flex">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">จัดการสินค้า</a></li>
            <li class="breadcrumb-item"><a href="{{ route('product_category') }}">หมวดหมู่สินค้า</a></li>
            <li class="breadcrumb-item active" aria-current="page">รหัสที่ได้รับสิทธิ์</li>
        </ol>
    </nav>
@endsection

@section('content')
    <div class="intro-y box p-5 mt-5">
        <div class="flex items-center mb-4">
            <div class="mr-auto">
                <div class="font-medium text-lg">{{ $category->category_name }} — รหัสที่ได้รับสิทธิ์ ({{ number_format($total) }} รหัส)</div>
                <div class="text-slate-500 mt-1">
                    @if ($category->access_mode != 'whitelist')
                        <span class="text-danger">หมวดนี้ยังไม่ได้ตั้งเป็น "เฉพาะรหัสที่ได้รับสิทธิ์" รายชื่อด้านล่างจึงยังไม่มีผล
                            (ตั้งค่าได้ที่ปุ่มแก้ไขในหน้าหมวดหมู่สินค้า)</span>
                    @else
                        ใช้สิทธิ์ 1 ท่านต่อ 1 รหัส เรื่องจำนวนชิ้นที่ซื้อได้ ตั้งที่ "ซื้อได้ไม่เกิน"
                        ({{ $category->max_qty_per_user ? number_format($category->max_qty_per_user) . ' ชิ้น/ท่าน' : 'ไม่จำกัด' }})
                    @endif
                </div>
            </div>
            <a href="{{ route('product_category') }}" class="btn btn-outline-secondary">ย้อนกลับ</a>
        </div>

        <div class="grid grid-cols-12 gap-4 mb-5">
            <form class="col-span-12 lg:col-span-7 border rounded p-3" method="POST" enctype="multipart/form-data"
                action="{{ route('category_privileges_import', $category->id) }}">
                @csrf
                <div class="font-medium mb-2">อิมพอร์ต Excel / CSV</div>
                <input type="file" name="file" accept=".xlsx,.xls,.csv" class="form-control" required>
                <div class="text-slate-500 text-xs mt-1">รหัสสมาชิกอยู่คอลัมน์แรก, คอลัมน์ที่ 2 (ไม่ใส่ก็ได้) = จำนวนที่ซื้อได้รายคน ว่าง = ใช้ค่ากลางของหมวด (มีหัวตารางหรือไม่มีก็ได้)
                    · <a href="{{ route('category_privileges_template') }}" class="text-primary underline">ดาวน์โหลดไฟล์ตัวอย่าง</a></div>
                <label class="flex items-center mt-2">
                    <input type="checkbox" name="replace_all" value="1" class="form-check-input mr-2">
                    แทนที่รายชื่อเดิมทั้งหมด (ไม่เลือก = เพิ่มต่อจากรายชื่อเดิม)
                </label>
                <button type="submit" class="btn btn-primary mt-3">นำเข้า</button>
            </form>

            <form class="col-span-12 lg:col-span-5 border rounded p-3" method="POST"
                action="{{ route('category_privileges_store', $category->id) }}">
                @csrf
                <div class="font-medium mb-2">เพิ่มรหัสเดียว</div>
                <div class="flex gap-2">
                    <input type="text" name="user_name" class="form-control" placeholder="รหัสสมาชิก" required>
                    <input type="number" min="0" name="max_qty" class="form-control w-40" placeholder="จำนวนที่ซื้อได้ (ไม่ใส่ = ค่ากลาง)">
                </div>
                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">เพิ่ม</button>
                    <a href="{{ route('category_privileges_export', $category->id) }}" class="btn btn-warning ml-1">Export Excel</a>
                    <a href="{{ route('category_privileges_clear', $category->id) }}" class="btn btn-outline-danger ml-1"
                        onclick="return confirm('ลบรหัสที่ได้รับสิทธิ์ทั้งหมดของหมวดนี้ ?')">ลบทั้งหมด</a>
                </div>
            </form>
        </div>

        <div class="overflow-x-auto">
            <div class="table-responsive">
                <table id="workL" class="table table-striped table-hover dt-responsive display nowrap" cellspacing="0"></table>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script>
        $(function() {
            $('#workL').DataTable({
                searching: true,
                ordering: true,
                lengthChange: false,
                responsive: true,
                pageLength: 50,
                processing: true,
                serverSide: true,
                language: {
                    zeroRecords: "ไม่พบข้อมูล", info: "แสดงหน้า _PAGE_ จาก _PAGES_ หน้า", search: "ค้นหา",
                    infoEmpty: "", infoFiltered: "", processing: "กำลังโหลดข้อมูล",
                    paginate: {first: "หน้าแรก", previous: "ย้อนกลับ", next: "ถัดไป", last: "หน้าสุดท้าย"}
                },
                ajax: {url: '{{ route('category_privileges_datable', $category->id) }}'},
                order: [[1, 'asc']],
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', title: 'ลำดับ', className: 'w-10 text-center', orderable: false, searchable: false},
                    {data: 'user_name', name: 'category_privileges.user_name', title: 'รหัสสมาชิก'},
                    {data: 'full_name', name: 'full_name', title: 'ชื่อ-สกุล'},
                    {data: 'business_qualifications', name: 'dataset_qualification.business_qualifications', title: 'ตำแหน่ง', defaultContent: '-', searchable: false},
                    {data: 'bought_qty', name: 'bought_qty', title: 'ซื้อไปแล้ว (ชิ้น)', className: 'text-end', orderable: false, searchable: false},
                    {data: 'limit', name: 'category_privileges.max_qty', title: 'ซื้อได้ไม่เกิน (ชิ้น)', className: 'text-end', searchable: false},
                    {data: 'created_at', name: 'category_privileges.created_at', title: 'วันที่ให้สิทธิ์', searchable: false},
                    {data: 'action', title: '', className: 'text-center', orderable: false, searchable: false},
                ],
            });
        });
    </script>
@endsection
