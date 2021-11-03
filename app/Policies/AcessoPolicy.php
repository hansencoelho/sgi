<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\AclAcesso;
use Auth;
use DB;

class AcessoPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     //
    // }

    public function view()
    {
        $permissao = $this->consulta_permissao();
        return $permissao >= 1;
    }

    public function create()
    {
        $permissao = $this->consulta_permissao();
        return $permissao >= 2;
    }

    public function edit()
    {
        $permissao = $this->consulta_permissao();
        return $permissao >= 3;
    }

    public function consulta_permissao(){

        $id_usuario = Auth::user()->id;
        $modulo = 1;

        $acl_acesso = AclAcesso::select(
            'acl_acesso.fk_acl_permissao AS PERMISSAO'
            )
            ->join('acl_funcao', 'acl_acesso.fk_acl_funcao', '=', 'acl_funcao.id')
            ->join('acl_modulo', 'acl_acesso.fk_acl_modulo', '=', 'acl_modulo.id')
            ->join('users', 'acl_funcao.id', '=', 'users.fk_acl_funcao')
            ->where('users.id' , '=', $id_usuario)
            ->where('acl_acesso.fk_acl_modulo' , '=', $modulo)
            ->get();

        if (empty($acl_acesso->first()->PERMISSAO)) {

            $controle_acesso = 0;

        } else {

            $controle_acesso = $acl_acesso->first()->PERMISSAO;

        }
        
        return $controle_acesso ;

    }

}
