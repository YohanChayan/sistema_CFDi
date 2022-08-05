@extends('layouts.app')

@section('my_scripts')
    <script src="{{ asset("js/providers/facturas.js") }}"></script>
@endsection

@section('content')
    <div class="container-fluid pt-4 px-4">
        <div class="row h-100 align-items-center justify-content-center" style="min-height: 80vh;">
            <div class="col-8 col-sm-8 col-md-10 col-lg-10 col-xl-8">
                <div class="bg-light rounded h-100 p-4">

                    <h2 class="mb-4">Nueva Factura</h2>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('invoices.store') }}" id="formulario" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-3 justify-content-center">
                            <div class="mb-3 col-md-8">
                                <label for="pdf_input" class="form-label">Seleccione archivo PDF</label>
                                <input type="file" class="form-control" name="pdf_input" id="pdf_input" required accept="application/pdf">
                                <div id="pdfHelp" class="form-text">Por favor seleccione un archivo PDF</div>
                            </div>
                        </div>

                        <div class="row g-3 justify-content-center">
                            <div class="mb-3 col-md-8">
                                <label for="xml_input" class="form-label">Seleccione archivo XML</label>
                                <input type="file" class="form-control" name="xml_input" id="xml_input" required accept="application/xml">
                                <div id="xmlHelp" class="form-text">Por favor seleccione un archivo XML</div>
                            </div>
                        </div>

                        <div class="row g-3 justify-content-center">
                            <div class="mb-3 col-md-8">
                            <label for="other" class="form-label">Seleccione archivo de cualquier formato</label>
                            <input type="file" class="form-control" name="other_input" id="other_input" required>
                            <div id="otherlHelp" class="form-text">Por favor seleccione archivo (Anexo)</div>
                            </div>
                        </div>

                        <div class="row g-3 justify-content-center">
                            <div class="mb-3 col-md-8">
                                <button type="button" class="btn btn-primary" onclick="registerCreateInvoiceData();">Subir</button>
                            </div>
                        </div>
                    </form>

                    <div class="modal fade" id="registerModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="registerModalLabel">Registro</h5>
                                </div>
                                <div class="modal-body">
                                    <div class="form-floating mb-3">
                                        <input class="form-control" type="password" name="password" id="password" placeholder="Contraseña">
                                        <label for="password">Contraseña</label>
                                        <div class="text-danger" id="error-password"></div>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <input class="form-control" type="password" name="confirm_password" id="confirm_password" placeholder="Confirmar Contraseña">
                                        <label for="confirm_password">Confirmar Contraseña</label>
                                        <div class="text-danger" id="error-confirm-password"></div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" onclick="cancelDataNewProvider();">Cancelar</button>
                                    <button type="button" class="btn btn-primary" onclick="registerDataNewProvider();">Registrar</button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
