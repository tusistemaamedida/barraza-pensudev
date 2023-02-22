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
        Schema::create('Tabla_Articulo', function (Blueprint $table) {
            $table->increments('Id');
            $table->float('ID_Insumo', 0, 0)->nullable();
            $table->string('Codigo', 10)->nullable();
            $table->string('Descripcion')->nullable();
            $table->string('RNPA')->nullable();
            $table->string('DiaVencimiento')->nullable();
            $table->string('EtiquetaPieza')->nullable();
            $table->float('CantEtiquetasPorPieza', 0, 0)->nullable();
            $table->float('PiezasPorCaja', 0, 0)->nullable();
            $table->string('EtiquetaCaja')->nullable();
            $table->float('CantEtiquetasPorCaja', 0, 0)->nullable();
            $table->float('CajasPorPallet', 0, 0)->nullable();
            $table->string('EtiquetaPallet')->nullable();
            $table->float('CantEtiquetasPorPallet', 0, 0)->nullable();
            $table->string('CodigoBarraArticulo_GS1')->nullable();
            $table->string('CodigoBarraCaja_GS1')->nullable();
            $table->float('PesoNominal', 0, 0)->nullable();
            $table->float('LargoMax', 0, 0)->nullable();
            $table->string('ConPesoNominal')->nullable();
            $table->string('upsize_ts')->nullable();
            $table->string('ConFechaVencimiento')->nullable();
            $table->integer('DiasAlmacenamiento')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Tabla_Articulo');
    }
};
