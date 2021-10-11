<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Declarante extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'declarante';

    protected $primarykey = 'id';

    protected $fillable = [

        'id'
    ];
}
