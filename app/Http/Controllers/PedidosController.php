<?php

namespace App\Http\Controllers;

use App\Models\CajasDelPallet;
use App\Models\Expedicion;
use App\Models\ExpedicionItem;
use App\Models\Historial;
use App\Models\PaletArmado;
use App\Models\PaletArmadoItem;
use App\Models\Pedido;
use App\Models\PedidoEnPreparacionItem;
use App\Models\PedidoItem;
use App\Models\PedItemMov;
use App\Models\Producto;
use App\Models\ProductosPedidoSession;
use App\Models\SPallet;
use App\Models\Ubicacion;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;
use stdClass;
use Yajra\DataTables\Facades\DataTables;

class PedidosController extends Controller
{
    public function index(){
        return view('pedidos.index');
    }

    public function pedidosArmados(){
        return view('pedidos.armados');
    }

    public function getPedidos(Request $request){
        if ($request->ajax()) {
           $expedicion = Expedicion::select('nro_comp')->get();

            if(count($expedicion)){
                $array_implode = implode(' | ',array_unique($expedicion->pluck('nro_comp')->toArray()));
                $array_explode = explode(' | ',$array_implode);
                $nros_comp = array_unique($array_explode);
                $pedidos = Pedido::where('Estado','P')->whereNotIn('NroCom',$nros_comp)->with('cliente','items','sucursal')->orderBy('NroCom','ASC')->get();
            }else{
                $pedidos = Pedido::where('Estado','P')->with('cliente','items','sucursal')->orderBy('NroCom','ASC')->get();
            }

            return Datatables::of($pedidos)
                ->addColumn('seleccionar', function ($pedidosItem) {
                    return '<input type="checkbox" class="deleteItem" value="'.$pedidosItem->NroCom.'"> &nbsp;&nbsp;&nbsp;'. $pedidosItem->NroCom;
                })
                ->addColumn('fecha', function ($pedidosItem) {
                    return Carbon::parse($pedidosItem->FecCom)->format('d/m/Y H:i');
                })
                ->addColumn('cantidad_items', function ($pedidosItem) {
                    return '<span class="badge bg-success" style="color:white;cursor:pointer" onclick="getDetalles('.$pedidosItem->NroCom.')">'.count($pedidosItem->items).'</span>' ;
                })
                ->addColumn('cliente', function ($pedidosItem) {
                    if(isset($pedidosItem->cliente)){
                        return $pedidosItem->cliente->RazSoc;
                    }
                })
                ->addColumn('sucursal', function ($pedidosItem) {
                    if(isset($pedidosItem->cliente) && isset($pedidosItem->sucursal) && !is_null($pedidosItem->sucursal->Nombre)){
                        return $pedidosItem->sucursal->Nombre ;
                    }elseif(isset($pedidosItem->cliente)){
                        return $pedidosItem->cliente->NomFan;
                    }
                })
                ->addColumn('direccion', function ($pedidosItem) {
                    if(isset($pedidosItem->cliente) && isset($pedidosItem->sucursal)){
                        return $pedidosItem->sucursal->Domici ;
                    }elseif(isset($pedidosItem->cliente)){
                        return $pedidosItem->cliente->Domici;
                    }
                })
                ->rawColumns(['fecha','seleccionar','cantidad_items','cliente','sucursal'])
                ->addIndexColumn()
                ->make(true);
        }
    }

    public function getPedidosArmados(Request $request){
        if ($request->ajax()) {

            $pedidos = Pedido::where('Estado','AP')->with('cliente','items','sucursal','itemsMov')->orderBy('NroCom','ASC')->get();

            return Datatables::of($pedidos)
                ->addColumn('seleccionar', function ($pedidosItem) {
                    return $pedidosItem->NroCom;
                })
                ->addColumn('fecha', function ($pedidosItem) {
                    return Carbon::parse($pedidosItem->FecCom)->format('d/m/Y H:i');
                })
                ->addColumn('cantidad_items', function ($pedidosItem) {
                    return '<span class="badge bg-success" style="color:white;cursor:pointer" onclick="getDetalles('.$pedidosItem->NroCom.')">'.count($pedidosItem->items).'</span>' ;
                })
                ->addColumn('cantidad_items_armados', function ($pedidosItem) {
                    return '<span class="badge bg-success" style="color:white;cursor:pointer" onclick="getDetallesArmados('.$pedidosItem->NroCom.')">'.count($pedidosItem->itemsMov).'</span>' ;
                })
                ->addColumn('cliente', function ($pedidosItem) {
                    if(isset($pedidosItem->cliente)){
                        return $pedidosItem->cliente->RazSoc;
                    }
                })
                ->addColumn('sucursal', function ($pedidosItem) {
                    if(isset($pedidosItem->cliente) && isset($pedidosItem->sucursal) && !is_null($pedidosItem->sucursal->Nombre)){
                        return $pedidosItem->sucursal->Nombre ;
                    }elseif(isset($pedidosItem->cliente)){
                        return $pedidosItem->cliente->NomFan;
                    }
                })
                ->addColumn('direccion', function ($pedidosItem) {
                    if(isset($pedidosItem->cliente) && isset($pedidosItem->sucursal)){
                        return $pedidosItem->sucursal->Domici ;
                    }elseif(isset($pedidosItem->cliente)){
                        return $pedidosItem->cliente->Domici;
                    }
                })
                ->rawColumns(['fecha','cantidad_items','cliente','sucursal','cantidad_items_armados'])
                ->addIndexColumn()
                ->make(true);
        }
    }

