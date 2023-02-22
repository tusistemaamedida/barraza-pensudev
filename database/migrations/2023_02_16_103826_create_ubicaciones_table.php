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
        Schema::create('ubicaciones', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('estado_id')->nullable()->default(1);
            $table->integer('deposito_id');
            $table->integer('calle_id');
            $table->integer('altura_id');
            $table->integer('profundidad_id');
            $table->string('pallet', 150)->nullable();
            $table->decimal('peso_total', 10)->nullable()->default(0);
            $table->decimal('peso_real_total', 10)->nullable()->default(0);
            $table->decimal('piezas_total', 10)->nullable()->default(0);
            $table->decimal('cajas', 10)->nullable()->default(0);
            $table->string('fecha', 25)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ubicaciones');
    }
};
