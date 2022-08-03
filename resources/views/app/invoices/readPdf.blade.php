@extends('layouts.app')

@section('my_scripts')
    <script src="{{ asset("js/providers/facturas.js") }}"></script>
@endsection

@section('content')
<form action="{{ route('invoices.readPdfTest') }}" id="formulario" method="POST" enctype="multipart/form-data">
    @csrf

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row g-3 justify-content-center">
        <div class="mb-3 col-md-8">
            <label for="pdf_input" class="form-label">Seleccione archivo PDF</label>
            <input type="file" class="form-control" name="pdf_input" id="pdf_input" required accept="application/pdf">
            <div id="pdfHelp" class="form-text">Por favor seleccione un archivo PDF</div>
        </div>
    </div>

    <div class="row g-3 justify-content-center">
        <div class="mb-3 col-md-8">
            <button type="submit" class="btn btn-primary">Subir</button>
        </div>
    </div>
</form>
@endsection