    public function prepararPedido(Request $request){
        PedidoEnPreparacionItem::truncate();
        if($request->has('exp_id')){
            $prod_en_exp  = Expedicion::where('id',$request->exp_id)->first();
            if($prod_en_exp){
                $IdArti = ($prod_en_exp->codigo_articulo < 10)?(string)'0'.$prod_en_exp->codigo_articulo:(string)$prod_en_exp->codigo_articulo;
                $pedidosItemsCount = PedidoItem::select('IdArti',DB::raw('SUM(CanUni) as CanUni'))->where('IdArti',$IdArti)->whereIn('NroCom',$request->pedidosNro)->groupBy('IdArti')->get();
            }else{
                $pedidosItemsCount = [];
            }
        }else{
            //Pedidos de SICOI
            $pedidosItemsCount = PedidoItem::select('IdArti',DB::raw('SUM(CanUni) as CanUni'))->whereIn('NroCom',$request->pedidosNro)->groupBy('IdArti')->get();
        }

        foreach ($pedidosItemsCount as $item) {
            $break              = false;
            $sumaCantidades     = $cajas_en_exp = $piezas_en_exp = 0;
            $IdArti             = (int) $item->IdArti; //En SICOI el IdArti corresponde al codigo del producto
            $cantidadSolicitada = $item->CanUni;
            //Por cada item del pedido de SICOI busco en las ubicaciones de los pallets si se encuentra el producto
            $sPallets     = SPallet::with('ubicacion')->has('ubicacion')->where('estado_id',1)->where('codigo',$IdArti)->where('piezas','>',0)->orderBy('fecha_vencimiento','ASC')->orderBy('piezas','ASC')->get();
            $pedidosItems = PedidoItem::select('NroCom')->where('IdArti',$item->IdArti)->whereIn('NroCom',$request->pedidosNro)->pluck('NroCom')->toArray();
            $producto     = Producto::where('Codigo',$IdArti)->first();
            $prod_en_exp  = Expedicion::where('codigo_articulo',$IdArti)->first();

            if($prod_en_exp && $request->has('exp_id')){
                $cajas_en_exp  = $prod_en_exp->cajas_cargada;
                $piezas_en_exp = $prod_en_exp->piezas_cargada;
                $cantidadSolicitada -= $piezas_en_exp;
            }

            $nroPedidos = implode(' | ',$pedidosItems);

            if(count($sPallets) && $producto){
                $total_a_descontar = 0;
                foreach ($sPallets as $pallet) {
                    //Si en el primer pallet se cubre la cantidad solicitada se descuenta al pallet y se pasa al siguiente producto
                    if(($sumaCantidades == 0 && $cantidadSolicitada < $pallet->piezas) || ($sumaCantidades == 0 && $cantidadSolicitada == $pallet->piezas)){
                        $cantidad_a_descontar = $cantidadSolicitada;
                        $break = true;
                    }else{
                        if($sumaCantidades > 0){
                            $cantidad_faltante = $cantidadSolicitada - $sumaCantidades;
                            if(($cantidad_faltante < $pallet->piezas) || ($cantidad_faltante == $pallet->piezas) ){
                                $cantidad_a_descontar = $cantidad_faltante;
                                $break = true;
                            }else{
                                $sumaCantidades += $pallet->piezas;
                                $cantidad_a_descontar = $pallet->piezas;
                            }
                        }else{
                            $cantidad_a_descontar = $pallet->piezas;
                            $sumaCantidades += $pallet->piezas;
                        }
                    }


                    $ubicacion = $pallet->ubicacion->camara->nombre .' '.$pallet->ubicacion->calle->nombre . ' '.$pallet->ubicacion->altura->nombre .' '.$pallet->ubicacion->profundidad->nombre;

                    $total_a_descontar += $cantidad_a_descontar;

                    PedidoEnPreparacionItem::updateOrCreate([
                        'id_articulo'              => $producto->Id,
                        'codigo_articulo'          => $producto->Codigo,
                        'ubicacion_id'             => $pallet->ubicacion->id
                    ],[
                        'articulo'                 => $producto->Descripcion,
                        'nro_comp'                 => $nroPedidos,
                        'id_pallet'                => $pallet->id,
                        'fecha_de_pedido_sicoi'    => null,
                        'cantidad_solicitada'      => $cantidadSolicitada,
                        'cantidad_a_descontar'     => $cantidad_a_descontar,
                        'ubicacion'                => $ubicacion
                    ]);

                    if($break) break;
                }
            }elseif($producto){
                if(!PedidoEnPreparacionItem::where('id_articulo',$producto->Id)->where('codigo_articulo',$producto->Codigo)->exists()){
                    PedidoEnPreparacionItem::create([
                        'id_articulo'              => $producto->Id,
                        'codigo_articulo'          => $producto->Codigo,
                        'articulo'                 => $producto->Descripcion,
                        'ubicacion_id'             => null,
                        'nro_comp'                 => $nroPedidos,
                        'id_pallet'                => null,
                        'fecha_de_pedido_sicoi'    => null,
                        'cantidad_solicitada'      => $cantidadSolicitada,
                        'cantidad_a_descontar'     => 0,
                        'ubicacion'                => null,
                        'estado'                   => 'SIN_STOCK',
                    ]);
                }
            }
        }

        return new JsonResponse([
            'msj'  => 'Pedidos armados!',
            'type' => 'success',
        ]);
    }

    public function pedidosEnPreparacion(Request $request){
        return view('pedidos.en_preparacion');
    }

