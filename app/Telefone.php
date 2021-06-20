<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Telefone extends Model
{
    //
    use SoftDeletes;
    protected $fillable = ['numero'];

    public function user(){
        return $this->hasOne('App\User','telefone_id');
    }

    public function telefone(){
        return $this->hasOne('App\Telefone','telefone_id');
    }
}
