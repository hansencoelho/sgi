@extends("layouts.principal")

@section("content")

<!-- <script type="text/javascript" language="javascript" src="{!! asset('js/autocomplete_usuario.js') !!}"></script> -->

<div class="text-center">

  @if (!empty($mensagem) )
    <div class="alert alert-success alert-dismissible">
      <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
      <strong>Salvo com Sucesso!</strong> {{ $mensagem }}
    </div>
  @endif

  <h1 class="h3 mb-3 font-weight-normal" style="margin : 10px;"> @if(@isset($titulo)) {{ $titulo }} @endif <i class="fas fa-user fa-lg"></i></h1>
  <hr/>

</div>

<form method="POST" action="@if (isset($usuario[0])){{ route('usuario.update') }}@else{{ route('usuario.store') }}@endif" style="font-size: 14px;">
{{ csrf_field() }}
  <!-- Linha 1 - Dados Unidade -->
  <div class="form-row col-md-12">
   
    <input type="hidden" name="ID_USUARIO" class="form-control form-control-sm" id="ID_USUARIO" placeholder="" value="@if (isset($usuario)){{$usuario[0]->ID_USUARIO}}@endif">

    <div class="form-group col-md-4">
      <label for="name">Nome:</label>
      <input type="text" class="form-control form-control-sm" id="name" name="name" 
      value="@if (isset($usuario)){{$usuario[0]->NOME}}@endif" required disabled>
    </div>

    <div class="form-group col-md-3">
      <label for="email">E-mail:</label>
      <input type="email" class="form-control form-control-sm" id="email" name="email" 
      value="@if (isset($usuario)){{$usuario[0]->EMAIL}}@endif" required disabled>
    </div>

    <div class="form-group col-md-2">
      <label for="username">Usuário:</label>
      <input type="text" class="form-control form-control-sm" id="username" name="username" 
      value="@if (isset($usuario)){{$usuario[0]->USERNAME}}@endif" required disabled>
    </div>

    <div class="form-group col-md-3">
      <label for="area">Área:</label>
      <input type="text" class="form-control form-control-sm" id="area" name="area" 
      value="@if (isset($usuario)){{$usuario[0]->AREA}}@endif" required disabled>
    </div>  

    <div class="form-group col-md-4">
      <input type="hidden" name="ID_GRUPO_USUARIO" class="form-control form-control-sm" id="ID_GRUPO_USUARIO" placeholder="" value="@if (isset($usuario)){{$usuario[0]->ID_GRUPO_USUARIO}}@endif">
      <label for="FK_GRUPO_USUARIO">Grupo de Usuário:</label>
      <input type="text" class="form-control form-control-sm" id="FK_GRUPO_USUARIO" name="FK_GRUPO_USUARIO" value="@if (isset($usuario)){{$usuario[0]->GRUPO_USUARIO}}@endif" required
      @if (isset($usuario[0])) @can('usuario-edit') @elsecan('usuario-view') disabled @endcan @endif>
    </div>

    <div class="form-group col-md-2">
      <label for="integrado">Integrado:</label>
      <input type="date" class="form-control form-control-sm" id="integrado" name="integrado" 
      value="@if (isset($usuario)){{$usuario[0]->INTEGRADO}}@endif" required disabled>
    </div> 

    <div class="form-group col-md-2">
      <label for="atualizado">Atualizado:</label>
      <input type="date" class="form-control form-control-sm" id="atualizado" name="atualizado" 
      value="@if (isset($usuario)){{$usuario[0]->ATUALIZADO}}@endif" required disabled>
    </div> 

  </div>
   
  <div class="form-group col-md-12 text-right">
      
    @can('usuario-create', 'usuario-update')
    <button type="submit" class="btn btn-success">
    <i class="far fa-save fa-lg"></i> Salvar</button>
    @endcan

  </div>
</form>


@endsection