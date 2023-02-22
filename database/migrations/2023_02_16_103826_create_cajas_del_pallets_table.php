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
        Schema::create('cajas_del_pallets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('id_articulo')->nullable();
            $table->integer('codigo')->nullable();
            $table->string('nombre', 100)->nullable();
            $table->integer('nro_pieza')->nullable();
            $table->integer('nro_caja')->nullable();
            $table->integer('nro_pallet')->nullable();
            $table->decimal('peso', 10)->nullable()->default(0);
            $table->decimal('peso_real', 10)->nullable()->default(0);
            $table->string('lote', 100)->nullable();
            $table->string('codigo_barras_articulo', 100)->nullable();
            $table->string('codigo_barras_caja', 100)->nullable();
            $table->string('codigo_barras_pallet', 100)->nullable();
            $table->boolean('manual')->nullable()->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cajas_del_pallets');
    }
};
