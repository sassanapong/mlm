{{-- ฟิลด์ตั้งค่าสิทธิ์หมวดสินค้า: @include(..., ['p' => 'add'|'edit']) --}}
<div class="col-span-12">
    <div class="font-medium border-t pt-3">สิทธิ์การมองเห็น / สั่งซื้อ (ไม่ตั้ง = ทุกคนเห็นและซื้อได้ตามปกติ)</div>
</div>

<div class="col-span-12">
    <label class="form-label">ผู้มีสิทธิ์</label>
    <select name="access_mode" id="{{ $p }}_access_mode" class="form-select">
        <option value="public">ทุกรหัส</option>
        <option value="whitelist">เฉพาะรหัสที่ได้รับสิทธิ์เท่านั้น (อิมพอร์ต Excel ได้หลังบันทึก)</option>
    </select>
</div>

<div class="col-span-12">
    <label class="form-label">ตำแหน่งที่มองเห็นหมวดนี้ (ไม่เลือก = ทุกตำแหน่ง)</label>
    <div class="flex flex-wrap gap-x-4 gap-y-1">
        @foreach ($positions as $position)
            <label class="flex items-center">
                <input type="checkbox" name="positions[]" value="{{ $position->code }}"
                    class="form-check-input {{ $p }}_positions mr-1">
                {{ $position->business_qualifications }}
            </label>
        @endforeach
    </div>
</div>

<div class="col-span-12 sm:col-span-4">
    <label class="form-label">ซื้อได้ไม่เกิน (ชิ้น/ท่าน)</label>
    <input type="number" min="1" name="max_qty_per_user" id="{{ $p }}_max_qty_per_user"
        class="form-control" placeholder="ไม่จำกัด">
</div>
<div class="col-span-12 sm:col-span-4">
    <label class="form-label">เริ่มแสดง</label>
    <input type="datetime-local" name="show_start_at" id="{{ $p }}_show_start_at" class="form-control">
</div>
<div class="col-span-12 sm:col-span-4">
    <label class="form-label">สิ้นสุดแสดง</label>
    <input type="datetime-local" name="show_end_at" id="{{ $p }}_show_end_at" class="form-control">
</div>
