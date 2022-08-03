@extends('layouts.app')

@section('my_css')
  <style>
    @media only screen and (min-width: 1200px) {
      .img-carousel {
        height: 600px;
        max-height: 600px;
      }
    }

    @media only screen and (min-width: 992px && max-width: 1999px) {
      .img-carousel {
        height: 550px;
        max-height: 550px;
      }
    }

    @media only screen and (min-width: 768px && max-width: 991px) {
      .img-carousel {
        height: 400px;
        max-height: 400px;
      }
    }

    @media only screen and (max-width: 767px) {
      .img-carousel {
        height: 250px;
        max-height: 250px;
      }
    }
  </style>
@endsection

@section('content')

<h1 class="text-center mt-2">Frutioro</h1>

<div class="col-md-8 m-auto mt-5 mb-5 shadow-lg">
  <div id="carouselControls" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-indicators">
      <button type="button" data-bs-target="#carouselControls" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
      <button type="button" data-bs-target="#carouselControls" data-bs-slide-to="1" aria-label="Slide 2"></button>
      <button type="button" data-bs-target="#carouselControls" data-bs-slide-to="2" aria-label="Slide 3"></button>
      <button type="button" data-bs-target="#carouselControls" data-bs-slide-to="3" aria-label="Slide 4"></button>
    </div>
    <div class="carousel-inner">
      <div class="carousel-item active" data-bs-interval="5000">
        <img src="{{ asset('imagenes/fruti-1.jpg') }}" class="d-block img-fluid img-carousel" alt="...">
      </div>
      <div class="carousel-item" data-bs-interval="5000">
        <img src="{{ asset('imagenes/fruti-2.jpg') }}" class="d-block img-fluid img-carousel" alt="...">
      </div>
      <div class="carousel-item" data-bs-interval="5000">
        <img src="{{ asset('imagenes/fruti-3.jpg') }}" class="d-block img-fluid img-carousel" alt="...">
      </div>
      <div class="carousel-item" data-bs-interval="5000">
        <img src="{{ asset('imagenes/fruti-4.jpg') }}" class="d-block img-fluid img-carousel" alt="...">
      </div>
      {{-- <div class="carousel-item">
        <img src="{{ asset('imagenes/fruti-5.jpg') }}" class="d-block w-100" alt="...">
      </div> --}}
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselControls" data-bs-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselControls" data-bs-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Next</span>
    </button>
  </div>
</div>
@endsection
