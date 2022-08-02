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
Route::get('/loginProvider', [ProviderController::class, 'login'])->name('login.provider');
Route::post('/loginProvider', [ProviderController::class, 'loginProvider'])->name('loginProvider');

//* Rutas públicas de invoices
Route::group(['prefix' => '/invoice'], function() {
    Route::get('/create', [InvoiceController::class, 'create'])->name('invoices.create');
    Route::post('/store', [InvoiceController::class, 'store'])->name('invoices.store');
    Route::get('/validateProvider', [InvoiceController::class, 'validateProvider'])->name('invoices.validateProvider');
    Route::get('/createNewProvider', [InvoiceController::class, 'createNewProvider'])->name('invoices.createNewProvider');
    Route::get('/modalPayment', [InvoiceController::class, 'modalPayment'])->name('invoices.modalPayment');
});

//* Rutas de administradores
Route::group(['prefix' => 'administrador', 'middleware' => ['auth']], function() {
    Route::get('/', [AdminController::class, 'index'])->name('admin.index');

    //* Rutas de invoices
    Route::prefix('/invoice', function() {
        Route::get('/index', [InvoiceController::class, 'index'])->name('invoices.index');
        Route::get('/addPayment', [InvoiceController::class, 'addPayment'])->name('invoices.addPayment');
        Route::get('/paymentsBulkUpload', [InvoiceController::class, 'paymentsBulkUpload'])->name('invoices.paymentsBulkUpload');
        Route::get('/providersDatalist', [InvoiceController::class, 'providersDatalist'])->name('invoices.providersDatalist');
        Route::get('/pendingPaymentsTable', [InvoiceController::class, 'pendingPaymentsTable'])->name('invoices.pendingPaymentsTable');
        Route::post('/addFilteredPayments', [InvoiceController::class, 'addFilteredPayments'])->name('invoices.addFilteredPayments');
    });

    //* Rutas de providers
    Route::get('/provider/index', [ProviderController::class, 'index'])->name('providers.index');

    //* Rutas de owners
    Route::get('/owners/index', [OwnerController::class, 'index'])->name('owners.index');
});

//* Rutas de proveedores
Route::group(['middleware' => ['is_provider'] ], function() {
    Route::get('/invoice/myInvoices', [InvoiceController::class, 'myInvoices'])->name('invoices.myInvoices');
});

//!Ruta para usar cmd desde la web
// Route::get('cmd/{command}', function ($command) {
//     Artisan::call($command);
//     dd(Artisan::output());
// });