<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\YohanController;
use App\Http\Controllers\InvoiceController;

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

// Route::get('/', [App\Http\Controllers\JesusController::class, 'index'])->name('index');
// Route::get('/jesus/leerPDF', [App\Http\Controllers\JesusController::class, 'leerPDF'])->name('jesusLeerPDF');
// Route::get('/jesus/leerXML', [App\Http\Controllers\JesusController::class, 'leerXML'])->name('jesusLeerXML');
// Route::get('/jesus/subirArchivos', [App\Http\Controllers\JesusController::class, 'subirArchivos'])->name('jesusSubirArchivos');
// Route::post('/jesus/enviarArchivos', [App\Http\Controllers\JesusController::class, 'enviarArchivos'])->name('jesusenviarArchivos');
//
// Route::resource('yohan', YohanController::class);


Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::group(['middleware' => ['auth'] ], function(){

    Route::resource('invoices', InvoiceController::class);

});

