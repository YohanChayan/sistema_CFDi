<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SimpleXMLElement;
use Smalot\PdfParser\Parser;

class JesusController extends Controller
{
    public function index() {
        return view('index');
    }

    public function leerPDF() {
        $parser = new Parser();   //Crea una instancia de la clase Parser
        $pdf = $parser->parseFile(public_path('archivos/565_PPA180626CC4.pdf'));   //Obtiene el PDF y lo guarda en un objeto de la clase Parser
        $text = $pdf->getText();   //Convierte el PDF a texto
        //dd($text);
        $array = explode("\n", $text);   //Separa el PDF en un arreglo, utilizando como delimitador el salto de línea
        $dataFirstFilter = [];   //Arreglo que contendrá la información seleccionada del PDF después de aplicar el primer filtro (patron = "\n")

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

        return view('jesus.leerPDF', ["data" => $dataThirdFilter]);
    }

    public function leerXML() {
        $xmlObject = simplexml_load_file(public_path('archivos/565_PPA180626CC4.xml'));   //Convertir el archivo XML en un objeto XML de PHP
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

        return view('jesus.leerXML', compact('data'));
    }
}
