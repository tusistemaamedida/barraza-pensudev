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
        Schema::create('pallet_en_preparacion_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('pallet_en_preparacion_id');
            $table->integer('comprobante');
            $table->integer('nro_pieza')->nullable();
            $table->integer('nro_caja')->nullable();
            $table->integer('nro_pallet')->nullable();
            $table->decimal('peso', 10)->nullable()->default(0);
            $table->decimal('peso_real', 10)->nullable()->default(0);
            $table->string('lote', 100)->nullable();
            $table->string('codigo_barras_articulo', 100)->nullable();
            $table->string('codigo_barras_caja', 100)->nullable();
            $table->string('codigo_barras_pallet', 100)->nullable();
            $table->integer('id_articulo')->nullable();
            $table->string('codigo_articulo', 10)->nullable();
            $table->string('articulo', 250)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pallet_en_preparacion_items');
    }
};
