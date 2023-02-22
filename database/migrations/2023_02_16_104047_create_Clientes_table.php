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
        Schema::create('Clientes', function (Blueprint $table) {
            $table->string('IdClie', 5);
            $table->string('RazSoc', 40);
            $table->string('NomFan', 30)->nullable();
            $table->string('Domici', 30);
            $table->string('Locali', 30);
            $table->string('CodPos', 8);
            $table->string('IdPrvc', 4);
            $table->string('Telefo', 40)->nullable();
            $table->string('Respon', 30)->nullable();
            $table->string('IdCIVA', 4);
            $table->string('NuCuit', 15);
            $table->string('IdCond', 4);
            $table->string('IdCobr', 4);
            $table->string('IdVend', 4);
            $table->string('IdZona', 4);
            $table->string('IdTran', 4);
            $table->decimal('Bonifi', 6);
            $table->decimal('Margen', 18);
            $table->dateTime('FecAlt')->nullable();
            $table->dateTime('FecBaj')->nullable();
            $table->dateTime('FecUco')->nullable();
            $table->string('IdEsta', 4);
            $table->string('Observ')->nullable();
            $table->string('IdRubr', 4)->nullable();
            $table->string('IdSubR', 4)->nullable();
            $table->string('DomEnt', 30)->nullable();
            $table->string('LocEnt', 30)->nullable();
            $table->string('CoPEnt', 8)->nullable();
            $table->string('IdPEnt', 4)->nullable();
            $table->string('e_mail', 100)->nullable();
            $table->dateTime('VtoCer')->nullable();
            $table->string('TipCli', 1)->nullable();
            $table->string('NroMat', 50)->nullable();
            $table->string('DirTec', 50)->nullable();
            $table->dateTime('FeVTCo')->nullable();
            $table->string('NroIBr', 20)->nullable();
            $table->string('TiDocu', 4)->nullable();
            $table->string('ExenPB', 1)->nullable();
            $table->dateTime('ExPBde')->nullable();
            $table->dateTime('ExPBha')->nullable();
            $table->string('HorEnt', 50)->nullable();
            $table->string('DiaEnt', 6)->nullable();
            $table->string('idLoca', 4)->nullable();
            $table->string('lispre', 1)->nullable();
            $table->string('GeoLat', 18)->nullable();
            $table->string('GeoLon', 18)->nullable();
            $table->string('GeoDir', 200)->nullable();
            $table->string('ExenPF', 1)->nullable();
            $table->dateTime('ExPFDe')->nullable();
            $table->dateTime('ExPFHa')->nullable();
            $table->decimal('CM05Co', 18, 4)->nullable();
            $table->dateTime('CM05De')->nullable();
            $table->dateTime('CM05Ha')->nullable();
            $table->string('IBTiCo', 4)->nullable();

            $table->primary(['IdClie'], 'PK_Clientes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Clientes');
    }
};
