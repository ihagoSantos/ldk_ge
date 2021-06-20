<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    //
    protected $fillable = ['formaPagamento','desconto','dataEntrega','valorTotal'];

    function funcionario(){
        return $this->belongsTo('App\Funcionario');
    }
    function cliente(){
        return $this->belongsTo('App\Cliente');
    }

    function itensPedidos(){
        return $this->hasMany('App\ItensPedido','pedido_id');
    }

    function status(){
        return $this->belongsTo('App\Status');
    }
    function pagamento(){
        return $this->hasMany('App\Pagamento','pedido_id');
    }

    public function filtro($filtro,$itensPorPagina){
        try {
            //code...
            return $this->where(function($query) use ($filtro){
                if(isset($filtro['status_id'])){

                    $query->where('status_id',intval($filtro['status_id']));
                }
                if( isset($filtro['dataEntrega'])){
                    $query->where('dataEntrega',$filtro['dataEntrega']);
                }
                if(isset($filtro['cliente'])){
                    $user = User::where('name','LIKE','%'.strtoupper($filtro['cliente']).'%')->first();
                    $cliente = Cliente::where('user_id',$user->id)->first();
                    $query->where('cliente_id',$cliente->id);
                }
                if(isset($filtro['nomeReduzido'])){
                    // $user = User::where('name','LIKE','%'.strtoupper($filtro['cliente']).'%')->first();
                    $cliente = Cliente::where('nomeReduzido','LIKE','%'.strtoupper($filtro['nomeReduzido']).'%')->first();
                    $query->where('cliente_id',$cliente->id);
                }
            })->orderBy('status_id')->orderBy('dataEntrega')->paginate($itensPorPagina);
        } catch (\Throwable $th) {
            return [];
        }           

    }
}
