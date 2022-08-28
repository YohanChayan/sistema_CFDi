<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Smalot\PdfParser\Parser;

class Invoice extends Model
{
    use HasFactory;
    protected $table = 'invoices';

    protected $fillable = [
        'uuid',
        'pdf',
        'xml',
    ];

    public function owner() {
        return $this->belongsTo(Owner::class, 'owner_id');
    }

    public function provider() {
        return $this->belongsTo(Provider::class, 'provider_id');
    }

    public function payments() {
        return $this->hasMany(PaymentHistory::class, 'invoice_id');
    }

    public static function readXML($file) {
        libxml_use_internal_errors(true);   //Previene de posibles errores al intentar leer el archivo XML
        $xmlObject = simplexml_load_file($file);   //Convertir el archivo XML en un objeto XML de PHP
        $xmlNamespaces = $xmlObject->getNamespaces(true);   //Obtener los namespaces utilizados al inicio del documento XML
        $xmlObject->registerXPathNamespace('c', $xmlNamespaces['cfdi']);   //c hará referencia a todos los prefijos que empiecen con cfdi
        $xmlObject->registerXPathNamespace('t', $xmlNamespaces['tfd']);   //t hará referencia a todos los prefijos que empiecen con tdf

        //Convertir a JSON los resultados obtenidos
        $json = json_encode([
            "Comprobante" => $xmlObject->xpath('//c:Comprobante'),
            "Emisor" => $xmlObject->xpath('//c:Emisor'),
            "Receptor" => $xmlObject->xpath('//c:Receptor'),
            "Productos" => $xmlObject->xpath('//c:Concepto'),
            "TimbreFiscalDigital" => $xmlObject->xpath('//t:TimbreFiscalDigital'),
        ]);

        $data = json_decode($json, true);   //Convertir de JSON a arreglo asociativo los resultados

        return $data;   //Retorna los datos como un arreglo
    }

    public static function readPDF($file) {
        $parser = new Parser();   //Crea una instancia de la clase Parser
        
        //Se utiliza un try-catch para evaluar el posible caso de que no pueda leer el pdf por alguna razón
        try {
            $pdf = $parser->parseFile($file);   //Obtiene el PDF y lo guarda en un objeto de la clase Parser
            $text = $pdf->getText();   //Convierte el PDF a texto
            if($text != "")
                $data = explode("\n", $text);   //Separa el PDF en un arreglo, utilizando como delimitador el salto de línea
            else
                $data = -1;
        } catch (Exception $e) {
            $data = -1;
        }

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

    public static function getUUIDXML($xml) {
        return $xml['TimbreFiscalDigital'][0]['@attributes']['UUID'];   //Obtiene el UUID del archivo xml
    }

    public static function getProviderRFCXML($xml) {
        return $xml['Emisor'][0]['@attributes']['Rfc'];   //Obtiene el RFC del emisor (proveedor) del archivo xml
    }

    public static function getOwnerRFCXML($xml) {
        return $xml['Receptor'][0]['@attributes']['Rfc'];   //Obtiene el RFC del receptor (propietario) del archivo xml
    }

    public static function getNameProviderXML($xml) {
        return $xml['Emisor'][0]['@attributes']['Nombre'];   //Obtiene el Nombre del emisor (proveedor) del archivo xml
    }

    public static function getNameOwnerXML($xml) {
        return $xml['Receptor'][0]['@attributes']['Nombre'];   //Obtiene el Nombre del emisor (propietario) del archivo xml
    }

    public static function getTotalXML($xml) {
        return $xml['Comprobante'][0]['@attributes']['Total'];   //Obtiene el Total de la factura del archivo xml
    }

    public static function getFolio($xml) {
        try {
            return $xml['Comprobante'][0]['@attributes']['Folio'];   //Obtiene el Folio de la factura del archivo xml
        } catch(Exception $e) {
            return null;
        }
    }

    public static function getProductsXML($xml) {
        $array_products = [];
        foreach($xml['Productos'] as $product) {
            array_push($array_products, [
                'name' => $product['@attributes']['Descripcion'],
                'quantity' => $product['@attributes']['Cantidad'],
                'price' => $product['@attributes']['ValorUnitario'],
                'total' => $product['@attributes']['Importe'],
                'sat_product' => intval($product['@attributes']['ClaveProdServ']),
                'sat_measurement_unit' => strtoupper($product['@attributes']['ClaveUnidad']),
            ]);
        }
        return $array_products;   //Obtiene los Productos de la factura del archivo xml
    }
}
