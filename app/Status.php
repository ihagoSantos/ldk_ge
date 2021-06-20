<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    public $fillable =['status'];

    function pedido(){
        return $this->hasOne('App\Pedido','status_id');
    }
}
