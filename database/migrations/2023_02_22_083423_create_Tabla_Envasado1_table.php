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
        Schema::connection('envasado')->create('Tabla_Envasado1', function (Blueprint $table) {
            $table->bigInteger('Id');
            $table->integer('ID_Articulo')->nullable();
            $table->integer('ID_Usuario')->nullable();
            $table->integer('ID_Establecimiento')->nullable();
            $table->dateTime('FechaPesaje')->nullable();
            $table->dateTime('HoraPesaje')->nullable();
            $table->string('Lote', 50)->nullable();
            $table->integer('NrodePieza')->nullable();
            $table->integer('NrodeCaja')->nullable();
            $table->integer('NrodePallet')->nullable();
            $table->dateTime('FechaElaboracion')->nullable();
            $table->dateTime('FechaVencimiento')->nullable();
            $table->float('Peso', 0, 0)->nullable();
            $table->float('Peso_Real', 0, 0)->nullable();
            $table->string('CodBarraArt_Int')->nullable();
            $table->string('CodBarraCaja_Int')->nullable();
            $table->string('CodBarraPallet_Int')->nullable();
            $table->timestamp('upsize_ts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('envasado')->dropIfExists('Tabla_Envasado1');
    }
};
