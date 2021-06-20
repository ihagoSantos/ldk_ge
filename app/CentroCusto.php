<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CentroCusto extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'nome',
        'obs'
    ];

    public function contasPagar(){
        return $this->hasMany('App\ContasPagar');
    }
}
