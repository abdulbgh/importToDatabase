<?php

namespace App\Http\Controllers;


use App\Models\ExcelBuffer;
use App\Imports\ExcelImport;
use Illuminate\Http\Request;
use App\Services\ExcelService;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use Maatwebsite\Excel\Facades\Excel;



class ExcelController extends Controller
{
    public function __construct(private readonly ExcelService $excelService)
    {
        
    }
    public function upload(Request $request){
            $data = [
                'imported_by' =>  1,
                'module_id' => 1,
                'validation_status' => false,
                'import_status' => false,
                'document_id' => 1,
                'row_no' => 0,
                'message' => '',
                'reupload' => false

            ];
       
            $filePath = $this->excelService->storeFile($request->file('file'),'customer','excel/customer/');
            $data = $this->importData($filePath,$data);
            return response()->json([
                'status' => true,
                'error_data' => $data['error_data'],
                'success_data' => $data['success_data'],
            ]);
    }
 
    public function importData($filePath,$data){
        try{
            $customerImport = new ExcelImport($data);
            $fullPath = storage_path('app/'.$filePath);
            if (file_exists($fullPath)) {
                $customerImport->import($fullPath);
               
                return [
                    'error_data'  =>  $customerImport->getErrors(),
                    'success_data' => $customerImport->getSuccessData(),
                ];
                return;
            } else {
                return  response()->json([
                    'status' => false,
                    'message' => 'File not found'
                ],404);
            } 
        }
        catch (\Exception $e) {
            return  response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ],400);
        } 
            
    }

    public function insertData(){
        $query  = ExcelBuffer::where('import_status',1);
       $data =  $query->pluck('meta_data')->map(function ($item) {
        return json_decode($item, true); // Decode each JSON string
    })->toArray();

       Customer::insert($data);
       $query->delete();
       return response()->json([
        'data' => $data
       ]);
    }

    public function reUpload(Request $request){
            $data = [
                'imported_by' =>  1,
                'module_id' => 1,
                'validation_status' => false,
                'import_status' => false,
                'document_id' => $request->document_id,
                'row_no' => $request->row_id,
                'message' => '',
                'reupload' => true
            ];
        
            $filePath = $this->excelService->storeFile($request->file('file'),'customer','excel/customer/');
            $data = $this->importData($filePath,$data);
          return $data;
            return response()->json([
                'status' => true,
                'error_data' => $data['error_data'],
                'success_data' => $data['success_data'],
            ]);
    }
}
