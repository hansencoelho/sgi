<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Gate;
use DB;
use App\Models\Registro;
use App\Models\TipoRegistro;
use App\Models\TipoLocalRegistro;
use App\Models\Declarante;
use App\Models\EstadoCivil;
use App\Models\Uf;
use App\Models\Cidade;
use App\Models\Religiao;
use App\Models\NacionalidadeSobrenome;
use Auth;

class ControllerRegistro extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        
        if( Gate::denies('registro-view')) {

        abort(403, 'Não autorizado');

        } else {

        
        $registro = Registro::select(

            'registro.id                AS id',
            'data_registro              AS data_registro',
            'nome                       AS nome',
            'sobrenome                  AS sobrenome',
            'nome_pai                   AS nome_pai',
            'nome_mae                   AS nome_mae',
            'nome_pai                   AS nome_pai',
            'livro                      AS livro',
            'folha                      AS folha',
            'termo                      AS termo',
            'tr.descricao               AS tipo_registro',
            're.descricao               AS religicao',
            'local_registro             AS local_registro',
            'tlr.descricao              AS tipo_local_registro',


        )

        ->leftjoin('tipo_registro AS tr', 'registro.fk_tipo_registro', '=', 'tr.id')
        ->leftjoin('religiao AS re', 'registro.fk_religiao', '=', 're.id')
        ->leftjoin('tipo_local_registro AS tlr', 'registro.fk_tipo_local_registro', '=', 'tlr.id')
        ->orderBy('nome', 'ASC')
        ->get();

        // dd($registro);

        // $registro = Registro::all();

        $contagem = $registro->count();
        $titulo = 'Registro';
        

        return view('registro.view', compact(
            'registro',
            'titulo',
            'contagem',
            
        ));

        }
    }

    public function autocomplete_grupo_usuario(Request $request)
    {
        $pesquisa = $request->term;

        $acl_grupo_usuario = AclGrupoUsuario::select('ID_ACL_GRUPO_USUARIO', 'DESCRICAO')
        ->where('DESCRICAO', 'LIKE', '%'.$pesquisa.'%')
        ->get();

        foreach ($acl_grupo_usuario as $tb_acl_grupo_usuario ) 
        {
            $data [] = [
            
            'id'    => $tb_acl_grupo_usuario->ID_ACL_GRUPO_USUARIO,
            'value' => $tb_acl_grupo_usuario->DESCRICAO,
            
            ];
        }

        return  response()->json($data);

    }

    public function find(Request $request)
    {
        if( Gate::denies('usuario-view')) {

            abort(403, 'Não autorizado');
    
            } else {

            $pesquisa_opcao = $request->pesquisa_opcao;
            $texto          = $request->pesquisa_texto;

            switch ($pesquisa_opcao) {
            case 1:
                $pesquisa_opcao = 'users.name';
                break;
            case 2:
                $pesquisa_opcao = 'email'; 
                break;
            case 3:
                $pesquisa_opcao = 'username'; 
                break;

            default:
                $pesquisa_opcao = 'name'; 
                break;
        
            }
        
            $usuario = User::select(
        
                'id                 AS ID_USUARIO',
                'name               AS NOME',
                'email              AS EMAIL',
                'username           AS USERNAME',
                'departamento       AS AREA',
                'gu.DESCRICAO       AS GRUPO_USUARIO',
                'FK_GRUPO_USUARIO   AS ID_GRUPO_USUARIO',    
                )
                ->leftjoin('acl_grupo_usuario AS gu', 'users.FK_GRUPO_USUARIO', '=', 'gu.ID_ACL_GRUPO_USUARIO')

                ->where($pesquisa_opcao, 'LIKE','%'.$texto.'%')
                ->orderby('name', 'ASC')
                ->get();

            $contagem = $usuario->count();
            $titulo = 'Usuário';

            $pesquisa_retorno = [
                'pesquisa_opcao' => $request->pesquisa_opcao,
                'pesquisa_texto' => $request->pesquisa_texto,
            ];        

            return view('admin.usuario.listagem', compact(
                'usuario',
                'contagem',
                'titulo',
                'pesquisa_retorno',
                
            ));

        }
    }

    public function exportar(Request $request)
    {
        if( Gate::denies('usuario-view')) {

            abort(403, 'Não autorizado');
    
            } else {

            $pesquisa_opcao = $request->pesquisa_opcao;
            $texto          = $request->pesquisa_texto;

            switch ($pesquisa_opcao) {
            case 1:
                $pesquisa_opcao = 'users.name';
                break;
            case 2:
                $pesquisa_opcao = 'email'; 
                break;
            case 3:
                $pesquisa_opcao = 'username'; 
                break;

            default:
                $pesquisa_opcao = 'name'; 
                break;
        
            }
        
            $usuario = User::select(
        
                'id                 AS ID_USUARIO',
                'name               AS NOME',
                'email              AS EMAIL',
                'username           AS USERNAME',
                'departamento       AS AREA',
                'gu.DESCRICAO       AS GRUPO_USUARIO',
                'FK_GRUPO_USUARIO   AS ID_GRUPO_USUARIO',    
                )
                ->leftjoin('acl_grupo_usuario AS gu', 'users.FK_GRUPO_USUARIO', '=', 'gu.ID_ACL_GRUPO_USUARIO')

                ->where($pesquisa_opcao, 'LIKE','%'.$texto.'%')
                ->orderby('name', 'ASC')
                ->get();

            $arquivo = 'Usuários.xls';

                // Criar uma tabela HTML com o formato da planilha
                $html = '<meta charset="UTF-8">';
                $html .= '<table class="table table-sm table-hover" border="1">';
                    $html .= '<div>';
                        $html .= '<thead>';
                            $html .= '<tr>';
                                $html .= '<th scope="col">ID</th>';
                                $html .= '<th scope="col">NOME</th>';
                                $html .= '<th scope="col">EMAIL</th>';
                                $html .= '<th scope="col">USUÁRIO</th>';
                                $html .= '<th scope="col">ÁREA</th>';
                                $html .= '<th scope="col">GRUPO DO USUÁRIO</th>';
                            $html .= '</tr>';
                        $html .= '</thead>';

                        $html .= '<tbody>';

                        foreach($usuario as $tb_usuario){

                            $html .= '<tr>';
                                $html .= '<td>'.$tb_usuario->ID_USUARIO.'</td>';
                                $html .= '<td>'.$tb_usuario->NOME.'</td>';
                                $html .= '<td>'.$tb_usuario->EMAIL.'</td>';
                                $html .= '<td>'.$tb_usuario->USERNAME.'</td>';
                                $html .= '<td>'.$tb_usuario->AREA.'</td>';
                                $html .= '<td>'.$tb_usuario->GRUPO_USUARIO.'</td>';
                            $html .= '</tr>';
                        }

                        $html .= '</tbody>';
                    $html .= '</table>';
        
                    # Configurações header para download
                    header ("Expires: 0");
                    header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
                    header ("Cache-Control: no-cache, must-revalidate");
                    header ("Pragma: no-cache");
                    header ("Content-type: application/x-msexcel");
                    header ("Content-Disposition: attachment; filename=\"{$arquivo}\"" );
                    header ("Content-Description: PHP Generated Data" );

                    echo $html;

                    exit;

        }
    }

    public function new()
    {

        if( Gate::denies('registro-view')) {

            abort(403, 'Não autorizado');
    
        } else {

        $dados = array();  
        $dados = (object) $dados;
        
        // $dados->registro = 'show';
        // $dados['teste'] = 123;
        // $dados->show->registros =  [ 0 => 'karen', 1 => 'rodrigo'];

        // $dados->registro = Registro::select(

        //     'registro.*',
        //     'tr.descricao       AS ds_tipo_registro',
        //     'tlr.descricao      AS ds_tipo_local_registro',
        //     'uf.id              AS id_uf',
        //     'uf.sigla           AS ds_uf',
        //     'ci.id              AS id_cidade',
        //     'ci.descricao       AS ds_cidade',
        //     'de.descricao       AS ds_declarante',
        //     'ns.descricao       AS ds_nacionalidade_sobrenome',
        //     'cv.descricao       AS ds_estado_civil',
        //     'rl.id              AS ds_religiao',
        //     'rl.descricao       AS ds_religiao',
    
        //     )
        //     ->leftjoin('tipo_registro AS tr', 'registro.fk_tipo_registro', '=', 'tr.id')
        //     ->leftjoin('tipo_local_registro AS tlr', 'registro.fk_tipo_local_registro', '=', 'tlr.id')
        //     ->leftjoin('cidade AS ci', 'registro.fk_cidade', '=', 'ci.id')
        //     ->leftjoin('uf', 'ci.fk_uf', '=', 'uf.id')
        //     ->leftjoin('declarante AS de', 'registro.fk_declarante', '=', 'de.id')
        //     ->leftjoin('nacionalidade_sobrenome AS ns', 'registro.fk_nacionalidade_sobrenome', '=', 'ns.id')
        //     ->leftjoin('estado_civil AS cv', 'registro.fk_estado_civil', '=', 'cv.id')
        //     ->leftjoin('religiao AS rl', 'registro.fk_religiao', '=', 'rl.id')
        //     ->where('registro.id' , '=' , $id_usuario)
        //     // ->orderBy('name', 'ASC')
        //     ->get();

        $dados->tipo_registro = TipoRegistro::all();
        $dados->tipo_local_registro = TipoLocalRegistro::all();
        $dados->declarante = Declarante::all();
        $dados->tipo_registro = TipoRegistro::all();
        $dados->estado_civil = EstadoCivil::all();
        // $dados->religiao = Religiao::all();
        // $dados->uf = Uf::all();
        // $dados->cidade = Cidade::all();
        $dados->nacionalidade_sobrenome = NacionalidadeSobrenome::all();

        // dd($dados);

        $dados->titulo = "Novo - Registro";
  
        

        // return view('admin.usuario.formulario', compact(
        //     'titulo',
        //     'grupo_usuario',
        //     'usuario',
        // ));

        return response()->json($dados);

        }

    }

    public function show($id_usuario)
    {

        if( Gate::denies('registro-view')) {

            abort(403, 'Não autorizado');
    
        } else {

        $dados = array();  
        $dados = (object) $dados;
        
        // $dados->registro = 'show';
        // $dados['teste'] = 123;
        // $dados->show->registros =  [ 0 => 'karen', 1 => 'rodrigo'];

        $dados->registro = Registro::select(

            'registro.*',
            'tr.descricao       AS ds_tipo_registro',
            'tlr.descricao      AS ds_tipo_local_registro',
            'uf.id              AS id_uf',
            'uf.sigla           AS ds_uf',
            'ci.id              AS id_cidade',
            'ci.descricao       AS ds_cidade',
            'de.descricao       AS ds_declarante',
            'ns.descricao       AS ds_nacionalidade_sobrenome',
            'cv.descricao       AS ds_estado_civil',
            'rl.id              AS ds_religiao',
            'rl.descricao       AS ds_religiao',
    
            )
            ->leftjoin('tipo_registro AS tr', 'registro.fk_tipo_registro', '=', 'tr.id')
            ->leftjoin('tipo_local_registro AS tlr', 'registro.fk_tipo_local_registro', '=', 'tlr.id')
            ->leftjoin('cidade AS ci', 'registro.fk_cidade', '=', 'ci.id')
            ->leftjoin('uf', 'ci.fk_uf', '=', 'uf.id')
            ->leftjoin('declarante AS de', 'registro.fk_declarante', '=', 'de.id')
            ->leftjoin('nacionalidade_sobrenome AS ns', 'registro.fk_nacionalidade_sobrenome', '=', 'ns.id')
            ->leftjoin('estado_civil AS cv', 'registro.fk_estado_civil', '=', 'cv.id')
            ->leftjoin('religiao AS rl', 'registro.fk_religiao', '=', 'rl.id')
            ->where('registro.id' , '=' , $id_usuario)
            // ->orderBy('name', 'ASC')
            ->get();

        $dados->tipo_registro = TipoRegistro::all();
        $dados->tipo_local_registro = TipoLocalRegistro::all();
        $dados->declarante = Declarante::all();
        $dados->tipo_registro = TipoRegistro::all();
        $dados->estado_civil = EstadoCivil::all();
        // $dados->religiao = Religiao::all();
        // $dados->uf = Uf::all();
        // $dados->cidade = Cidade::all();
        $dados->nacionalidade_sobrenome = NacionalidadeSobrenome::all();

        // dd($dados);

        $dados->titulo = "Visualizar - Registro";
  
        

        // return view('admin.usuario.formulario', compact(
        //     'titulo',
        //     'grupo_usuario',
        //     'usuario',
        // ));

        return response()->json($dados);

        }

    }

    public function store(Request $request)
    {

        // dd($request);

        // dd($request);

        
        // if( Gate::denies('registro-edit')) {

        //     abort(403, 'Não autorizado. Você não tem permissão de editar.');
    
        // } else {

        // }

        return $request;

    }


    public function update(Request $request)
    {

        if( Gate::denies('usuario-edit')) {

            abort(403, 'Não autorizado. Você não tem permissão de editar.');
    
        } else {

            $update = User::where('id', '=', $request->ID_USUARIO)->update([

            'FK_GRUPO_USUARIO'          => $request->ID_GRUPO_USUARIO,

            ]);

            $usuario = User::select(
        
                'id                 AS ID_USUARIO',
                'name               AS NOME',
                'email              AS EMAIL',
                'username           AS USERNAME',
                'departamento       AS AREA',
                'gu.DESCRICAO       AS GRUPO_USUARIO',
                'FK_GRUPO_USUARIO   AS ID_GRUPO_USUARIO',
                'users.created_at   AS INTEGRADO',
                'users.updated_at   AS ATUALIZADO',
        
        
                )
                ->leftjoin('acl_grupo_usuario AS gu', 'users.FK_GRUPO_USUARIO', '=', 'gu.ID_ACL_GRUPO_USUARIO')
                ->where('id' , '=' , $request->ID_USUARIO)
                ->orderBy('name', 'ASC')
                ->get();
    
            $grupo_usuario = AclGrupoUsuario::all();
    
            $titulo = "Usuário";
            $mensagem = "Usuário alterado com sucesso.";
    
            return view('admin.usuario.formulario', compact(
                'titulo',
                'mensagem',
                'usuario',
            ));

                    
        }

    }


    public function autocomplete_uf(Request $request)
    {

        if( Gate::denies('registro-view')) {

            abort(403, 'Não autorizado. Você não tem permissão de visualizar.');
    
        } else {

        $uf = Uf::select(
        'id AS id',
        'sigla AS value',
        )
        ->where('sigla' , 'like' , '%'.$request->term.'%')
        ->get();

        return response()->json($uf);

        }

    }

    public function autocomplete_cidade(Request $request)
    {

        if( Gate::denies('registro-view')) {

            abort(403, 'Não autorizado. Você não tem permissão de visualizar.');
    
        } else {

        $cidade = Cidade::select(
        'cidade.id AS id',
        'cidade.descricao AS value',
        'cidade.fk_uf AS fk_uf',
        )
        ->leftjoin('uf', 'cidade.fk_uf', '=', 'uf.id')
        ->where('cidade.descricao' , 'like' , '%'.$request->term.'%')
        ->where('uf.id' , '=' , $request->id_uf)
        ->get();

        return response()->json($cidade);

        }

    }

    public function autocomplete_religiao(Request $request)
    {

        if( Gate::denies('registro-view')) {

            abort(403, 'Não autorizado. Você não tem permissão de visualizar.');
    
        } else {

        $religiao = Religiao::select(
        'id AS id',
        'descricao AS value',
        )
        ->where('descricao' , 'like' , '%'.$request->term.'%')
        ->get();

        return response()->json($religiao);

        }

    }

}
