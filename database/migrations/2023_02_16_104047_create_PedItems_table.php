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
        Schema::connection('pedidos')->create('PedItems', function (Blueprint $table) {
            $table->string('IdTipo', 4);
            $table->decimal('NroCom', 12, 0);
            $table->string('IdArti', 12);
            $table->dateTime('FecCom');
            $table->decimal('Cantid', 18);
            $table->decimal('SaldoP', 18);
            $table->decimal('Bonifi', 18);
            $table->decimal('Precio', 18);
            $table->decimal('CanUni', 18)->nullable();
            $table->integer('itemPI');
            $table->decimal('PreFin', 18)->nullable();
            $table->char('artbon', 1)->nullable();

            $table->primary(['IdTipo', 'NroCom', 'IdArti', 'itemPI'], 'PK_PedItems_1__13');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('pedidos')->dropIfExists('PedItems');
    }
};
