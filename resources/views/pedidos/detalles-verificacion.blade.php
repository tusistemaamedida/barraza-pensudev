<div class="p-5" id="striped-rows-table">
    <div class="preview">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Artículo</th>
                    <th>Salen</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody style="font-size:10px">
                @foreach ($pedidos_en_preparacion as $item)
                    <tr>
                        <td style="padding:5px">{{$item->codigo_articulo}} - {{$item->articulo}}<br>{{$item->ubicacion}}</td>
                        <td style="padding:5px; width:15px;text-align:center">{{(int)$item->cant_de_piezas_en_session}} / {{(int)$item->cantidad_a_descontar}}</td>
                        <td style="padding:5px; width:15px;text-align:center">
                            @if(((int)$item->cant_de_piezas_en_session < (int)$item->cantidad_a_descontar))
                                <div class="text-danger">
                                    Faltan {{(int)$item->cantidad_a_descontar - (int)$item->cant_de_piezas_en_session}}
                                </div>
                            @endif

                            @if(((int)$item->cant_de_piezas_en_session > (int)$item->cantidad_a_descontar))
                                <div class="text-danger">
                                    Sobran {{(int)$item->cant_de_piezas_en_session - (int)$item->cantidad_a_descontar}}
                                </div>
                            @endif

                            @if(((int)$item->cant_de_piezas_en_session == (int)$item->cantidad_a_descontar))
                                <div class="text-success" title="Cantidad correcta">
                                    Ok
                                </div>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
