<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePalletEnPreparacionItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pallet_en_preparacion_items', function (Blueprint $table) {
            $table->id();
            $table->integer('pallet_en_preparacion_id')->unsigned();
            $table->integer('comprobante')->unsigned();
            $table->integer('nro_pieza')->unsigned()->nullable()->default(null);
            $table->integer('nro_caja')->unsigned()->nullable()->default(null);
            $table->integer('nro_pallet')->unsigned()->nullable()->default(null);
            $table->decimal('peso', 10, 2)->nullable()->default(0);
            $table->decimal('peso_real', 10, 2)->nullable()->default(0);
            $table->string('lote', 100)->nullable()->default(null);
            $table->string('codigo_barras_articulo', 100)->nullable()->default(null);
            $table->string('codigo_barras_caja', 100)->nullable()->default(null);
            $table->string('codigo_barras_pallet', 100)->nullable()->default(null);
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
}
