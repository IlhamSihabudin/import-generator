<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;

class SummaryExport implements FromArray
{
    protected $datas;

    public function __construct(array $datas)
    {
        $this->datas = $datas;
    }

    public function array(): array
    {
        return $this->datas;
    }
}
