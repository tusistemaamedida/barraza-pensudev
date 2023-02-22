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
                Detalles palet armado
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
                <table class="table table-striped ">
                    <thead>
                        <tr>
                            <th >F. Prepación</th>
                            <th >F. Cierre</th>
                            <th >Usuario</th>
                            <th >Piezas</th>
                            <th >Cajas</th>
                            <th >Comprobante/s</th>
                            <th >Lote/s</th>
                            <th >Peso</th>
                            <th >Peso R</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                {{\Carbon\Carbon::parse($palet_armado->fecha_preparacion)->format('d/m/Y')}}
                            </td>
                            <td>
                                {{\Carbon\Carbon::parse($palet_armado->fecha_cierre)->format('d/m/Y')}}
                            </td>
                            <td>{{$palet_armado->user->nombre}}</td>
                            <td>{{$palet_armado->piezas}}</td>
                            <td>{{ $palet_armado->cajas }}</td>
                            <td>{!! str_replace('|','<br>',$palet_armado->comprobantes)  !!}</td>
                            <td>{!! str_replace('|','<br>',$palet_armado->lotes)  !!}</td>
                            <td>{{ $palet_armado->peso_total }}</td>
                            <td>{{ $palet_armado->peso_real_total }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <hr style="padding: 15px 0px;">
            <div class="preview">
                <table class="table table-striped ">
                    <thead>
                        <tr>
                            <th>CB. Palet</th>
                            <th>CB. Caja</th>
                            <th>CB. Artículo</th>
                            <th>Nombre</th>
                            <th>Lote</th>
                            <th >Peso</th>
                            <th >Peso R</th>
                            <th>Comprobante</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($palet_armado->items as $item)
                            <tr>
                                <td>
                                    {{$item->CodBarraPallet_Int}}
                                </td>
                                <td>
                                    {{$item->CodBarraCaja_Int}}
                                </td>
                                <td>
                                    {{$item->CodBarraArt_Int}}
                                </td>
                                <td>
                                    {{$item->nombre}}
                                </td>
                                <td>
                                    {{$item->Lote}}
                                </td>
                                <td>{{ $item->Peso }}</td>
                                <td>{{ $item->Peso_Real }}</td>
                                <td>{{ $item->comprobante }}</td>
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
