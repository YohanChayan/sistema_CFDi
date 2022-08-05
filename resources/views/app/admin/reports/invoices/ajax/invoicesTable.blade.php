<table class="table text-start align-middle table-bordered mb-0">
    <thead>
        <tr class="text-dark">
            <th class="text-center">#</th>
            <th class="text-center">Fecha</th>
            <th class="text-center">Proveedor</th>
            <th class="text-center">UUID</th>
            <th class="text-center">Total</th>
        </tr>
    </thead>
    <tbody>
        @if(count($invoices) > 0)
            @foreach($invoices as $key => $invoice)
                <tr>
                    <td class="text-center">{{ $key+1 }}</td>
                    <td class="text-center">{{ date("d-m-Y", strtotime($invoice->created_at)) }}</td>
                    <td class="text-center">{{ $invoice->provider->nombre }}</td>
                    <td class="text-center">{{ $invoice->uuid }}</td>
                    <td class="text-center">${{ number_format($invoice->total, 2) }}</td>
                </tr>
            @endforeach
        @else
            <tr>
                <td class="text-center" colspan="6">No hay registros en ese rango de fechas.</td>
            </tr>
        @endif
    </tbody>
</table>