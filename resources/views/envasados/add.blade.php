@extends('layouts.app')

@section('css')
@parent
@endsection

@section('content')
    <div class="intro-y box mt-5" style="    min-height: 400px;">
        <div class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60">
            <div class="w-full mt-3 xl:mt-0 flex-1">
                <form action="#" id="form-palet-info" style="display: contents">
                    @csrf
                    <input type="hidden" name="codigo_barras_palet" id="codigo_barras_palet" @if(isset($codigo_barras_palet)) value="{{$codigo_barras_palet}}" @endif>
                    <div class=" gap-2 flex flex-col sm:flex-row items-center">
                        <div class="mt-2" style="width: 30% !important;">
                            <label for="input-state-2" class="form-label">Producto:</label>
                            <select data-placeholder="Buscar en envasados" class="tom-select" name="articulo_id" id="articulo_select">
                                <option value="" selected>Seleccione uno</option>
                                @foreach ($articulos_envasados as $art)
                                    <option value="{{$art->Id}}">
                                        {{$art->Codigo}} - {{$art->Descripcion}}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mt-2"  style="width: 20% !important;">
                            <label for="input-state-2" class="form-label">Lote:</label>
                            <input type="text" class="form-control mt-2 sm:mt-0" placeholder="Ingrese lote" name="lote"      id="lote" />
                        </div>
                        <div class="mt-2"  style="width: 20% !important;">
                            <label for="input-state-2" class="form-label">Fecha Elab:</label>
                            <input type="date" class="form-control mt-2 sm:mt-0" placeholder="F. Elab."  name="fecha_elaboracion"  id="fecha_elaboracion">
                        </div>
                        <div class="mt-2"   style="width: 15% !important;">
                            <label for="input-state-2" class="form-label">Unidades:</label>
                            <input type="number" class="form-control mt-2 sm:mt-0" placeholder="Ingrese cantidad"  name="cantidad"      id="cantidad" />
                        </div>
                        <div class="mt-2" style="width: 15% !important;">
                            <label for="input-state-2" class="form-label">Kilos:</label>
                            <input type="number" class="form-control mt-2 sm:mt-0" placeholder="Ingrese kgs totales" name="kilos"     id="kilos" step="0.5" />
                        </div>
                    </div>
                    <div class="sm:grid grid-cols-1 gap-2" style="float:right;">
                        <button type="button" class="btn btn-xs btn-success mt-2 sm:mt-0" id="btn-add-to-palet" style="color: white;width:150px;margin-top:25px;"> Agregar al palet </button>
                    </div>
                    <p class='p-alert' style="text-align:right;color:red" id="p-alert"></p>
                </form>
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
            <div class="col-span-6 sm:col-span-3 xl:col-span-2 flex flex-col justify-end items-center">
                <i data-loading-icon="circles" class="w-8 h-8"></i>
                <div class="text-center text-xs mt-2">Cargando...</div>
            </div>
        </div>

        <div class="p-5" id="session_products_table"></div>

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
        $("#btn-add-to-palet").click(function(){
            var form = $("#form-palet-info").serialize();
            $("#btn-add-to-palet").attr('disabled',true);
            $.ajax({
                url: "{{route('store.palet.envasado')}}",
                type: 'POST',
                data: form,
                beforeSend: function(data) {
                    show_spinner()
                    $("#p-alert").html('Aguarde...');
                },
                success: function(data) {
                    if(data['type'] == 'error'){
                        notificacionAlerta(data['msj']);
                        $("#p-alert").html(data['msj']);
                    }else{
                        jQuery("#session_products_table").html('');
                        notificacionExito(data['msj']);
                        $("#codigo_barras_palet").val(data['codigo_barra_palet']);
                        $("#p-alert").html('');
                        $("#lote").val('');
                        $("#cantidad").val('');
                        $("#kilos").val('');
                        $("#fecha_elaboracion").val('');
                        cargarTablaProductos();
                    }
                    hide_spinner();
                    $("#btn-add-to-palet").attr('disabled',false);
                },
                error: function(data) {
                    var lista_errores = "";
                    var errors = JSON.parse(data.responseText);
                    jQuery.each(errors.errors, function(index, value) {
                        console.log(index)
                        lista_errores += value + ' ';
                    });
                    notificacionAlerta(lista_errores);
                    $("#p-alert").html(lista_errores);
                    hide_spinner();
                    $("#btn-add-to-palet").attr('disabled',false);
                }
            });
        })

        jQuery(document).ready(function() {
            cargarTablaProductos();
        });

        function cargarTablaProductos() {
            var codigo_barras_palet = jQuery("#codigo_barras_palet").val();
            var url = "{{ route('get.items.palet.envasado') }}";

            jQuery.ajax({
                url: url,
                type: 'GET',
                data: {codigo_barras_palet},
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
