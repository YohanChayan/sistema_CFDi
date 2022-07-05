<x-dashboard>
    <div class="mt-3">
        <h2>Datos extraídos del archivo PDF</h2>

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
                <tr>
                    <td>{{ $data["FOLIO"] }}</td>
                    <td>{{ date("d-m-Y h:i:s a", strtotime($data["FECHA DE EMISIÓN"])) }}</td>
                    <td>{{ $data["TOTAL"] }}</td>
                    <td>{{ $data["NO. SERIE CSD DEL EMISOR"] }}</td>
                </tr>
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
                <tr>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                </tr>
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
                <tr>
                    <td>{{ $data["UUID"] }}</td>
                    <td>{{ date("d-m-Y h:i:s a", strtotime($data["FECHA DE TIMBRADO"])) }}</td>
                    <td>{{ $data["RFC"] }}</td>
                    <td>{{ $data["NO. SERIE CSD DEL SAT"] }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</x-dashboard>