@extends('layouts.app')

@section('css')
@parent
<link href="{{asset('dt/css/jquery.dataTables.min.css')}}" rel="stylesheet">
<link href="{{asset('dt/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
<style>
    div.dataTables_wrapper div.dataTables_length select{
        width: 80px !important;
    }
</style>
@endsection

@section('content')
    <div class="intro-y box mt-5">
        <div class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60">
            <h2 class="font-medium text-base mr-auto">
                Expedición <span style="font-size: 11px;">(TS: Total Solicitado - PP: Piezas Preparadas - CP: Cajas Preparadas)</span>
            </h2>
        </div>
        <div class="p-5" id="striped-rows-table">
            <div class="preview">
                @if (Session::has('msj'))
                    <div class="alert alert-success alert-dismissible show flex items-center mb-2" role="alert">
                        <i data-lucide="alert-triangle" class="w-6 h-6 mr-2"></i> {{Session::get('msj')}}
                        <button type="button" class="btn-close" data-tw-dismiss="alert" aria-label="Close">
                            <i data-lucide="x" class="w-4 h-4"></i>
                        </button>
                    </div>
                @endif
                <table class="table table-striped yajra-datatable">
                    <thead>
                        <tr>
                            <th>Artículo</th>
                            <th>Comprobantes</th>
                            <th>TS</th>
                            <th>PP</th>
                            <th>CP</th>
                            <th>Usuario</th>
                            <th>Fecha</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <form action="{{route('preparar.pedido.view')}}" method="POST" id="form-view-ped">
        @csrf
        <input type="hidden" name="pedidosNro" id="pedidosNro" value="">
        <input type="hidden" name="exp_id" id="exp_id" value="">
        <input type="hidden" name="actualiza_exp" id="actualiza_exp" value="1">
    </form>
@endsection

@section('js')
@parent
<script src="{{asset('dt/js/jquery.js')}}"></script>
<script src="{{asset('dt/js/jquery.validate.js')}}"></script>
<script src="{{asset('dt/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('dt/js/bootstrap.min.js')}}"></script>
<script src="{{asset('dt/js/dataTables.bootstrap4.min.js')}}"></script>
<script type="text/javascript">

    var table = $('.yajra-datatable').DataTable({
        @include('partials.lenguaje-dt'),
        processing: true,
        serverSide: true,
        ajax: "{{ route('get.expediciones') }}",
        columns: [
            {data: 'cod_articulo'},
            {data: 'nro_comp'},
            {data: 'ts'},
            {data: 'pp'},
            {data: 'cp'},
            {data: 'usuario'},
            {data: 'fecha_format'},
            {data: 'editar'},
        ]
    });

    function prepararPedidosSeleccionados(comprob) {
        $("#span-error").html('');
        var split_comp = comprob.split('|');
        var arrId = split_comp;

        if (arrId.length > 0) {
            $("#pedidosNro").val(JSON.stringify(arrId))
            $.ajax({
                url: "{{route('preparar.pedido')}}",
                type: 'GET',
                data: {pedidosNro:arrId},
                beforeSend: function(data) {
                    show_spinner()
                },
                success: function(data) {
                    if(data['type'] == 'error'){
                        notificacionAlerta(data['msj']);
                        hide_spinner()
                    }else{
                        $("#form-view-ped").submit();
                        hide_spinner();
                    }
                },
                error: function(data) {
                    notificacionAlerta(JSON.stringify(data));
                    hide_spinner()
                }
            });
        }else{
            $("#span-error").html('¡NO selecciono ningún pedido!');
            notificacionAlerta('¡NO selecciono ningún pedido!');
        }
    }

    function editarExpedicion(comprob,exp_id) {
        $("#span-error").html('');
        var split_comp = comprob.split('|');
        var arrId = split_comp;

        if (arrId.length > 0) {
            $("#pedidosNro").val(JSON.stringify(arrId))
            $("#exp_id").val(exp_id)
            $.ajax({
                url: "{{route('preparar.pedido')}}",
                type: 'GET',
                data: {
                    pedidosNro:arrId,
                    exp_id
                },
                beforeSend: function(data) {
                    show_spinner()
                },
                success: function(data) {
                    if(data['type'] == 'error'){
                        notificacionAlerta(data['msj']);
                        hide_spinner()
                    }else{
                        $("#form-view-ped").submit();
                        hide_spinner();
                    }
                },
                error: function(data) {
                    notificacionAlerta(JSON.stringify(data));
                    hide_spinner()
                }
            });
        }else{
            $("#span-error").html('¡NO selecciono ningún artículo!');
            notificacionAlerta('¡NO selecciono ningún artículo!');
        }
    }

  </script>
@endsection
