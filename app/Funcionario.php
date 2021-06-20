<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Funcionario extends Model
{
    //

    public function cargo(){
        return $this->belongsTo('App\Cargo');
    }
    public function user(){
        return $this->belongsTo('App\User');
    }


    function pedidos(){
        return $this->hasMany('App\Pedido','funcionario_id');
    }
    function pagamento(){
        return $this->hasOne('App\Pagamento','funcionario_id');
    }
}
