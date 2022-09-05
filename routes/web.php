<?php

use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\ReportsController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\Provider\InvoiceController as ProviderInvoiceController;
use App\Http\Controllers\Provider\PaymentHistoryController;
use App\Http\Controllers\ProviderController;
use App\Http\Controllers\UniversalDashboardController;

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

//* Rutas públicas de facturas
Route::group(['prefix' => '/invoice'], function() {
    Route::get('/create', [ProviderInvoiceController::class, 'create'])->name('invoices.create');
    Route::post('/store', [ProviderInvoiceController::class, 'store'])->name('invoices.store');
    Route::get('/validateProvider', [ProviderInvoiceController::class, 'validateProvider'])->name('invoices.validateProvider');
    Route::get('/createNewProvider', [ProviderInvoiceController::class, 'createNewProvider'])->name('invoices.createNewProvider');
});

//* Ruta de dashboard Universal (auth)
Route::group(['middleware' => ['auth']], function() {
    Route::get('/dashboard', [UniversalDashboardController::class, 'index'])->name('dashboard');
});

//* Rutas de administradores
Route::group(['middleware' => ['is_admin'] ], function() {

    //* Rutas de facturas y pagos
    Route::group(['prefix' => '/invoice'], function() {
        Route::get('/index', [InvoiceController::class, 'index'])->name('invoices.index');
        Route::get('/invoicesTable', [InvoiceController::class, 'invoicesTable'])->name('invoices.invoicesTable');
        Route::post('/addPayment2', [InvoiceController::class, 'addPayment2'])->name('invoices.addPayment2');
        Route::get('/addPayment', [InvoiceController::class, 'addPayment'])->name('invoices.addPayment');
        Route::get('/modalPayment', [InvoiceController::class, 'modalPayment'])->name('invoices.modalPayment');
        Route::get('/downloadfile/{id}',[InvoiceController::class, 'download'])->name('invoices.download');
        Route::get('/resendEmail/{id}',[InvoiceController::class, 'resendEmail'])->name('invoices.resendEmail');
        Route::get('/delete/{id}',[InvoiceController::class, 'destroy'])->name('invoices.destroy');

        Route::get('/paymentsBulkUpload', [InvoiceController::class, 'paymentsBulkUpload'])->name('invoices.paymentsBulkUpload');
        Route::get('/providersDatalist', [InvoiceController::class, 'providersDatalist'])->name('invoices.providersDatalist');
        Route::get('/pendingPaymentsTable', [InvoiceController::class, 'pendingPaymentsTable'])->name('invoices.pendingPaymentsTable');
        Route::post('/addFilteredPayments', [InvoiceController::class, 'addFilteredPayments'])->name('invoices.addFilteredPayments');
    });

    //* Rutas de reportes
    Route::group(['prefix' => '/reports'], function() {
        Route::get('/payments', [ReportsController::class, 'payments'])->name('reports.payments');
        Route::get('/paymentsTable', [ReportsController::class, 'paymentsTable'])->name('reports.paymentsTable');
        Route::get('/paymentsPDFReport', [ReportsController::class, 'paymentsPDFReport'])->name('reports.paymentsPDFReport');

        Route::get('/invoices', [ReportsController::class, 'invoices'])->name('reports.invoices');
        Route::get('/invoicesTable', [ReportsController::class, 'invoicesTable'])->name('reports.invoicesTable');
        Route::get('/invoicesPDFReport', [ReportsController::class, 'invoicesPDFReport'])->name('reports.invoicesPDFReport');
    });


    Route::get('/provider/index', [ProviderController::class, 'index'])->name('providers.index');
    Route::get('/owners/index', [OwnerController::class, 'index'])->name('owners.index');
});

//* Rutas de proveedores
Route::group(['middleware' => ['is_provider'] ], function() {
    Route::get('/invoice/myInvoices', [ProviderInvoiceController::class, 'myInvoices'])->name('invoices.myInvoices');
    Route::get('/invoice/myInvoicesTable', [ProviderInvoiceController::class, 'myInvoicesTable'])->name('invoices.myInvoicesTable');

    Route::get('/payments/myPayments', [PaymentHistoryController::class, 'myPayments'])->name('invoices.myPayments');
    Route::get('/payments/preview', [PaymentHistoryController::class, 'preview'])->name('invoices.preview');
    Route::get('/payments/download/${id}', [PaymentHistoryController::class, 'download'])->name('invoices.downloadPayment');
    Route::get('/payments/myPaymentsTable', [PaymentHistoryController::class, 'myPaymentsTable'])->name('invoices.myPaymentsTable');
});


//!Ruta para usar cmd desde la web
// Route::get('cmd/{command}', function ($command) {
//     Artisan::call($command);
//     dd(Artisan::output());
// });
