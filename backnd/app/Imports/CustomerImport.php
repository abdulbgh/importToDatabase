<?php

namespace App\Imports;

use App\Models\Customer;


use Illuminate\Support\Facades\Log;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Validators\Failure;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class CustomerImport implements ToModel, WithHeadingRow,WithValidation,SkipsOnFailure,WithChunkReading,WithBatchInserts
{
    use Importable, SkipsFailures;

    protected $errors = [];
    protected $successData = [];
    public function __construct()
    {
       
    }

    public function model(array $row)
    {
        $customer =  new Customer([
            'id' => $row['id'],
            'name' => $row['name'],
            'email' => $row['email'],
            'phone' => $row['phone'],
            'gender' => $row['gender'],
            'dob' => $row['dob'],
            'ip_address' => $row['ip_address'],
        ]);
        $this->successData[] = $customer;
        return $customer;
    
    }

    public function rules(): array
    {
        return [
            'email' => 'email|unique:customers,email', 
            'phone' => 'regex:/^\d{3}-\d{3}-\d{4}$/', 
        ];
    }

    public function onFailure(Failure ...$failures)
    {
        foreach ($failures as $failure) {
            $this->errors[] = [
                'row' => $failure->row(),
                'errors' => $failure->errors(),
                'values' => $failure->values(),
            ];
        }
    }

    public function getErrors()
    {
        return $this->errors;
    }
    
    public function getSuccessData()
    {
        return $this->successData;
    }
    public function batchSize(): int
    {
        return 100;
    }
    public function chunkSize(): int
    {
        return 100;
    }
 

}