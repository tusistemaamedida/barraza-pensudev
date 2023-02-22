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
        Schema::create('expediciones', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('id_articulo')->nullable();
            $table->string('codigo_articulo', 10)->nullable();
            $table->string('articulo', 250)->nullable();
            $table->string('nro_comp', 250)->nullable();
            $table->decimal('cantidad_solicitada', 10)->nullable()->default(0);
            $table->decimal('piezas_cargada', 10)->nullable()->default(0);
            $table->decimal('cajas_cargada', 10)->nullable()->default(0);
            $table->integer('user_id')->nullable();
            $table->date('fecha')->nullable()->default('0');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('expediciones');
    }
};
