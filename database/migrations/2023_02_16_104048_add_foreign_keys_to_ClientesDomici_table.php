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
        Schema::connection('pedidos')->table('ClientesDomici', function (Blueprint $table) {
            $table->foreign(['idClie'], 'FK_ClientesDomici_Clientes')->references(['IdClie'])->on('Clientes')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('pedidos')->table('ClientesDomici', function (Blueprint $table) {
            $table->dropForeign('FK_ClientesDomici_Clientes');
        });
    }
};
