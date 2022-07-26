@extends('layouts.app')

@section('content')

<div class="container-fluid pt-4 px-4">
  <div class="bg-light text-center rounded p-4">
    <div class="d-flex justify-content-left mb-4">
      <h2 class="mb-0">Proveedores</h2>
    </div>

    <div class="table-responsive">
      <table class="table text-start align-middle table-bordered mb-0">
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

@endsection