@extends('layouts.app')

@section('my_scripts')
  <script src="{{ asset("js/administrador/invoices/invoices.js") }}"></script>
@endsection

@section('content')

<div class="container-fluid pt-4 px-4">
  <div class="bg-light text-center rounded p-4">
    <div class="d-flex align-items-center justify-content-between mbp-4">
      <h2 class="mb-0">Facturas</h2>
    </div>

    <br>

    <div class="table-responsive">
      <table class="table text-start align-middle table-bordered mb-0">
        <thead>
          <tr class="text-dark">
            <th scope="col" class="text-center" style="width: 15%;">Fecha de creación</th>
            <th scope="col" class="text-center" style="width: 35%;">Empresa registrada</th>
            <th scope="col" class="text-center" style="width: 35%;">Proveedor</th>
            <th scope="col" class="text-center" style="width: 15%;">Acciones</th>
          </tr>
        </thead>
        <tbody>
          @foreach($invoices as $inv)
            <tr>
              <td class="text-center">
                {{  date("d/m/Y", strtotime($inv->created_at)) }}
              </td>

              <td>
                <table class="table">
                  <thead>
                    <tr>
                      <th scope="col" style="width: 75%;">Nombre</th>
                      <th scope="col" style="width: 25%;">RFC</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>{{ $inv->owner->nombre }}</td>
                      <td>{{ $inv->owner->rfc }}</td>
                    </tr>
                  </tbody>
                </table>
              </td>

              <td>
                <table class="table">
                  <thead>
                    <tr>
                      <th scope="col" style="width: 75%;">Nombre</th>
                      <th scope="col" style="width: 25%;">RFC</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>{{ $inv->provider->nombre }}</td>
                      <td>{{ $inv->provider->rfc }}</td>
                    </tr>
                  </tbody>
                </table>
              </td>

              <td class="text-center">
                <div class="row d-flex flex-column align-items-center ">
                    <div class="col-md-6 mx-auto p-0 my-1">
                      <a class="btn btn-sm btn-primary link-center" data-bs-toggle="modal" data-bs-target="#paymentsModal" onclick="modalPayment({{ $inv->id }});">Pagos</a>
                    </div>
                    <div class="col-md-6 mx-auto p-0 my-1">
                      <a class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#filesModal" onclick="modalFile({{ $inv->id }});">Descargar</a>
                    </div>
                </div>

              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
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
            <div class="col-lg-12">
              <a class="text-primary" data-bs-toggle="collapse" data-bs-target="#paymentsHistory" aria-expanded="false" aria-controls="paymentsHistory" style="cursor: pointer">
                  Historial de pagos
              </a>
              <div class="collapse" id="paymentsHistory">

              </div>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          <button id="addPaymentBtn" type="button" class="btn btn-primary">Agregar</button>
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
          <button type="button" class="btn btn-success" onclick="downloadFile();">Descargar</button>
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
