<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lote extends Model
{
    use HasFactory;
    protected $connection = 'envasado';
    protected $table = 'Tabla_Envasado';

}
