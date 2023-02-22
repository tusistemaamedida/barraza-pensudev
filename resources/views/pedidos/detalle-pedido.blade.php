@extends('layouts.app')

@section('css')
@parent
<link href="{{asset('dt/css/jquery.dataTables.min.css')}}" rel="stylesheet">
<link href="{{asset('dt/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
<style>
    div.dataTables_wrapper div.dataTables_length select{
        width: 80px !important;
    }
    .visible{
        display: block;
    }
    .no-visible{
        display: none;
        visibility: hidden;
    }
</style>
@endsection

@section('content')
    <div class="intro-y box mt-5">
        <div class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60">
            <h2 class="font-medium text-base mr-auto">
                Pedidos N°: {{$nroCom}}
            </h2>

            <input type="hidden" name="cod_art" id="cod_art" value="{{$cod_art}}">

            <button type="button" class="btn btn-sm btn-success mr-1 mb-2" id="btn-preparap-pedido" style="float: right; color:white" onclick="verificarPaseAexpedicion()" id="btn-to-expedicion">
                @if($actualiza_exp)
                    <i data-lucide="save" class="block mx-auto" style="height: 15px;"></i> Actualizar
                @else
                    <i data-lucide="shopping-cart" class="block mx-auto" style="height: 15px;"></i> Pasar a Expedición
                @endif
            </button>
        </div>
        <span style="float:right; color:red; font-size:12px;margin: -25px 10px;" id="span-error"></span>
        <div class="p-5" id="striped-rows-table">
            <div class="container mx-auto">
                @for ($i = 0; $i < count($data); $i++)
                    @php
                        $item = $data[$i];
                    @endphp
                    <div class="intro-y">
                        <div class="columns-1 inbox__item inbox__item--active inline-block sm:block text-slate-600 dark:text-slate-500 bg-slate-200 dark:bg-darkmode-400/70 border-b border-slate-200/60 dark:border-darkmode-400"
                        style="border-radius: 50px; margin-bottom: 7px;">
                            <div class="flex px-5 py-3">
                                <div class="w-72 flex-none flex items-center mr-5">
                                    <div class="inbox__item--sender truncate ml-3">
                                        {{$item->producto }}
                                    </div>
                                </div>
                                <div class="w-72 flex-none flex items-center mr-5">
                                    <div class="inbox__item--sender truncate ml-3">
                                        @if($actualiza_exp) Cant. faltante: @else Cant. solicitada: @endif <strong>{{(int)$item->cantidad_solicitada}}</strong>
                                    </div>
                                </div>
                                <div class="inbox__item--time whitespace-nowrap ml-auto pl-10">
                                    @if((int)$item->cantidad_a_descontar == 0)
                                        <span style="color:white;background-color:rgb(196, 88, 88);padding:3px 10px; border-radius:5px;">SIN STOCK</span>
                                    @else
                                        <span style="color:white;background-color:rgb(94, 167, 88);padding:3px 10px; border-radius:5px;">{{(int)$item->cantidad_a_descontar}}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    @if(count($item->ubicaciones))
                        @for ($u = 0; $u < count($item->ubicaciones); $u++)
                            @php
                                $ubicacion = $item->ubicaciones[$u];
                            @endphp
                            <div class="intro-y" style="margin-left:30px;" >
                                <div class="inbox__item inbox__item--active inline-block sm:block text-slate-600 dark:text-slate-500 bg-slate-100 dark:bg-darkmode-400/70 border-b border-slate-200/60 dark:border-darkmode-400"
                                    style="    border-radius: 20px; margin-bottom: 7px;">
                                    <div class="flex px-5 py-3">
                                        <div class="w-72 flex-none flex items-center mr-5">
                                            <div class="inbox__item--sender truncate ml-3">
                                                 {!!$ubicacion['link']!!} <br><br>
                                                 Quitar del pallet:
                                                 <span style="color:white;background-color:rgb(32, 36, 32);padding:3px 10px; border-radius:5px;">
                                                     {{(int)$ubicacion['cant_a_descontar_del_pallet']}}
                                                 </span> &nbsp;
                                                 Cant. del pallet: <strong>{{(int)$ubicacion['cant__del_pallet']}}</strong>
                                            </div>
                                        </div>
                                        <div class="inbox__item--time whitespace-nowrap ml-auto pl-10">
                                            <input type="text"
                                                   class="form-control form-control-sm"
                                                   placeholder="Ingrese código barras"
                                                   name="cod-barras-{{(int)$ubicacion['pedido_en_prep_id']}}"
                                                   id="cod-barras-{{(int)$ubicacion['pedido_en_prep_id']}}"
                                                   onkeyup="add_pallet(
                                                            event,
                                                            {{(int)$ubicacion['cant_a_descontar_del_pallet']}},
                                                            {{(int)$ubicacion['cant__del_pallet']}},
                                                            {{(int)$ubicacion['pedido_en_prep_id']}},
                                                            {{(int)$item->cantidad_solicitada}}
                                                        )">

                                           <div class="mt-3">
                                                <div class="flex flex-col sm:flex-row mt-2">
                                                    @if($ubicacion['muestra_select_all'])
                                                        <div class="form-check mr-2">
                                                            <input id="radio-{{(int)$ubicacion['pedido_en_prep_id']}}"
                                                                   class="form-check-input"
                                                                   type="radio"
                                                                   name="movimiento-{{(int)$ubicacion['pedido_en_prep_id']}}"
                                                                   value="all"
                                                                   @if($ubicacion['muestra_select_all']) checked @endif>
                                                            <label class="form-check-label" for="radio-{{(int)$ubicacion['pedido_en_prep_id']}}">Todo el pallet</label>
                                                        </div>
                                                    @endif
                                                    <div class="form-check mr-2 mt-2 sm:mt-0">
                                                        <input id="radio-{{(int)$ubicacion['pedido_en_prep_id']}}" class="form-check-input" type="radio" name="movimiento-{{(int)$ubicacion['pedido_en_prep_id']}}" value="in">
                                                        <label class="form-check-label" for="radio-{{(int)$ubicacion['pedido_en_prep_id']}}">Quedan en pallet</label>
                                                    </div>
                                                    <div class="form-check mr-2 mt-2 sm:mt-0">
                                                        <input id="radio-{{(int)$ubicacion['pedido_en_prep_id']}}"
                                                               class="form-check-input"
                                                               type="radio"
                                                               name="movimiento-{{(int)$ubicacion['pedido_en_prep_id']}}"
                                                               value="out"
                                                               @if(!$ubicacion['muestra_select_all']) checked @endif>
                                                        <label class="form-check-label" for="radio-{{(int)$ubicacion['pedido_en_prep_id']}}">Salen del pallet</label>
                                                        <label class="form-check-label" id="ocultar-{{(int)$ubicacion['pedido_en_prep_id']}}" onclick="ocultar({{(int)$ubicacion['pedido_en_prep_id']}})"></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="intro-y" style="margin:15px 0px 15px 75px;" >
                                <p class='p-alert' style="margin: 5px ; text-align:right;color:red" id="p-alert-{{(int)$ubicacion['pedido_en_prep_id']}}"></p>
                                <p class="visible" style="margin: 5px ; text-align:right" id="p-{{(int)$ubicacion['pedido_en_prep_id']}}"></p>
                            </div>
                        @endfor
                    @endif
                @endfor
            </div>
        </div>
    </div>

    <div id="modal-detalles-verificacion" class="modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg" >
            <div class="modal-content">
                <div class="modal-body p-0">
                    <div class="p-5 text-center">
                        <i data-lucide="x-circle" class="w-16 h-16 text-danger mx-auto mt-3"></i>
                        <div class="text-3xl mt-5">¿Estos son los datos con los que cierra?</div>
                        <div class="text-slate-500 mt-2" id="insert-view"></div>
                    </div>
                    <div class="px-5 pb-8 text-center">
                        <button type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary w-24 mr-1">Cancelar</button>
                        <button type="button" class="btn btn-danger w-24" onclick="pasarAExpedicion()" id="btn-pasar-a-expedicion">Confirmar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form action="{{route('pasar.a.expedicion')}}" method="POST" id="form-a-expedicion">
        @csrf
    </form>

    <div id="modal-set-pesos" class="modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg" style="width: 700px">
            <div class="modal-content">
                <form action="#" method="post" id="form-set-pesos">
                    @csrf
                    <div class="modal-body p-0">
                        <div class="p-5 text-center">
                            <i data-lucide="check-circle" class="w-10 h-10 text-success mx-auto mt-3"></i>
                            <div class="text-3xl mt-5">Items sin pesos</div>
                            <div class="text-slate-500 mt-2" id="insert-form-caja"></div>
                        </div>
                        <div class="px-5 pb-8 text-center">
                            <button type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary w-24 mr-1">Cerrar</button>
                            <button type="button" data-tw-dismiss="modal" class="btn btn-outline-success w-24 mr-1" onclick="setPesos()">Guardar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('js')
