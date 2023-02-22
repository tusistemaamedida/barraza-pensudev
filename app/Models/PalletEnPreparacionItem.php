<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PalletEnPreparacionItem extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'pallet_en_preparacion_items';

    protected $fillable = [
        'id_articulo',
        'codigo_articulo',
        'articulo',
        'pallet_en_preparacion_id',
        'comprobante',
        'nro_pieza',
        'nro_caja',
        'nro_pallet',
        'peso',
        'peso_real',
        'lote',
        'codigo_barras_articulo',
        'codigo_barras_caja',
        'codigo_barras_pallet'
    ];
}
