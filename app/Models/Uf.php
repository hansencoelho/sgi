<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Uf extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'uf';

    protected $primarykey = 'id';

    protected $fillable = [

        'id'
    ];
}
