<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CodigoBarraPaletArmado extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'codigo_barra_palet_armados';
    protected $fillable = [
        'numero',
        'cantidad'
	];
}
