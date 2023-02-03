<div id="edit_product" class="modal" data-tw-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content">
            {{ Form::open(['url' => ['/admin/product/edit'], 'id' => 'product-edit', 'autocomplete' => 'off', 'enctype' => 'multipart/form-data']) }}
            <!-- BEGIN: Modal Header -->
            <div class="modal-header">
                <h2 class="font-medium text-base mr-auto">แก้ไขสินค้า</h2>
                <a data-tw-dismiss="modal" href="javascript:;"> <i data-lucide="x" class="w-8 h-8 text-slate-400"></i>
                </a>
            </div> <!-- END: Modal Header -->
            <!-- BEGIN: Modal Body -->
            <div class="modal-body grid grid-cols-12 gap-4 gap-y-3 bg-slate-100/50">
                <div class="col-span-12">
                    <div>
                        <label for="regular-form-1" class="form-label">Product Name :
                            <span class="text-danger name_err _err"></span>
                        </label>
                        <input id="regular-form-1" name="product_name_update" id="product_name_update" type="text"
                            value="" class="form-control" >
                    </div>
                </div>

                <div class="col-span-12">
                    <div>
                        <label for="regular-form-1" class="form-label">Image <span style="color: red"> (jpeg, jpg,
                                png) ขนาด ... px</span> :
                            <span class="text-danger name_err _err"></span>
                        </label>
                        <input type="file" name="product_img_update" id="input-file-now" class="dropify"
                            data-default-file="DROP IMAGE (jpeg, jpg, png)"  />
                    </div>
                </div>

                <div class="col-span-12">
                    <div>
                        <label for="regular-form-1" class="form-label">Product Title :
                            <span class="text-danger name_err _err"></span>
                        </label>
                        <input id="regular-form-1" name="product_title_update" id="product_title_update" type="text"
                            value="" class="form-control">
                    </div>
                </div>

                <div class="col-span-12">
                    <div>
                        <label for="regular-form-1" class="form-label">Descriptions :
                        </label>
                        <textarea class="summernote_ed1" rows="3" name="product_descrip_update" id="product_descrip_update"
                            placeholder="Text"></textarea>
                    </div>
                </div>

                <div class="col-span-12">
                    <div>
                        <label for="regular-form-1" class="form-label">Product Details :
                        </label>
                        <textarea class="summernote_ed1" rows="3" name="products_details_update" id="products_details_update"
                            placeholder="Text"></textarea>
                    </div>
                </div>

                <div class="col-span-4">
                    <div>
                        <label for="regular-form-1" class="form-label">Product Cost-Price :
                            <span class="text-danger name_err _err"></span>
                        </label>
                        <input id="regular-form-1" name="cost_price_update" id="cost_price_update" type="number"
                            step='0.01' placeholder='0.00' class="form-control" value="">
                    </div>
                </div>

                <div class="col-span-4">
                    <div>
                        <label for="regular-form-1" class="form-label">Product Selling-Price :
                            <span class="text-danger name_err _err"></span>
                        </label>
                        <input id="regular-form-1" name="selling_price_update" id="selling_price_update" type="number"
                            step='0.01' placeholder='0.00' class="form-control" value="">
                    </div>
                </div>

                <div class="col-span-4">
                    <div>
                        <label for="regular-form-1" class="form-label">Product Member-Price :
                            <span class="text-danger name_err _err"></span>
                        </label>
                        <input id="regular-form-1" name="member_price_update" id="member_price_update" type="number"
                            step='0.01' placeholder='0.00' class="form-control" value="">
                    </div>
                </div>

                <div class="col-span-6">
                    <div>
                        <label for="regular-form-1" class="form-label">Product PV :
                            <span class="text-danger name_err _err"></span>
                        </label>
                        <input id="regular-form-1" name="product_pv_update" id="product_pv_update" type="number"
                            placeholder='0' class="form-control" value="">
                    </div>
                </div>

                <div class="col-span-12">
                    <div>
                        <select type="text" class="rounded" name="select_category_update" id="select_category_update"
                            style="width:100%; padding: 4px; font-size:14px;">
                            <option value="" selected>กรุณาเลือกหมวดหมู่สินค้า</option>
                            @if (isset($Product_cate))
                                @foreach ($Product_cate as $item => $value)
                                    <option value="{{ $value->id }}">{{ $value->category_name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>

                <div class="col-span-6">
                    <div>
                        <select type="text" class="rounded" name="select_unit_update" id="select_unit_update"
                            style="width:100%; padding: 4px; font-size:14px;">
                            <option value="" selected>กรุณาเลือกหน่วยสินค้า</option>
                            @if (isset($Product_unit))
                                @foreach ($Product_unit as $item => $value)
                                    <option value="{{ $value->id }}">{{ $value->product_unit }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>

                <div class="col-span-6">
                    <div>
                        <select type="text" class="rounded" name="select_size_update" id="select_size_update"
                            style="width:100%; padding: 4px; font-size:14px;">
                            <option value="" selected>กรุณาเลือกขนาดสินค้า</option>
                            @if (isset($Product_size))
                                @foreach ($Product_size as $item => $value)
                                    <option value="{{ $value->id }}">{{ $value->size }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>

                <div class="col-span-12">
                </div>

                <div class="col-span-6">
                    <div>
                        <select type="text" class="rounded" name="select_product_lang_update" id="select_product_lang_update"
                            style="width:100%; padding: 4px; font-size:14px;">
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
                            <option value="" selected>เลือกสถานะ</option>
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
