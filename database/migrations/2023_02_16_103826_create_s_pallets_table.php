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
        Schema::create('s_pallets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('id_articulo')->nullable();
            $table->string('codigo', 25)->nullable();
            $table->integer('estado_id')->nullable()->default(1);
            $table->string('nombre', 250)->nullable();
            $table->string('codigo_barras', 100)->nullable();
            $table->string('lote', 100)->nullable();
            $table->decimal('piezas', 10)->nullable()->default(0);
            $table->decimal('peso', 10)->nullable()->default(0);
            $table->decimal('peso_real', 10)->nullable()->default(0);
            $table->integer('dias_almacenamiento')->nullable()->default(0);
            $table->date('fecha_elaboracion')->nullable()->default('0');
            $table->date('fecha_vencimiento')->nullable()->default('0');
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
        Schema::dropIfExists('s_pallets');
    }
};
