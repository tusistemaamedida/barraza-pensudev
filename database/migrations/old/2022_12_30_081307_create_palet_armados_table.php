<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaletArmadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('codigo_barra_palet_armados', function (Blueprint $table) {
            $table->id();
            $table->integer('numero')->nullable()->default(null);
            $table->integer('cantidad')->nullable()->default(null);
        });

        Schema::create('palet_armados', function (Blueprint $table) {
            $table->id();
            $table->date('fecha_preparacion')->nullable()->default(null);
            $table->date('fecha_cierre')->nullable()->default(null);
            $table->integer('piezas')->nullable()->default(null);
            $table->integer('cajas')->nullable()->default(null);
            $table->string('comprobantes', 250)->nullable()->default(null);
            $table->string('lotes', 250)->nullable()->default(null);
            $table->decimal('peso_total', 10, 2)->nullable()->default(0);
            $table->decimal('peso_real_total', 10, 2)->nullable()->default(0);
            $table->integer('user_id')->unsigned();
        });

        Schema::create('palet_armados_items', function (Blueprint $table) {
            $table->id();
            $table->integer('pallet_armado_id')->unsigned();
            $table->integer('ID_Articulo')->nullable()->default(null);
            $table->string('codigo', 25)->nullable()->default(null);
            $table->string('nombre', 250)->nullable()->default(null);
            $table->string('CodBarraPallet_Int', 100)->nullable()->default(null);
            $table->string('CodBarraCaja_Int', 100)->nullable()->default(null);
            $table->string('CodBarraArt_Int', 100)->nullable()->default(null);
            $table->string('Lote', 100)->nullable()->default(null);
            $table->decimal('Peso_Real', 10, 2)->nullable()->default(0);
            $table->decimal('Peso', 10, 2)->nullable()->default(0);
            $table->date('FechaElaboracion')->nullable()->default(0);
            $table->date('FechaVencimiento')->nullable()->default(0);
            $table->date('fecha_cierre_comprobante')->nullable()->default(0);
            $table->integer('comprobante')->nullable()->default(null);
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
        Schema::dropIfExists('codigo_barra_palet_armados');
        Schema::dropIfExists('palet_armados');
        Schema::dropIfExists('palet_armados_items');
    }
}