@parent
<script src="{{asset('dt/js/jquery.js')}}"></script>
<script src="{{asset('dt/js/jquery.validate.js')}}"></script>
<script src="{{asset('dt/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('dt/js/bootstrap.min.js')}}"></script>
<script src="{{asset('dt/js/dataTables.bootstrap4.min.js')}}"></script>

<script>
    const myModal = tailwind.Modal.getInstance(document.querySelector("#modal-set-pesos"));

    var table = $('.yajra-datatable').DataTable({
        @include('partials.lenguaje-dt')
    });

    function setTolerancia(id){
        $("#span-msj-"+id).html('');
        var peso_nominal = $("#peso_nominal").val();
        var tolerancia   = $("#tolerancia").val();
        var valor        = $("#cajaid-"+id).val();
        var porc         = (parseInt(tolerancia) * parseFloat(peso_nominal)) / 100;
        var min          = parseFloat(peso_nominal) - porc;
        var max          = parseFloat(peso_nominal) + porc;

        if(valor > min && valor < max){
            return true;
        }else{
            $("#span-msj-"+id).html('Kgrs entre '+min + ' y '+max);
        }
    }

    function setPesos(){
        var pedido_en_prep_id = $("#pedido_en_prep_id_set_pesos").val();
        $.ajax({
            url: "{{route('set.pesos')}}",
            type: 'POST',
            data: $("#form-set-pesos").serialize(),
            beforeSend: function(data) {
                $("#p-alert-"+pedido_en_prep_id).html('Aguarde...');
            },
            success: function(data) {
                myModal.hide();
                $("#p-"+pedido_en_prep_id).html(data['html']);
                if(data['type'] == 'error'){
                    notificacionAlerta(data['msj']);
                    $("#p-alert-"+pedido_en_prep_id).html(data['msj']);
                    if(data['view'] != ''){
                        $("#insert-form-caja").html(data['view'])
                        myModal.show();
                    }
                }else{
                    notificacionExito(data['msj']);
                    $("#p-alert-"+pedido_en_prep_id).html('');
                }
            },
            error: function(data) {
                notificacionAlerta(JSON.stringify(data));
                $("#p-alert-"+pedido_en_prep_id).html('ERROR!');
                myModal.hide();
            },
            complete: function() {
            }
        });
    }

    function add_pallet(event,cant_a_descontar_del_pallet,cant__del_pallet,pedido_en_prep_id,cantidad_solicitada){
        if (event.keyCode == 13) {
            var cod_barras = $("#cod-barras-"+pedido_en_prep_id).val();
            var movimiento = document.querySelector('input[name="movimiento-'+pedido_en_prep_id+'"]:checked').value;
            if(cod_barras.length > 0){
                $.ajax({
                    url: "{{route('set.cod-barras.to.pedido')}}",
                    type: 'GET',
                    data: {
                        cant_a_descontar_del_pallet,
                        cant__del_pallet,
                        pedido_en_prep_id,
                        movimiento,
                        cod_barras,
                        cantidad_solicitada
                    },
                    beforeSend: function(data) {
                        $("#p-alert-"+pedido_en_prep_id).html('Aguarde...');
                    },
                    success: function(data) {
                        $("#p-"+pedido_en_prep_id).html(data['html']);
                        if(data['type'] == 'error'){
                            notificacionAlerta(data['msj']);
                            $("#p-alert-"+pedido_en_prep_id).html(data['msj']);
                            if(data['view'] && data['view'] != ''){
                                $("#insert-form-caja").html(data['view'])
                                myModal.show();
                            }
                        }else{
                            notificacionExito(data['msj']);
                            $("#p-alert-"+pedido_en_prep_id).html('');
                        }
                        $("#cod-barras-"+pedido_en_prep_id).val('');
                        $("#cod-barras-"+pedido_en_prep_id).focus();
                        $("#ocultar-"+pedido_en_prep_id).html('(-)');
                    },
                    error: function(data) {
                        notificacionAlerta(JSON.stringify(data));
                        $("#p-alert-"+pedido_en_prep_id).html('ERROR!');
                        $("#cod-barras-"+pedido_en_prep_id).val('');
                        $("#cod-barras-"+pedido_en_prep_id).focus();
                    },
                    complete: function() {
                    }
                });
            }else{
                notificacionAlerta('Ingrese un código de barras');
            }
        } else {
            return false;
        }
    }

    function clean_alerts(){
        $(".p-alert").html('');
    }

    setInterval('clean_alerts()',10000);

    function quitarArticulo(ped_prep_id,cod_barra){
        var mensaje = confirm("¿Desea quitar el artículo "+cod_barra+"?");

        if (mensaje) {
            $.ajax({
                url: "{{route('quitar.articulo.session')}}",
                type: 'GET',
                data: {pedido_en_prep_id:ped_prep_id,codigo_barras_articulo: cod_barra},
                beforeSend: function(data) {
                    $("#p-alert-"+ped_prep_id).html('Aguarde...');
                },
                success: function(data) {
                    if(data['type'] == 'error'){
                        notificacionAlerta(data['msj']);
                        $("#p-alert-"+ped_prep_id).html(data['msj']);
                    }else{
                        notificacionExito(data['msj']);
                        $("#p-alert-"+ped_prep_id).html('');
                        $("#p-"+ped_prep_id).html(data['html']);
                    }
                },
                error: function(data) {
                    notificacionAlerta(JSON.stringify(data));
                    $("#p-alert-"+ped_prep_id).html('ERROR!');
                },
                complete: function() {
                }
            });
        }
    }

    function quitarCaja(ped_prep_id,cod_barra){
        var mensaje = confirm("¿Desea quitar la caja "+cod_barra+"?");

        if (mensaje) {
            $.ajax({
                url: "{{route('quitar.caja.session')}}",
                type: 'GET',
                data: {pedido_en_prep_id:ped_prep_id,codigo_barras_caja: cod_barra},
                beforeSend: function(data) {
                    $("#p-alert-"+ped_prep_id).html('Aguarde...');
                },
                success: function(data) {
                    if(data['type'] == 'error'){
                        notificacionAlerta(data['msj']);
                        $("#p-alert-"+ped_prep_id).html(data['msj']);
                    }else{
                        notificacionExito(data['msj']);
                        $("#p-alert-"+ped_prep_id).html('');
                        $("#p-"+ped_prep_id).html(data['html']);
                    }
                },
                error: function(data) {
                    notificacionAlerta(JSON.stringify(data));
                    $("#p-alert-"+ped_prep_id).html('ERROR!');
                },
                complete: function() {
                }
            });
        }
    }

    function ocultar(id){
        var className = $('#p-'+id)[0].className;
        if(className == 'visible'){
            $("#ocultar-"+id).html('(+)');
            $('#p-'+id).removeClass('visible');
            $('#p-'+id).addClass('no-visible');
        }else{
            $("#ocultar-"+id).html('(-)');
            $('#p-'+id).removeClass('no-visible');
            $('#p-'+id).addClass('visible');
        }
    }

    function verificarPaseAexpedicion(){
        const myModal = tailwind.Modal.getInstance(document.querySelector("#modal-detalles-verificacion"));
        $("#btn-to-expedicion").attr('disabled',true);
        var cod_art = $("#cod_art").val();
        $.ajax({
            url: "{{route('verificar.pase.a.aexpedicion')}}",
            data:{cod_art},
            type: 'GET',
            beforeSend: function(data) {
                $("#span-error").html('Aguarde...');
            },
            success: function(data) {
                if(data['type'] == 'error'){
                    notificacionAlerta(data['msj']);
                     $("#span-error").html(data['msj']);
                }else{
                    $("#insert-view").html(data['view']);
                    notificacionExito('Datos de los pedidos que pasan a expedición');
                    $("#span-error").html('');
                    myModal.show();
                }
            },
            error: function(data) {
                notificacionAlerta(JSON.stringify(data));
                 $("#span-error").html('ERROR!');
            },
            complete: function() {
                $("#btn-to-expedicion").attr('disabled',false);
            }
        });
    }

    function pasarAExpedicion(){
        const myModal = tailwind.Modal.getInstance(document.querySelector("#modal-detalles-verificacion"));
        $("#btn-pasar-a-expedicion").attr('disabled',true);
        $.ajax({
            url: "{{route('pasar.a.expedicion')}}",
            type: 'POST',
            data: $("#form-a-expedicion").serialize(),
            beforeSend: function(data) {
                show_spinner()
            },
            success: function(data) {
                if(data['type'] == 'error'){
                    notificacionAlerta(data['msj']);
                     $("#span-error").html(data['msj']);
                }else{
                    notificacionExito(data['msj']);
                    let url = "{{route('pedidos')}}";
                    window.location = url;
                }
                hide_spinner();
            },
            error: function(data) {
                notificacionAlerta(JSON.stringify(data));
                hide_spinner();
                myModal.hide();
            },
            complete: function() {
                $("#btn-pasar-a-expedicion").attr('disabled',false);
            }
        });
    }
</script>
@endsection
