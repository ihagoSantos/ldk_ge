<?php

use Illuminate\Database\Seeder;
use App\Fornecedor;

class FornecedorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('fornecedors')->insert([
            'nome'=> 'TESTE',
            'nomeResponsavel'=> 'RESPONSAVEL TESTE',
            'cpfCnpj'=>'123.456.789.00',
            'email'=>'teste@teste',
            'tipo'=>'CARNE',
            'telefone_id'=>1,
        ]);
    }
}
