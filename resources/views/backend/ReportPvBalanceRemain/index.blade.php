@extends('layouts.backend.app_new')
@section('head')
@endsection

@section('head_text')
    <nav aria-label="breadcrumb" class="-intro-x mr-auto hidden sm:flex">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">รายงาน</a></li>
            <li class="breadcrumb-item active" aria-current="page">คะแนน Balance คงเหลือ</li>
        </ol>
    </nav>
@endsection

@section('content')
    <div class="intro-y box p-5 mt-5">
        <div class="flex flex-col sm:flex-row sm:items-end xl:items-start mb-2">
            <form id="filter-form" class="xl:flex sm:mr-auto" action="{{ route('report_pv_balance_remain_excel') }}" method="GET">
                <div class="sm:flex items-center sm:mr-4">
                    <div class="col-span-12 sm:col-span-6">
                        <label class="form-label">รหัสสมาชิก</label><br>
                        <input type="text" id="user_name" name="user_name" class="form-control sm:w-40 2xl:w-full mt-2 sm:mt-0"
                            placeholder="รหัสสมาชิก">
                    </div>
                </div>
                <div class="mt-2 xl:mt-2">
                    <div class="col-span-12 sm:col-span-6 mt-6">
                        <button id="search-form" type="button" class="btn btn-primary w-full sm:w-16">ค้นหา</button>
                        <button type="submit" class="btn btn-warning w-full sm:w-16 mt-2 sm:mt-0 sm:ml-1">Excel</button>
                    </div>
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
            var table = $('#workL').DataTable({
                searching: false,
                ordering: true,
                lengthChange: false,
                responsive: true,
                paging: true,
                pageLength: 50,
                processing: true,
                serverSide: true,
                "language": {"lengthMenu": "แสดง _MENU_ แถว","zeroRecords": "ไม่พบข้อมูล","info": "แสดงหน้า _PAGE_ จาก _PAGES_ หน้า","search": "ค้นหา","infoEmpty": "","infoFiltered": "","paginate": {"first": "หน้าแรก","previous": "ย้อนกลับ","next": "ถัดไป","last": "หน้าสุดท้าย"},"processing": "กำลังโหลดข้อมูล"},
                ajax: {
                    url: '{{ route('report_pv_balance_remain_datable') }}',
                    data: function(d) {
                        d.user_name = $('#user_name').val();
                    },
                },
                order: [[2, 'asc']],
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', title: 'ลำดับ', className: 'w-10 text-center', orderable: false, searchable: false},
                    {data: 'date_action', name: 'x.date_action', title: 'วันที่ล่าสุด'},
                    {data: 'user_name', name: 'x.user_name', title: 'รหัสสมาชิก'},
                    {data: 'name', name: 'x.name', title: 'ชื่อ'},
                    {data: 'business_qualifications', name: 'dataset_qualification.business_qualifications', title: 'ตำแหน่ง', defaultContent: '-'},
                    {data: 'balance', name: 'x.balance', title: 'balance', className: 'text-end'},
                    {data: 'balance_type', name: 'x.balance_type', title: 'balance_type', className: 'text-center'},
                    {data: 'pv_a', name: 'x.pv_a', title: 'pv_a', className: 'text-end'},
                    {data: 'pv_b', name: 'x.pv_b', title: 'pv_b', className: 'text-end'},
                    {data: 'kang', name: 'x.kang', title: 'kang', className: 'text-end'},
                    {data: 'aoon', name: 'x.aoon', title: 'aoon', className: 'text-end'},
                    {data: 'detail', title: 'รายละเอียด', className: 'text-center', orderable: false, searchable: false},
                ],
            });
            $('#search-form').on('click', function(e) {
                table.draw();
                e.preventDefault();
            });
        });
    </script>
@endsection
