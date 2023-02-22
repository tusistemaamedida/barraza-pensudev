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
                Estados de Pedidos
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
                            <th colspan="2" style="width: 200px">Articulos solicitados</th>
                            <th>Cliente</th>
                            <th>Sucursal/Nom. Fant.</th>
                            <th>Dirección</th>
                            <th style="width: 50px"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pedidos as $pedido)
                            @php
                                $valor = '0';
                                $color = ' ';
                                $porc = (int) (($pedido->articulos_preparados/$pedido->articulos_solicitados)*100);
                                if($porc < 26){
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
                                    {{$pedido->NroCom}}
                                </td>
                                <td>
                                    {{\Carbon\Carbon::parse($pedido->FecCom)->format('d/m/Y H:i')}}
                                </td>
                                <td>
                                    {{$pedido->articulos_solicitados}}
                                </td>
                                <td style="width: 150px" class="tooltip" title="Articulos agregados: {{$pedido->articulos_preparados}}">
                                    <div class="progress h-4">
                                        <div class=" progress-bar w-{{$valor}}  {{$color}}" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" >
                                            {{$porc}}%
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if(isset($pedido->cliente))
                                        {{$pedido->cliente->RazSoc}}
                                    @endif
                                </td>
                                <td>
                                    @if(isset($pedido->cliente) && isset($pedido->sucursal) && !is_null($pedido->sucursal->Nombre))
                                        {{$pedido->sucursal->Nombre}}
                                    @elseif(isset($pedido->cliente))
                                        {{$pedido->cliente->NomFan}}
                                    @endif
                                </td>
                                <td>
                                    @if(isset($pedido->cliente) && isset($pedido->sucursal))
                                        {{$pedido->sucursal->Domici}}
                                    @elseif(isset($pedido->cliente))
                                        {{$pedido->cliente->Domici}}
                                    @endif
                                </td>
                                <td class="table-report__action" style="width: 50px">
                                    <div class="flex justify-center items-center">
                                        <a class="flex items-center text-info" href="{{route('ver.pedido.en.transito',['nro_comp' => $pedido->NroCom])}}" >
                                            <i class="fa fa-eye" class="w-4 h-4 mr-1" style="padding-right: 5px"></i> Ver
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('js')
@parent

@endsection
