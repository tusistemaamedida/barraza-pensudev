<?php

namespace App\Http\Controllers;

use App\Models\CajasDelPallet;
use Illuminate\Support\Facades\DB;
use PDF;
use Illuminate\Support\Facades\File;

class BarCodeGeneratorController extends Controller
{
    public function generar($code_bar,$tipo){
        if (!File::exists(public_path('images'))) {
            File::makeDirectory(public_path('images'), $mode = 0777, true, true);
        }

        \QrCode::generate($code_bar, 'images/' . $code_bar . '.svg');
        $qr_url      = 'images/' . $code_bar . '.svg';

        if($tipo == 'P'){
            $pallet = CajasDelPallet::select(
                            'id_articulo as ID_Articulo',
                            'codigo_barras_pallet as CodBarraPallet_Int',
                            DB::raw('SUM(Peso_Real) as PesoReal'))
                    ->groupBy('codigo_barras_pallet' ,'id_articulo')
                    ->with('producto')
                    ->where('codigo_barras_pallet',$code_bar)
                    ->first();
            $cajas = CajasDelPallet::select(DB::raw('COUNT(codigo_barras_caja) as cantidad'))->groupBy('codigo_barras_caja')->where('codigo_barras_pallet',$code_bar)->first();
        }else{
            $pallet = CajasDelPallet::select(
                            'id_articulo as ID_Articulo',
                            'codigo_barras_caja',
                            DB::raw('SUM(Peso_Real) as PesoReal'))
                    ->groupBy('codigo_barras_caja' ,'id_articulo')
                    ->with('producto')
                    ->where('codigo_barras_caja',$code_bar)
                    ->first();
            $cajas = CajasDelPallet::select(DB::raw('COUNT(codigo_barras_caja) as cantidad'))->groupBy('codigo_barras_caja')->where('codigo_barras_caja',$code_bar)->first();
        }

        $producto = $pallet->producto->Descripcion;
        $rnpa     = $pallet->producto->RNPA;
        $unidades = $cajas->cantidad;
        $peso     = $pallet->PesoReal;
        $caja_gs1 = $pallet->producto->CodigoBarraCaja_GS1;
        $art_gs1  = $pallet->producto->CodigoBarraArticulo_GS1;

        $view =  \View::make('print.code_bar', compact('qr_url','code_bar','producto','rnpa','unidades','peso','tipo','caja_gs1','art_gs1'))->render();
        $pdf = \App::make('dompdf.wrapper');
        $pdf->setPaper([0,0,287.37,287.37], 'portrait');
        $pdf->loadHTML($view);

        return $pdf->stream($code_bar.'.pdf');
    }

    private function generateEAN($code){
		$weightflag = true;
		$sum = 0;
		for ($i = strlen($code) - 1; $i >= 0; $i--){
			$sum += (int)$code[$i] * ($weightflag?3:1);
			$weightflag = !$weightflag;
		}
		$code .= (10 - ($sum % 10)) % 10;
		return $code;
	}
}
