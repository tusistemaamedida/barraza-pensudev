<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expedicion extends Model
{
    use HasFactory;
    protected $table = 'expediciones';
    public $timestamps = false;
    protected $fillable = [
        'id_articulo',
        'codigo_articulo',
        'articulo',
        'nro_comp',
        'cantidad_solicitada',
        'piezas_cargada',
        'cajas_cargada',
        'user_id',
        'fecha'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function items(){
        return $this->hasMany(ExpedicionItem::class, 'expedicion_id', 'id');
    }
}
