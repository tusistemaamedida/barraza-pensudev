<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pallets extends Model
{
    use HasFactory;
    //protected $connection = 'envasado';
    protected $table = 'Tabla_Envasado';

    public function producto(){
        return $this->belongsTo(Producto::class, 'ID_Articulo', 'Id');
    }
}
