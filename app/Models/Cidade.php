<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Cidade extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'cidade';

    protected $primarykey = 'id';

    protected $fillable = [

        'id'
    ];
}
