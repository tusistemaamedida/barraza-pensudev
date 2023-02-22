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
        Schema::table('Tabla_Envasado', function (Blueprint $table) {
            $table->foreign(['ID_Establecimiento'], 'Tabla_Envasado_FK01')->references(['Id'])->on('Tabla_Establecimiento')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('Tabla_Envasado', function (Blueprint $table) {
            $table->dropForeign('Tabla_Envasado_FK01');
        });
    }
};
