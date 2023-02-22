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
        DB::connection('envasado')->statement("CREATE VIEW dbo.Consulta_Envasado
AS
SELECT     dbo.Tabla_Envasado.Id, dbo.Tabla_Articulo.Codigo, dbo.Tabla_Articulo.Descripcion AS Articulo, dbo.Tabla_Usuario.Descripcion AS Usuario, dbo.Tabla_Establecimiento.RazonSocial, 
                      dbo.Tabla_Envasado.FechaPesaje, dbo.Tabla_Envasado.HoraPesaje, dbo.Tabla_Envasado.Lote, dbo.Tabla_Envasado.NrodePieza, dbo.Tabla_Envasado.NrodeCaja, 
                      dbo.Tabla_Envasado.NrodePallet, dbo.Tabla_Envasado.FechaElaboracion, dbo.Tabla_Envasado.FechaVencimiento, dbo.Tabla_Envasado.Peso, dbo.Tabla_Envasado.CodBarraArt_Int, 
                      dbo.Tabla_Envasado.CodBarraCaja_Int, dbo.Tabla_Envasado.CodBarraPallet_Int, dbo.Tabla_Envasado.ID_Articulo, dbo.Tabla_Envasado.Peso_Real, dbo.Sinonimo_Insumo.Id AS IdIns, 
                      dbo.Sinonimo_Insumo.Descripcion AS Insumo, dbo.Tabla_Articulo.PiezasPorCaja
FROM         dbo.Tabla_Establecimiento RIGHT OUTER JOIN
                      dbo.Tabla_Envasado ON dbo.Tabla_Establecimiento.Id = dbo.Tabla_Envasado.ID_Establecimiento LEFT OUTER JOIN
                      dbo.Tabla_Usuario ON dbo.Tabla_Envasado.ID_Usuario = dbo.Tabla_Usuario.Id LEFT OUTER JOIN
                      dbo.Tabla_Articulo LEFT OUTER JOIN
                      dbo.Sinonimo_Insumo ON dbo.Tabla_Articulo.ID_Insumo = dbo.Sinonimo_Insumo.Id_TablaAux ON dbo.Tabla_Envasado.ID_Articulo = dbo.Tabla_Articulo.Id
");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::connection('envasado')->statement("DROP VIEW IF EXISTS [Consulta_Envasado]");
    }
};
