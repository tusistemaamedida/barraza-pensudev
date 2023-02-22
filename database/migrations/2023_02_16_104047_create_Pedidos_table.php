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
        Schema::create('Pedidos', function (Blueprint $table) {
            $table->string('IdTipo', 4);
            $table->decimal('NroCom', 12, 0);
            $table->string('IdClie', 5);
            $table->dateTime('FecCom');
            $table->string('Refere', 30);
            $table->string('IdCIVA', 4);
            $table->string('IdDepo', 4);
            $table->string('IdVend', 4);
            $table->decimal('comisi', 18)->nullable();
            $table->string('IdZona', 4);
            $table->string('IdTran', 4);
            $table->string('IdCond', 4);
            $table->decimal('BonCli', 18);
            $table->decimal('BonCom', 18);
            $table->decimal('NetoGr', 18);
            $table->decimal('NetoNG', 18)->nullable();
            $table->decimal('IvaIns', 18);
            $table->decimal('IvaNoi', 18);
            $table->decimal('Descto', 18);
            $table->decimal('TotalC', 18);
            $table->string('Estado', 4)->nullable();
            $table->string('UsrMov', 15);
            $table->string('Observ')->nullable();
            $table->char('Impres', 1)->nullable();
            $table->integer('Bultos')->nullable();
            $table->dateTime('FecEnv')->nullable();
            $table->string('ObsEnv')->nullable();
            $table->integer('ClDomi')->nullable();
            $table->integer('NuRepa')->nullable();
            $table->decimal('NuGuia', 12, 0)->nullable();
            $table->boolean('GruCom')->nullable()->default(false);
            $table->string('ResCOT', 100)->nullable();
            $table->integer('LisPre')->nullable();
            $table->string('idMone', 4)->nullable();
            $table->decimal('cotdol', 18, 4)->nullable();

            $table->primary(['IdTipo', 'NroCom'], 'PK_Pedidos_1__13');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Pedidos');
    }
};
