<div class="sidebar pe-4 pb-3">
    <nav class="navbar bg-light navbar-light">
        <a href="{{ route('login') }}" class="navbar-brand mx-4 mb-3">
            <h3 class="text-primary">
                <img src="{{ asset('favicon/papaya.png') }}" alt="" class="w-25">
                Sistema CFDI
            </h3>
        </a>
        @if (Auth::Check())
            <div class="d-flex align-items-center ms-4 mb-4">
                <div class="position-relative">
                    <img class="rounded-circle border border-dark" src="{{ auth()->user()->profile_photo_path == null ? \App\Models\User::find(auth()->user()->id)->profile_photo_url : asset(auth()->user()->profile_photo_path) }}" alt="" style="width: 40px; height: 40px;">
                    <div class="bg-success rounded-circle border border-2 border-white position-absolute end-0 bottom-0 p-1"></div>
                </div>
                <div class="ms-3">                                            
                    <h6 class="mb-0"> {{Auth()->user()->name}} </h6>                    
                    <span>Role</span>
                </div>
            </div>
        @endif
        <div class="navbar-nav w-100">
            <a href="{{route('home')}}" class="nav-item nav-link active"><i class="fa fa-tachometer-alt me-2"></i>Inicio</a>
            @if (!Auth::Check())
                <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="far fa-file-alt me-2"></i>Facturas</a>
                    <div class="dropdown-menu bg-transparent border-0">
                        <a href="{{route('invoices.create')}}" class="dropdown-item">Crear nueva factura</a>
                        
                        {{-- <a href="#" class="dropdown-item">Other Elements</a> --}}
                    </div>
                </div>
            @else
            <div class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="far fa-file-alt me-2"></i>Facturas</a>
                <div class="dropdown-menu bg-transparent border-0">
                    <a href="{{route('invoices.index')}}" class="dropdown-item">Consultar</a>                    
                </div>
            </div>
            @endif
            {{-- <a href="#" class="nav-item nav-link"><i class="fa fa-th me-2"></i>Widgets -</a>
            <a href="#" class="nav-item nav-link"><i class="fa fa-keyboard me-2"></i>Forms -</a>
            <a href="#" class="nav-item nav-link"><i class="fa fa-table me-2"></i>Tables -</a>
            <a href="#" class="nav-item nav-link"><i class="fa fa-chart-bar me-2"></i>Charts -</a>
            <div class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="far fa-file-alt me-2"></i>Pages -</a>
                <div class="dropdown-menu bg-transparent border-0">
                    <a href="#" class="dropdown-item">Sign In</a>
                    <a href="#" class="dropdown-item">Sign Up</a>
                    <a href="#" class="dropdown-item">404 Error</a>
                    <a href="#" class="dropdown-item">Blank Page</a>
                </div>
            </div> --}}
        </div>
    </nav>
</div>
