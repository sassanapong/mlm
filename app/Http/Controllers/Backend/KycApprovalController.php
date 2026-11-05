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

        $kyc->update([
            'status' => CustomersKyc::STATUS_APPROVED,
            'note' => null,
            'approved_by' => Auth::guard('admin')->id(),
            'approved_at' => now(),
        ]);
        return back()->with('success', 'อนุมัติ ' . $kyc->user_name . ' แล้ว');
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
