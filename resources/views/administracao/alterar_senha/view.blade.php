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

        <script type="text/javascript" language="javascript" src="{{ asset('js/letras_numeros.js') }}"></script>
        <script type="text/javascript" language="javascript" src="{{ asset('js/views/alterar_senha.js') }}"></script>
    </head>

    <body>

<div class="text-center">
  <hr>
  <h1 class="h3 mb-3 font-weight-normal">@if(isset($titulo)){{$titulo}}@endif <i class="fa fa-key fa-lg"></i></h1>
  <hr>
</div>

      <!-- Formulário -->
      <div id="div_carregamento" class="spinner-border text-primary" role="status" style="display: none;">
            <span class="visually-hidden ms-auto">Loading...</span>
          </div>
      <!-- Formulário -->
      <form id="formulario_usuario" action="{{ route ('alterar_senha.update') }}" method="post">
        @csrf
        <!-- Linha 1 - Dados Registro -->
        <div class="row mb-3 g-3">
          <input type="hidden" id="id" name="id" class="form-control form-control-sm" value="{{ Auth::User()->id }}">
        </div>

          <!-- Linha 2 - Senha -->
        <div class="row mb-3 g-3">
         
          <div class="col-sm-4">
            <label for="senha" class="form-label">Nova Senha:</label>
            <div class="input-group input-group-sm mb-3">
              <input type="password" class="form-control" id="senha" name="senha" onkeyup="validar_forca_senha()" placeholder="Nova Senha" aria-label="Nova Senha" aria-describedby="senha_visivel">
              <button class="btn btn-outline-primary" type="button" id="senha_visivel"><i class="fa fa-eye" aria-hidden="true"></i></button>
            </div>
            <div id="div_forca_senha" style="display: none;"></div>
          </div>
    
        </div>

        <div class="row mb-3 g-3">
          <div class="col-sm-3">
            <div class="form-check form-switch" id="div_enviar_senha">
              <label class="form-check-label" for="enviar_senha">Enviar Senha no E-mail</label>
              <input class="form-control form-check-input" type="checkbox" id="enviar_senha" name="enviar_senha" checked>
            </div>
          </div>
        </div>
        
      <!-- Botões de Ação -->
      <div class="modal-footer">
        @if (Auth::User()->primeiro_login == 0) <a href="{{ url()->previous() }}"><button type="button" class="btn btn-sm btn-danger" ><i class="fa fa-sign-out-alt fa-lg"></i> Voltar</button></a> @endif
        <button type="submit" id="button_modal" class="btn btn-sm btn-success" disabled><i class="fa fa-save fa-lg"></i> Salvar</button>
      </div>
        
      </form>
    </body>
</html>