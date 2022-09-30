@extends('layouts.app')

@section('my_scripts')
  <script src="{{ asset('js/providers/payments.js') }}"></script>
@endsection

@section('content')

<div class="container-fluid pt-4 px-4">
  <div class="bg-light rounded p-4">
    <div class="d-flex justify-content-left mb-4">
      <h2 class="mb-0">Mis Productos</h2>
    </div>

    <br>

    <table class="table text-start align-middle table-bordered mb-0" id="myPaymentTable" style="width: 100%;">
      <thead>
          <tr class="text-dark">
              <th style="width: 10%;" class="text-center">#</th>
              <th style="width: 35%;" class="text-center">Nombre</th>
              <th style="width: 35%;" class="text-center">Producto SAT</th>
              <th style="width: 20%;" class="text-center">Fecha de registro</th>
          </tr>
      </thead>
      <tbody>
          @if(count($products) > 0)
            @php $cont = 1; @endphp
            @foreach($products as $product)
                <tr>
                    <td class="text-center">{{ $cont }}</td>
                    <td class="text-center">{{ $product->name }}</td>
                    <td class="text-center">{{ $product->sat_product->name }}</td>
                    <td class="text-center">{{ date('d-m-Y', strtotime($product->created_at)) }}</td>
                </tr>
                @php $cont += 1; @endphp
            @endforeach
          @else
              <tr>
                  <td class="text-center" colspan="3">No has subido ninguna factura.</td>
              </tr>
          @endif
      </tbody>
    </table>


    <!-- Modal payment preview -->
    <div class="modal fade" id="paymentPreview" tabindex="-1" aria-labelledby="paymentPreviewLabel" aria-hidden="true" data-bs-backdrop="static">
      <div class="modal-dialog">
        <div class="modal-content bg-light">
          <div class="modal-header">
            <h5 class="modal-title" id="paymentPreviewLabel">Vista previa pago</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body ">
            <div class="row">
                <div class="col-md-8 mx-auto" id="imgPreviewContainer">

                </div>
            </div>

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>

@endsection
