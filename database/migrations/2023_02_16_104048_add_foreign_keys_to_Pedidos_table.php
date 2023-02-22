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
        Schema::connection('pedidos')->table('Pedidos', function (Blueprint $table) {
            $table->foreign(['IdClie'], 'FK_Pedidos_Clientes')->references(['IdClie'])->on('Clientes')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['IdClie', 'ClDomi'], 'FK_Pedidos_ClientesDomici')->references(['idClie', 'itemDo'])->on('ClientesDomici')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('pedidos')->table('Pedidos', function (Blueprint $table) {
            $table->dropForeign('FK_Pedidos_Clientes');
            $table->dropForeign('FK_Pedidos_ClientesDomici');
        });
    }
};
