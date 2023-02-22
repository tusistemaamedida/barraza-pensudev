
<strong>Código Barras:</strong> <span style="float: right"> {{$ubicacion->pallet}} </span><hr style="margin: 5px 0px;">
@foreach ($ubicacion->s_pallet as $p)
    <strong>Producto:</strong> <span style="float: right;color:green"> {{$p->nombre}}</span><br>
    <strong>Lote:</strong> {{$p->lote}}
    <span style="float: right"><strong> </strong> {{(int)$p->piezas}} Uni.<strong></span><hr style="margin: 5px 0px;">
@endforeach
<strong>F.Elab:</strong> <span style="float: right">
@if(isset($ubicacion->s_pallet[0]->fecha_elaboracion)){{\Carbon\Carbon::parse($ubicacion->s_pallet[0]->fecha_elaboracion)->format('d/m/Y')}} @else '--' @endif
</span><br>
<strong>F.Disp:</strong> <span style="float: right">
@if(isset($ubicacion->s_pallet[0]->fecha_vencimiento)){{\Carbon\Carbon::parse($ubicacion->s_pallet[0]->fecha_vencimiento)->format('d/m/Y')}} @else '--' @endif
</span><br>
<strong>F.Ubicación:</strong> <span style="float: right"> {{$ubicacion->fecha}}</span><br>

<strong>Total Unidades:</strong> <span style="float: right"> {{(int) $ubicacion->piezas_total}} </span><br>
<strong>Cajas:</strong> <span style="float: right"> {{(int) $ubicacion->cajas}} </span><br>
<strong>Total Peso:</strong> <span style="float: right"> {{(int) $ubicacion->peso_total}} Kg.</span><br>
<strong>Total Peso Real:</strong> <span style="float: right"> {{(int) $ubicacion->peso_real_total}} Kg.</span><br><br>

@if(!$no_mostrar_estado)
    <div class="col-span-12 sm:col-span-12">
        <form action="" method="POST" id="form-cambiar-estado">
            @csrf
            <input type="hidden" name="codigo_barra_palet" id="codigo_barra_palet_mover" value="{{$ubicacion->pallet}}">
            <input type="hidden" name="ubicacion_id" id="ubicacion_id_mover" value="{{$ubicacion->id}}">
            <select id="estado" name="estado" class="form-select">
                <option value="">Cambiar estado</option>
                @foreach ($estados as $estado)
                    <option @if($ubicacion->estado_id == $estado->id) selected @endif value="{{$estado->id}}">{{$estado->nombre}}</option>
                @endforeach
            </select><br>
        </form><br>
    </div>
@endif
