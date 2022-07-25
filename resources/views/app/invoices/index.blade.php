@extends('layouts.app')


@section('content')

<div class="container-fluid pt-4 px-4">
    <div class="bg-light text-center rounded p-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h2 class="mb-0">Facturas</h2>
            <a href="">Mostrar todos</a>
        </div>
        <div class="table-responsive">
            <table class="table text-start align-middle table-bordered mb-0">
                <thead>
                    <tr class="text-dark">
                        <th scope="col" class="text-center">Fecha de creación</th>
                        <th scope="col" class="text-center">Empresa registrada</th>
                        <th scope="col" class="text-center">Proveedor</th>
                        <th scope="col" class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach($invoices as $inv)

                        <tr>
                            <td class="text-center">
                                {{  date("d M Y", strtotime($inv->fecha_creacion)) }}
                            </td>

                            <td>
                                <table class="table">
                                  <thead>
                                    <tr>
                                      <th scope="col">Nombre</th>
                                      <th scope="col">RFC</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <tr>
                                      <td>{{$inv->nombre_empresa}}</td>
                                      <td>{{$inv->RFC_empresa}}</td>
                                    </tr>
                                  </tbody>
                                </table>
                             </td>


                            <td>
                                <table class="table">
                                  <thead>
                                    <tr>
                                      <th scope="col">Nombre</th>
                                      <th scope="col">RFC</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <tr>
                                      <td>{{$inv->nombre_proveedor}}</td>
                                      <td>{{$inv->RFC_proveedor}}</td>
                                    </tr>
                                  </tbody>
                                </table>
                             </td>

                            <td>
                                {{-- <a href="{{ route('invoices.show', $inv->id) }}" class="btn btn-sm btn-primary">Detalles</a> --}}
                                <a href="#" data-bs-toggle="modal" data-bs-target="#exampleModal" class="btn btn-sm btn-primary">Detalles</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
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
                                  <div class="card-header ">

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



                           {{-- <div class="card">
                             <div class="card-body">
                               <h5 class="card-title">Acta de titulación</h5>
                               <h6 class="card-subtitle mb-2 text-muted">Card subtitle</h6>
                               <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>

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
                            </div> --}}
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
