<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SPallet extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $casts = [
		'fecha_elaboracion' => 'date'
	];

    protected $fillable = [
		'estado_id',
        'codigo',
        'nombre',
        'id_articulo',
        'codigo_barras',
        'lote',
        'piezas',
        'peso',
        'peso_real',
        'fecha_elaboracion',
        'fecha_vencimiento',
        'dias_almacenamiento',
        'manual'
	];

    public function estado(){
        return $this->belongsTo(Estado::class);
    }

    public function ubicacion(){
        return $this->belongsTo(Ubicacion::class, 'codigo_barras', 'pallet');
    }

    public function piezasTotales(){
        $piezas = $this->where('codigo',$this->codigo)->select(DB::raw("SUM(piezas) as totales"))->first();
        return $piezas->totales;
    }

    public function producto(){
        return $this->belongsTo(Producto::class, 'id_articulo', 'Id');
    }

    public function piezas_envasadas(){
        return $this->belongsTo(CajasDelPallet::class, 'codigo_barras', 'codigo_barras_pallet');
    }
}
