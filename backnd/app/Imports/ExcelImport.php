<?php

namespace App\Imports;

use App\Models\ExcelBuffer;
use App\Rules\UniqueNestedEmail;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Validators\Failure;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\RemembersRowNumber;


class ExcelImport implements ToModel,WithHeadingRow,WithValidation,SkipsOnFailure,WithChunkReading,WithBatchInserts
{
    use Importable,SkipsFailures,RemembersRowNumber;

    protected $errors = [];
    protected $successData = [];
    protected $data = [];
    

    public function __construct($data)
    {
      $this->data = $data;
    }

    public function model(array $row)
    {
       
        if($this->data['reupload']){
            $row_array = explode(",",$this->data['row_no']); //error row no from api  - row_no string to array
            foreach($row_array as $row_no){
                if($row_no == $this->getRowNumber()){
                    ExcelBuffer::where('row_no',$this->getRowNumber())->delete();
                    $this->data['row_no'] =   $this->getRowNumber();
                    $this->data['meta_data'] = json_encode($row);
                    $this->data['validation_status'] = true;
                    $this->data['import_status'] =  true;
                    $data = collect($this->data)->except('reupload')->toArray();
                    $model = new ExcelBuffer($data);
                    $this->successData[] = $data;
                    return $model ; 
                }
            }  
        }
       
        if($this->data['reupload'] == false){
       
            $this->data['row_no'] =   $this->getRowNumber();
            $this->data['meta_data'] = json_encode($row);
            $this->data['validation_status'] = true;
            $this->data['import_status'] =  true;
            $bufferData =  new ExcelBuffer($this->data);
            $this->successData[] = $bufferData;
            return $bufferData;
        }
       
        
    }
    public function rules(): array
    {
        
        return [
            'email' => 'required|email', 
            // 'phone' => 'regex:/^\d{3}-\d{3}-\d{4}$/', 
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
            $this->data['row_no'] =   $failure->row();
            $this->data['meta_data'] =  json_encode($failure->values());
            $this->data['message'] =  $failure->errors()[0];
            $buffer =  new ExcelBuffer($this->data);
            $buffer->save();
            $this->data['message'] = '';
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
