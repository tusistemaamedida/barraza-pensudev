<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSPalletsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('s_pallets', function (Blueprint $table) {
            $table->id();
            $table->integer('id_articulo')->nullable()->default(null);
            $table->string('codigo', 25)->nullable()->default(null);
            $table->integer('estado_id')->unsigned()->nullable()->default(1);
            $table->string('nombre', 250)->nullable()->default(null);
            $table->string('codigo_barras', 100)->nullable()->default(null);
            $table->string('lote', 100)->nullable()->default(null);
            $table->decimal('piezas', 10, 2)->nullable()->default(0);
            $table->decimal('peso', 10, 2)->nullable()->default(0);
            $table->decimal('peso_real', 10, 2)->nullable()->default(0);
            $table->integer('dias_almacenamiento')->unsigned()->nullable()->default(0);
            $table->date('fecha_elaboracion')->nullable()->default(0);
            $table->date('fecha_vencimiento')->nullable()->default(0);
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
}
