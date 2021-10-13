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

        <!-- Bootstrap JS -->
        <script src="{{ asset('/js/bootstrap.bundle.min.js') }}"></script>

        <!-- Font Awesome 6 -->
        <script src="{{ asset('/js/font-awesome-6.js') }}" crossorigin="anonymous"></script>

        <!-- jQuery library -->
        <script type="text/javascript" language="javascript" src="{{ asset('js/jquery-3.6.0.min.js') }}"></script>

        <!-- jQueryUI library JS -->
        <script type="text/javascript" language="javascript" src="{{ asset('js/jquery-ui-1.13.0.min.js') }}"></script>
        
        <!-- jQueryUI library CSS -->
        <link href="{{ asset('/css/jquery-ui-1.13.0.min.css') }}" rel="stylesheet">
    </head>

    <body>

        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">

            <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <img src="{{ asset('img/logo_menu.png') }}" width="73.32" height="35">

                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#"><i class="fas fa-chart-area fa-lg"></i> Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('registro')}}"><i class="fas fa-book fa-lg"></i> Registro</a>
                    </li>

                    <!-- Administração -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle active" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">
                        <i class="fa fa-cogs fa-lg"></i>
                        Administração
                        </a>

                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="{{ route('usuario') }}" style="padding-left: 5px; padding-right: 15px">
                                    <div class="d-sm-flex flex-row bd-highlight">
                                        <div class="bd-highlight" style="text-align: center; width: 3em">
                                        <i class="fas fa-user fa-lg"></i></div>
                                        <div class="flex-grow-1 bd-highlight">Usuário</div>
                                    </div>
                                </a>
                            </li>
                        </ul>
                        <!-- @can('grupo_usuario-view')
                        <a class="dropdown-item" href="{{ route('grupo_usuario') }}" style="padding-left: 5px; padding-right: 15px">
                            <div class="d-sm-flex flex-row bd-highlight">
                                <div class="bd-highlight" style="text-align: center; width: 3em">
                                <i class="fas fa-users fa-lg"></i></div>
                                <div class="flex-grow-1 bd-highlight">Grupo de Usuário</div>
                            </div>
                        </a>
                        @endcan 

                        @can('regional-view')
                        <a class="dropdown-item" href="{{ route('regional') }}" style="padding-left: 5px; padding-right: 15px">
                            <div class="d-sm-flex flex-row bd-highlight">
                                <div class="bd-highlight" style="text-align: center; width: 3em">
                                <i class="fas fa-city fa-lg"></i></div>
                                <div class="flex-grow-1 bd-highlight">Regional</div>
                            </div>
                        </a>
                        @endcan 

                        @can('velocidadelink-view')
                        <a class="dropdown-item" href="{{ route('velocidadelink') }}" style="padding-left: 5px; padding-right: 15px">
                            <div class="d-sm-flex flex-row bd-highlight">
                                <div class="bd-highlight" style="text-align: center; width: 3em">
                                <i class="fas fa-ethernet fa-lg"></i></div>
                                <div class="flex-grow-1 bd-highlight">Velocidade de Link</div>
                            </div>
                        </a>
                        @endcan 

                        @can('planolinha-view')
                        <a class="dropdown-item" href="{{ route('planolinha') }}" style="padding-left: 5px; padding-right: 15px">
                            <div class="d-sm-flex flex-row bd-highlight">
                                <div class="bd-highlight" style="text-align: center; width: 3em">
                                <i class="fas fa-file-invoice-dollar fa-lg"></i></div>
                                <div class="flex-grow-1 bd-highlight">Plano de Linha Móvel</div>
                            </div>
                        </a>
                        @endcan -->
                    </li>
                </ul>
                    
            </div>
            <div>
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle active" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="true">
                            <i class="fa fa-user fa-lg"></i>    
                            {{ Auth::user()->name }} 
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                            <li>
                            <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" style="padding-left: 5px; padding-right: 15px">
                                <div class="d-sm-flex flex-row bd-highlight">
                                    <div class="bd-highlight" style="text-align: center; width: 3em">
                                    <i class="fas fa-power-off fa-lg"></i></div>
                                    <div class="flex-grow-1 bd-highlight">Sair</div>
                                </div>
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>

    </body>

</html>

