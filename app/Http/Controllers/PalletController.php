<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddPaletManualRequest;
use App\Models\CajasDelPallet;
use App\Models\CodigoBarraPaletArmado;
use App\Models\Estado;
use App\Models\ExpedicionItem;
use App\Models\Historial;
use App\Models\PaletArmado;
use App\Models\PaletArmadoItem;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;

use App\Models\Pallets;
use App\Models\Producto;
use App\Models\SPallet;
use App\Models\Ubicacion;
use Illuminate\Support\Facades\Auth;
use stdClass;

class PalletController extends Controller
{
    public function getPallet(Request $request){
        $validaposicion = ($request->validaposicion == 'true')?1:0;
        // DE LA TABLA ENVASADO OBTENGO TODOS LOS ENVASADOS POR CODIGO DE BARRAS DEL PALLET
        $pallet = Pallets::select(
                            'ID_Articulo',
                            'CodBarraPallet_Int',
                            'Lote',
                            'FechaElaboracion',
                            'FechaVencimiento',
                            DB::raw('COUNT(Lote) as piezas'), // piezas por lote
                            DB::raw('SUM(Peso_Real) as PesoReal'),
                            DB::raw('SUM(Peso) as Peso'))
                          ->groupBy('CodBarraPallet_Int' ,'Lote', 'ID_Articulo', 'FechaElaboracion','FechaVencimiento')
                          ->with('producto')
                          ->where('CodBarraPallet_Int',$request->codigo)
                          ->get();
        $cajas = Pallets::select(DB::raw('COUNT(CodBarraCaja_Int) as cantidad'))->groupBy('CodBarraCaja_Int')->where('CodBarraPallet_Int',$request->codigo)->get();
        // Palet armados manualmente
        if(!count($pallet)){
            $pallet = CajasDelPallet::select(
                            'id_articulo as ID_Articulo',
                            'codigo_barras_pallet as CodBarraPallet_Int',
                            'lote as Lote',
                            DB::raw('COUNT(Lote) as piezas'), // piezas por lote
                            DB::raw('SUM(Peso_Real) as PesoReal'),
                            DB::raw('SUM(Peso) as Peso'))
                    ->groupBy('codigo_barras_pallet' ,'lote', 'id_articulo')
                    ->with('producto')
                    ->where('codigo_barras_pallet',$request->codigo)
                    ->get();
            $cajas = CajasDelPallet::select(DB::raw('COUNT(codigo_barras_caja) as cantidad'))->groupBy('codigo_barras_caja')->where('codigo_barras_pallet',$request->codigo)->get();

        }
        //Palet armados con productos de expedicion
        if(!count($pallet)){
            $pallet = PaletArmadoItem::select(
                            'ID_Articulo',
                            'CodBarraPallet_Int',
                            'Lote',
                            'FechaElaboracion',
                            'FechaVencimiento',
                            DB::raw('COUNT(Lote) as piezas'), // piezas por lote
                            DB::raw('SUM(Peso_Real) as PesoReal'),
                            DB::raw('SUM(Peso) as Peso'))
                        ->groupBy('CodBarraPallet_Int' ,'Lote', 'ID_Articulo', 'FechaElaboracion','FechaVencimiento')
                        ->with('producto')
                        ->where('CodBarraPallet_Int',$request->codigo)
                        ->get();
            $cajas = PaletArmadoItem::select(DB::raw('COUNT(CodBarraCaja_Int) as cantidad'))->groupBy('CodBarraCaja_Int')->where('CodBarraPallet_Int',$request->codigo)->get();
        }

        if($pallet && count($pallet)){
            if($validaposicion){
                $ubicado = Ubicacion::where('pallet',$request->codigo)->first();
                if($ubicado) return new JsonResponse(['msj'  => 'Pallet ya está ubicado!','type' => 'error',]);
            }
            $fecha_elab = (isset($pallet[0]->FechaElaboracion))?\Carbon\Carbon::parse($pallet[0]->FechaElaboracion)->format('d/m/Y'):'--';
            $fecha_venc = (isset($pallet[0]->FechaVencimiento))?\Carbon\Carbon::parse($pallet[0]->FechaVencimiento)->format('d/m/Y'):'--';

            $totalPeso = $totalPesoReal = $piezas = 0;
            $texto  = '<span style="width:30%; float:left; padding: 5px 15px; border:1px solid; border-radius: 10px; margin-right: 15px">';
            $texto .= '<strong>Código Barras</strong>: <span style=" float:right">'.$pallet[0]->CodBarraPallet_Int .'</span><br>';

            foreach ($pallet as $p) {
                $texto .= '<strong>Producto</strong>: <span style=" float:right;color:green">'.$p->producto->Descripcion .'</span><br>';
                $texto .= '<strong>Piezas por caja</strong>: <span style=" float:right">'.$p->producto->PiezasPorCaja .'</span><br>';
                $texto .= '<strong>Cajas por pallet</strong>: <span style=" float:right">'.$p->producto->CajasPorPallet .'</span><br>';
            }

            $texto .= '</span>';

            $texto .= '<span style="width:30%; float:left; padding: 5px 15px; border:1px solid; border-radius: 10px; margin-right: 15px">';
            $texto .= '<strong>F.Elab.</strong>: <span style=" float:right">'.$fecha_elab .'</span><br>';
            $texto .= '<strong>F.Disp.</strong>: <span style=" float:right">'.$fecha_venc .'</span><br>';

            foreach ($pallet as $p) {
                $totalPeso     += $p->Peso;
                $totalPesoReal += $p->PesoReal;
                $piezas += $p->piezas;
                $texto .= '<strong>Lote:</strong> '.$p->Lote .'<span style=" float:right">'.$p->piezas .' piezas</span><br>';
               /*  $texto .= '<strong>Lote:</strong> '.$p->Lote .'<strong> Cant. piezas</strong>: '.$p->piezas .'<strong> Peso</strong>: '.number_format($p->Peso,2,'.',',');
                $texto .= '<strong> Peso Real:</strong> '.number_format($p->PesoReal,2,'.',',').'<br>'; */
            }
            $texto .= '</span>';

            $texto .= '<span style="width:30%; float:left; padding: 5px 15px; border:1px solid; border-radius: 10px">';
            $texto .= '<strong>Total de piezas:</strong> <span style=" float:right;color:green">'.$piezas .'</span><br>';
            $texto .= '<strong>Total de cajas:</strong> <span style=" float:right">'.$cajas->count().'</span><br>';
            $texto .= '<strong>Peso Total: </strong> <span style=" float:right">'.$totalPeso .'Kg.</span><br>';
            $texto .= '<strong>Peso Real Total:</strong> <span style=" float:right">'.$totalPesoReal .'Kg.</span><br>';
            $texto .= '</span>';


            return new JsonResponse([
                'msj'  => $texto,
                'pallet'  => $pallet,
                'type' => 'success',
            ]);

        }else{
            return new JsonResponse([
                'msj'  => 'Pallet no encontrado!',
                'type' => 'error',
            ]);
        }
    }

