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
use Auth;

class ControllerPrimeiroAcesso extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $titulo = "Alteração de senha do 1º Login";
        
        return view('administracao.alterar_senha.view', compact(
            'titulo',
            
        ));

        // return view('administracao.alterar_senha.view');

    }

    public function alterar_senha()
    {
        $titulo = "Alteração de senha de Usuário";
        
        return view('administracao.alterar_senha.view', compact(
            'titulo',
            
        ));

        // return view('administracao.alterar_senha.view');

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
