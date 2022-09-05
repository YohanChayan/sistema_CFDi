<link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">

<table class="table text-start align-middle table-bordered mb-0" id="myPaymentTable" style="widows: 100%;">
    <thead>
        <tr class="text-dark">
            <th style="width: 10%;" class="text-center">#</th>
            <th style="width: 15%;" class="text-center">Fecha</th>
            <th style="width: 10%;" class="text-center">Folio</th>
            <th style="width: 35%;" class="text-center">UUID</th>
            <th style="width: 15%;" class="text-center">Pago</th>
            <th style="width: 15%;" class="text-center">Comprobante</th>
        </tr>
    </thead>
    <tbody>
        @if(count($payments) > 0)
            @foreach($payments as $key => $payment)
                <tr>
                    <td class="text-center">{{ $key+1 }}</td>
                    <td class="text-center">{{ date("d-m-Y", strtotime($payment->date)) }}</td>
                    <td class="text-center">{{ $payment->invoice->folio }}</td>
                    <td class="text-center">{{ $payment->invoice->uuid }}</td>
                    <td class="text-center">${{ number_format($payment->payment, 2) }}</td>
                    <td class="text-center">
                        <div class="row d-flex justify-content-evenly">
                            <div class="col-md-3" >
                                <a onclick="paymentPreview({{$payment->id}});" class="text-primary" style="cursor: pointer;">
                                    <span data-bs-toggle="tooltip" data-bs-placement="top" title="Vista previa">
                                        <i class="fas fa-eye me-3"></i>
                                    </span>
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="{{route('invoices.downloadPayment', $payment->id)}}" class="text-primary" style="cursor: pointer;">
                                    <span data-bs-toggle="tooltip" data-bs-placement="top" title="Descargar">
                                        <i class="fas fa-download"></i>
                                    </span>
                                </a>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td class="text-center" colspan="6">No hay registros en ese rango de fechas.</td>
            </tr>
        @endif
    </tbody>
</table>


<script>
      $(document).ready( function () {
          $('#myPaymentTable').DataTable();
      } );
</script>
