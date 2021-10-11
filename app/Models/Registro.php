<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Registro extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'registro';

    protected $primarykey = 'id';

    protected $fillable = [

        'id'
    ];
}
