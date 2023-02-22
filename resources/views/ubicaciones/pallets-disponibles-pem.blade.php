@if(count($pallets))
    <div style="width: 100%; text-align:center; margin-bottom:5px;">
        {!! $pallets->appends(Request::only(['articulo_id']))->render() !!}
    </div>
    <div class="scrollbar-medium" style=" height:400px; overflow-y: scroll; overflow-x: hidden">
        @foreach ($pallets as $pallet)
            <div class="intro-y">
                <div class="box px-2 py-2 mb-1 flex items-center zoom-in" onclick="setCodigoBarras('{{$pallet->codigo_barras}}')">
                    <div class="ml-4 mr-auto">
                        <div class="font-medium">{{$pallet->codigo_barras}}</div>
                        <div class="text-slate-500 text-xs mt-0.5">{{$pallet->codigo}} - {{$pallet->nombre}}</div>
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
