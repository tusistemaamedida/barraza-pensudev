<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePalletEnPreparacionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pallets_en_preparacion', function (Blueprint $table) {
            $table->id();
            $table->date('fecha')->nullable()->default(null);
            $table->time('hora')->nullable()->default(null);
            $table->string('token', 32)->nullable()->default(null);
            $table->string('estado', 32)->nullable()->default('PENDIENTE CIERRE');
            $table->integer('user_id')->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pallets_en_preparacion');
    }
}
