<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ubicacion extends Model
{
    use HasFactory;
    protected $table = 'ubicaciones';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function calle(){
        return $this->belongsTo(Calle::class);
    }

    public function altura(){
        return $this->belongsTo(Altura::class);
    }

    public function profundidad(){
        return $this->belongsTo(Profundidad::class);
    }

    public function estado(){
        return $this->belongsTo(Estado::class);
    }

    public function camara(){
        return $this->belongsTo(Deposito::class,'deposito_id','id');
    }

    public function s_pallet(){
        return $this->hasMany(SPallet::class,'codigo_barras','pallet');
    }

    public function u_nombre(){
        return $this->camara->nombre .' '.$this->calle->nombre . ' '.$this->altura->nombre .' '.$this->profundidad->nombre;
    }

}
