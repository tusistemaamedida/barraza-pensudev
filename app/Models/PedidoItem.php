<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PedidoItem extends Model
{
    use HasFactory;
    protected $connection = 'pedidos';
    protected $table = 'PedItems';

    protected $casts = [
		'IdArti' => 'int'
	];

    public function pedido(){
        return $this->belongsTo(Pedido::class, 'NroCom', 'NroCom');
    }

    public function producto(){
        return $this->belongsTo(Producto::class, 'IdArti', 'Codigo');
    }
}
