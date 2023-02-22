<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpedicionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expediciones', function (Blueprint $table) {
            $table->id();
            $table->integer('id_articulo')->nullable()->default(null);
            $table->string('codigo_articulo',10)->nullable()->default(null);
            $table->string('articulo',250)->nullable()->default(null);
            $table->string('nro_comp',250)->nullable()->default(null);
            $table->decimal('cantidad_solicitada', 10, 2)->nullable()->default(0);
            $table->decimal('piezas_cargada', 10, 2)->nullable()->default(0);
            $table->decimal('cajas_cargada', 10, 2)->nullable()->default(0);
            $table->integer('user_id')->nullable()->default(null);
            $table->date('fecha')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('expediciones');
    }
}
