<p><b>UUID:</b> {{ $invoice->uuid }}</p>
<p><b>Folio:</b> {{ $invoice->folio }}</p>
<p><b>Factura subida el:</b> {{ date('d-m-Y', strtotime($invoice->created_at)) }}</p>
<p><b>Productos registrados</b></p>

<div class="table-responsive mt-3">
    <table class="table text-start align-middle table-bordered mb-0">
        <thead>
        <tr>
            <th class="text-center">#</th>
            <th class="text-center">Nombre</th>
            <th class="text-center">Cantidad</th>
            <th class="text-center">Precio</th>
            <th class="text-center">Subtotal</th>
        </tr>
        </thead>
        <tbody>
            @if(count($invoice->details) > 0)
                @foreach($invoice->details as $key => $detail)
                    <tr>
                        <td class="text-center">{{ $key+1 }}</td>
                        <td class="text-center">{{ $detail->name }}</td>
                        <td class="text-center">{{ $detail->quantity }}</td>
                        <td class="text-center">${{ number_format($detail->price, 2) }}</td>
                        <td class="text-center">${{ number_format($detail->total, 2) }}</td>
                    </tr>
                @endforeach

                <tr>
                    <th colspan="4" class="text-end">Total</th>
                    <th colspan="1" class="text-center">${{ number_format($invoice->details->sum('total'), 2) }}</th>
                </tr>
            @else
                <tr>
                    <td colspan="5" class="text-center">No encontramos los detalles de esta factura.</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>