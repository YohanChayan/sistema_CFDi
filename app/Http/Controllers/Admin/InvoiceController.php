<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\FilesReceived;
use Illuminate\Http\Request;

use App\Models\Invoice;
use App\Models\Owner;
use App\Models\Provider;
use App\Models\PaymentHistory;

use Smalot\PdfParser\Parser;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Response;
use ZipArchive;

class InvoiceController extends Controller
{
    public function index()
    {
        $owners = Owner::all();
        $invoices = Invoice::with('owner', 'provider')->where('status', 'A')->orderBy('created_at', 'desc')->get();
        return view('app.admin.invoices.index')->with('invoices', $invoices)->with('owners', $owners);
    }

    public function invoicesTable(Request $request) {
        $owner = $request->get('owner');
        $provider = $request->get('provider');

        $invoices = Invoice::with('provider')->where('status', 'A')->get();

        if($owner != -1) {
            $invoices = $invoices->where('owner_id', $owner);
        }
        if($provider != -1) {
            $invoices = $invoices->where('provider_id', $provider);
        }

        return view('app.admin.invoices.ajax.invoicesTable')->with('invoices', $invoices);
    }

    public function leerPDF($id) {
        $invoice =  Invoice::find($id);

        $data = $this->GetDataFromPDF($invoice);
        // return view('jesus.leerPDF', ["data" => $data]);
    }

