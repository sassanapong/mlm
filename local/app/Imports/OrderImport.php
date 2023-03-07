<?php

namespace App\Imports;

use App\Orders;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class OrderImport implements
    ToModel,
    WithStartRow,
    WithValidation

{
    use Importable, SkipsFailures;
    public function model(array $row)
    {




        $dataPrapare = [
            'tracking_no' => $row[8],
            'order_status_id_fk' => 7,
        ];
        $query = Orders::where('code_order', $row[1])->update($dataPrapare);
    }

    public function startRow(): int
    {
        return 2;
    }

    public function rules(): array
    {
        return [
            '*.1' => ['required'],
            '*.8' => ['required'],

        ];
    }
    public function customValidationMessages()
    {
        return [
            '1.required' => 'กรุณากรอก รหัสสินค้า',
            '8.required' => 'กรุณากรอก เลขพัสดุ',
        ];
    }
}
