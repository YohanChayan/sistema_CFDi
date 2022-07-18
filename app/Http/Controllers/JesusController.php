<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Jesus;
use App\Models\Owner;
use App\Models\Provider;
use Illuminate\Http\Request;
use Smalot\PdfParser\Parser;
use RealRashid\SweetAlert\Facades\Alert;

class JesusController extends Controller
{
    public function index() {
        return view('index');
    }

    public function leerPDF() {
        $data = $this->GetDataFromPDF();
        // dd($data);
        return view('jesus.leerPDF', ["data" => $data]);
    }

    private function GetDataFromPDF(){
        $parser = new Parser();   //Crea una instancia de la clase Parser
        // $pdf = $parser->parseFile(public_path('archivos/pdf/565_PPA180626CC4.pdf'));   //Obtiene el PDF y lo guarda en un objeto de la clase Parser
        $pdf = $parser->parseFile(public_path('archivos/8074.pdf'));   //Obtiene el PDF y lo guarda en un objeto de la clase Parser
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

    public function leerXML() {
        $data = $this->GetDataFromXML();
        return view('jesus.leerXML')->with('data', $data);
    }

    private function GetDataFromXML(){
        $xmlObject = simplexml_load_file(public_path('archivos/8074.xml'));   //Convertir el archivo XML en un objeto XML de PHP
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

    public function subirArchivos() {
        return view('jesus.subirArchivos');
    }

    public function enviarArchivos(Request $request) {
        $pdf_file = $request->file('pdf_file');
        $xml_file = $request->file('xml_file');
        $other_file = $request->file('other_file');

        if($pdf_file == null || $xml_file == null) {
            Alert::warning('Advertencia', 'Es necesario que subas al menos un archivo pdf y un archivo xml');
            return redirect()->back();
        }
        else {
            $pdf_file_extension = strtolower($pdf_file->extension());   //Obtiene la extensión del archivo pdf
            $xml_file_extension = strtolower($xml_file->extension());   //Obtiene la extensión del archivo xml
            $other_file_extension = ($other_file != null) ? strtolower($other_file->extension()) : "";   //Obtiene la extensión del archivo anexo

            //Evaluación de extensiones de archivos
            if($pdf_file_extension == "pdf" && $xml_file_extension == "xml") {
                $pdf = Jesus::readPDF($pdf_file);
                $xml = Jesus::readXML($xml_file);
                $result = Jesus::compareFiles($pdf, $xml);

                //Validar si el UUID es el mismo entre el archivo pdf y xml
                if($result) {
                    $uuid = Jesus::getUUIDXML($xml);   //Obtiene el UUID del archivo xml
                    $provider_rfc = Jesus::getProviderRFCXML($xml);   //Obtiene el RFC del emisor (proveedor)
                    $owner_rfc = Jesus::getOwnerRFCXML($xml);   //Obtiene el RFC del receptor (propietario)

                    $search_uuid = Invoice::where('uuid', $uuid)->first();

                    //Evaluar si el uuid ya se había registrado anteriormente
                    if($search_uuid == null) {
                        $search_owner = Owner::where('rfc', $owner_rfc)->first();   //Busca el RFC del receptor (propietario) en la base de datos
                        $search_provider = Provider::where('rfc', $provider_rfc)->first();   //Busca el RFC del emisor (proveedor) en la base de datos

                        //Evaluar si el proveedor ya existe en la base de datos
                        if($search_provider != null) {
                            //Guardar los archivos en la carpeta public/archivos
                            $name_pdf_file = time() . '.' . $pdf_file_extension;
                            $pdf_file->move(public_path("archivos/pdf"), $name_pdf_file);
                            $pdf_name = "archivos/pdf/" . $name_pdf_file;

                            $name_xml_file = time() . '.' . $xml_file_extension;
                            $xml_file->move(public_path("archivos/xml"), $name_xml_file);
                            $xml_name = "archivos/pdf/" . $name_xml_file;

                            if($other_file != null) {
                                $name_other_file = time() . '.' . $other_file_extension;
                                $other_file->move(public_path("archivos/anexo"), $name_other_file);
                                $other_name = "archivos/pdf/" . $name_other_file;
                            }
                            else
                                $other_name = null;

                            //Guardar la factura en la base de datos
                            $invoice = new Invoice();
                            $invoice -> owner_id = $search_owner->id;
                            $invoice -> provider_id = $search_provider->id;
                            $invoice -> uuid = $uuid;
                            $invoice -> pdf = $pdf_name;
                            $invoice -> xml = $xml_name;
                            $invoice -> other = $other_name;
                            $invoice -> save();

                            Alert::success('Éxito', 'Factura guardada correctamente');
                            return redirect()->back();
                        }
                        else {
                            $provider = new Provider();
                            $provider -> rfc = $provider_rfc;
                            $provider -> nombre = Jesus::getNameOwnerXML($xml);
                            $provider -> save();

                            Alert::success('Éxito', 'Factura guardada correctamente');
                            return redirect()->back();
                        }
                    }
                    else {
                        Alert::error('Error', 'Está factura ya se había registrado anteriormente');
                        return redirect()->back();
                    }
                }
                else {
                    Alert::error('Error', 'Los archivos NO contienen el mismo UUID');
                    return redirect()->back();
                }
            }
            else {
                Alert::warning('Advertencia', 'Los archivos no tienen el formato solicitado');
                return redirect()->back();
            }
        }
    }
}
