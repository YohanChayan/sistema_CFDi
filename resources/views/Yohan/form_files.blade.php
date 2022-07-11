<x-dashboard>
    <form action="{{ route('yohan.store') }}" method="POST" enctype="multipart/form-data">
      @csrf
        <div class="mb-3 col-md-8">
          <label for="pdf_input" class="form-label">Seleccione archivo PDF</label>
          <input type="file" class="form-control" name="pdf_input" required accept="application/pdf">
          <div id="pdfHelp" class="form-text">Por favor seleccione un archivo PDF</div>
        </div>
        <div class="mb-3 col-md-8">
          <label for="xml_input" class="form-label">Seleccione archivo XML</label>
          <input type="file" class="form-control" name="xml_input" required accept="application/xml">
          <div id="xmlHelp" class="form-text">Por favor seleccione un archivo XML</div>
        </div>
        <div class="mb-3 col-md-8">
          <label for="other" class="form-label">Opcional. Seleccione archivo de cualquier formato</label>
          <input type="file" class="form-control" name="other">
          <div id="otherlHelp" class="form-text">Por favor seleccione archivo (Anexo)</div>
        </div>
        <button type="submit" class="btn btn-primary">Subir</button>
      </form>
</x-dashboard>