<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Smalot\PdfParser\Parser;

class Jesus extends Model
{
    use HasFactory;

    public static function readXML($file) {
        $xmlObject = simplexml_load_file($file);   //Convertir el archivo XML en un objeto XML de PHP
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

        return $data;   //Retorna los datos como un arreglo
    }

    public static function readPDF($file) {
        $parser = new Parser();   //Crea una instancia de la clase Parser
        $pdf = $parser->parseFile($file);   //Obtiene el PDF y lo guarda en un objeto de la clase Parser
        $text = $pdf->getText();   //Convierte el PDF a texto
        $data = explode("\n", $text);   //Separa el PDF en un arreglo, utilizando como delimitador el salto de línea

        return $data;   //Retorna los datos como un arreglo
    }

    public static function compareFiles($pdf, $xml) {
        $uuid = $xml["TimbreFiscalDigital"][0]["@attributes"]["UUID"];   //Obtiene el UUID del archivo xml
        $flag = false;

        //Busca el uuid dentro del pdf (línea por línea)
        foreach($pdf as $line) {
            if(str_contains($line, $uuid)) {
                $flag = true;
                break;
            }
        }

        //Busca nuevamente, pero ahora tratando de unir las palabras en caso de que haya espacios entre sí
        if(!$flag) {
            $caracter = "";
            $sentence = "";
            foreach($pdf as $rare_phrase) {
                for($i = 0; $i < strlen($rare_phrase); $i++) {
                    $caracter = substr($rare_phrase, $i, 1);
                    if($caracter != " ")
                        $sentence .= $caracter;
                }

                if(str_contains($sentence, $uuid)) {
                    $flag = true;
                    break;
                }
                else {
                    $sentence = "";
                }
            }
        }

        return $flag;   //Retorna verdadero o falso dependiendo si los archivos contienen el mismo UUID
    }
}
