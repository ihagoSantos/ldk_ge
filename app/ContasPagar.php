<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContasPagar extends Model
{

    use SoftDeletes;

    protected $fillable = [
        'descricao',
        'dataPagamento',
        'dataVencimento',
        'obs',
        'status',
        'valorTotalPgm',
        'valorPago'
    ];

    public function fontePagamento(){
        return $this->belongsTo('App\FontePagamento','fontePagamento_id');
    }

    public function centroCusto(){
        return $this->belongsTo('App\CentroCusto','centroCusto_id');
    }

    public function fornecedor(){
        return $this->belongsTo('App\Fornecedor');
    }
}
