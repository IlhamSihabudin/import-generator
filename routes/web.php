<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImportController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/excel', function () {
    return view('excel');
});
Route::post('/import', [ImportController::class, 'index'])->name('import');
Route::post('/excel', [ImportController::class, 'excel'])->name('excel');
Route::view('/gr/pdf', 'pdf/gr_view');
