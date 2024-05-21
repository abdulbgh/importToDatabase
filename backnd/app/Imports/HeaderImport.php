<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\OnHeadingRow;

class HeaderImport implements ToCollection
{
    protected $columnNames = [];

    public function collection(Collection $rows)
    {
        $this->columnNames = $rows->shift()->toArray();
    }

    public function getColumnNames()
    {
        return $this->columnNames;
    }
}