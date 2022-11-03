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

    public function details() {
        return $this->hasMany(InvoiceDetail::class, 'invoice_id');
    }

    /** 
     * Lee el XML subido por el usuario.
     * @param mixed $file Archivo XML.
     * @return array Regresa un arreglo asociativo con los datos leídos del XML.
     * @return int Bandera que indicará que el XML no tiene formato válido
    */
    public static function readXML($file) {
        libxml_use_internal_errors(true);   // Previene de posibles errores al intentar leer el archivo XML
        $xmlObject = simplexml_load_file($file);   // Convertir el archivo XML en un objeto XML de PHP
        $xmlNamespaces = $xmlObject->getNamespaces(true);   // Obtener los namespaces utilizados al inicio del documento XML
        
        // Evaluar si el XML incluye la parte de cfdi y tfd
        if(isset($xmlNamespaces['cfdi']) && isset($xmlNamespaces['tfd'])) {
            $xmlObject->registerXPathNamespace('c', $xmlNamespaces['cfdi']);   // c hará referencia a todos los prefijos que empiecen con cfdi
            $xmlObject->registerXPathNamespace('t', $xmlNamespaces['tfd']);   // t hará referencia a todos los prefijos que empiecen con tdf

            // Convertir a JSON los resultados obtenidos
            $json = json_encode([
                "Comprobante" => $xmlObject->xpath('//c:Comprobante'),
                "Emisor" => $xmlObject->xpath('//c:Emisor'),
                "Receptor" => $xmlObject->xpath('//c:Receptor'),
                "Productos" => $xmlObject->xpath('//c:Concepto'),
                "TimbreFiscalDigital" => $xmlObject->xpath('//t:TimbreFiscalDigital'),
            ]);

            $data = json_decode($json, true);   // Convertir de JSON a arreglo asociativo los resultados

            return $data;   // Retorna los datos como un arreglo
        }
        else {
            return -1;   // Bandera que indicará que el XML no tiene formato válido
        }
    }

    /** 
     * Lee el PDF subido por el usuario.
     * @param mixed $file Archivo PDF.
     * @return array Regresa un arreglo con los datos leídos del PDF.
    */
    public static function readPDF($file) {
        $parser = new Parser();   // Crea una instancia de la clase Parser
        
        // Se utiliza un try-catch para evaluar el posible caso de que no pueda leer el pdf por alguna razón
        try {
            $pdf = $parser->parseFile($file);   // Obtiene el PDF y lo guarda en un objeto de la clase Parser
            $text = $pdf->getText();   // Convierte el PDF a texto
            if($text != "")
                $data = explode("\n", $text);   // Separa el PDF en un arreglo, utilizando como delimitador el salto de línea
            else
                $data = -1;
        } catch (Exception $e) {
            $data = -1;
        }

        return $data;   // Retorna los datos como un arreglo
    }

    /** 
     * Se encarga de comparar el archivo PDF y XML con la finalidad de que el UUID sea el mismo.
     * @param mixed $pdf Archivo PDF.
     * @param mixed $xml Archivo XML.
     * @return boolean Indica si los archivos son iguales.
    */
    public static function compareFiles($pdf, $xml) {
        $uuid = $xml["TimbreFiscalDigital"][0]["@attributes"]["UUID"];   // Obtiene el UUID del archivo xml
        $flag = false;

        // Busca el uuid dentro del pdf (línea por línea)
        foreach($pdf as $line) {
            if(str_contains($line, $uuid)) {
                $flag = true;
                break;
            }
        }

        // Busca nuevamente, pero ahora tratando de unir las palabras en caso de que haya espacios entre sí
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

        return $flag;   // Retorna verdadero o falso dependiendo si los archivos contienen el mismo UUID
    }

    /** 
     * Obtiene el UUID del archivo XML.
     * @param mixed $xml Archivo XML.
     * @return string UUID.
     * @return null Retorna null en caso de que no encuentre el campo.
    */
    public static function getUUIDXML($xml) {
        try {
            return $xml['TimbreFiscalDigital'][0]['@attributes']['UUID'];   // Obtiene el UUID del archivo xml
        } catch(Exception $e) {
            return null;
        }
    }

    /** 
     * Obtiene el RFC del emisor del archivo XML.
     * @param mixed $xml Archivo XML.
     * @return string RFC del emisor.
     * @return null Retorna null en caso de que no encuentre el campo.
    */
    public static function getProviderRFCXML($xml) {
        try {
            return $xml['Emisor'][0]['@attributes']['Rfc'];   // Obtiene el RFC del emisor (proveedor) del archivo xml
        } catch(Exception $e) {
            return null;
        }
    }

    /** 
     * Obtiene el RFC del receptor del archivo XML.
     * @param mixed $xml Archivo XML.
     * @return string RFC del receptor.
     * @return null Retorna null en caso de que no encuentre el campo.
    */
    public static function getOwnerRFCXML($xml) {
        try {
            return $xml['Receptor'][0]['@attributes']['Rfc'];   // Obtiene el RFC del receptor (propietario) del archivo xml
        } catch(Exception $e) {
            return null;
        }
    }

    /** 
     * Obtiene el nombre del emisor del archivo XML.
     * @param mixed $xml Archivo XML.
     * @return string Nombre del emisor.
     * @return null Retorna null en caso de que no encuentre el campo.
    */
    public static function getNameProviderXML($xml) {
        try {
            return $xml['Emisor'][0]['@attributes']['Nombre'];   // Obtiene el Nombre del emisor (proveedor) del archivo xml
        } catch(Exception $e) {
            return null;
        }
    }

    /** 
     * Obtiene el nombre del receptor del archivo XML.
     * @param mixed $xml Archivo XML.
     * @return string Nombre del receptor.
     * @return null Retorna null en caso de que no encuentre el campo.
    */
    public static function getNameOwnerXML($xml) {
        try {
            return $xml['Receptor'][0]['@attributes']['Nombre'];   // Obtiene el Nombre del emisor (propietario) del archivo xml
        } catch(Exception $e) {
            return null;
        }
    }

    /** 
     * Obtiene el total de la factura del archivo XML.
     * @param mixed $xml Archivo XML.
     * @return string Total de la factura.
     * @return null Retorna null en caso de que no encuentre el campo.
    */
    public static function getTotalXML($xml) {
        try {
            return $xml['Comprobante'][0]['@attributes']['Total'];   // Obtiene el Total de la factura del archivo xml
        } catch(Exception $e) {
            return null;
        }
    }

    /** 
     * Obtiene el folio de la factura del archivo XML.
     * @param mixed $xml Archivo XML.
     * @return string Folio de la factura.
     * @return null Retorna null en caso de que no encuentre el campo.
    */
    public static function getFolio($xml) {
        try {
            return $xml['Comprobante'][0]['@attributes']['Folio'];   // Obtiene el Folio de la factura del archivo xml
        } catch(Exception $e) {
            return null;
        }
    }

    /** 
     * Obtiene el lugar de expedición (código postal) del emisor del archivo XML.
     * @param mixed $xml Archivo XML.
     * @return string Lugar de expedición del emisor.
     * @return null Retorna null en caso de que no encuentre el campo.
    */
    public static function getZipCode($xml) {
        try {
            return $xml['Comprobante'][0]['@attributes']['LugarExpedicion'];   // Obtiene el Lugar de Expedición del emisor (C.P.) de la factura del archivo xml
        } catch(Exception $e) {
            return null;
        }
    }

    /** 
     * Obtiene los productos registrados en la factura del archivo XML.
     * @param mixed $xml Archivo XML.
     * @return array Productos registrados en la factura.
     * @return null Retorna null en caso de que no encuentre el campo.
    */
    public static function getProductsXML($xml) {
        if(isset($xml['Productos'])) {
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
            return $array_products;   // Obtiene los Productos de la factura del archivo xml
        }
        else {
            return null;
        }
    }
}
