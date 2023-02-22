@extends('layouts.app')

@section('css')
@parent
<style>
    .btn-ant{
        float:left;
        border-color:green;
        cursor: pointer;
    }
    .btn-ant:hover{
        background-color: rgb(189, 241, 140)
    }

    .btn-sig{
        float:right;
        border-color:green;
        cursor: pointer;
    }
    .btn-sig:hover{
        background-color: rgb(189, 241, 140)
    }
    .tom-select .ts-dropdown{
        background-color: azure
    }
</style>
@endsection

@section('content')
<div class="grid grid-cols-12 gap-6 mt-5">
    <div class="intro-y col-span-12">
        <div class="intro-y box">
            <div class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                <h2 class="font-medium text-base mr-auto">
                    Ubicaciones
                </h2>
            </div>
            <div id="inline-form" class="p-5">
                <div class="preview">
                    <div class="grid grid-cols-12 gap-2">
                        <input type="text" class="form-control col-span-4" onkeyup="buscar_pallet(event)" autofocus
                               placeholder="Ingresar código de barras" aria-label="default input inline 3" id="in-pallet">
                        <select data-placeholder="Depósitos" class="w-full col-span-3" id="deposito">
                            <option>Selecione un depósito</option>
                            @foreach ($depositos as $dep)
                                <option value="{{$dep->id}}">{{$dep->nombre}}</option>
                            @endforeach
                        </select>
                        <select data-placeholder="Calles" class=" w-full col-span-3" id="calle" disabled></select>
                        <p class="col-span-12" id="p_error" style="color: red;font-size:11px"></p>
                        <p class="col-span-12" id="p_info" style="width: 100%"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="w-full mt-3 xl:mt-0 flex-1" style="margin-top:15px">
    <div class="sm:grid grid-cols-4 gap-4"  >
        <div>
            <div class="grid grid-rows-3 grid-flow-col gap-2">
                <div style="width: 100%; margin-bottom:5px;">
                    <select data-placeholder="Buscar en envasados" class="tom-select" name="articulo_id" id="articulo_select">
                        <option value="ENV" selected>Todos Envasados</option>
                        <option value="PA" >Palet Armados</option>
                        <option value="PEM" >Palet Envasados Manual</option>
                        @foreach ($articulos_envasados as $art)
                            <option value="{{$art->Id}}">
                                {{$art->Codigo}} - {{$art->Descripcion}}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div id="pallets-disponibles"></div>
            </div>
        </div>
        <div class="col-span-3" id="ubicaciones-insert" ></div>
    </div>
</div>

<div id="modal-ubicar" class="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body p-0">
                <div class="p-5 text-center"> <i data-lucide="package" class="w-16 h-16 text-danger mx-auto mt-3"></i>
                    <div class="text-3xl mt-5" >Ubicar Pallet</div>
                    <div class="text-slate-500 mt-2" id="titulo-modal"></div>
                </div>
                <div class="px-5 pb-8 text-center">
                    <button type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary w-24 mr-1">Cancelar</button>
                    <button type="button" class="btn btn-danger w-24" id="btn-ubicar">Ubicar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="modal-detalles" class="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body p-0">
                <div class="p-5 text-center">
                    <i data-lucide="package" class="w-16 h-16 text-success mx-auto mt-3"></i>
                </div>

                <div style="padding: 0px 25px;">
                    <div class="text-slate-500 mt-2" id="detalles"></div>
                </div>
                <div class="px-5 pb-8 text-center">
                    <button type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary w-24 mr-1">Cancelar</button>
                    <button type="button" class="btn btn-danger w-24" id="btn-mover">Mover</button>
                    <button type="button" class="btn btn-success w-24" id="btn-cambiar-estado">Guardar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<form action="" method="POST" id="form-send-ubicacion">
    @csrf
    <input type="hidden" id="ubicacion-id-seleccionada" name="ubicacion-id-seleccionada" value="">
    <input type="hidden" id="pallet-seleccionado" name="pallet-seleccionado" value="">
    <input type="hidden" id="pallet-profundidades" name="pallet-profundidades" value="">
    <input type="hidden" id="ubicacion_id_a_mover" name="ubicacion_id_a_mover" value="">
</form>

@endsection

