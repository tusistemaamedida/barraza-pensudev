<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->bigInteger('documento')->nullable()->default(null);
            $table->bigInteger('telefono')->nullable()->default(null);
            $table->string('direccion',255)->nullable()->default(null);
            $table->string('legajo')->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('rol', ['ADMIN', 'OPERARIO','GERENTE','GERENTE PLANTA','SUPERVISOR','OPERARIO ENTRADA','OPERARIO SALIDA'])->nullable()->default('ADMIN');
            $table->boolean('activo')->nullable()->default(true);
            $table->rememberToken();
            $table->string('created_at')->nullable()->default(null);
            $table->string('updated_at')->nullable()->default(null);
            $table->string('deleted_at')->nullable()->default(null);
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
}
