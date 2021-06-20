<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('nomeReduzido')->nullable();
            $table->string('nomeResponsavel')->nullable();
            $table->string('cpfCnpj');
            $table->string('tipo'); //física ou juridica
            $table->string('inscricaoEstadual')->nullable();
            $table->unsignedBigInteger('funcionario_id')->nullable();

            $table->foreign('funcionario_id')->references('id')->on('funcionarios');

            // fk_user
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clientes');
    }
}
