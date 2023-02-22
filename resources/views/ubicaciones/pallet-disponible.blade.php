<div class="box" id="card-ubicacion-{{$ubicacion->id}}"
    onclick="ubicar({{$ubicacion->id}},{{$ubicacion->profundidad->nombre}},{{$profundidades}})"
    style="width: {{100/$profundidades}}%; margin-right:4px; height:75px; background-color: darkgray;cursor:pointer">
    <div class="flex text-slate-500 truncate text-xs mt-0.5" style="float: right; padding: 7px">
        <span class="text-primary text-xs inline-block truncate">
            {{$ubicacion->profundidad->nombre}}
        </span>
    </div>
    <div class="flex items-center border-slate-200/60 dark:border-darkmode-400 px-1 py-1" style="height: 100%">
        <div class="ml-1 mr-auto">
            <span class="font-medium" style="color:white">Disponible</span>
        </div>
    </div>
</div>
