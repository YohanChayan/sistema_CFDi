<?php

namespace App\SAT;

use Exception;

class ValidateCFDI
{
	protected $rfcEmisor;
	protected $rfcReceptor;
	protected $total;
	protected $uuid;
	protected $urlConsulta;
	protected $headers;
	protected $errores;
	protected $data;

	public function __construct($rfcEmisor, $rfcReceptor, $total, $uuid) {
		// Inicializa los campos en el constructor
		$this->rfcEmisor = $rfcEmisor;
		$this->rfcReceptor = $rfcReceptor;
		$this->total = $total;
		$this->uuid = $uuid;

		// WSDL para consultar el estatus del CFDI ante el SAT
		$this->urlConsulta = 'https://consultaqr.facturaelectronica.sat.gob.mx/consultacfdiservice.svc';

		// Encabezados requeridos para la petición
		$this->headers = array(
			'Accept: text/xml',
			'Content-Type: text/xml; charset=utf-8',
            'SOAPAction: http://tempuri.org/IConsultaCFDIService/Consulta'
		);

		// Inicializa el campo de errores
		$this->errores = array();
	}

	// Obtiene los errores encontrados en la ejecución de la petición
	public function getErrors() {
		return $this->errores;
	}

	// Obtiene el estatus del CFDI a partir del RFC del emisor, RFC del receptor, total y el CFDI
	public function validate() {
		// Configura el tiempo límite en caso de que el servicio tarde mas de lo esperado
        set_time_limit(0);

		// Estructura de la consulta
		$request = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:tem="http://tempuri.org/">';
            $request .= '<soapenv:Header/>';
                $request .= '<soapenv:Body>';
                    $request .= '<tem:Consulta>';
                        $request .= '<tem:expresionImpresa>';
                            $request .= '?re=' . $this->rfcEmisor . '&amp;rr=' . $this->rfcReceptor . '&amp;tt=' . number_format($this->total, 2, '.', '') . '&amp;id=' . $this->uuid;
                        $request .= '</tem:expresionImpresa>';
                    $request .= '</tem:Consulta>';
                $request .= '</soapenv:Body>';
        $request .= '</soapenv:Envelope>';

		// Agrega a las cabeceras la longitud del request, referenciando a este atributo como Content-length
		array_push($this->headers, 'Content-length: ' . strlen($request));

		// Encierra la petición dentro de un bloque try-catch para controlar las excepciones
		try {
            // Inicia una sesión de curl
            $ch = curl_init();

            // Configura curl
            curl_setopt($ch, CURLOPT_URL, $this->urlConsulta);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

            // Ejecuta la petición
            $response = curl_exec($ch);

            // Cierra la petición
            curl_close($ch);

            // Da formato al XML que se obtuvo como respuesta
            $xml = simplexml_load_string($response);
            $xmlContent = $xml->children('s', true)->children('', true)->children('', true);
            $xmlContentData = json_decode(json_encode($xmlContent->children('a', true), JSON_UNESCAPED_UNICODE), true);
            
            // La respuesta retornada por parte del servicio es la siguiente:
            // [CodigoEstatus]
            // [EsCancelable]
            // [Estado]
            // [EstatusCancelacion] => Array
            // [ValidacionEFOS]

            // Asigna la información ya formateada
            $this->data = $xmlContentData;
		} catch (Exception $e) {
			// Cacha el error
			$this->errores[] = $this->uuid . ': Error con el servicio de consulta del SAT. Por favor intente más tarde.';
		}

		// Regresa la información dependiendo si existen errores
		return (count($this->errores) > 0) ? false : $this->data;
	}
}