<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Inicio de Sesión</title>
    @include('layouts.custom_links')
</head>
<body>
    <div class="container-xxl position-relative bg-white d-flex p-0">
        <!-- Spinner Start -->
        <div id="spinner" class="bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <!-- Spinner End -->

        <!-- Sign In Start -->
        <div class="container-fluid">
            <div class="row h-100 align-items-center justify-content-center" style="min-height: 100vh;">
                <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                    <div class="bg-light rounded p-4 p-sm-5 my-4 mx-3">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <a href="#" class="">
                                <h3 class="text-primary">Frutioro</h3>
                            </a>
                            <h3>Iniciar Sesión</h3>
                        </div>
                        <form method="POST" action="{{ route('loginProvider') }}">
                            @csrf
                            <div class="form-floating mb-3">
                                <input id="rfc" type="string" class="form-control @error('rfc') is-invalid @enderror" name="rfc" value="{{ old('rfc') }}" required autocomplete="rfc" autofocus placeholder="RFC">
                                
                                <label for="rfc">{{ __('RFC') }}</label>
                                @error('rfc')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-floating mb-4">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Contraseña">
                                <label for="email">{{ __('Contraseña') }}</label>
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                {{-- <label for="floatingPassword">Password</label> --}}
                            </div>
                            <div class="d-flex align-items-center justify-content-between mb-4">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="exampleCheck1">
                                    <label class="form-check-label" for="exampleCheck1">Recordar Contraseña</label>
                                </div>
                                <a href="#">Olvidé mi contraseña</a>
                            </div>
                            <button type="submit" class="btn btn-primary py-3 w-100 mb-4">Iniciar Sesión</button>
                            {{-- <p class="text-center mb-0">Don't have an Account? <a href="">Sign Up</a></p> --}}
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Sign In End -->
    </div>
    @include('layouts.scripts')
</body>
</html>