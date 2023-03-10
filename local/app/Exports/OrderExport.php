<?php

namespace App\Exports;

use App\Orders;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Reader\Xml\Style\NumberFormat;

class OrderExport implements FromCollection, WithHeadings, WithColumnWidths
{

    protected $data;
    function __construct($data)

    {
        $this->date_start = $data['date_start'];
        $this->date_end = $data['date_end'];
    }


    public function collection()
    {
        $order = Orders::select(
            'db_orders.tracking_no_sort',
            'db_orders.code_order',
            'db_orders.customers_user_name',
            'db_orders.created_at',
            // 'customers.name',
            'db_orders.name',
            'db_orders.pay_type',
            'db_orders.ewallet_price',
            'db_orders.tracking_type',
        )
            ->leftjoin('dataset_order_status', 'dataset_order_status.orderstatus_id', '=', 'db_orders.order_status_id_fk')
            ->leftjoin('customers', 'customers.id', '=', 'db_orders.customers_id_fk')
            ->where('dataset_order_status.lang_id', '=', 1)
            ->where('db_orders.order_status_id_fk', '=', '5')
            ->whereDate('db_orders.created_at', '>=', date('Y-m-d', strtotime($this->date_start)))
            ->whereDate('db_orders.created_at', '<=', date('Y-m-d', strtotime($this->date_end)))
            ->orderby('db_orders.tracking_type', 'asc')
            ->orderby('db_orders.tracking_no_sort', 'asc')
            ->get();


        return ($order);
    }

    public function headings(): array
    {
        return [
            'ลำดับ',
            'Code Order',
            'รหัสผู้สั่งซื้อ',
            'วันที่สั่งซื้อ',
            'ผู้สั่งซื้อ',
            'รูปแบบการชำระเงิน',
            'จำนวนเงิน',
            'ประเภทขนส่ง',
            'Tracking No',
        ];
    }
    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 20,
            'C' => 15,
            'D' => 30,
            'E' => 25,
            'F' => 20,
            'G' => 15,
            'H' => 15,
            'I' => 25,
        ];
    }
}
