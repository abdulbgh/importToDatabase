<?php

namespace App\Imports;

use App\Models\Customer;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CustomerImport implements ToModel, WithHeadingRow
{
    protected $mapping;

    public function __construct($mapping)
    {
        $this->mapping = $mapping;
    }

    public function model(array $row)
    {
        $userData = [];

        foreach ($this->mapping as $excelColumn => $tableColumn) {
            $userData[$tableColumn] = $row[$excelColumn];
        }

        return new Customer($userData);
    }
}