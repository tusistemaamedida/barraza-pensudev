@extends('layouts.app')

@section('css')
@parent
@endsection

@section('content')
    <div class="intro-y box mt-5" style="    min-height: 400px;">
        <div class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60">
            <div class="w-full mt-3 xl:mt-0 flex-1">
                <div class="sm:grid grid-cols-2 gap-2">
                    <select data-placeholder="Seleccione por NP o cliente" class="tom-select" name="nro_comp" id="nro_comp">
                        @foreach ($pedidos as $ped)
                            <option value="{{$ped->NroCom}}">
                                {{$ped->IdTipo}}{{$ped->NroCom}} -
                                @if(isset($ped->cliente)) {{$ped->cliente->RazSoc}} @endif -
                                @if(isset($ped->cliente) && isset($ped->sucursal) && !is_null($ped->sucursal->Nombre))
                                    {{$ped->sucursal->Nombre}}
                                @elseif(isset($ped->cliente))
                                    {{$ped->cliente->NomFan}}
                                @endif -
                                @if(isset($ped->cliente) && isset($ped->sucursal))
                                    {{$ped->sucursal->Domici}}
                                @elseif(isset($ped->cliente))
                                    {{$ped->cliente->Domici}}
                                @endif
                            </option>
                        @endforeach
                    </select>
                    <div class="sm:grid grid-cols-2 gap-2">
                        <input type="text" class="form-control mt-2 sm:mt-0" placeholder="Codigo de barras" name="codigo_barras" id="codigo_barras" onkeyup="inputCodigoBaras(event)">
                        @if($palet_en_prep->estado == 'PENDIENTE CIERRE')
                            <button class="btn btn-xs btn-success mt-2 sm:mt-0" id="btn-cerrar-palet" style="color: white"> Cerrar palet </button>
                        @else
                            <span style="color: red">EL palet esta cerrado, puede agregar o quitarle articulos.</span>
                        @endif
                    </div>
                </div>

                <p class='p-alert' style="margin: 5px ; text-align:right;color:red" id="p-alert"></p>
            </div>
        </div>
        @if (Session::has('msj'))
            <div class="alert alert-success alert-dismissible show flex items-center mb-2" role="alert">
                <i data-lucide="alert-triangle" class="w-6 h-6 mr-2"></i> {{Session::get('msj')}}
                <button type="button" class="btn-close" data-tw-dismiss="alert" aria-label="Close">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
        @endif

        <div class="hidden" id="loader" style="margin-top: 130px">
            <div class="col-span-6 sm:col-span-3 xl:col-span-2 flex flex-col justify-end items-center"> <i data-loading-icon="circles" class="w-8 h-8"></i> <div class="text-center text-xs mt-2">Cargando...</div> </div>
        </div>
        <div class="p-5" id="session_products_table">

        </div>

    </div>

    <div id="cerrar-palet-modal" class="modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body p-0">
                    <div class="p-5 text-center">
                        <i data-lucide="x-circle" class="w-16 h-16 text-danger mx-auto mt-3"></i>
                        <div class="text-3xl mt-5">
                            ¿Está seguro de cerrar el palet?
                        </div>
                        <div class="text-slate-500 mt-2">
                            Se conformará un nuevo N° de palet que tendrá que ubicar.
                        </div>
                    </div>
                    <div class="px-5 pb-8 text-center">
                        <form action="{{route('cerrar.palet')}}" method="post">
                            <button type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary w-24 mr-1">Cancelar</button>
                            @csrf
                            <input type="hidden" name="token" id="token" value="{{$token}}">
                            <button type="submit" class="btn btn-danger w-24">Cerrar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="delete-caja-modal" class="modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body p-0">
                    <div class="p-5 text-center">
                        <i data-lucide="x-circle" class="w-16 h-16 text-danger mx-auto mt-3"></i>
                        <div class="text-3xl mt-5">
                            ¿Está seguro de eliminar la caja?
                        </div>
                        <div class="text-slate-500 mt-2">
                            Se eliminará la caja del palet en preparación.
                        </div>
                    </div>
                    <div class="px-5 pb-8 text-center">
                        <form action="{{route('delete.caja.en.preparacion')}}" method="post">
                            <button type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary w-24 mr-1">Cancelar</button>
                            @csrf
                            <input type="hidden" name="caja_en_preparacion" id="caja_en_preparacion" value="">
                            <button type="submit" class="btn btn-danger w-24">Eliminar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    @parent
    <script src="{{asset('dt/js/jquery.js')}}"></script>
    <script>
        function inputCodigoBaras(e){
            if (event.keyCode == 13) {
                addCodigoBarras();
            }
        }

        $("#btn-cerrar-palet").click(function(){
            const myModal = tailwind.Modal.getInstance(document.querySelector("#cerrar-palet-modal"));
            myModal.show();
        })

        function modalDelete(cod_barras_caja){
            const myModal = tailwind.Modal.getInstance(document.querySelector("#delete-caja-modal"));
            $("#caja_en_preparacion").val(cod_barras_caja) ;
            myModal.show();
        }

        function addCodigoBarras(){
            var nro_comp = $("#nro_comp").val();
                var codigo_barras = $("#codigo_barras").val();
                var token = $("#token").val();
                var comprobante = $("#nro_comp").val();
                if(codigo_barras.length > 0){
                    $.ajax({
                        url: "{{route('verificar.cod-barras.en-expedicion')}}",
                        type: 'GET',
                        data: {
                            codigo_barras,
                            token,
                            comprobante
                        },
                        beforeSend: function(data) {
                            $("#p-alert").html('Aguarde...');
                        },
                        success: function(data) {
                            if(data['type'] == 'error'){
                                notificacionAlerta(data['msj']);
                                $("#p-alert").html(data['msj']);
                            }else{
                                jQuery("#session_products_table").html('');
                                notificacionExito(data['msj']);
                                $("#p-alert").html('');
                                $("#codigo_barras").val('');
                                cargarTablaProductos();
                            }
                            $("#codigo_barras").val('');
                            $("#codigo_barras").focus();
                        },
                        error: function(data) {
                            notificacionAlerta(JSON.stringify(data));
                            $("#p-alert").html('ERROR!');
                            $("#codigo_barras").val('');
                            $("#codigo_barras").focus();
                        }
                    });
                }else{
                    notificacionAlerta('Ingrese un código de barras');
                    $("#codigo_barras").focus();
                }
        }

        jQuery(document).ready(function() {
            cargarTablaProductos();
        });

        function cargarTablaProductos() {
            var token = jQuery("#token").val();
            var url = "{{ route('get.item.palet') }}";

            jQuery.ajax({
                url: url,
                type: 'GET',
                data: {token},
                beforeSend: function() {
                    jQuery("#loader").removeClass('hidden')
                },
                success: function(data) {
                    jQuery("#loader").addClass('hidden')
                    jQuery("#session_products_table").html('');
                    jQuery("#session_products_table").html(data['html']);
                },
                error: function(data) {},
                complete: function() {
                }
            });
        }
    </script>
@endsection
