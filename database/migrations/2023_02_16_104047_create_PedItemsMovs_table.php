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
        Schema::create('PedItemsMovs', function (Blueprint $table) {
            $table->string('IdTipo', 4);
            $table->decimal('NroCom', 12, 0);
            $table->string('IdArti', 12);
            $table->integer('itemPI');
            $table->integer('itemPM');
            $table->decimal('Cantid', 18);
            $table->decimal('CanUni', 18)->nullable();
            $table->string('NroLote', 30)->nullable();

            $table->primary(['IdTipo', 'NroCom', 'IdArti', 'itemPI', 'itemPM'], 'PK_PedItemsMovs');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('PedItemsMovs');
    }
};
