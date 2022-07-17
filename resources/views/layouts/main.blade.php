<!-- Spinner Start -->
<div class="container-fluid-xxl">

<div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
    <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
        <span class="sr-only">Cargando...</span>
    </div>
</div>
<!-- Spinner End -->

<!-- Sidebar Start -->
    @include('layouts.sidebar')
<!-- Sidebar End -->


<!-- Content Start -->
<div class="content">

    @include('layouts.navbar2')

    <div class="container-fluid">
        @yield('content')
    </div>

</div>
<!-- Content End -->


<!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
{{-- </div> --}}

</div>
@include('layouts.scripts')

@yield('my_scripts')




