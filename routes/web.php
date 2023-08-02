<?php

use App\Http\Controllers\CsvGeneratorController;
use App\Http\Controllers\ImporterController;
use Illuminate\Support\Facades\Route;

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
    return view('welcome');
});

Route::get('/generate-csv', [CsvGeneratorController::class, 'generateCsv']);

Route::get('import', [ImporterController::class, 'import']);
