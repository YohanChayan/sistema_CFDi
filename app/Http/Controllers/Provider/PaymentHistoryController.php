<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use App\Models\PaymentHistory;
use Illuminate\Http\Request;
use Response;

use RealRashid\SweetAlert\Facades\Alert;

class PaymentHistoryController extends Controller
{
    public function myPayments() {
        return view('app.providers.payments.index');
    }

    public function myPaymentsTable(Request $request) {
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        $payments = PaymentHistory::with('invoice')->whereBetween('date', [$startDate, $endDate])->where('user_id', auth()->user()->id)->get();

        return view('app.providers.payments.ajax.myPaymentsTable')->with('payments', $payments);
    }

    public function preview(Request $request){
        $id = $request->input('id');
        $payment = PaymentHistory::find($id);
        if($payment->receipt){
            return asset($payment->receipt);
        }else
            return 'No';

    }

    public function download($id){
        $payment = PaymentHistory::find($id);
            if($payment->receipt)
                return Response::download($payment->receipt);
            else{
                Alert::error('Error', 'No existe archivo de factura con este pago');
                return redirect()->back();
                }
    }

}
