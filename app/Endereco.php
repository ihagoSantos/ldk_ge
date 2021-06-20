<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Endereco extends Model
{
    //
    use SoftDeletes;
    protected $fillable = ['rua','numero','bairro','cidade','uf','cep'];

    public function user(){
        return $this->hasOne('App\User','endereco_id');
    }
}
