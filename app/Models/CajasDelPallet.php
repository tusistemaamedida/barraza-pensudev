<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CajasDelPallet extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'id_articulo',
        'codigo',
        'nombre',
        'nro_pieza',
        'nro_caja',
        'nro_pallet',
        'lote',
        'peso',
        'peso_real',
        'codigo_barras_articulo',
        'codigo_barras_caja',
        'codigo_barras_pallet',
        'manual'
    ];

    public function producto(){
        return $this->belongsTo(Producto::class, 'ID_Articulo', 'Id');
    }

    public function producto2(){
        return $this->belongsTo(Producto::class, 'id_articulo', 'Id');
    }
}
