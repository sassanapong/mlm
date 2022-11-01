<!-- Modal -->
<form action="{{ route('frontendwithdraw') }}" method="post">
    @csrf
    <div class="modal fade" id="withdrawModal" tabindex="-1" aria-labelledby="withdrawModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
            <div class="modal-content borderR25">
                <div class="modal-header">
                    <h5 class="modal-title" id="withdrawModalLabel">ถอนเงิน eWallet</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning d-flex align-items-center" role="alert">
                        <i class='bx bxs-info-circle me-2'></i>
                        <div>
                            การถอนเงิน eWallet ขั้นต่ำ = 300 บาท
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
                                <p class="small">eWallet ที่สามารถถอนได้</p>
                                <p class="text-end mb-0"><span class="h5 text-purple1 bg-opacity-100">
                                    @php
                                    $ewallet_use = Auth::guard('c_user')->user()->ewallet_use;
                                    $ewallet = Auth::guard('c_user')->user()->ewallet;
                                    if($ewallet_use > $ewallet){
                                        $price_ewallet = Auth::guard('c_user')->user()->ewallet;
                                    }else{
                                        $price_ewallet = Auth::guard('c_user')->user()->ewallet_use;
                                    }
                                    @endphp
                                        {{ number_format($price_ewallet, 2) }}</span>฿
                                </p>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="card p-2 borderR10 mb-3">
                                <h5 class="text-center">ยอดถอน</h5>
                                <input type="number" name="amt" min="300" value="0" step="0.01"
                                    id="amtwithdraw" required
                                    class="form-control text-purple1 bg-opacity-100 form-control-lg">
                                <p class="small text-muted mb-0">** ไม่สามารถโอนได้มากกว่ายอดเงินคงเหลือที่มีอยู่</p>
                            </div>
                        </div>
                    </div>
                    <div class="alert alert-danger d-flex" role="alert">
                        <i class='bx bxs-error me-2 bx-sm'></i>
                        <div>
                            คำเตือน ! ต้องมีการยืนยันตัวตนและยืนยันข้อมูลทางบัญชีแล้วเท่านั้น (ข้อมูลส่งบัญชี)
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between border-0">
                    <button type="button" class="btn btn-outline-dark rounded-pill"
                        data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="button" onclick="withdraw_confirm()"
                        class="btn btn-p1 rounded-pill d-flex align-items-center"><i
                            class='bx bxs-check-circle me-2'></i>ทำรายการ</button>
                </div>
            </div>

        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="withdrawModal2" tabindex="-1" aria-labelledby="withdrawModal2Label" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
            <div class="modal-content borderR25">
                <div class="modal-header justify-content-center">
                    <h5 class="modal-title" id="withdrawModal2Label">ท่านทำรายการถอนเงิน eWallet</h5>
                </div>
                <div class="modal-body">
                    <div class="row gx-2 justify-content-center">
                        <div class="col-sm-12">
                            <p class="mb-0">ผู้ทำรายการ</p>
                            <div class="alert alert-white p-2 borderR10">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <img src="{{ asset('frontend/images/man.png') }}" alt="..." width="30px">
                                    </div>
                                    <div class="flex-grow-1 ms-2">
                                        <p class="small mb-0"> {{ Auth::guard('c_user')->user()->user_name }} </p>
                                        <h6> {{ Auth::guard('c_user')->user()->name }}
                                            {{ Auth::guard('c_user')->user()->last_name }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row gx-2">
                        <div class="col-sm-12">
                            <h5 class="text-center">ยอดถอน</h5>
                            <div class="card p-2 borderR10 mb-3 text-center">
                                <h4 id="withdraw_text_confirm" class="mb-0 text-purple1 bg-opacity-100"> บาท </h4>
                            </div>
                        </div>
                    </div>
                    <div class="alert alert-warning d-flex" role="alert">
                        <i class='bx bxs-info-circle me-2 bx-sm'></i>
                        <div>
                            เงินจะโอนเข้าบัญชีธนาคารตามที่ระบุในระบบ ตามรอบการตัดจ่ายโบนัส “ค่าธรรมเนียมการโอน 13 บาท”
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
                    <button type="button" class="btn btn-outline-dark rounded-pill" data-bs-target="#withdrawModal2"
                        data-bs-toggle="modal">ยกเลิก</button>
                    <button type="submit" class="btn btn-p1 rounded-pill d-flex align-items-center"><i
                            class='bx bxs-check-circle me-2'></i>ยืนยันทำรายการ</button>
                </div>
            </div>
        </div>
    </div>
</form>
<!-- Modal -->
<div class="modal fade" id="withdrawModal3" tabindex="-1" aria-labelledby="withdrawModal3Label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
        <div class="modal-content borderR25">
            <div class="modal-header justify-content-center">
                <h5 class="modal-title" id="withdrawModal3Label">คุณได้ทำรายการถอนเงินเรียบร้อยแล้ว</h5>
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
    function withdraw_confirm() {

        amt = $("#amtwithdraw").val();
        amount = {{Auth::guard('c_user')->user()->ewallet_use}};
        id = {{Auth::guard('c_user')->user()->id}};
        amt2 = parseInt(amt);
        console.log(amt2,amount);
        if (amount < amt) {
            $('#withdrawModal').modal('hide')
            Swal.fire({
                icon: 'error',
                title: 'เกิดข้อผิดพลาด',
                text: 'eWallet ของท่านไม่เพียงพอ!',
            }).then((result) => {
                location.reload();
            });
        } else if (amt2 < 299) {
            $('#withdrawModal').modal('hide')
            Swal.fire({
                icon: 'error',
                title: 'เกิดข้อผิดพลาด',
                text: 'จำนวนถอนขั้นต่ำไม่ถูกต้อง!',
            }).then((result) => {
                location.reload();
            });
        } else if (amt == '') {
            $('#withdrawModal').modal('hide')
            Swal.fire({
                icon: 'error',
                title: 'เกิดข้อผิดพลาด',
                text: 'กรุณากรอกจำนวนเงิน !',
            }).then((result) => {
                location.reload();
            });
        } else {
            withdraw = $('#amtwithdraw').val();
            $('#withdrawModal').modal('hide')
            $('#withdrawModal2').modal('toggle');
            $('#withdraw_text_confirm').text(withdraw + " บาท");
        }
    }
</script>

