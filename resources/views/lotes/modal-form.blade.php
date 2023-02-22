<div id="modal-lote" class="modal modal-slide-over" data-tw-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <a data-tw-dismiss="modal" href="javascript:;"> <i data-lucide="x" class="w-8 h-8 text-slate-400"></i> </a>
            <div class="modal-header">
                <h2 class="font-medium text-base mr-auto">
                    Nuevo Lote
                </h2>
            </div>
            <div class="modal-body">
                <div>
                    <label for="numero" class="form-label">Número</label>
                    <input id="numero" type="text" name="numero" class="form-control" placeholder="LOTE-1234">
                </div>
                <div class="mt-3">
                    <label for="estado" class="form-label">Estado</label>
                    <select id="estado" name="estado" class="tom-select w-full tomselected">
                        @foreach ($estados as $estado)
                            <option value="{{$estado->nombre}}">{{$estado->nombre}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-footer w-full absolute bottom-0">
                <button type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary w-20 mr-1"><i class="fa fa-ban" style="margin-top: 3px;padding-right: 5px;"></i> Cancelar</button>
                <button type="button" class="btn btn-primary w-20"><i class="fa fa-save" style="margin-top: 3px;padding-right: 5px;"></i> Guardar</button>
            </div>
            <!-- END: Slide Over Footer -->
        </div>
    </div>
</div>
