<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItensPedido extends Model
{
    //
    protected $fillable = ['pesoSolicitado','pesoFinal','valorReal'];

    function pedido(){
        return $this->belongsTo('App\Pedido');
    }
    function produto(){
        return $this->belongsTo('App\Produto');
    }
    
}
