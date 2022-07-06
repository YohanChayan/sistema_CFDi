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
                    <input class="form-control" type="file" name="archivos[]" id="archivos" multiple required>
                    <br>
                    <div class="d-flex justify-content-end">
                        <button class="btn btn-primary" type="submit">Subir</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-dashboard>