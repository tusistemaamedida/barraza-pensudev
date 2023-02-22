
<p style="width: 100%;text-align:center;background-color:beige;padding:5px; font-size:11px; font-weight:bold;border-radius:15px;">POSICIONES</p>



<div class=""  style="height: 450px;
width: 20px;
position: absolute;
background-color: #99b9d9;
border-radius: 7px;
margin-left: -15px">
    <h1 style="padding: 0px 5px;margin-top: 130px; text-align:center;color:white; font-size:11px; font-weight:bold;">
        <span style="display: block; "> N </span>
        <span style="display: block; "> I </span>
        <span style="display: block; "> V </span>
        <span style="display: block; "> E </span>
        <span style="display: block; "> L </span>
        <span style="display: block; "> E </span>
        <span style="display: block; "> S </span>
    </h1>
</div>

@for ($i = 0; $i < count($ubicaciones); $i++)
    @if ($i%$profundidades == 0 || $i == 0)
        @php
            $count_p = 1;
        @endphp
        <div class="intro-y flex flex-col sm:flex-row" style="margin-top: 15px;margin-left:15px;" >
    @endif
        <div style="display: contents;" id="limpiar-card-ubicacion-{{$ubicaciones[$i]->id}}">
            <div class="box" id="card-ubicacion-{{$ubicaciones[$i]->id}}"
                @if(is_null($ubicaciones[$i]->pallet))
                    onclick="ubicar({{$ubicaciones[$i]->id}},{{$ubicaciones[$i]->profundidad->nombre}},{{$profundidades}})"
                @else
                    onclick="getDetalles({{$ubicaciones[$i]->id}})"
                @endif
                style="width: {{100/$profundidades}}%; margin-right:4px; height:75px;
                @if(is_null($ubicaciones[$i]->pallet)) background-color: darkgray; @else background-color: {{$ubicaciones[$i]->estado->color}}; @endif cursor:pointer">
                <div class="flex text-slate-500 truncate text-xs mt-0.5" style="float: right; padding: 7px">
                    <span class="text-primary text-xs inline-block truncate">
                        {{$ubicaciones[$i]->profundidad->nombre}}
                    </span>
                </div>
                <div class="flex items-center border-slate-200/60 dark:border-darkmode-400 px-1 py-1" style="height: 100%">
                    <div class="ml-1 mr-auto">
                        @if(is_null($ubicaciones[$i]->pallet))
                            <span class="font-medium" style="color:white">Disponible</span>
                        @else
                            <span class="font-medium">{{$ubicaciones[$i]->pallet}}</span><br>
                            <span style="font-size: 9px">{{$ubicaciones[$i]->s_pallet[0]->codigo}} - {{$ubicaciones[$i]->s_pallet[0]->nombre}} @if(!is_null($ubicaciones[$i]->pallet)) - {{$ubicaciones[$i]->estado->nombre}} @endif</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @if ($count_p == $profundidades)
        </div>
    @else
        @php
            $count_p++;
        @endphp
    @endif
@endfor