    private function GetDataFromPDF($invoice){
        $parser = new Parser();   //Crea una instancia de la clase Parser
        // $pdf = $parser->parseFile(public_path('archivos/pdf/565_PPA180626CC4.pdf'));   //Obtiene el PDF y lo guarda en un objeto de la clase Parser
        $pdf = $parser->parseFile(public_path('archivos/pdf/565_PPA180626CC4.pdf'));   //Obtiene el PDF y lo guarda en un objeto de la clase Parser
        $text = $pdf->getText();   //Convierte el PDF a texto
        // dd($text);
        $array = explode("\n", $text);   //Separa el PDF en un arreglo, utilizando como delimitador el salto de línea
        $dataFirstFilter = [];   //Arreglo que contendrá la información seleccionada del PDF después de aplicar el primer filtro (patron = "\n")
        // dd($array);

        $flagCFDI = false;   //Bandera que sirve para añadir el elemento que sigue después del texto CFDI, es decir, el nombre del emisor
        $flagRFCReceptor = false;   //Bandera que sirve para añadir el elemento que sigue después del texto Receptor, es decir, el nombre del receptor
        $flagRegimenFiscalEmisor = false;   //Bandera que sirve para añadir el elemento que sigue después del texto RFC (emisor), es decir, el regimen fiscal del emisor
        $flagRegimenFiscalReceptor = false;   //Bandera que sirve para añadir el elemento que sigue después del texto RFC (receptor), es decir, el regimen fiscal del receptor
        $keyRFC = "";   //Almacena la llave (contador) del texto y la compara con el último registro (regimen fiscal) que se agregó al arreglo

        foreach($array as $key => $arr) {
            if($flagCFDI) {
                array_push($dataFirstFilter, $arr);
                $flagCFDI = false;
            }
            if($flagRFCReceptor) {
                array_push($dataFirstFilter, $arr);
                $flagRFCReceptor = false;
            }
            if($flagRegimenFiscalEmisor) {
                if($keyRFC == "") {
                    array_push($dataFirstFilter, $arr);
                    $keyRFC = $key;
                }
                $flagRegimenFiscalEmisor = false;
            }
            if($flagRegimenFiscalReceptor) {
                if($keyRFC != "" && $keyRFC != $key) {
                    array_push($dataFirstFilter, $arr);
                }
                $flagRegimenFiscalReceptor = false;
            }
            if(str_contains($arr, "CFDI"))
                $flagCFDI = true;
            if(str_contains($arr, "RECEPTOR"))
                $flagRFCReceptor = true;

            if(str_contains($arr, "FOLIO"))
                array_push($dataFirstFilter, $arr);
            if(str_contains($arr, "FECHA DE EMISIÓN"))
                array_push($dataFirstFilter, $arr);
            if(str_contains($arr, "FECHA DE TIMBRADO"))
                array_push($dataFirstFilter, $arr);
            if(str_contains($arr, "TOTAL"))
                array_push($dataFirstFilter, $arr);
            if(str_contains($arr, "NO. SERIE CSD DEL EMISOR"))
                array_push($dataFirstFilter, $arr);
            if(str_contains($arr, "RFC")) {
                array_push($dataFirstFilter, $arr);
                $flagRegimenFiscalEmisor = true;
                $flagRegimenFiscalReceptor = true;
            }
            if(str_contains($arr, "UUID"))
                array_push($dataFirstFilter, $arr);
            if(str_contains($arr, "NO. SERIE CSD DEL SAT"))
                array_push($dataFirstFilter, $arr);
        }

        //dd($dataFirstFilter);

        $dataSecondFilter = [];   //Arreglo que contendrá la información seleccionada del PDF después de aplicar el segundo filtro (patrón = ": ")

        foreach($dataFirstFilter as $data1)
            array_push($dataSecondFilter, explode(": ", $data1));

        //dd($dataSecondFilter);
        $dataThirdFilter = [];   //Arreglo que contendrá la información seleccionada del PDF después de aplicar el tercer filtro (patrón = revisión de llaves y aplicación de expresiones regulares)
        $dataThirdFilter = [
            "FOLIO" => "",
            "FECHA DE EMISIÓN" => "",
            "FECHA DE TIMBRADO" => "",
            "TOTAL" => "",
            "NO. SERIE CSD DEL EMISOR" => "",
            "NOMBRE EMISOR" => "",
            "RFC EMISOR" => "",
            "REGIMEN FISCAL EMISOR" => "",
            "NOMBRE RECEPTOR" => "",
            "RFC RECEPTOR" => "",
            "REGIMEN FISCAL RECEPTOR" => "",
            "UUID" => "",
            "NO. SERIE CSD DEL SAT" => ""
        ];

        foreach($dataSecondFilter as $data2) {
            foreach($data2 as $key => $d2) {
                if(count($data2) == 1) {
                    if(str_contains($d2, "(") && str_contains($d2, ")")) {
                        if($dataThirdFilter["REGIMEN FISCAL EMISOR"] == "") {
                            $dataThirdFilter["REGIMEN FISCAL EMISOR"] = trim($data2[$key]);
                            break;
                        }
                        else if($dataThirdFilter["REGIMEN FISCAL RECEPTOR"] == "") {
                            $dataThirdFilter["REGIMEN FISCAL RECEPTOR"] = trim($data2[$key]);
                            break;
                        }
                    }

                    if(!str_contains($d2, "TOTAL")) {
                        if($dataThirdFilter["NOMBRE EMISOR"] == "") {
                            $dataThirdFilter["NOMBRE EMISOR"] = trim($data2[$key]);
                            break;
                        }
                        else if($dataThirdFilter["NOMBRE RECEPTOR"] == "") {
                            $dataThirdFilter["NOMBRE RECEPTOR"] = trim($data2[$key]);
                            break;
                        }
                    }
                }

                if(str_contains($d2, "FOLIO") && $dataThirdFilter["FOLIO"] == "") {
                    $dataThirdFilter["FOLIO"] = trim($data2[$key + 1]);
                    break;
                }
                if(str_contains($d2, "FECHA DE EMISIÓN") && $dataThirdFilter["FECHA DE EMISIÓN"] == "") {
                    $dataThirdFilter["FECHA DE EMISIÓN"] = trim($data2[$key + 1]);
                    break;
                }
                if(str_contains($d2, "FECHA DE TIMBRADO") && $dataThirdFilter["FECHA DE TIMBRADO"] == "") {
                    $dataThirdFilter["FECHA DE TIMBRADO"] = trim($data2[$key + 1]);
                    break;
                }
                if(str_contains($d2, "TOTAL $") && preg_match("/^TOTAL/", trim($d2)) && $dataThirdFilter["TOTAL"] == "") {
                    $dataThirdFilter["TOTAL"] = trim(substr(trim($data2[$key]), 7));
                    break;
                }
                if(str_contains($d2, "NO. SERIE CSD DEL EMISOR") && $dataThirdFilter["NO. SERIE CSD DEL EMISOR"] == "") {
                    $dataThirdFilter["NO. SERIE CSD DEL EMISOR"] = trim($data2[$key + 1]);
                    break;
                }
                if(str_contains($d2, "RFC") && $dataThirdFilter["RFC EMISOR"] == "") {
                    $dataThirdFilter["RFC EMISOR"] = trim($data2[$key + 1]);
                    break;
                }
                if(str_contains($d2, "RFC") && $dataThirdFilter["RFC RECEPTOR"] == "") {
                    $dataThirdFilter["RFC RECEPTOR"] = trim($data2[$key + 1]);
                    break;
                }
                if(str_contains($d2, "UUID") && $dataThirdFilter["UUID"] == "") {
                    $dataThirdFilter["UUID"] = trim($data2[$key + 1]);
                    break;
                }
                if(str_contains($d2, "NO. SERIE CSD DEL SAT") && $dataThirdFilter["NO. SERIE CSD DEL SAT"] == "") {
                    $dataThirdFilter["NO. SERIE CSD DEL SAT"] = trim($data2[$key + 1]);
                    break;
                }
            }
        }

        $rfe = $dataThirdFilter["REGIMEN FISCAL EMISOR"];   //rfe = regimen fiscal emisor
        $rfr = $dataThirdFilter["REGIMEN FISCAL RECEPTOR"];   //rfr = regimen fiscal receptor

        //Almacenarán los números del regimen fiscal para cada caso
        $caracteresRFE = "";
        $caracteresRFR = "";

        //Extraer caracteres númericos del regimen fiscal del emisor
        for($i = 0; $i < strlen($rfe); $i++) {
            $extraerRFE = substr($rfe, $i, 1);
            if(preg_match("/[0-9]/", $extraerRFE)) {
                $caracteresRFE .= $extraerRFE;
            }
        }

        //Extraer caracteres númericos del regimen fiscal del receptor
        for($i = 0; $i < strlen($rfr); $i++) {
            $extraerRFR = substr($rfr, $i, 1);
            if(preg_match("/[0-9]/", $extraerRFR)) {
                $caracteresRFR .= $extraerRFR;
            }
        }

        $dataThirdFilter["REGIMEN FISCAL EMISOR"] = $caracteresRFE;
        $dataThirdFilter["REGIMEN FISCAL RECEPTOR"] = $caracteresRFR;

        // dd($dataThirdFilter);

        return $dataThirdFilter;
    }

