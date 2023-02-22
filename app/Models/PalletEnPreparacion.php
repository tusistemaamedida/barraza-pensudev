<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PalletEnPreparacion extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'pallets_en_preparacion';

    protected $fillable = [
        'fecha',
        'hora',
        'token',
        'estado',
        'user_id'
    ];

    public function piezas(){
        return $this->hasMany(PalletEnPreparacionItem::class, 'pallet_en_preparacion_id', 'id');
    }

    public function cajas(){
        return PalletEnPreparacionItem::where('pallet_en_preparacion_id',$this->id)->distinct('codigo_barras_caja')->count();
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
