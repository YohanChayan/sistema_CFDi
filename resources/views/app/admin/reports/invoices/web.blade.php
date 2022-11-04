@extends('layouts.app')

@section('my_scripts')
  <script src="{{ asset('js/administrador/reports/invoices/invoices.js') }}"></script>
@endsection

@section('content')

<div class="container-fluid pt-4 px-4">
  <div class="bg-light rounded p-4">
    <div class="d-flex justify-content-between mb-4">
      <h2 class="mb-0">Reporte De Facturas</h2>
      <a target="_blank" class="btn btn-danger" onclick="generatePDF();">Generar PDF <i class="far fa-file-pdf ms-2"></i></a>
    </div>

    <div class="row">
      <div class="col-md-3">
        <label for="owner">Empresa</label>
        <select class="form-select" name="owner" id="owner">
          @foreach($owners as $owner)
            <option value="{{ $owner->id }}">{{ $owner->rfc }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-3">
        <label for="start_date">Fecha de inicio</label>
        <input class="form-control" type="date" name="start_date" id="start_date" value="{{ date("Y-m-d") }}">
        <div class="text-danger" id="error_start_date"></div>
      </div>
      <div class="col-md-3">
        <label for="end_date">Fecha de fin</label>
        <input class="form-control" type="date" name="end_date" id="end_date" value="{{ date("Y-m-d") }}">
        <div class="text-danger" id="error_end_date"></div>
      </div>
      <div class="col-md-3">
        <a class="btn btn-primary text-light mt-4" onclick="filter();"><i class="fas fa-search"></i></a>
      </div>
    </div>

    <br>

    <div class="text-center mt-3">
      <div id="my_spinner" class="spinner-border text-primary" style="width: 3rem; height: 3rem; display: none;" role="status">
      </div>
    </div>
    <div class="table-responsive" id="my_invoices_table">
      
    </div>
  </div>
</div>

@endsection