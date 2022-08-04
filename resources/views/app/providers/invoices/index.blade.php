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
  </div>
</div>

@endsection