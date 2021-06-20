<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Cargo;

class FuncionarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


        DB::table('funcionarios')->insert([
            'user_id'=> 1,
            'cargo_id'=> 2,
        ]);

        DB::table('funcionarios')->insert([
            'user_id'=> 3,
            'cargo_id'=> 3,
        ]);
        DB::table('funcionarios')->insert([
            'user_id'=> 6,
            'cargo_id'=> 3,
        ]);
    }
    
}
