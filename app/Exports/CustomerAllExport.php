<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use ZipArchive;

/**
 * Export ตาราง customers ทุกฟิลด์ (ใช้ชื่อฟิลด์เดิมเป็นหัวตาราง)
 *
 * ตารางมีหลายฟิลด์/หลายแถว PhpSpreadsheet (Maatwebsite) กิน memory จนเต็ม
 * จึงเขียนไฟล์ .xlsx แบบ streaming เองทีละแถว
 */
class CustomerAllExport
{
    protected $status;

    // $status: 'normal' = สมาชิกที่คงอยู่ในระบบ, 'all' = สมาชิกทั้งหมด
    public function __construct($status = 'all')
    {
        $this->status = $status;
    }

    public function headings(): array
    {
        return Schema::getColumnListing('customers');
    }

    // สร้างไฟล์ xlsx ชั่วคราว คืน path
    public function store(): string
    {
        $columns = $this->headings();
        $sheetPath = tempnam(sys_get_temp_dir(), 'cust_sheet_');
        $xlsxPath = tempnam(sys_get_temp_dir(), 'cust_xlsx_');

        $fh = fopen($sheetPath, 'w');
        fwrite($fh, '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main"><sheetData>');
        fwrite($fh, $this->rowXml(1, $columns));

        $query = DB::table('customers');
        if ($this->status == 'normal') {
            $query->where('status_customer', 'normal');
        }

        $n = 1;
        $query->orderBy('id', 'DESC')->chunk(1000, function ($rows) use ($fh, $columns, &$n) {
            foreach ($rows as $r) {
                $row = (array) $r;
                $values = [];
                foreach ($columns as $c) {
                    $values[] = $row[$c] ?? null;
                }
                fwrite($fh, $this->rowXml(++$n, $values));
            }
        });

        fwrite($fh, '</sheetData></worksheet>');
        fclose($fh);

        $zip = new ZipArchive();
        $zip->open($xlsxPath, ZipArchive::OVERWRITE);
        $zip->addFromString('[Content_Types].xml', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">'
            . '<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>'
            . '<Default Extension="xml" ContentType="application/xml"/>'
            . '<Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>'
            . '<Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>'
            . '</Types>');
        $zip->addFromString('_rels/.rels', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
            . '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/>'
            . '</Relationships>');
        $zip->addFromString('xl/workbook.xml', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">'
            . '<sheets><sheet name="customers" sheetId="1" r:id="rId1"/></sheets></workbook>');
        $zip->addFromString('xl/_rels/workbook.xml.rels', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
            . '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/>'
            . '</Relationships>');
        $zip->addFile($sheetPath, 'xl/worksheets/sheet1.xml');
        $zip->close();
        @unlink($sheetPath);

        return $xlsxPath;
    }

    // ทุกค่าเก็บเป็นข้อความ (inlineStr) กันเลขบัตร/เบอร์โทรที่ขึ้นต้นด้วย 0 เพี้ยน
    protected function rowXml(int $rowNum, array $values): string
    {
        $xml = '<row r="' . $rowNum . '">';
        foreach (array_values($values) as $i => $v) {
            if ($v === null || $v === '') {
                continue;
            }
            $v = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F]/', '', (string) $v);
            $xml .= '<c r="' . $this->colName($i) . $rowNum . '" t="inlineStr"><is><t xml:space="preserve">'
                . htmlspecialchars($v, ENT_XML1 | ENT_SUBSTITUTE, 'UTF-8') . '</t></is></c>';
        }
        return $xml . '</row>';
    }

    protected function colName(int $i): string
    {
        $name = '';
        for ($i++; $i > 0; $i = intdiv($i - 1, 26)) {
            $name = chr(65 + ($i - 1) % 26) . $name;
        }
        return $name;
    }
}
