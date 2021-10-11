<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class AclAcesso extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'acl_acesso';

    protected $primarykey = 'id';

    protected $fillable = [

        'id',
        'fk_acl_modulo',
        'fk_acl_permissao',
        'fk_acl_funcao',

    ];
}
