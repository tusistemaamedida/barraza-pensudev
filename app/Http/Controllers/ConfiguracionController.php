<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

use App\Models\Deposito;
use App\Models\Altura;
use App\Models\Calle;
use App\Models\Estado;
use App\Models\Profundidad;
use App\Models\Ubicacion;

class ConfiguracionController extends Controller
{
    public function configuraciones(){
        $estados = Estado::all();
        return view('configuraciones', compact('estados'));
    }

    private function generateEAN($number) {
        $number = rand(1,99).'0'.$number;
        $code = '20' . str_pad($number, 10, '0',STR_PAD_LEFT);
        $weightflag = true;
        $sum = 0;
        for ($i = strlen($code) - 1; $i >= 0; $i--) {
            $sum += (int)$code[$i] * ($weightflag ? 3 : 1);
            $weightflag = !$weightflag;
        }
        $code .= (10 - ($sum % 10)) % 10;

        return $code;
    }

    public function crearUbicaciones(Request $request){
        try {
            if(is_null($request->camara)) return new JsonResponse(['msj'  => 'Ingrese el nombre de la cámara','type' => 'error']);
            if(is_null($request->calles) || $request->calles == 0) return new JsonResponse(['msj'  => 'Ingrese la cantidad de calles mayor a cero','type' => 'error']);
            if(is_null($request->niveles) || $request->niveles == 0) return new JsonResponse(['msj'  => 'Ingrese la cantidad de niveles mayor a cero','type' => 'error']);
            if(is_null($request->posiciones) || $request->posiciones == 0) return new JsonResponse(['msj'  => 'Ingrese la cantidad de posiciones mayor a cero','type' => 'error']);
            DB::beginTransaction();
            Schema::disableForeignKeyConstraints();

            $num = (string)Ubicacion::count();
            $dep = Deposito::create([
                'nombre' => $request->camara,
                'codigo_barra' => $this->generateEAN($num ),
                'abrev' => strtoupper(substr($request->camara,0,2)) . $num
            ]);
            // 33 calles
            $calles = $request->calles + 1;
            for ($c=1; $c < $calles; $c++) {
                $ce = Calle::create([
                    'nombre' => 'Calle N°: '.$c,
                    'codigo_barra' => $this->generateEAN($num.$c),
                    'abrev' => 'CALLE'.$c
                ]);
                $niveles = $request->niveles + 1;
                //5 niveles / alturas
                for ($a=1; $a < $niveles; $a++) {
                    $alt = Altura::create([
                        'nombre' => 'Altura N°: '.$a,
                        'codigo_barra' => $this->generateEAN($num.$c.$a),
                        'abrev' => 'AL'.$a
                    ]);
                    $posiciones = $request->posiciones + 1;
                    // 5 posiciones / profundidades
                    for ($p=1; $p < $posiciones; $p++) {
                        $prof = Profundidad::create([
                            'nombre' => $num.$c.$a.$p,
                            'codigo_barra' => $this->generateEAN($num.$c.$a.$p),
                            'abrev' => 'POS'.$p
                        ]);

                        Ubicacion::create([
                            'deposito_id' => $dep->id,
                            'calle_id' => $ce->id,
                            'altura_id' => $alt->id,
                            'profundidad_id' => $prof->id,
                            'pallet' => null
                        ]);
                    }
                }
            }
            DB::commit();
            Schema::enableForeignKeyConstraints();
            return new JsonResponse(['msj'  => 'Ubicaciones creadas correctamente','type' => 'success']);
        } catch (\Exception $e) {
            DB::rollback();
            Schema::enableForeignKeyConstraints();
            return new JsonResponse(['msj'  => $e->getMessage(),'type' => 'error']);
        }
    }

    public function setNewColor(Request $request){
        try {
            $estado = Estado::where('id',$request->id)->firstOrFail();
            $estado->color = $request->color;
            $estado->save();
            return new JsonResponse(['msj'  => 'Color cambiado correctamente','type' => 'success']);
        } catch (\Exception $e) {
            return new JsonResponse(['msj'  => $e->getMessage(),'type' => 'error']);
        }
    }

    public function setDefault(Request $request){
        try {
            Estado::where('default',true)->update(['default' => false]);
            $estado = Estado::where('id',$request->id)->firstOrFail();
            $estado->default = true;
            $estado->save();
            return new JsonResponse(['msj'  => 'Se establecio el estado por defecto correctamente','type' => 'success']);
        } catch (\Exception $e) {
            return new JsonResponse(['msj'  => $e->getMessage(),'type' => 'error']);
        }
    }


}
