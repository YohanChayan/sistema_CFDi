@extends('layouts.app')

@section('content')

<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-sm-6 col-xl-3">
            <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                <i class="fa fa-chart-line fa-3x text-primary"></i>
                <div class="ms-3">
                    <p class="mb-2">Total proveedores registrados</p>
                    <h6 class="mb-0">{{ $providers_count }}</h6>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                <i class="fa fa-chart-bar fa-3x text-primary"></i>
                <div class="ms-3">
                    <p class="mb-2">Total de facturas generadas</p>
                    <h6 class="mb-0">{{ $invoices_count }}</h6>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                <i class="fa fa-chart-area fa-3x text-primary"></i>
                <div class="ms-3">
                    <p class="mb-2">Total de facturas generadas hoy</p>
                    <h6 class="mb-0">{{ $invoices_today }}</h6>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                <i class="fa fa-chart-pie fa-3x text-primary"></i>
                <div class="ms-3">
                    <p class="mb-2">Total de usuarios registrados</p>
                    <h6 class="mb-0">{{ $users_count }}</h6>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-sm-12 col-xl-6">
            <div class="bg-light text-center rounded p-4">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h6 class="mb-0">TOP 5 proveedores mas facturados </h6>
                    <a href="">Mostrar todo</a>
                </div>
                <canvas id="bar-chart" width="902" height="450" style="display: block; box-sizing: border-box; height: 225px; width: 451px;"></canvas>
            </div>
        </div>
        <div class="col-sm-12 col-xl-6">
            <div class="bg-light rounded h-100 p-4">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h6 class="mb-0">Facturas por meses</h6>
                    <a href="">Mostrar todo</a>
                </div>
                <canvas id="line-chart" width="902" height="450" style="display: block; box-sizing: border-box; height: 225px; width: 451px;"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-sm-12 col-md-6 col-xl-4">
            <div class="h-100 bg-light rounded p-4">
                <div class="d-flex align-items-center justify-content-left mb-2">
                    <h6 class="mb-0">Facturas Recientes</h6>
                </div>
                @foreach($recent_invoices as $invoice)
                    <div class="d-flex align-items-center border-bottom py-3">
                        <img class="rounded-circle flex-shrink-0" src="{{asset('custom/img/user.jpg')}}" alt="" style="width: 40px; height: 40px;">
                        <div class="w-100 ms-3">
                            <div class="row">
                                <h6 class="mb-0 col-8">{{ $invoice->provider->nombre }}</h6>
                                <small class="col-4 text-end">{{ $invoice->created_at->diffForHumans() }}</small>
                            </div>
                            <span>Ha subido {{ $invoice->other != '' ? '3' : '2' }} archivos</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="col-sm-12 col-md-6 col-xl-4">
            <div class="h-100 bg-light rounded p-4">
                <div class="d-flex align-items-center justify-content-left mb-2">
                    <h6 class="mb-0">Pagos Recientes</h6>
                </div>
                @foreach($recent_payments as $payment)
                    <div class="d-flex align-items-center border-bottom py-3">
                        <img class="rounded-circle flex-shrink-0" src="{{asset('custom/img/user.jpg')}}" alt="" style="width: 40px; height: 40px;">
                        <div class="w-100 ms-3">
                            <div class="row">
                                <h6 class="mb-0 col-8">{{ $payment->invoice->provider->nombre }}</h6>
                                <small class="col-4 text-end">{{ $payment->created_at->diffForHumans() }}</small>
                            </div>
                            <span>Ha pagado ${{ number_format($payment->payment, 2) }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="col-sm-12 col-md-6 col-xl-4">
            <div class="h-100 bg-light rounded p-4">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h6 class="mb-0">Calender</h6>
                    <a href="">Show All</a>
                </div>
                <div id="calender"></div>
            </div>
        </div>
    </div>
</div>
<!-- Widgets End -->


<!-- Footer Start -->
<div class="container-fluid pt-4 px-4">
    <div class="bg-light rounded-top p-4">
        <div class="row">
            <div class="col-12 col-sm-6 text-center text-sm-start">
                &copy; <a href="#">Frutioro</a> 2022. All Right Reserved.
            </div>
        </div>
    </div>
</div>
<!-- Footer End -->

</div>

@section('my_scripts')
<script>

    const providers_vs_invoices = @json($providers_vs_invoices);
    const arrayLabel_SingleBar = [];
    const arrayData_SingleBar = [];

    let i = 0;
    for( p of providers_vs_invoices){
        arrayLabel_SingleBar[i] = p['nombre_proveedor'];
        arrayData_SingleBar[i] = p['facturas'];
        i++;
    }


    // Single Bar Chart
    var ctx4 = $("#bar-chart").get(0).getContext("2d");
    var myChart4 = new Chart(ctx4, {
        type: "bar",
        data: {
            labels: ["#1", "#2", "#3", "#4", "#5"],
            // labels: arrayLabel_SingleBar,
            datasets: [{
                label: "Facturas",
                backgroundColor: [
                    "rgba(0, 156, 255, .7)",
                    "rgba(0, 156, 255, .6)",
                    "rgba(0, 156, 255, .5)",
                    "rgba(0, 156, 255, .4)",
                    "rgba(0, 156, 255, .3)"
                ],
                data: arrayData_SingleBar
            }]
        },
        options: {
            responsive: true,
            plugins:{
                tooltip:{
                    callbacks:{
                        title: function(context){
                            return ` ${arrayLabel_SingleBar[context[0].dataIndex]} `;
                        }
                    }
                }
            }
        }

    });


    const arrayMonthsLabel = [
    'Enero',
    'Febrero',
    'Marzo',
    'Abril',
    'Mayo',
    'Junio',
    'Julio',
    'Agosto',
    'Septiembre',
    'Octubre',
    'Noviembre',
    'Diciembre'];

    const invoices_vs_months = @json($invoices_vs_months);

    // Single Line Chart
    var ctx3 = $("#line-chart").get(0).getContext("2d");
    var myChart3 = new Chart(ctx3, {
        type: "line",
        data: {
            // labels: [50, 60, 70, 80, 90, 100, 110, 120, 130, 140, 150],
            labels: arrayMonthsLabel,
            datasets: [{
                label: "Facturas",
                fill: false,
                backgroundColor: "rgba(0, 156, 255, .3)",
                // data: [7, 8, 8, 9, 9, 9, 10, 11, 14, 14, 11]
                data: invoices_vs_months
            }]
        },
        options: {
            responsive: true
        },
        scales:{
            y:{
                beginAtZero: true
            }
        }
    });
</script>
@endsection

@endsection