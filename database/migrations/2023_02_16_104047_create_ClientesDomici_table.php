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
        Schema::connection('pedidos')->create('ClientesDomici', function (Blueprint $table) {
            $table->string('idClie', 5);
            $table->integer('itemDo');
            $table->string('IdDoTi', 4);
            $table->string('Nombre', 60)->nullable();
            $table->string('Domici', 60);
            $table->string('Locali')->nullable();
            $table->string('CodPos', 8)->nullable();
            $table->string('idPrvc', 4);
            $table->string('Telefo', 60)->nullable();
            $table->string('Observ', 1000)->nullable();
            $table->string('idTran', 4)->nullable();
            $table->string('DiaEnt', 6)->nullable();
            $table->string('idLoca', 4)->nullable();
            $table->string('idZona', 4)->nullable();
            $table->string('IdVend', 4)->nullable();
            $table->string('GeoLat', 18)->nullable();
            $table->string('GeoLon', 18)->nullable();
            $table->string('GeoDir', 200)->nullable();
            $table->string('GLNCod', 50)->nullable();

            $table->primary(['idClie', 'itemDo'], 'PK_ClientesDomici');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('pedidos')->dropIfExists('ClientesDomici');
    }
};
