<form action="{{ route("invoices.addFilteredPayments") }}" method="POST" id="paymentsForm" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="paymentsFiltered" id="paymentsFiltered" value="{{ json_encode($invoices) }}">
    <input type="hidden" name="pendingPayments" id="pendingPayments">
    {{-- <input type="hidden" name="filePayment"> --}}
    <div class="row">
        <div class="col-lg-4">
            <label for="date">Fecha de Pago</label>
            <input class="form-control" type="date" name="date" id="date">
            <div class="text-danger" id="error_date"></div>
        </div>
        <div class="col-lg-4">
            <label for="filePayment">Comprobante de Pago</label>
            <input class="form-control" type="file" id="filePayment" name="filePayment">
            <div class="text-danger" id="error_filePayment"></div>
        </div>
    </div>
</form>

<br>

<div class="table-responsive">
    <table class="table text-start align-middle table-bordered mb-0">
        <thead>
            <tr class="text-dark">
                <th scope="col" class="text-center" style="width: 10%;">Seleccionar</th>
                <th scope="col" class="text-center" style="width: 15%;">UUID</th>
                <th scope="col" class="text-center" style="width: 10%;">Folio</th>
                <th scope="col" class="text-center" style="width: 15%;">Nombre</th>
                <th scope="col" class="text-center" style="width: 10%;">Importe</th>
                <th scope="col" class="text-center" style="width: 10%;">Saldo</th>
                {{-- <th scope="col" class="text-center" style="width: 10%;">F. Pago</th> --}}
                <th scope="col" class="text-center" style="width: 15%;">Método Pago</th>
                <th scope="col" class="text-center" style="width: 15%;">Pago</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoices as $inv)
                <tr>
                    <td class="text-center">
                        <input class="form-check-input" type="checkbox" id="check_{{ $inv->id }}">
                    </td>
                    <td class="text-center">{{ $inv->uuid }}</td>
                    <td class="text-center">{{ $inv->folio }}</td>
                    <td class="text-center">{{ $inv->provider->nombre }}</td>
                    <td class="text-center">${{ number_format($inv->total, 2) }}</td>
                    <td class="text-center">${{ number_format($inv->total - $inv->payments->sum('payment'), 2) }}</td>
                    {{-- <td class="text-center">
                        <input class="form-control" type="date" name="date_{{ $inv->id }}" id="date_{{ $inv->id }}">
                    </td> --}}
                    <td class="text-center">
                        <select class="form-select" name="payment_method_{{ $inv->id }}" id="payment_method_{{ $inv->id }}">
                            <option value="1">Efectivo</option>
                            <option value="2">Tarjeta crédito</option>
                            <option value="3">Tarjeta débito</option>
                            <option value="4">Transferencia</option>
                            <option value="5">Otro</option>
                        </select>
                    </td>
                    <td class="text-center">
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input class="form-control" type="number" name="payment_{{ $inv->id }}" id="payment_{{ $inv->id }}" min="0" onchange="payment();">
                        </div>
                    </td>
                </tr>
            @endforeach
            <tr>
                <th class="text-end fs-5" colspan="7">Monto Total</th>
                <td class="text-center fs-5" id="total">$0.00</td>
            </tr>
        </tbody>
    </table>
</div>

<br>

<div class="d-flex justify-content-end">
    <button class="btn btn-primary" type="button" onclick="saveAll()">Guardar</button>
</div>