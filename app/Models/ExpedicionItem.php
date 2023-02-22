<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpedicionItem extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'expedicion_items';

    protected $fillable = [
        'expedicion_id',
        'nro_pieza',
        'nro_caja',
        'nro_pallet',
        'lote',
        'peso',
        'peso_real',
        'codigo_barras_articulo',
        'codigo_barras_caja',
        'codigo_barras_pallet',
        'estado',
        'ubicacion_id',
        'ubicacion'
    ];

    public function expedicion(){
        return $this->belongsTo(Expedicion::class);
    }
}
