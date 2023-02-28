<!-- Modal -->
<div class="modal fade" id="transferModal" tabindex="-1" aria-labelledby="transferModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
        <form id="form_transfer" method="post" enctype="multipart/form-data">
            @csrf
            <div class="modal-content borderR25">
                <div class="modal-header">
                    <h5 class="modal-title" id="transferModalLabel">โอนเงิน eWallet</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning d-flex align-items-center" role="alert">
                        <i class='bx bxs-info-circle me-2'></i>
                        <div>
                            การโอนเงิน eWallet ขั้นต่ำ = 300 บาท
                        </div>
                    </div>
                    <div class="row gx-2">
                        <div class="col-sm-6">
                            <div class="alert alert-white p-2 h-82 borderR10">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <img src="{{ asset('frontend/images/man.png') }} " alt="..."
                                            width="30px">
                                    </div>
                                    <div class="flex-grow-1 ms-2">
                                        <p class="small mb-0"> {{ Auth::guard('c_user')->user()->user_name }} </p>
                                        <h6> {{ Auth::guard('c_user')->user()->name }}
                                            {{ Auth::guard('c_user')->user()->last_name }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="alert alert-purple p-2 h-82 borderR10">
                                <p class="small">eWallet คงเหลือ</p>
                                <p class="text-end mb-0"><span class="h5 text-purple1 bg-opacity-100">
                                        {{ Auth::guard('c_user')->user()->ewallet }} </span>฿
                                </p>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="card p-2 borderR10 mb-3">
                                <h5 class="text-center">รหัสรับโอน eWallet</h5>
                                <div class="row gx-3">
                                    <div class="col-md-6 mb-3">
                                        <label for="" class="form-label">รหัสสมาชิก <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="customers_id_receive" class="form-control" required
                                            id="customers_id_receive">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="" class="form-label">ชื่อสมาชิก</label>
                                        <input type="text" readonly name="customers_name_receive"
                                            class="form-control" id="customers_name_receive">
                                    </div>
                                </div>
                                <div class="row gx-3 mb-3">
                                    <label for="" class="col-sm-3 col-form-label">ยอดโอน <span
                                            class="text-danger">*</span></label>
                                    <div class="col-sm-9">


                                        @if( Auth::guard('c_user')->user()->user_name == '0534768')
                                        <input type="number" name="amt" min="1" step="1" required class="form-control text-purple1 bg-opacity-100" id="amt">
                                        @else
                                        <input type="number" name="amt" min="300" step="0.01" required class="form-control text-purple1 bg-opacity-100" id="amt">

                                        @endif
                                        <p class="small text-muted mb-0">**ไม่สามารถโอนได้มากกว่ายอดเงินคงเหลือที่มีอยู่
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="alert alert-danger d-flex" role="alert">
                        <i class='bx bxs-error me-2 bx-sm'></i>
                        <div>
                            คำเตือน ! บริษัทไม่สามารถแก้ไขหากโอน <u>ผิดพลาด</u> กรุณาตรวจสอบความถูกต้องก่อนทำการโอน
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between border-0">
                    <button type="button" class="btn btn-outline-dark rounded-pill"
                        data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="submit" disabled class="btn btn-p1 rounded-pill d-flex align-items-center"><i
                            class='bx bxs-check-circle me-2'></i>ทำรายการ</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="transferModal2" tabindex="-1" aria-labelledby="transferModal2Label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
        <div class="modal-content borderR25">
            <div class="modal-header justify-content-center">
                <h5 class="modal-title" id="transferModal2Label">ท่านทำรายการโอนเงิน eWallet</h5>
            </div>
            <div class="modal-body">
                <div class="row gx-2">
                    <div class="col-sm-5">
                        <p class="mb-0">ผู้โอน</p>
                        <div class="alert alert-white p-2 borderR10">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <img src="{{ asset('frontend/images/man.png') }}  " alt="..." width="30px">
                                </div>
                                <div class="flex-grow-1 ms-2">
                                    <p class="small mb-0">MLM0534768</p>
                                    <h6>ชัยพัทธ์ ศรีสดุดี</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-2 text-center">
                        <p class="mb-0">&nbsp;</p>
                        <p>ไปยัง</p>
                    </div>
                    <div class="col-sm-5">
                        <p class="mb-0">ผู้รับ</p>
                        <div class="alert alert-white p-2 borderR10">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <img src="{{ asset('frontend/images/man.png') }}" alt="..." width="30px">
                                </div>
                                <div class="flex-grow-1 ms-2">
                                    <p class="small mb-0">MLM0534890</p>
                                    <h6>ยศธร อินทรประสาท</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <h5 class="text-center">ยอดโอน</h5>
                        <div class="card p-2 borderR10 mb-3 text-center">
                            <h4 class="mb-0 text-purple1 bg-opacity-100">4000 บาท</h4>
                        </div>
                    </div>
                </div>
                <div class="alert alert-danger d-flex" role="alert">
                    <i class='bx bxs-error me-2 bx-sm'></i>
                    <div>
                        กรุณาแคปหน้าจอการทำรายการเพื่อใช้ตรวจสอบกรณีมีปัญหาในการทำรายการ
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between border-0">
                <button type="button" class="btn btn-outline-dark rounded-pill" data-bs-target="#transferModal2"
                    data-bs-toggle="modal">ยกเลิก</button>
                <button type="button" class="btn btn-p1 rounded-pill d-flex align-items-center"
                    data-bs-target="#transferModal3" data-bs-toggle="modal"><i
                        class='bx bxs-check-circle me-2'></i>ยืนยันทำรายการ</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="transferModal3" tabindex="-1" aria-labelledby="transferModal3Label"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
        <div class="modal-content borderR25">
            <div class="modal-header justify-content-center">
                <h5 class="modal-title" id="transferModal3Label">คุณได้ทำรายการโอนเงินเรียบร้อยแล้ว</h5>
            </div>
            <div class="modal-body">
                <div class="row gx-2">
                    <div class="col-sm-12 text-center">
                        <i class='far fa-check-circle text-success fa-5x mb-3'></i>
                        <h5 class="text-center">ทำรายการสำเร็จ</h5>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-center border-0">
                <button type="button" class="btn btn-p1 rounded-pill" data-bs-dismiss="modal">ตกลง</button>
            </div>
        </div>
    </div>
</div>


<script>
    function printErrorMsg(msg) {

        $('._err').text('');
        $.each(msg, function(key, value) {
            let class_name = key.split(".").join("_");
            console.log(class_name);
            $('.' + class_name + '_err').text(`*${value}*`);
        });
    }

    $('#form_transfer').submit(function(e) {

        e.preventDefault();
        var formData = new FormData($(this)[0]);
        $.ajax({
            url: '{{ route('frontendtransfer') }}',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(data) {
                if (data.status == "success") {

                    Swal.fire({
                        icon: 'success',
                        title: 'บันทึกสำเร็จ',
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'ปิด',
                    }).then((result) => {
                        location.href = "eWallet_history";
                    })
                } else {
                    Swal.fire({
                            icon: 'error',
                            title: data['ms'],
                        })
                    // printErrorMsg(data.error);
                }
            }
        });
    });
</script>
