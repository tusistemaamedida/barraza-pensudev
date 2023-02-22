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
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nombre');
            $table->bigInteger('documento')->nullable();
            $table->bigInteger('telefono')->nullable();
            $table->string('direccion')->nullable();
            $table->string('legajo')->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('rol', ['ADMIN', 'OPERARIO', 'GERENTE', 'GERENTE PLANTA', 'SUPERVISOR', 'OPERARIO ENTRADA', 'OPERARIO SALIDA'])->nullable()->default('ADMIN');
            $table->boolean('activo')->nullable()->default(true);
            $table->rememberToken();
            $table->string('created_at')->nullable();
            $table->string('updated_at')->nullable();
            $table->string('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
