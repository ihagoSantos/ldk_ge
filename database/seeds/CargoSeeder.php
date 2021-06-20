<?php

use Illuminate\Database\Seeder;

class CargoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $cargos = [
            'GERENTE ADMINISTRATIVO', 
            'GERENTE GERAL',
            'VENDEDOR(A)', 
            'SECRETÁRIO(A)',
            'ATENDENTE',
            'AÇOUGUEIRO(A)',
            'SALSICHEIRO(A)',
            'DESOSSADOR(A)',
            'BALCONISTA',
            'ENTREGADOR',
        ];
        for($i = 0; $i < count($cargos); $i++){
            DB::table('cargos')->insert([
                'nome'=>$cargos[$i]
            ]);
        }
        
    }
}
