<div class="p-5" id="striped-rows-table">
    <div class="preview">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Artículo</th>
                    <th>Lote</th>
                    <th>Kgs</th>
                    <th>Cantidad</th>
                </tr>
            </thead>
            <tbody style="font-size:12px">
                @foreach ($items as $item)
                    <tr>
                        <td style="padding:5px">
                            @if(isset($item->producto))
                                {{$item->producto->Codigo}} - {{$item->producto->Descripcion}}
                            @else
                                COD: {{$item->IdArti}}
                            @endif
                        </td>
                        <td style="padding:5px; width:15px;text-align:center">{{$item->NroLote}}</td>
                        <td style="padding:5px; width:15px;text-align:center">{{$item->Cantid}}</td>
                        <td style="padding:5px; width:15px;text-align:center">{{$item->CanUni}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
