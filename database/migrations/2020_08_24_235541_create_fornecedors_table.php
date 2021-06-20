<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFornecedorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fornecedors', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nome');
            $table->string('nomeResponsavel')->nullable();
            $table->string('cpfCnpj');
            $table->string('email')->nullable()->unique();
            $table->string('tipo');
            $table->timestamps();

            $table->unsignedBigInteger('telefone_id');
            $table->foreign('telefone_id')->references('id')->on('telefones');

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
        Schema::dropIfExists('fornecedors');
    }
}
