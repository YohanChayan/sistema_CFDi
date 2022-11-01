@extends('layouts.app')

@section('my_scripts')
  <script src="{{ asset('js/administrador/quotes/quotes.js') }}"></script>
  <script>
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
      return new bootstrap.Popover(popoverTriggerEl)
    });
  </script>
@endsection

@section('content')

<div class="container-fluid pt-4 px-4">
  <div class="bg-light rounded p-4">
    <div class="mb-4">
      <h2 class="mb-0">Cotización</h2>
    </div>

    <div class="row">
      <div class="col-lg-3">
        <label for="product">Producto</label>
        <input class="form-control" type="text" name="product" id="product" style="text-transform: uppercase;">
        <div class="text-danger" id="error_product"></div>
      </div>
      <div class="col-lg-3">
        <label for="budget">
          Presupuesto <i class="far fa-question-circle" style="cursor: pointer;" data-bs-toggle="popover" title="Nota"
          data-bs-content="El presupuesto debe ser por una pieza del producto a considerar." data-bs-trigger="hover"></i>
        </label>
        <div class="input-group">
          <span class="input-group-text">$</span>
          <input class="form-control" type="number" name="budget" id="budget" min="0">
        </div>
        <div class="text-danger" id="error_budget"></div>
      </div>
      <div class="col-lg-3">
        <label for="filter">Filtro</label>
        <select class="form-select" name="filter" id="filter">
          <option value="-1" selected>Seleccione una opción...</option>
          <option value="Todos">Todos</option>
          <option value="Mas_Comprados">Más comprados</option>
          <option value="Novedades">Novedades</option>
        </select>
        <div class="text-danger" id="error_filter"></div>
      </div>
      <div class="col-lg-3">
        <label for="location">Ubicación</label>
        <select class="form-select" name="location" id="location">
          <option value="-1" selected>Seleccione una opción...</option>
          <option value="Todos">Todos</option>
          @foreach($states as $state)
            <option value="{{ $state->name }}">{{ $state->name }}</option>
          @endforeach
        </select>
        <div class="text-danger" id="error_location"></div>
      </div>
    </div>

    <br>

    <div class="d-flex justify-content-center mb-5">
      <button class="btn btn-primary btn-lg" type="button" onclick="quote();">
        Cotizar &nbsp;<i class="fas fa-coins"></i>
      </button>
    </div>

    <br>

    <div class="text-center">
      <div id="my_spinner" class="spinner-border text-primary" style="width: 3rem; height: 3rem; display: none;" role="status">
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12" id="quote_result">

      </div>
    </div>
  </div>
</div>
@endsection
