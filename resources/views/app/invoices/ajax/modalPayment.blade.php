<div class="table-responsive mt-3">
    <table class="table text-start align-middle table-bordered mb-0">
        <thead>
        <tr>
            <th class="text-center">Fecha</th>
            <th class="text-center">Monto</th>
        </tr>
        </thead>
        <tbody>
            @php $saldo = $invoice->total; @endphp
            @if(count($invoice->payments) > 0)
                @foreach($invoice->payments as $payment)
                <tr>
                    <td class="text-center">{{ date("d/m/Y", strtotime($payment->date)) }}</td>
                    <td class="text-center">${{ number_format($payment->payment, 2) }}</td>
                    @php $saldo -= $payment->payment; @endphp
                </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="2" class="text-center">No se han realizado pagos.</td>
                </tr>
            @endif
        
            <tr>
                <td class="text-center border border-3"><b>Saldo pendiente</b></td>
                <td id="pendingPayment" class="text-center border border-3">${{ number_format($saldo, 2) }}</td>
            </tr>
        </tbody>
    </table>
</div>

<script>
    let pending_payment = @json($saldo);

    if(pending_payment <= 0) {
        $('#addPaymentBtn').prop('disabled', true);
    }
</script>