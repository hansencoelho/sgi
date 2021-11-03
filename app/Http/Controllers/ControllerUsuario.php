<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Gate;
use DB;
use App\Models\User;
use App\Models\AclFuncao;
use Auth;

class ControllerUsuario extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        
        if( Gate::denies('usuario-view')) {

        abort(403, 'Não autorizado');

        } else {

        
        $usuario = User::select(
        
        'users.id           AS ID_USUARIO',
        'name               AS NOME',
        'email              AS EMAIL',
        'username           AS USERNAME',
        'fk_acl_funcao      AS ID_FUNCAO',
        'fu.descricao       AS FUNCAO',


        )
        ->leftjoin('acl_funcao AS fu', 'users.fk_acl_funcao', '=', 'fu.id')
        ->orderBy('name', 'ASC')
        ->get();

        $contagem = $usuario->count();
        $titulo = 'Usuário';

        return view('administracao.usuario.view', compact(
            'usuario',
            'titulo',
            'contagem',
            
        ));

        }
    }

    public function autocomplete_funcao(Request $request)
    {

        if( Gate::denies('usuario-view')) {

            abort(403, 'Não autorizado. Você não tem permissão de visualizar.');
    
        } else {

        $funcao = AclFuncao::select(
        'id AS id',
        'descricao AS value',
        )
        ->where('descricao' , 'like' , '%'.$request->term.'%')
        ->get();

        return response()->json($funcao);

        }

    }

    // public function autocomplete_grupo_usuario(Request $request)
    // {
    //     $pesquisa = $request->term;

    //     $acl_grupo_usuario = AclGrupoUsuario::select('ID_ACL_GRUPO_USUARIO', 'DESCRICAO')
    //     ->where('DESCRICAO', 'LIKE', '%'.$pesquisa.'%')
    //     ->get();

    //     foreach ($acl_grupo_usuario as $tb_acl_grupo_usuario ) 
    //     {
    //         $data [] = [
            
    //         'id'    => $tb_acl_grupo_usuario->ID_ACL_GRUPO_USUARIO,
    //         'value' => $tb_acl_grupo_usuario->DESCRICAO,
            
    //         ];
    //     }

    //     return  response()->json($data);

    // }

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
        
                'id                 AS ID',
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

    // public function exportar(Request $request)
    // {
    //     if( Gate::denies('usuario-view')) {

    //         abort(403, 'Não autorizado');
    
    //         } else {

    //         $pesquisa_opcao = $request->pesquisa_opcao;
    //         $texto          = $request->pesquisa_texto;

    //         switch ($pesquisa_opcao) {
    //         case 1:
    //             $pesquisa_opcao = 'users.name';
    //             break;
    //         case 2:
    //             $pesquisa_opcao = 'email'; 
    //             break;
    //         case 3:
    //             $pesquisa_opcao = 'username'; 
    //             break;

    //         default:
    //             $pesquisa_opcao = 'name'; 
    //             break;
        
    //         }
        
    //         $usuario = User::select(
        
    //             'id                 AS ID_USUARIO',
    //             'name               AS NOME',
    //             'email              AS EMAIL',
    //             'username           AS USERNAME',
    //             'departamento       AS AREA',
    //             'gu.DESCRICAO       AS GRUPO_USUARIO',
    //             'FK_GRUPO_USUARIO   AS ID_GRUPO_USUARIO',    
    //             )
    //             ->leftjoin('acl_grupo_usuario AS gu', 'users.FK_GRUPO_USUARIO', '=', 'gu.ID_ACL_GRUPO_USUARIO')

    //             ->where($pesquisa_opcao, 'LIKE','%'.$texto.'%')
    //             ->orderby('name', 'ASC')
    //             ->get();

    //         $arquivo = 'Usuários.xls';

    //             // Criar uma tabela HTML com o formato da planilha
    //             $html = '<meta charset="UTF-8">';
    //             $html .= '<table class="table table-sm table-hover" border="1">';
    //                 $html .= '<div>';
    //                     $html .= '<thead>';
    //                         $html .= '<tr>';
    //                             $html .= '<th scope="col">ID</th>';
    //                             $html .= '<th scope="col">NOME</th>';
    //                             $html .= '<th scope="col">EMAIL</th>';
    //                             $html .= '<th scope="col">USUÁRIO</th>';
    //                             $html .= '<th scope="col">ÁREA</th>';
    //                             $html .= '<th scope="col">GRUPO DO USUÁRIO</th>';
    //                         $html .= '</tr>';
    //                     $html .= '</thead>';

    //                     $html .= '<tbody>';

    //                     foreach($usuario as $tb_usuario){

    //                         $html .= '<tr>';
    //                             $html .= '<td>'.$tb_usuario->ID_USUARIO.'</td>';
    //                             $html .= '<td>'.$tb_usuario->NOME.'</td>';
    //                             $html .= '<td>'.$tb_usuario->EMAIL.'</td>';
    //                             $html .= '<td>'.$tb_usuario->USERNAME.'</td>';
    //                             $html .= '<td>'.$tb_usuario->AREA.'</td>';
    //                             $html .= '<td>'.$tb_usuario->GRUPO_USUARIO.'</td>';
    //                         $html .= '</tr>';
    //                     }

    //                     $html .= '</tbody>';
    //                 $html .= '</table>';
        
    //                 # Configurações header para download
    //                 header ("Expires: 0");
    //                 header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
    //                 header ("Cache-Control: no-cache, must-revalidate");
    //                 header ("Pragma: no-cache");
    //                 header ("Content-type: application/x-msexcel");
    //                 header ("Content-Disposition: attachment; filename=\"{$arquivo}\"" );
    //                 header ("Content-Description: PHP Generated Data" );

    //                 echo $html;

    //                 exit;

    //     }
    // }



    public function show($id_usuario)
    {

        if( Gate::denies('usuario-view')) {

            abort(403, 'Não autorizado');
    
        } else {

        $dados = array();  
        $dados = (object) $dados;

        $dados->usuario = User::select(
        
            'users.id                   AS id',
            'users.name                 AS nome',
            'users.email                AS email',
            'users.username             AS usuario',
            'fu.descricao               AS funcao',
            'users.fk_acl_funcao        AS id_funcao',
            'users.created_at           AS criado',
            'users.updated_at           AS atualizado',
    
    
            )
            ->leftjoin('acl_funcao AS fu', 'users.fk_acl_funcao', '=', 'fu.id')
            ->where('users.id' , '=' , $id_usuario)
            ->orderBy('name', 'ASC')
            ->get();

        // $dados->funcao = AclFuncao::all();

        $dados->titulo = "Visualizar - Usuário";
        
        // dd($dados);

        return response()->json($dados);

        // return view('admin.usuario.formulario', compact(
        //     'dados',
        // ));

        }

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

}