@section('js')
    @parent
    <script src="{{asset('dt/js/jquery.js')}}"></script>
    <script>
        const modal_ubicar = tailwind.Modal.getInstance(document.querySelector("#modal-ubicar"));
        const modal_detalles = tailwind.Modal.getInstance(document.querySelector("#modal-detalles"));
        $("#deposito").change(function(){
            var dep_id = $("#deposito").val();
            $.ajax({
                url: "{{route('get.calles')}}",
                type: 'GET',
                data: {dep_id},
                beforeSend: function() {
                },
                success: function(data) {
                    $('#calle').html('');
                    $("#calle").append('<option >Selecione una calle</option>');
                    for(var i = 0; i < data['calles'].length; i++){
                        $("#calle").append('<option value="' + data['calles'][i].calle.id + '">' + data['calles'][i].calle.nombre + '</option>');
                    }
                    document.getElementById('calle').disabled = false;
                    $("#calle").focus();
                },
                error: function(data) {},
                complete: function() {
                }
            });
        })

        $("#calle").change(function(){
            getUbicaciones();
        })

        function getUbicaciones(){
            var dep_id = $("#deposito").val();
            var calle_id = $("#calle").val();
            $.ajax({
                url: "{{route('get.ubicaciones')}}",
                type: 'GET',
                data: {dep_id,calle_id},
                beforeSend: function(data) {
                    show_spinner()
                },
                success: function(data) {
                    $("#ubicaciones-insert").html('');
                    $("#ubicaciones-insert").html(data['html']);
                },
                error: function(data) {},
                complete: function() {
                    hide_spinner()
                }
            });
        }

        function buscar_pallet(event){
            $("#p_info").html('');
            $("#p_error").html('');
            if (event.keyCode == 13) {
                loadInfo();
            } else {
                return false;
            }
        }

        function loadInfo(showspinner = true, validaposicion = true){
            var codigo = $("#in-pallet").val();
            if(codigo.length === 0){
                notificacionAlerta('Ingrese algún código de barras');
                $("#p_error").html('Ingrese algún código de barras')
            }else{
                $.ajax({
                    url: "{{route('get.pallet')}}",
                    type: 'GET',
                    data: {codigo,validaposicion},
                    beforeSend: function(data) {
                        if(showspinner) show_spinner()
                    },
                    success: function(data) {
                        if(data['type'] == 'error'){
                            $("#p_error").html(data['msj'])
                            notificacionAlerta(data['msj']);
                        }else{
                            $("#p_info").html(data['msj']);
                            document.getElementById('deposito').disabled = false;
                            $("#deposito").focus();
                        }
                    },
                    error: function(data) {},
                    complete: function() {
                        if(showspinner)  hide_spinner()
                    }
                });
            }
        }

        function ubicar(u_id,u_nombre,profundidades){
            $("#ubicacion-id-seleccionada").val('');
            $("#pallet-seleccionado").val('');
            $("#ubicacion-id-seleccionada").val(u_id);
            var codigo_barra_pallet = $("#in-pallet").val();
            $("#pallet-seleccionado").val(codigo_barra_pallet);
            $("#pallet-profundidades").val(profundidades);
            $("#titulo-modal").html('¿Ubicar el pallet: '+codigo_barra_pallet+' en la posición '+u_nombre+'?')
            modal_ubicar.show();
        }

        $("#btn-ubicar").click(function(){
            var u_id         = $("#ubicacion-id-seleccionada").val();
            var u_id_a_mover = $("#ubicacion_id_a_mover").val();
            document.getElementById('btn-ubicar').disabled = true;
            $.ajax({
                url: "{{route('ubicar.pallet')}}",
                type: 'POST',
                data: $("#form-send-ubicacion").serialize(),
                beforeSend: function(data) {
                    show_spinner()
                },
                success: function(data) {
                    if(data['type'] == 'error'){
                        notificacionAlerta(data['msj']);
                        hide_spinner()
                    }else{
                        console.log(u_id)
                        $("#limpiar-card-ubicacion-"+u_id).html('');
                        $("#limpiar-card-ubicacion-"+u_id).html(data['html']);
                        if(data['html_disponible'] != ''){
                            $("#limpiar-card-ubicacion-"+u_id_a_mover).html('');
                            $("#limpiar-card-ubicacion-"+u_id_a_mover).html(data['html_disponible']);
                        }
                        $('#form-send-ubicacion')[0].reset();
                        $("#in-pallet").val('')
                        $("#p_info").html('');
                        $("#p_error").html('');
                        $("#ubicacion_id_a_mover").val('');
                        hide_spinner();
                        document.getElementById('btn-ubicar').disabled = false;
                        modal_ubicar.hide();
                    }
                },
                error: function(data) {
                    notificacionAlerta(JSON.stringify(data));
                    hide_spinner()
                },
                complete: function() {
                    document.getElementById('btn-ubicar').disabled = false;
                    modal_ubicar.hide();
                    document.getElementById("form-send-ubicacion").reset();
                    getPalletsDispponibles()
                }
            });
        })

        $( document ).ready(function() {
            getPalletsDispponibles();
        });

        $("#articulo_select").change(function(){
            getPalletsDispponibles();
        })

        $(document).on('click','.pagination a',function(e){
            e.preventDefault();
            var link = $(this);
            var page = $(this).attr('href').split('page=')[1];
            var url =  $(this).attr('href').split('page=')[0];
            var route = url+'page='+page;
            $.ajax({
                url: route,
                data: {page: page},
                type: 'GET',
                beforeSend: function(data) {
                    show_spinner()
                },
                success: function(data) {
                    $("#pallets-disponibles").html('');
                    $("#pallets-disponibles").html(data['html']);
                },
                error: function(data) {},
                complete: function() {
                    hide_spinner()
                }
            });
        });

        function getPalletsDispponibles(){
            let articulo_id = $("#articulo_select").val();
            $.ajax({
                url: "{{route('get.pallet.pendientes')}}",
                type: 'GET',
                data:{articulo_id},
                beforeSend: function(data) {
                    show_spinner()
                },
                success: function(data) {
                    $("#pallets-disponibles").html('');
                    $("#pallets-disponibles").html(data['html']);
                },
                error: function(data) {},
                complete: function() {
                    hide_spinner()
                }
            });
        }

        function setCodigoBarras(codigo_barras){
            var el = document.getElementById('in-pallet');
            el.value = codigo_barras
            loadInfo()
        }

        function getDetalles(u_id){
            $.ajax({
                url: "{{route('get.pallet.detalles')}}",
                type: 'GET',
                data: {u_id},
                beforeSend: function(data) {
                    show_spinner()
                },
                success: function(data) {
                    $("#detalles").html(data['html'])
                     modal_detalles.show();
                },
                error: function(data) {},
                complete: function() {
                    hide_spinner()
                }
            });
        }

        $("#btn-cambiar-estado").click(function(){
            document.getElementById('btn-cambiar-estado').disabled = true;
            $.ajax({
                url: "{{route('cambiar.estado.pallet')}}",
                type: 'POST',
                data: $("#form-cambiar-estado").serialize(),
                beforeSend: function(data) {
                    show_spinner()
                },
                success: function(data) {
                    if(data['type'] == 'error'){
                        notificacionAlerta(data['msj']);
                    }else{
                        document.getElementById('btn-cambiar-estado').disabled = false;
                        modal_detalles.hide();
                        $('#form-cambiar-estado')[0].reset();
                        hide_spinner()
                        setTimeout(() => {
                            getUbicaciones();
                        }, 200);
                    }
                },
                error: function(data) {
                    notificacionAlerta(JSON.stringify(data));
                },
                complete: function() {
                    hide_spinner()
                    document.getElementById('btn-cambiar-estado').disabled = false;
                    modal_detalles.hide();
                }
            });
        });

        $("#btn-mover").click(function(){
            $("#p_info").html('');
            $("#p_error").html('');
            const showspinner = false;
            const validaposicion = false;
            var codigo_barras = $("#codigo_barra_palet_mover").val();
            var ubicacion_id_mover  = $("#ubicacion_id_mover").val();
            var el = document.getElementById('in-pallet');
            el.value = codigo_barras;
            modal_detalles.hide();
            $('#form-cambiar-estado')[0].reset();
            const card = document.getElementById('card-ubicacion-'+ubicacion_id_mover);
            card.style.backgroundColor = '#2196f3';
            card.style.color = 'white';
            $("#ubicacion_id_a_mover").val(ubicacion_id_mover);
            $("#p_error").html('Esta por mover el pallet seleccionado');
            //loadInfo(showspinner,validaposicion);
        })
    </script>
@endsection
