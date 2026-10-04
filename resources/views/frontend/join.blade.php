@extends('layouts.frontend.app_registerurl')

@section('css')
    <style>
        body { background: #f5f3fa; }
        .join-wrap { max-width: 960px; margin: 0 auto; padding: 90px 12px 40px; }
        .agreement { max-height: 400px; overflow-y: auto; border: 1px solid #ccc; padding: 10px; background: #f9f9f9; }

        /* ตัวบอกขั้นตอน */
        .stepper { display: flex; justify-content: space-between; margin-bottom: 18px; position: relative; }
        .stepper::before { content: ''; position: absolute; top: 17px; left: 8%; right: 8%; height: 3px; background: #ddd; z-index: 0; }
        .stepper .s { flex: 1; text-align: center; position: relative; z-index: 1; font-size: .78rem; color: #888; }
        .stepper .s .dot { width: 36px; height: 36px; border-radius: 50%; background: #ddd; color: #fff; margin: 0 auto 4px; display: flex; align-items: center; justify-content: center; font-weight: 600; }
        .stepper .s.active .dot { background: #6f42c1; box-shadow: 0 0 0 4px rgba(111, 66, 193, .2); }
        .stepper .s.done .dot { background: #28a745; }
        .stepper .s.active { color: #6f42c1; font-weight: 600; }

        .step-card { background: #fff; border-radius: 12px; padding: 20px; box-shadow: 0 2px 10px rgba(0, 0, 0, .06); }
        .step-pane { display: none; }
        .step-pane.active { display: block; }
        .step-title { font-size: 1.15rem; font-weight: 600; margin-bottom: 14px; color: #4b2a8a; }
        .err { color: #dc3545; font-size: .8rem; min-height: 1px; }
        .nav-btns { display: flex; justify-content: space-between; margin-top: 20px; gap: 10px; }
        .nav-btns .btn { min-width: 120px; }

        .img-preview { max-width: 100%; max-height: 260px; border: 1px dashed #bbb; border-radius: 8px; padding: 4px; }

        /* สินค้า */
        .prod-card { border: 1px solid #eee; border-radius: 10px; padding: 10px; height: 100%; display: flex; flex-direction: column; }
        .prod-card img { width: 100%; height: 130px; object-fit: contain; }
        .prod-card .name { font-size: .85rem; font-weight: 500; margin: 6px 0 2px; flex: 1; }
        .prod-card .price { color: #6f42c1; font-weight: 600; }
        .prod-card .pv { font-size: .78rem; color: #28a745; }
        .qty-box { display: flex; align-items: center; justify-content: center; gap: 6px; margin-top: 6px; }
        .qty-box button { width: 30px; height: 30px; padding: 0; }
        .qty-box input { width: 48px; text-align: center; }
        .summary-box { position: sticky; bottom: 0; background: #fff; border-top: 2px solid #6f42c1; padding: 12px; border-radius: 10px 10px 0 0; box-shadow: 0 -4px 12px rgba(0, 0, 0, .08); margin-top: 16px; }
        .pos-badge { display: inline-block; background: #6f42c1; color: #fff; border-radius: 20px; padding: 2px 14px; font-weight: 600; }
        .readonly-sel { pointer-events: none; background: #e9ecef; }
    </style>
@endsection

@section('conten')
    <div class="join-wrap">
        <h4 class="text-center mb-3" style="color:#4b2a8a;">สมัครสมาชิก</h4>

        <div class="stepper" id="stepper">
            <div class="s active" data-s="1"><div class="dot">1</div>ผู้แนะนำ</div>
            <div class="s" data-s="2"><div class="dot">2</div>ข้อมูลสมาชิก</div>
            <div class="s" data-s="3"><div class="dot">3</div>ที่อยู่/รายได้</div>
            <div class="s" data-s="4"><div class="dot">4</div>เอกสาร</div>
            <div class="s" data-s="5"><div class="dot">5</div>สินค้า/ชำระเงิน</div>
        </div>

        <form id="join_form" enctype="multipart/form-data" autocomplete="off">
            @csrf
            <div class="step-card">

                {{-- ========== ขั้นตอนที่ 1: ผู้แนะนำ / ทีมงาน ========== --}}
                <div class="step-pane active" data-step="1">
                    <div class="step-title">ผู้แนะนำและขา</div>
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label">รหัสผู้แนะนำ <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="sponsor" id="sponsor" value="{{ $sponsor }}"
                                {{ $sponsor ? 'readonly' : '' }}>
                            <div class="small text-success" id="sponsor_name"></div>
                            <div class="err" data-err="sponsor"></div>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label">เลือกขา <span class="text-danger">*</span></label>
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check" name="type" id="type_a" value="A"
                                    {{ $type == 'A' ? 'checked' : '' }}>
                                <label class="btn btn-outline-primary" for="type_a">ขา A (ซ้าย)</label>
                                <input type="radio" class="btn-check" name="type" id="type_b" value="B"
                                    {{ $type == 'B' ? 'checked' : '' }}>
                                <label class="btn btn-outline-primary" for="type_b">ขา B (ขวา)</label>
                            </div>
                            <div class="err" data-err="type"></div>
                        </div>
                    </div>
                    <div class="alert alert-info py-2 small mt-3 mb-0">
                        ระบบจะวางตำแหน่งต่อท้ายสุดของขาที่เลือก ใต้ผู้แนะนำ หลังชำระเงินสำเร็จ
                    </div>
                </div>

                {{-- ========== ขั้นตอนที่ 2: ข้อมูลสมาชิก ========== --}}
                <div class="step-pane" data-step="2">
                    <div class="step-title">ข้อมูลสมาชิก</div>
                    <div class="row g-3">
                        <div class="col-6 col-md-3">
                            <label class="form-label">คำนำหน้า <span class="text-danger">*</span></label>
                            <select name="prefix_name" class="form-select">
                                <option value="">เลือก</option>
                                <option value="นาย">นาย</option>
                                <option value="นาง">นาง</option>
                                <option value="นางสาว">นางสาว</option>
                            </select>
                            <div class="err" data-err="prefix_name"></div>
                        </div>
                        <div class="col-6 col-md-4">
                            <label class="form-label">ชื่อ <span class="text-danger">*</span></label>
                            <input name="name" type="text" class="form-control">
                            <div class="err" data-err="name"></div>
                        </div>
                        <div class="col-6 col-md-5">
                            <label class="form-label">นามสกุล <span class="text-danger">*</span></label>
                            <input name="last_name" type="text" class="form-control">
                            <div class="err" data-err="last_name"></div>
                        </div>
                        <div class="col-6 col-md-3">
                            <label class="form-label">เพศ <span class="text-danger">*</span></label>
                            <select name="gender" class="form-select">
                                <option value="">เลือก</option>
                                <option value="ชาย">ชาย</option>
                                <option value="หญิง">หญิง</option>
                                <option value="ไม่ระบุ">ไม่ระบุ</option>
                            </select>
                            <div class="err" data-err="gender"></div>
                        </div>
                        <div class="col-12 col-md-5">
                            <label class="form-label">ชื่อทางธุรกิจ <span class="text-danger">*</span></label>
                            <input name="business_name" type="text" class="form-control">
                            <div class="err" data-err="business_name"></div>
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label">สัญชาติ <span class="text-danger">*</span></label>
                            <select class="form-select" name="nation_id" id="nation_id">
                                @foreach ($region as $r)
                                    <option value="{{ $r->id }}">{{ $r->name }}</option>
                                @endforeach
                            </select>
                            <div class="err" data-err="nation_id"></div>
                        </div>
                        <div class="col-4 col-md-2">
                            <label class="form-label">วันเกิด <span class="text-danger">*</span></label>
                            <select name="day" class="form-select">
                                <option value="">วัน</option>
                                @foreach ($day as $val)
                                    <option value="{{ $val }}">{{ $val }}</option>
                                @endforeach
                            </select>
                            <div class="err" data-err="day"></div>
                        </div>
                        <div class="col-4 col-md-3">
                            <label class="form-label d-none d-md-block">&nbsp;</label>
                            <select name="month" class="form-select">
                                <option value="">เดือน</option>
                                @foreach (['01' => 'มกราคม', '02' => 'กุมภาพันธ์', '03' => 'มีนาคม', '04' => 'เมษายน', '05' => 'พฤษภาคม', '06' => 'มิถุนายน', '07' => 'กรกฎาคม', '08' => 'สิงหาคม', '09' => 'กันยายน', '10' => 'ตุลาคม', '11' => 'พฤศจิกายน', '12' => 'ธันวาคม'] as $k => $m)
                                    <option value="{{ $k }}">{{ $m }}</option>
                                @endforeach
                            </select>
                            <div class="err" data-err="month"></div>
                        </div>
                        <div class="col-4 col-md-2">
                            <label class="form-label d-none d-md-block">&nbsp;</label>
                            <select name="year" class="form-select">
                                <option value="">ปี</option>
                                @foreach ($arr_year as $val)
                                    <option value="{{ $val }}">{{ $val }}</option>
                                @endforeach
                            </select>
                            <div class="err" data-err="year"></div>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">เลขบัตรประชาชน <span class="text-danger">*</span></label>
                            <input name="id_card" id="id_card" type="text" class="form-control" maxlength="13"
                                inputmode="numeric">
                            <div class="err" data-err="id_card"></div>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">โทรศัพท์ <span class="text-danger">*</span></label>
                            <input name="phone" type="text" class="form-control" maxlength="10" inputmode="numeric">
                            <div class="err" data-err="phone"></div>
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label">E-mail</label>
                            <input name="email" type="text" class="form-control">
                            <div class="err" data-err="email"></div>
                        </div>
                        <div class="col-6 col-md-4">
                            <label class="form-label">Line ID</label>
                            <input name="line_id" type="text" class="form-control">
                        </div>
                        <div class="col-6 col-md-4">
                            <label class="form-label">Facebook</label>
                            <input name="facebook" type="text" class="form-control">
                        </div>
                    </div>
                </div>

                {{-- ========== ขั้นตอนที่ 3: ที่อยู่ / รายได้ ========== --}}
                <div class="step-pane" data-step="3">
                    <div class="step-title">ที่อยู่ และวิธีการรับรายได้</div>

                    <div class="fw-semibold mb-2">ที่อยู่ตามบัตรประชาชน</div>
                    <div class="row g-3 mb-4">
                        <div class="col-md-5"><label class="form-label">ที่อยู่ <span class="text-danger">*</span></label>
                            <input type="text" name="card_address" class="form-control card_f">
                            <div class="err" data-err="card_address"></div></div>
                        <div class="col-6 col-md-3"><label class="form-label">หมู่ที่ <span class="text-danger">*</span></label>
                            <input type="text" name="card_moo" class="form-control card_f">
                            <div class="err" data-err="card_moo"></div></div>
                        <div class="col-6 col-md-4"><label class="form-label">ซอย <span class="text-danger">*</span></label>
                            <input type="text" name="card_soi" class="form-control card_f">
                            <div class="err" data-err="card_soi"></div></div>
                        <div class="col-md-4"><label class="form-label">ถนน <span class="text-danger">*</span></label>
                            <input type="text" name="card_road" class="form-control card_f">
                            <div class="err" data-err="card_road"></div></div>
                        <div class="col-md-4"><label class="form-label">จังหวัด <span class="text-danger">*</span></label>
                            <select class="form-select card_f" name="card_province" id="card_province">
                                <option value="">--กรุณาเลือก--</option>
                                @foreach ($province as $item)
                                    <option value="{{ $item->province_id }}">{{ $item->province_name }}</option>
                                @endforeach
                            </select>
                            <div class="err" data-err="card_province"></div></div>
                        <div class="col-md-4"><label class="form-label">อำเภอ/เขต <span class="text-danger">*</span></label>
                            <select class="form-select card_f" name="card_district" id="card_district" disabled>
                                <option value="">--กรุณาเลือก--</option>
                            </select>
                            <div class="err" data-err="card_district"></div></div>
                        <div class="col-md-4"><label class="form-label">ตำบล <span class="text-danger">*</span></label>
                            <select class="form-select card_f" name="card_tambon" id="card_tambon" disabled>
                                <option value="">--กรุณาเลือก--</option>
                            </select>
                            <div class="err" data-err="card_tambon"></div></div>
                        <div class="col-md-4"><label class="form-label">รหัสไปรษณีย์ <span class="text-danger">*</span></label>
                            <input type="text" name="card_zipcode" id="card_zipcode" class="form-control card_f">
                            <div class="err" data-err="card_zipcode"></div></div>
                        <div class="col-md-4"><label class="form-label">เบอร์มือถือ</label>
                            <input type="text" name="card_phone" maxlength="10" class="form-control card_f"></div>
                    </div>

                    <div class="fw-semibold mb-2 d-flex flex-wrap align-items-center gap-3">
                        ที่อยู่จัดส่ง
                        <div class="form-check fw-normal">
                            <input class="form-check-input" id="status_address" type="checkbox" name="status_address" value="1">
                            <label class="form-check-label" for="status_address">ใช้ที่อยู่เดียวกับบัตรประชาชน</label>
                        </div>
                    </div>
                    <div class="row g-3 mb-4">
                        <div class="col-md-5"><label class="form-label">ที่อยู่ <span class="text-danger">*</span></label>
                            <input type="text" name="same_address" class="form-control same_f">
                            <div class="err" data-err="same_address"></div></div>
                        <div class="col-6 col-md-3"><label class="form-label">หมู่ที่ <span class="text-danger">*</span></label>
                            <input type="text" name="same_moo" class="form-control same_f">
                            <div class="err" data-err="same_moo"></div></div>
                        <div class="col-6 col-md-4"><label class="form-label">ซอย <span class="text-danger">*</span></label>
                            <input type="text" name="same_soi" class="form-control same_f">
                            <div class="err" data-err="same_soi"></div></div>
                        <div class="col-md-4"><label class="form-label">ถนน <span class="text-danger">*</span></label>
                            <input type="text" name="same_road" class="form-control same_f">
                            <div class="err" data-err="same_road"></div></div>
                        <div class="col-md-4"><label class="form-label">จังหวัด <span class="text-danger">*</span></label>
                            <select class="form-select same_f" name="same_province" id="same_province">
                                <option value="">--กรุณาเลือก--</option>
                                @foreach ($province as $item)
                                    <option value="{{ $item->province_id }}">{{ $item->province_name }}</option>
                                @endforeach
                            </select>
                            <div class="err" data-err="same_province"></div></div>
                        <div class="col-md-4"><label class="form-label">อำเภอ/เขต <span class="text-danger">*</span></label>
                            <select class="form-select same_f" name="same_district" id="same_district" disabled>
                                <option value="">--กรุณาเลือก--</option>
                            </select>
                            <div class="err" data-err="same_district"></div></div>
                        <div class="col-md-4"><label class="form-label">ตำบล <span class="text-danger">*</span></label>
                            <select class="form-select same_f" name="same_tambon" id="same_tambon" disabled>
                                <option value="">--กรุณาเลือก--</option>
                            </select>
                            <div class="err" data-err="same_tambon"></div></div>
                        <div class="col-md-4"><label class="form-label">รหัสไปรษณีย์ <span class="text-danger">*</span></label>
                            <input type="text" name="same_zipcode" id="same_zipcode" class="form-control same_f">
                            <div class="err" data-err="same_zipcode"></div></div>
                        <div class="col-md-4"><label class="form-label">เบอร์มือถือ</label>
                            <input type="text" name="same_phone" maxlength="10" class="form-control same_f"></div>
                    </div>

                    <div class="fw-semibold mb-2">บัญชีธนาคารเพื่อรับรายได้</div>
                    <div class="alert alert-warning py-2 small">ไม่บังคับ แต่หากไม่ใส่จะมีผลกับการโอนเงินรายได้ให้สมาชิก</div>
                    <div class="row g-3 mb-4">
                        <div class="col-md-4"><label class="form-label">ธนาคาร</label>
                            <select name="bank_name" class="form-select">
                                <option value="">เลือกธนาคาร</option>
                                @foreach ($bank as $b)
                                    <option value="{{ $b->id }}">{{ $b->name }}</option>
                                @endforeach
                            </select>
                            <div class="err" data-err="bank_name"></div></div>
                        <div class="col-md-4"><label class="form-label">สาขา</label>
                            <input type="text" name="bank_branch" class="form-control">
                            <div class="err" data-err="bank_branch"></div></div>
                        <div class="col-md-4"><label class="form-label">เลขที่บัญชี (เฉพาะตัวเลข)</label>
                            <input type="text" name="bank_no" maxlength="15" class="form-control" inputmode="numeric">
                            <div class="err" data-err="bank_no"></div></div>
                        <div class="col-12"><label class="form-label">ชื่อบัญชี</label>
                            <input type="text" name="account_name" class="form-control">
                            <div class="err" data-err="account_name"></div></div>
                    </div>

                    <div class="fw-semibold mb-2">ผู้รับผลประโยชน์</div>
                    <div class="alert alert-warning py-2 small">ถ้าไม่กรอกถือว่าผู้รับผลประโยชน์เป็นไปตามกฎหมาย</div>
                    <div class="row g-3">
                        <div class="col-md-4"><label class="form-label">ชื่อ</label>
                            <input type="text" name="name_benefit" class="form-control">
                            <div class="err" data-err="name_benefit"></div></div>
                        <div class="col-md-4"><label class="form-label">นามสกุล</label>
                            <input type="text" name="last_name_benefit" class="form-control">
                            <div class="err" data-err="last_name_benefit"></div></div>
                        <div class="col-md-4"><label class="form-label">เกี่ยวข้องเป็น</label>
                            <input type="text" name="involved" class="form-control">
                            <div class="err" data-err="involved"></div></div>
                    </div>
                </div>

                {{-- ========== ขั้นตอนที่ 4: เอกสาร ========== --}}
                <div class="step-pane" data-step="4">
                    <div class="step-title">แนบเอกสาร</div>
                    <div class="row g-4">
                        <div class="col-md-6 text-center">
                            <label class="form-label fw-semibold">สำเนาบัตรประชาชน <span class="text-danger">*</span></label>
                            <input type="file" class="form-control" name="file_card" id="file_card" accept="image/*">
                            <div class="err" data-err="file_card"></div>
                            <img class="img-preview mt-2" id="img_card" src="https://via.placeholder.com/250x300.png?text=card">
                        </div>
                        <div class="col-md-6 text-center">
                            <label class="form-label fw-semibold">หน้าสมุดบัญชีธนาคาร <span class="small text-muted">(ถ้ากรอกบัญชีธนาคารต้องแนบ)</span></label>
                            <input type="file" class="form-control" name="file_bank" id="file_bank" accept="image/*">
                            <div class="err" data-err="file_bank"></div>
                            <img class="img-preview mt-2" id="img_bank" src="https://via.placeholder.com/250x300.png?text=Bank">
                        </div>
                    </div>
                </div>

                {{-- ========== ขั้นตอนที่ 5: เลือกสินค้า / ชำระเงิน ========== --}}
                <div class="step-pane" data-step="5">
                    <div class="step-title">เลือกสินค้า และชำระเงิน</div>
                    <div class="alert alert-info py-2 small">
                        ตำแหน่งของคุณคำนวณจาก PV รวมของสินค้าที่เลือก (ขั้นต่ำ {{ number_format($pv_min) }} PV)
                        สมาชิกจะถูกสร้างหลังชำระเงินสำเร็จเท่านั้น
                    </div>
                    <div class="row g-3" id="product_list">
                        @foreach ($products as $p)
                            <div class="col-6 col-md-4 col-lg-3">
                                <div class="prod-card" data-id="{{ $p->products_id }}">
                                    <img src="{{ asset($p->img_url . $p->product_img) }}" alt="">
                                    <div class="name">{{ $p->product_name }}</div>
                                    <div class="price">{{ number_format($p->member_price, 2) }} ฿</div>
                                    <div class="pv">{{ number_format($p->pv, 2) }} PV</div>
                                    <div class="qty-box">
                                        <button type="button" class="btn btn-outline-secondary btn-sm qty-minus">−</button>
                                        <input type="number" min="0" max="999" value="0" class="form-control form-control-sm qty-input">
                                        <button type="button" class="btn btn-outline-secondary btn-sm qty-plus">+</button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="err mt-2" data-err="items"></div>

                    <div class="summary-box">
                        <div id="summary_lines" class="small mb-2"></div>
                        <div class="d-flex justify-content-between"><span>ค่าสินค้า</span><span id="sum_price">0.00</span></div>
                        <div class="d-flex justify-content-between"><span>ค่าจัดส่ง</span><span id="sum_ship">0.00</span></div>
                        <div class="d-flex justify-content-between fw-bold fs-5"><span>ยอดชำระ</span><span id="sum_total">0.00 ฿</span></div>
                        <div class="d-flex justify-content-between mt-1"><span>PV รวม</span><span id="sum_pv" class="fw-semibold text-success">0</span></div>
                        <div class="d-flex justify-content-between align-items-center mt-1">
                            <span>ตำแหน่งที่จะได้รับ</span><span class="pos-badge" id="sum_pos">-</span>
                        </div>
                        <div class="small text-danger text-end" id="pos_next"></div>

                        <div class="mt-3 fw-semibold">วิธีการชำระเงิน</div>
                        <div class="btn-group w-100 mb-2" role="group">
                            <input type="radio" class="btn-check" name="pay_method" id="pay_payso" value="payso" checked>
                            <label class="btn btn-outline-success" for="pay_payso">ชำระออนไลน์ (PaySo)</label>
                            <input type="radio" class="btn-check" name="pay_method" id="pay_slip" value="slip">
                            <label class="btn btn-outline-success" for="pay_slip">แนบสลิปโอนเงิน</label>
                        </div>
                        <div id="slip_box" class="d-none mb-2">
                            <input type="file" class="form-control" name="file_slip" id="file_slip" accept="image/*">
                            <div class="err" data-err="file_slip"></div>
                            <div class="small text-muted mt-1">เจ้าหน้าที่จะตรวจสอบสลิป แล้วอนุมัติเพื่อสร้างรหัสสมาชิก</div>
                        </div>

                        <div class="form-check mt-3">
                            <input class="form-check-input" type="checkbox" id="accept_terms">
                            <label class="form-check-label" for="accept_terms">
                                ข้าพเจ้ายอมรับ <a href="#" data-bs-toggle="modal" data-bs-target="#agreeModal">ข้อตกลงและเงื่อนไข</a> ของบริษัท
                            </label>
                        </div>
                    </div>
                </div>

                <div class="nav-btns">
                    <button type="button" class="btn btn-outline-secondary" id="btn_back" style="visibility:hidden;">ย้อนกลับ</button>
                    <button type="button" class="btn btn-primary" id="btn_next">ถัดไป</button>
                    <button type="button" class="btn btn-success d-none" id="btn_pay">ชำระเงิน</button>
                </div>
            </div>
        </form>
    </div>

    <div class="modal fade" id="agreeModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">ข้อตกลงและเงื่อนไข</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    @include('frontend.partials.agreement')
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('input[name=_token]').val() } });

        var TOTAL_STEPS = 5;
        var current = 1;
        var cart = {}; // {product_id: qty}
        var PV_MIN = {{ $pv_min }};
        var POSITION_LADDER = @json(\App\Support\PositionService::LADDER);
        var REGISTER_PROMO = @json(\App\Support\PositionService::REGISTER_PROMO);
        // บันไดตำแหน่งตอนสมัคร: ขั้นปกติที่ต่ำกว่าโปร + ขั้นโปร (ตรงกับ PositionService::fromRegisterPv)
        var REGISTER_STEPS = POSITION_LADDER.filter(function(s) {
            return !REGISTER_PROMO.length || s.pv < REGISTER_PROMO[0].pv;
        }).concat(REGISTER_PROMO);

        var $form = $('#join_form');

        function nextStepOf(pv) {
            for (var i = 0; i < REGISTER_STEPS.length; i++) {
                if (pv < REGISTER_STEPS[i].pv) return REGISTER_STEPS[i];
            }
            return null;
        }

        function fmt(n) {
            return Number(n).toLocaleString('th-TH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }

        // ---------- ตัวบอกขั้นตอน ----------
        function showStep(n) {
            current = n;
            $('.step-pane').removeClass('active').filter('[data-step=' + n + ']').addClass('active');
            $('#stepper .s').each(function() {
                var s = $(this).data('s');
                $(this).toggleClass('active', s === n).toggleClass('done', s < n);
            });
            $('#btn_back').css('visibility', n === 1 ? 'hidden' : 'visible');
            $('#btn_next').toggleClass('d-none', n === TOTAL_STEPS);
            $('#btn_pay').toggleClass('d-none', n !== TOTAL_STEPS);
            if (n === TOTAL_STEPS) refreshQuote();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function clearErrors() { $('.err').text(''); }

        function showErrors(errors) {
            clearErrors();
            $.each(errors || {}, function(k, v) {
                $('[data-err="' + k + '"]').text(Array.isArray(v) ? v[0] : v);
            });
        }

        function loading(title) {
            Swal.fire({ title: title || 'รอสักครู่...', allowOutsideClick: false, allowEscapeKey: false, didOpen: function() { Swal.showLoading(); } });
        }

        // ---------- ตรวจแต่ละขั้นตอนกับ server ----------
        function validateStep(n, done) {
            var fd = new FormData($form[0]);
            fd.append('step', n);
            loading('กำลังตรวจสอบข้อมูล...');
            $.ajax({ url: '{{ route('join.validate') }}', method: 'POST', data: fd, processData: false, contentType: false })
                .done(function() { Swal.close(); clearErrors(); done(); })
                .fail(function(xhr) {
                    Swal.close();
                    var r = xhr.responseJSON || {};
                    showErrors(r.errors);
                    Swal.fire({ icon: 'warning', title: r.message || 'ข้อมูลไม่ถูกต้อง' });
                });
        }

        $('#btn_next').on('click', function() {
            if (current === 4) {
                if (!$('#file_card')[0].files.length) {
                    showErrors({ file_card: 'กรุณาอัปโหลดสำเนาบัตรประชาชน' });
                    return;
                }
            }
            validateStep(current, function() { showStep(current + 1); });
        });
        $('#btn_back').on('click', function() { if (current > 1) showStep(current - 1); });

        // ---------- ขั้นตอนที่ 1: ตรวจรหัส ----------
        $('#sponsor').on('change', function() {
            var v = $.trim($(this).val());
            $('#sponsor_name').text('');
            if (!v) return;
            $.get('{{ route('join.check_sponsor') }}', { sponsor: v }, function(r) {
                if (r.status === 'success') {
                    $('#sponsor_name').text(r.name);
                } else {
                    $('#sponsor').val('');
                    Swal.fire({ icon: 'error', title: r.message });
                }
            });
        });
        @if ($sponsor)
            $('#sponsor').trigger('change');
        @endif

        // ---------- ขั้นตอนที่ 2 ----------
        $('#nation_id').on('change', function() {
            $('#id_card').attr('maxlength', $(this).val() == '1' ? 13 : 15).val('');
        });
        $('#id_card, [name=phone], [name=card_phone], [name=same_phone], [name=bank_no]').on('input', function() {
            if ($(this).attr('id') === 'id_card' && $('#nation_id').val() != '1') return;
            this.value = this.value.replace(/[^0-9]/g, '');
        });

        // ---------- ขั้นตอนที่ 3: ที่อยู่ (จังหวัด -> อำเภอ -> ตำบล -> รหัสไปรษณีย์) ----------
        function loadDistricts(prefix, province, selected) {
            var $d = $('#' + prefix + '_district'), $t = $('#' + prefix + '_tambon');
            $d.html('<option value="">--กรุณาเลือก--</option>').prop('disabled', true);
            $t.html('<option value="">--กรุณาเลือก--</option>').prop('disabled', true);
            $('#' + prefix + '_zipcode').val('');
            if (!province) return $.Deferred().resolve().promise();
            return $.getJSON('{{ route('getDistrict') }}', { province_id: province }, function(data) {
                data.forEach(function(i) { $d.append('<option value="' + i.district_id + '">' + i.district_name + '</option>'); });
                $d.prop('disabled', false);
                if (selected) $d.val(selected);
            });
        }
        function loadTambons(prefix, district, selected) {
            var $t = $('#' + prefix + '_tambon');
            $t.html('<option value="">--กรุณาเลือก--</option>').prop('disabled', true);
            if (!district) return $.Deferred().resolve().promise();
            return $.getJSON('{{ route('getTambon') }}', { district_id: district }, function(data) {
                data.forEach(function(i) { $t.append('<option value="' + i.tambon_id + '">' + i.tambon_name + '</option>'); });
                $t.prop('disabled', false);
                if (selected) $t.val(selected);
            });
        }
        ['card', 'same'].forEach(function(prefix) {
            $('#' + prefix + '_province').on('change', function() { loadDistricts(prefix, $(this).val()); });
            $('#' + prefix + '_district').on('change', function() { loadTambons(prefix, $(this).val()); });
            $('#' + prefix + '_tambon').on('change', function() {
                $.getJSON('{{ route('getZipcode') }}', { tambon_id: $(this).val() }, function(d) {
                    $('#' + prefix + '_zipcode').val(d.zipcode);
                });
            });
        });

        $('#status_address').on('change', function() {
            var same = this.checked;
            if (!same) {
                $('.same_f').prop('readonly', false).removeClass('readonly-sel');
                return;
            }
            ['address', 'moo', 'soi', 'road', 'zipcode'].forEach(function(f) {
                $('[name=same_' + f + ']').val($('[name=card_' + f + ']').val());
            });
            $('[name=same_phone]').val($('[name=card_phone]').val());
            $('#same_province').val($('#card_province').val());
            loadDistricts('same', $('#card_province').val(), $('#card_district').val()).then(function() {
                return loadTambons('same', $('#card_district').val(), $('#card_tambon').val());
            }).then(function() {
                $('#same_zipcode').val($('#card_zipcode').val());
            });
            $('.same_f').prop('readonly', true);
            $('#same_province, #same_district, #same_tambon').addClass('readonly-sel');
        });

        // ---------- ขั้นตอนที่ 4: ตัวอย่างรูป ----------
        $('#file_card').on('change', function() { if (this.files[0]) $('#img_card').attr('src', URL.createObjectURL(this.files[0])); });
        $('#file_bank').on('change', function() { if (this.files[0]) $('#img_bank').attr('src', URL.createObjectURL(this.files[0])); });

        // ---------- ขั้นตอนที่ 5: สินค้า ----------
        function cartItems() {
            var items = [];
            $.each(cart, function(id, qty) { if (qty > 0) items.push({ id: id, qty: qty }); });
            return items;
        }

        var quoteTimer = null;
        function refreshQuote() {
            clearTimeout(quoteTimer);
            quoteTimer = setTimeout(function() {
                $.post('{{ route('join.quote') }}', { items: cartItems(), zipcode: $('#same_zipcode').val() }, function(q) {
                    var html = '';
                    q.lines.forEach(function(l) {
                        html += '<div class="d-flex justify-content-between"><span>' + $('<div>').text(l.name).html() + ' × ' + l.qty +
                            '</span><span>' + fmt(l.total_price) + '</span></div>';
                    });
                    $('#summary_lines').html(html || '<span class="text-muted">ยังไม่ได้เลือกสินค้า</span>');
                    $('#sum_price').text(fmt(q.sum_price));
                    $('#sum_ship').text(fmt(q.shipping));
                    $('#sum_total').text(fmt(q.total_price) + ' ฿');
                    $('#sum_pv').text(Number(q.pv_total).toLocaleString('th-TH') + ' PV');

                    if (q.pv_total < PV_MIN) {
                        $('#sum_pos').text('-');
                        $('#pos_next').text('เลือกสินค้าให้ได้ PV ขั้นต่ำ ' + PV_MIN.toLocaleString() + ' PV (ขาดอีก ' +
                            Math.ceil(PV_MIN - q.pv_total).toLocaleString() + ' PV)');
                    } else {
                        $('#sum_pos').text(q.position);
                        var next = nextStepOf(q.pv_total);
                        $('#pos_next').text(next ? 'อีก ' + Math.ceil(next.pv - q.pv_total).toLocaleString() + ' PV ขึ้นตำแหน่ง ' + next.code : 'ถึงตำแหน่งสูงสุดแล้ว');
                    }
                });
            }, 250);
        }

        function setQty($card, qty) {
            qty = Math.max(0, Math.min(999, parseInt(qty) || 0));
            $card.find('.qty-input').val(qty);
            cart[$card.data('id')] = qty;
            refreshQuote();
        }
        $('#product_list').on('click', '.qty-plus', function() {
            var $c = $(this).closest('.prod-card'); setQty($c, (parseInt($c.find('.qty-input').val()) || 0) + 1);
        });
        $('#product_list').on('click', '.qty-minus', function() {
            var $c = $(this).closest('.prod-card'); setQty($c, (parseInt($c.find('.qty-input').val()) || 0) - 1);
        });
        $('#product_list').on('change', '.qty-input', function() { setQty($(this).closest('.prod-card'), $(this).val()); });

        $('input[name=pay_method]').on('change', function() {
            var slip = $('input[name=pay_method]:checked').val() === 'slip';
            $('#slip_box').toggleClass('d-none', !slip);
            $('#btn_pay').text(slip ? 'ส่งใบสมัคร' : 'ชำระเงิน');
        });

        // ---------- ชำระเงิน ----------
        $('#btn_pay').on('click', function() {
            if (!$('#accept_terms').is(':checked')) {
                Swal.fire({ icon: 'warning', title: 'กรุณายอมรับข้อตกลงและเงื่อนไข' });
                return;
            }
            var items = cartItems();
            if (!items.length) {
                showErrors({ items: 'กรุณาเลือกสินค้า' });
                return;
            }

            var fd = new FormData($form[0]);
            items.forEach(function(it, i) {
                fd.append('items[' + i + '][id]', it.id);
                fd.append('items[' + i + '][qty]', it.qty);
            });

            loading('กำลังสร้างรายการชำระเงิน...');
            $.ajax({ url: '{{ route('join.submit') }}', method: 'POST', data: fd, processData: false, contentType: false })
                .done(function(r) {
                    if (r.method === 'slip') {
                        window.location.href = r.redirect;
                        return;
                    }
                    // ส่งต่อไป PaySo ด้วยฟอร์ม POST
                    var f = $('<form method="POST"></form>').attr('action', r.payment_url);
                    $.each(r.payload, function(k, v) { f.append($('<input type="hidden">').attr('name', k).val(v)); });
                    $('body').append(f);
                    f[0].submit();
                })
                .fail(function(xhr) {
                    Swal.close();
                    var r = xhr.responseJSON || {};
                    if (r.step && r.step < TOTAL_STEPS) showStep(r.step);
                    showErrors(r.errors);
                    Swal.fire({ icon: 'warning', title: r.message || 'ไม่สามารถทำรายการได้ กรุณาลองใหม่' });
                });
        });
    </script>
@endsection
