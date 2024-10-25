<script>
    $(function() {
        table_ewallet = $('#workL').DataTable({
            dom: 'Bfrtip',
            buttons: ['excel'],
            searching: false,
            ordering: false,
            lengthChange: false,
            responsive: true,
            paging: true,
            pageLength: 50,
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
                url: '{{ route('get_transfer') }}',
                data: function(d) {

                    d.user_name = $('#user_name').val();
                        // d.name = $('#name').val();
                        d.status = $('#status').val();
                        d.s_date = $('#s_date').val();
                        d.e_date = $('#e_date').val();


                },
            },
            columns: [{
                    data: "id",
                    title: "ลำดับ",
                    className: "table-report__action w-10 text-center",
                },
                {
                    data: "transaction_code",
                    title: "รหัสรายการ",
                    className: "table-report__action w-10 whitespace-nowrap",
                },
                {
                    data: "created_at",
                    title: "วันที่ทำรายการ",
                    className: "table-report__action w-10 text-center whitespace-nowrap",
                },
                {
                    data: "user_name",
                    title: "ผู้โอน",
                    className: "table-report__action w-24 whitespace-nowrap",
                },
                {
                    data: "customers_id_receive",
                    title: "ผู้รับ",
                    className: "table-report__action w-24 whitespace-nowrap",
                },

                {
                    data: "amt",
                    title: "จำนวนเงิน",
                    className: "table-report__action w-10 text-right whitespace-nowrap",
                },
                {
                    data: "edit_amt",
                    title: "จำนวนเงินที่แก้ไข",
                    className: "table-report__action w-12 text-right whitespace-nowrap",
                },
                {
                    data: "type",
                    title: "ประเภท",
                    className: "table-report__action w-10 text-center",
                },
                {
                    data: "status",
                    title: "สถานะ",
                    className: "table-report__action w-10 text-center whitespace-nowrap",
                },
                {
                    data: "date_mark",
                    title: "วันที่อนุมัติ",
                    className: "table-report__action w-10 text-center whitespace-nowrap",
                },
                {
                    data: "ew_mark",
                    title: "ผู้อนุมัติ",
                    className: "table-report__action w-10 text-center whitespace-nowrap",
                },
                {
                    data: "id",
                    title: "",
                    className: "table-report__action w-10 text-center",
                },


            ],
            rowCallback: function(nRow, aData, dataIndex) {
                //คำนวนลำดับของ รายการที่แสดง
                var info = table_ewallet.page.info();
                var page = info.page;
                var length = info.length;
                var index = (page * length + (dataIndex + 1));
                var id = aData['id'];

                //แสดงเลขลำดับ
                $('td:nth-child(1)', nRow).html(`${index}`);


                //สถานะ

                var status = aData['status'];
                var text_status = "";
                var status_bg = "";



                if (status == 1) {
                    text_status = "รออนุมัติ"
                    status_bg = "text-warning"

                }
                if (status == 2) {
                    text_status = "อนุมัติ"
                    status_bg = "text-success"

                }
                if (status == 3) {
                    text_status = "ไม่อนุมัติ"
                    status_bg = "text-danger"
                }

                var edit_amt = aData['edit_amt'];
                $('td:nth-child(7)', nRow).html(
                    ` <div class="text-warning">${edit_amt} </div> `
                );
                var type_note = aData['type_note'];
                $('td:nth-child(9)', nRow).html(
                    ` <div class="${status_bg}"> ${text_status} ${type_note == null ? '': `(${type_note})` } </div> `
                );

                //Action
                $('td:nth-last-child(1)', nRow).html(
                    `<a data-tw-toggle="modal" data-tw-target="#info_ewallet" onclick="get_data_info_ewallet(${id})" class="btn btn-sm btn-warning mr-2 "><i class="fa-solid fa-pen-to-square"></i></a>`
                );
            },
        });



        // $('.myWhere,.myLike,.datepicker,.iSort,.myCustom').on('change', function(e) {
        //     table_ewallet.draw();
        // });

        $('#search-form').on('click', function(e) {
            table_ewallet.draw();
                e.preventDefault();
            });
    });
</script>
