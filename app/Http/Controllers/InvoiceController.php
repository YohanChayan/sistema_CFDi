<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Invoice;
use App\Models\Owner;
use App\Models\Provider;
use App\Models\User;
use App\Models\PaymentHistory;
use Illuminate\Support\Facades\DB;

use Smalot\PdfParser\Parser;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Mail;
use App\Mail\FilesReceived;
use Illuminate\Support\Facades\Auth;

use Response;
use ZipArchive;

class InvoiceController extends Controller
{
    public function index()
    {
        $owners = Owner::all();
        $invoices = Invoice::with('owner', 'provider')->get();
        return view('app.invoices.index')->with('invoices', $invoices)->with('owners', $owners);
    }

    public function invoicesTable(Request $request) {
        $owner = $request->get('owner');
        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date');

        $invoices = Invoice::whereBetween('created_at', [date('Y-m-d 00:00:00', strtotime($start_date)), date('Y-m-d 23:59:59', strtotime($end_date))])->where('owner_id', $owner)->get();

        return view('app.invoices.ajax.invoicesTable')->with('invoices', $invoices);
    }

    public function leerPDF($id) {
        $invoice =  Invoice::Find($id);

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

    public function create()
    {
        return view('app.invoices.create');
    }

    // Descarga de factura
    public function download(Request $request, $id) {
        $option = $request->get('option');
        $invoice = Invoice::find($id);

        if($option == 'T') {
            $filename = 'factura_' . $invoice->uuid . '.zip';

            $filename_pdf = 'factura_' . $invoice->uuid . '.pdf';
            $filename_xml = 'factura_' . $invoice->uuid . '.xml';
            preg_match('/\.[0-9a-z]+$/i', $invoice->other, $extension);   //Obtiene la extensión del archivo
            $filename_other = 'factura_' . $invoice->uuid . $extension[0];

            $zip = new ZipArchive();
            $zip->open($filename, ZipArchive::CREATE | ZipArchive::OVERWRITE);
            $zip->addFile($invoice->pdf, $filename_pdf);
            $zip->addFile($invoice->xml, $filename_xml);
            $zip->addFile($invoice->other, $filename_other);
            $zip->close();

            return Response::download($filename);
        }
        else if($option == 'PDF') {
            $filename = 'factura_' . $invoice->uuid . '.pdf';
            return Response::download($invoice->pdf, $filename);
        }
        else if($option == 'XML') {
            $filename = 'factura_' . $invoice->uuid . '.xml';
            return Response::download($invoice->xml, $filename);
        }
        else if($option == 'A') {
            preg_match('/\.[0-9a-z]+$/i', $invoice->other, $extension);   //Obtiene la extensión del archivo
            $filename = 'factura_' . $invoice->uuid . $extension[0];
            return Response::download($invoice->other, $filename);
        }
    }

    public function store(Request $request)
    {
        $file_pdf = $request->file('pdf_input');       // Obtiene el archivo pdf
        $file_xml = $request->file('xml_input');       // Obtiene el archivo xml
        $file_other = $request->file('other_input');   // Obtiene el archivo anexo

        $convertedPDF = Invoice::readPDF($file_pdf);   // Lee el archivo pdf
        $convertedXML = Invoice::readXML($file_xml);   // Lee el archivo xml
        if($convertedPDF != -1)                        //En caso de que el pdf no se pueda leer
            $filesCompared = Invoice::compareFiles($convertedPDF, $convertedXML);   // Compara los archivos pdf y xml, devolviendo un valor booleano
        else
            $filesCompared = true;   //La comparación la forzamos a que sea verdadera

        if($filesCompared) {   // Archivos iguales

            /**************************************************/
            /*         Obtener datos del archivo xml          */
            /**************************************************/

            $uuid = Invoice::getUUIDXML($convertedXML);                  // Obtiene el UUID del archivo xml
            $provider_rfc = Invoice::getProviderRFCXML($convertedXML);   // Obtiene el RFC del emisor
            $owner_rfc = Invoice::getOwnerRFCXML($convertedXML);         // Obtiene el RFC del receptor
            $total = Invoice::getTotalXML($convertedXML);                // Obtiene el Total de la factura
            $folio = Invoice::getFolio($convertedXML);

            /**************************************************/
            /*    Validar que exista el receptor en la BD     */
            /**************************************************/

            $search_owner = Owner::where('rfc', $owner_rfc)->first();

            if($search_owner == null){
                Alert::error('Error', 'El RFC del receptor no coincide con ninguna empresa');
                return redirect()->back();
            }

            /**************************************************/
            /*   Validar que no exista la factura en la BD    */
            /**************************************************/

            $invoice_uuid = Invoice::where('uuid', $uuid)->first();

            if ($invoice_uuid != null) {
                Alert::error('Error', 'El UUID ya se encuentra registrado');
                return redirect()->back();
            }

            /**************************************************/
            /*             Registro de la factura             */
            /**************************************************/

            $search_provider = Provider::where('rfc', $provider_rfc)->first();   // Busca el RFC del emisor en la base de datos
            $xml_name = '';
            $name_xml_file = '';
            $pdf_name = '';
            $name_pdf_file = '';
            $other_name = '';
            $name_other_file = '';
            $name_provider = '';
            $other_file_aux = '';
            $name_provider = Invoice::getNameProviderXML($convertedXML);

            // Mover archivos a la carpeta pública
            $name_pdf_file = time() . '.pdf';
            $file_pdf->move(public_path("archivos/pdf"), $name_pdf_file);
            $pdf_name = "archivos/pdf/" . $name_pdf_file;

            $name_xml_file = time() . '.xml';
            $file_xml->move(public_path("archivos/xml"), $name_xml_file);
            $xml_name = "archivos/xml/" . $name_xml_file;

            $name_other_file = '';
            if ($file_other != null) {
                $other_file_aux = $file_other->getClientOriginalName();

                $name_other_file = time() . '.' . pathinfo($other_file_aux, PATHINFO_EXTENSION);
                $file_other->move(public_path("archivos/anexo"), $name_other_file);
                $other_name = "archivos/anexo/" . $name_other_file;
            }

            // Crear la factura en la base de datos
            $new_invoice = new Invoice();
            $new_invoice->provider_id = $search_provider->id;
            $new_invoice->owner_id = $search_owner->id;
            $new_invoice->uuid = $uuid;
            $new_invoice->folio = $folio;
            $new_invoice->total = $total;
            $new_invoice->pdf = $pdf_name;
            $new_invoice->xml = $xml_name;
            $new_invoice->other = ($name_other_file == '') ? null : $other_name;
            $new_invoice->save();

            // Enviar correo electrónico
            $pdf_original_name = $file_pdf->getClientOriginalName();
            $xml_original_name = $file_xml->getClientOriginalName();
            $archivos_email = new FilesReceived($xml_name, $xml_original_name, $pdf_name, $pdf_original_name, $other_name, $name_other_file, $name_provider, $other_file_aux);
            Mail::to('proveedoresfrutioro@hotmail.com')->send($archivos_email);

            Alert::success('Éxito', 'Factura guardada correctamente');
            return redirect()->back();
        }
        else {
            Alert::error('Error', 'Los archivos NO contienen el mismo UUID');
            return redirect()->back();
        }
    }

    public function validateProvider(Request $request) {
        $rfc = $request->get('rfc');   //Obtiene el rfc que se le pasa por el método get desde ajax
        $search_provider = Provider::where('rfc', $rfc)->first();   // Busca el RFC del emisor en la base de datos
        if($search_provider == null)
            return 0;
        else
            return 1;
    }

    public function createNewProvider(Request $request) {
        //Datos obtenidos de ajax
        $data = $request->all();

        //Creación de un nuevo proveedor
        $user = new User();
        $user -> name = $data['nombre'];
        $user -> rfc = $data['rfc'];
        $user -> password = bcrypt($data['password']);
        $user -> save();

        $provider = new Provider();
        $provider -> user_id = $user->id;
        $provider -> rfc = $data['rfc'];
        $provider -> nombre = $data['nombre'];
        $provider -> password = bcrypt($data['password']);
        $provider -> save();

        return 1;
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
        return view('app.invoices.ajax.modalPayment')->with('invoice', $invoice);
    }

    public function addPayment(Request $request) {
        $id = $request->get('id');
        $date = $request->get('date');
        $amount = $request->get('amount');

        $invoice = Invoice::find($id);
        $subtotal = PaymentHistory::where('invoice_id', $id)->sum('payment');

        if($subtotal + $amount > $invoice->total) {
            return 0;
        }
        else {
            $payment = new PaymentHistory();
            $payment -> user_id = Auth::id();
            $payment -> invoice_id = $id;
            $payment -> date = $date;
            $payment -> payment = $amount;
            $payment -> save();

            return 1;
        }
    }

    public function paymentsBulkUpload() {
        $owners = Owner::all();
        $providers = Provider::all();
        return view('app.invoices.paymentsBulkUpload')
            ->with('owners', $owners)
            ->with('providers', $providers);
    }

    public function providersDatalist(Request $request) {
        $id = $request->get('owner');
        $invoices = Invoice::where('owner_id', $id)->get();
        $provider_ids = [];
        foreach($invoices as $invoice) {
            array_push($provider_ids, $invoice->provider_id);
        }
        $providers = Provider::whereIn('id', $provider_ids)->get();
        return view('app.invoices.ajax.providersDatalist')->with('providers', $providers);
    }

    public function pendingPaymentsTable(Request $request) {
        $owner_id = $request->get('owner');
        $provider_id = $request->get('provider');
        if($owner_id == -1 || $provider_id == -1) {
            $invoices = Invoice::with("provider", "payments")->where('status', 'Pendiente')->get();
            return view('app.invoices.ajax.paymentsTable')->with('invoices', $invoices);
        }
        else {
            $invoices = Invoice::where([['owner_id', $owner_id], ['provider_id', $provider_id], ['status', 'Pendiente']])->get();
            return view('app.invoices.ajax.pendingPaymentsTable')->with('invoices', $invoices);
        }
    }

    public function addFilteredPayments(Request $request) {
        $pendingPayments = json_decode($request->post('pendingPayments'));

        foreach($pendingPayments as $pendingPayment) {
            $payment = new PaymentHistory();
            $payment -> user_id = Auth::id();
            $payment -> invoice_id = $pendingPayment->invoice_id;
            $payment -> date = $pendingPayment->date;
            $payment -> payment = $pendingPayment->payment;
            $payment -> save();

            $invoice = Invoice::with('payments')->where('id', $payment->invoice_id)->first();
            if($invoice->total == $invoice->payments->sum('payment')) {
                $invoice -> status = 'Pagado';
                $invoice -> save();
            }
        }

        Alert::success('Éxito', 'Pagos guardados correctamente');
        return redirect()->route('invoices.paymentsBulkUpload');
    }

    public function myInvoices() {
        return view('app.providers.invoices.index');
    }

    public function myInvoicesTable(Request $request) {
        $filter = $request->get('filter');

        if($filter == 'TO')
            $invoices = Invoice::where('provider_id', auth()->user()->provider->id)->get();
        else if($filter == 'PE')
            $invoices = Invoice::where([['provider_id', auth()->user()->provider->id], ['status', 'Pendiente']])->get();
        else if($filter == 'PA')
            $invoices = Invoice::where([['provider_id', auth()->user()->provider->id], ['status', 'Pagado']])->get();

        return view('app.providers.invoices.ajax.myInvoicesTable')->with('invoices', $invoices);
    }
}
