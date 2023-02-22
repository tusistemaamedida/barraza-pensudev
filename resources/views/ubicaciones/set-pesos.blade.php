<div class="p-2" id="striped-rows-table">
    <input type="hidden" name="pedido_en_prep_id_set_pesos" id="pedido_en_prep_id_set_pesos" value="{{$pedido_en_prep_id}}">
    <input type="hidden" name="peso_nominal" id="peso_nominal" value="{{$peso_nominal}}">
    <input type="hidden" name="tolerancia" id="tolerancia" value="{{$tolerancia}}">
    <div class="preview">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Artículo</th>
                    <th>CB</th>
                    <th>Peso N.</th>
                    <th>Kgs</th>
                </tr>
            </thead>
            <tbody style="font-size:10px">
                @foreach ($array_piezas as $item)
                    <tr>
                        <td style="padding:5px">
                            {{$item->producto2->Codigo}} - {{$item->producto2->Descripcion}}
                        </td>
                        <td style="padding:5px; width:15px;text-align:center">{{$item->codigo_barras_articulo}}</td>
                        <td style="padding:5px; width:15px;text-align:center;width: 110px">{{$item->producto2->PesoNominal}}</td>
                        <td style="width: 60px">
                            <input type="hidden" name="array_ids[]" value="{{$item->id}}">
                            <input type="number" step="0.5" name="cajaid-{{$item->id}}" id="cajaid-{{$item->id}}" style="width: 100px; height: 25px; float: right;" onkeyup="setTolerancia({{$item->id}})"/><br>
                            <span style="color: red;float:right" id="span-msj-{{$item->id}}"></span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
