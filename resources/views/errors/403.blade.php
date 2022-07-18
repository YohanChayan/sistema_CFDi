@extends('layouts.custom_links')

<div class="container-fluid pt-4 px-4">
    <div class="row vh-100 bg-light rounded align-items-center justify-content-center mx-0">
        <div class="col-md-6 text-center p-4">
            <i class="bi bi-exclamation-triangle display-1 text-primary"></i>
            {{-- <h1 class="display-1 fw-bold">Error</h1> --}}
            <h1 class="mb-4">Usted no tiene acceso a esta página</h1>
            {{-- <p class="mb-4">Comuníquese con control escolar para dar solución a esta problemática.</p> --}}
            <a class="btn btn-primary rounded-pill py-3 px-5" href="{{route('home')}}">Inicio</a>
        </div>
    </div>
</div>
