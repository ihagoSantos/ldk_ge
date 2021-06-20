<?php

use Illuminate\Database\Seeder;
use App\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
class EnderecoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        // $user_id = User::where('name','Admin')->pluck('id');
        DB::table('enderecos')->insert([
            'rua'=>"Rua Tal",
            'numero'=>'123',
            'bairro'=>"Bairro Tal",
            'cidade'=>"Cidade Tal",
            'uf'=>"PE",
            'cep'=>"00000-000",
            'complemento'=>"Apartamento",
            // 'user_id'=> $user_id[0]
        ]);
    }
}
