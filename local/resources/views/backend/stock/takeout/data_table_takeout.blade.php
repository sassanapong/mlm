<script>
    $(function() {
        table_takeout = $('#table_takeout').DataTable({
            searching: false,
            ordering: false,
            lengthChange: false,
            pageLength: 10,
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
                url: '{{ route('get_data_takeout') }}',
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
                    data: "branch_id_fk",
                    title: "สาขา",
                    className: "table-report__action whitespace-nowrap",
                },
                {
                    data: "warehouse_id_fk",
                    title: "คลัง",
                    className: "table-report__action ",
                },
                {
                    data: "product_id_fk",
                    title: "สินค้า",
                    className: "table-report__action whitespace-nowrap",
                },
                {
                    data: "amt",
                    title: "จำนวน",
                    className: "table-report__action ",
                },
                {
                    data: "lot_number",
                    title: "ล็อตสินค้า",
                    className: "table-report__action ",
                },
                {
                    data: "lot_expired_date",
                    title: "วันหมดอายุ",
                    className: "table-report__action ",
                },

                {
                    data: "created_at",
                    title: "วันที่นำออก",
                    className: "table-report__action ",
                },
                {
                    data: "action_user",
                    title: "ผู้ทำรายการ",
                    className: "table-report__action ",
                },


            ],
            rowCallback: function(nRow, aData, dataIndex) {

                //คำนวนลำดับของ รายการที่แสดง
                var info = table_takeout.page.info();
                var page = info.page;
                var length = info.length;
                var index = (page * length + (dataIndex + 1));
                var id = aData['id'];

                //แสดงเลขลำดับ
                $('td:nth-child(1)', nRow).html(`${index}`);
            },
        });
        $('.myWhere,.myLike,.datepicker,.iSort,.myCustom').on('change', function(e) {
            table_takeout.draw();
        });
    });
</script>
