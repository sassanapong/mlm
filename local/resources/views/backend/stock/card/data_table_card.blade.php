<script>
    $(function() {
        table_stock_card = $('#table_stock_card').DataTable({
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
                url: `{{ route('get_stock_card') }}`,
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
                    d.product_id_fk = {};
                    d.product_id_fk = '{{ $product_id_fk }}';
                    d.branch_id_fk = {};
                    d.branch_id_fk = '{{ $branch_id_fk }}';
                    d.warehouse_id_fk = {};
                    d.warehouse_id_fk = '{{ $warehouse_id_fk }}';
                },
            },
            columns: [{
                    data: "product_id_fk",
                    title: "ลำดับ",
                    className: "table-report__action w-10 text-center",
                },

                {
                    data: "product_id_fk",
                    title: "สินค้า",
                    className: "table-report__action whitespace-nowrap",
                },
                {
                    data: "lot_number",
                    title: "ล็อตสินค้า",
                    className: "table-report__action ",
                },
                {
                    data: "doc_no",
                    title: "เลขที่เอกสาร",
                    className: "table-report__action ",
                },
                {
                    data: "doc_date",
                    title: "วันที่เอกสาร",
                    className: "table-report__action ",
                },
                {
                    data: "lot_expired_date",
                    title: "วันหมดอายุ",
                    className: "table-report__action ",
                },

                {
                    data: "action_date",
                    title: "วันที่รับเข้า",
                    className: "table-report__action ",
                },
                {
                    data: "in_out",
                    title: "ประเภท",
                    className: "table-report__action text-center",
                },
                {
                    data: "amt",
                    title: "จำนวน",
                    className: "table-report__action text-right",
                },
                {
                    data: "action_user",
                    title: "ผู้ทำรายการ",
                    className: "table-report__action ",
                },


            ],
            rowCallback: function(nRow, aData, dataIndex) {

                //คำนวนลำดับของ รายการที่แสดง
                var info = table_stock_card.page.info();
                var page = info.page;
                var length = info.length;
                var index = (page * length + (dataIndex + 1));


                //แสดงเลขลำดับ
                $('td:nth-child(1)', nRow).html(`${index}`);

                var in_out = aData['in_out'];
                var text_bg = "";

                if (in_out == 'รับเข้า') {
                    text_bg = 'text-success'
                } else {
                    text_bg = 'text-danger'
                }

                $('td:nth-child(8)', nRow).html(
                    `
                    <p class="${text_bg}"> ${in_out}</p>
    
                `);
            },
        });
        $('.myWhere,.myLike,.datepicker,.iSort,.myCustom').on('change', function(e) {
            table_stock_card.draw();
        });


    });
</script>
