@extends('layouts.app')

@section('css')
@parent
@endsection

@section('content')
<div class="intro-y box mt-5">
    <div class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60">
        <h2 class="font-medium text-base mr-auto">
            Configuraciones del sistema
        </h2>
    </div>
    <div class="p-5" id="striped-rows-table">
        <div class="preview">
            <ul class="nav nav-tabs" role="tablist">
                <li id="example-1-tab" class="nav-item flex-1" role="presentation">
                    <button class="nav-link w-full py-2 active" data-tw-toggle="pill" data-tw-target="#example-tab-1" type="button" role="tab" aria-controls="example-tab-1" aria-selected="true">
                        Crear ubicaciones
                    </button>
                </li>
                <li id="example-2-tab" class="nav-item flex-1" role="presentation">
                    <button class="nav-link w-full py-2" data-tw-toggle="pill" data-tw-target="#example-tab-2" type="button" role="tab" aria-controls="example-tab-2" aria-selected="false">
                        Estados de Pallets
                    </button>
                </li>
            </ul>
            <div class="tab-content border-l border-r border-b">
                <div id="example-tab-1" class="tab-pane leading-relaxed p-5 active" role="tabpanel" aria-labelledby="example-1-tab">
                    <form action="{{route('create.ubicaciones')}}" method="POST" id="form-nueva-ubicacion">
                        @csrf
                        <div class="mt-3">
                            <div class="grid grid-cols-12 gap-2">
                                <label for="camara" class="form-label col-span-3">Nombre de cámara</label>
                                <label for="documento" class="form-label col-span-3">Calles</label>
                                <label for="telefono" class="form-label col-span-3">Niveles</label>
                                <label for="telefono" class="form-label col-span-3">Posiciones</label>
                            </div>
                            <div class="grid grid-cols-12 gap-2">
                                <input id="camara" type="text" name="camara" class="form-control col-span-3" required>
                                <input id="calles" type="number" min="0" name="calles" class="form-control col-span-3" required>
                                <input id="niveles" type="number" min="0" name="niveles" class="form-control col-span-3" required>
                                <input id="posiciones" type="number" min="0" name="posiciones" class="form-control col-span-3" required>
                            </div>
                        </div>
                        <div class="mt-3" >
                            <button type="button" class="btn btn-primary w-20" id="btn-crear-ubicacion" >
                                <i class="fa fa-save" style="margin-top: 3px;padding-right: 5px;"></i> Guardar
                            </button>
                        </div>
                    </form>
                </div>
                <div id="example-tab-2" class="tab-pane leading-relaxed p-5" role="tabpanel" aria-labelledby="example-2-tab">
                    <div class="overflow-x-auto">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th class="whitespace-nowrap">#</th>
                                    <th class="whitespace-nowrap">Nombre</th>
                                    <th class="whitespace-nowrap">Color</th>
                                    <th class="whitespace-nowrap">Defecto</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($estados as $estado)
                                    <tr>
                                        <td>{{$estado->id}}</td>
                                        <td>{{$estado->nombre}}</td>
                                        <td>
                                            <input type="color" name="color-{{$estado->id}}" id="color-{{$estado->id}}" value="{{$estado->color}}" onchange="setColor({{$estado->id}})">
                                        </td>
                                        <td>
                                            <input type="radio" name="default" id="default-{{$estado->id}}" @if($estado->default) checked @endif value="{{$estado->id}}" onchange="setDefault({{$estado->id}})">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
    @parent
    <script src="{{asset('dt/js/jquery.js')}}"></script>
    <script type="text/javascript">

        $("#btn-crear-ubicacion").click(function(){
            document.getElementById('btn-crear-ubicacion').disabled = true;
            $.ajax({
                url: "{{route('create.ubicaciones')}}",
                type: 'POST',
                data: $("#form-nueva-ubicacion").serialize(),
                beforeSend: function(data) {
                    show_spinner()
                },
                success: function(data) {
                    if(data['type'] == 'error'){
                        notificacionAlerta(data['msj']);
                        hide_spinner()
                    }else{
                        notificacionExito(data['msj']);
                        $('#form-nueva-ubicacion')[0].reset();
                        hide_spinner();
                        document.getElementById('btn-crear-ubicacion').disabled = false;
                    }
                },
                error: function(data) {
                    notificacionAlerta(JSON.stringify(data));
                    hide_spinner()
                },
                complete: function() {
                    document.getElementById('btn-crear-ubicacion').disabled = false;
                }
            });
        })

        function setColor(id){
            var color = $("#color-"+id).val();
            $.ajax({
                url: "{{route('set.color.estado')}}",
                type: 'GET',
                data: {color,id},
                beforeSend: function(data) {
                    show_spinner()
                },
                success: function(data) {
                    if(data['type'] == 'error'){
                        notificacionAlerta(data['msj']);
                        hide_spinner()
                    }else{
                        notificacionExito(data['msj']);
                        hide_spinner();
                    }
                },
                error: function(data) {
                    notificacionAlerta(JSON.stringify(data));
                    hide_spinner()
                },
                complete: function() {
                }
            });
        }

        function setDefault(id){
            var valor = $("#default-"+id).val();
            $.ajax({
                url: "{{route('set.default.estado')}}",
                type: 'GET',
                data: {valor,id},
                beforeSend: function(data) {
                    show_spinner()
                },
                success: function(data) {
                    if(data['type'] == 'error'){
                        notificacionAlerta(data['msj']);
                        hide_spinner()
                    }else{
                        notificacionExito(data['msj']);
                        hide_spinner();
                    }
                },
                error: function(data) {
                    notificacionAlerta(JSON.stringify(data));
                    hide_spinner()
                },
                complete: function() {
                }
            });
        }

    </script>
@endsection

