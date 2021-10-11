@extends("layouts.main")

@section("content")

<!-- jQuery library -->
<!-- <script type="text/javascript" language="javascript" src="{!! asset('js/jquery1.11.1.min.js') !!}"></script> -->
<!-- jQueryUI library -->
<!-- <link rel="stylesheet" href="{!! asset('css/jquery-ui1.9.1.css') !!}"> -->
<!-- jQueryUI library -->
<!-- <script src="{!! asset('js/jquery-ui1.10.3.js') !!}"></script> -->

<!-- <script type="text/javascript" language="javascript" src="{!! asset('js/autocomplete_linhamovel.js') !!}"></script> -->

<div class="text-center">
  <h1 class="h3 mb-3 font-weight-normal">@if(isset($titulo)){{$titulo}}@endif <i class="fa fa-user fa-lg"></i></h1>
  <hr>
</div>

{{-- Opções --}}
<div class="col-md-12">
  <div class="row row-cols-2">
    <div class="col-md-10">

      <button class="btn btn-sm btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#pesquisar" aria-expanded="false">
      <i class="fa fa-search fa-lg"></i> Pesquisar</button>
      
      <a href="@if(isset($pesquisa_retorno)) {{ route('usuario.exportar') }}?&opcao={{$pesquisa_retorno['pesquisa_opcao']}}&texto={{$pesquisa_retorno['pesquisa_texto']}}@else{{ route('usuario.exportar') }}?status=1 @endif" target="_blank">
      <button type="button" class="btn btn-sm btn-secondary">
      <i class="fa fa-download fa-lg"></i> Exportar</button></a>

    </div>  

    <div class="col-md-2 aling-left">
      <h6 style="text-align: right;">Resultado(s): @if (isset($contagem)) {{ $contagem }} @endif </h6>
    </div>
  </div>
</div>

{{-- Pesquisa --}}

<div class="@if(isset($pesquisa_retorno)){{'collapse show'}}@else{{'collapse'}}@endif" id="pesquisar">
  <div class="card card-body">
  <form method="POST" action="{{ route('usuario.find') }}">
    {{ csrf_field() }}
      <div class="form-row">

        <div class="form-group col-md-2 mb-0">
          <select id="pesquisa_opcao" name="pesquisa_opcao" class="form-control form-control-sm">
            <option value="1" @if (isset($pesquisa_retorno) && $pesquisa_retorno['pesquisa_opcao'] == 1 ) selected="selected" @endif>Nome</option>
            <option value="2" @if (isset($pesquisa_retorno) && $pesquisa_retorno['pesquisa_opcao'] == 2 ) selected="selected" @endif>E-mail</option>
            <option value="3" @if (isset($pesquisa_retorno) && $pesquisa_retorno['pesquisa_opcao'] == 3 ) selected="selected" @endif>Usuário</option>
          </select>
        </div>

        <div class="form-group input-group col-md-2">
          <input type="text" id="pesquisa_texto" name="pesquisa_texto" value="@if (isset($pesquisa_retorno)){{ $pesquisa_retorno['pesquisa_texto'] }}@endif" class="form-control form-control-sm" placeholder="Pesquisar..." aria-label="Recipient's username" aria-describedby="button-addon2">
            <div class="input-group-append">
              <button class="btn btn-primary btn-sm" type="submit" id="button-addon2">
              <i class="fa fa-search fa-lg"></i></button>
            </div>
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
        <th scope="col">Usuário</th>
        <!-- <th scope="col">Área</th> -->
        <th scope="col">Grupo do Usuário</th>
        <th scope="col" style="text-align: center;">Ação</th>
      </tr>
    </thead>

    <tbody>
      @forelse($usuario as $tb_usuario)
      <tr>
        <td>{{ $tb_usuario->NOME }}</td>
        <td>{{ $tb_usuario->EMAIL }}</td>
        <td>{{ $tb_usuario->USERNAME }}</td>
        <!-- <td>{{ $tb_usuario->AREA }}</td> -->
        <td>{{ $tb_usuario->GRUPO_USUARIO }}</td>
        <td style="text-align: center;"><a href="{{ route('usuario.show', ['id_usuario' => $tb_usuario->ID_USUARIO]) }}" target="_blank"><i class="fa fa-eye fa-lg"></i></a> </td>
        <!-- {{-- <td style="vertical-align: middle; text-align: center;"><a href="{{ route('admin_user.show', ['id_user' => $tb_usuario->ID_USUARIO]) }}" target="_blank"><i class="fa fa-save fa-lg"></i></a> </td> --}} -->
      </tr>
      @empty
      @endforelse
    </tbody>
  </table>
</div>

@endsection