    public function prepararPedidoView(Request $request){
        $nroCom = json_decode($request->pedidosNro);
        $nroCom = implode(' - ',$nroCom);
        $actualiza_exp = ($request->has('actualiza_exp'))?1:0;
        $cod_art = 0;
        ProductosPedidoSession::truncate();

        if($request->has('exp_id')){
            $prod_en_exp  = Expedicion::where('id',$request->exp_id)->first();
            if($prod_en_exp){
                $pedidosEnPrep = PedidoEnPreparacionItem::where('codigo_articulo',$prod_en_exp->codigo_articulo)->get();
                $cod_art = $prod_en_exp->codigo_articulo;
            }else{
                $pedidosEnPrep = [];
            }
        }else{
            $pedidosEnPrep = PedidoEnPreparacionItem::get();
        }

        $data = [];
        $array_ids = [];
        $array_ubicaciones = [];
        $array_suma_ubicaciones = [];

        foreach ($pedidosEnPrep as $pi) {
            $total_a_descontar =  $total_solicitado =  0;
            $links = [];
            $subQuery = PedidoEnPreparacionItem::select('id','ubicacion','ubicacion_id','id_pallet','cantidad_a_descontar','cantidad_solicitada')
                                               ->where('codigo_articulo',$pi->codigo_articulo)
                                               ->with('s_pallet')
                                               ->get();

            foreach ($subQuery as $s) {
                $total_a_descontar += $s->cantidad_a_descontar;
                if(!is_null($s->ubicacion) && !in_array($s->ubicacion_id,$array_ubicaciones)){
                    $cant                = (int) $s->cantidad_a_descontar;
                    $piezas_pallet_total = (int) $s->s_pallet->piezasTotales();
                    $piezas_pallet       = $s->s_pallet->piezas;

                    $route = route('ver.ubicacion',['idUbicacion' => $s->ubicacion_id, 'idSPallet' => $s->id_pallet]);
                    $_link =' <a href="'.$route.'" target="_blank" rel="noopener noreferrer" style="color:green;cursor:pointer" title="Ver Ubicación">'.$s->ubicacion .'</a>';

                    $link['link']                        = $_link;
                    $link['cant_a_descontar_del_pallet'] = $cant;
                    $link['cant__del_pallet']            = $piezas_pallet;
                    $link['pedido_en_prep_id']           = $s->id;
                    $link['muestra_select_all']          = ($cant == $piezas_pallet)?1:0;

                    array_push($links,$link);
                    array_push($array_ubicaciones,$s->ubicacion_id);
                }
            }

            $dataItem = new stdClass;

            if(!in_array($pi->id_articulo,$array_ids)){
                $dataItem->producto             = $pi->codigo_articulo .' - '.$pi->articulo;
                $dataItem->cantidad_solicitada  = $pi->cantidad_solicitada;
                $dataItem->ubicaciones          = $links;
                $dataItem->cantidad_a_descontar = $total_a_descontar;

                array_push($data,$dataItem);
                array_push($array_ids,$pi->id_articulo);
            }
        }

        return view('pedidos.detalle-pedido', compact('data','nroCom','actualiza_exp','cod_art'));
    }

    public function setPesos(Request $request){
        $data = $request->all();
        $peso_nominal = $data['peso_nominal'];
        $tolerancia   = $data['tolerancia'];
        $pedido_en_prep_id   = $data['pedido_en_prep_id_set_pesos'];
        $porc         = ($tolerancia * $peso_nominal) / 100;
        $min          = $peso_nominal - $porc;
        $max          = $peso_nominal + $porc;
        $array_piezas = [];
        try {
            for ($i=0 ; $i < count($data['array_ids']) ; $i++ ) {
                $id = $data['array_ids'][$i];
                if($data['cajaid-'.$id]){
                    $valor = $data['cajaid-'.$id];
                    if($valor > $min && $valor < $max){
                        CajasDelPallet::where('id',$id)->update(['peso_real' => $valor,'peso' => $valor]);
                    }else{
                        $pieza = CajasDelPallet::where('id',$id)->first();
                        array_push($array_piezas,$pieza);
                    }
                }
            }
            if(count($array_piezas)){
                $view = view('ubicaciones.set-pesos',compact('array_piezas','pedido_en_prep_id','peso_nominal','tolerancia'))->render();
                return new JsonResponse([
                    'cod_barra' => '',
                    'pedido_en_prep_id' => $pedido_en_prep_id,
                    'view' => $view,
                    'msj'  => 'Hay piezas que no tienen peso o están alejados de su valor nominal',
                    'type' => 'error',
                ]);
            }
            return new JsonResponse([
                'msj'  => 'Se actualizaron los pesos de los items',
                'type' => 'success',
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'msj'  => $e->getMessage(),
                'type' => 'error',
            ]);
        }
    }

    public function setCodbarrasToPEdido(Request $request){
        try {
            $cod_barras = $request->cod_barras;
            $pedido_en_prep_id = $request->pedido_en_prep_id;
            $array_piezas = [];
            $piezas_pallet = CajasDelPallet::where('codigo_barras_pallet',$cod_barras)
                                           ->orWhere('codigo_barras_articulo',$cod_barras)
                                           ->orWhere('codigo_barras_caja',$cod_barras)
                                           ->with('producto2')
                                           ->get();
            foreach ($piezas_pallet as $pieza) {
                if($pieza->peso_real < 1){
                    array_push($array_piezas,$pieza);
                }
            }
            if(count($array_piezas)){
                $peso_nominal = $array_piezas[0]->producto2->PesoNominal;
                $tolerancia = 15;
                $view = view('ubicaciones.set-pesos',compact('array_piezas','pedido_en_prep_id','peso_nominal','tolerancia'))->render();
                return new JsonResponse([
                    'cod_barra' => $cod_barras,
                    'pedido_en_prep_id' => $pedido_en_prep_id,
                    'view' => $view,
                    'msj'  => 'Hay piezas que no tienen peso',
                    'type' => 'error',
                ]);
            }
            $result = $this->verificar($request);

            return new JsonResponse($result);

        } catch (\Exception $e) {
            return new JsonResponse([
                'cod_barra' => '',
                'pedido_en_prep_id' => '',
                'view' => '',
                'msj'  => $e->getMessage(),
                'type' => 'error',
            ]);
        }
    }

