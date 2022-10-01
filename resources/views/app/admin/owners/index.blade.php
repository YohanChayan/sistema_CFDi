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
      <button class="btn btn-success" type="button" data-bs-toggle="modal" data-bs-target="#createOwnerModal">Nueva Empresa</button>
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

  <div class="modal fade" id="createOwnerModal" tabindex="-1" aria-labelledby="createOwnerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="createOwnerModalLabel">Registrar nueva empresa</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="{{ route('owners.store') }}" method="POST" id="createOwnerForm">
            @csrf
            <div class="row">
              <div class="col-md-6">
                <label for="rfc">RFC</label>
                <input class="form-control" type="text" name="rfc" id="rfc" maxlength="13" style="text-transform: uppercase">
                <div class="text-danger" id="error_rfc"></div>
              </div>
              <div class="col-md-6">
                <label for="name">Nombre</label>
                <input class="form-control" type="text" name="name" id="name" style="text-transform: uppercase">
                <div class="text-danger" id="error_name"></div>
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          <button type="button" class="btn btn-primary" onclick="registerOwner(this);">Guardar</button>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection