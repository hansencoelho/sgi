<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class TipoRegistro extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'tipo_registro';

    protected $primarykey = 'id';

    protected $fillable = [

        'id'
    ];
}
