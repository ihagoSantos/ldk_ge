<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContasPagarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contas_pagars', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('descricao');
            $table->date('dataPagamento');
            $table->date('dataVencimento');
            $table->string('obs')->nullable();
            $table->string('status');
            $table->float('valorTotalPgm');
            $table->float('valorPago')->nullable();

            $table->unsignedBigInteger('centroCusto_id');
            $table->foreign('centroCusto_id')->references('id')->on('centro_custos');

            $table->unsignedBigInteger('fontePagamento_id');
            $table->foreign('fontePagamento_id')->references('id')->on('fonte_pagamentos');

            $table->unsignedBigInteger('fornecedor_id');
            $table->foreign('fornecedor_id')->references('id')->on('fornecedors');
            $table->timestamps();

            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contas_pagars');
    }
}
