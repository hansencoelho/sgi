<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class NacionalidadeSobrenome extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'nacionalidade_sobrenome';

    protected $primarykey = 'id';

    protected $fillable = [

        'id'
    ];
}
