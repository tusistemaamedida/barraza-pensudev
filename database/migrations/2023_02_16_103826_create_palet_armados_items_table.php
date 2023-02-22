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
        Schema::create('palet_armados_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('pallet_armado_id');
            $table->integer('ID_Articulo')->nullable();
            $table->string('codigo', 25)->nullable();
            $table->string('nombre', 250)->nullable();
            $table->string('CodBarraPallet_Int', 100)->nullable();
            $table->string('CodBarraCaja_Int', 100)->nullable();
            $table->string('CodBarraArt_Int', 100)->nullable();
            $table->string('Lote', 100)->nullable();
            $table->decimal('Peso_Real', 10)->nullable()->default(0);
            $table->decimal('Peso', 10)->nullable()->default(0);
            $table->date('FechaElaboracion')->nullable()->default('0');
            $table->date('FechaVencimiento')->nullable()->default('0');
            $table->date('fecha_cierre_comprobante')->nullable()->default('0');
            $table->integer('comprobante')->nullable();
            $table->string('estado', 10)->nullable()->default('P');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('palet_armados_items');
    }
};
