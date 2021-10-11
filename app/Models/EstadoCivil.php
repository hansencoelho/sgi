<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class EstadoCivil extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'estado_civil';

    protected $primarykey = 'id';

    protected $fillable = [

        'id'
    ];
}
