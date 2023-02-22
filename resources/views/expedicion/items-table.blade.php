<div class="preview">
    <table class="table table-striped ">
        <thead>
            <tr>
                <th>Comprobante</th>
                <th >Articulo</th>
                <th >Caja</th>
                <th >Lote</th>
                <th >Piezas</th>
                <th >Peso</th>
                <th >Peso R</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @php
                $t_cajas = $t_piezas = $t_peso = $t_peso_real  = 0;
            @endphp
            @foreach ($items as $item)
                @php
                    $t_cajas ++;
                    $t_piezas += $item->piezas;
                    $t_peso += $item->peso;
                    $t_peso_real += $item->peso_real;
                @endphp
                <tr>
                    <td>
                        {{$item->comprobante}}
                    </td>
                    <td>
                        {{$item->codigo_articulo}} - {{$item->articulo}}
                    </td>
                    <td>{{$item->codigo_barras_caja}}</td>
                    <td>{{$item->lote}}</td>
                    <td>{{$item->piezas}}</td>
                    <td>{{$item->peso}}</td>
                    <td>{{$item->peso_real}}</td>
                    <td class="table-report__action w-56">
                        <div class="flex justify-center items-center">
                            <a class="flex items-center text-danger" href="javascript:;" onclick="modalDelete('{{$item->codigo_barras_caja}}')">
                                <i class="fa fa-trash" class="w-4 h-4 mr-1" style="padding-right: 5px"></i> Eliminar
                            </a>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>

        <tfoot>
            <tr>
                <th>TOTALES</th>
                <th ></th>
                <th >{{$t_cajas}} Cajas</th>
                <th ></th>
                <th >{{$t_piezas}} U</th>
                <th >{{$t_peso}} Kgs</th>
                <th >{{$t_peso_real}} Kgs</th>
                <th></th>
            </tr>
        </tfoot>
    </table>
</div>
