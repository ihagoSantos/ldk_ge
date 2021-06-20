<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class  Produto extends Model
{

    use SoftDeletes;
    //
    protected $fillable = ['nome', 'validade', 'preco', 'descricao'];

    public function categoria(){
        return $this->belongsTo('App\Categoria');
    }

    public function fotosProduto(){
        return $this->hasMany('App\FotosProduto');
    }

    function itensPedidos(){
        return $this->hasMany('App\ItensPedido','produto_id');
    }
}
