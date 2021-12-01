<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Gate;
use DB;
use Hash;
use Mail;
use App\Models\User;
use App\Models\AclFuncao;
use App\Mail\SendMailUsuario;
use App\Mail\SendMailUsuarioAlterarSenha;
use Auth;

class ControllerUsuario extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if (Auth::User()->primeiro_login == 1) {
            return redirect()->route('alterar_senha'); 
        }
        
        if( Gate::denies('usuario-view')) {

        abort(403, 'Não autorizado');

        } else {

        
        $usuario = User::select(
        
        'users.id           AS ID',
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

    public function find(Request $request)
    {
        if( Gate::denies('usuario-view')) {

            abort(403, 'Não autorizado');

        } else {

            $pesquisa_opcao         = $request->pesquisa_opcao;
            $texto                  = $request->pesquisa_texto;


            switch ($pesquisa_opcao) {
            case 1:
                $pesquisa_opcao = 'users.nome';
                break;
            case 2:
                $pesquisa_opcao = 'users.email'; 
                break;
            case 3:
                $pesquisa_opcao = 'fu.descricao'; 
                break;

            default:
                $pesquisa_opcao = 'users.nome'; 
                break;
        
            }
    
            $usuario = User::select(
    
                'users.id           AS ID',
                'name               AS NOME',
                'email              AS EMAIL',
                'username           AS USERNAME',
                'fk_acl_funcao      AS ID_FUNCAO',
                'fu.descricao       AS FUNCAO',
        
            )
            ->leftjoin('acl_funcao AS fu', 'users.fk_acl_funcao', '=', 'fu.id')
            ->where($pesquisa_opcao, 'LIKE','%'.$texto.'%')
            ->orderby($pesquisa_opcao, 'ASC')
            ->get();

            $contagem = $usuario->count();
            $titulo = 'Usuário';

            $pesquisa_retorno = [
                'pesquisa_opcao' => $request->pesquisa_opcao,
                'pesquisa_texto' => $request->pesquisa_texto,
            ];        

            return view('administracao.usuario.view', compact(
                'usuario',
                'contagem',
                'titulo',
                'pesquisa_retorno',
                
            ));

        }
    }

    public function show($id_usuario)
    {
        // dd($id_usuario);
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

        $dados->titulo = "Visualizar - Usuário";

        return response()->json($dados);

        }

    }

    public function store(Request $request)
    {

        if( Gate::denies('registro-create')) {

            abort(403, 'Não autorizado. Você não tem permissão de editar.');
    
        } else {

            $usuario = User::select(
        
            'users.id                   AS id',
    
            )
            ->where('users.email' , 'like' , $request->email)
            ->get();

            if ($usuario != '[]') {

                abort(403, 'Não autorizado. Você não tem permissão de editar.');

            } else {

                $usuario = new User;

                $usuario->name                  = $request->nome;
                $usuario->email                 = $request->email;
                $usuario->fk_acl_funcao         = $request->id_funcao;
                $usuario->password              = Hash::make($request->senha);
            
                if ($request->alterar_senha == "on" ) {

                    $usuario->primeiro_login = 1;

                } else {

                    $usuario->primeiro_login = 0;

                }
                
                $insert = $usuario->save();

                if ($request->enviar_senha == "on" ) {
                    
                    Mail::to($request->email)
                    // ->cc('copy@email.com')
                    ->send(new SendMailUsuario($request));
                }

                $dados = array();  
                $dados = (object) $dados;

                $dados->id_usuario = $usuario->id;
                $dados->resposta_mensagem = "Usuário salvo com sucesso!";
                
                return response()->json($dados);

            }

        }

    }

    public function update(Request $request)
    {

        if( Gate::denies('usuario-edit')) {

            abort(403, 'Não autorizado. Você não tem permissão de editar.');
    
        } else {

            $usuario = User::select(
    
            'users.id                   AS id',
    
            )
            ->where('users.email' , 'like' , $request->email)
            ->where('users.id' , '!=' , $request->id)
            ->get();

            if ($usuario != '[]') {

                abort(403, 'Não autorizado. Você não tem permissão de editar.');

            } else {

            if ($request->alterar_senha == "on") {

                $request->alterar_senha = 1;

            } else {

                $request->alterar_senha = 0;

            }

            if ($request->senha == '') {

                $update = User::where('id', '=', $request->id)->update([

                    'name'                  => $request->nome,
                    'email'                 => $request->email,
                    'fk_acl_funcao'         => $request->id_funcao,
                    'primeiro_login'        => $request->alterar_senha,
        
                ]);

            } else {

                $update = User::where('id', '=', $request->id)->update([

                    'name'                  => $request->nome,
                    'email'                 => $request->email,
                    'fk_acl_funcao'         => $request->id_funcao,
                    'primeiro_login'        => $request->alterar_senha,
                    'password'              => Hash::make($request->senha),
        
                ]);

            }

            if ($request->enviar_senha == "on" and  $request->senha != ''  ) {
                
                Mail::to($request->email)
                // ->cc('copy@email.com')
                ->send(new SendMailUsuarioAlterarSenha($request));
            }

            $dados = array();  
            $dados = (object) $dados;

            $dados->id_usuario = Auth::User()->id;
            // $dados->usuario =  $usuario;
            $dados->resposta_mensagem = "Usuário alterado com sucesso!";
            
            return response()->json($dados);
            
            }
                    
        }

    }

    public function delete(Request $request)
    {

        // dd($request);

        if( Gate::denies('usuario-edit')) {

            abort(403, 'Não autorizado. Você não tem permissão de visualizar.');
    
        } else {

            $delete = User::where('id', $request->id_usuario)->delete();

            if ($delete != 0) {

                $retorno = 1; 

            } else {

                $retorno = 0;

            }

            return response()->json($retorno);

        }

    }



}
