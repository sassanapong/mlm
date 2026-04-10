<?php

namespace App\Exports;


use Maatwebsite\Excel\Concerns\FromArray;

class LogPvPerDayExport implements FromArray
{
    protected $logArray;

    public function __construct(array $logArray)
    {
        $this->logArray = $logArray;
    }

    public function array(): array
    {
        return $this->logArray;
    }
}
