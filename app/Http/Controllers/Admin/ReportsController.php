<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Owner;
use App\Models\PaymentHistory;
use Illuminate\Http\Request;
use Mpdf\Mpdf;

class ReportsController extends Controller
{
    /****************************************/
    /*********** REPORTE DE PAGOS ***********/
    /****************************************/

    public function payments() {
        $owners = Owner::all();
        return view('app.admin.reports.payments.web')->with('owners', $owners);
    }

    public function paymentsTable(Request $request) {
        $owner = $request->get('owner');
        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date');

        $invoices = Invoice::where('owner_id', $owner)->get();
        $invoice_ids = [];

        foreach($invoices as $invoice) {
            array_push($invoice_ids, $invoice->id);
        }

        $payments = PaymentHistory::with('invoice')->whereIn('invoice_id', $invoice_ids)->whereBetween('date', [$start_date, $end_date])->get();

        return view('app.admin.reports.payments.ajax.paymentsTable')->with('payments', $payments);
    }

    public function paymentsPDFReport(Request $request) {
        $id = $request->get('owner');
        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date');
        $date = date('d/m/Y', strtotime($start_date)) . ' — ' . date('d/m/Y', strtotime($end_date));

        $invoices = Invoice::where('owner_id', $id)->get();
        $invoice_ids = [];

        foreach($invoices as $invoice) {
            array_push($invoice_ids, $invoice->id);
        }

        $owner = Owner::find($id);
        $payments = PaymentHistory::with('invoice')->whereIn('invoice_id', $invoice_ids)->whereBetween('date', [date('Y-m-d 00:00:00', strtotime($start_date)), date('Y-m-d 23:59:59', strtotime($end_date))])->orderBy('date', 'asc')->get();

        $arreglo = [];
        $totalPayments = 0;
        foreach($payments as $payment) {
            $totalPayments += $payment->payment;
            
            if(count($arreglo) == 0) {
                array_push($arreglo, [[
                    'date' => $payment->date,
                    'provider_name' => $payment->invoice->provider->nombre,
                    'provider_rfc' => $payment->invoice->provider->rfc,
                    'folio' => $payment->invoice->folio,
                    'uuid' => $payment->invoice->uuid,
                    'payment' => $payment->payment,
                ]]);
            }
            else {
                $bandera = false;

                foreach($arreglo as $key => $datos) {
                    foreach($datos as $dato) {
                        if($dato['provider_rfc'] == $payment->invoice->provider->rfc) {
                            array_push($arreglo[$key], [
                                'date' => $payment->date,
                                'provider_name' => $payment->invoice->provider->nombre,
                                'provider_rfc' => $payment->invoice->provider->rfc,
                                'folio' => $payment->invoice->folio,
                                'uuid' => $payment->invoice->uuid,
                                'payment' => $payment->payment,
                            ]);
                            $bandera = true;
                            break;
                        }
                    }

                    if($bandera) {
                        break;
                    }
                }

                if(!$bandera) {
                    array_push($arreglo, [[
                        'date' => $payment->date,
                        'provider_name' => $payment->invoice->provider->nombre,
                        'provider_rfc' => $payment->invoice->provider->rfc,
                        'folio' => $payment->invoice->folio,
                        'uuid' => $payment->invoice->uuid,
                        'payment' => $payment->payment,
                    ]]);
                }
            }
        }

        $html = view('app.admin.reports.payments.pdf')->with('payments', $arreglo)->with('date', $date)->with('owner', $owner)->with('totalPayments', $totalPayments)->render();
        $name_file = 'pagos_' . time() . '.pdf';
        $mpdf = new Mpdf([
            'default_font' => 'arial',
            'mode' => 'utf-8',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 10,
            'margin_bottom' => 10,
            'orientation' => 'L',
            'format' => 'letter',
        ]);
        
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->WriteHTML($html);
        $mpdf->Output($name_file, 'I');
    }

    /****************************************/
    /********** REPORTE DE FACTURAS *********/
    /****************************************/

    public function invoices() {
        $owners = Owner::all();
        return view('app.admin.reports.invoices.web')->with('owners', $owners);
    }

    public function invoicesTable(Request $request) {
        $owner = $request->get('owner');
        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date');

        $invoices = Invoice::whereBetween('created_at', [date('Y-m-d 00:00:00', strtotime($start_date)), date('Y-m-d 23:59:59', strtotime($end_date))])->where('owner_id', $owner)->get();

        return view('app.admin.reports.invoices.ajax.invoicesTable')->with('invoices', $invoices);
    }

    public function invoicesPDFReport(Request $request) {
        $id = $request->get('owner');
        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date');
        $date = date('d/m/Y', strtotime($start_date)) . ' — ' . date('d/m/Y', strtotime($end_date));

        $invoices = Invoice::whereBetween('created_at', [date('Y-m-d 00:00:00', strtotime($start_date)), date('Y-m-d 23:59:59', strtotime($end_date))])->where('owner_id', $id)->get();
        $owner = Owner::find($id);

        $html = view('app.admin.reports.invoices.pdf')->with('invoices', $invoices)->with('date', $date)->with('owner', $owner)->render();
        $name_file = 'pagos_' . time() . '.pdf';
        $mpdf = new Mpdf([
            'default_font' => 'arial',
            'mode' => 'utf-8',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 10,
            'margin_bottom' => 10,
            'orientation' => 'L',
            'format' => 'letter',
        ]);
        
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->WriteHTML($html);
        $mpdf->Output($name_file, 'I');
    }
}
