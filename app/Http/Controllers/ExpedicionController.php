<?php

namespace App\Http\Controllers;

use App\Models\CodigoBarraPaletArmado;
use App\Models\Expedicion;
use App\Models\ExpedicionItem;
use App\Models\Historial;
use App\Models\PaletArmado;
use App\Models\PaletArmadoItem;
use App\Models\PalletEnPreparacion;
use App\Models\PalletEnPreparacionItem;
use App\Models\Pedido;
use App\Models\PedidoEnPreparacionItem;
use App\Models\PedidoItem;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;
use stdClass;

class ExpedicionController extends Controller
{
    public function index(){
        return view('expedicion.index');
    }

    public function getExpediciones(Request $request){
        if ($request->ajax()) {
           $expediciones = Expedicion::with('user')->orderBy('id','ASC')->get();

            return Datatables::of($expediciones)
                ->addColumn('cod_articulo', function ($exp) {
                    return $exp->codigo_articulo.' - '.$exp->articulo;
                })
                ->addColumn('fecha_format', function ($exp) {
                    return Carbon::parse($exp->fecha)->format('d/m/Y');
                })
                ->addColumn('usuario', function ($exp) {
                    return $exp->user->nombre;
                })
                ->addColumn('ts', function ($exp) {
                    return '<div class="py-1 px-2 rounded-full text-xs bg-primary text-white text-center font-medium">'.(int)$exp->cantidad_solicitada.'</div>';
                })
                ->addColumn('pp', function ($exp) {
                    if($exp->piezas_cargada > 0){
                        return '<div class="py-1 px-2 rounded-full text-xs bg-success text-white text-center font-medium">'.(int)$exp->piezas_cargada.'</div>';
                    }else{
                        return '<div class="py-1 px-2 rounded-full text-xs bg-danger text-white text-center font-medium">'.(int)$exp->piezas_cargada.'</div>';
                    }
                })
                ->addColumn('cp', function ($exp) {
                    if($exp->piezas_cargada > 0){
                        return '<div class="py-1 px-2 rounded-full text-xs bg-success text-white text-center font-medium">'.(int)$exp->cajas_cargada.'</div>';
                    }else{
                        return '<div class="py-1 px-2 rounded-full text-xs bg-danger text-white text-center font-medium">'.(int)$exp->cajas_cargada.'</div>';
                    }
                })
                ->addColumn('editar', function ($exp) {
                    $cs = (int)$exp->cantidad_solicitada;
                    $pc = (int)$exp->piezas_cargada;
                    if(($cs - $pc) > 0){
                        $explode = explode('|',$exp->nro_comp);
                        $comprobanes = implode('-',$explode);
                        $actionBtn = "<a style='cursor:pointer' ";
                        $actionBtn .= 'onclick="editarExpedicion(';
                        $actionBtn .= "'".$exp->nro_comp."',";
                        $actionBtn .= $exp->id;
                        $actionBtn .= ')"  class="flex items-center text-danger" href="javascript:;"> <i class="fa fa-edit"></i> Editar </a>';
                    }else{
                        $actionBtn = '--';
                    }
                    return $actionBtn;
                })
                ->rawColumns(['fecha_format','usuario','editar','pp','cp','ts'])
                ->addIndexColumn()
                ->make(true);
        }
    }

    public function prepararPalet($token = null){
        $pedidos = [];
        $expedicion = Expedicion::select('nro_comp')->get();
        $palet_en_prep = PalletEnPreparacion::has('piezas','=',0)->where('estado','PENDIENTE CIERRE')->first();
        if($palet_en_prep && !$token){
            $token = $palet_en_prep->token;
        }
        if(!$token){
            $token = Str::random(32);
            PalletEnPreparacion::create([
                'token' => $token,
                'user_id' => Auth::user()->id,
                'fecha' => Carbon::parse(now())->format('Y-m-d'),
                'hora' => Carbon::parse(now())->format('H:i'),
            ]);
        }
        if(count($expedicion)){
            $array_implode = implode(' | ',array_unique($expedicion->pluck('nro_comp')->toArray()));
            $array_explode = explode(' | ',$array_implode);
            $nros_comp = array_unique($array_explode);
            $pedidos = Pedido::where('Estado','P')->whereIn('NroCom',$nros_comp)->with('cliente','items','sucursal')->orderBy('NroCom','ASC')->get();
        }
        return view('expedicion.preparar-palet',compact('pedidos','token','palet_en_prep'));
    }

