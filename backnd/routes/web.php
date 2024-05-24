<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExcelController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('import');
});


// Route::post('upload/data',[ExcelController::class,'upload'])->name('upload.data');
// Route::post('cross/join',[ExcelController::class,'crossjoin'])->name('crossjoin.data');