    public function leerXML($id) {
        $invoice =  Invoice::Find($id);
        $data = $this->GetDataFromXML($invoice);
        // return view('jesus.leerXML')->with('data', $data);
    }

    private function GetDataFromXML($invoice){
        $xmlObject = simplexml_load_file(public_path('archivos/xml/565_PPA180626CC4.xml'));   //Convertir el archivo XML en un objeto XML de PHP
        $xmlNamespaces = $xmlObject->getNamespaces(true);   //Obtener los namespaces utilizados al inicio del documento XML
        $xmlObject->registerXPathNamespace('c', $xmlNamespaces['cfdi']);   //c hará referencia a todos los prefijos que empiecen con cfdi
        $xmlObject->registerXPathNamespace('t', $xmlNamespaces['tfd']);   //t hará referencia a todos los prefijos que empiecen con tdf

        //Convertir a JSON los resultados obtenidos
        $json = json_encode([
            "Comprobante" => $xmlObject->xpath('//c:Comprobante'),
            "Emisor" => $xmlObject->xpath('//c:Emisor'),
            "Receptor" => $xmlObject->xpath('//c:Receptor'),
            "TimbreFiscalDigital" => $xmlObject->xpath('//t:TimbreFiscalDigital')
        ]);

        $data = json_decode($json, true);   //Convertir de JSON a arreglo asociativo los resultados
        // dd($data);
        return $data;
    }

