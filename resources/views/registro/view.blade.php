@extends("layouts.main")

@section("content")

<meta name="_token" content="{{ csrf_token() }}">

<script type="text/javascript" language="javascript" src="{{ asset('js/views/registro.js') }}"></script>

<div class="text-center">
  <h1 class="h3 mb-3 font-weight-normal">@if(isset($titulo)){{$titulo}}@endif <i class="fa fa-book fa-lg"></i></h1>
  <hr>
</div>

{{-- Opções --}}
<div class="col-md-12">
  <div class="row row-cols-2">
    <div class="col-md-10">

      <button type="button" onclick="create_registro()" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#modal_registro">
      <i class="fa fa-plus fa-lg"></i> Novo</button>

      <button class="btn btn-sm btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#pesquisar" aria-expanded="false">
      <i class="fa fa-search fa-lg"></i> Pesquisa</button>
      
      <button onclick="exportar();" target="_blank" type="button" class="btn btn-sm btn-secondary">
      <i class="fa fa-download fa-lg"></i> Exportar para Excel</button>

    </div>  

    <div class="col-md-2 aling-left">
      <h6 style="text-align: right;" id="contador_resultado">Resultado(s): @if (isset($contagem)) {{ $contagem }} @endif </h6>
    </div>
  </div>
</div>

{{-- Pesquisa --}}

<div class="@if(isset($pesquisa_retorno)){{'collapse show'}}@else{{'collapse'}}@endif" id="pesquisar">
    <div class="card card-body">
        <form id="formulario_pesquisa" class="row g-12" method="POST" action="{{ route('registro.find') }}">
        {{ csrf_field() }}
        
            <div class="col-sm-2">
              <select id="pesquisa_tipo_registro" name="pesquisa_tipo_registro" class="form-select form-select-sm">
                <option value="">Tipo de Registro...</option>
                @forelse($tipo_registro as $tb_tipo_registro)
                <option value="{{ $tb_tipo_registro['id'] }}" @if ( isset($pesquisa_retorno) && $tb_tipo_registro['id'] == $pesquisa_retorno['pesquisa_tipo_registro'] ) selected="selected" @endif>{{ $tb_tipo_registro['descricao'] }}</option>
                @empty
                @endforelse
              </select>
            </div>

            <div class="col-sm-2">
              <select id="pesquisa_tipo_local_registro" name="pesquisa_tipo_local_registro" class="form-select form-select-sm">
                <option value="">Tipo do Local de Registro...</option>
                @forelse($tipo_local_registro as $tb_tipo_local_registro)
                <option value="{{ $tb_tipo_local_registro['id'] }}" @if ( isset($pesquisa_retorno) && $tb_tipo_local_registro['id'] == $pesquisa_retorno['pesquisa_tipo_local_registro'] ) selected="selected" @endif>{{ $tb_tipo_local_registro['descricao'] }}</option>
                @empty
                @endforelse
              </select>
            </div>

            <div class="col-sm-2">
                <select id="pesquisa_opcao" name="pesquisa_opcao" class="form-select form-select-sm">
                <option value="1" @if (isset($pesquisa_retorno) && $pesquisa_retorno['pesquisa_opcao'] == 1 ) selected="selected" @endif>Nome</option>
                <option value="2" @if (isset($pesquisa_retorno) && $pesquisa_retorno['pesquisa_opcao'] == 2 ) selected="selected" @endif>Sobrenome</option>
                <option value="3" @if (isset($pesquisa_retorno) && $pesquisa_retorno['pesquisa_opcao'] == 3 ) selected="selected" @endif>Local de Registro</option>
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
    <!-- <thead class="thead" style="background-color: #004375; color: white;"> -->
    <thead class="thead table-dark">
      <tr>
        <th scope="col">Tipo Registro</th>
        <th scope="col">Nome</th>
        <th scope="col">Sobrenome</th>
        <th scope="col">Nome do Pai</th>
        <th scope="col">Nome da Mãe</th>
        <th scope="col">Livro</th>
        <th scope="col">Folha</th>
        <th scope="col">Termo</th>
        <th scope="col" style="text-align: center;">Ação</th>
      </tr>
    </thead>

    <tbody>
      @forelse($registro as $tb_registro)
      <tr id="registro_{{$tb_registro->id}}">
        <td>{{ $tb_registro->tipo_registro }}</td>
        <td>{{ $tb_registro->nome }}</td>
        <td>{{ $tb_registro->sobrenome }}</td>
        <td>{{ $tb_registro->nome_pai }}</td>
        <td>{{ $tb_registro->nome_mae }}</td>
        <td>{{ $tb_registro->livro }}</td>
        <td>{{ $tb_registro->folha }}</td>
        <td>{{ $tb_registro->termo }}</td>
        <td style="text-align: center;"> 
        <a href="#" onclick="show_registro({{ $tb_registro->id }})" data-bs-toggle="modal" data-bs-target="#modal_registro"> <i class="fa fa-eye fa-lg"></i></a>
        <a href="#" onclick="delete_registro({{ $tb_registro->id }})"> <i style="color: #dc3545;" class="fa fa-trash-alt fa-lg"></i></a></td>
        

        {{-- <a href="{{ route('usuario.show', ['id_usuario' => $tb_usuario->ID_USUARIO]) }}" target="_blank"><i class="fa fa-eye fa-lg"></i></a> </td> --}}
        {{-- <td style="vertical-align: middle; text-align: center;"><a href="{{ route('admin_user.show', ['id_user' => $tb_usuario->ID_USUARIO]) }}" target="_blank"><i class="fa fa-save fa-lg"></i></a> </td> --}}
      </tr>
      @empty
      @endforelse
    </tbody>
  </table>
