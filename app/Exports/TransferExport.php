<?php

namespace App\Exports;

use App\Customers;
use App\Member;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

/*
| ส่งออกรายการโอนเงิน (หน้า /admin/transfer) "ทุกแถวตามตัวกรอง" ไม่ใช่เฉพาะหน้าที่แสดง
| $query = query เดียวกับตารางในหน้า (eWalletController::transferQuery)
*/
class TransferExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    protected $query;

    public function __construct($query)
    {
        $this->query = $query;
    }

    public function headings(): array
    {
        return [
            'ลำดับ', 'รหัสรายการ', 'วันที่ทำรายการ', 'ผู้โอน', 'ผู้รับ',
            'จำนวนเงิน', 'จำนวนเงินที่แก้ไข', 'ประเภท', 'สถานะ', 'วันที่อนุมัติ', 'ผู้อนุมัติ',
        ];
    }

    public function collection()
    {
        $rows = $this->query->get();

        // ดึงชื่อสมาชิก/ผู้อนุมัติทีเดียว ไม่ query ทีละแถว
        $usernames = $rows->pluck('customers_username_tranfer')
            ->merge($rows->pluck('customers_name_receive'))
            ->filter()->unique()->values()->all();

        $customers = [];
        foreach (array_chunk($usernames, 1000) as $chunk) {
            foreach (Customers::select('name', 'last_name', 'user_name')->whereIn('user_name', $chunk)->get() as $c) {
                $customers[$c->user_name] = trim($c->name . ' ' . $c->last_name) . ' (' . $c->user_name . ')';
            }
        }

        $memberIds = $rows->pluck('ew_mark')->filter()->unique()->values()->all();
        $members = [];
        if ($memberIds) {
            foreach (Member::select('id', 'name', 'last_name')->whereIn('id', $memberIds)->get() as $m) {
                $members[$m->id] = trim($m->name . ' ' . $m->last_name);
            }
        }

        $statusText = [1 => 'รออนุมัติ', 2 => 'อนุมัติ', 3 => 'ไม่อนุมัติ'];
        $typeText = [1 => 'ฝากเงิน', 2 => 'โอนเงิน', 3 => 'ถอนเงิน'];

        $i = 0;

        return $rows->map(function ($r) use (&$i, $customers, $members, $statusText, $typeText) {
            $i++;
            $status = $statusText[$r->status] ?? '';
            if (!empty($r->type_note)) {
                $status .= ' (' . $r->type_note . ')';
            }

            return [
                $i,
                $r->transaction_code,
                $r->created_at ? date('d-m-Y H:i:s', strtotime($r->created_at)) : '',
                $customers[$r->customers_username_tranfer] ?? '-',
                $customers[$r->customers_name_receive] ?? '-',
                (float) $r->amt,
                $r->edit_amt ? (float) $r->edit_amt : '',
                $typeText[$r->type] ?? '',
                $status,
                ($r->date_mark && strtotime($r->date_mark) > 0) ? date('d-m-Y H:i:s', strtotime($r->date_mark)) : '-',
                $members[$r->ew_mark] ?? '-',
            ];
        });
    }
}
