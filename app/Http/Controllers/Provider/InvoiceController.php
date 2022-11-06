<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Invoice;
use App\Models\Owner;
use App\Models\Provider;
use App\Models\User;

use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Mail;
use App\Mail\FilesReceived;
use App\Models\InvoiceDetail;
use App\Models\SatMeasurementUnit;
use App\Models\SatProduct;
use App\SAT\ValidateCFDI;

class InvoiceController extends Controller
{
    public function create()
    {
        return view('app.invoices.create');
    }

    public function store(Request $request)
    {
        $file_pdf = $request->file('pdf_input');       // Obtiene el archivo pdf
        $file_xml = $request->file('xml_input');       // Obtiene el archivo xml
        $file_other = $request->file('other_input');   // Obtiene el archivo anexo

        $convertedPDF = Invoice::readPDF($file_pdf);   // Lee el archivo pdf
        $convertedXML = Invoice::readXML($file_xml);   // Lee el archivo xml

        if($convertedXML !== -1) {   // Evaluar si el XML tiene un formato válido
            if($convertedPDF != -1)                        // En caso de que el pdf no se pueda leer
                $filesCompared = Invoice::compareFiles($convertedPDF, $convertedXML);   // Compara los archivos pdf y xml, devolviendo un valor booleano
            else
                $filesCompared = true;   // La comparación la forzamos a que sea verdadera

            if($filesCompared) {   // Archivos iguales

                /**************************************************/
                /*         Obtener datos del archivo XML          */
                /**************************************************/

                $uuid = Invoice::getUUIDXML($convertedXML);                  // Obtiene el UUID
                $provider_rfc = Invoice::getProviderRFCXML($convertedXML);   // Obtiene el RFC del emisor
                $owner_rfc = Invoice::getOwnerRFCXML($convertedXML);         // Obtiene el RFC del receptor
                $total = Invoice::getTotalXML($convertedXML);                // Obtiene el Total
                $folio = Invoice::getFolio($convertedXML);                   // Obtiene el Folio
                $zip_code = Invoice::getZipCode($convertedXML);              // Obtiene el Código Postal
                $products = Invoice::getProductsXML($convertedXML);          // Obtiene los Productos Registrados

                // Validar que no existan valores nulos al obtener los datos del archivo XML
                if($uuid != null && $provider_rfc != null && $owner_rfc && $total != null && $zip_code != null && $products != null) {

                    /**************************************************/
                    /*           Valida el CFDI ante el SAT           */
                    /**************************************************/

                    $validate_cfdi = new ValidateCFDI($provider_rfc, $owner_rfc, $total, $uuid);
                    $sat_response = $validate_cfdi->validate();
                    $status_code = substr(trim($sat_response['CodigoEstatus']), 0, 1);   // Devuelve S si encontró la factura y N si no la encontró
                    $cfdi_status = trim($sat_response['Estado']);   // Devuelve el estado de la factura, ya sea que esté vigente o cancelada
                    
                    if($status_code != 'S') {
                        Alert::warning('Advertencia', 'No pudimos encontrar esta factura en el sistema del SAT');
                        return redirect()->back();
                    }
                    else if($cfdi_status != 'Vigente') {
                        Alert::warning('Advertencia', 'Esta factura está cancelada ante el SAT');
                        return redirect()->back();
                    }

                    /**************************************************/
                    /*    Validar que exista el receptor en la BD     */
                    /**************************************************/

                    $search_owner = Owner::where([['rfc', $owner_rfc], ['status', 'A']])->first();

                    if($search_owner == null){
                        Alert::error('Error', 'El RFC del receptor no coincide con ninguna empresa');
                        return redirect()->back();
                    }

                    /**************************************************/
                    /*   Validar que no exista la factura en la BD    */
                    /**************************************************/

                    $invoice_uuid = Invoice::where('uuid', $uuid)->first();
                    $invoice_flag = false;   // Se utiliza para saber si se quiere subir de nuevo una factura que fue eliminada anteriormente

                    if($invoice_uuid != null) {
                        if($invoice_uuid->status == 'A') {
                            Alert::error('Error', 'El UUID ya se encuentra registrado');
                            return redirect()->back();
                        }
                        else {
                            $invoice_flag = true;
                        }
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
                    $new_invoice = (!$invoice_flag) ? new Invoice() : $invoice_uuid;   // Crea o edita la factura, dependiendo el caso
                    $new_invoice->provider_id = $search_provider->id;
                    $new_invoice->owner_id = $search_owner->id;
                    $new_invoice->uuid = $uuid;
                    $new_invoice->folio = $folio;
                    $new_invoice->zip_code = $zip_code;
                    $new_invoice->total = $total;
                    $new_invoice->pdf = $pdf_name;
                    $new_invoice->xml = $xml_name;
                    $new_invoice->other = ($name_other_file == '') ? null : $other_name;
                    $new_invoice->status = 'A';
                    $new_invoice->save();

                    if(count($products) > 0) {
                        foreach($products as $product) {
                            $sat_product = SatProduct::where('code', $product['sat_product'])->first();
                            $sat_measurement_unit = SatMeasurementUnit::where('code', $product['sat_measurement_unit'])->first();

                            $new_invoice_detail = new InvoiceDetail();
                            $new_invoice_detail -> invoice_id = $new_invoice -> id;
                            $new_invoice_detail -> sat_product_id = ($sat_product != null) ? $sat_product -> id : null;
                            $new_invoice_detail -> sat_measurement_unit_id = ($sat_measurement_unit != null) ? $sat_measurement_unit -> id : null;
                            $new_invoice_detail -> name = $product['name'];
                            $new_invoice_detail -> quantity = $product['quantity'];
                            $new_invoice_detail -> price = $product['price'];
                            $new_invoice_detail -> total = $product['total'];
                            $new_invoice_detail -> save();
                        }
                    }

                    // Enviar correo electrónico
                    $pdf_original_name = $file_pdf->getClientOriginalName();
                    $xml_original_name = $file_xml->getClientOriginalName();
                    // $archivos_email = new FilesReceived($xml_name, $xml_original_name, $pdf_name, $pdf_original_name, $other_name, $name_other_file, $name_provider, $other_file_aux);
                    // Mail::to('proveedoresfrutioro@hotmail.com')->send($archivos_email);
                    // Mail::to('chuyatlas2001@hotmail.com')->send($archivos_email);

                    Alert::success('Éxito', 'Factura guardada correctamente');
                    return redirect()->route('invoices.create');
                }
                else {
                    Alert::error('Error', 'Los archivos no siguen una estructura válida. Por favor intente de nuevo.');
                    return redirect()->route('invoices.create');
                }
            }
            else {
                Alert::error('Error', 'Los archivos NO contienen el mismo UUID');
                return redirect()->route('invoices.create');
            }
        }
        else {
            Alert::error('Error', 'Los archivos no siguen una estructura válida. Por favor intente de nuevo.');
            return redirect()->route('invoices.create');
        }
    }

    public function validateProvider(Request $request) {
        $rfc = $request->get('rfc');   // Obtiene el rfc que se le pasa por el método get desde ajax
        $search_provider = Provider::where('rfc', $rfc)->first();   // Busca el RFC del emisor en la base de datos
        if($search_provider == null)
            return $rfc;   //El proveedor aún no se ha dado de alta
        else
            return 1;   //EL proveedor ya existe
    }

    public function createNewProvider(Request $request) {
        // Datos obtenidos de ajax
        $data = $request->all();

        // Creación de un nuevo proveedor
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

    public function myInvoices() {
        return view('app.providers.invoices.index');
    }

    public function myInvoicesTable(Request $request) {
        $filter = $request->get('filter');

        if($filter == 'TO')
            $invoices = Invoice::with('payments')->where([['provider_id', auth()->user()->provider->id], ['status', 'A']])->get();
        else if($filter == 'PE')
            $invoices = Invoice::with('payments')->where([['provider_id', auth()->user()->provider->id], ['payment_status', 'Pendiente'], ['status', 'A']])->get();
        else if($filter == 'PA')
            $invoices = Invoice::with('payments')->where([['provider_id', auth()->user()->provider->id], ['payment_status', 'Pagado'], ['status', 'A']])->get();

        return view('app.providers.invoices.ajax.myInvoicesTable')->with('invoices', $invoices);
    }

    public function modalDetails(Request $request) {
        $id = $request->get('id');
        $invoice = Invoice::with('details')->find($id);
        return view('app.providers.invoices.ajax.modalDetails')->with('invoice', $invoice);
    }
}
