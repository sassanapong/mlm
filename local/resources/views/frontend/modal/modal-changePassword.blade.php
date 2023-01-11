<!-- Modal -->
<div class="modal fade" id="changePassModal" tabindex="-1" aria-labelledby="changePassModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
        <div class="modal-content borderR25">
            <form id="form_change_password" method="post">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="changePassModalLabel">เปลี่ยนรหัสผ่าน สำหรับเข้าใช้ระบบ</h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{-- <div class="mb-3">
                        <label for="" class="form-label">รหัสผ่านเดิม</label>
                        <small for="" class="fs-12 text-danger password_err  _err"></small>
                        <input type="password" class="form-control" id="" name="password" placeholder="">
                    </div> --}}
                    <div class="mb-3">
                        <label for="" class="form-label">รหัสผ่านใหม่ ที่ต้องการเปลี่ยน</label>
                        <small for="" class="fs-12 text-danger password_new_err  _err"></small>
                        <input type="password" class="form-control" id="" name="password_new" placeholder="">
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">ยืนยันรหัสผ่าน ที่ต้องการเปลี่ยน</label>
                        <small for="" class="fs-12 text-danger password_new_comfirm_err  _err"></small>
                        <input type="password" class="form-control" id="" name="password_new_comfirm"
                            placeholder="">
                    </div>
                    <small for="" class="fs-12 text-danger check_comfirm_err  _err"></small>
                    <div class="form-check">

                        <input class="form-check-input" type="checkbox" name="check_comfirm" value="1"
                            id="flexCheckDefault">
                        <label class="form-check-label" for="flexCheckDefault">
                            ยืนยันการเปลี่ยนรหัสผ่าน สำหรับการใช้งานระบบ
                        </label>
                    </div>
                </div>
                <div class="modal-footer justify-content-between border-0">
                    <button type="button" class="btn btn-outline-dark rounded-pill"
                        data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="submit" class="btn btn-p1 rounded-pill d-flex align-items-center"><i
                            class='bx bxs-save me-2'></i>บันทึกการเปลี่ยนรหัสผ่าน</button>
                </div>
            </form>
        </div>
    </div>
</div>


{{-- BEGIN form_change_password --}}
<script>
    $('#form_change_password').submit(function(e) {
        e.preventDefault();
        var formData = new FormData($(this)[0]);
        $.ajax({
            url: '{{ route('change_password') }}',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(data) {
                if ($.isEmptyObject(data.error) || data.status == "success") {
                    window.location.href = "logout";
                } else {
                    printErrorMsg(data.error);
                }
            }
        });
    });
</script>
{{-- END form_change_password --}}
