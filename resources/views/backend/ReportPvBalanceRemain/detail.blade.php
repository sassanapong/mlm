@extends('layouts.backend.app_new')
@section('head')
@endsection

@section('head_text')
    <nav aria-label="breadcrumb" class="-intro-x mr-auto hidden sm:flex">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">รายงาน</a></li>
            <li class="breadcrumb-item"><a href="{{ route('report_pv_balance_remain') }}">คะแนน Balance คงเหลือ</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $user_name }}</li>
        </ol>
    </nav>
@endsection

@section('content')
    <div class="intro-y box p-5 mt-5">
        <div class="flex items-center mb-4">
            <div class="mr-auto">
                <div class="font-medium text-lg">
                    {{ trim(($customer->name ?? $latest->name ?? '') . ' ' . ($customer->last_name ?? '')) }} ({{ $user_name }})
                </div>
                @if ($latest)
                    <div class="text-slate-500 mt-1">
                        คงเหลือล่าสุด: balance {{ number_format($latest->balance) }} ({{ $latest->balance_type }}),
                        pv_a {{ number_format($latest->pv_a) }}, pv_b {{ number_format($latest->pv_b) }},
                        kang {{ number_format($latest->kang) }}, aoon {{ number_format($latest->aoon) }}
                        — {{ $latest->date_action ? date('d/m/Y', strtotime($latest->date_action)) : '' }}
                    </div>
                @endif
            </div>
            <a href="{{ route('report_pv_balance_remain') }}" class="btn btn-outline-secondary">ย้อนกลับ</a>
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
                searching: false,
                ordering: true,
                lengthChange: false,
                responsive: true,
                paging: true,
                pageLength: 50,
                processing: true,
                serverSide: true,
                "language": {"lengthMenu": "แสดง _MENU_ แถว","zeroRecords": "ไม่พบข้อมูล","info": "แสดงหน้า _PAGE_ จาก _PAGES_ หน้า","search": "ค้นหา","infoEmpty": "","infoFiltered": "","paginate": {"first": "หน้าแรก","previous": "ย้อนกลับ","next": "ถัดไป","last": "หน้าสุดท้าย"},"processing": "กำลังโหลดข้อมูล"},
                ajax: {url: '{{ route('report_pv_balance_remain_detail_datable', $user_name) }}'},
                order: [[1, 'desc']],
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', title: 'ลำดับ', className: 'w-10 text-center', orderable: false, searchable: false},
                    {data: 'id', name: 'log_pv_per_day_ab_balance_all.id', title: 'id'},
                    {data: 'date_action', name: 'log_pv_per_day_ab_balance_all.date_action', title: 'วันที่'},
                    {data: 'business_qualifications', name: 'dataset_qualification.business_qualifications', title: 'ตำแหน่ง', defaultContent: '-'},
                    {data: 'balance', title: 'balance', className: 'text-end'},
                    {data: 'balance_type', title: 'balance_type', className: 'text-center'},
                    {data: 'balance_up_old', title: 'balance_up_old', className: 'text-end'},
                    {data: 'pv_today', title: 'pv_today', className: 'text-end'},
                    {data: 'pv_a_new', title: 'pv_a_new', className: 'text-end'},
                    {data: 'pv_b_new', title: 'pv_b_new', className: 'text-end'},
                    {data: 'pv_a', title: 'pv_a', className: 'text-end'},
                    {data: 'pv_b', title: 'pv_b', className: 'text-end'},
                    {data: 'pv_a_old', title: 'pv_a_old', className: 'text-end'},
                    {data: 'pv_b_old', title: 'pv_b_old', className: 'text-end'},
                    {data: 'kang', title: 'kang', className: 'text-end'},
                    {data: 'aoon', title: 'aoon', className: 'text-end'},
                    {data: 'status', title: 'status', className: 'text-center'},
                ],
            });
        });
    </script>
@endsection
