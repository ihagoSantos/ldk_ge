<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FontePagamento extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'nome',
        'agência',
        'conta',
        'obs'
    ];

    public function contasPagar(){
        return $this->hasMany('App\ContasPagar');
    }

}
