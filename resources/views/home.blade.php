@extends('layouts.app')

@section('content')

<h1 class="text-center mt-2">Frutioro</h1>


<div class="col-md-8 m-auto mt-5 mb-5 shadow-lg">
  <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
      <div class="carousel-inner">
        <div class="carousel-item active">
          <img src="{{ asset('imagenes/fruti-1.jpg') }}" class="d-block w-100" alt="...">
        </div>
        <div class="carousel-item">
          <img src="{{ asset('imagenes/fruti-2.jpg') }}" class="d-block w-100" alt="...">
        </div>
        <div class="carousel-item">
          <img src="{{ asset('imagenes/fruti-3.jpg') }}" class="d-block w-100" alt="...">
        </div>
        <div class="carousel-item">
          <img src="{{ asset('imagenes/fruti-4.jpg') }}" class="d-block w-100" alt="...">
        </div>
        {{-- <div class="carousel-item">
          <img src="{{ asset('imagenes/fruti-5.jpg') }}" class="d-block w-100" alt="...">
        </div> --}}
      </div>
      <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
      </button>
  </div>
</div>
@endsection
