<?php

namespace App\Http\Controllers\Backend;

use App\CustomersKyc;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use Illuminate\Http\Request;

// 1 Code 1 World: แอดมินตรวจและอนุมัติการยืนยันตัวตนของสมาชิกเดิม
class KycApprovalController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', (string) CustomersKyc::STATUS_PENDING);
        $q = trim((string) $request->get('q', ''));

        $rows = DB::table('customers_kyc')
            ->leftJoin('customers', 'customers.id', '=', 'customers_kyc.customers_id')
            ->select('customers_kyc.*', 'customers.name', 'customers.last_name')
            ->when($status !== '', function ($query) use ($status) {
                $query->where('customers_kyc.status', $status);
            })
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($w) use ($q) {
                    $w->where('customers_kyc.user_name', 'like', "%{$q}%")
                        ->orWhere('customers_kyc.id_card', 'like', "%{$q}%")
                        ->orWhere('customers_kyc.phone', 'like', "%{$q}%")
                        ->orWhere('customers_kyc.bank_no', 'like', "%{$q}%");
                });
            })
            ->orderBy('customers_kyc.id', 'DESC')
            ->paginate(50)
            ->appends($request->query());

        // ข้อมูลซ้ำกับรหัสอื่นที่ยืนยันผ่านแล้ว
        foreach ($rows as $r) {
            $r->dup = $this->duplicates($r);
        }

        // ประวัติการส่งรอบก่อนๆ ของแต่ละรายการ
        $history = DB::table('customers_kyc_history')
            ->whereIn('customers_id', $rows->pluck('customers_id'))
            ->orderBy('id', 'DESC')
            ->get()
            ->groupBy('customers_id');
        foreach ($rows as $r) {
            $r->history = $history->get($r->customers_id, collect());
        }

        return view('backend.kyc_approval.index', compact('rows', 'status', 'q'));
    }

    public function approve($id)
    {
        $kyc = CustomersKyc::findOrFail($id);
        if ($kyc->status != CustomersKyc::STATUS_PENDING) {
            return back()->with('error', 'รายการนี้ถูกตรวจสอบแล้ว');
        }
        if ($this->duplicates($kyc)) {
            return back()->with('error', 'อนุมัติไม่ได้ ข้อมูลซ้ำกับรหัสที่อนุมัติแล้ว: ' . implode(', ', $this->duplicates($kyc)));
        }

        try {
            DB::transaction(function () use ($kyc) {
                $this->applyToMember($kyc);
                $kyc->update([
                    'status' => CustomersKyc::STATUS_APPROVED,
                    'note' => null,
                    'approved_by' => Auth::guard('admin')->id(),
                    'approved_at' => now(),
                ]);
            });
        } catch (\Exception $e) {
            return back()->with('error', 'อนุมัติไม่สำเร็จ อัปเดตข้อมูลสมาชิกไม่ได้: ' . $e->getMessage());
        }
        return back()->with('success', 'อนุมัติ ' . $kyc->user_name . ' และอัปเดตข้อมูลสมาชิกแล้ว');
    }

    // อนุมัติแล้วให้ข้อมูลปัจจุบันของสมาชิกเป็นค่าที่ยืนยัน (เลขบัตร/เบอร์/บัญชี/รูป) เก็บค่าเดิมไว้ใน customers_doc_history ไม่ลบรูปเก่า
    private function applyToMember($kyc)
    {
        $cid = $kyc->customers_id;
        $adminId = Auth::guard('admin')->id();
        $log = function ($type, $old, $new) use ($cid, $kyc, $adminId) {
            DB::table('customers_doc_history')->insert([
                'customers_id' => $cid,
                'user_name' => $kyc->user_name,
                'type' => $type,
                'action' => 'edit',
                'old_data' => json_encode($old, JSON_UNESCAPED_UNICODE),
                'new_data' => json_encode($new, JSON_UNESCAPED_UNICODE),
                'admin_id' => $adminId,
                'created_at' => now(),
            ]);
        };

        // บัตรประชาชน + เบอร์โทร -> customers, รูปบัตร -> customers_address_card (ถ้ามีแถวอยู่)
        $cust = DB::table('customers')->where('id', $cid)->first();
        if ($cust) {
            $new = ['id_card' => $kyc->id_card, 'phone' => $kyc->phone];
            $old = ['id_card' => $cust->id_card, 'phone' => $cust->phone];
            $card = DB::table('customers_address_card')->where('customers_id', $cid)->first();
            if ($card) {
                $old += ['url' => $card->url, 'img_card' => $card->img_card];
                $new += ['url' => dirname($kyc->img_card), 'img_card' => basename($kyc->img_card)];
                DB::table('customers_address_card')->where('customers_id', $cid)
                    ->update(['url' => $new['url'], 'img_card' => $new['img_card']]);
            }
            DB::table('customers')->where('id', $cid)->update(['id_card' => $kyc->id_card, 'phone' => $kyc->phone]);
            if ($old != $new) {
                $log('card', $old, $new);
            }
        }

        // บัญชีธนาคาร + รูปหน้าบัญชี -> customers_bank
        $bank = DB::table('dataset_bank')->where('id', $kyc->bank_id_fk)->first();
        $newBank = [
            'user_name' => $kyc->user_name,
            'url' => dirname($kyc->img_bank),
            'img_bank' => basename($kyc->img_bank),
            'bank_id_fk' => $kyc->bank_id_fk,
            'bank_name' => $bank->name ?? $kyc->bank_name,
            'code_bank' => $bank->code ?? null,
            'bank_no' => $kyc->bank_no,
        ];
        $oldBank = DB::table('customers_bank')->where('customers_id', $cid)->first();
        DB::table('customers_bank')->updateOrInsert(['customers_id' => $cid], $newBank);
        $log('bank', $oldBank ? (array) $oldBank : null, $newBank);
    }

    public function reject(Request $request, $id)
    {
        $request->validate(['note' => 'required|string|max:255'], ['note.required' => 'กรุณาระบุเหตุผล']);

        $kyc = CustomersKyc::findOrFail($id);
        if ($kyc->status != CustomersKyc::STATUS_PENDING) {
            return back()->with('error', 'รายการนี้ถูกตรวจสอบแล้ว');
        }

        $kyc->update([
            'status' => CustomersKyc::STATUS_REJECTED,
            'note' => $request->note,
            'approved_by' => Auth::guard('admin')->id(),
            'approved_at' => now(),
        ]);
        return back()->with('success', 'ไม่อนุมัติ ' . $kyc->user_name . ' แล้ว');
    }

    // คืนรายการที่ซ้ำกับรหัสอื่นที่อนุมัติแล้ว เช่น ['เลขบัตร (A1234)', 'เบอร์โทร (B5678)']
    private function duplicates($kyc)
    {
        $dup = [];
        $other = CustomersKyc::where('customers_id', '!=', $kyc->customers_id)
            ->where('status', CustomersKyc::STATUS_APPROVED);
        foreach (['id_card' => 'เลขบัตร', 'phone' => 'เบอร์โทร', 'bank_no' => 'เลขบัญชี'] as $col => $label) {
            $names = (clone $other)->where($col, $kyc->$col)->pluck('user_name');
            if ($names->isNotEmpty()) {
                $dup[] = $label . ' (ตรงกับรหัส ' . $names->implode(', ') . ')';
            }
        }
        return $dup;
    }
}
