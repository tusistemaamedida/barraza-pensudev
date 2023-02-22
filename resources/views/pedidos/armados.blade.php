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
                Pedidos Armados
            </h2>
        </div>
        <span style="float:right; color:red; font-size:12px;margin: -25px 10px;" id="span-error"></span>
        <div class="p-5" id="striped-rows-table">
            <div class="preview">
                <table class="table table-striped yajra-datatable">
                    <thead>
                        <tr>
                            <th >N° Comprob.</th>
                            <th >Fecha Comprob.</th>
                            <th>Items Solic</th>
                            <th>Items Armados</th>
                            <th>Cliente</th>
                            <th>Sucursal/Nom. Fant.</th>
                            <th>Dirección</th>
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
    </form>

    <div id="modal-detalles-items" class="modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-body p-0">
                    <div class="p-5 text-center">
                        <i data-lucide="check-circle" class="w-10 h-10 text-success mx-auto mt-3"></i>
                        <div class="text-3xl mt-5">Items Solicitados</div>
                        <div class="text-slate-500 mt-2" id="insert-view"></div>
                    </div>
                    <div class="px-5 pb-8 text-center">
                        <button type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary w-24 mr-1">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="modal-detalles-items-armados" class="modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-body p-0">
                    <div class="p-5 text-center">
                        <i data-lucide="check-circle" class="w-10 h-10 text-success mx-auto mt-3"></i>
                        <div class="text-3xl mt-5">Items Armados</div>
                        <div class="text-slate-500 mt-2" id="insert-view2"></div>
                    </div>
                    <div class="px-5 pb-8 text-center">
                        <button type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary w-24 mr-1">Cerrar</button>
                    </div>
                </div>
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
<script type="text/javascript">
    const myModal = tailwind.Modal.getInstance(document.querySelector("#modal-detalles-items"));
    const myModal2 = tailwind.Modal.getInstance(document.querySelector("#modal-detalles-items-armados"));
    var table = $('.yajra-datatable').DataTable({
        @include('partials.lenguaje-dt'),
        processing: true,
        serverSide: true,
        ajax: "{{ route('get.pedidos.armados') }}",
        columns: [
            {data: 'seleccionar'},
            {data: 'fecha'},
            {data: 'cantidad_items'},
            {data: 'cantidad_items_armados'},
            {data: 'cliente'},
            {data: 'sucursal'},
            {data: 'direccion'},
        ]
    });


    function getDetalles(nroComp){
        $.ajax({
            url: "{{route('get.pedido.items')}}",
            type: 'GET',
            data: {nroComp},
            beforeSend: function(data) {
                show_spinner()
            },
            success: function(data) {
                hide_spinner()
                if(data['type'] == 'error'){
                    notificacionAlerta(data['msj']);
                }else{
                    $("#insert-view").html(data['view']);
                    myModal.show();
                }
            },
            error: function(data) {
                notificacionAlerta(JSON.stringify(data));
                hide_spinner()
            }
        });
    }

    function getDetallesArmados(nroComp){
        $.ajax({
            url: "{{route('get.pedido-armado.items')}}",
            type: 'GET',
            data: {nroComp},
            beforeSend: function(data) {
                show_spinner()
            },
            success: function(data) {
                hide_spinner()
                if(data['type'] == 'error'){
                    notificacionAlerta(data['msj']);
                }else{
                    $("#insert-view2").html(data['view']);
                    myModal2.show();
                }
            },
            error: function(data) {
                notificacionAlerta(JSON.stringify(data));
                hide_spinner()
            }
        });
    }

  </script>
@endsection