    public function verificar($request){
        $cod_barras = $request->cod_barras;
        $cant_a_descontar_del_pallet = $request->cant_a_descontar_del_pallet;
        $cant__del_pallet = $request->cant__del_pallet;
        $pedido_en_prep_id = $request->pedido_en_prep_id;
        $movimiento = $request->movimiento;
        $pedido_item = PedidoEnPreparacionItem::where('id',$pedido_en_prep_id)->with('s_pallet')->first();

        if(!$pedido_item) return ['type' => 'error', 'msj' => "El pedido no existe!"];
        // all envia Código de Barras  del pallet
        // in/out puede enviar codigo de barra del articulo o caja
        switch ($movimiento) {
            case 'all':
                if($pedido_item->s_pallet->codigo_barras != $cod_barras) return ['type' => 'error', 'msj' => "El Codigo de barras no corresponde a un pallet en el pedido!"];

                $count_ped_session = ProductosPedidoSession::where('pedido_en_prep_id',$pedido_en_prep_id)->where('codigo_barras_pallet',$cod_barras)->count();
                if($count_ped_session == $cant_a_descontar_del_pallet || $count_ped_session > $cant_a_descontar_del_pallet ){
                    $html_final   = $this->armarHtml($pedido_en_prep_id,$cod_barras,'pallet');
                    return ['type' => 'error', 'html' => $html_final, 'msj' => "Ya cargo el pallet y completó los ".$cant_a_descontar_del_pallet." artículos!"];
                }

                $piezas_pallet = CajasDelPallet::where('codigo_barras_pallet',$cod_barras)->get();

                foreach ($piezas_pallet as $pieza_pallet) {
                    ProductosPedidoSession::updateOrCreate([
                        'pedido_en_prep_id'      => $pedido_en_prep_id,
                        'codigo_barras_pallet'   => $cod_barras,
                        'codigo_barras_articulo' => $pieza_pallet->codigo_barras_articulo ,
                        'peso'                   => $pieza_pallet->peso ,
                        'peso_real'              => $pieza_pallet->peso_real ,
                        'nro_pieza'              => $pieza_pallet->nro_pieza ,
                        'nro_caja'               => $pieza_pallet->nro_caja ,
                        'nro_pallet'             => $pieza_pallet->nro_pallet ,
                        'lote'                   => $pieza_pallet->lote ,
                        'codigo_barras_caja'     => $pieza_pallet->codigo_barras_caja],[
                        'tipo_mov'               => 'all',
                    ]);
                }

                $html_final   = $this->armarHtml($pedido_en_prep_id,$cod_barras,'pallet');

                return ['type' => 'success', 'html' => $html_final, 'msj' => 'Pallet: '. $cod_barras . ' agregado!' ];
                break;

            case 'out':
                $piezas_caja      = CajasDelPallet::where('codigo_barras_caja',$cod_barras)->get();

                if(count($piezas_caja)){
                    $codigo_barras_pallet_de_la_caja = $piezas_caja[0]->codigo_barras_pallet;
                    if($pedido_item->s_pallet->codigo_barras != $codigo_barras_pallet_de_la_caja) return ['type' => 'error', 'msj' => "El Codigo de barras no corresponde a una caja del pallet!"];

                    $count_ped_session = ProductosPedidoSession::where('pedido_en_prep_id',$pedido_en_prep_id)->where('codigo_barras_pallet',$codigo_barras_pallet_de_la_caja)->count();
                    if($count_ped_session == $cant_a_descontar_del_pallet ){
                        $html_final   = $this->armarHtml($pedido_en_prep_id,$codigo_barras_pallet_de_la_caja,'pallet');
                        return ['type' => 'error', 'html' => $html_final, 'msj' => "Ya cargo el pallet y completó los ".$cant_a_descontar_del_pallet." artículos!"];
                    }

                    foreach ($piezas_caja as $pieza_caja) {
                        ProductosPedidoSession::updateOrCreate([
                            'pedido_en_prep_id'      => $pedido_en_prep_id,
                            'codigo_barras_pallet'   => $codigo_barras_pallet_de_la_caja,
                            'codigo_barras_articulo' => $pieza_caja->codigo_barras_articulo ,
                            'peso'                   => $pieza_caja->peso ,
                            'peso_real'              => $pieza_caja->peso_real ,
                            'nro_pieza'              => $pieza_caja->nro_pieza ,
                            'nro_caja'               => $pieza_caja->nro_caja ,
                            'nro_pallet'             => $pieza_caja->nro_pallet ,
                            'lote'                   => $pieza_caja->lote ,
                            'codigo_barras_caja'     => $pieza_caja->codigo_barras_caja],[
                            'tipo_mov'               => 'out',
                        ]);
                    }

                    $html_final   = $this->armarHtml($pedido_en_prep_id,$codigo_barras_pallet_de_la_caja,'pallet');
                    return ['type' => 'success', 'html' => $html_final, 'msj' => 'Caja: '. $cod_barras . ' agregada!' ];
                }else{
                    $pieza      = CajasDelPallet::where('codigo_barras_articulo',$cod_barras)->first();
                    if($pieza){
                        $codigo_barras_pallet_de_la_pieza = $pieza->codigo_barras_pallet;
                        if($pedido_item->s_pallet->codigo_barras != $codigo_barras_pallet_de_la_pieza) return ['type' => 'error', 'msj' => "El Codigo de barras no corresponde a una pieza del pallet!"];

                        $count_ped_session = ProductosPedidoSession::where('pedido_en_prep_id',$pedido_en_prep_id)->where('codigo_barras_pallet',$codigo_barras_pallet_de_la_pieza)->count();
                        if($count_ped_session == $cant_a_descontar_del_pallet ){
                            $html_final   = $this->armarHtml($pedido_en_prep_id,$codigo_barras_pallet_de_la_pieza,'pallet');
                            return ['type' => 'error', 'html' => $html_final, 'msj' => "Ya cargo el pallet y completó los ".$cant_a_descontar_del_pallet." artículos!"];
                        }

                        ProductosPedidoSession::updateOrCreate([
                            'pedido_en_prep_id'      => $pedido_en_prep_id,
                            'codigo_barras_pallet'   => $codigo_barras_pallet_de_la_pieza,
                            'codigo_barras_articulo' => $pieza->codigo_barras_articulo ,
                            'peso'                   => $pieza->peso ,
                            'peso_real'              => $pieza->peso_real ,
                            'nro_pieza'              => $pieza->nro_pieza ,
                            'nro_caja'               => $pieza->nro_caja ,
                            'nro_pallet'             => $pieza->nro_pallet ,
                            'lote'                   => $pieza->lote ,
                            'codigo_barras_caja'     => $pieza->codigo_barras_caja],[
                            'tipo_mov'               => 'out',
                        ]);


                        $html_final   = $this->armarHtml($pedido_en_prep_id,$codigo_barras_pallet_de_la_pieza,'pallet');
                        return ['type' => 'success', 'html' => $html_final, 'msj' => 'Caja: '. $cod_barras . ' agregada!' ];

                    }else{
                        return ['type' => 'error', 'msj' => "El Codigo de barras no existe en el pallet!"];
                    }
                }

                break;

            case 'in':
                $piezas_caja      = CajasDelPallet::where('codigo_barras_caja',$cod_barras)->get();

                if(count($piezas_caja)){
                    $codigo_barras_pallet_de_la_caja = $piezas_caja[0]->codigo_barras_pallet;
                    if($pedido_item->s_pallet->codigo_barras != $codigo_barras_pallet_de_la_caja) return ['type' => 'error', 'msj' => "El Codigo de barras no corresponde a una caja del pallet!"];

                    ProductosPedidoSession::where('pedido_en_prep_id',$pedido_en_prep_id)->where('tipo_mov','!=','in')->delete();
                    $count_ped_session = ProductosPedidoSession::where('pedido_en_prep_id',$pedido_en_prep_id)->where('tipo_mov','in')->count();

                    if(!$count_ped_session){
                        $piezas_pallet = CajasDelPallet::where('codigo_barras_pallet',$codigo_barras_pallet_de_la_caja)->get();

                        foreach ($piezas_pallet as $pieza_pallet) {
                            ProductosPedidoSession::updateOrCreate([
                                'pedido_en_prep_id' => $pedido_en_prep_id,
                                'peso'                   => $pieza_pallet->peso ,
                                'peso_real'              => $pieza_pallet->peso_real ,
                                'nro_pieza'              => $pieza_pallet->nro_pieza ,
                                'nro_caja'               => $pieza_pallet->nro_caja ,
                                'nro_pallet'             => $pieza_pallet->nro_pallet ,
                                'lote'                   => $pieza_pallet->lote ,
                                'codigo_barras_pallet' => $codigo_barras_pallet_de_la_caja,
                                'codigo_barras_articulo' => $pieza_pallet->codigo_barras_articulo ,
                                'codigo_barras_caja' => $pieza_pallet->codigo_barras_caja],[
                                'tipo_mov' => 'in',
                            ]);
                        }
                    }

                    ProductosPedidoSession::where('pedido_en_prep_id',$pedido_en_prep_id)->where('codigo_barras_caja',$cod_barras)->delete();

                    $html_final   = $this->armarHtml($pedido_en_prep_id,$codigo_barras_pallet_de_la_caja,'pallet');
                    return ['type' => 'success', 'html' => $html_final, 'msj' => 'Caja: '. $cod_barras . ' agregada!' ];
                }else{
                    $pieza      = CajasDelPallet::where('codigo_barras_articulo',$cod_barras)->first();
                    if($pieza){
                        $codigo_barras_pallet_de_la_pieza = $pieza->codigo_barras_pallet;
                        if($pedido_item->s_pallet->codigo_barras != $codigo_barras_pallet_de_la_pieza) return ['type' => 'error', 'msj' => "El Codigo de barras no corresponde a una pieza del pallet!"];

                        ProductosPedidoSession::where('pedido_en_prep_id',$pedido_en_prep_id)->where('tipo_mov','!=','in')->delete();
                        $count_ped_session = ProductosPedidoSession::where('pedido_en_prep_id',$pedido_en_prep_id)->where('tipo_mov','in')->count();

                        if(!$count_ped_session){
                            $piezas_pallet = CajasDelPallet::where('codigo_barras_pallet',$codigo_barras_pallet_de_la_pieza)->get();

                            foreach ($piezas_pallet as $pieza_pallet) {
                                ProductosPedidoSession::updateOrCreate([
                                    'pedido_en_prep_id' => $pedido_en_prep_id,
                                    'peso'                   => $pieza_pallet->peso ,
                                    'peso_real'              => $pieza_pallet->peso_real ,
                                    'nro_pieza'              => $pieza_pallet->nro_pieza ,
                                    'nro_caja'               => $pieza_pallet->nro_caja ,
                                    'nro_pallet'             => $pieza_pallet->nro_pallet ,
                                    'lote'                   => $pieza_pallet->lote ,
                                    'codigo_barras_pallet' => $codigo_barras_pallet_de_la_pieza,
                                    'codigo_barras_articulo' => $pieza_pallet->codigo_barras_articulo ,
                                    'codigo_barras_caja' => $pieza_pallet->codigo_barras_caja],[
                                    'tipo_mov' => 'in',
                                ]);
                            }
                        }

                        ProductosPedidoSession::where('pedido_en_prep_id',$pedido_en_prep_id)->where('codigo_barras_articulo',$cod_barras)->delete();

                        $html_final   = $this->armarHtml($pedido_en_prep_id,$codigo_barras_pallet_de_la_pieza,'pallet');
                        return ['type' => 'success', 'html' => $html_final, 'msj' => 'Caja: '. $cod_barras . ' agregada!' ];

                    }else{
                        return ['type' => 'error', 'msj' => "El Codigo de barras no existe en el pallet!"];
                    }
                }

                break;
            default:
                # code...
                break;
        }
    }

