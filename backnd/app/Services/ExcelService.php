<?php 
namespace App\Services;

use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Schema;



class ExcelService {

    public function createColumnMapping($excelHeader,$tableHeader){
        $excel = $excelHeader;
        $table = $tableHeader;
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

    public function removeColumnsFromArray($columns,$columnsToRemove){
        foreach ($columnsToRemove as $column) {
            if (($key = array_search($column, $columns)) !== false) {
                unset($columns[$key]);
            }
        }
        return $columns;
    }
    public function storeFile($file,$filename,$path) {
        $name = $filename;
        $fileName =  $this->getFileName($file,$name);
        return $file->storeAs($path, $fileName); 
    }
}