<table class="table text-start align-middle table-bordered mb-0">
    <thead>
      <tr class="text-dark">
        <th scope="col" class="text-center">UUID</th>
        <th scope="col" class="text-center">Folio</th>
        <th scope="col" class="text-center">Nombre</th>
        <th scope="col" class="text-center">Importe</th>
        <th scope="col" class="text-center">Saldo</th>
      </tr>
    </thead>
    <tbody>
      @foreach($invoices as $inv)
      @if ($inv->statuseliminado != "I")
        <tr>
          <td class="text-center">{{ $inv->uuid }}</td>
          <td class="text-center">{{ $inv->folio }}</td>
          <td class="text-center">{{ $inv->provider->nombre }}</td>
          <td class="text-center">${{ number_format($inv->total, 2) }}</td>
          <td class="text-center">${{ number_format($inv->total - $inv->payments->sum('payment'), 2) }}</td>
        </tr>
        @endif
      @endforeach
    </tbody>
</table>