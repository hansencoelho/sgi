@extends("layouts.main")

@section("content")

<meta name="_token" content="{{ csrf_token() }}">

<script type="text/javascript" language="javascript" src="{{ asset('js/letras_numeros.js') }}"></script>
<script type="text/javascript" language="javascript" src="{{ asset('js/views/usuario.js') }}"></script>

<div class="text-center">
  <h1 class="h3 mb-3 font-weight-normal">@if(isset($titulo)){{$titulo}}@endif <i class="fa fa-user fa-lg"></i></h1>
  <hr>
</div>

{{-- Opções --}}
<div class="col-md-12">
  <div class="row row-cols-2">
    <div class="col-md-10">

      @can('usuario-create')
      <button type="button" onclick="create_usuario()" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#modal_usuario">
      <i class="fa fa-plus fa-lg"></i> Novo</button>
      @endcan
      
      <button class="btn btn-sm btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#pesquisar" aria-expanded="false">
      <i class="fa fa-search fa-lg"></i> Pesquisar</button>

    </div>  

    <div class="col-md-2 aling-left">
      <h6 style="text-align: right;">Resultado(s): @if (isset($contagem)) {{ $contagem }} @endif </h6>
    </div>
  </div>
</div>

{{-- Pesquisa --}}
<div class="@if(isset($pesquisa_retorno)){{'collapse show'}}@else{{'collapse'}}@endif" id="pesquisar">
    <div class="card card-body">
        <form id="formulario_pesquisa" class="row g-12" method="POST" action="{{ route('usuario.find') }}">
        @csrf

            <div class="col-sm-2">
                <select id="pesquisa_opcao" name="pesquisa_opcao" class="form-select form-select-sm">
                <option value="1" @if (isset($pesquisa_retorno) && $pesquisa_retorno['pesquisa_opcao'] == 1 ) selected="selected" @endif>Nome</option>
                <option value="2" @if (isset($pesquisa_retorno) && $pesquisa_retorno['pesquisa_opcao'] == 2 ) selected="selected" @endif>E-mail</option>
                <option value="3" @if (isset($pesquisa_retorno) && $pesquisa_retorno['pesquisa_opcao'] == 3 ) selected="selected" @endif>Função do Usuário</option>
                </select>
            </div>
            
            <div class="col-md-4">
                <!-- <label for="pesquisa_texto" class="form-label">Pesquisa:</label> -->
                <input type="text" id="pesquisa_texto" name="pesquisa_texto" value="@if (isset($pesquisa_retorno)){{ $pesquisa_retorno['pesquisa_texto'] }}@endif" class="form-control form-control-sm" placeholder="Pesquisar..." aria-label="Recipient's username" aria-describedby="button-addon2">
            </div>
            
            <div class="col-md-2">
                <!-- <label for="botao_pesquisar" class="form-label">.</label> -->
                <button class="btn btn-primary btn-sm" type="submit" id="botao_pesquisar">
                <i class="fa fa-search fa-lg"></i></button>
            </div>
          
      </div>
    </form>
  </div>
</div>
  
<hr>

{{-- Lista de Conteúdo --}}
<div class="table-responsive-sm" style="font-size: 14px;">
  <table class="table table-sm table-striped">
    <thead class="thead table-dark">
      <tr>
        <th scope="col">Nome</th>
        <th scope="col">E-mail</th>
        <th scope="col">Função do Usuário</th>
        <th scope="col" style="text-align: center;">Ação</th>
      </tr>
    </thead>

    <tbody>
      @forelse($usuario as $tb_usuario)
      <tr id="usuario_{{$tb_usuario->ID}}">
        <td>{{ $tb_usuario->NOME }}</td>
        <td>{{ $tb_usuario->EMAIL }}</td>
        <td>{{ $tb_usuario->FUNCAO }}</td>
        <td style="text-align: center;"> 
        <a href="#" onclick="show_usuario({{ $tb_usuario->ID }})" data-bs-toggle="modal" data-bs-target="#modal_usuario"> <i class="fa fa-eye fa-lg"></i></a>
        <a href="#" onclick="delete_usuario({{ $tb_usuario->ID }})"> <i style="color: #dc3545;" class="fa fa-trash-alt fa-lg"></i></a>
        </td>
      </tr>
      @empty
      @endforelse
    </tbody>
  </table>
</div>

<div class="modal fade" id="modal_usuario" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-fullscreen">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="titulo_modal">Cadastro de Usuario</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body ui-front">
          <div id="div_carregamento" class="spinner-border text-primary" role="status" style="display: none;">
            <span class="visually-hidden ms-auto">Loading...</span>
          </div>
      <!-- Formulário -->
      <form id="formulario_usuario">

        @csrf
      <!-- class="was-validated" -->

        <!-- Linha 1 - Dados Registro -->
        <div class="row mb-3 g-3">
          <input type="hidden" id="id" name="id" class="form-control form-control-sm" value="">
          <div class="col-sm-4">
            <label for="nome" class="form-label">Nome:</label>
            <input type="text" id="nome" name="nome" class="form-control form-control-sm" required>
          </div>
          <div class="col-sm-4">
            <label for="email" class="form-label">E-mail:</label>
            <input type="email" id="email" name="email" class="form-control form-control-sm" required>
          </div>
          <!-- <div class="col-sm-3">
            <label for="usuario" class="form-label">Usuário:</label>
            <input type="text" id="usuario" name="usuario" class="form-control form-control-sm" required>
          </div> -->
          <div class="col-sm-4">
          <input type="hidden" id="id_funcao" name="id_funcao" class="form-control form-control-sm" value="">
            <label for="funcao" class="form-label">Função:</label>
            <input type="text" id="funcao" name="funcao" class="form-control form-control-sm" required>
          </div>
        </div>

          <!-- Linha 1 - Dados Registro -->
        <div class="row mb-3 g-3">
         
          <div class="col-sm-4">
            <label for="senha" class="form-label">Senha:</label>
            <div class="input-group input-group-sm mb-3">
              <input type="password" class="form-control" id="senha" name="senha" onkeyup="validar_forca_senha()" placeholder="Senha" aria-label="Senha" aria-describedby="senha_visivel">
              <button class="btn btn-outline-primary" type="button" id="senha_visivel"><i class="fa fa-eye" aria-hidden="true"></i></button>
            </div>

            <div id="div_forca_senha" style="display: none;"></div>
            <!-- <div id="div_erro_forca_senha" style="display: none;"></div> -->

          </div>
    
        </div>

        <div class="row mb-3 g-3">
          <div class="col-sm-3">
            <div class="form-check form-switch" id="div_enviar_senha">
              <label class="form-check-label" for="enviar_senha">Enviar Senha no E-mail do Usuário</label>
              <input class="form-control form-check-input" type="checkbox" id="enviar_senha" name="enviar_senha" checked>
            </div>
            <div class="form-check form-switch">
              <label class="form-check-label" for="alterar_senha">Alterar Senha no Primeiro Login</label>
              <input class="form-control form-check-input" type="checkbox" id="alterar_senha" name="alterar_senha" checked>
            </div>
          </div>
        </div>
        <!-- </div> -->
        
         <!-- Botões de Ação -->
         <div class="modal-footer">
          <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal"><i class="fa fa-sign-out-alt fa-lg"></i> Sair</button>
          <button type="button" id="button_modal" id="botao_save" onclick="" class="btn btn-sm btn-success"><i class="fa fa-save fa-lg"></i> Salvar</button>
        </div>
        
      </form>

      </div>
    </div>
  </div>
@endsection
