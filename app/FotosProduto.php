<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FotosProduto extends Model
{
    //
    protected $fillable = ['path'];
    
    public function produto(){
        return $this->belongsTo('App\Produto','produto_id');
    }
}
