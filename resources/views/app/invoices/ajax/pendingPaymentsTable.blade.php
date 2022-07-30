<table class="table text-start align-middle table-bordered mb-0">
    <thead>
        <tr class="text-dark">
            <th scope="col" class="text-center">UUID</th>
            <th scope="col" class="text-center">Folio</th>
            <th scope="col" class="text-center">Nombre</th>
            <th scope="col" class="text-center">Importe</th>
            <th scope="col" class="text-center">Saldo</th>
            <th scope="col" class="text-center">F. Pago</th>
            <th scope="col" class="text-center">Pago</th>
        </tr>
    </thead>
    <tbody>
        @foreach($invoices as $inv)
            <tr>
                <td class="text-center">{{ $inv->uuid }}</td>
                <td class="text-center">{{ $inv->folio }}</td>
                <td class="text-center">{{ $inv->provider->nombre }}</td>
                <td class="text-center">${{ number_format($inv->total, 2) }}</td>
                <td class="text-center">${{ number_format($inv->payments->sum('payment'), 2) }}</td>
                <td class="text-center">
                    <input class="form-control" type="date" name="date_{{ $inv->id }}" id="date_{{ $inv->id }}">
                </td>
                <td class="text-center">
                    <div class="input-group">
                        <span class="input-group-text">$</span>
                        <input class="form-control" type="number" name="payment_{{ $inv->id }}" id="payment_{{ $inv->id }}" min="0">
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>