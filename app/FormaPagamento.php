<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FormaPagamento extends Model
{
    //
    protected $fillable=['nome'];

    public function pagamento(){
        return $this->hasMany('App\Pagamento','formaPagamento_id');
    }
}
