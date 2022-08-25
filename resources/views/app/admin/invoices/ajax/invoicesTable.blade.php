<script>
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    })
</script>

<table class="table text-start align-middle table-bordered mb-0" style="width: 100%;">
    <thead>
        <tr class="text-dark">
            <th scope="col" class="text-center" style="width: 20%;">Nombre</th>
            <th scope="col" class="text-center" style="width: 15%;">RFC</th>
            <th scope="col" class="text-center" style="width: 30%;">UUID</th>
            <th scope="col" class="text-center" style="width: 10%;">Folio</th>
            <th scope="col" class="text-center" style="width: 10%;">Total</th>
            <th scope="col" class="text-center" style="width: 15%;">Acciones</th>
        </tr>
    </thead>
    <tbody>
        @if(count($invoices) > 0)
            @foreach($invoices as $inv)
                <tr>
                    {{-- PROVEEDOR --}}
                    <td class="text-center">{{ $inv->provider->nombre }}</td>
                    <td class="text-center">{{ $inv->provider->rfc }}</td>
                    <td class="text-center">{{ $inv->uuid }}</td>
                    <td class="text-center">{{ $inv->folio }}</td>
                    <td class="text-center">${{ number_format($inv->total, 2) }}</td>

                    {{-- ACCIONES --}}
                    <td class="text-center">
                        <div class="row d-flex justify-content-between">
                            <div class="col-md-3">
                                <a class="text-success" data-bs-toggle="modal" data-bs-target="#paymentsModal" onclick="modalPayment({{ $inv->id }});" style="cursor: pointer;">
                                    <span data-bs-toggle="tooltip" data-bs-placement="top" title="Pagos">
                                        <i class="fas fa-money-bill-wave"></i>
                                    </span>
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a class="text-primary" data-bs-toggle="modal" data-bs-target="#filesModal" onclick="modalFile({{ $inv->id }});" style="cursor: pointer;">
                                    <span data-bs-toggle="tooltip" data-bs-placement="top" title="Descargas">
                                        <i class="fas fa-file-download"></i>
                                    </span>
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a class="text-secondary" onclick="resendEmail({{ $inv->id }});" style="cursor: pointer;">
                                    <span data-bs-toggle="tooltip" data-bs-placement="top" title="Reenviar Correo">
                                        <i class="fas fa-envelope-open-text"></i>
                                    </span>
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a class="text-danger" id="delete_{{ $inv->id }}" onclick="deleteInvoice(this);" style="cursor: pointer;">
                                    <span data-bs-toggle="tooltip" data-bs-placement="top" title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </span>
                                </a>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td class="text-center" colspan="3">No hay registros.</td>
            </tr>
        @endif
    </tbody>
</table>