    private function armarHtml($pedido_en_prep_id,$cod_barras,$tipo){
        if($tipo == 'pallet'){
            $piezas_pallet = ProductosPedidoSession::where('pedido_en_prep_id',$pedido_en_prep_id)->where('codigo_barras_pallet',$cod_barras)->get();
        }
        $count_piezas  = $count_cajas = 0;
        $html          = $html_c = '';
        $array_cajas   = [];

        foreach ($piezas_pallet as $pieza_pallet) {
            $count_piezas++;
            $stringPieza = "'".(string)$pieza_pallet->codigo_barras_articulo."'";
            $html .= '<span class="text-xs px-1 rounded-full bg-success text-white mr-1">'. $count_piezas .'</span>'. $pieza_pallet->codigo_barras_articulo ;
            $html .= ' <button class="btn btn-sm btn-danger mr-1 mb-2" title="Quitar Artículo" style="margin: 0px 1px 2px;padding: 1px 15px" ';
            $html .= 'onclick="quitarArticulo('. $pedido_en_prep_id.','.$stringPieza.')">';
            $html .= 'Quitar</button><br>';

            if(!in_array($pieza_pallet->codigo_barras_caja,$array_cajas)) array_push($array_cajas,$pieza_pallet->codigo_barras_caja);
        }

        $total_piezas = count($piezas_pallet);
        $total_cajas  = count($array_cajas);

        for ($i=0; $i < count($array_cajas) ; $i++) {
            $count_cajas++;
            $stringCaja = "'".(string)$array_cajas[$i]."'";
            $html_c .= '<span class="text-xs px-1 rounded-full bg-primary text-white mr-1">'. $count_cajas .'</span>'. $array_cajas[$i];
            $html_c .= ' <button class="btn btn-sm btn-danger mr-1 mb-2" title="Quitar Caja" style="margin: 0px 1px 2px;padding: 1px 15px" ';
            $html_c .= ' onclick="quitarCaja('. $pedido_en_prep_id.','.$stringCaja.')">';
            $html_c .= 'Quitar</button><br>';
        }

        $html_final   = '<strong>Cantidad de cajas: '.$total_cajas .'</strong><br>'.$html_c. '<strong>Cantidad de piezas: '.$total_piezas.'</strong><br>'.$html;
        return $html_final;
    }

