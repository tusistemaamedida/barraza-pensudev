<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Models\Pallets;

use App\Models\Deposito;
use App\Models\Producto;
use App\Models\SPallet;
use App\Models\Ubicacion;

class UbicacionesController extends Controller
{
    public function getDepositos(){
        $depositos = Deposito::orderBy('id','ASC')->get();
        $articulos_envasados = Producto::select('Id','Codigo','Descripcion')->orderBy('Codigo','ASC')->get();
        return view('ubicaciones.index', compact('depositos','articulos_envasados'));
    }

    public function getCalles(Request $request){
        $dep_id = $request->dep_id;
        $calles = Ubicacion::select('calle_id')->where('deposito_id',$dep_id)->groupBy('calle_id')->with('calle')->orderBy('calle_id','ASC')->get();
        return new JsonResponse(['calles' => $calles]);
    }

    public function getUbicaciones(Request $request){
        $dep_id = $request->dep_id;
        $calle_id = $request->calle_id;
        $ubicaciones    = Ubicacion::where('deposito_id',$dep_id)->where('calle_id',$calle_id)->with(['altura','profundidad'])->orderBy('profundidad_id','DESC')->get();
        $alturas        = count(Ubicacion::select('altura_id')->where('deposito_id',$dep_id)->where('calle_id',$calle_id)->groupBy('altura_id')->get());
        $profundidades  = count($ubicaciones) / $alturas;
        $html = view('ubicaciones.ubicaciones', compact('ubicaciones','profundidades'))->render();
        return new JsonResponse(['html' => $html]);
    }

    public function verUbicacion($idUbicacion,$idSPallet){
        $sPallet        = SPallet::where('id',$idSPallet)->first();
        $ubicacion      = Ubicacion::where('id',$idUbicacion)->first();
        $ubicaciones    = Ubicacion::where('deposito_id',$ubicacion->deposito_id)->where('calle_id',$ubicacion->calle_id)->with(['altura','profundidad'])->orderBy('profundidad_id','DESC')->get();
        $alturas        = count(Ubicacion::select('altura_id')->where('deposito_id',$ubicacion->deposito_id)->where('calle_id',$ubicacion->calle_id)->groupBy('altura_id')->get());
        $profundidades  = count($ubicaciones) / $alturas;

        return view('ubicaciones.ver-ubicacion',compact('ubicaciones','profundidades','sPallet'));
    }
}
