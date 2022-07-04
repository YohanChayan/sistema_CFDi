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
        $array = explode("\n", $text);   //Separa el PDF en un arreglo, utilizando como delimitador el salto de línea

        foreach($array as $arr) {
            //if(str_contains($arr, "FOLIO"))
                //dd($arr);
            //if(str_contains($arr, "FECHA DE EMISIÓN"))
                //dd($arr);
            //if(str_contains($arr, "FECHA DE TIMBRADO"))
                //dd($arr);
            //if(str_contains($arr, "TOTAL"))
                //dd($arr);
            //if(str_contains($arr, "NO. SERIE CSD DEL EMISOR"))
                //dd($arr);
            //if(str_contains($arr, "RFC"))
                //dd($arr);
            //if(str_contains($arr, "UUID"))
                //dd($arr);
            //if(str_contains($arr, "NO. SERIE CSD DEL SAT"))
                //dd($arr);
        }

        dd($array);
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
            "TimbreFiscalDigital" => $xmlObject->xpath('//t:TimbreFiscalDigital')
        ]);
        
        $data = json_decode($json, true);   //Convertir de JSON a arreglo asociativo los resultados
        //dd($data);

        return view('jesus.leerXML', compact('data'));
    }
}