    public function quitarArticuloSession(Request $request){
        try {
            $prodSession = ProductosPedidoSession::where('pedido_en_prep_id',$request->pedido_en_prep_id)->where('codigo_barras_articulo',$request->codigo_barras_articulo)->first();
            $cod_barras = $prodSession->codigo_barras_pallet;
            $prodSession->delete();
            if($prodSession){
                $html_final   = $this->armarHtml($request->pedido_en_prep_id,$cod_barras,'pallet');

                return ['type' => 'success', 'html' => $html_final, 'msj' => 'Artículo quitado!' ];
            }else{
                return new JsonResponse([
                    'msj'  => 'No se pudo elimiar el artículo!',
                    'type' => 'error',
                ]);
            }
        } catch (\Exception $e) {
            return new JsonResponse([
                'msj'  => $e->getMessage(),
                'type' => 'error',
            ]);
        }
    }

    public function quitarCajaSession(Request $request){
        try {
            $prodSession = ProductosPedidoSession::where('pedido_en_prep_id',$request->pedido_en_prep_id)->where('codigo_barras_caja',$request->codigo_barras_caja)->first();
            $cod_barras  = $prodSession->codigo_barras_pallet;
            $prodSession = ProductosPedidoSession::where('pedido_en_prep_id',$request->pedido_en_prep_id)->where('codigo_barras_caja',$request->codigo_barras_caja)->delete();
            if($prodSession){
                $html_final   = $this->armarHtml($request->pedido_en_prep_id,$cod_barras,'pallet');

                return ['type' => 'success', 'html' => $html_final, 'msj' => 'Caja quitada!' ];
            }else{
                return new JsonResponse([
                    'msj'  => 'No se pudo elimiar la caja!',
                    'type' => 'error',
                ]);
            }
        } catch (\Exception $e) {
            return new JsonResponse([
                'msj'  => $e->getMessage(),
                'type' => 'error',
            ]);
        }
    }

    public function verificarPaseAExpedicion(Request $request){
        if($request->cod_art > 0 || $request->cod_art != '0'){
            $pedidos_en_preparacion = PedidoEnPreparacionItem::where('codigo_articulo',$request->cod_art)->get();
        }else{
            $pedidos_en_preparacion = PedidoEnPreparacionItem::all();
        }

        foreach ($pedidos_en_preparacion as $pedido_en_prep) {
            $cant_de_piezas_en_session = ProductosPedidoSession::where('pedido_en_prep_id',$pedido_en_prep->id)->count();
            $pedido_en_prep->cant_de_piezas_en_session = $cant_de_piezas_en_session;
        }
        $view = view('pedidos.detalles-verificacion',compact('pedidos_en_preparacion'))->render();
        return ['type' => 'success', 'view' => $view];
    }

