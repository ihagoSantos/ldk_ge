<?php

use Illuminate\Database\Seeder;

class ProdutoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('produtos')->insert([
            'nome' => 'PRODUTO 1',
            'validade' => 1,
            'preco' => 10,
            'descricao' => null,
            'categoria_id' => 1,
        ]);
        DB::table('produtos')->insert([
            'nome' => 'PRODUTO 2',
            'validade' => 1,
            'preco' => 10,
            'descricao' => null,
            'categoria_id' => 1,
        ]);
    }
}
