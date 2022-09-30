@extends('layouts.app')

@section('my_scripts')
  <script src="{{ asset('js/administrador/owners/owners.js') }}"></script>
  <script>
    //Tooltip
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
  </script>
@endsection

@section('content')

<div class="container-fluid pt-4 px-4">
  <div class="bg-light text-center rounded p-4">
    <div class="d-flex justify-content-between mb-4">
      <h2 class="mb-0">Mis Datos</h2>
      <button class="btn btn-success" type="button">Nueva Empresa</button>
    </div>

    <div class="table-responsive">
      <table class="table text-start align-middle table-bordered mb-0">
        <thead>
          <tr class="text-dark">
            <th class="text-center">#</th>
            <th class="text-center">RFC</th>
            <th class="text-center">Nombre</th>
            <th class="text-center">Acciones</th>
          </tr>
        </thead>
        <tbody>
          @foreach($owners as $key => $owner)
            <tr>
              <td class="text-center">{{ $key+1 }}</td>
              <td class="text-center">{{ $owner->rfc }}</td>
              <td class="text-center">{{ $owner->nombre }}</td>
              <td class="text-center">
                <a id="delete_{{ $owner->id }}" class="text-danger" style="cursor: pointer;" onclick="deleteOwner(this);">
                  <span data-bs-toggle="tooltip" data-bs-placement="top" title="Eliminar">
                    <i class="fas fa-trash"></i>
                  </span>
                </a>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>

@endsection