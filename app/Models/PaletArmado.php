<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaletArmado extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'palet_armados';
    protected $fillable = [
        'fecha_preparacion',
        'fecha_cierre',
        'piezas',
		'cajas',
        'comprobantes',
        'lotes',
        'peso_total',
        'peso_real_total',
        'user_id'
	];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function items(){
        return $this->hasMany(PaletArmadoItem::class, 'pallet_armado_id', 'id');
    }
}
