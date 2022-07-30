@foreach($invoices as $invoice)
    <option id="{{ $invoice->provider->id }}" value="{{ $invoice->provider->rfc }}"></option>
@endforeach