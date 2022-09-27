<script>
    $(function() {
        table_branch = $('#table_branch').DataTable({
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
                url: '{{ route('get_data_branch') }}',
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
                },
            },
            columns: [{
                    data: "id",
                    title: "ลำดับ",
                    className: "table-report__action w-10 text-center",
                },
                {
                    data: "b_code",
                    title: "รหัสสาขา",
                    className: "table-report__action",
                },
                {
                    data: "b_name",
                    title: "ชื่อสาขา",
                    className: "table-report__action",
                },
                {
                    data: "warehouse",
                    title: "คลัง",
                    className: "table-report__action",
                },
                {
                    data: "tel",
                    title: "เบอร์โทรศัพท์",
                    className: "table-report__action",
                },
                {
                    data: "b_maker",
                    title: "ผู้ทำรายการ",
                    className: "table-report__action",
                },
                {
                    data: "tel",
                    title: "สถานะ",
                    className: "table-report__action",
                },
                {
                    data: "id",
                    title: "",
                    className: "table-report__action text-center",
                },


            ],
            rowCallback: function(nRow, aData, dataIndex) {

                //คำนวนลำดับของ รายการที่แสดง
                var info = table_branch.page.info();
                var page = info.page;
                var length = info.length;
                var index = (page * length + (dataIndex + 1));

                var id = aData['id'];

                //แสดงเลขลำดับ
                $('td:nth-child(1)', nRow).html(`${index}`);


                $('td:nth-child(4)', nRow).html(`
                <div class="box_warehouse "></div>
                `);


                var warehouse = aData['warehouse'];

                warehouse.forEach((val, key) => {

                    val.forEach((items, i_key) => {
                        var text_bg = ''
                        if (items['status'] == 99) {
                            text_bg = 'text-danger'
                        }

                        $('td:nth-child(4) .box_warehouse', nRow).append(
                            `<p  class="${text_bg}" >${items['w_code']} : ${items['w_name']}</p> `
                        );
                    })
                });

                // คลัง


                var b_maker = aData['b_maker'];
                var time_b_maker = aData['updated_at'];
                // ผู้ทำรายการ
                $('td:nth-child(6)', nRow).html(
                    `<p class="font-medium whitespace-nowrap">${b_maker}</p>
                     <div class="text-slate-500 text-xs whitespace-nowrap mt-0.5">${ time_b_maker}</div>`)

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
                $('td:nth-child(7)', nRow).html(
                    ` <div class="${status_bg}"> ${text_status} </div> `)




                // Action
                $('td:nth-child(8)', nRow).html(
                    `
                    <a data-tw-toggle="modal" data-tw-target="#info_branch" onclick="get_data_info_branch(${id})" class="btn btn-sm btn-warning mr-2 "><i class="fa-solid fa-pen-to-square"></i></a>
                    
                    <a  onclick="view_warehouse(${id})" class="btn btn-sm btn-twitter mr-2 text-black"> <i class="fa-solid fa-warehouse"></i> </a>
                    `
                );


            },
        });
        $('.myWhere,.myLike,.datepicker,.iSort,.myCustom').on('change', function(e) {
            table_branch.draw();
        });
    });
</script>
