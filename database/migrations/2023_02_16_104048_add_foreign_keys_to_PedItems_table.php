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
        Schema::table('PedItems', function (Blueprint $table) {
            $table->foreign(['IdTipo', 'NroCom'], 'FK_PedItems_Pedidos')->references(['IdTipo', 'NroCom'])->on('Pedidos')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('PedItems', function (Blueprint $table) {
            $table->dropForeign('FK_PedItems_Pedidos');
        });
    }
};
