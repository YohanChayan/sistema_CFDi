@extends('layouts.app')

@section('my_scripts')
  <script src="{{ asset("js/administrador/invoices/invoices.js") }}"></script>
@endsection

@section('content')

<div class="container-fluid pt-4 px-4">
  <div class="bg-light rounded p-4">
    <div class="mb-4">
      <h2 class="mb-0">Facturas</h2>
    </div>

    <div class="row">
      <div class="col-lg-4">
        <label for="owner">Empresa</label>
        <input class="form-control" type="text" list="owners_list" name="owner" id="owner" onchange="changeOwner();">
        <datalist id="owners_list">
          @foreach($owners as $owner)
            <option id="{{ $owner->id }}" value="{{ $owner->rfc }}"></option>
          @endforeach
        </datalist>
      </div>
      <div class="col-lg-4">
        <label for="provider">Proveedor</label>
        <input class="form-control" type="text" list="providers_list" name="provider" id="provider" onchange="filter();">
        <datalist id="providers_list">
        
        </datalist>
      </div>
    </div>

    <br>

    <div class="table-responsive" id="my_invoices_table">
      
    </div>
  </div>


  <div class="modal fade" id="paymentsModal" tabindex="-1" aria-labelledby="paymentsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="paymentsModalLabel">Pagos</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-lg-6">
              <label for="date">Fecha</label>
              <input class="form-control" type="date" name="date" id="date">
            </div>
            <div class="col-lg-6">
              <label for="payment">Monto</label>
              <div class="input-group">
                <span class="input-group-text">$</span>
                <input class="form-control" type="number" name="payment" id="payment" min="0">
              </div>
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-lg-6">
              <label for="payment_method">Método de pago</label>
              <select class="form-select" name="payment_method" id="payment_method">
                <option value="-1" selected>Seleccione un método</option>
                <option value="1">Efectivo</option>
                <option value="2">Tarjeta crédito</option>
                <option value="3">Tarjeta débito</option>
                <option value="4">Transferencia</option>
                <option value="5">Otro</option>
              </select>
            </div>
            <div class="col-lg-6">
              <label for="receipt">Comprobante</label>
              <input class="form-control" type="file" name="receipt" id="receipt">
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-lg-12">
              <a class="text-success" data-bs-toggle="collapse" data-bs-target="#paymentsHistory" aria-expanded="false" aria-controls="paymentsHistory" style="cursor: pointer">
                  Historial de pagos
              </a>
              <div class="collapse" id="paymentsHistory">

              </div>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          <button id="addPaymentBtn" type="button" class="btn btn-success">Agregar</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="filesModal" tabindex="-1" aria-labelledby="filesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="filesModalLabel">Descargar archivos</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-lg-12">
              <label class="mb-3" for="option">Seleccione una opción:</label>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="option" id="option1" value="T">
                <label class="form-check-label" for="option1">Todos</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="option" id="option2" value="PDF">
                <label class="form-check-label" for="option2">Archivo PDF</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="option" id="option3" value="XML">
                <label class="form-check-label" for="option3">Archivo XML</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="option" id="option4" value="A">
                <label class="form-check-label" for="option4">Archivo Anexo</label>
              </div>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          <button type="button" class="btn btn-primary" onclick="downloadFile();">Descargar</button>
        </div>
      </div>
    </div>
  </div>


  {{-- <div class="modal  fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row g-2">
            <div class="col-md-6 mb-2">
              <div class="card text-center">
                <div class="card-header">
                  <p class="fw-bolder mb-0">Ejemplo</p>
                </div>
                <div class="card-body">
                  <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>

                  <div class="btn-group dropend">
                    <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                      Ver
                    </button>

                    <ul class="dropdown-menu">
                      <li><a class="dropdown-item" href="#">Action</a></li>
                      <li><a class="dropdown-item" href="#">Action two</a></li>
                      <li><a class="dropdown-item" href="#">Action three</a></li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-md-6 mb-2">
              <div class="card text-center">
                <div class="card-header">
                  <p class="fw-bolder mb-0">Ejemplo</p>
                </div>
                <div class="card-body">
                  <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                  <div class="btn-group dropend">
                    <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                      Ver
                    </button>

                    <ul class="dropdown-menu">
                      <li><a class="dropdown-item" href="#">Action</a></li>
                      <li><a class="dropdown-item" href="#">Action two</a></li>
                      <li><a class="dropdown-item" href="#">Action three</a></li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="row g-2">
            <div class="col-md-6 mb-2">
              <div class="card text-center">
                <div class="card-header">
                  <p class="fw-bolder mb-0">Ejemplo</p>
                </div>
                <div class="card-body">
                  <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>

                  <div class="btn-group dropend">
                    <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                      Ver
                    </button>
                    <ul class="dropdown-menu">
                      <li><a class="dropdown-item" href="#">Action</a></li>
                      <li><a class="dropdown-item" href="#">Action two</a></li>
                      <li><a class="dropdown-item" href="#">Action three</a></li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-md-6 mb-2">
              <div class="card text-center">
                <div class="card-header">
                  <p class="fw-bolder mb-0">Ejemplo</p>
                </div>
                <div class="card-body">
                  <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>

                  <div class="btn-group dropend">
                    <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                      Ver
                    </button>

                    <ul class="dropdown-menu">
                      <li><a class="dropdown-item" href="#">Action</a></li>
                      <li><a class="dropdown-item" href="#">Action two</a></li>
                      <li><a class="dropdown-item" href="#">Action three</a></li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div> --}}
</div>

@endsection
