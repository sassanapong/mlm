<script>
    $(function() {
        table_stock_report = $('#table_stock_report').DataTable({
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
                url: '{{ route('get_data_stock_report') }}',
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
                    data: "product_id_fk",
                    title: "รหัสสินค่า : ชื่อสินค้า",
                    className: "table-report__action",
                },
                {

                    data: "lot_number",
                    title: "ล็อตสินค้า",
                    className: "table-report__action",

                },
                {

                    data: "lot_expired_date",
                    title: "วันหมดอายุ",
                    className: "table-report__action",

                },
                {

                    data: "amt",
                    title: "จำนวน",
                    className: "table-report__action",

                },



            ],
            rowCallback: function(nRow, aData, dataIndex) {

                //คำนวนลำดับของ รายการที่แสดง
                var info = table_stock_report.page.info();
                var page = info.page;
                var length = info.length;
                var index = (page * length + (dataIndex + 1));
                var id = aData['id'];

                //แสดงเลขลำดับ
                $('td:nth-child(1)', nRow).html(`${index}`);

                //lot_product
                $('td:nth-child(3)', nRow).html(`
                <div class="box_lot_product  "></div>
                `);
                var lot_number = aData['lot_number'];
                lot_number.forEach((val, key) => {
                    $('td:nth-child(3) .box_lot_product', nRow).append(
                        `<p class="mt-3">${val}</p> `
                    );
                });

                //lot_expired_date
                $('td:nth-child(4)', nRow).html(`
                <div class="box_lot_expired_date "></div>
                `);
                var lot_expired_date = aData['lot_expired_date'];
                lot_expired_date.forEach((val, key) => {
                    $('td:nth-child(4) .box_lot_expired_date', nRow).append(
                        `<p class="mt-3">${val}</p> `
                    );
                });

                //amt
                $('td:nth-child(5)', nRow).html(`
                <div class="box_amt "></div>
                `);
                var amt = aData['amt'];
                amt.forEach((val, key) => {
                    $('td:nth-child(5) .box_amt', nRow).append(
                        `<p class="mt-3">${val}</p> `
                    );
                });



            },
        });
        $('.myWhere,.myLike,.datepicker,.iSort,.myCustom').on('change', function(e) {
            table_stock_report.draw();
        });
    });
</script>
