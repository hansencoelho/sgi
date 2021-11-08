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
        <script type="text/javascript" language="javascript" src="{{ asset('js/views/usuario.js') }}"></script>
    </head>

    <body>

<div class="text-center">
  <hr>
  <h1 class="h3 mb-3 font-weight-normal">@if(isset($titulo)){{$titulo}}@endif <i class="fa fa-key fa-lg"></i></h1>
  <hr>
</div>

      <!-- Formulário -->
      <form id="formulario_alterar_senha" >

      @csrf

      {{-- Opções --}}
      <div class="col-md-12">
        <!-- Linha 1 - Dados Registro -->
        <div class="row mb-3 g-3" id="div_senha">
          <div class="col-sm-2">
            <label for="senha" class="form-label">Senha:</label>
            <input type="password" class="form-control form-control-sm" id="senha" name="senha" class="form-control form-control-sm"   required>
          </div>
          <div class="col-sm-2">
            <label for="confirmacao_senha" class="form-label">Confirmação de Senha:</label>
            <input type="password" class="form-control form-control-sm" id="confirmacao_senha" name="confirmacao_senha" class="form-control form-control-sm" required>
          </div>
        </div>
        <div class="row mb-3 g-3">
          <div class="col-sm-3">
            <div class="form-check form-switch">
              <label class="form-check-label" for="enviar_senha">Enviar Senha no E-mail do Usuário</label>
              <input class="form-control form-check-input" type="checkbox" id="enviar_senha" name="enviar_senha" checked>
            </div>
            <div class="form-check form-switch">
              <label class="form-check-label" for="alterar_senha">Alterar Senha no Primeiro Login</label>
              <input class="form-control form-check-input" type="checkbox" id="alterar_senha" name="alterar_senha" checked>
            </div>
          </div>
        </div>
        
      </div>
        
      <!-- Botões de Ação -->
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal"><i class="fa fa-sign-out-alt fa-lg"></i> Sair</button>
        <button type="button" id="button_modal" onclick="" class="btn btn-sm btn-success"><i class="fa fa-save fa-lg"></i> Salvar</button>
      </div>
        
      </form>
    </body>
</html>