    public function verificarCodbarrasEnExpedicion(Request $request){
        $codigo_barras = $request->codigo_barras;
        $token = $request->token;
        $comprobante = trim($request->comprobante);
        $texto = 'Opps!! No se pudo cargar al palet el codigo de barras ingresado';
        $exp_item = ExpedicionItem::with('expedicion')
                                  ->where('codigo_barras_articulo',$codigo_barras)
                                  ->orWhere('codigo_barras_caja',$codigo_barras)
                                  ->orWhere('codigo_barras_pallet',$codigo_barras)
                                  ->first();
        if($exp_item){
            if($exp_item->estado == 'PREPARADO')  return new JsonResponse([ 'msj'  => 'El código de barras ingresado ya se encuentra en un palet en preparación', 'type' => 'error' ]);
            $pallet_prep = PalletEnPreparacion::where('token',$token)->first();

            $expedicion  = Expedicion::select('nro_comp')->where('id',$exp_item->expedicion_id)->pluck('nro_comp')->toArray();
            $array_implode = implode(' | ',$expedicion);
            $array_explode = explode(' | ',$array_implode);
            $nros_comp = array_unique($array_explode);
            if(!in_array($comprobante,$nros_comp)) return new JsonResponse([ 'msj'  => 'El código de barras no corresponde al pedido', 'type' => 'error' ]);

            //$count_solicitado = Expedicion::where('nro_comp', 'LIKE', "%{$comprobante}%")->where('codigo_articulo',$exp_item->expedicion->codigo_articulo)->first()['cantidad_solicitada'];
            $IdArti = ($exp_item->expedicion->codigo_articulo < 10)?(string)'0'.$exp_item->expedicion->codigo_articulo:(string)$exp_item->expedicion->codigo_articulo;

            $count_solicitado = PedidoItem::where('NroCom',$comprobante)->where('IdArti',$IdArti)->first()['CanUni'];
            $count_cargados   = PalletEnPreparacionItem::where('comprobante',$comprobante)->where('codigo_articulo',$exp_item->expedicion->codigo_articulo)->count();

            if($count_cargados == $count_solicitado || $count_cargados > $count_solicitado) return new JsonResponse([
                'msj'  => 'Para el comprobante '. $comprobante .' ya tiene cargados ' .$count_cargados. '  de '.(int)$count_solicitado .' solicitados para '. $exp_item->expedicion->articulo,
                'type' => 'error'
            ]);

            if($codigo_barras == $exp_item->codigo_barras_articulo){
                if(!is_null($exp_item->estado)) return new JsonResponse([ 'msj'  => 'El código de barras ya se encuentra en un pallet', 'type' => 'error' ]);
                PalletEnPreparacionItem::create([
                    'id_articulo' => $exp_item->expedicion->id_articulo,
                    'codigo_articulo' => $exp_item->expedicion->codigo_articulo,
                    'articulo' => $exp_item->expedicion->articulo,
                    'pallet_en_preparacion_id' => $pallet_prep->id,
                    'comprobante' => $comprobante,
                    'nro_pieza' => $exp_item->nro_pieza,
                    'nro_caja' => $exp_item->nro_caja,
                    'nro_pallet' => $exp_item->nro_pallet,
                    'peso' => $exp_item->peso,
                    'peso_real' => $exp_item->peso_real,
                    'lote' => $exp_item->lote,
                    'codigo_barras_articulo' => $exp_item->codigo_barras_articulo,
                    'codigo_barras_caja' => $exp_item->codigo_barras_caja,
                    'codigo_barras_pallet' => $exp_item->codigo_barras_pallet
                ]);
                $exp_item->estado = 'PREPARADO';
                $exp_item->save();
                $texto = 'Pieza en expedición agregado al nuevo palet';
            }elseif($codigo_barras == $exp_item->codigo_barras_caja){
                $exps = ExpedicionItem::with('expedicion')->where('codigo_barras_caja',$codigo_barras)->whereNull('estado')->get();
                foreach ($exps as $exp_item) {
                    PalletEnPreparacionItem::create([
                        'id_articulo' => $exp_item->expedicion->id_articulo,
                        'codigo_articulo' => $exp_item->expedicion->codigo_articulo,
                        'articulo' => $exp_item->expedicion->articulo,
                        'pallet_en_preparacion_id' => $pallet_prep->id,
                        'comprobante' => $comprobante,
                        'nro_pieza' => $exp_item->nro_pieza,
                        'nro_caja' => $exp_item->nro_caja,
                        'nro_pallet' => $exp_item->nro_pallet,
                        'peso' => $exp_item->peso,
                        'peso_real' => $exp_item->peso_real,
                        'lote' => $exp_item->lote,
                        'codigo_barras_articulo' => $exp_item->codigo_barras_articulo,
                        'codigo_barras_caja' => $exp_item->codigo_barras_caja,
                        'codigo_barras_pallet' => $exp_item->codigo_barras_pallet
                    ]);
                    $exp_item->estado = 'PREPARADO';
                    $exp_item->save();
                }
                $texto = 'Cajas en expedición agregado al nuevo palet';
            }elseif($codigo_barras == $exp_item->codigo_barras_pallet){
                $exps = ExpedicionItem::with('expedicion')->where('codigo_barras_pallet',$codigo_barras)->whereNull('estado')->get();
                foreach ($exps as $exp_item) {
                    PalletEnPreparacionItem::create([
                        'id_articulo' => $exp_item->expedicion->id_articulo,
                        'codigo_articulo' => $exp_item->expedicion->codigo_articulo,
                        'articulo' => $exp_item->expedicion->articulo,
                        'pallet_en_preparacion_id' => $pallet_prep->id,
                        'comprobante' => $comprobante,
                        'nro_pieza' => $exp_item->nro_pieza,
                        'nro_caja' => $exp_item->nro_caja,
                        'nro_pallet' => $exp_item->nro_pallet,
                        'peso' => $exp_item->peso,
                        'peso_real' => $exp_item->peso_real,
                        'lote' => $exp_item->lote,
                        'codigo_barras_articulo' => $exp_item->codigo_barras_articulo,
                        'codigo_barras_caja' => $exp_item->codigo_barras_caja,
                        'codigo_barras_pallet' => $exp_item->codigo_barras_pallet
                    ]);
                    $exp_item->estado = 'PREPARADO';
                    $exp_item->save();
                }
                $texto = 'Palet en expedición agregado al nuevo palet';
            }

            return new JsonResponse([
                'msj'  => $texto,
                'type' => 'success'
            ]);

        }else{
            return new JsonResponse([
                'msj'  => 'El código de barras ingresado no existe en expedición',
                'type' => 'error'
            ]);
        }
    }

