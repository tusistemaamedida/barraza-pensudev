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
        DB::statement("CREATE VIEW dbo.Consulta_EnvasadasArticuloTurno
AS
SELECT     TOP (100) PERCENT dbo.Tabla_Envasado.FechaPesaje, dbo.Tabla_Envasado.ID_Usuario, COUNT(dbo.Tabla_Envasado.ID_Usuario) AS Unidades, dbo.Tabla_Usuario.Descripcion AS Usuario,
                      dbo.Tabla_Articulo.Descripcion AS Articulo
FROM         dbo.Tabla_Envasado LEFT OUTER JOIN
                      dbo.Tabla_Articulo ON dbo.Tabla_Envasado.ID_Articulo = dbo.Tabla_Articulo.Id LEFT OUTER JOIN
                      dbo.Tabla_Usuario ON dbo.Tabla_Envasado.ID_Usuario = dbo.Tabla_Usuario.Id
GROUP BY dbo.Tabla_Envasado.FechaPesaje, dbo.Tabla_Envasado.ID_Usuario, dbo.Tabla_Usuario.Descripcion, dbo.Tabla_Articulo.Descripcion
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
        DB::statement("DROP VIEW IF EXISTS [Consulta_EnvasadasArticuloTurno]");
    }
};
