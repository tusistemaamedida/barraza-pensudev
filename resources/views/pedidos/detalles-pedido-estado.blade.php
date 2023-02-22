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
                Detalles del pedido {{$pedido->NroCom}}
            </h2>
            <p>
                @if(isset($pedido->cliente)) {{$pedido->cliente->RazSoc}} @endif <br>
                @if(isset($pedido->cliente) && isset($pedido->sucursal) && !is_null($pedido->sucursal->Nombre))
                    {{$pedido->sucursal->Nombre}}
                @elseif(isset($pedido->cliente))
                    {{$pedido->cliente->NomFan}}
                @endif <br>
                @if(isset($pedido->cliente) && isset($pedido->sucursal))
                    {{$pedido->sucursal->Domici}}
                @elseif(isset($pedido->cliente))
                    {{$pedido->cliente->Domici}}
                @endif
            </p>
        </div>
        <span style="float:right; color:red; font-size:12px;margin: -25px 10px;" id="span-error"></span>
        <div class="p-5" id="striped-rows-table">
            @if (Session::has('msj'))
            <div class="alert alert-success alert-dismissible show flex items-center mb-2" role="alert">
                <i data-lucide="alert-triangle" class="w-6 h-6 mr-2"></i> {{Session::get('msj')}}
                <button type="button" class="btn-close" data-tw-dismiss="alert" aria-label="Close">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
        @endif
            <div class="preview">
                <table class="table table-striped yajra-datatable">
                    <thead>
                        <tr>
                            <th>Artículo</th>
                            <th>Kilos</th>
                            <th>Unidades</th>
                            <th>Cajas P.</th>
                            <th>Uni. P.</th>
                            <th>Peso</th>
                            <th>P. Real</th>
                            <th>Lote</th>
                            <th>Palet</th>
                            <th style="width: 150px"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($articulos as $arti)
                            @php
                                $valor = '0';
                                $color = ' ';
                                $porc = (int) (($arti->unidades_p/$arti->unidades)*100);
                                if($porc == 0){
                                    $valor = '0';
                                    $color = ' ';
                                }elseif($porc < 26){
                                    $valor = '1/4';
                                    $color = ' bg-danger';
                                }elseif($porc>25 && $porc <51){
                                    $valor = '1/2';
                                    $color = ' bg-warning';
                                }elseif($porc>50 && $porc <76){
                                    $valor = '3/4';
                                    $color = ' ';
                                }elseif($porc>75){
                                    $valor = '1';
                                    $color = ' bg-success';
                                }
                            @endphp
                            <tr>
                                <td>
                                    {{$arti->codigo}} - {{$arti->nombre}}
                                </td>
                                <td>
                                    {{$arti->kilos}}
                                </td>
                                <td>
                                    {{$arti->unidades}}
                                </td>
                                <td>
                                    {{$arti->cajas_p}}
                                </td>
                                <td>
                                    {{$arti->unidades_p}}
                                </td>
                                <td>
                                    {{$arti->peso_real_p}}
                                </td>
                                <td>
                                    {{$arti->peso_p}}
                                </td>
                                <td>
                                    {!! str_replace("|",'<br>', $arti->lote) !!}
                                </td>
                                <td>
                                    {!! str_replace("|",'<br>', $arti->palet) !!}
                                </td>
                                <td style="width: 150px" class="tooltip" title="Solicitado: {{$arti->unidades}} / Preparado: {{$arti->unidades_p}}">
                                    <div class="progress h-4">
                                        <div class=" progress-bar w-{{$valor}}  {{$color}}" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" >
                                           @if($porc) {{$porc}}% @endif
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <button class="btn btn-xs btn-danger" type="button" id="btn-cerrar-pedido" style="color: white; margin-left:80%; width:20%;margin-top:15px;">
                    Cerrar Pedido  {{$pedido->NroCom}}
                </button>

            </div>
        </div>
    </div>

    <div id="cerrar-pedido-modal" class="modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body p-0">
                    <div class="p-5 text-center">
                        <i data-lucide="x-circle" class="w-16 h-16 text-danger mx-auto mt-3"></i>
                        <div class="text-3xl mt-5">
                            ¿Está seguro de cerrar el pedido {{$pedido->NroCom}}?
                        </div>
                        <div class="text-slate-500 mt-2">
                            Se enviará la información a facturación SICOI.
                        </div>
                    </div>
                    <div class="px-5 pb-8 text-center">
                        <form action="{{route('cerrar.pedido')}}" method="post">
                            <button type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary w-24 mr-1">Cancelar</button>
                            @csrf
                            <input type="hidden" name="nro_comp" id="nro_comp" value="{{$pedido->NroCom}}">
                            <button type="submit" class="btn btn-danger w-24">Cerrar</button>
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
    $("#btn-cerrar-pedido").click(function(){
            const myModal = tailwind.Modal.getInstance(document.querySelector("#cerrar-pedido-modal"));
            myModal.show();
        })
</script>
@endsection
