<table class="table text-start align-middle table-bordered mb-0">
    <thead>
        <tr class="text-dark">
            <th style="width: 5%" class="text-center">#</th>
            <th style="width: 10%" class="text-center">Fecha</th>
            <th style="width: 20%" class="text-center">Proveedor</th>
            <th style="width: 15%" class="text-center">RFC</th>
            <th style="width: 10%" class="text-center">Folio</th>
            <th style="width: 30%" class="text-center">UUID</th>
            <th style="width: 10%" class="text-center">Pago</th>
        </tr>
    </thead>
    <tbody>
        @if(count($payments) > 0)
            @foreach($payments as $key => $payment)
                <tr>
                    <td class="text-center">{{ $key+1 }}</td>
                    <td class="text-center">{{ date("d-m-Y", strtotime($payment->date)) }}</td>
                    <td class="text-center">{{ $payment->invoice->provider->nombre }}</td>
                    <td class="text-center">{{ $payment->invoice->provider->rfc }}</td>
                    <td class="text-center">{{ $payment->invoice->folio }}</td>
                    <td class="text-center">{{ $payment->invoice->uuid }}</td>
                    <td class="text-center">${{ number_format($payment->payment, 2) }}</td>
                </tr>
            @endforeach
        @else
            <tr>
                <td class="text-center" colspan="6">No hay registros en ese rango de fechas.</td>
            </tr>
        @endif
    </tbody>
</table>