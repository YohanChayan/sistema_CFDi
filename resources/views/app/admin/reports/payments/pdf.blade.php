<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte De Pagos</title>

    <style>
        img {
            vertical-align: middle;
        }

        span {
            font-size: 22px;
            font-weight: bold;
        }

        h1 {
            color: #333333;
            font-family: 'Bitter', serif;
            font-size: 50px;
            font-weight: normal;
        }

        p {
            color: #333333;
            font-family: Georgia, serif;
            font-size: 18px;
            line-height: 20px;
        }

        table {
            margin: 0 auto;
            color: #333;
            background: white;
            border-collapse: collapse;
            width: 80%;
        }

        table th, td {
            padding: .5em;
            border: 1px solid lightgrey;
        }

        th {
            color: #777;
            background: rgba(0, 0 , 0, 0.1);
            font-size: 20px;
        }

        td {
            font-size: 16px;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }
    </style>
</head>
<body>
    <img src="{{ asset('favicon/papaya.png') }}" alt=""><span>Frutioro</span>
    
    <div class="text-center">
        <h1>Reporte de pagos</h1>
    </div>

    <div class="text-right">
        <p>Empresa: {{ $owner->nombre . ' (' . $owner->rfc . ')' }}</p>
        <p>Rango De Fechas: {{ $date }}</p>
    </div>

    <p class="text-right">Monto total: ${{ number_format($totalPayments, 2) }}</p>
    
    <table>
        <thead>
            <tr>
                <th style="width: 5%" class="text-center">#</th>
                <th style="width: 10%" class="text-center">Fecha</th>
                <th style="width: 20%" class="text-center">Proveedor</th>
                <th style="width: 15%" class="text-center">RFC</th>
                <th style="width: 10%" class="text-center">Folio</th>
                <th style="width: 30%" class="text-center">UUID</th>
                <th style="width: 10%" class="text-center">Pago</th>
            </tr>
        </thead>
        <tbody>
            @if(count($payments) > 0)
                @php $cont = 0; @endphp
                @foreach($payments as $payment)
                    @php $total = 0; @endphp
                    @foreach($payment as $data)
                        <tr>
                            <td class="text-center">{{ $cont+1 }}</td>
                            <td class="text-center">{{ date("d-m-Y", strtotime($data['date'])) }}</td>
                            <td class="text-center">{{ $data['provider_name'] }}</td>
                            <td class="text-center">{{ $data['provider_rfc'] }}</td>
                            <td class="text-center">{{ $data['folio'] }}</td>
                            <td class="text-center">{{ $data['uuid'] }}</td>
                            <td class="text-center">${{ number_format($data['payment'], 2) }}</td>
                        </tr>
                        @php $cont++; $total += $data['payment']; @endphp
                    @endforeach
                    <tr>
                        <td class="text-right" colspan="6"><b>Total</b></td>
                        <td class="text-center"><b>${{ number_format($total, 2) }}</b></td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td class="text-center" colspan="6">No hay registros en ese rango de fechas.</td>
                </tr>
            @endif
        </tbody>
    </table>
</body>
</html>