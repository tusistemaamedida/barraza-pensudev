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
        Schema::table('ubicaciones', function (Blueprint $table) {
            $table->foreign(['deposito_id'])->references(['id'])->on('depositos')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['calle_id'])->references(['id'])->on('calles')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['altura_id'])->references(['id'])->on('alturas')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['profundidad_id'])->references(['id'])->on('profundidades')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ubicaciones', function (Blueprint $table) {
            $table->dropForeign('ubicaciones_deposito_id_foreign');
            $table->dropForeign('ubicaciones_calle_id_foreign');
            $table->dropForeign('ubicaciones_altura_id_foreign');
            $table->dropForeign('ubicaciones_profundidad_id_foreign');
        });
    }
};
