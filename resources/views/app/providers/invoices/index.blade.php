@extends('layouts.app')

@section('my_scripts')
  <script src="{{ asset('js/providers/invoices.js') }}"></script>
@endsection

@section('content')

<div class="container-fluid pt-4 px-4">
  <div class="bg-light rounded p-4">
    <div class="d-flex justify-content-left mb-4">
      <h2 class="mb-0">Mis Facturas</h2>
    </div>

    <div class="row">
      <div class="col-md-3">
        <label for="filter">Filtrar por</label>
        <select class="form-select" name="filter" id="filter" onchange="filter();">
          <option value="TO">Todas</option>
          <option value="PE">Pendientes de saldar</option>
          <option value="PA">Pagadas</option>
        </select>
      </div>
    </div>

    <br>

    <div class="table-responsive" id="my_invoices_table">
      
    </div>

    <div class="modal fade" id="invoiceDetailsModal" tabindex="-1" aria-labelledby="invoiceDetailsModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="invoiceDetailsModalLabel">Detalles de la factura</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body" id="invoiceDetailsModalContent">
            
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