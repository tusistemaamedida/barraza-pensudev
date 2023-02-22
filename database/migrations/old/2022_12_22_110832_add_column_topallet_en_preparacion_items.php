<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnTopalletEnPreparacionItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pallet_en_preparacion_items', function (Blueprint $table) {
            $table->integer('id_articulo')->nullable()->default(null);
            $table->string('codigo_articulo',10)->nullable()->default(null);
            $table->string('articulo',250)->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pallet_en_preparacion_items', function (Blueprint $table) {
            //
        });
    }
}
