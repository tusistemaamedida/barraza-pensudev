<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pallets extends Model
{
    use HasFactory;
    //protected $connection = 'envasado';
    protected $table = 'tabla_envasado';

    public function producto(){
        return $this->belongsTo(Producto::class, 'ID_Articulo', 'Id');
    }
}
