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
                Pallets armados
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
                            <th>#</th>
                            <th >F. Prepación</th>
                            <th >F. Cierre</th>
                            <th >Usuario</th>
                            <th >Piezas</th>
                            <th >Cajas</th>
                            <th >Comprobante/s</th>
                            <th >Lote/s</th>
                            <th >Peso</th>
                            <th >Peso R</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($palet_armados as $pallet)
                            <tr>
                                <td>
                                    {{$pallet->items[0]->CodBarraPallet_Int}}
                                </td>
                                <td>
                                    {{\Carbon\Carbon::parse($pallet->fecha_preparacion)->format('d/m/Y')}}
                                </td>
                                <td>
                                    {{\Carbon\Carbon::parse($pallet->fecha_cierre)->format('d/m/Y')}}
                                </td>
                                <td>{{$pallet->user->nombre}}</td>
                                <td>{{$pallet->piezas}}</td>
                                <td>{{ $pallet->cajas }}</td>
                                <td>{!! str_replace('|','<br>',$pallet->comprobantes)  !!}</td>
                                <td>{!! str_replace('|','<br>',$pallet->lotes)  !!}</td>
                                <td>{{ $pallet->peso_total }}</td>
                                <td>{{ $pallet->peso_real_total }}</td>
                                <td class="table-report__action w-56">
                                    <div class="flex justify-center items-center">
                                        <a class="flex items-center mr-3" href="{{route('ver.preparar.armado',['pallet_armado_id' => $pallet->id])}}">
                                            <i data-lucide="eye" class="w-4 h-4 mr-1"></i> Ver
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
