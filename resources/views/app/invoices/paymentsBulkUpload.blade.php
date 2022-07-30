@extends('layouts.app')

@section('my_scripts')
  <script src="{{ asset("js/administrador/facturas/facturas.js") }}"></script>
@endsection

@section('content')

<div class="container-fluid pt-4 px-4">
  <div class="bg-light rounded p-4">
    <div class="d-flex align-items-center justify-content-between">
      <h2 class="mb-0">Facturas</h2>
      <div id="alert_user">
        <div class="alert alert-warning alert-dismissible fade show mt-3" role="alert">
          <i class="fa fa-exclamation-circle me-2"></i>Digite el RFC de su empresa y del proveedor
        </div>
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

    <div class="table-responsive" id="table_pending_payments">
      <table class="table text-start align-middle table-bordered mb-0">
        <thead>
          <tr class="text-dark">
            <th scope="col" class="text-center">UUID</th>
            <th scope="col" class="text-center">Folio</th>
            <th scope="col" class="text-center">Nombre</th>
            <th scope="col" class="text-center">Importe</th>
            <th scope="col" class="text-center">Saldo</th>
          </tr>
        </thead>
        <tbody>
          @foreach($invoices as $inv)
            <tr>
              <td class="text-center">{{ $inv->uuid }}</td>
              <td class="text-center">{{ $inv->folio }}</td>
              <td class="text-center">{{ $inv->provider->nombre }}</td>
              <td class="text-center">${{ number_format($inv->total, 2) }}</td>
              <td class="text-center">${{ number_format($inv->payments->sum('payment'), 2) }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>

@endsection
