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
        Schema::create('productos_pedido_sessions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('pedido_en_prep_id');
            $table->integer('nro_pieza')->nullable();
            $table->integer('nro_caja')->nullable();
            $table->integer('nro_pallet')->nullable();
            $table->decimal('peso', 10)->nullable()->default(0);
            $table->decimal('peso_real', 10)->nullable()->default(0);
            $table->string('lote', 100)->nullable();
            $table->string('codigo_barras_articulo', 100)->nullable();
            $table->string('codigo_barras_caja', 100)->nullable();
            $table->string('codigo_barras_pallet', 100)->nullable();
            $table->enum('tipo_mov', ['all', 'in', 'out'])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('productos_pedido_sessions');
    }
};
