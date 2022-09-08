@extends('layouts.app')

@section('my_scripts')
  <script src="{{ asset("js/administrador/invoices/payments.js") }}"></script>
@endsection

@section('content')

<div class="container-fluid pt-4 px-4">
  <div class="bg-light rounded p-4">
    <div class="d-flex align-items-center justify-content-between">
      <h2 class="mb-0">Saldos pendientes</h2>
      <div class="alert alert-warning alert-dismissible fade show mt-3" role="alert" id="div_alert">
        <i class="fa fa-exclamation-circle me-2" id="icon_alert"></i><span id="text_alert">Ingrese el RFC de su empresa y del proveedor</span>
      </div>
    </div>

    <br>

    <div class="row">
      <div class="col-lg-4">
        <label for="owner">Empresa</label>
        <input class="form-control" type="text" list="owners_list" name="owner" id="owner" onchange="changeOwner();">
        <datalist id="owners_list">
          @foreach($owners as $owner)
            <option id="{{ $owner->id }}" value="{{ $owner->rfc }}"></option>
          @endforeach
        </datalist>
      </div>
      <div class="col-lg-4">
        <label for="provider">Proveedor</label>
        <input class="form-control" type="text" list="providers_list" name="provider" id="provider" onchange="changeProvider();">
        <datalist id="providers_list">
          
        </datalist>
      </div>
    </div>

    <br>

    <div id="table_pending_payments">
      
    </div>
  </div>

@endsection
