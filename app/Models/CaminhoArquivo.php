<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class CaminhoArquivo extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'caminho_arquivo';

    protected $primarykey = 'id';

    protected $fillable = [

        'id'
    ];
}
