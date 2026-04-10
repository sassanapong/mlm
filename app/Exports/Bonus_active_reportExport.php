<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class Bonus_active_reportExport implements FromArray, WithHeadings
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        return $this->data;
    }

    public function headings(): array
    {
        return [
            'วันที่ทำรายการ',
            'เลขที่รายการ',
            'ผู้ทำรายการ',
            'Active',
            'ผู้รับโบนัส',
            'ตำแหน่ง',
            'ชั้น',
            'PV',
            'ยอดที่ได้รับ',
            '% โบนัส',
            'หักภาษี Tax(3%)',
            'ยอดสุทธิ',
        ];
    }
}
