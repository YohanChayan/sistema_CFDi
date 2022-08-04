<table class="table text-start align-middle table-bordered mb-0">
    <thead>
        <tr class="text-dark">
            <th class="text-center">#</th>
            <th class="text-center">UUID</th>
            <th class="text-center">Folio</th>
            <th class="text-center">Total</th>
            <th class="text-center">Saldo por pagar</th>
            <th class="text-center">Estado</th>
        </tr>
    </thead>
    <tbody>
        @if(count($invoices) > 0)
            @foreach($invoices as $key => $invoice)
                <tr>
                    <td class="text-center">{{ $key+1 }}</td>
                    <td class="text-center">{{ $invoice->uuid }}</td>
                    <td class="text-center">{{ $invoice->folio }}</td>
                    <td class="text-center">${{ number_format($invoice->total, 2) }}</td>
                    <td class="text-center">${{ number_format($invoice->total - $invoice->payments->sum('payment'), 2) }}</td>
                    <td class="text-center">{{ $invoice->status }}</td>
                </tr>
            @endforeach
        @else
            <tr>
                <td class="text-center" colspan="6">No hay registros.</td>
            </tr>
        @endif
    </tbody>
</table>