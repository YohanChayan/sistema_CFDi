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
      <h2 class="mb-0">Proveedores</h2>
    </div>

    <br>

    <div class="row">
      <div class="col-lg-12">
        <table id="table" class="table text-start align-middle table-bordered mb-0" style="width: 100%;">
          <thead>
            <tr class="text-dark">
              <th class="text-center">#</th>
              <th class="text-center">RFC</th>
              <th class="text-center">Nombre</th>
            </tr>
          </thead>
          <tbody>
            @foreach($providers as $key => $provider)
              <tr>
                <td class="text-center">{{ $key+1 }}</td>
                <td class="text-center">{{ $provider->rfc }}</td>
                <td class="text-center">{{ $provider->nombre }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

@endsection