<?php

use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $status = [
            'SOLICITADO',
            'PESADO',
            'ENTREGUE',
            'PAGO PARCIALMENTE',
            'PAGO TOTALMENTE',
        ];

        for($i = 0; $i < count($status); $i++) {
            # code...
            DB::table('statuses')->insert(['status'=>$status[$i]]);
        }
    }
}
