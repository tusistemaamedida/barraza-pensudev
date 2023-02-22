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
                Pallets pendientes de cerrar
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
                            <th >Fecha</th>
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
                        @foreach ($palets_pendientes as $pallet)
                            <tr>
                                <td>
                                    {{$pallet->id}}
                                </td>
                                <td>
                                    {{\Carbon\Carbon::parse($pallet->fecha)->format('d/m/Y')}} {{\Carbon\Carbon::parse($pallet->hora)->format('H:i')}}
                                </td>
                                <td>{{$pallet->user->nombre}}</td>
                                <td>{{count($pallet->piezas)}}</td>
                                <td>{{ $pallet->cajas() }}</td>
                                <td>{{ implode(' | ',array_unique($pallet->piezas->pluck('comprobante')->toArray())) }}</td>
                                <td>{{ implode(' | ',array_unique($pallet->piezas->pluck('lote')->toArray())) }}</td>
                                <td>{{ array_sum($pallet->piezas->pluck('peso')->toArray()) }}</td>
                                <td>{{ array_sum($pallet->piezas->pluck('peso_real')->toArray()) }}</td>
                                <td class="table-report__action w-56">
                                    <div class="flex justify-center items-center">
                                        <a class="flex items-center mr-3" href="{{route('preparar.palet',['token' => $pallet->token])}}">
                                            <i data-lucide="check-square" class="w-4 h-4 mr-1"></i> Editar
                                        </a>
                                        <a class="flex items-center text-danger" href="javascript:;" onclick="modalDelete({{$pallet->id}})">
                                            <i data-lucide="trash-2" class="w-4 h-4 mr-1"></i> Eliminar
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

    <div id="delete-confirmation-modal" class="modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body p-0">
                    <div class="p-5 text-center">
                        <i data-lucide="x-circle" class="w-16 h-16 text-danger mx-auto mt-3"></i>
                        <div class="text-3xl mt-5">
                            ¿Está seguro de eliminar el palet?
                        </div>
                        <div class="text-slate-500 mt-2">
                            Se eliminará el palet que esta en preparación.
                        </div>
                    </div>
                    <div class="px-5 pb-8 text-center">
                        <form action="{{route('delete.palet.en.preparacion')}}" method="post">
                            <button type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary w-24 mr-1">Cancelar</button>
                            @csrf
                            <input type="hidden" name="palet_en_preparacion" id="palet_en_preparacion" value="">
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

    <script>
        function modalDelete(id_palet_en_preparacion){
            const myModal = tailwind.Modal.getInstance(document.querySelector("#delete-confirmation-modal"));
            $("#palet_en_preparacion").val(id_palet_en_preparacion) ;
            myModal.show();
        }
    </script>
@endsection
