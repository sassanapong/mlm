<?php

namespace App\Imports;

use App\Http\Controllers\Backend\OrderController;
use App\Orders;
use App\TestExcel;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use DB;

class OrderImport implements
    ToModel,
    WithStartRow,
    WithValidation,
    SkipsOnFailure,
    SkipsEmptyRows

{
    use Importable, SkipsFailures;
    private $items = [];
    public function model(array $row)
    {

        try {
            $dataPrapare = [
                'code_order' => $row[1],
                'tracking_no' => $row[8],
            ];
            // $query = TestExcel::create($dataPrapare);
            // $query_getdata = Orders::select('code_order')->where('code_order', $row[1])->first();
            // all good
            array_push($this->items, $dataPrapare);
            // (new OrderController)->get_material($res);
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function getdata(): array
    {
        return $this->items;
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
