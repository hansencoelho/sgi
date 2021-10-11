<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class AclFuncao extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'acl_funcao';

    protected $primarykey = 'id';

    protected $fillable = [

        'descricao'
    ];
}
