<div class="sidebar pe-4 pb-3">
    <nav class="navbar bg-light navbar-light">

        @if(Auth::Check())
        <a href="{{ route('dashboard') }}" class="navbar-brand mx-4 mb-3">
            <h3 class="text-primary">
                <img src="{{ asset('favicon/papaya.png') }}" alt="" class="w-25">
                Frutioro
            </h3>
        </a>
        @else
        <a href="{{ route('home') }}" class="navbar-brand mx-4 mb-3">
            <h3 class="text-primary">
                <img src="{{ asset('favicon/papaya.png') }}" alt="" class="w-25">
                Frutioro
            </h3>
        </a>
        @endif

        @if(!Auth::Check())
        
        @endif
        @if (Auth::Check())
            <div class="d-flex align-items-center ms-4 mb-4">
                <div class="position-relative">
                    <img class="rounded-circle border border-dark" src="{{ auth()->user()->profile_photo_path == null ? \App\Models\User::find(auth()->user()->id)->profile_photo_url : asset(auth()->user()->profile_photo_path) }}" alt="" style="width: 40px; height: 40px;">
                    <div class="bg-success rounded-circle border border-2 border-white position-absolute end-0 bottom-0 p-1"></div>
                </div>
                <div class="ms-3">                                            
                    <h6 class="mb-0"> {{ Auth()->user()->name }} </h6>                    
                    <span>
                        @if(Auth()->user()->type == 'A')
                            Administrador
                        @elseif(Auth()->user()->type == 'P')
                            Proveedor
                        @else
                            Usuario sin permiso.
                        @endif
                            
                    </span>
                </div>
            </div>
        @endif
        <div class="navbar-nav w-100">
            @if(Auth::Check())
                <a href="{{ route('dashboard') }}" class="nav-item nav-link active"><i class="fa fa-tachometer-alt me-2"></i>Inicio</a>
            @else
                <a href="{{ route('home') }}" class="nav-item nav-link active"><i class="fa fa-tachometer-alt me-2"></i>Inicio</a>
            @endif
            @if(!Auth::Check() || auth()->user()->type == "P")
                <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="far fa-file-alt me-2"></i>Facturas</a>
                    <div class="dropdown-menu bg-transparent border-0">
                            <a href="{{ route('invoices.create') }}" class="dropdown-item ms-4">Subir nueva factura</a>
                            <a href="{{ route('invoices.myInvoices') }}" class="dropdown-item ms-4">Mis Facturas</a>
                        </div>
                    </div> 
            @endif 
            @if(Auth::Check() && auth()->user()->type == "A")
                <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="far fa-file-alt me-2"></i>Facturas</a>
                    <div class="dropdown-menu bg-transparent border-0">
                        <a href="{{ route('invoices.index') }}" class="dropdown-item ms-4">Consultar</a>
                        <a href="{{ route('invoices.paymentsBulkUpload') }}" class="dropdown-item ms-4">Carga masiva de pagos</a>
                    </div>
                </div>
                <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="far fa-address-book me-2"></i>Proveedores</a>
                    <div class="dropdown-menu bg-transparent border-0">
                        <a href="{{ route('providers.index') }}" class="dropdown-item ms-4">Consultar</a>
                    </div>
                </div>
                <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="far fa-building me-2"></i>Mi Empresa</a>
                    <div class="dropdown-menu bg-transparent border-0">
                        <a href="{{ route('owners.index') }}" class="dropdown-item ms-4">Consultar</a>
                    </div>
                </div>
            @endif
        </div>
    </nav>
</div>
