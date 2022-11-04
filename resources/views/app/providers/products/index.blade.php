@extends('layouts.app')

@section('my_scripts')
  <script>
    $('#table').DataTable({
        ordering: false,
        language: {
            url: '//cdn.datatables.net/plug-ins/1.12.1/i18n/es-MX.json'
        },
    });
  </script>
@endsection

@section('content')

<div class="container-fluid pt-4 px-4">
  <div class="bg-light rounded p-4">
    <div class="d-flex justify-content-left mb-4">
      <h2 class="mb-0">Mis Productos</h2>
    </div>

    <br>

    <div class="row">
      <div class="col-lg-12">
        <table id="table" class="table text-start align-middle table-bordered mb-0" style="width: 100%;">
          <thead>
              <tr class="text-dark">
                  <th style="width: 10%;" class="text-center">#</th>
                  <th style="width: 30%;" class="text-center">Nombre</th>
                  <th style="width: 25%;" class="text-center">Producto SAT</th>
                  <th style="width: 20%;" class="text-center">Unidad Medida SAT</th>
                  <th style="width: 15%;" class="text-center">Fecha de registro</th>
              </tr>
          </thead>
          <tbody>
              @php $cont = 1; @endphp
              @foreach($products as $product)
                  <tr>
                      <td class="text-center">{{ $cont }}</td>
                      <td class="text-center">{{ $product->name }}</td>
                      <td class="text-center">{{ $product->sat_product->name }}</td>
                      <td class="text-center">{{ $product->sat_measurement_unit->name }}</td>
                      <td class="text-center">{{ date('d-m-Y', strtotime($product->created_at)) }}</td>
                  </tr>
                  @php $cont += 1; @endphp
              @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

@endsection
