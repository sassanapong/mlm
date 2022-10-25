<?php

namespace App\Exports;

use App\Customers;
use App\CustomersBank;
use App\CustomersBenefit;
use App\eWallet;


use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

class Exportaccounting implements
    FromCollection,
    WithHeadings,
    WithColumnWidths,
    WithColumnFormatting
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $customer = CustomersBank::select(
            'customers_bank.user_name',
            'customers_bank.account_name',
            'ewallet.amt',            
            'ewallet.amt as tax',     
            'ewallet.amt as infoamt',     
            'customers.id_card as idcard',     
        )
            ->join('customers','customers_bank.customers_id','=','customers.id')
            ->join('ewallet', 'customers_bank.customers_id', '=', 'ewallet.customers_id_fk')
            ->where('ewallet.type', '3') // ประเภท
            ->where('ewallet.status', '1') // สถานะ
            ->get()
            ->map(function ($customer) {
                if($customer->amt < 1000){
                    $customer->infoamt = $customer->amt-13;
                    $customer->tax = "-";
                }else{
                    $customer->tax = ($customer->amt*3/100);
                    $customer->amt = $customer->amt - ($customer->amt*3/100) - 13;
                }
                $customer->idcard = "$customer->idcard";
                return $customer;
            });
        return ($customer);
    }

    public function headings(): array
    {
        return [
            'รหัสสมาชิก',
            'รายชื่อ',
            'ยอดถอน',
            'ภาษี 3%',
            'ยอดสุทธิ',
            'เลขบัตรประชาชน',
            'ที่อยู่',
        ];
    }
    public function columnWidths(): array
    {
        return [
            'A' => 15,
            'B' => 25,     
            'C' => 15,      
            'D' => 10,      
            'E' => 25,   
            'F' => 35,
            'G' => 35,
        ];
    }
    public function columnFormats(): array
    {
        return [
            'F' => '[$-,100]0000000000000' ,
        ];
    }
}