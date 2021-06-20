<?php

use Illuminate\Database\Seeder;

class ClienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('clientes')->insert([
            'nomeReduzido' => 'Cliente Nome Reduzido',
            'nomeResponsavel' => 'Cliente Nome Responsável',
            'cpfCnpj' => '11111111111',
            'tipo' => 'PESSOA FÍSICA',
            'inscricaoEstadual' => '123456',
            'funcionario_id' => 1,
            'user_id' => 7,
        ]);
    }
}
