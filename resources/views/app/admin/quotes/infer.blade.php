<script>
    $('#table').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.12.1/i18n/es-MX.json'
        },
    });
</script>

<table id="table" class="table text-start align-middle table-bordered mt-3" style="width: 100%;">
    <thead>
        <tr>
            <th style="width: 5%;" class="text-center">#</th>
            <th style="width: 20%;" class="text-center">Nombre</th>
            <th style="width: 20%;" class="text-center">Producto SAT</th>
            <th style="width: 10%;" class="text-center">Precio</th>
            <th style="width: 10%;" class="text-center">Ubicación</th>
            <th style="width: 20%;" class="text-center">Proveedor</th>
            <th style="width: 15%;" class="text-center">Últ. Actualización</th>
        </tr>
    </thead>
    <tbody>
        @if(count($products) > 0)
            @php $cont = 1; @endphp
            @foreach($products as $product)
                @if($product->invoice->status == 'A')
                    @php $zip_code = $zip_codes->where('zip_code', $product->invoice->zip_code)->first(); @endphp
                    @if($zip_code != null)
                        <tr>
                            <td class="text-center">{{ $cont }}</td>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->sat_product->name }}</td>
                            <td class="text-center">${{ number_format($product->price, 2) }}</td>
                            <td>{{ $zip_code->state }}</td>
                            <td>{{ $product->invoice->provider->nombre }}</td>
                            <td class="text-center">{{ date('d-m-Y', strtotime($product->created_at)) }}</td>
                        </tr>
                        @php $cont++; @endphp
                    @endif
                @endif
            @endforeach
        @else
            <tr>
                <td class="text-center" colspan="6">No pudimos cotizar productos de acuerdo a los criterios establecidos. Prueba nuevamente.</td>
            </tr>
        @endif
    </tbody>
</table>