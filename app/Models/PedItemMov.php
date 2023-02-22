<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PedItemMov extends Model
{
    use HasFactory;
    //protected $connection = 'pedidos';
    protected $table = 'PedItemsMovs';
    protected $casts = [
		'IdArti' => 'int'
	];
    public $timestamps = false;
    protected $fillable = [
        'IdTipo',
        'NroCom',
        'IdArti',
        'itemPI',
        'itemPM',
        'Cantid',
        'CanUni',
        'NroLote'
    ];

    public function producto(){
        return $this->belongsTo(Producto::class, 'IdArti', 'Codigo');
    }
}
