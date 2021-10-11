<!doctype html>
<html lang="pt">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="Rodrigo Hansen Coelho">
        <link rel="icon" href="{{ asset('/img/favicon.ico') }}">

        <title>{{ 'SGI' }}</title>

        <!-- Bootstrap CSS -->
        <link href="{{ asset('/css/bootstrap.min.css') }}" rel="stylesheet">

        <!-- CSS Personalizado da Página -->
        <link href="{{ asset('/css/signin.css') }}" rel="stylesheet">

        <!-- Bootstrap JS -->
        <script src="{{ asset('/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('/js/font-awesome-6.js') }}" crossorigin="anonymous"></script>
    </head>

    <body class="text-center" style="background-color: #004375">
        <form class="form-signin" method="POST" action="{{ route('login') }}">
        @csrf
            <img class="mb-3" src="{{ asset('/img/logo_menu.png') }}" alt="" width="300" height="143.19">
            <input name="email" id="email" type="text" class="form-control @error('email') is-invalid @enderror" placeholder="E-mail" value="{{ old('email') }}" required autocomplete="email" autofocus>
            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror

            <input name="password" id="password" type="password" class="form-control" placeholder="Senha" required autocomplete="current-password">
            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror

            <!-- <div class="checkbox mb-3">
            <label>
                <input type="checkbox" value="remember-me"> Lembrar-me
            </label>
            </div> -->
            
            <div class="d-grid gap-2">
            <button class="btn btn-lg btn-success btn-block" type="submit">
            <i class="fas fa-unlock-alt"></i>
            Entrar</button>
            </div>

            <!-- @if (Route::has('passwords.request'))
                <a class="btn btn-link" href="{{ route('password.request') }}">
                    {{ __('Esqueceu sua senha?') }}
                </a>
            @endif

            @if (Route::has('register'))
                <a class="btn btn-link" href="{{ route('register') }}">
                    {{ __('Registrar') }}
                </a>
            @endif -->
            
            <p class="mt-5 mb-3" style="color: white;">&copy; {{ date('Y') }}</p>
        </form>
    </body>
</html>