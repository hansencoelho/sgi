<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Religiao extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'religiao';

    protected $primarykey = 'id';

    protected $fillable = [

        'id'
    ];
}
