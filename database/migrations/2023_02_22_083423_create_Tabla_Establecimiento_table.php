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
        Schema::create('Tabla_Establecimiento', function (Blueprint $table) {
            $table->increments('Id');
            $table->string('RazonSocial', 40)->nullable();
            $table->string('Direccion', 50)->nullable();
            $table->string('Telefono', 30)->nullable();
            $table->string('Mail', 40)->nullable();
            $table->string('RNE', 30)->nullable();
            $table->string('SENASA', 30)->nullable();
            $table->string('CUIT', 20)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Tabla_Establecimiento');
    }
};
