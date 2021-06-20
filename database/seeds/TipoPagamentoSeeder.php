<?php

use Illuminate\Database\Seeder;

class TipoPagamentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $formasPagamento = ['À VISTA','CHEQUE','CARTÃO DE CRÉDITO','BOLETO'];
        for($i=0;$i<count($formasPagamento);$i++){
            DB::table('forma_pagamentos')->insert([
                'nome'=> $formasPagamento[$i],
            ]);
        }
    }
}
