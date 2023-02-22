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
        Schema::create('palet_armados', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('fecha_preparacion')->nullable();
            $table->date('fecha_cierre')->nullable();
            $table->integer('piezas')->nullable();
            $table->integer('cajas')->nullable();
            $table->string('comprobantes', 250)->nullable();
            $table->string('lotes', 250)->nullable();
            $table->decimal('peso_total', 10)->nullable()->default(0);
            $table->decimal('peso_real_total', 10)->nullable()->default(0);
            $table->integer('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('palet_armados');
    }
};
