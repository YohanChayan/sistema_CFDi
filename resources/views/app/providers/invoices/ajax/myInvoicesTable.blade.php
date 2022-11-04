<script>
    $('#table').DataTable({
        ordering: false,
        language: {
            url: '//cdn.datatables.net/plug-ins/1.12.1/i18n/es-MX.json'
        },
    });
</script>

<table id="table" class="table text-start align-middle table-bordered mb-0" style="width: 100%;">
    <thead>
        <tr class="text-dark">
            <th class="text-center">#</th>
            <th class="text-center">UUID</th>
            <th class="text-center">Folio</th>
            <th class="text-center">Total</th>
            <th class="text-center">Saldo por pagar</th>
            <th class="text-center">Estado</th>
            <th class="text-center">Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach($invoices as $key => $invoice)
            <tr>
                <td class="text-center">{{ $key+1 }}</td>
                <td class="text-center">{{ $invoice->uuid }}</td>
                <td class="text-center">{{ $invoice->folio }}</td>
                <td class="text-center">${{ number_format($invoice->total, 2) }}</td>
                <td class="text-center">${{ number_format($invoice->total - $invoice->payments->sum('payment'), 2) }}</td>
                <td class="text-center">{{ $invoice->payment_status }}</td>
                <td class="text-center">
                    <a id="details_{{ $invoice->id }}" class="text-primary" style="cursor: pointer;" onclick="modalDetails(this);">
                        <span data-bs-toggle="tooltip" data-bs-placement="top" title="Ver Detalles">
                            <i class="far fa-eye"></i>
                        </span>
                    </a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<script>
    //Tooltip
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
</script>