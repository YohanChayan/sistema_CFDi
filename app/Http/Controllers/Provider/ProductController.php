<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\PaymentHistory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Response;

use RealRashid\SweetAlert\Facades\Alert;

class ProductController extends Controller
{
    public function myProducts() {
        $user = User::with('provider')->find(Auth::id());
        $invoices = Invoice::where('provider_id', $user->provider->id)->get();

        $invoices_ids = [];
        foreach($invoices as $invoice) {
            array_push($invoices_ids, $invoice->id);
        }

        $products = InvoiceDetail::with('sat_product', 'sat_measurement_unit')->whereIn('invoice_id', $invoices_ids)->get()->unique('name');

        return view('app.providers.products.index')->with('products', $products);
    }

    public function myPaymentsTable(Request $request) {
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        $payments = PaymentHistory::with('invoice')->whereBetween('date', [$startDate, $endDate])->where('user_id', auth()->user()->id)->get();

        return view('app.providers.payments.ajax.myPaymentsTable')->with('payments', $payments);
    }
}
