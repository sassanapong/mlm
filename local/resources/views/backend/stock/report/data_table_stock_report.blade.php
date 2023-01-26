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
                    data: "materials_id_fk",
                    title: "สินค้า",
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
                    className: "table-report__action text-right",

                },
                {
                    data: "branch_id_fk",
                    title: "คลัง",
                    className: "table-report__action",

                },
                {
                    data: "warehouse_id_fk",
                    title: "สาขา",
                    className: "table-report__action",

                },
                {
                    data: "s_maker",
                    title: "",
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
                        `<p class="mt-4">${val}</p> `
                    );
                });

                //lot_expired_date
                $('td:nth-child(4)', nRow).html(`
                <div class="box_lot_expired_date "></div>
                `);
                var lot_expired_date = aData['lot_expired_date'];
                lot_expired_date.forEach((val, key) => {
                    $('td:nth-child(4) .box_lot_expired_date', nRow).append(
                        `<p class="mt-4">${val}</p> `
                    );
                });

                //amt
                $('td:nth-child(5)', nRow).html(`
                <div class="box_amt "></div>
                `);
                var amt = aData['amt'];

                amt.forEach((val, key) => {
                    $('td:nth-child(5) .box_amt', nRow).append(
                        `<p class="mt-4">${ new Intl.NumberFormat().format(val.amt)}  </p> `
                    );
                });

                //branch_id_fk
                $('td:nth-child(6)', nRow).html(`
                <div class="box_branch_id_fk "></div>
                `);
                var branch_id_fk = aData['branch_id_fk'];
                branch_id_fk.forEach((val, key) => {
                    $('td:nth-child(6) .box_branch_id_fk', nRow).append(
                        `<p class="mt-4">${val}</p> `
                    );
                });

                //warehouse_id_fk
                $('td:nth-child(7)', nRow).html(`
                <div class="box_warehouse_id_fk "></div>
                `);
                var warehouse_id_fk = aData['warehouse_id_fk'];
                warehouse_id_fk.forEach((val, key) => {
                    $('td:nth-child(7) .box_warehouse_id_fk', nRow).append(
                        `<p class="mt-4">${val}</p> `
                    );
                });
                //btn_info
                $('td:nth-child(8)', nRow).html(`
                <div class="box_btn_info "></div>
                `);
                var card_product_id = aData['card_product_id'];
                var card_branch_id_fk = aData['card_branch_id_fk'];
                var card_warehouse_id_fk = aData['card_warehouse_id_fk'];
                var lot_expired_date = aData['lot_expired_date'];
                var s_maker = aData['s_maker'];
                var lot_number = aData['lot_number'];

                s_maker.forEach((val, key) => {
                    $('td:nth-child(8) .box_btn_info', nRow).append(
                        `
                        <p onclick="view_stock_card(${card_product_id},${card_branch_id_fk},${card_warehouse_id_fk[key]},'${lot_expired_date[key]}','${lot_number[key]}')" class="mt-4 w-24 btn_Stock_Card text-center">
                            STOCK CARD
                        </p>
                        `
                    );
                });
            },

            columnDefs: [{
                visible: true,
                targets: 4
            }],
            order: [
                [4, 'asc']
            ],
            drawCallback: function(settings) {

                var api = this.api();

                var rows = api.rows({
                    page: 'current'
                }).nodes();
                var last = null;

                api.column(4, {
                        page: 'current'
                    })
                    .data()

                    .each(function(val, key) {
                        var sum_amt_out = 0;
                        var sum_amt_in = 0;

                        if (last !== val) {
                            val.forEach((item, i_key) => {
                                if (item.in_out == 2) {
                                    sum_amt_out -= item.amt;

                                } else {
                                    sum_amt_in += item.amt;

                                }
                            });
                            var sumtotal = sum_amt_in - sum_amt_out;


                            $(rows)
                                .eq(key)
                                .after(`
                                    <tr class="intro-x  test">
                                    <td colspan="3"></td>
                                    <td colspan="2" class="text-right font-semibold">รวมทั้งหมด ${new Intl.NumberFormat().format(sumtotal) }  </td>
                                    <td colspan="3"></td>
                                    </tr>`);

                            last = val;
                        }

                    });
            },


        });


        $('.myWhere,.myLike,.datepicker,.iSort,.myCustom').on('change', function(e) {
            table_stock_report.draw();
        });


    });


    function view_stock_card(materials_id_fk, branch_id_fk, warehouse_id_fk, lot_expired_date, lot_number) {

        // console.log(product_id_fk, branch_id_fk, warehouse_id_fk, lot_expired_date, lot_number)
        window.location.href =
            `{{ URL::to('admin/stock/stockcard/${materials_id_fk}/${branch_id_fk}/${warehouse_id_fk}/${lot_expired_date}/${lot_number}') }}`
    }
</script>
