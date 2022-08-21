@foreach($providers as $provider)
    <option id="{{ $provider->id }}" value="{{ $provider->rfc }}"></option>
@endforeach