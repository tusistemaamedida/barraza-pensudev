<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaletArmadoItem extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'palet_armados_items';

    protected $fillable = [
        'pallet_armado_id',
        'comprobante',
        'codigo',
        'nombre',
		'ID_Articulo',
        'FechaElaboracion',
        'FechaVencimiento',
        'Lote',
        'Peso_Real',
        'Peso',
        'CodBarraPallet_Int',
        'CodBarraCaja_Int',
        'CodBarraArt_Int',
        'estado',
        'fecha_cierre_comprobante'
	];

    public function producto(){
        return $this->belongsTo(Producto::class, 'ID_Articulo', 'Id');
    }
}
