<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateColumnToPedidoProductoSession extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('productos_pedido_sessions', function (Blueprint $table) {
            $table->renameColumn('pedido_en_prep_id', 'pedido_en_prep_id')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('productos_pedido_sessions', function (Blueprint $table) {
            //
        });
    }
}