    // Descarga de factura
    public function download(Request $request, $id) {
        $option = $request->get('option');
        $invoice = Invoice::find($id);

        if($option == 'T') {
            $filename = 'factura_' . $invoice->uuid . '.zip';

            $filename_pdf = 'factura_' . $invoice->uuid . '.pdf';
            $filename_xml = 'factura_' . $invoice->uuid . '.xml';
            if($invoice->other != null) {
                preg_match('/\.[0-9a-z]+$/i', $invoice->other, $extension);   //Obtiene la extensión del archivo
                $filename_other = 'factura_' . $invoice->uuid . $extension[0];
            }

            $zip = new ZipArchive();
            //!Así debe ir en producción (situación con las rutas)
            // $zip->open($filename, ZipArchive::CREATE | ZipArchive::OVERWRITE);
            // $routePdf = 'laravel/public/' . $invoice->pdf;
            // $zip->addFile($routePdf, $filename_pdf);
            // $routeXml = 'laravel/public/' . $invoice->xml;
            // $zip->addFile($routeXml, $filename_xml);
            // $routeOther = 'laravel/public/' . $invoice->other;
            // $zip->addFile($routeOther, $filename_other);

            $zip->open($filename, ZipArchive::CREATE | ZipArchive::OVERWRITE);
            $zip->addFile($invoice->pdf, $filename_pdf);
            $zip->addFile($invoice->xml, $filename_xml);
            if($invoice->other != null) {
                $zip->addFile($invoice->other, $filename_other);
            }
            $zip->close();

            return Response::download($filename);
        }
        else if($option == 'PDF') {
            $filename = 'factura_' . $invoice->uuid . '.pdf';
            //!Así debe ir en producción (situación con las rutas
            // $route = 'laravel/public/' . $invoice->pdf;
            // return Response::download($route, $filename);
            return Response::download($invoice->pdf, $filename);
        }
        else if($option == 'XML') {
            $filename = 'factura_' . $invoice->uuid . '.xml';
            //!Así debe ir en producción (situación con las rutas
            // $route = 'laravel/public/' . $invoice->xml;
            // return Response::download($route, $filename);
            return Response::download($invoice->xml, $filename);
        }
        else if($option == 'A') {
            if($invoice->other != null) {
                preg_match('/\.[0-9a-z]+$/i', $invoice->other, $extension);   //Obtiene la extensión del archivo
                $filename = 'factura_' . $invoice->uuid . $extension[0];
                //!Así debe ir en producción (situación con las rutas
                // $route = 'laravel/public/' . $invoice->other;
                // return Response::download($route, $filename);
                return Response::download($invoice->other, $filename);
            }
            else {
                Alert::warning('Advertencia', 'No pudimos encontrar este archivo :(');
                return redirect()->route('invoices.index');
            }
        }
    }

    public function readPdf() {
        return view("app.invoices.readPdf");
    }

    public function readPdfTest(Request $request) {
        $pdf = $request->file('pdf_input');

        $pdf_procesado = Invoice::readPDF($pdf);
        dd($pdf_procesado);
    }

    public function modalPayment(Request $request) {
        $id = $request->get('id');
        $invoice = Invoice::with('payments')->where('id', $id)->first();
        return view('app.admin.invoices.ajax.modalPayment')->with('invoice', $invoice);
    }

    public function addPayment(Request $request) {
        $data = $request->all();

        $id = $data['prov_id'];
        $date = $data['date'];
        $amount = $data['payment'];
        $payment_method = $data['payment_method'];

        $receiptFile = $request->file('receipt');
        $name_file = time() . '.' . $receiptFile->extension();
        $receiptFile->move(public_path('archivos/pagos'), $name_file);
        $file_name = 'archivos/pagos/' . $name_file;

        $invoice = Invoice::with('provider')->find($id);
        $subtotal = PaymentHistory::where('invoice_id', $id)->sum('payment');

        if($subtotal + $amount > $invoice->total) {
            return 0;
        }
        else {
            $payment = new PaymentHistory();
            $payment -> user_id = $invoice->provider->user_id;
            $payment -> approved_by = Auth::id();
            $payment -> invoice_id = $id;
            $payment -> date = $date;
            $payment -> payment_method = $payment_method;
            $payment -> payment = $amount;
            $payment -> receipt = $file_name;
            $payment -> save();

            return 1;
        }
    }

