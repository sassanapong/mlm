<script>
    $(function() {
        table_orders = $('#table_orders').DataTable({
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
                url: '{{ route('get_data_order_list') }}',
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
                    data: "code_order",
                    title: "รหัส",
                    className: "table-report__action w-10 text-center whitespace-nowrap",
                },
                {
                    data: "customers_user_name",
                    title: "รหัสผู้สั่งซื้อ",
                    className: "table-report__action w-10 text-center whitespace-nowrap",
                },
                {
                    data: "name",
                    title: "ผู้สั่งซื้อ",
                    className: "table-report__action  text-center whitespace-nowrap",
                },
                {
                    data: "pay_type",
                    title: "รูปแบบการชำระเงิน",
                    className: "table-report__action  text-center whitespace-nowrap",
                },
                {
                    data: "total_price",
                    title: "จำนวนเงิน",
                    className: "table-report__action  text-right whitespace-nowrap",
                },
                {
                    data: "created_at",
                    title: "วันที่สั่งซื้อ",
                    className: "table-report__action  text-center whitespace-nowrap",
                },

                {
                    data: "type_order",
                    title: "Type",
                    className: "table-report__action  text-center whitespace-nowrap",
                },

                {
                    data: "api",
                    title: "System",
                    className: "table-report__action  text-center whitespace-nowrap",
                },



                {
                    data: "detail",
                    title: "สถานะ",
                    className: "table-report__action  text-center whitespace-nowrap",
                },

           
                {
                    data: "count_print_detail",
                    title: "จำนวนปริ้น",
                    className: "table-report__action  text-center whitespace-nowrap",
                },
                {
                    data: "id",
                    title: "",
                    className: "table-report__action text-center",
                },
                {
                    data: "id",
                    title: "",
                    className: "table-report__action text-center",
                },
                {
                    data: "id",
                    title: "",
                    className: "table-report__action text-center",
                },


            ],
            rowCallback: function(nRow, aData, dataIndex) {

                //คำนวนลำดับของ รายการที่แสดง
                var info = table_orders.page.info();
                var page = info.page;
                var length = info.length;
                var index = (page * length + (dataIndex + 1));

                var id = aData['id'];

                //แสดงเลขลำดับ
                $('td:nth-child(1)', nRow).html(`${index}`);


                //แสดงสถานะ
                var status = aData['detail'];
                var css_class = aData['css_class'];
                $('td:nth-last-child(5)', nRow).html(
                    ` <p class="text-${css_class}"> ${status} </p> `);

                // Action
                var code_order = aData['code_order'];
                $('td:nth-last-child(3)', nRow).html(
                    `<a data-tw-toggle="modal" data-tw-target="#tracking" onclick="updatestatus('${code_order}')" class="btn text-white btn-sm btn-success mr-2 "> <i class="fa-solid fa-box"></i> </a>`
                );
                $('td:nth-last-child(2)', nRow).html(
                    `<a  onclick="view_detail_oeder('${code_order}')" class="btn btn-sm btn-warning mr-2 "> <i class="fa-solid fa-magnifying-glass"></i> </a>`
                );
                $('td:nth-last-child(1)', nRow).html(
                    `<a  onclick="view_detail_oeder_pdf('${code_order}')" class="btn btn-sm btn-success mr-2 text-white "> <i class="fa-solid fa-print"></i> </a>`
                );
            },
        });
        $('.myWhere,.myLike,.datepicker,.iSort,.myCustom').on('change', function(e) {
            table_orders.draw();
        });
    });



    function view_detail_oeder(code_order) {

        window.open(`view_detail_oeder/${code_order}`)
    }

    function updatestatus(code_order) {
        $('#code_order').val(code_order)
    }




    function view_detail_oeder_pdf(code_order) {

        // table_orders.draw();
        Swal.fire({
                title: 'รอสักครู่...',
                html: 'ระบบกำลังเตรียมไฟล์ PDF...',
                didOpen: () => {
                    Swal.showLoading()
                },
            }),

            $.ajax({
                url: "{{ route('view_detail_oeder_pdf') }}",
                type: 'post',
                data: {
                    '_token': '{{ csrf_token() }}',
                    'code_order': code_order
                },
                success: function(data) {
                    Swal.close();
                    // บน serve ใช้อันนี้
                    let full_url = '/local/public/pdf/result.pdf';
                    // local
                    // let full_url = '/mlm/local/public/pdf/result.pdf';
                    window.open(full_url);


                }

            });




    }
</script>