    public function paletsPendientes(){
        $palets_pendientes = PalletEnPreparacion::with(['piezas','user'])->get();
        return view('expedicion.palet-pendientes',compact('palets_pendientes'));
    }

    public function deletePalet(Request $request){
        $items = PalletEnPreparacionItem::where('pallet_en_preparacion_id',$request->palet_en_preparacion)->get();
        foreach ($items as $item){
            $exp = ExpedicionItem::where('codigo_barras_articulo',$item->codigo_barras_articulo)
                                   ->where('codigo_barras_caja',$item->codigo_barras_caja)
                                   ->where('codigo_barras_pallet',$item->codigo_barras_pallet)
                                   ->first();
            if($exp){
                $exp->estado = null;
                $exp->save();
            }

            $item->delete();
        }
        PalletEnPreparacion::where('id',$request->palet_en_preparacion)->delete();
        $request->session()->flash('msj', 'Palet eliminado correctamente');
        return redirect()->back()->withInput();
    }

    public function getItemPalet(Request $request){
        $items = [];
        $pallet_en_preparacion = PalletEnPreparacion::where('token',$request->token)->first();
        if($pallet_en_preparacion){
            //$items = PalletEnPreparacionItem::where('pallet_en_preparacion_id',$pallet_en_preparacion->id)->get();

            $items = PalletEnPreparacionItem::select(
                'comprobante',
                'codigo_barras_caja',
                'articulo',
                'lote',
                'codigo_articulo',
                DB::raw('SUM(peso_real) as peso_real'),
                DB::raw('SUM(peso) as peso'),
                DB::raw('COUNT(id) as piezas'),
                DB::raw('COUNT(DISTINCT codigo_barras_caja) as cajas'))
              ->groupBy('codigo_barras_caja','comprobante','articulo' ,'codigo_articulo','lote')
              ->where('pallet_en_preparacion_id',$pallet_en_preparacion->id)
              ->get();
        }

        return new JsonResponse([
            'html' => view('expedicion.items-table', compact('items'))->render(),
        ]);
    }

