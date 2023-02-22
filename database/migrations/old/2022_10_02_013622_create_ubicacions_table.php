<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUbicacionsTable extends Migration
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
            $table->integer('estado_id')->unsigned()->nullable()->default(1);
            $table->integer('deposito_id')->unsigned();
			$table->foreign('deposito_id')->references('id')->on('depositos')->onDelete('cascade')->onUpdate('cascade');
            $table->integer('calle_id')->unsigned();
			$table->foreign('calle_id')->references('id')->on('calles')->onDelete('cascade')->onUpdate('cascade');
            $table->integer('altura_id')->unsigned();
			$table->foreign('altura_id')->references('id')->on('alturas')->onDelete('cascade')->onUpdate('cascade');
            $table->integer('profundidad_id')->unsigned();
			$table->foreign('profundidad_id')->references('id')->on('profundidades')->onDelete('cascade')->onUpdate('cascade');
            $table->string('pallet', 150)->nullable()->default(null);
            $table->decimal('peso_total', 10, 2)->nullable()->default(0);
            $table->decimal('peso_real_total', 10, 2)->nullable()->default(0);
            $table->decimal('piezas_total', 10, 2)->nullable()->default(0);
            $table->decimal('cajas', 10, 2)->nullable()->default(0);
            $table->string('fecha', 25)->nullable()->default(null);
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
}
