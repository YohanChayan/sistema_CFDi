<script>
    $('#table').DataTable({
        ordering: false,
        language: {
            url: '//cdn.datatables.net/plug-ins/1.12.1/i18n/es-MX.json'
        },
    });
</script>

<table id="table" class="table text-start align-middle table-bordered mt-3" style="width: 100%;">
    <thead>
        <tr>
            <th class="text-center">#</th>
            <th class="text-center">Nombre</th>
            <th class="text-center">Producto SAT</th>
            <th class="text-center">Precio</th>
            <th class="text-center">Últ. Actualización</th>
        </tr>
    </thead>
    <tbody>
        @if(count($products) > 0)
            @php $cont = 1; @endphp
            @foreach($products as $product)
                @if($product->invoice->status == 'A')
                    <tr>
                        <td class="text-center">{{ $cont }}</td>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->sat_product->name }}</td>
                        <td class="text-center">${{ number_format($product->price, 2) }}</td>
                        <td class="text-center">{{ date('d-m-Y', strtotime($product->created_at)) }}</td>
                    </tr>
                    @php $cont++; @endphp
                @endif
            @endforeach
        @else
            <tr>
                <td class="text-center" colspan="5">No pudimos cotizar productos de acuerdo a los criterios establecido. Prueba nuevamente.</td>
            </tr>
        @endif
    </tbody>
</table>