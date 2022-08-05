<table class="table text-start align-middle table-bordered mb-0" style="width: 100%;">
    <thead>
        <tr class="text-dark">
            <th scope="col" class="text-center" style="width: 15%;">Fecha de creación</th>
            <th scope="col" class="text-center" style="width: 35%;">Empresa registrada</th>
            <th scope="col" class="text-center" style="width: 35%;">Proveedor</th>
            <th scope="col" class="text-center" style="width: 15%;">Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach($invoices as $inv)
            <tr>
                <td class="text-center">
                    {{  date("d/m/Y", strtotime($inv->created_at)) }}
                </td>

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
                        <td>{{ $inv->provider->nombre }}</td>
                        <td>{{ $inv->provider->rfc }}</td>
                        </tr>
                    </tbody>
                    </table>
                </td>

                <td class="text-center">
                    <div class="row d-flex flex-column align-items-center ">
                        <div class="col-md-6 mx-auto p-0 my-1">
                        <a class="btn btn-sm btn-primary link-center" data-bs-toggle="modal" data-bs-target="#paymentsModal" onclick="modalPayment({{ $inv->id }});">Pagos</a>
                        </div>
                        <div class="col-md-6 mx-auto p-0 my-1">
                        <a class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#filesModal" onclick="modalFile({{ $inv->id }});">Descargar</a>
                        </div>
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>