    public function getPalletHistorial(Request $request){
        if($request->codigo == 0){
            $historial = Historial::with('user')->orderBy('id','DESC')->take(500)->get();
            $html_historila = view('pallets.historial', compact('historial'))->render();
            return new JsonResponse([
                'msj'  => '',
                'type' => 'success',
                'html' => $html_historila
            ]);
        }
        $historial = Historial::where('CodBarraPallet_Int',$request->codigo)->with('user')->orderBy('id','DESC')->get();
        $html_historila = view('pallets.historial', compact('historial'))->render();
        $ubicacion = Ubicacion::where('pallet',$request->codigo)->with('s_pallet')->first();
        $texto = 'No tiene ubicación';

        if($ubicacion){
            $route = route('ver.ubicacion',['idUbicacion' => $ubicacion->id, 'idSPallet' => $ubicacion->s_pallet[0]->id]);
            $_link =' <a href="'.$route.'" target="_blank" rel="noopener noreferrer" style="color:green;cursor:pointer" title="Ver Ubicación">'.$ubicacion->u_nombre() .'</a>';

            $texto  = '<span style="width:30%; float:left; padding: 5px 15px; border:1px solid; border-radius: 10px; margin-right: 15px">';
            $texto .= '<strong>Código Barras</strong>: <span style=" float:right">'.$ubicacion->pallet .'</span><hr style="margin: 5px 0px;">';

            foreach ($ubicacion->s_pallet as $p){
                $texto .= '<strong>Producto</strong>: <span style=" float:right;color:green">'.$p->nombre .'</span><br>';
                $texto .= '<strong>Lote:</strong> '.$p->lote .'<span style=" float:right">'.$p->piezas .' Uni.</span><hr style="margin: 5px 0px;">';
            }

            $texto .= '<strong>F.Elab.</strong>: <span style=" float:right">'.\Carbon\Carbon::parse($ubicacion->s_pallet[0]->fecha_elaboracion)->format('d/m/Y') .'</span><br>';
            $texto .= '<strong>F.Disp.</strong>: <span style=" float:right">'.\Carbon\Carbon::parse($ubicacion->s_pallet[0]->fecha_vencimiento)->format('d/m/Y') .'</span><br>';

            $texto .= '</span>';

            $texto .= '<span style="width:30%; float:left; padding: 5px 15px; border:1px solid; border-radius: 10px; margin-right: 15px">';
            $texto .= '<strong>Total de piezas:</strong> <span style=" float:right;color:green">'.$ubicacion->piezas_total .'</span><br>';
            $texto .= '<strong>Total de cajas:</strong> <span style=" float:right">'.$ubicacion->cajas.'</span><br>';
            $texto .= '<strong>Peso Total: </strong> <span style=" float:right">'.$ubicacion->peso_total .'Kg.</span><br>';
            $texto .= '<strong>Peso Real Total:</strong> <span style=" float:right">'.$ubicacion->peso_real_total .'Kg.</span><br>';
            $texto .= '<strong>Ubicación:</strong> <span style=" float:right">'.$_link .'.</span><br>';
            $texto .= '</span>';
        }

        $pallet = ExpedicionItem::select(
                'lote',
                'codigo_barras_pallet',
                DB::raw('COUNT(lote) as piezas'), // piezas por lote
                DB::raw('SUM(peso_real) as PesoReal'),
                DB::raw('SUM(peso) as Peso'))
              ->groupBy('codigo_barras_pallet' ,'lote')
              ->where('codigo_barras_pallet',$request->codigo)
              ->get();
        $cajas = ExpedicionItem::select(DB::raw('COUNT(codigo_barras_caja) as cantidad'))->groupBy('codigo_barras_caja')->where('codigo_barras_pallet',$request->codigo)->get();

        if($pallet && count($pallet)){
            $totalPeso = $totalPesoReal = $piezas = 0;
            $texto  .= '<span style="width:30%; float:left; padding: 5px 15px; border:1px solid; border-radius: 10px; margin-right: 15px">';
            $texto .= '<strong>EN EXPEDICION</strong>: <span style=" float:right"></span><br>';

            foreach ($pallet as $p) {
                $totalPeso     += $p->Peso;
                $totalPesoReal += $p->PesoReal;
                $piezas += $p->piezas;
                $texto .= '<strong>Lote:</strong> '.$p->lote .'<span style=" float:right">'.$p->piezas .' piezas</span><br>';
            }
            $texto .= '<strong>Total de piezas:</strong> <span style=" float:right;color:green">'.$piezas .'</span><br>';
            $texto .= '<strong>Total de cajas:</strong> <span style=" float:right">'.$cajas->count().'</span><br>';
            $texto .= '<strong>Peso Total: </strong> <span style=" float:right">'.$totalPeso .'Kg.</span><br>';
            $texto .= '<strong>Peso Real Total:</strong> <span style=" float:right">'.$totalPesoReal .'Kg.</span><br>';
            $texto .= '</span>';
        }

        return new JsonResponse([
            'msj'  => $texto,
            'type' => 'success',
            'html' => $html_historila
        ]);
    }

