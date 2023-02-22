<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PedidoEnPreparacionItem extends Model
{
    use HasFactory;
    protected $table = 'pedido_en_preparacion_items';
    public $timestamps = false;
    protected $fillable = [
        'id_articulo',
        'codigo_articulo',
        'articulo',
        'nro_comp',
        'id_pallet',
        'ubicacion_id',
        'ubicacion',
        'cantidad_solicitada',
        'cantidad_a_descontar',
        'estado'
    ];

    public function s_pallet(){
        return $this->belongsTo(SPallet::class, 'id_pallet', 'id');
    }
}
