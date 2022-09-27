<div id="edit_lrn" class="modal" data-tw-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content">
            {{ Form::open(['url' => ['/admin/mdk_learning/edit'], 'id' => 'lrn-edit', 'autocomplete' => 'off', 'enctype' => 'multipart/form-data']) }}
            <!-- BEGIN: Modal Header -->
            <div class="modal-header">
                <h2 class="font-medium text-base mr-auto">แก้ไขการเรียนรู้</h2>
                <a data-tw-dismiss="modal" href="javascript:;"> <i data-lucide="x" class="w-8 h-8 text-slate-400"></i>
                </a>
            </div> <!-- END: Modal Header -->
            <!-- BEGIN: Modal Body -->
            <div class="modal-body grid grid-cols-12 gap-4 gap-y-3 bg-slate-100/50">
                <div class="col-span-12">
                    <div>
                        <label for="regular-form-1" class="form-label">Title :
                            <span class="text-danger name_err _err"></span>
                        </label>
                        <input id="regular-form-1" name="title_lrn_update" id="title_news_update" type="text"
                            value="" class="form-control">
                    </div>
                </div>

                <div class="col-span-12">
                    <div>
                        <label for="regular-form-1" class="form-label">Detail :
                            <span class="text-danger name_err _err"></span>
                        </label>
                        <input id="regular-form-1" name="detail_lrn_update" id="detail_lrn_update" type="text"
                            class="form-control">
                    </div>
                </div>

                <div class="col-span-12">
                    <div>
                        <label for="regular-form-1" class="form-label">Image <span style="color: red"> (jpeg,
                                jpg,
                                png) ขนาด 1920 × 1297 px</span> :
                            <span class="text-danger name_err _err"></span>
                        </label>
                        <input type="file" name="image_lrn_update" id="image_lrn_update" class="dropify"
                            data-default-file="DROP IMAGE (jpeg, jpg, png)" />
                    </div>
                </div>

                <div class="col-span-12">
                    <div>
                        <label for="regular-form-1" class="form-label">Detail All :
                        </label>
                        <textarea class="summernote_ed1" rows="5" name="detail_lrn_all_update" id="summernote_lrn" placeholder="Text"></textarea>
                    </div>
                </div>

                <div class="col-span-12">
                    <span class="text-danger role_err _err"></span>
                    <label class="col-md-2 col-form-label text-right">Upload Video :</label>
                    <div class="col-span-12">
                        <div class="form-group row">
                            <div class="col-md-12 dz-default dz-massage">
                                <div class="fallback">
                                    <input name="check_type2" type="radio" value="Upload" checked />
                                    <label for="">Upload</label>&nbsp;&nbsp;
                                    <input name="check_type2" type="radio" value="Link" />
                                    <label for="">Link</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-span-12" id="input3_file1">
                    <label for="regular-form-1" class="form-label"> Video <span style="color: red"> </span> (mp4) :
                    </label>
                    <div class="fallback">
                        <input name="upload_video_lrn2" type="file" />
                    </div>
                </div>

                <div class="col-span-12" id="input3_file2" style="display: none;">
                    <label for="regular-form-1" class="form-label"> Link :
                    </label>
                    <label for="regular-form-1" class="form-label"><span style="color:red">** Link Youtube เช่น
                            https://www.youtube.com<span style="color:blue">/embed/</span>zthvZvw-yJE <span
                                style="color:blue">ต้องใช้ /embed/ เพราะเป็นข้อจำกัดของ Youtube </span>**</span>
                    </label>
                    <input name="link_video_lrn2" type="text" class="form-control" value="" />
                </div>

                <div class="col-span-6">
                    <div>
                        <label for="regular-form-1" class="form-label">Start-Date :
                        </label>
                        <input id="regular-form-1" name="start_date_lrn2" type="date" class="form-control"
                            value="">
                    </div>
                </div>

                <div class="col-span-6">
                    <div>
                        <label for="regular-form-1" class="form-label">End-Date :
                        </label>
                        <input id="regular-form-1" name="end_date_lrn2" type="date" class="form-control"
                            value="">
                    </div>
                </div>

                <div class="col-span-6">
                    <div>
                        <label for="regular-form-1" class="form-label">Upload file <span style="color: red"> (PDF) </span> :
                        </label>
                        <input id="regular-form-1" name="uploadfile_lrn2" type="file" class="form-control">
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
