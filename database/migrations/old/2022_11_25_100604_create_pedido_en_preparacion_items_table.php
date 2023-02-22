<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePedidoEnPreparacionItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pedido_en_preparacion_items', function (Blueprint $table) {
            $table->id();
            $table->integer('id_articulo')->nullable()->default(null);
            $table->string('codigo_articulo',10)->nullable()->default(null);
            $table->string('articulo',250)->nullable()->default(null);
            $table->string('nro_comp',250)->nullable()->default(null);
            $table->integer('id_pallet')->nullable()->default(null);
            $table->integer('ubicacion_id')->nullable()->default(null);
            $table->string('ubicacion',250)->nullable()->default(null);
            $table->decimal('cantidad_solicitada', 10, 2)->nullable()->default(0);
            $table->decimal('cantidad_a_descontar', 10, 2)->nullable()->default(0);
            $table->enum('estado', ['PENDIENTE', 'PREPARADO','SIN_STOCK'])->nullable()->default('PENDIENTE');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pedido_en_preparacion_items');
    }
}