    public function cerrarPalet(Request $request){
        try {
            DB::beginTransaction();
            Schema::disableForeignKeyConstraints();
                $newCodBar = $this->getCodigoBarras();
                $palet_pendiente = PalletEnPreparacion::with(['piezas','user'])->where('token',$request->token)->first();
                if($palet_pendiente){
                    $piezas = count($palet_pendiente->piezas);
                    $cajas  =  $palet_pendiente->cajas() ;
                    $compr  =  implode(' | ',array_unique($palet_pendiente->piezas->pluck('comprobante')->toArray())) ;
                    $lotes  =  implode(' | ',array_unique($palet_pendiente->piezas->pluck('lote')->toArray())) ;
                    $peso   =  array_sum($palet_pendiente->piezas->pluck('peso')->toArray()) ;
                    $peso_r =  array_sum($palet_pendiente->piezas->pluck('peso_real')->toArray()) ;
                }else{
                    $request->session()->flash('msj', 'Token erroneo');
                    return redirect()->back()->withInput();
                }

                $paletArmado = PaletArmado::create([
                    'fecha_preparacion' => $palet_pendiente->fecha,
                    'fecha_cierre' => Carbon::parse(now())->format('Y-m-d'),
                    'piezas' => $piezas,
                    'cajas' => $cajas,
                    'comprobantes' => $compr,
                    'lotes' => $lotes,
                    'peso_total' => $peso,
                    'peso_real_total' => $peso_r,
                    'user_id' => Auth::user()->id
                ]);

                Historial::create([
                    'CodBarraPallet_Int'=> $newCodBar,
                    'user_id' => Auth::user()->id,
                    'fecha' => Carbon::parse(now())->format('Y-m-d'),
                    'hora' => Carbon::parse(now())->format('H:i'),
                    'descripcion'=> 'Se cerro palet. <br> Comprobantes '. $compr . '<br> Cajas '. $cajas. '<br> Cantidad '. $piezas . '<br> Lotes '. $lotes
                ]);

                $expedicion_ids = [];
                foreach ($palet_pendiente->piezas as $pp) {
                    $item = PaletArmadoItem::create([
                        'pallet_armado_id' => $paletArmado->id,
                        'comprobante' => $pp->comprobante,
                        'codigo' => $pp->codigo_articulo ,
                        'nombre' => $pp->articulo ,
                        'ID_Articulo' => $pp->id_articulo ,
                        'FechaElaboracion' => Carbon::parse(now())->format('Y-m-d') ,
                        'FechaVencimiento' => null,
                        'Lote' => $pp->lote,
                        'Peso_Real' => $pp->peso_real,
                        'Peso' => $pp->peso,
                        'CodBarraPallet_Int' => $newCodBar,
                        'CodBarraCaja_Int' => $pp->codigo_barras_caja ,
                        'CodBarraArt_Int' => $pp->codigo_barras_articulo,
                        'fecha_cierre_comprobante' => null
                    ]);

                    $expedicion_item = ExpedicionItem::where('codigo_barras_articulo',$pp->codigo_barras_articulo)->first();
                    if($expedicion_item){
                        array_push($expedicion_ids,$expedicion_item->expedicion_id);
                        $expedicion_item->delete();
                    }
                }

                $pallet = PalletEnPreparacionItem::select(
                                'comprobante',
                                'codigo_barras_pallet',
                                DB::raw('COUNT(comprobante) as piezas'))
                            ->groupBy('codigo_barras_pallet' ,'comprobante')
                            ->get();

                if($pallet && count($pallet)){
                    foreach ($pallet as $p) {
                        $cajas = PalletEnPreparacionItem::select(DB::raw('COUNT(codigo_barras_caja) as cant'))
                        ->where('comprobante',$p->comprobante)->where('codigo_barras_pallet',$p->codigo_barras_pallet)
                        ->groupBy('codigo_barras_caja')
                        ->first();
                        Historial::create([
                            'CodBarraPallet_Int'=> $p->codigo_barras_pallet,
                            'user_id' => Auth::user()->id,
                            'fecha' => Carbon::parse(now())->format('Y-m-d'),
                            'hora' => Carbon::parse(now())->format('H:i'),
                            'descripcion'=> 'Se agregaron a nuevo palet para cumplimentar NP: <br> Comprobante '. $p->comprobante . '<br> Cajas '. $cajas->cant. '<br> Cantidad '. $p->piezas . '<br> Palet armado '. $newCodBar
                        ]);
                    }
                }

                $ids = array_values(array_unique($expedicion_ids));
                foreach ($ids as $id) {
                    $piezas = ExpedicionItem::where('expedicion_id',$id)->get();
                    $cant_de_cajas = ExpedicionItem::select('codigo_barras_caja')->where('expedicion_id',$id)->groupBy('codigo_barras_caja')->get();
                    if(count($cant_de_cajas)){
                        $exp = Expedicion::where('id',$id)->first();
                        $exp->piezas_cargada = count($piezas);
                        $exp->cajas_cargada  = count($cant_de_cajas);
                        $exp->save();
                    }else{
                        Expedicion::where('id',$id)->delete();
                    }
                }
                $palet_pendiente->estado = 'CERRADO EDITABLE';
                $palet_pendiente->save();
            DB::commit();
            Schema::enableForeignKeyConstraints();
            return redirect()->route('palet.armados');
        } catch (\Exception $e) {
            DB::rollback();
            Schema::enableForeignKeyConstraints();
            Session::flash('msj',$e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function deleteCaja(Request $request){
        $items = PalletEnPreparacionItem::where('codigo_barras_caja',$request->caja_en_preparacion)->get();
        foreach ($items as $item){
            $exp = ExpedicionItem::where('codigo_barras_articulo',$item->codigo_barras_articulo)
                                   ->where('codigo_barras_caja',$item->codigo_barras_caja)
                                   ->where('codigo_barras_pallet',$item->codigo_barras_pallet)
                                   ->first();
            if($exp){
                $exp->estado = null;
                $exp->save();
            }

            $item->delete();
        }
        PalletEnPreparacionItem::where('codigo_barras_caja',$request->caja_en_preparacion)->delete();
        $request->session()->flash('msj', 'Caja eliminada correctamente');
        return redirect()->back()->withInput();
    }

    private function getCodigoBarras(){
        $numero = date('Y').date('m').date('d').'4';
        try {
            $cod    = CodigoBarraPaletArmado::where('numero',$numero)->first();

            if($cod){
                $newCantidad = $cod->cantidad + 1;
                $cod->cantidad = $newCantidad;
                $cod->save();
            }else{
                $newCantidad = 1;
                CodigoBarraPaletArmado::create(['numero'=>$numero,'cantidad'=> $newCantidad]);
            }


            $nropallet = str_pad($newCantidad, 3, "0", STR_PAD_LEFT);
            $hm = date('Hi');
            return $numero.$hm.$nropallet;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function paletArmados(){
        $palet_armados = PaletArmado::with(['user','items'])->get();
        return view('expedicion.palet-armados',compact('palet_armados'));
    }

    public function getPaletArmadoDetalles($palet_armado_id){
        $palet_armado = PaletArmado::where('id',$palet_armado_id)->with(['user','items'])->first();
        return view('expedicion.palet-armado-items',compact('palet_armado'));
    }
}
