<div id="modal-usuario" class="modal modal-slide-over" data-tw-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <a data-tw-dismiss="modal" href="javascript:;"> <i data-lucide="x" class="w-8 h-8 text-slate-400"></i> </a>
            <div class="modal-header">
                <h2 class="font-medium text-base mr-auto">
                    Nuevo Usuario
                </h2>
            </div>
            <form action="{{route('store.usuario')}}" method="POST" id="formData">
                @csrf
                <div class="modal-body">
                    <div>
                        <label for="nombre" class="form-label">Nombre y apellido</label>
                        <input id="nombre" type="text" name="nombre" class="form-control" placeholder="Ingrese el nombre  apellido">
                    </div>
                    <div class="mt-3">
                        <div class="grid grid-cols-12 gap-2">
                            <label for="documento" class="form-label col-span-6">Documento</label>
                            <label for="telefono" class="form-label col-span-6">Teléfono</label>
                        </div>
                        <div class="grid grid-cols-12 gap-2">
                            <input id="documento" type="text" name="documento" class="form-control col-span-6" placeholder="Ingrese documento">
                            <input id="telefono" type="text" name="telefono" class="form-control col-span-6" placeholder="Ingrese tel/cel">
                        </div>
                    </div>
                    <div class="mt-3">
                        <label for="direccion" class="form-label">Dirección</label>
                        <input id="direccion" type="text" name="direccion" class="form-control" placeholder="Ingrese dirección">
                    </div>
                    <div class="mt-3">
                        <label for="legajo" class="form-label">Legajo</label>
                        <input id="legajo" type="text" name="legajo" class="form-control" placeholder="Ingrese n° de legajo">
                    </div>
                    <div class="mt-3">
                        <div class="grid grid-cols-12 gap-2">
                            <label for="role" class="form-label col-span-6">ROL</label>
                            <label for="activo" class="form-label col-span-6">Estado</label>
                        </div>
                        <div class="grid grid-cols-12 gap-2">
                            <select id="rol" name="rol" class="tom-select w-full tomselected col-span-6">
                                @foreach ($roles as $role)
                                    <option value="{{$role}}">{{$role}}</option>
                                @endforeach
                            </select>

                            <select id="activo" name="activo" class="tom-select w-full tomselected col-span-6">
                                <option value="1">ACTIVO</option>
                                <option value="0">INACTIVO</option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="grid grid-cols-12 gap-2">
                            <label for="documento" class="form-label col-span-6">Email</label>
                            <label for="telefono" class="form-label col-span-6">Password</label>
                        </div>
                        <div class="grid grid-cols-12 gap-2">
                            <input id="email" type="email" name="email" class="form-control col-span-6" placeholder="Ingrese email">
                            <input id="password" type="password" name="password" class="form-control col-span-6" placeholder="Ingrese password">
                        </div>
                    </div>
                </div>
                <div class="modal-footer w-full absolute bottom-0">
                    <button type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary w-20 mr-1"><i class="fa fa-ban" style="margin-top: 3px;padding-right: 5px;"></i> Cancelar</button>
                    <button type="button" class="btn btn-primary w-20"  onclick="store('{{ route('store.usuario') }}')">
                        <i class="fa fa-save" style="margin-top: 3px;padding-right: 5px;"></i> Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