    public function ubicarPallet(Request $request){
        try {
            DB::beginTransaction();
            Schema::disableForeignKeyConstraints();
            $estado = Estado::where('default',true)->first();
            $ubicacion_ocupada = null;
            $html_disponible = '';
            $id_ubicacion_pallet_a_mover = $request->input('ubicacion_id_a_mover');
            $totalPeso = $totalPesoReal = $piezas = 0;

            if($id_ubicacion_pallet_a_mover && $id_ubicacion_pallet_a_mover != '' && !is_null($id_ubicacion_pallet_a_mover) ){
                $ubicacion_nueva = $ubicacion = Ubicacion::where('id',$request->input('ubicacion-id-seleccionada'))->first();
                $ubicacion_ocupada =  Ubicacion::where('id',$id_ubicacion_pallet_a_mover)->first();

                $ubicacion_nueva->peso_total      = $ubicacion_ocupada->peso_total;
                $ubicacion_nueva->peso_real_total = $ubicacion_ocupada->peso_real_total;
                $ubicacion_nueva->piezas_total    = $ubicacion_ocupada->piezas_total;
                $ubicacion_nueva->cajas           = $ubicacion_ocupada->cajas;
                $ubicacion_nueva->pallet          = $ubicacion_ocupada->pallet;
                $ubicacion_nueva->fecha           = Carbon::parse(now())->format('d/m/Y H:i');
                $ubicacion_nueva->save();

                $ubicacion_ocupada->peso_total      = 0;
                $ubicacion_ocupada->peso_real_total = 0;
                $ubicacion_ocupada->piezas_total    = 0;
                $ubicacion_ocupada->cajas           = 0;
                $ubicacion_ocupada->pallet          = null;
                $ubicacion_ocupada->fecha           = null;
                $ubicacion_ocupada->save();

                $_ubicacion = $ubicacion_nueva->camara->nombre .' '.$ubicacion_nueva->calle->nombre . ' '.$ubicacion_nueva->altura->nombre .' '.$ubicacion_nueva->profundidad->nombre;
                Historial::create([
                    'CodBarraPallet_Int'=> $ubicacion->pallet,
                    'user_id' => Auth::user()->id,
                    'fecha' => Carbon::parse(now())->format('Y-m-d'),
                    'hora' => Carbon::parse(now())->format('H:i'),
                    'descripcion'=> 'Se movió a la posición '.$_ubicacion . '<br><strong>P: </strong>'. $ubicacion_nueva->peso_total. ' <strong>PR: </strong>'. $ubicacion_nueva->peso_real_total. ' <strong>C:</strong> '. $ubicacion_nueva->cajas. '<strong> U: </strong>'. $ubicacion_nueva->piezas_total
                ]);

            }else{
                $ubicado = Ubicacion::where('pallet',$request->input('pallet-seleccionado'))->first();
                if($ubicado) return new JsonResponse(['msj'  => 'El pallet ya se encuentra ubicado','type' => 'error',]);

                $ubicacion = Ubicacion::where('id',$request->input('ubicacion-id-seleccionada'))->first();
                $ubicacion->pallet = $request->input('pallet-seleccionado');
                $ubicacion->fecha = Carbon::parse(now())->format('d/m/Y H:i');

                $pallet = Pallets::select(
                                'ID_Articulo',
                                'CodBarraPallet_Int',
                                'Lote',
                                'FechaElaboracion',
                                'FechaVencimiento',
                                DB::raw('COUNT(Lote) as piezas'), // piezas por lote
                                DB::raw('SUM(Peso_Real) as PesoReal'),
                                DB::raw('SUM(Peso) as Peso'))
                            ->groupBy('CodBarraPallet_Int' ,'Lote', 'ID_Articulo', 'FechaElaboracion','FechaVencimiento')
                            ->with('producto')
                            ->where('CodBarraPallet_Int',$request->input('pallet-seleccionado'))
                            ->get();
                $cajas = Pallets::select(DB::raw('COUNT(CodBarraCaja_Int) as cantidad'))->groupBy('CodBarraCaja_Int')->where('CodBarraPallet_Int',$request->input('pallet-seleccionado'))->get();

                if(!count($pallet)){
                    $pallet = CajasDelPallet::select(
                                    'id_articulo as ID_Articulo',
                                    'codigo_barras_pallet as CodBarraPallet_Int',
                                    'lote as Lote',
                                    DB::raw('COUNT(Lote) as piezas'), // piezas por lote
                                    DB::raw('SUM(Peso_Real) as PesoReal'),
                                    DB::raw('SUM(Peso) as Peso'))
                            ->groupBy('codigo_barras_pallet' ,'lote', 'id_articulo')
                            ->with('producto')
                            ->where('codigo_barras_pallet',$request->input('pallet-seleccionado'))
                            ->get();
                    $cajas = CajasDelPallet::select(DB::raw('COUNT(codigo_barras_caja) as cantidad'))->groupBy('codigo_barras_caja')->where('codigo_barras_pallet',$request->input('pallet-seleccionado'))->get();
                }
                if(!count($pallet)){
                    $pallet = PaletArmadoItem::select(
                                    'ID_Articulo',
                                    'CodBarraPallet_Int',
                                    'Lote',
                                    'FechaElaboracion',
                                    'FechaVencimiento',
                                    DB::raw('COUNT(Lote) as piezas'), // piezas por lote
                                    DB::raw('SUM(Peso_Real) as PesoReal'),
                                    DB::raw('SUM(Peso) as Peso'))
                                ->groupBy('CodBarraPallet_Int' ,'Lote', 'ID_Articulo', 'FechaElaboracion','FechaVencimiento')
                                ->with('producto')
                                ->where('CodBarraPallet_Int',$request->input('pallet-seleccionado'))
                                ->get();
                    $cajas = PaletArmadoItem::select(DB::raw('COUNT(CodBarraCaja_Int) as cantidad'))->groupBy('CodBarraCaja_Int')->where('CodBarraPallet_Int',$request->codigo)->get();
                }

                $articulo = Producto::where('Id',$pallet[0]->ID_Articulo)->first();

                foreach ($pallet as $p) {
                    $totalPeso     += $p->Peso;
                    $totalPesoReal += $p->PesoReal;
                    $piezas        += $p->piezas;
                    SPallet::updateOrCreate([
                        'codigo_barras' => $request->input('pallet-seleccionado'),
                        'lote'          => $p->Lote,
                    ],[
                        'id_articulo'         => $articulo->Id,
                        'codigo'              => $articulo->Codigo,
                        'estado_id'           => $estado->id,
                        'nombre'              => $articulo->Descripcion,
                        'piezas'              => round($p->piezas,2),
                        'peso'                => round($p->Peso,2),
                        'peso_real'           => round($p->PesoReal,2),
                        'dias_almacenamiento' => $articulo->DiasAlmacenamiento,
                        'fecha_elaboracion'   =>\Carbon\Carbon::parse($pallet[0]->FechaElaboracion)->format('Y-m-d'),
                        'fecha_vencimiento'   =>\Carbon\Carbon::parse($pallet[0]->FechaElaboracion)->addDays($articulo->DiasAlmacenamiento)->format('Y-m-d'),
                    ]);
                }
                $ubicacion->estado_id       = $estado->id;
                $ubicacion->peso_total      = $totalPeso;
                $ubicacion->peso_real_total = $totalPesoReal;
                $ubicacion->piezas_total    = $piezas;
                $ubicacion->cajas           = $cajas->count();
                $ubicacion->save();

                $cajas = Pallets::where('CodBarraPallet_Int',$request->input('pallet-seleccionado'))->get();

                $_ubicacion = $ubicacion->camara->nombre .' '.$ubicacion->calle->nombre . ' '.$ubicacion->altura->nombre .' '.$ubicacion->profundidad->nombre;
                Historial::create([
                    'CodBarraPallet_Int'=> $ubicacion->pallet,
                    'user_id' => Auth::user()->id,
                    'fecha' => Carbon::parse(now())->format('Y-m-d'),
                    'hora' => Carbon::parse(now())->format('H:i'),
                    'descripcion'=> 'Se ubicó en la posición '.$_ubicacion . '<br><strong>P: </strong>'. $ubicacion->peso_total. ' <strong>PR: </strong>'. $ubicacion->peso_real_total. ' <strong>C:</strong> '. $ubicacion->cajas. '<strong> U: </strong>'. $ubicacion->piezas_total
                ]);
                $array_cajas = [];
                $i = 0;
                foreach ($cajas as $caja) {
                    $new_caja = new stdClass;
                    $new_caja = [
                        'nro_pieza' => $caja->NrodePieza,
                        'nro_caja'  => $caja->NrodeCaja ,
                        'nro_pallet'=> $caja->NrodePallet ,
                        'lote'      => $caja->Lote ,
                        'peso'      => $caja->Peso ,
                        'peso_real' => $caja->Peso_Real ,
                        'codigo_barras_articulo' => $caja->CodBarraArt_Int ,
                        'codigo_barras_caja'     => $caja->CodBarraCaja_Int ,
                        'codigo_barras_pallet'   => $caja->CodBarraPallet_Int
                    ];
                    array_push($array_cajas,$new_caja);
                    $i++;
                    if($i == 200){
                        CajasDelPallet::insert($array_cajas);
                        $i = 0;
                        $array_cajas = [];
                    }
                }
                CajasDelPallet::insert($array_cajas);
            }
            DB::commit();
            Schema::enableForeignKeyConstraints();
            $profundidades = $request->input('pallet-profundidades');
            $html = view('ubicaciones.pallet-ubicado', compact('ubicacion','profundidades'))->render();
            if(!is_null($ubicacion_ocupada)){
                $ubicacion = $ubicacion_ocupada;
                $html_disponible = view('ubicaciones.pallet-disponible', compact('ubicacion','profundidades'))->render();
            }
            return new JsonResponse([
                'msj'  => 'Pallet ubicado correctamente',
                'type' => 'success',
                'html' => $html,
                'html_disponible'=> $html_disponible
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            Schema::enableForeignKeyConstraints();
            return new JsonResponse([
                'msj'  => $e->getMessage(),
                'type' => 'error',
            ]);
        }
    }

    public function getPalletPendientes(Request $request){
        $pallets_ubicados = Ubicacion::select('pallet')->whereNotNull('pallet')->pluck('pallet')->toArray();
        $articulo_id = $request->articulo_id;

        switch ($articulo_id) {
            case 'ENV':
                $pallets = Pallets::select(
                    'ID_Articulo',
                    'CodBarraPallet_Int')
                ->groupBy('ID_Articulo','CodBarraPallet_Int')
                ->with('producto')
                ->whereNotIn('CodBarraPallet_Int',$pallets_ubicados)
                ->paginate(50);
                $html = view('ubicaciones.pallets-disponibles', compact('pallets','articulo_id'))->render();
                break;
            case 'PEM':
                $pallets = SPallet::select(
                    'codigo',
                    'codigo_barras',
                    'nombre'
                )
                ->whereNotIn('codigo_barras',$pallets_ubicados)
                ->where('manual',1)
                ->paginate(50);
                $html = view('ubicaciones.pallets-disponibles-pem', compact('pallets','articulo_id'))->render();
                break;
            case 'PA':
                $pallets = PaletArmadoItem::select(
                    'ID_Articulo',
                    'CodBarraPallet_Int')
                ->groupBy('ID_Articulo','CodBarraPallet_Int')
                ->with('producto')
                ->whereNotIn('CodBarraPallet_Int',$pallets_ubicados)
                ->paginate(50);
                $html = view('ubicaciones.pallets-disponibles', compact('pallets','articulo_id'))->render();
                break;
            default:
                $pallets = Pallets::select(
                    'ID_Articulo',
                    'CodBarraPallet_Int')
                ->groupBy('ID_Articulo','CodBarraPallet_Int')
                ->with('producto')
                ->whereNotIn('CodBarraPallet_Int',$pallets_ubicados)
                ->where('ID_Articulo',$articulo_id)
                ->paginate(50);
                $html = view('ubicaciones.pallets-disponibles', compact('pallets','articulo_id'))->render();
                break;
        }


        return new JsonResponse(['html' => $html]);
    }

    public function getPalletDetalles(Request $request){
        $id_ubicacion = $request->u_id;
        $no_mostrar_estado = ($request->has('no_mostrar_estado') && $request->input('no_mostrar_estado'))?true:false;
        $ubicacion = Ubicacion::where('id',$id_ubicacion)->with('s_pallet')->first();
        $estados = Estado::all();
        $html = view('ubicaciones.detalles-pallet', compact('ubicacion','estados','no_mostrar_estado'))->render();
        return new JsonResponse(['html' => $html]);
    }

    public function cambiarEstadoPallet(Request $request){
        try {
            DB::beginTransaction();
            Schema::disableForeignKeyConstraints();
            $data = $request->all();
            Ubicacion::where('pallet',$data['codigo_barra_palet'])->update(['estado_id' => $data['estado']]);
            SPallet::where('codigo_barras',$data['codigo_barra_palet'])->update(['estado_id' => $data['estado']]);
            Historial::create([
                'CodBarraPallet_Int'=> $data['codigo_barra_palet'],
                'user_id' => Auth::user()->id,
                'fecha' => Carbon::parse(now())->format('Y-m-d'),
                'hora' => Carbon::parse(now())->format('H:i'),
                'descripcion'=> 'Se cambió de estado a '.$data['estado']
            ]);
            DB::commit();
            Schema::enableForeignKeyConstraints();
            return new JsonResponse(['type' => 'success']);
        } catch (\Exception $e) {
            DB::rollback();
            Schema::enableForeignKeyConstraints();
            return new JsonResponse(['type' => 'error','msj' =>$e->getMessage()]);
        }

    }

    public function add(Request $request){
        $codigo_barras_palet = ($request->has('codigo_barras_palet'))?$request->codigo_barras_palet:null;
        $articulos_envasados = Producto::select('Id','Codigo','Descripcion')->orderBy('Codigo','ASC')->get();
        return view('envasados.add',compact('articulos_envasados','codigo_barras_palet'));
    }

    public function store(AddPaletManualRequest $request){
       try {
            DB::beginTransaction();
            Schema::disableForeignKeyConstraints();
            $new = false;
            $codigo_barras_palet = $request->codigo_barras_palet;
            if(!$codigo_barras_palet){
                $codigo_barras_palet = $this->getCodigoBarras(5);
                $new = true;
            }
            if(SPallet::where('codigo_barras',$codigo_barras_palet)->where('lote',$request->lote)->where('id_articulo',$request->articulo_id)->exists()){
                return new JsonResponse(['msj'  => 'Ya fue ingresado un articulo con el mismo N° de lote en éste palet','type' => 'error' ]);
            }
            $estado   = Estado::where('default',true)->first();
            $producto = Producto::where('Id',$request->articulo_id)->first();
            $piezas   = $request->cantidad;

            $spalet = SPallet::create([
                'id_articulo' => $producto->Id,
                'codigo' => $producto->Codigo,
                'nombre' => $producto->Descripcion,
                'estado_id' => $estado->id,
                'codigo_barras' => $codigo_barras_palet,
                'lote' => $request->lote,
                'piezas' => $piezas,
                'peso' => $request->kilos,
                'peso_real' => $request->kilos,
                'fecha_elaboracion'   =>\Carbon\Carbon::parse($request->fecha_elaboracion)->format('Y-m-d'),
                'fecha_vencimiento'   =>\Carbon\Carbon::parse($request->fecha_elaboracion)->addDays($producto->DiasAlmacenamiento)->format('Y-m-d'),
                'manual' => true
            ]);

            if($new){
                Historial::create([
                    'CodBarraPallet_Int'=> $codigo_barras_palet,
                    'user_id' => Auth::user()->id,
                    'fecha' => Carbon::parse(now())->format('Y-m-d'),
                    'hora' => Carbon::parse(now())->format('H:i'),
                    'descripcion'=> 'Se creó palet. <br> Artículo: '. $producto->Codigo .' - '.$producto->Descripcion . '<br> Unidades: '. $piezas. '<br> Kilos: '. $request->kilos . '<br> Lote: '. $request->lote
                ]);
            }else{
                Historial::create([
                    'CodBarraPallet_Int'=> $codigo_barras_palet,
                    'user_id' => Auth::user()->id,
                    'fecha' => Carbon::parse(now())->format('Y-m-d'),
                    'hora' => Carbon::parse(now())->format('H:i'),
                    'descripcion'=> 'Se agregó al palet. <br> Artículo: '. $producto->Codigo .' - '.$producto->Descripcion . '<br> Unidades: '. $piezas. '<br> Kilos: '. $request->kilos . '<br> Lote: '. $request->lote
                ]);
            }

            $cant_cajas  =  $total_permitido = $count_cajas = 0;
            $array_cajas = [];

            if($producto->PiezasPorCaja){
                $cant_cajas = (int) $producto->PiezasPorCaja;
            }

            for ($i=0; $i < $piezas ; $i++) {
                $nro_pieza = $i +1;
                if($cant_cajas){
                    if($count_cajas == $cant_cajas) $count_cajas = 0;
                    if($count_cajas == 0) $CodBarraCaja_Int = $this->getCodigoBarras(6);
                    $count_cajas++;
                }else{
                    $CodBarraCaja_Int = $this->getCodigoBarras(6);
                    $count_cajas += 1;
                }

                $new_caja = new stdClass;
                $new_caja = [
                    'id_articulo' => $producto->Id,
                    'codigo' => $producto->Codigo,
                    'nombre' => $producto->Descripcion,
                    'nro_pieza' => $nro_pieza,
                    'nro_caja'  => $count_cajas ,
                    'nro_pallet'=> $this->getNumeroPalet(5) ,
                    'lote'      => $request->lote ,
                    'peso'      => 0 ,
                    'peso_real' => 0 ,
                    'codigo_barras_articulo' => $this->getCodigoBarras(7) ,
                    'codigo_barras_caja'     => $CodBarraCaja_Int,
                    'codigo_barras_pallet'   => $codigo_barras_palet,
                    'manual' => true
                ];
                array_push($array_cajas,$new_caja);
                $total_permitido++;
                if($total_permitido == 200){
                    CajasDelPallet::insert($array_cajas);
                    $total_permitido = 0;
                    $array_cajas = [];
                }
            }

            CajasDelPallet::insert($array_cajas);
            $ubicacion = Ubicacion::where('pallet',$codigo_barras_palet)->first();
            if($ubicacion){
                if($cant_cajas){
                    $total_cajas = round($piezas,0,PHP_ROUND_HALF_UP);
                }else{
                    $total_cajas = $piezas;
                }
                $ubicacion->piezas_total += $piezas;
                $ubicacion->cajas +=$total_cajas;
                $ubicacion->save();
            }

            DB::commit();
            Schema::enableForeignKeyConstraints();

            return new JsonResponse([
                'codigo_barra_palet' => $codigo_barras_palet,
                'msj'  => 'Artículo agregado',
                'type' => 'success',
            ]);
       } catch (\Exception $e) {
            DB::rollback();
            Schema::enableForeignKeyConstraints();
            return new JsonResponse([
                'msj'  => $e->getMessage(),
                'type' => 'error',
            ]);
       }
    }

    public function getItemsPaletEnvasado(Request $request){
        $items = [];
        $items = CajasDelPallet::where('codigo_barras_pallet',$request->codigo_barras_palet)->get();

        return new JsonResponse([
            'html' => view('envasados.items-table', compact('items'))->render(),
        ]);
    }

    private function getCodigoBarras($num){
        // num = 5 palet  6 cajas  7 art
        $numero = date('Y').date('m').date('d').$num;
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
            if($num == 7){
                $nropallet = str_pad($newCantidad, 5, "0", STR_PAD_LEFT);
                $hm = date('H');
            }else{
                $nropallet = str_pad($newCantidad, 3, "0", STR_PAD_LEFT);
                $hm = date('Hi');
            }

            return $numero.$hm.$nropallet;

        } catch (\Throwable $th) {
            throw $th;
        }
    }

    private function getNumeroPalet($num){
        // num = 5 palet  6 cajas  7 art
        $numero = date('Y').date('m').date('d').$num;
        try {
            $cod    = CodigoBarraPaletArmado::where('numero',$numero)->first();
            return $cod->cantidad + 1;

        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function index(){
        return view('envasados.index');
    }

    public function getEnvasadosIndex(Request $request){
        if ($request->ajax()) {
            $palets = SPallet::where('manual',true)->orderBy('id','DESC')->get();
            return Datatables::of($palets)
                ->addColumn('fecha_elaboracion', function ($palet) {
                    return Carbon::parse($palet->fecha_elaboracion)->format('d/m/Y');
                })
                ->addColumn('fecha_vencimiento', function ($palet) {
                    return Carbon::parse($palet->fecha_vencimiento)->format('d/m/Y');
                })
                ->addColumn('editar', function ($palet) {
                        $route = route('add.palet.envasado',['codigo_barras_palet' => $palet->codigo_barras]);
                        $actionBtn = "<a style='cursor:pointer' ";
                        $actionBtn .= 'href="'.$route;
                        $actionBtn .= '"  class="flex items-center text-danger" href="javascript:;"> <i class="fa fa-edit"></i> Editar </a>';
                    return $actionBtn;
                })
                ->rawColumns(['fecha_elaboracion','fecha_vencimiento','editar'])
                ->addIndexColumn()
                ->make(true);
        }
    }
}
