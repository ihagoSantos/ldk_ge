<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Fornecedor extends Model
{
    //
    use SoftDeletes;

    protected $fillable = [
        'nome',
        'nomeResponsavel',
        'cpfCnpj',
        'email',
        'tipo'
    ];

    protected $dates = ['deleted_at'];

    public function telefone(){
        return $this->belongsTo('App\Telefone');
    }

    public function contasPagar(){
        return $this->hasMany('App\ContasPagar','fornecedor_id');
    }
}
