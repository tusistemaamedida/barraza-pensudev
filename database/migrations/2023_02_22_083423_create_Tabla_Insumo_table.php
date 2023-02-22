<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('envasado')->create('Tabla_Insumo', function (Blueprint $table) {
            $table->increments('Id');
            $table->string('Descripcion', 80)->nullable();

            $table->primary(['Id'], 'aaaaaTabla_Insumo_PK');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('envasado')->dropIfExists('Tabla_Insumo');
    }
};
