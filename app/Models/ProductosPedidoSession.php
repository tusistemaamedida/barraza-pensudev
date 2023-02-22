<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductosPedidoSession extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'pedido_en_prep_id',
        'nro_pieza',
        'nro_caja',
        'nro_pallet',
        'lote',
        'peso',
        'peso_real',
        'codigo_barras_articulo',
        'codigo_barras_caja',
        'codigo_barras_pallet',
        'tipo_mov'
    ];
}
