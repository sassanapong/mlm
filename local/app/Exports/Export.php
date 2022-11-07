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


class Export implements
    FromCollection,
    WithHeadings,
    WithColumnWidths
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $customer = CustomersBank::select(
            'customers_bank.user_name',
            'customers_bank.code_bank as bank_code',
            'customers_bank.bank_no',
            'customers_bank.account_name as info1',
            'customers_bank.user_name as space',
            'ewallet.amt as amt',
            'customers_bank.user_name as info2',
            'customers_bank.user_name as info3',
            'customers_bank.user_name as info4',
            'customers_bank.user_name as info5',
            'customers_bank.user_name as info6',
            'customers_bank.user_name as info7',
            'customers_bank.user_name as info8',
            'customers.phone',
            'ewallet.transaction_code',
            'customers_bank.user_name as info9',
            'customers_bank.user_name as info10',
        )
            ->join('customers','customers_bank.customers_id','=','customers.id')
            ->join('ewallet', 'customers_bank.customers_id', '=', 'ewallet.customers_id_fk')
            ->where('ewallet.type', '3') // ประเภท
            ->where('ewallet.status', '1') // สถานะ
            ->get()
            ->map(function ($customer) {
                if($customer->amt < 1000){
                $customer->amt = $customer->amt-13;
                }else{
                $customer->amt = $customer->amt - ($customer->amt*3/100) - 13;
                }
                $customer->space = " ";
                $customer->info1  =   $customer->user_name.' / '.$customer->info1;
                $customer->info2  =  'รอคอนเฟริม';
                $customer->info3  =  'N';
                $customer->info4  =  'N';
                $customer->info5  =  'Y';
                $customer->info6  =  ' ';
                $customer->info7  =  ' ';
                if($customer->bank_code != "030"){
                    $customer->info8  =  '0001';
                }else{
                    $customer->info8  =  ' ';
                }
                $customer->info9  =  ' ';
                $customer->info10  =  ' ';
                return $customer;
            });
            // $check = eWallet::select(
            //     'ewallet.transaction_code',
            //     'customers_bank.user_name',
            //     'customers_bank.bank_name as bankcode',
            //     'customers_bank.bank_no',
            //     'customers_bank.account_name as info1',
            //     'customers_bank.user_name as space',
            //     'ewallet.amt as amt',
            //     'ewallet.status',
            //     'customers_bank.user_name as info2',
            //     'customers_bank.user_name as info3',
            //     'customers_bank.user_name as info4',
            //     'customers_bank.user_name as info5',
            //     'customers_bank.user_name as info6',
            //     'customers_bank.user_name as info7',
            //     'customers_bank.user_name as info8',
            // )
            //     ->join('customers_bank', 'ewallet.customers_id_fk', '=', 'customers_bank.customers_id')
            //     ->join('customers','customers_bank.customers_id','=','customers.id')
            //     ->where('ewallet.type', '3') // ประเภท
            //     ->where('ewallet.status', '1') // สถานะ
            //     ->get();
            //     foreach($check as $c){
            //         $ewallet = eWallet::where('transaction_code',$c->transaction_code)->first();
            //         $ewallet->status = "2";
            //         $ewallet->save();
            //     }
        return ($customer);
    }

    public function headings(): array
    {
        return [
            'Vendor ID',
            'Bank Code',
            'Account Number',
            'Vendor Name',
            ' ',
            'Amount',
            'Bene Ref',
            'WHT',
            'Advice',
            'SMS',
            'Payment Detail',
            '#Invoice',
            'Branch Code',
            'เบอร์โทรศัพท์',
            'Transaction Code',
            'วันที่โอน',
            'รายละเอียด',
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
            'H' => 15,
            'I' => 15,
            'J' => 15,
            'K' => 15,
            'L' => 15,
            'M' => 25,
            'N' => 20,
            'O' => 35,
            'P' => 20,
            'Q' => 25,
        ];
    }
    
}