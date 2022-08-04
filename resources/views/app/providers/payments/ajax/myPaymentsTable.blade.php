<table class="table text-start align-middle table-bordered mb-0">
    <thead>
        <tr class="text-dark">
            <th class="text-center">#</th>
            <th class="text-center">Fecha</th>
            <th class="text-center">UUID</th>
            <th class="text-center">Pago</th>
        </tr>
    </thead>
    <tbody>
        @if(count($payments) > 0)
            @foreach($payments as $key => $payment)
                <tr>
                    <td class="text-center">{{ $key+1 }}</td>
                    <td class="text-center">{{ date("d-m-Y", strtotime($payment->date)) }}</td>
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