
<div class="grid grid-cols-12 gap-6 mt-5">
    <div class="intro-y col-span-12">
        <div class="intro-y box">
            <div class="p-5">
                @if($historial)
                    <ol class="border-l border-gray-300">
                        @foreach ($historial as $item)
                            <li>
                                <div class="flex flex-start items-center pt-3">
                                    <div class="bg-gray-300 w-2 h-2 rounded-full -ml-1 mr-3"></div>
                                    <p class="text-gray-500 text-sm">
                                        {{\Carbon\Carbon::parse($item->fecha)->format('d/m/Y')}}  {{\Carbon\Carbon::parse($item->hora)->format('H:i')}}
                                    </p>
                                </div>
                                <div class="mt-0.5 ml-4 mb-6">
                                    <span class="py-1 px-2 rounded-full text-xs bg-primary text-white text-center font-medium"> {{$item->CodBarraPallet_Int}} </span><br>
                                    {!!$item->descripcion!!}
                                    <h6 class="text-gray-400 font-semibold text-xl mb-1.5" style="font-size:14px">Por: {{$item->user->nombre}}</h6>
                                </div>
                            </li>
                        @endforeach
                    </ol>
                @else
                    <p>NO SE ENCONTRARON RESULTADOS</p>
                @endif
            </div>
        </div>
    </div>
</div>
