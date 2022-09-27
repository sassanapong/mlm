<script>
    $(function() {
        table_warehouse = $('#table_warehouse').DataTable({
            searching: false,
            ordering: false,
            lengthChange: false,
            pageLength: 5,
            processing: true,
            serverSide: true,
            "language": {
                "lengthMenu": "แสดง _MENU_ แถว",
                "zeroRecords": "ไม่พบข้อมูล",
                "info": "แสดงหน้า _PAGE_ จาก _PAGES_ หน้า",
                "search": "ค้นหา",
                "infoEmpty": "",
                "infoFiltered": "",
                "paginate": {
                    "first": "หน้าแรก",
                    "previous": "ย้อนกลับ",
                    "next": "ถัดไป",
                    "last": "หน้าสุดท้าย"
                },
                'processing': "กำลังโหลดข้อมูล",
            },
            ajax: {
                url: '{{ route('get_data_warehouse') }}',
                data: function(d) {

                    d.Where = {};
                    $('.myWhere').each(function() {
                        if ($.trim($(this).val()) && $.trim($(this).val()) != '0') {
                            d.Where[$(this).attr('name')] = $.trim($(this).val());
                            if ($('#Search').val() == '') $('#btn-Excel').css("display",
                                "initial");
                        }
                    });
                    d.Like = {};
                    $('.myLike').each(function() {
                        if ($.trim($(this).val()) && $.trim($(this).val()) != '0') {
                            d.Like[$(this).attr('name')] = $.trim($(this).val());
                        }
                    });
                    d.Custom = {};
                    $('.myCustom').each(function() {
                        if ($.trim($(this).val()) && $.trim($(this).val()) != '0' && $(this)
                            .attr('type') != 'checkbox') {
                            d.Custom[$(this).attr('name')] = $.trim($(this).val());
                        }
                        if ($.trim($(this).val()) && $.trim($(this).val()) != '0' && $(this)
                            .is(':checked')) {
                            d.Custom[$(this).attr('name')] = $.trim($(this).val());
                        }
                    });
                    d.branch_id_fk = {};
                    d.branch_id_fk = '{{ $branch[0]['id'] }}';
                },

            },
            columns: [{
                    data: "id",
                    title: "ลำดับ",
                    className: "table-report__action w-10 text-center",
                },
                {
                    data: "w_code",
                    title: "รหัสคลัง",
                    className: "table-report__action",
                },
                {
                    data: "w_name",
                    title: "ชื่อคลัง",
                    className: "table-report__action",
                },
                {
                    data: "w_maker",
                    title: "ผู้ทำรายการ",
                    className: "table-report__action",
                },
                {
                    data: "id",
                    title: "สถานะ",
                    className: "table-report__action",
                },
                {
                    data: "id",
                    title: "",
                    className: "table-report__action",
                },


            ],
            rowCallback: function(nRow, aData, dataIndex) {

                //คำนวนลำดับของ รายการที่แสดง
                var info = table_warehouse.page.info();
                var page = info.page;
                var length = info.length;
                var index = (page * length + (dataIndex + 1));

                var id = aData['id'];


                //แสดงเลขลำดับ
                $('td:nth-child(1)', nRow).html(`${index}`);
                var w_maker = aData['w_maker'];
                var time_w_maker = aData['updated_at'];
                $('td:nth-child(4)', nRow).html(
                    `<p class="font-medium whitespace-nowrap">${w_maker}</p>
                     <div class="text-slate-500 text-xs whitespace-nowrap mt-0.5">${ time_w_maker}</div>`);

                // สถานะ
                var status = aData['status'];
                var text_status = "";
                var status_bg = "";
                var edit_status = "";
                var edit_text_status = "";
                var icon = ""

                if (status == 1) {
                    text_status = "เปิดใช้งาน"
                    status_bg = "text-success"
                    edit_status = 2
                    edit_text_status = 'ระงับการใช้งาน'
                    icon = '<i class="fa-solid fa-user-large-slash"></i>'
                    icon_bg = 'bg-danger'
                } else {
                    text_status = "ไม่เปิดการใช้งาน"
                    status_bg = "text-danger"
                    edit_status = 1
                    edit_text_status = "เปิดใช้งาน"
                    icon = '<i class="fa-solid fa-user-large"></i>'
                    icon_bg = 'bg-success'
                }
                $('td:nth-child(5)', nRow).html(
                    ` <div class="${status_bg}"> ${text_status} </div> `)

                // // Action
                $('td:nth-child(6)', nRow).html(
                    `
                    <a data-tw-toggle="modal" data-tw-target="#info_warehouse" onclick="get_data_info_warehouse(${id})" class="btn btn-sm btn-warning mr-2 "><i class="fa-solid fa-pen-to-square"></i></a>
                    `
                );
            },
        });
        $('.myWhere,.myLike,.datepicker,.iSort,.myCustom').on('change', function(e) {
            table_warehouse.draw();
        });
    });
</script>
