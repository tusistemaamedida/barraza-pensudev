@extends('layouts.app')

@section('content')
<h2 class="intro-y text-lg font-medium mt-10">
    Productos
</h2>
<div class="grid grid-cols-12 gap-6 mt-5">
    <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
        <a href="#" class="btn btn-primary shadow-md mr-2">Agregar Nuevo Producto</a>
        <div class="dropdown">
            <button class="dropdown-toggle btn px-2 box" aria-expanded="false" data-tw-toggle="dropdown">
                <span class="w-5 h-5 flex items-center justify-center"> <i class="w-4 h-4" data-lucide="plus"></i> </span>
            </button>
            <div class="dropdown-menu w-40">
                <ul class="dropdown-content">
                    <li>
                        <a href="" class="dropdown-item"> <i data-lucide="file-text" class="w-4 h-4 mr-2"></i> Exportar Excel </a>
                    </li>
                    <li>
                        <a href="" class="dropdown-item"> <i data-lucide="file-text" class="w-4 h-4 mr-2"></i> Imprimir en PDF </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="hidden md:block mx-auto text-slate-500">{{$productos->total()}} productos encontrados</div>
        <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
            <div class="w-56 relative text-slate-500">
                <input type="text" class="form-control w-56 box pr-10" placeholder="Buscar...">
                <i class="w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0" data-lucide="Buscar"></i>
            </div>
        </div>
    </div>

    <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
        <table class="table table-report -mt-2">
            <thead>
                <tr>
                    <th class="whitespace-nowrap">Código</th>
                    <th class="whitespace-nowrap">Descripción</th>
                    <th class="whitespace-nowrap">Pallet</th>
                    <th class="whitespace-nowrap">Cod. Artículo GS1</th>
                    <th class=" whitespace-nowrap">Cod. Caja GS1</th>
                    <th class="text-center whitespace-nowrap">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($productos as $producto)
                    <tr class="intro-x">
                        <td class="w-60">
                            <div class="font-medium whitespace-nowra">
                                {{$producto->code}}
                            </div>
                        </td>
                        <td >
                            <div class="text-slate-500 text-xs whitespace-nowrap mt-0.5">
                                {{$producto->description}}
                            </div>
                        </td>
                        <td class="w-60">
                            <div class="text-slate-500 text-xs whitespace-nowrap mt-0.5">
                                {{$producto->pallet->lot_number}}
                            </div>
                        </td>
                        <td >
                            <div class="text-slate-500 text-xs whitespace-nowrap mt-0.5">
                                {{$producto->bar_code_article_gs1}}
                            </div>
                        </td>
                        <td >
                            <div class="text-slate-500 text-xs whitespace-nowrap mt-0.5">
                                {{$producto->bar_code_box_gs1}}
                            </div>
                        </td>
                        <td class="table-report__action w-56">
                            <div class="flex justify-center items-center">
                                <a class="flex items-center mr-3" href="javascript:;"> <i data-lucide="check-square" class="w-4 h-4 mr-1"></i> Editar </a>
                                <a class="flex items-center text-danger" href="javascript:;" data-tw-toggle="modal" data-tw-target="#delete-confirmation-modal"> <i data-lucide="trash-2" class="w-4 h-4 mr-1"></i> Eliminar </a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<!-- BEGIN: Delete Confirmation Modal -->
<div id="delete-confirmation-modal" class="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body p-0">
                <div class="p-5 text-center">
                    <i data-lucide="x-circle" class="w-16 h-16 text-danger mx-auto mt-3"></i>
                    <div class="text-3xl mt-5">Are you sure?</div>
                    <div class="text-slate-500 mt-2">
                        Do you really want to delete these records?
                        <br>
                        This process cannot be undone.
                    </div>
                </div>
                <div class="px-5 pb-8 text-center">
                    <button type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary w-24 mr-1">Cancel</button>
                    <button type="button" class="btn btn-danger w-24">Delete</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
