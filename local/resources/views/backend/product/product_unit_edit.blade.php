<div id="edit_product_unit" class="modal" data-tw-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content">
            {{ Form::open(['url' => ['/admin/product_unit/edit'], 'id' => 'product_unit-edit', 'autocomplete' => 'off', 'enctype' => 'multipart/form-data']) }}
            <!-- BEGIN: Modal Header -->
            <div class="modal-header">
                <h2 class="font-medium text-base mr-auto">แก้ไขหน่วยสินค้า</h2>
                <a data-tw-dismiss="modal" href="javascript:;"> <i data-lucide="x" class="w-8 h-8 text-slate-400"></i>
                </a>
            </div> <!-- END: Modal Header -->
            <!-- BEGIN: Modal Body -->
            <div class="modal-body grid grid-cols-12 gap-4 gap-y-3 bg-slate-100/50">
                <div class="col-span-12">
                    <div>
                        <label for="regular-form-1" class="form-label">Unit Name :
                            <span class="text-danger name_err _err"></span>
                        </label>
                        <input id="regular-form-1" name="product_unit_update" id="product_unit_update" type="text"
                            class="form-control" required>
                    </div>
                </div>

                <div class="col-span-6">
                    <div>
                        <select type="text" class="rounded" name="select_pro_unit_lang_update"
                            id="select_pro_unit_lang_update" style="width:100%; padding: 4px; font-size:14px;">
                            {{-- <option value="" selected>เลือกภาษา</option>
                            <option value="0">EN</option> --}}
                            <option value="1" selected>TH</option>
                        </select>
                    </div>
                </div>

                <div class="col-span-6">
                    <div>
                        <select type="text" class="rounded" name="status_update" id="status_update"
                            style="width:100%; padding: 4px; font-size:14px;">
                            <option value="0">ปิดการใช้งาน</option>
                            <option value="1">เปิดการใช้งาน</option>
                        </select>
                    </div>
                </div>
            </div> <!-- END: Modal Body -->
            <!-- BEGIN: Modal Footer -->
            <input type="hidden" name="id" id="id" value="">
            <div class="modal-footer">
                {{-- <button type="button" data-tw-dismiss="modal"
                        class="btn btn-outline-danger w-20 mr-1">ไม่ผ่าน</button> --}}
                {{-- <button type="submit" class="btn btn-outline-success  w-20">ตกลง</button> --}}
                <button type="submit" id="submit" class="btn btn-outline-success  w-20">ตกลง</button>
            </div>
            <!-- END: Modal Footer -->
            {{ Form::close() }}
        </div>
    </div>
</div> <!-- END: Modal Content -->
