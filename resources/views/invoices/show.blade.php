@extends('layouts.app')

@section('content')

<div class="container-fluid pt-4 px-4">
    <div class="row h-100 align-items-center justify-content-center" style="min-height: 80vh; ">
        <div class="col-8 col-sm-8 col-md-10 col-lg-10 col-xl-8">
            <div class="bg-light rounded h-100 p-4">
                <h2 class="mb-4">Nueva Factura</h2>


                <form action="http://syscfdi.localhost.com/invoices" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="0oLNC1ymASgizES60UT44FYgTy3RbGP0oH1RLiDn">

                        <div class="row g-3 justify-content-center">
                            <div class="mb-3 col-md-8">
                              <label for="pdf_input" class="form-label">Seleccione archivo PDF</label>
                              <input type="file" class="form-control" name="pdf_input" required="" accept="application/pdf">
                              <div id="pdfHelp" class="form-text">Por favor seleccione un archivo PDF</div>
                            </div>

                        </div>

                        <div class="row g-3 justify-content-center">
                            <div class="mb-3 col-md-8">
                              <label for="xml_input" class="form-label">Seleccione archivo XML</label>
                              <input type="file" class="form-control" name="xml_input" required="" accept="application/xml">
                              <div id="xmlHelp" class="form-text">Por favor seleccione un archivo XML</div>
                            </div>

                        </div>
                        <div class="row g-3 justify-content-center">
                            <div class="mb-3 col-md-8">
                              <label for="other" class="form-label">Opcional. Seleccione archivo de cualquier formato</label>
                              <input type="file" class="form-control" name="other">
                              <div id="otherlHelp" class="form-text">Por favor seleccione archivo (Anexo)</div>
                            </div>
                        </div>

                        <div class="row g-3 justify-content-center">
                            <div class="mb-3 col-md-8">
                                <button type="submit" class="btn btn-primary">Subir</button>
                            </div>
                        </div>


                </form>
            </div>
        </div>
    </div>
</div>




@endsection
