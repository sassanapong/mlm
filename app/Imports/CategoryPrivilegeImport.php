<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToArray;

// อ่านไฟล์ Excel/CSV ทั้งชีตแรกเป็น array (ใช้เฉพาะคอลัมน์แรก = รหัสสมาชิก)
class CategoryPrivilegeImport implements ToArray
{
    public function array(array $array)
    {
        //
    }
}
