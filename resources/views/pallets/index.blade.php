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
                Pallets
            </h2>
        </div>
        <div class="p-5" id="striped-rows-table">
            <div class="preview">
                <table class="table table-striped ">
                    <thead>
                        <tr>
                            <th >Producto</th>
                            <th >Pallet</th>
                            <th >Lote</th>
                            <th >F.Elab.</th>
                            <th >Unidades</th>
                            <th >KIlos</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pallets as $pallet)
                            <tr>
                                <td>{{$pallet->producto->Codigo}} - {{$pallet->producto->Descripcion}}</td>
                                <td>{{$pallet->CodBarraPallet_Int}}</td>
                                <td>{{$pallet->Lote}}</td>
                                <td>{{\Carbon\Carbon::parse($pallet->FechaElaboracion)->format('d/m/Y')}}</td>
                                <td>{{$pallet->unidades}}</td>
                                <td>{{number_format($pallet->kilos, 2, ',', '.')}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="row">
                {!! $pallets->links()!!}
            </div>
        </div>
    </div>

@endsection

@section('js')
    @parent
@endsection
