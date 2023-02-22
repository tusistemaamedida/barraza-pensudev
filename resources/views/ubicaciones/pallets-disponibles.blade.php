@if(count($pallets))
    <div style="width: 100%; text-align:center; margin-bottom:5px;">
        {!! $pallets->appends(Request::only(['articulo_id']))->render() !!}
    </div>
    <div class="scrollbar-medium" style=" height:400px; overflow-y: scroll; overflow-x: hidden">
        @foreach ($pallets as $pallet)
            <div class="intro-y">
                <div class="box px-2 py-2 mb-1 flex items-center zoom-in" onclick="setCodigoBarras('{{$pallet->CodBarraPallet_Int}}')">
                    <div class="ml-4 mr-auto">
                        <div class="font-medium">{{$pallet->CodBarraPallet_Int}}</div>
                        <div class="text-slate-500 text-xs mt-0.5">{{$pallet->producto->Codigo}} - {{$pallet->producto->Descripcion}}</div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="scrollbar-medium" style=" height:400px; overflow-y: scroll; overflow-x: hidden">
        <p>NO SE ENCONTRARON RESULTADOS</p>
    </div>
@endif
