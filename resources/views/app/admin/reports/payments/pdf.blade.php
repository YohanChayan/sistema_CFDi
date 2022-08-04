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

        table {
            margin: 0 auto;
        }

        /* Default Table Style */
        table {
            color: #333;
            background: white;
            border: 1px solid grey;
            font-size: 12pt;
            border-collapse: collapse;
        }
        table thead th,
        table tfoot th {
            color: #777;
            background: rgba(0,0,0,.1);
        }
        table caption {
            padding:.5em;
        }
        table th,
        table td {
            padding: .5em;
            border: 1px solid lightgrey;
        }
        /* Zebra Table Style */
        [data-table-theme*=zebra] tbody tr:nth-of-type(odd) {
            background: rgba(0,0,0,.05);
        }
        [data-table-theme*=zebra][data-table-theme*=dark] tbody tr:nth-of-type(odd) {
            background: rgba(255,255,255,.05);
        }
        /* Dark Style */
        [data-table-theme*=dark] {
            color: #ddd;
            background: #333;
            font-size: 12pt;
            border-collapse: collapse;
        }
        [data-table-theme*=dark] thead th,
        [data-table-theme*=dark] tfoot th {
        color: #aaa;
            background: rgba(0255,255,255,.15);
        }
        [data-table-theme*=dark] caption {
            padding:.5em;
        }
        [data-table-theme*=dark] th,
        [data-table-theme*=dark] td {
            padding: .5em;
            border: 1px solid grey;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        h1 { color: #333333; font-family: 'Bitter', serif; font-size: 50px; font-weight: normal; line-height: 54px; margin: 0 0 54px; }
        p { color: #333333; font-family: Georgia, serif; font-size: 18px; line-height: 28px; margin: 0 0 28px; }
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

    <br>

    <table>
        <thead>
            <tr>
                <th class="text-center">#</th>
                <th class="text-center">Fecha</th>
                <th class="text-center">UUID</th>
                <th class="text-center">Pago</th>
            </tr>
        </thead>
        <tbody>
            @if(count($payments) > 0)
                @foreach($payments as $key => $payment)
                    <tr>
                        <td class="text-center">{{ $key+1 }}</td>
                        <td class="text-center">{{ date("d-m-Y", strtotime($payment->date)) }}</td>
                        <td class="text-center">{{ $payment->invoice->uuid }}</td>
                        <td class="text-center">${{ number_format($payment->payment, 2) }}</td>
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