    public function pasarAExpedicion(Request $request){
        try {
            DB::beginTransaction();
            Schema::disableForeignKeyConstraints();

            $ped = PedidoEnPreparacionItem::all();
            //Obtengo todos los peddidos para procesar uno a uno
            foreach ($ped as $p) {
                $descripcion_hist = null;
                //Total de piezas que tiene el pedido id en preparacion
                $productos_session = ProductosPedidoSession::where('pedido_en_prep_id',$p->id)->get();
                $cant_de_piezas_en_session = count($productos_session);
                $p->cant_de_piezas_en_session = $cant_de_piezas_en_session;

                $pesos = ProductosPedidoSession::select('codigo_barras_pallet',DB::raw('SUM(peso) as peso'), DB::raw('SUM(peso_real) as peso_real'))
                                    ->groupBy('codigo_barras_pallet')
                                    ->where('pedido_en_prep_id',$p->id)
                                    ->first();

                $cant_de_cajas_en_session = ProductosPedidoSession::select('codigo_barras_caja')->where('pedido_en_prep_id',$p->id)->groupBy('codigo_barras_caja')->get();

                if(!is_null($p->id_pallet)){
                    $_peso = $_peso_real = 0;
                    if($pesos){
                        $_peso = $pesos->peso;
                        $_peso_real = $pesos->peso_real;
                    }
                    //Si la cantidad de piezas que tiene el pallet del pedido es igual a la cantidad de piezas que se cargaron en session se elimina el pallet o si es diferente se actualiza
                    $s_pallet = SPallet::where('id',$p->id_pallet)->first();
                    $result_piezas = $s_pallet->piezas - $cant_de_piezas_en_session;

                    if($result_piezas == 0){
                        $descripcion_hist = 'Quedá disponible la ubicación '. $p->ubicacion;
                        $s_pallet->delete();
                    }elseif($s_pallet){
                        $s_pallet->piezas    = $result_piezas;
                        $s_pallet->peso      = $s_pallet->peso - $_peso ;
                        $s_pallet->peso_real = $s_pallet->peso_real - $_peso_real;
                        $s_pallet->save();
                    }

                    $ubicacion = Ubicacion::where('id',$p->ubicacion_id)->first();
                    $result_piezas = $ubicacion->piezas_total - $cant_de_piezas_en_session;
                    if($result_piezas == 0){
                        $ubicacion->pallet = null;
                        $ubicacion->peso_total = 0;
                        $ubicacion->peso_real_total = 0;
                        $ubicacion->piezas_total = 0;
                        $ubicacion->cajas = 0;
                        $ubicacion->fecha = null;
                    }else{
                        $ubicacion->peso_total      = $ubicacion->peso_total - $_peso;
                        $ubicacion->peso_real_total = $ubicacion->peso_real_total - $_peso_real;
                        $ubicacion->piezas_total = $result_piezas;
                        $ubicacion->cajas = $ubicacion->cajas - count($cant_de_cajas_en_session);
                    }

                    $ubicacion->save();
                }

                $p->piezas_cargada   = $cant_de_piezas_en_session;
                $p->cajas_cargada    = count($cant_de_cajas_en_session);
                $p->user_id          = Auth::user()->id;
                $p->fecha            = now();
                $exp = Expedicion::where('id_articulo',$p->id_articulo)->where('nro_comp',$p->nro_comp)->first();
                if($exp){
                    $id_exp = $exp->id;
                    $exp->piezas_cargada += $p->piezas_cargada;
                    $exp->cajas_cargada += $p->cajas_cargada;
                    $exp->save();
                }else{
                    $exp = Expedicion::create($p->toArray());
                    $id_exp = $exp->id;
                }

                /* $exp = Expedicion::where('id_articulo',$p->id_articulo)->first();
                if($exp){
                    Log::info($exp->nro_comp);
                    Log::info($p->nro_comp);
                    $exp->nro_comp .= ' | '.$p->nro_comp;
                    $id_exp = $exp->id;
                    $exp->piezas_cargada       += $p->piezas_cargada;
                    $exp->cajas_cargada        += $p->cajas_cargada;
                    $exp->cantidad_solicitada  += $p->cantidad_solicitada;
                    $exp->save();
                    Log::info($exp->nro_comp);
                }else{
                    $exp = Expedicion::create($p->toArray());
                    $id_exp = $exp->id;
                } */

                if(isset($pesos->codigo_barras_pallet)){
                    Historial::create([
                        'CodBarraPallet_Int'=> $pesos->codigo_barras_pallet,
                        'user_id' => Auth::user()->id,
                        'fecha' => Carbon::parse(now())->format('Y-m-d'),
                        'hora' => Carbon::parse(now())->format('H:i'),
                        'descripcion'=> 'Se pasaron a expedición: <br> '. $p->cajas_cargada .' cajas'. '<br> '. $p->piezas_cargada .' unidades'. '<br> Para NP: '. $p->nro_comp
                    ]);

                    if($descripcion_hist){
                        Historial::create([
                            'CodBarraPallet_Int'=> $pesos->codigo_barras_pallet,
                            'user_id' => Auth::user()->id,
                            'fecha' => Carbon::parse(now())->format('Y-m-d'),
                            'hora' => Carbon::parse(now())->format('H:i'),
                            'descripcion'=> $descripcion_hist
                        ]);
                    }
                }
                $array_ps = $array_cb = [];
                foreach ($productos_session as $ps) {
                    $ps->expedicion_id = $id_exp;
                    $ps->ubicacion_id  = $p->ubicacion_id;
                    $ps->ubicacion     = $p->ubicacion;
                    array_push($array_cb,$ps->codigo_barras_articulo);
                    $new_ps = $ps->toArray();
                    unset($new_ps['pedido_en_prep_id']);
                    unset($new_ps['tipo_mov']);
                    unset($new_ps['id']);
                    array_push($array_ps,$new_ps);
                }
                //dd($array_ps);
                CajasDelPallet::whereIn('codigo_barras_articulo',$array_cb)->delete();
                ExpedicionItem::insert($array_ps);
            }

            ProductosPedidoSession::truncate();
            PedidoEnPreparacionItem::truncate();

            DB::commit();
            Schema::enableForeignKeyConstraints();

            return new JsonResponse([
                'msj'  => 'Pedidos pasados a expedición',
                'type' => 'success'
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

    public function getDetallesPedido(Request $request){
        $items = PedidoItem::where('NroCom',$request->nroComp)->with('producto')->get();
        $view = view('pedidos.items',compact('items'))->render();
        return ['type' => 'success', 'view' => $view];
    }

    public function getDetallesPedidoArmado(Request $request){
        $items = PedItemMov::where('NroCom',$request->nroComp)->with('producto')->get();
        $view = view('pedidos.items-armados',compact('items'))->render();
        return ['type' => 'success', 'view' => $view];
    }

    public function pedidosEstado(){
        $palets  = PaletArmado::select('comprobantes')->pluck('comprobantes')->toArray();
        if(count($palets)){
            $array_implode = implode(' | ',$palets);
            $array_explode = explode(' | ',$array_implode);

            $nros_comp = array_unique($array_explode);
            $pedidos = Pedido::where('Estado','P')->whereIn('NroCom',$nros_comp)->with('cliente','items','sucursal')->orderBy('NroCom','ASC')->get();
            foreach ($pedidos as $pedido) {
                $pedido->articulos_solicitados = count($pedido->items);
                $pedido->articulos_preparados  = PaletArmadoItem::where('comprobante',$pedido->NroCom)->distinct('codigo')->count();
            }
        }else{
            $pedidos = [];
        }

        return view('pedidos.estados',compact('pedidos'));
    }

    public function pedidoEstadoDetalle($nro_comp){
        $articulos = [];
        $pedido = Pedido::where('Estado','P')->where('NroCom',$nro_comp)->with('cliente','items','sucursal')->first();
        foreach ($pedido->items as $item) {
            $articulo = new stdClass;
            $articulo->codigo    = $item->producto->Codigo;
            $articulo->nombre    = $item->producto->Descripcion;
            $articulo->kilos     = $item->Cantid;
            $articulo->unidades  = $item->CanUni;

            $item_armado = PaletArmadoItem::select('codigo',
                                                    DB::raw('SUM(Peso_Real) as PesoReal'),
                                                    DB::raw('SUM(Peso) as Peso'),
                                                    DB::raw('COUNT(DISTINCT CodBarraCaja_Int) as cajas'),
                                                    DB::raw('COUNT(DISTINCT CodBarraArt_Int) as unidades')                                                   )
                                                  ->where('codigo',$item->producto->Codigo)
                                                  ->where('comprobante',$nro_comp)
                                                  ->where('estado','P')
                                                  ->groupBy('codigo')
                                                  ->first();

            $pai  = PaletArmadoItem::select('Lote','CodBarraPallet_Int')->where('codigo',$item->producto->Codigo)->where('estado','P')->where('comprobante',$nro_comp)->get();
            $lotes = $pai->pluck('Lote')->toArray();
            $palets = $pai->pluck('CodBarraPallet_Int')->toArray();
            $articulo->cajas_p     = ($item_armado)?$item_armado->cajas:0;
            $articulo->peso_real_p = ($item_armado)?$item_armado->PesoReal:0;
            $articulo->peso_p      = ($item_armado)?$item_armado->Peso:0;
            $articulo->unidades_p  = ($item_armado)?$item_armado->unidades:0;
            $articulo->palet       = ($item_armado)?implode(' | ',array_unique($palets)):null;
            $articulo->lote        = ($item_armado)?implode(' | ',array_unique($lotes)):null;
            array_push($articulos,$articulo);
        }

        return view('pedidos.detalles-pedido-estado',compact('articulos','pedido'));
    }

    public function cerrarPedido(Request $request){
        try {
            DB::beginTransaction();
            Schema::disableForeignKeyConstraints();

            $nro_comp = $request->nro_comp;
            $pedido = Pedido::where('Estado','P')->where('NroCom',$nro_comp)->first();
            $pedido->update(['Estado' => 'AP']);

            $palet_armados = PaletArmadoItem::select('CodBarraPallet_Int','nombre','Lote', 'Peso_Real','Peso','codigo')->where('comprobante',$nro_comp)->get();
            $items = PaletArmadoItem::select('comprobante','Lote','codigo',
                                                    DB::raw('SUM(Peso) as Peso'),
                                                    DB::raw('COUNT(DISTINCT CodBarraArt_Int) as unidades')
                                                )
                                                ->where('comprobante',$nro_comp)
                                                ->where('estado','P')
                                                ->groupBy('comprobante','Lote','codigo')
                                                ->get();
            $pedItemsMov =  $codigos  = [];
            foreach ($items as $item) {
                $codigo = $item->codigo;
                array_push($codigos,$codigo);
                $count_values_cod = array_count_values($codigos);
                $codigo_alfanumerico = ($codigo<10)? '0'.(string)$codigo: (string)$codigo;
                $ped_item = PedidoItem::select('IdTipo','NroCom','IdArti','itemPi')->where('NroCom',$nro_comp)->where('IdArti',$codigo_alfanumerico)->first();
                $pedItemMov = [
                    'IdTipo' => $ped_item->IdTipo,
                    'NroCom' => $ped_item->NroCom,
                    'IdArti' => $codigo_alfanumerico,
                    'itemPI' => $ped_item->itemPi,
                    'itemPM' => $count_values_cod[$codigo],
                    'Cantid' => $item->Peso,
                    'CanUni' => $item->unidades,
                    'NroLote'=> $item->Lote,
                ];
                array_push($pedItemsMov,$pedItemMov);
            }

            foreach ($palet_armados as $palet_armado) {
                Historial::create([
                    'CodBarraPallet_Int'=> $palet_armado->CodBarraPallet_Int,
                    'user_id' => Auth::user()->id,
                    'fecha' => Carbon::parse(now())->format('Y-m-d'),
                    'hora' => Carbon::parse(now())->format('H:i'),
                    'descripcion'=> 'Cierra NP'.$nro_comp . ' con '.$palet_armado->codigo .' - '. $palet_armado->nombre . ' Lote: '.$palet_armado->Lote. ' Peso R: '.$palet_armado->Peso_Real. ' Peso: '.$palet_armado->Peso
                ]);
            }

            PedItemMov::insert($pedItemsMov);
            PaletArmadoItem::where('comprobante',$nro_comp)->update(['estado' => 'AP','fecha_cierre_comprobante' =>Carbon::parse(now())->format('Y-m-d')]);
            $expediciones = Expedicion::where('nro_comp', 'LIKE', "%{$nro_comp}%")->with('items')->get();

            foreach ($expediciones as $exp) {
                $explode = explode(' | ',$exp->nro_comp);
                unset($explode[$nro_comp]);
                $nros_comp = implode(' | ',$explode);
                $exp->nro_comp = $nros_comp;
                $exp->save();
                foreach ($exp->items as $item) {
                    $item->estado = 'NO AGREGADO';
                    $item->save();
                }
            }
            DB::commit();
            Schema::enableForeignKeyConstraints();
            Session::flash('msj','El pedido '.$nro_comp. ' fue cerrado exitosamente');
            return redirect()->route('pedidos.armados');

        } catch (\Exception $e) {
            DB::rollback();
            Schema::enableForeignKeyConstraints();
            Session::flash('msj',$e->getMessage());
            return redirect()->back()->withInput();
        }

    }
}
