<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExcelController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


// Route::post('upload/data',[ExcelController::class,'upload'])->name('upload.data');
// Route::post('cross/join',[ExcelController::class,'crossjoin'])->name('crossjoin.data');



Route::post('upload',[ExcelController::class,'upload']);
Route::get('insert',[ExcelController::class,'insertData']);
Route::post('re-upload',[ExcelController::class,'reUpload']);
// Route::post('cross/join',[ExcelController::class,'crossjoin'])->name('crossjoin.data');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
