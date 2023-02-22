<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Historial extends Model
{
    use HasFactory;
    protected $table = 'historial';
    public $timestamps = false;
    protected $fillable = [
        'CodBarraPallet_Int',
        'descripcion',
        'fecha',
        'hora',
        'user_id',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
