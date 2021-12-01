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

class ControllerPrimeiroAcesso extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $titulo = "Alteração de senha";
        
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

        if (Auth::User()->id == $request->id) {           

            $update = User::where('id', '=', $request->id)->update([

                'primeiro_login'        => 0,
                'password'              => Hash::make($request->senha),
    
            ]);

            $usuario = User::select('*')
            ->where('users.id', '=', $request->id)
            ->get();

            // dd($usuario);

            $dados = array();  
            $dados = (object) $dados;

            $dados->senha = $request->senha;
            $dados->email    = $usuario[0]->email;
          
            if ($request->enviar_senha == "on" and  $request->senha != ''  ) {
                
                Mail::to($dados->email)
                // ->cc('copy@email.com')
                ->send(new SendMailUsuarioAlterarSenha($dados));
            }

             

            $dados = array();  
            $dados = (object) $dados;

            $dados->resposta_mensagem = "Senha do Usuário alterada com sucesso!";
            
            return view('dashboard.home');
            
            } else {

                abort(403, 'Não autorizado. Você não tem permissão de editar outro usuário.');

            }
        

    }

}
