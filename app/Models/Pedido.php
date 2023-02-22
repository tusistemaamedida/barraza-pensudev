<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;
    protected $connection = 'pedidos';
    public $timestamps = false;
    protected $table = 'Pedidos';
    protected $primaryKey = 'NroCom';
    protected $fillable = [
        'Estado'
	];

    public function cliente(){
        return $this->belongsTo(Cliente::class, 'IdClie', 'IdClie');
    }

    public function sucursal(){
        return $this->belongsTo(Sucursal::class, 'ClDomi', 'itemDo');
    }

    public function items(){
        return $this->hasMany(PedidoItem::class, 'NroCom', 'NroCom');
    }

    public function itemsMov(){
        return $this->hasMany(PedItemMov::class, 'NroCom', 'NroCom');
    }
}
