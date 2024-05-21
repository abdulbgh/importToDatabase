<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use App\Imports\HeaderImport;
use App\Imports\CustomerImport;
use Exception;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class ExcelController extends Controller
{
    public function upload(Request $request){
        try{
            $name = 'customer'; //filename
            $file = $request->file('file');
            $fileName = $this->getFileName($file,$name);
            $file->storeAs('excel/customer/', $fileName);  //store file
            $import = new HeaderImport();
            $excelHeader = $this->getExcelColumnName($import,$file);
            $model = new Customer();
            $tableHeader = $this->getTableColumnName($model);
            $matchedData = [];
            foreach ($tableHeader as $column) {
                if (in_array($column, $excelHeader)) {
                    // Match found, add to matched data array
                    $matchedData[] = $column; // Or you can assign the index of $column to retain Excel order
                }
            }

         
            return response()->json([
                'status' => true,
                'excelHeader' =>$excelHeader,
                'tableHeader' => $tableHeader,
                'matchData' => $matchedData
            ],200);
        }catch(Exception $e) {
            return response()->json([
                'status' => false,
                'excelHeader' => [],
                'tableHeader' => [],
                'message' => $e->getMessage()
            ]);
        }
        // return view('compare',compact('excelHeader','tableHeader'));
       
    }
    public function crossjoin(Request $request){
    
       try{
        $columnMapping = $this->createColumnMapping($request);
        $customerImport = new CustomerImport($columnMapping);
        $filePath  =  'excel/customer/customer.xlsx';
        $result =  $this->storeExcelDataToDatabase($customerImport,$filePath);
        return  response()->json([
            'status' => true,
            'message' => $result
        ]);
        
       }catch(\Exception $e){
        return  response()->json([
            'status' => false,
            'message' => $e->getMessage()
        ],400);
       }
       
    }

    public function storeExcelDataToDatabase($customerImport,$filePath){
       
        $fullPath = storage_path('app/'.$filePath);
        if (file_exists($fullPath)) {
            Excel::import($customerImport, $fullPath);
            return 'Successfully Excel Data store to Database';
        } else {
            abort(404, 'File not found');
        }
    }


    public function createColumnMapping($request){
        $excel = $request->excel;
        $table = $request->table;
        $columnMapping = array_combine($excel, $table);
        return $columnMapping;
    }

    public function getFileName($file,$name) {
        $fileName = $name.'.'. $file->getClientOriginalExtension();
        $filename = trim($fileName);
        return $filename ;
    }
    public function getExcelColumnName($import,$file){
        Excel::import($import, $file);
        $excelHeader = $import->getColumnNames();
        return $excelHeader;
    }
    public function getTableColumnName($model) {
       
        $tableName = $model->getTable();
        $tableHeader = Schema::getColumnListing($tableName);
        if (!in_array('id', $tableHeader)) {
            $tableHeader[] = 'id';
        }
        return $tableHeader;
    }
}
