@extends('layouts.app')

@section('my_scripts')
    <script src="{{asset('js/administrador/dashboard/charts.js')}}"></script>
    <script>
        $(document).ready(function(){
          load_top_providers(@json($providers_vs_invoices));
          load_invoices_months(@json($invoices_vs_months));
        })
    </script>
@endsection

@section('content')

    <!-- Widgets Start -->
    <div class="container-fluid pt-4 px-4">
        <div class="row g-4">
            <div class="col-sm-6 col-xl-3">
                <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                    <i class="far fa-address-book fa-3x text-primary"></i>
                    <div class="ms-3">
                        <p class="mb-2">Total de proveedores registrados</p>
                        <h6 class="mb-0">{{ $providers_count }}</h6>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                    <i class="far fa-file-alt fa-3x text-primary"></i>
                    <div class="ms-3">
                        <p class="mb-2">Total de facturas generadas</p>
                        <h6 class="mb-0">{{ $invoices_count }}</h6>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                    <i class="far fa-clock fa-3x text-primary"></i>
                    <div class="ms-3">
                        <p class="mb-2">Total de facturas generadas hoy</p>
                        <h6 class="mb-0">{{ $invoices_today }}</h6>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                    <i class="far fa-money-bill-alt fa-3x text-primary"></i>
                    <div class="ms-3">
                        <p class="mb-2">Total de pagos registrados</p>
                        <h6 class="mb-0">{{ $payments_count }}</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="container-fluid pt-4 px-4">
        <div class="row g-4">
            <div class="col-sm-12 col-xl-6">
                <div class="bg-light text-center rounded p-4">
                    <div class="d-flex align-items-center justify-content-center mb-4">
                        <h6 class="mb-0">TOP 5 proveedores mas facturados </h6>
                        {{-- <a href="">Mostrar todo</a> --}}
                    </div>
                    <canvas id="bar-chart" width="902" height="450" style="display: block; box-sizing: border-box; height: 225px; width: 451px;"></canvas>
                </div>
            </div>
            <div class="col-sm-12 col-xl-6">
                <div class="bg-light rounded h-100 p-4">
                    <div class="d-flex align-items-center justify-content-center mb-4">
                        <h6 class="mb-0">Facturas por meses</h6>
                        {{-- <a href="">Mostrar todo</a> --}}
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
                    @if(count($recent_invoices) > 0)
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
                    @else
                        No hay facturas recientes.
                    @endif
                </div>
            </div>
            <div class="col-sm-12 col-md-6 col-xl-4">
                <div class="h-100 bg-light rounded p-4">
                    <div class="d-flex align-items-center justify-content-left mb-2">
                        <h6 class="mb-0">Pagos Recientes</h6>
                    </div>
                    @if(count($recent_payments) > 0)
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
                    @else
                        No hay pagos recientes.
                    @endif
                </div>
            </div>
            <div class="col-sm-12 col-md-6 col-xl-4">
                <div class="h-100 bg-light rounded p-4">
                    <div class="d-flex align-items-center justify-content-left mb-2">
                        <h6 class="mb-0">Proveedores Registrados Recientemente</h6>
                    </div>
                    @if(count($recent_providers) > 0)
                        @foreach($recent_providers as $provider)
                            <div class="d-flex align-items-center border-bottom py-3">
                                <img class="rounded-circle flex-shrink-0" src="{{asset('custom/img/user.jpg')}}" alt="" style="width: 40px; height: 40px;">
                                <div class="w-100 ms-3">
                                    <div class="row">
                                        <h6 class="mb-0 col-12">{{ $provider->nombre . ' (' . $provider->rfc . ')' }}</h6>
                                    </div>
                                    <span>Se registró {{ $provider->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        @endforeach
                    @else
                        No hay pagos recientes.
                    @endif
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

@endsection
