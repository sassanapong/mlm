<?php

namespace App\Exports;

use App\Orders;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class OrderExport implements FromCollection, WithHeadings, WithColumnWidths
{

    protected $data;

    function __construct($data)

    {

        dd($data);
    }


    public function collection()
    {
        $customer = Orders::select(
            'db_orders.code_order',
            'db_orders.customers_user_name',
            'customers.name',
            'db_orders.pay_type',
            'db_orders.ewallet_price',
            'db_orders.id as info1',
            'db_orders.id as info2',
        )
            ->leftjoin('dataset_order_status', 'dataset_order_status.orderstatus_id', '=', 'db_orders.order_status_id_fk')
            ->leftjoin('customers', 'customers.id', '=', 'db_orders.customers_id_fk')
            ->where('dataset_order_status.lang_id', '=', 1)
            ->where('db_orders.order_status_id_fk', '=', '5')
            ->orderby('db_orders.updated_at', 'DESC')
            ->get()
            ->map(function ($customer) {
                $customer->info1  = "7";
                $customer->info2  = "";
                return $customer;
            });
        return ($customer);
    }

    public function headings(): array
    {
        return [
            'Code Order',
            'รหัสผู้สั่งซื้อ',
            'ผู้สั่งซื้อ',
            'รูปแบบการชำระเงิน',
            'จำนวนเงิน',
            'สถานะ',
            'Tracking No',
        ];
    }
    public function columnWidths(): array
    {
        return [
            'A' => 45,
            'B' => 25,
            'C' => 35,
            'D' => 35,
            'E' => 10,
            'F' => 50,
            'G' => 15,
        ];
    }
}
