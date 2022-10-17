<?php

namespace App\Imports;

use App\Orders;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class OrderImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {   
        
            foreach ($rows as $key => $row ) 
            {
                if($key != 0){
                    $order = Orders::where('code_order',$row[0])->first();
                    $order->order_status_id_fk = $row[5];
                    $order->tracking_no = $row[6];
                    $order->save();
                }
            }
        
    }
}
