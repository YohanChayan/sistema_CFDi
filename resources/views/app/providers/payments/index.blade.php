@extends('layouts.app')

@section('my_scripts')
  <script src="{{ asset('js/providers/payments.js') }}"></script>
@endsection

@section('content')

<div class="container-fluid pt-4 px-4">
  <div class="bg-light rounded p-4">
    <div class="d-flex justify-content-left mb-4">
      <h2 class="mb-0">Mis Pagos</h2>
    </div>

    <div class="row">
      <div class="col-md-3">
        <label for="start_date">Fecha de inicio</label>
        <input class="form-control" type="date" name="start_date" id="start_date" value="{{ date("Y-m-01") }}">
        <div class="text-danger" id="error_start_date"></div>
      </div>
      <div class="col-md-3">
        <label for="end_date">Fecha de fin</label>
        <input class="form-control" type="date" name="end_date" id="end_date" value="{{ date("Y-m-t") }}">
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
    <div class="table-responsive" id="my_payments_table">

    </div>


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
