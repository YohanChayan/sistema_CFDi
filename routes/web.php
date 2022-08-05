<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\YohanController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\PaymentHistoryController;
use App\Http\Controllers\ProviderController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\UniversalDashboardController;
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
// Route::get('/jesus/leerPDF', [App\Http\Controllers\JesusController::class, 'leerPDF'])->name('jesusLeerPDF');
// Route::get('/jesus/leerXML', [App\Http\Controllers\JesusController::class, 'leerXML'])->name('jesusLeerXML');
// Route::get('/jesus/subirArchivos', [App\Http\Controllers\JesusController::class, 'subirArchivos'])->name('jesusSubirArchivos');
// Route::post('/jesus/enviarArchivos', [App\Http\Controllers\JesusController::class, 'enviarArchivos'])->name('jesusenviarArchivos');
//
// Route::resource('yohan', YohanController::class);

Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/loginProvider', [ProviderController::class, 'loginProviderView'])->name('loginProviderView');
Route::post('/loginProvider', [ProviderController::class, 'loginProvider'])->name('loginProvider');

//* Rutas públicas de invoices
Route::group(['prefix' => '/invoice'], function() {
    Route::get('/create', [InvoiceController::class, 'create'])->name('invoices.create');
    Route::post('/store', [InvoiceController::class, 'store'])->name('invoices.store');
    Route::get('/validateProvider', [InvoiceController::class, 'validateProvider'])->name('invoices.validateProvider');
    Route::get('/createNewProvider', [InvoiceController::class, 'createNewProvider'])->name('invoices.createNewProvider');
    Route::get('/modalPayment', [InvoiceController::class, 'modalPayment'])->name('invoices.modalPayment');
});

//* Ruta de dashboard Universal (auth)
Route::group(['middleware' => ['auth']], function() {
    Route::get('/dashboard', [UniversalDashboardController::class, 'index'])->name('dashboard');
});

//* Rutas de administradores
Route::group(['middleware' => ['is_admin'] ], function() {

    Route::group(['prefix' => '/invoice'], function() {
        Route::get('/index', [InvoiceController::class, 'index'])->name('invoices.index');
        Route::get('/addPayment', [InvoiceController::class, 'addPayment'])->name('invoices.addPayment');
        Route::get('/paymentsBulkUpload', [InvoiceController::class, 'paymentsBulkUpload'])->name('invoices.paymentsBulkUpload');
        Route::get('/providersDatalist', [InvoiceController::class, 'providersDatalist'])->name('invoices.providersDatalist');
        Route::get('/pendingPaymentsTable', [InvoiceController::class, 'pendingPaymentsTable'])->name('invoices.pendingPaymentsTable');
        Route::post('/addFilteredPayments', [InvoiceController::class, 'addFilteredPayments'])->name('invoices.addFilteredPayments');
        
    });

    Route::group(['prefix' => '/reports'], function() {
        Route::get('/payments', [ReportsController::class, 'payments'])->name('reports.payments');
        Route::get('/paymentsTable', [ReportsController::class, 'paymentsTable'])->name('reports.paymentsTable');
        Route::get('/paymentsPDFReport', [ReportsController::class, 'paymentsPDFReport'])->name('reports.paymentsPDFReport');

        Route::get('/invoices', [ReportsController::class, 'invoices'])->name('reports.invoices');
        Route::get('/invoicesTable', [ReportsController::class, 'invoicesTable'])->name('reports.invoicesTable');
        Route::get('/invoicesPDFReport', [ReportsController::class, 'invoicesPDFReport'])->name('reports.invoicesPDFReport');
    });

    //* Rutas de providers
    Route::get('/provider/index', [ProviderController::class, 'index'])->name('providers.index');
    
    //* Rutas de owners
    Route::get('/owners/index', [OwnerController::class, 'index'])->name('owners.index');
    
});

//* Rutas de proveedores
Route::group(['middleware' => ['is_provider'] ], function() {
    Route::get('/invoice/myInvoices', [InvoiceController::class, 'myInvoices'])->name('invoices.myInvoices');
    Route::get('/invoice/myInvoicesTable', [InvoiceController::class, 'myInvoicesTable'])->name('invoices.myInvoicesTable');

    Route::get('/payments/myPayments', [PaymentHistoryController::class, 'myPayments'])->name('invoices.myPayments');
    Route::get('/payments/myPaymentsTable', [PaymentHistoryController::class, 'myPaymentsTable'])->name('invoices.myPaymentsTable');
});


//!Ruta para usar cmd desde la web
// Route::get('cmd/{command}', function ($command) {
//     Artisan::call($command);
//     dd(Artisan::output());
// });