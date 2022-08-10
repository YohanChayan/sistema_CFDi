<script>
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    })
</script>

<table class="table text-start align-middle table-bordered mb-0" style="width: 100%;">
    <thead>
        <tr class="text-dark">
            <th scope="col" class="text-center" style="width: 25%;">Empresa registrada</th>
            <th scope="col" class="text-center" style="width: 65%;">Proveedor</th>
            <th scope="col" class="text-center" style="width: 10%;">Acciones</th>
        </tr>
    </thead>
    <tbody>
        @if(count($invoices) > 0)
        @foreach($invoices as $inv)
            <tr>
                {{-- EMPRESA REGISTRADA --}}
                <td>
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col" style="width: 75%;">Nombre</th>
                                <th scope="col" style="width: 25%;">RFC</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $inv->owner->nombre }}</td>
                                <td>{{ $inv->owner->rfc }}</td>
                            </tr>
                        </tbody>
                    </table>
                    </td>

                    {{-- PROVEEDOR --}}
                    <td>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col" style="width: 25%;">Nombre</th>
                                    <th scope="col" style="width: 10%;">RFC</th>
                                    <th scope="col" style="width: 55%;">UUID</th>
                                    <th scope="col" style="width: 10%;">Folio</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ $inv->provider->nombre }}</td>
                                    <td>{{ $inv->provider->rfc }}</td>
                                    <td>{{ $inv->uuid }}</td>
                                    <td>{{ $inv->folio }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>

                    {{-- ACCIONES --}}
                    <td class="text-center">
                        <div class="row d-flex justify-content-between">
                            <div class="col-md-4">
                                <a class="text-success" data-bs-toggle="modal" data-bs-target="#paymentsModal" onclick="modalPayment({{ $inv->id }});" style="cursor: pointer;">
                                    <span data-bs-toggle="tooltip" data-bs-placement="top" title="Pagos">
                                        <i class="fas fa-money-bill-wave"></i>
                                    </span>
                                </a>
                            </div>
                            <div class="col-md-4">
                                <a class="text-primary" data-bs-toggle="modal" data-bs-target="#filesModal" onclick="modalFile({{ $inv->id }});" style="cursor: pointer;">
                                    <span data-bs-toggle="tooltip" data-bs-placement="top" title="Descargas">
                                        <i class="fas fa-file-download"></i>
                                    </span>
                                </a>
                            </div>
                            <div class="col-md-4">
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