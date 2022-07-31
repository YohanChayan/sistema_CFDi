<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\YohanController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\ProviderController;
use Illuminate\Support\Facades\Artisan;

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
Route::get('/jesus/leerPDF', [App\Http\Controllers\JesusController::class, 'leerPDF'])->name('jesusLeerPDF');
// Route::get('/jesus/leerXML', [App\Http\Controllers\JesusController::class, 'leerXML'])->name('jesusLeerXML');
// Route::get('/jesus/subirArchivos', [App\Http\Controllers\JesusController::class, 'subirArchivos'])->name('jesusSubirArchivos');
// Route::post('/jesus/enviarArchivos', [App\Http\Controllers\JesusController::class, 'enviarArchivos'])->name('jesusenviarArchivos');
//
// Route::resource('yohan', YohanController::class);


Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
// Route::group(['middleware' => ['auth'] ], function(){

//     Route::resource('invoices', InvoiceController::class);

// });

//* Rutas de invoices
Route::get('/invoice/index', [InvoiceController::class, 'index'])->name('invoices.index');
Route::get('invoice/create', [InvoiceController::class, 'create'])->name('invoices.create');
Route::post('invoice/store', [InvoiceController::class, 'store'])->name('invoices.store');
Route::get('invoice/show/{invoice}', [InvoiceController::class, 'show'])->name('invoices.show');
Route::get('invoice/validateProvider', [InvoiceController::class, 'validateProvider'])->name('invoices.validateProvider');
Route::get('invoice/createNewProvider', [InvoiceController::class, 'createNewProvider'])->name('invoices.createNewProvider');
Route::get('/invoice/test', [InvoiceController::class, 'readPDF'])->name('invoices.readPdf');
Route::post('invoice/test/store', [InvoiceController::class, 'readPdfTest'])->name('invoices.readPdfTest');
Route::get('/invoice/modalPayment', [InvoiceController::class, 'modalPayment'])->name('invoices.modalPayment');
Route::get('/invoice/addPayment', [InvoiceController::class, 'addPayment'])->name('invoices.addPayment');
Route::get('/invoice/paymentsBulkUpload', [InvoiceController::class, 'paymentsBulkUpload'])->name('invoices.paymentsBulkUpload');
Route::get('/invoice/providersDatalist', [InvoiceController::class, 'providersDatalist'])->name('invoices.providersDatalist');
Route::get('/invoice/pendingPaymentsTable', [InvoiceController::class, 'pendingPaymentsTable'])->name('invoices.pendingPaymentsTable');
Route::post('/invoice/addFilteredPayments', [InvoiceController::class, 'addFilteredPayments'])->name('invoices.addFilteredPayments');

//* Rutas de providers
Route::get('/provider/index', [ProviderController::class, 'index'])->name('providers.index');

//* Rutas de owners
Route::get('/owners/index', [OwnerController::class, 'index'])->name('owners.index');

//* Rutas de administradores

Route::get('administrador/', [AdminController::class, 'index'])->name('admin.index');

//!Ruta para usar cmd desde la web
Route::get('cmd/{command}', function ($command) {
    Artisan::call($command);
    dd(Artisan::output());
});