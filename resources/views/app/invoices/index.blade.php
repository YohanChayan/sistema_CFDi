@extends('layouts.app')

@section('my_scripts')
  <script src="{{ asset("js/administrador/facturas/facturas.js") }}"></script>
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
                  <a class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#paymentsModal" onclick="modalPayment({{ $inv->id }});">Pagos</a>
                  <a class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">Detalles</a>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>


  <div class="modal  fade" id="paymentsModal" tabindex="-1" aria-labelledby="paymentsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog modal-dialog-centered">
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


  <div class="modal  fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                  {{-- <h5 class="card-title">Special title treatment</h5> --}}
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
                  {{-- <h5 class="card-title">Special title treatment</h5> --}}
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
                  {{-- <h5 class="card-title">Special title treatment</h5> --}}
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
  </div>
</div>

@endsection
