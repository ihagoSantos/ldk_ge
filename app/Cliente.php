<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cliente extends Model
{
    //
    use SoftDeletes;

    protected $fillable = ['nomeReduzido',
                        'nomeResponsavel',
                        'cpfCnpj',
                        'tipo',
                        'inscricaoEstadual'];

    protected $dates = ['deleted_at'];

    function user(){
        return $this->belongsTo('App\User');
    }

    public function funcionario(){
        return $this->belongsTo('App\Funcionario');
    }

    function pedidos(){
        return $this->hasMany('App\Pedido','cliente_id');
    }
    public static $rules = [
    	'cpfCnpj' => 'required|min:14'
    ];
}
