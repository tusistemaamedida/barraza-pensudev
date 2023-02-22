<div class="preview">
    <table class="table table-striped ">
        <thead>
            <tr>
                <th>COD</th>
                <th>Articulo</th>
                <th>Lote</th>
                <th>F.Elab.</th>
                <th>F.Disp.</th>
                <th>CB Palet</th>
                <th>CB Caja</th>
                <th>CB Art</th>
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
                        {{$item->codigo}}
                    </td>
                    <td>
                        {{$item->codigo}} - {{$item->nombre}}
                    </td>
                    <td>{{$item->lote}}</td>
                    <td>{{\Carbon\Carbon::parse($item->fecha_elaboracion)->format('d/m/Y')}}</td>
                    <td>{{\Carbon\Carbon::parse($item->fecha_vencimiento)->format('d/m/Y')}}</td>
                    <td>
                        <a href="{{route('generate.bar_code',['code_bar' => $item->codigo_barras_pallet , 'tipo'=>'P' ])}}" target="_blank">{{$item->codigo_barras_pallet}}</a>
                    </td>
                    <td>
                        <a href="{{route('generate.bar_code',['code_bar' => $item->codigo_barras_caja , 'tipo'=>'C' ])}}" target="_blank">{{$item->codigo_barras_caja}}</a>
                    </td>
                    <td>{{$item->codigo_barras_articulo}}</td>
                    <td class="table-report__action w-56">
                        <div class="flex justify-center items-center">
                            <a class="flex items-center text-danger" href="javascript:;" onclick="modalDelete('{{$item->codigo_barras_articulo}}')">
                                <i class="fa fa-trash" class="w-4 h-4 mr-1" style="padding-right: 5px"></i> Eliminar
                            </a>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