</div>

<div class="modal fade" id="modal_registro" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-fullscreen">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="titulo_modal">Cadastro de Registro</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body ui-front">
          <div id="div_carregamento" class="spinner-border text-primary" role="status" style="display: none;">
            <span class="visually-hidden ms-auto">Loading...</span>
          </div>
      <!-- Formulário -->
      <form id="formulario_registro" class="was-validated">

        <!-- Linha 1 - Dados Registro -->
        <div class="row mb-3 g-3">
        <input type="hidden" id="id" name="id" class="form-control form-control-sm" value="">
          <div class="col-sm-2">
              <label for="tipo_registro" class="form-label">Tipo de Registro:</label>
              <select id="tipo_registro" name="tipo_registro" class="form-select form-select-sm" required>
              </select>
          </div>
          <div class="col-sm-2">
            <label for="tipo_local_registro" class="form-label">Tipo de Local de Registro:</label>
            <select id="tipo_local_registro" name="tipo_local_registro" class="form-select form-select-sm" required>
            </select>
          </div>
          <div class="col-sm-5">
            <label for="local_registro" class="form-label">Local de Registro:</label>
            <input type="text" id="local_registro" name="local_registro" class="form-control form-control-sm" required>
          </div>
          <div class="col-sm-1">
            <label for="livro" class="form-label">Livro:</label>
            <input type="text" id="livro" name="livro" class="form-control form-control-sm" required>
          </div>
          <div class="col-sm-1">
            <label for="folha" class="form-label">Folha:</label>
            <input type="text" id="folha" name="folha" class="form-control form-control-sm" required>
          </div>
          <div class="col-sm-1">
            <label for="termo" class="form-label">Termo:</label>
            <input type="text" id="termo" name="termo" class="form-control form-control-sm" required>
          </div>
        </div>

        <!-- Linha 3 - Dados Gerais -->
        <div class="row mb-3 g-3">
          <div class="col-sm-2">
            <label id="label_data_fato" for="data_fato" class="form-label">Data Fato:</label>
            <input type="date" id="data_fato" name="data_fato" class="form-control form-control-sm" required>
          </div>
          <div class="col-sm-2">
            <label id="label_data_registro" for="data_registro" class="form-label">Data Registro Cartório:</label>
            <input type="date" id="data_registro" name="data_registro" class="form-control form-control-sm" required>
          </div>
          <div class="col-sm-1">
            <label for="uf" class="form-label">UF:</label>
            <input type="hidden" id="id_uf" name="id_uf" value="" class="form-control form-control-sm" required>
            <input type="text" id="uf" name="uf" value="" class="form-control form-control-sm" required>
          </div>
          <div class="col-md-3">
            <label for="cidade" class="form-label">Cidade:</label>
            <input type="hidden" id="id_cidade" name="id_cidade" class="form-control form-control-sm" required>
            <input type="text" id="cidade" name="cidade" class="form-control form-control-sm" disabled="true" required>
          </div>
          <div id="div_declarante" class="col-sm-2">
            <label for="declarante" class="form-label">Declarante:</label>
            <select id="declarante" name="declarante" class="form-select form-select-sm" required>
            </select>
          </div>
          <div id="div_avos_registrados" class="col-sm-2">
            <label for="avos_registrados" class="form-label">Possui Avós Registrados:</label>
            <select id="avos_registrados" name="avos_registrados" class="form-select form-select-sm" required>
              <option selected>Não</option>
              <option>Sim</option>
            </select>
          </div>
        </div>

        <!-- Linha 2 - Dados Pessoa -->
        <div class="row mb-3 g-3">
          <div id="div_declarante_terceiro" class="col-md-4" style="display: none;">
            <label for="declarante_terceiro" class="form-label">Declarante Terceiro:</label>
            <input type="text" id="declarante_terceiro" name="declarante_terceiro" class="form-control form-control-sm" required>
          </div>
        </div>
          
        <!-- Linha 2 - Dados Pessoa -->
        <div class="row mb-3 g-3"> 
          <div class="col-sm-2">
            <label for="nome" class="form-label">Nome:</label>
            <input type="text" id="nome" name="nome" class="form-control form-control-sm" required>
          </div>
          <div class="col-sm-4">
            <label for="sobrenome" class="form-label">Sobrenome:</label>
            <input type="text" id="sobrenome" name="sobrenome" class="form-control form-control-sm" required>
          </div>
          <div id="div_nacionalidade_sobrenome" class="col-sm-2">
            <label for="nacionalidade_sobrenome" class="form-label">Nacionalidade Sobrenome:</label>
            <select id="nacionalidade_sobrenome" name="nacionalidade_sobrenome" class="form-select form-select-sm" required>
              <option selected>Brasileiro</option>
              <option>Italiano</option>
            </select>
          </div>
          <div id="div_estado_civil" class="col-sm-2" style="display: none;">
            <label id="label_estado_civil" for="estado_civil" class="form-label">Estado Civil:</label>
            <select id="estado_civil" name="estado_civil" class="form-select form-select-sm" required>
              <option selected>Solteiro(a)</option>
              <option>Casado(a)</option>
            </select>
          </div>
          <div id="div_religiao" class="col-sm-2">
            <label for="religiao" class="form-label">Religião:</label>
            <input type="hidden" id="id_religiao" name="id_religiao" class="form-control form-control-sm">
            <input type="text" id="religiao" name="religiao" class="form-control form-control-sm">
          </div>
        </div>

        
        <!-- Linha 4 - Dados Conjuge -->
        <div id="div_conjuge" class="row mb-3 g-3" style="display: none;">
          <div id="div_nome_conjuge" class="col-sm-2">
            <label for="nome_conjuge" class="form-label">Nome Conjuge:</label>
            <input type="text" id="nome_conjuge" name="nome_conjuge" class="form-control form-control-sm" required>
          </div>
          <div id="div_sobrenome_conjuge" class="col-sm-4">
            <label for="sobrenome_conjuge" class="form-label">Sobrenome Conjuge:</label>
            <input type="text" id="sobrenome_conjuge" name="sobrenome_conjuge" class="form-control form-control-sm" required>
          </div>
        </div>

        <!-- Linha 4 - Dados Conjuge -->

        <!-- Linha 4 - Dados Pais -->
        <div class="row mb-3 g-3"> 
          <div id="div_nome_pai" class="col-sm-2">
            <label for="nome_pai" class="form-label">Nome Pai:</label>
            <input type="text" id="nome_pai" name="nome_pai" class="form-control form-control-sm">
          </div>
          <div id="div_sobrenome_pai" class="col-sm-4">
            <label for="sobrenome_pai" class="form-label">Sobrenome Pai:</label>
            <input type="text" id="sobrenome_pai" name="sobrenome_pai" class="form-control form-control-sm">
          </div>
          <div id="div_nome_mae" class="col-sm-2">
            <label for="nome_mae" class="form-label">Nome Mãe:</label>
            <input type="text" id="nome_mae" name="nome_mae" class="form-control form-control-sm">
          </div>
          <div id="div_sobrenome_mae" class="col-sm-4">
            <label for="sobrenome_mae" class="form-label">Sobrenome Mãe:</label>
            <input type="text" id="sobrenome_mae" name="sobrenome_mae" class="form-control form-control-sm">
          </div>
        </div>

        <!-- Linha 5 - Dados Avós Paternos e Maternos -->
        <div class="row mb-3 g-3" id="div_avos" style="display: none;">

          <div id="div_nome_avo_paterno" class="col-sm-2">
            <label for="nome_avo_paterno" class="form-label">Nome Avô Paterno:</label>
            <input type="text" id="nome_avo_paterno" name="nome_avo_paterno" class="form-control form-control-sm">
          </div>

          <div id="div_sobrenome_avo_paterno" class="col-sm-4">
            <label for="sobrenome_avo_paterno" class="form-label">Sobrenome Avô Paterno:</label>
            <input type="text" id="sobrenome_avo_paterno" name="sobrenome_avo_paterno" class="form-control form-control-sm">
          </div>

          <div id="div_nome_avo_materno" class="col-sm-2">
            <label for="nome_avo_materno" class="form-label">Nome Avô Materno:</label>
            <input type="text" id="nome_avo_materno" name="nome_avo_materno" class="form-control form-control-sm">
          </div>

          <div id="div_sobrenome_avo_materno" class="col-sm-4">
            <label for="sobrenome_avo_materno" class="form-label">Sobrenome Avô Materno:</label>
            <input type="text" id="sobrenome_avo_materno" name="sobrenome_avo_materno" class="form-control form-control-sm">
          </div>

          <div id="div_nome_avo_paterna" class="col-sm-2">
            <label for="nome_avo_paterna" class="form-label">Nome Avó Paterna:</label>
            <input type="text" id="nome_avo_paterna" name="nome_avo_paterna" class="form-control form-control-sm">
          </div>

          <div id="div_sobrenome_avo_paterna" class="col-sm-4">
            <label for="sobrenome_avo_paterna" class="form-label">Sobrenome Avó Paterna:</label>
            <input type="text" id="sobrenome_avo_paterna" name="sobrenome_avo_paterna" class="form-control form-control-sm">
          </div>

          <div id="div_nome_avo_materna" class="col-sm-2">
            <label for="nome_avo_materna" class="form-label">Nome Avó Materna:</label>
            <input type="text" id="nome_avo_materna" name="nome_avo_materna" class="form-control form-control-sm">
          </div>

          <div id="div_sobrenome_avo_materna" class="col-sm-4">
            <label for="sobrenome_avo_materna" class="form-label">Sobrenome Avó Materna:</label>
            <input type="text" id="sobrenome_avo_materna" name="sobrenome_avo_materna" class="form-control form-control-sm">
          </div>

        </div>

        <!-- Lista de Arquivos -->
        <div class="row mb-3 g-3" id="div_lista_arquivos" style="display: none;">

          <div class="col-sm-6">
            <button class="btn btn-sm btn-dark" type="button" data-bs-toggle="collapse" data-bs-target="#lista_arquivos" aria-expanded="false">
            <i class="far fa-file fa-lg"></i> Arquivos</button>
          </div>
          
          <div class="collapse" id="lista_arquivos">
            <div class="card card-body">
              <div class="col-sm-6" id="card_arquivos">
               
              </div>
            </div>
          </div>

        </div>

        <!-- Upload de Arquivos -->
        <div class="row mb-3 g-3" id="div_upload_arquivos">

          <div class="col-sm-6">
            <button class="btn btn-sm btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#upload_arquivos" aria-expanded="false">
            <i class="fa fa-file-upload fa-lg"></i> Upload de Arquivos</button>
          </div>
          
          <div class="collapse" id="upload_arquivos">
            <div class="card card-body">
              <div class="col-sm-6">
                <label for="multiplos_arquivos" class="form-label">Selecione os Arquivos:</label>
                <input class="form-control form-control-sm" type="file" id="multiplos_arquivos" name="multiplos_arquivos[]" multiple/>
              </div>
            </div>
          </div>

        </div>

        <!-- Barra de Progresso -->
        <div class="row mb-3 g-3" id="div_barra_progresso" style="display: none;">
        <!-- style="display: none;" -->
          <div class="progress">
            <div id="barra_progresso" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 25%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">25%</div>
          </div>
          <label class="form-label" id="label_barra_progresso"></label>
        </div>

        <!-- Botões de Ação -->
        <div class="modal-footer">
          <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal"><i class="fa fa-sign-out-alt fa-lg"></i> Sair</button>
          <button type="button" id="button_modal" onclick="change_registro();" class="btn btn-sm btn-success"><i class="fa fa-save fa-lg"></i> Salvar</button>
        </div>
        
      </form>

    </div>
  </div>
</div>

@endsection
