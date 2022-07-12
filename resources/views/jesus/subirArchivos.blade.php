<x-dashboard>
    <div class="mt-3">
        <h2>Sube tus archivos</h2>
        <br>
        <h5>Favor de subir tu factura adjuntando a la vez el formato PDF y XML</h5>
        <br>
        <div class="row">
            <div class="col-md-6">
                <form action="{{ route("jesusenviarArchivos") }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <label for="pdf_file">Seleccione un archivo PDF</label>
                    <input class="form-control" type="file" name="pdf_file" id="pdf_file" required accept="application/pdf">
                    <br>
                    <label for="xml_file">Seleccione un archivo XML</label>
                    <input class="form-control" type="file" name="xml_file" id="xml_file" required accept="application/xml">
                    <br>
                    <label for="other_file">Seleccione un archivo anexo (opcional)</label>
                    <input class="form-control" type="file" name="other_file" id="other_file">
                    <br>
                    <div class="d-flex justify-content-end">
                        <button class="btn btn-primary" type="submit">Subir</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-dashboard>