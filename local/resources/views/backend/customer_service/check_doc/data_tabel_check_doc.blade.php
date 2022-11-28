<script>
    $(function() {
        check_doc = $('#check_doc').DataTable({
            searching: false,
            lengthChange: false,
            responsive: true,
            pageLength: 20,
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
                url: '{{ route('get_check_doc') }}',
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
                    data: "user_name",
                    title: "รหัส",
                    className: "table-report__action text-center",
                },
                {
                    data: "name",
                    title: "ชื่อ - สกุล",
                    className: "table-report__action  ",
                },

                {
                    data: "id",
                    title: "เอกสาร",
                    className: "table-report__action  ",
                },
                {
                    data: "id",
                    title: "ดูข้อมูลทั้งหมด",
                    className: "table-report__action w-24 text-center ",
                },
                {
                    data: "id",
                    title: "เข้าสู่ระบบ",
                    className: "table-report__action w-24 text-center ",
                },


            ],
            rowCallback: function(nRow, aData, dataIndex) {
                //คำนวนลำดับของ รายการที่แสดง
                var info = check_doc.page.info();
                var page = info.page;
                var length = info.length;
                var index = (page * length + (dataIndex + 1));
                var id = aData['id'];

                //แสดงเลขลำดับ
                $('td:nth-child(1)', nRow).html(`${index}`);

                var user_name = aData['user_name'];
                // ชื่อ นามสกุล เบอร์
                var name = aData['name'];
                var phone = aData['phone'];
                $('td:nth-child(3)', nRow).html(`
                <p class="font-medium whitespace-nowrap">${name}</p>
                <div class="text-slate-500 text-xs whitespace-nowrap mt-0.5">${phone}</div>
                `);

                // เอกสาร

                var regis_doc1_status = aData['regis_doc1_status'];
                var regis_doc4_status = aData['regis_doc4_status'];

                var text_color_doc_1 = aData['text_color_doc_1'];
                var text_color_doc_4 = aData['text_color_doc_4'];


                var doc_1 = "";
                if (regis_doc1_status != 0) {
                    doc_1 = `<a data-tw-toggle="modal" data-tw-target="#info_card" onclick="get_info_card('${user_name}')">
                    <i class="fa-regular fa-address-card icon_size mr-3 tooltip ${text_color_doc_1}"
                      title="ข้อมูลบัตรประชาชน">
                      </i>
                    </a>`
                }
                var doc_4 = "";
                if (regis_doc4_status != 0) {
                    doc_4 = `
                    <a data-tw-toggle="modal" data-tw-target="#info_bank" onclick="get_info_bank('${user_name}')">
                    <i class="fa-solid fa-money-check-dollar icon_size mr-3 tooltip  ${text_color_doc_4}"
                        title="ข้อมูลธนาคาร">
                        </i>
                    </a>`
                }

                $('td:nth-child(4)', nRow).html(`
                    ${doc_1}  ${doc_4}
       
                `);


                //Action
                $('td:nth-last-child(1)', nRow).html(
                    `<a  onclick="admin_login_user(${id})" class="btn btn-sm btn-success mr-2 text-white"> <i class="fa-solid fa-right-to-bracket"></i> </a>`
                );
                $('td:nth-last-child(2)', nRow).html(
                    `<a href="info_customer/${id}" target="_blank" onclick="info_customer(${id})" class="btn btn-sm btn-success mr-2 text-white"> <i class="fa-solid fa-magnifying-glass"></i> </a>`
                );
            },
        });
        $('.myWhere,.myLike,.datepicker,.iSort,.myCustom').on('change', function(e) {
            check_doc.draw();
        });
    });
</script>




<script>
    function admin_login_user(id) {
        window.open(`admin_login_user/${id}`);
    }
</script>


<script>
    function get_info_card(user_name) {
        $.ajax({
            url: '{{ route('admin_get_info_card') }}',
            method: 'POST',
            data: {
                '_token': '{{ csrf_token() }}',
                'user_name': user_name
            },
            success: function(data) {
                create_value_info_card(data);
            }
        });
    }

    function create_value_info_card(data) {

        if (data.id == null) {

            $('#info_card').find('.info_detail_card_null').show()
            $('#info_card').find('.modal-footer').hide();
            $('#info_card').find('.info_detail_card').hide()
        } else {
            $('#info_card').find('.info_detail_card_null').hide()
            $('#info_card').find('.info_detail_card').show()
            for (const [key, value] of Object.entries(data)) {
                $('#info_card').find('#' + key).val(value);
            }

            $('#img_crad').attr('src', `{{ asset('${data.url}/${data.img_card}') }}`);

            $('.user_name').val(data.user_name);

            if (data.regis_doc1_status == 1) {
                $('#info_card').find('.modal-footer').hide();
            } else {
                $('#info_card').find('.modal-footer').show();
            }
        }
    }
</script>


<script>
    function get_info_bank(user_name) {
        $.ajax({
            url: '{{ route('admin_get_info_bank') }}',
            method: 'POST',
            data: {
                '_token': '{{ csrf_token() }}',
                'user_name': user_name
            },
            success: function(data) {

                create_value_info_bank(data);
            }
        });
    }

    function create_value_info_bank(data) {

        console.log(data.regis_doc4_status);
        if (data.account_name == null) {
            $('#info_bank').find('.info_detail_card_null').show()
            $('#info_bank').find('.modal-footer').hide();
            $('#info_bank').find('.info_detail_card').hide()
        } else {
            $('#info_bank').find('.info_detail_card_null').hide()
            $('#info_bank').find('.info_detail_card').show()
            for (const [key, value] of Object.entries(data)) {
                $('#info_bank').find('#' + key).val(value);
            }

            $('#img_bank').attr('src', `{{ asset('${data.url}/${data.img_bank}') }}`);

            $('.user_name').val(data.user_name);

            if (data.regis_doc4_status == 1) {
                $('#info_bank').find('.modal-footer').hide();
            } else {
                $('#info_bank').find('.modal-footer').show();
            }
        }
    }
</script>