    public function paymentsBulkUpload() {
        $owners = Owner::all();
        $providers = Provider::all();
        return view('app.admin.invoices.paymentsBulkUpload')
            ->with('owners', $owners)
            ->with('providers', $providers);
    }

    public function providersDatalist(Request $request) {
        $id = $request->get('owner');
        $invoices = Invoice::where([['owner_id', $id], ['status', 'A']])->get();
        $provider_ids = [];
        foreach($invoices as $invoice) {
            array_push($provider_ids, $invoice->provider_id);
        }
        $providers = Provider::whereIn('id', $provider_ids)->get();
        return view('app.admin.invoices.ajax.providersDatalist')->with('providers', $providers);
    }

    public function pendingPaymentsTable(Request $request) {
        $owner_id = $request->get('owner');
        $provider_id = $request->get('provider');
        if($owner_id == -1 || $provider_id == -1) {
            $invoices = Invoice::with('provider', 'payments')->where([['payment_status', 'Pendiente'], ['status', 'A']])->get();
            return view('app.admin.invoices.ajax.paymentsTable')->with('invoices', $invoices);
        }
        else {
            $invoices = Invoice::with('provider', 'payments')->where([['owner_id', $owner_id], ['provider_id', $provider_id], ['payment_status', 'Pendiente'], ['status', 'A']])->get();
            return view('app.admin.invoices.ajax.pendingPaymentsTable')->with('invoices', $invoices);
        }
    }

    public function addFilteredPayments(Request $request) {
        $data = $request->all();
        $pendingPayments = json_decode($data['pendingPayments']);
        $receipt = $request->file('filePayment');
        
        foreach($pendingPayments as $pendingPayment) {
            $invoice = Invoice::with('payments', 'provider')->find($pendingPayment->invoice_id);

            $payment = new PaymentHistory();
            $payment -> user_id = $invoice->provider->user_id;
            $payment -> invoice_id = $pendingPayment->invoice_id;
            $payment -> approved_by = Auth::id();
            $payment -> date = $data['date'];
            $payment -> payment_method = $pendingPayment->payment_method;
            $payment -> payment = $pendingPayment->payment;

            $name_file = time() . '.' . $receipt->extension();
            $receipt->move(public_path('archivos/pagos'), $name_file);
            $file_name = 'archivos/pagos/' . $name_file;

            $payment -> receipt = $file_name;
            $payment -> save();

            if($invoice->total == $invoice->payments->sum('payment')) {
                $invoice -> payment_status = 'Pagado';
                $invoice -> save();
            }
        }

        Alert::success('Éxito', 'Pagos guardados correctamente');
        return redirect()->route('invoices.paymentsBulkUpload');
    }

    public function resendEmail($id) {
        $invoice = Invoice::with('provider')->find($id);
        $name_other_file_aux = explode('/', $invoice->other);
        $name_other_file = end($name_other_file_aux);
        $email_files = new FilesReceived($invoice->xml, 'factura.xml', $invoice->pdf, 'factura.pdf', $invoice->other, $name_other_file, $invoice->provider->nombre, $name_other_file);
        Mail::to('chuyatlas2001@hotmail.com')->send($email_files);

        Alert::success('Éxito', 'Correo reenviado correctamente');
        return redirect()->back();
    }

    public function destroy($id) {
        $invoice = Invoice::find($id);
        $invoice->status = 'I';
        $invoice->save();

        Alert::success('Éxito', 'Factura eliminada correctamente');
        return redirect()->route('invoices.index');
    }
}
