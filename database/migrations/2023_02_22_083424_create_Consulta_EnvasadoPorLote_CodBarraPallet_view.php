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
        DB::connection('envasado')->statement("CREATE VIEW dbo.Consulta_EnvasadoPorLote_CodBarraPallet
AS
SELECT     TOP (100) PERCENT dbo.Tabla_Usuario.Descripcion AS Usuario, dbo.Tabla_Articulo.Codigo, dbo.Tabla_Articulo.Descripcion AS Articulo, dbo.Tabla_Envasado.FechaElaboracion, 
                      dbo.Tabla_Envasado.Lote, SUM(dbo.Tabla_Envasado.Peso_Real) AS Kg, COUNT(dbo.Tabla_Envasado.Peso_Real) AS Unidades, AVG(dbo.Tabla_Envasado.Peso_Real) AS Promedio, 
                      dbo.Tabla_Envasado.CodBarraPallet_Int
FROM         dbo.Tabla_Articulo INNER JOIN
                      dbo.Tabla_Envasado ON dbo.Tabla_Articulo.Id = dbo.Tabla_Envasado.ID_Articulo INNER JOIN
                      dbo.Tabla_Usuario ON dbo.Tabla_Envasado.ID_Usuario = dbo.Tabla_Usuario.Id
GROUP BY dbo.Tabla_Articulo.Descripcion, dbo.Tabla_Usuario.Descripcion, dbo.Tabla_Envasado.FechaElaboracion, dbo.Tabla_Envasado.Lote, dbo.Tabla_Envasado.CodBarraPallet_Int, 
                      dbo.Tabla_Articulo.Codigo
ORDER BY dbo.Tabla_Envasado.FechaElaboracion, Usuario
");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::connection('envasado')->statement("DROP VIEW IF EXISTS [Consulta_EnvasadoPorLote_CodBarraPallet]");
    }
};
