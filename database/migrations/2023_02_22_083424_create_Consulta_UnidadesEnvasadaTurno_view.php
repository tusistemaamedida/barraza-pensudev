<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection('envasado')->statement("CREATE VIEW dbo.Consulta_UnidadesEnvasadaTurno
AS
SELECT     TOP (100) PERCENT dbo.Tabla_Envasado.FechaPesaje, dbo.Tabla_Envasado.ID_Usuario, COUNT(dbo.Tabla_Envasado.ID_Usuario) AS cuenta, dbo.Tabla_Usuario.Descripcion
FROM         dbo.Tabla_Envasado LEFT OUTER JOIN
                      dbo.Tabla_Usuario ON dbo.Tabla_Envasado.ID_Usuario = dbo.Tabla_Usuario.Id
GROUP BY dbo.Tabla_Envasado.FechaPesaje, dbo.Tabla_Envasado.ID_Usuario, dbo.Tabla_Usuario.Descripcion
ORDER BY dbo.Tabla_Envasado.FechaPesaje
");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::connection('envasado')->statement("DROP VIEW IF EXISTS [Consulta_UnidadesEnvasadaTurno]");
    }
};
