<x-dashboard>
    <div class="mt-3">
        <h2>Datos extraídos del archivo XML</h2>

        <br>

        <h4>Datos del comprobante</h4>

        <table class="table">
            <thead>
                <tr>
                    <th>Folio</th>
                    <th>Fecha</th>
                    <th>Total</th>
                    <th>No. Certificado</th>
                </tr>
            </thead>
        
            <tbody>
                @foreach ($data["Comprobante"][0] as $comprobante)
                    <tr>
                        <td>{{ $comprobante["Folio"] }}</td>
                        <td>{{ date("d-m-Y h:i:s a", strtotime($comprobante["Fecha"])) }}</td>
                        <td>{{ $comprobante["Total"] }}</td>
                        <td>{{ $comprobante["NoCertificado"] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <br>

        <h4>Datos del emisor</h4>

        <table class="table">
            <thead>
                <tr>
                    <th>RFC</th>
                    <th>Nombre</th>
                    <th>Regimen Fiscal</th>
                </tr>
            </thead>
        
            <tbody>
                @foreach ($data["Emisor"][0] as $emisor)
                    <tr>
                        <td>{{ $emisor["Rfc"] }}</td>
                        <td>{{ $emisor["Nombre"] }}</td>
                        <td>{{ $emisor["RegimenFiscal"] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <br>

        <h4>Datos del timbre fiscal</h4>

        <table class="table">
            <thead>
                <tr>
                    <th>UUID</th>
                    <th>Fecha</th>
                    <th>RFC Proveedor</th>
                    <th>No. Certificado SAT</th>
                </tr>
            </thead>
        
            <tbody>
                @foreach ($data["TimbreFiscalDigital"][0] as $timbreFiscal)
                    <tr>
                        <td>{{ $timbreFiscal["UUID"] }}</td>
                        <td>{{ date("d-m-Y h:i:s a", strtotime($timbreFiscal["FechaTimbrado"])) }}</td>
                        <td>{{ $timbreFiscal["RfcProvCertif"] }}</td>
                        <td>{{ $timbreFiscal["NoCertificadoSAT"] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-dashboard>