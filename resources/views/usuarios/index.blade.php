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
                Usuarios
            </h2>
            <div class="form-check form-switch w-full sm:w-auto sm:ml-auto mt-3 sm:mt-0">
                <button class="btn btn-success" style="color: white;margin-right:5px" data-tw-toggle="modal" data-tw-target="#modal-usuario" >
                    <i class="fa fa-plus" style="margin-top: 5px;padding-right: 5px;"></i> Agregar
                </button>
            </div>
        </div>
        <div class="p-5" id="striped-rows-table">
            <div class="preview">
                <table class="table table-striped yajra-datatable">
                    <thead>
                        <tr>
                            <th >#</th>
                            <th >Nombre</th>
                            <th >Documento</th>
                            <th >Teléfono</th>
                            <th >Rol</th>
                            <th >Estado</th>
                            <th >F. Alta</th>
                            <th style="width: 150px">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @include('usuarios.modal-form')
@endsection

@section('js')
@parent
<script src="{{asset('dt/js/jquery.js')}}"></script>
<script src="{{asset('dt/js/jquery.validate.js')}}"></script>
<script src="{{asset('dt/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('dt/js/bootstrap.min.js')}}"></script>
<script src="{{asset('dt/js/dataTables.bootstrap4.min.js')}}"></script>
<script type="text/javascript">

    var table = $('.yajra-datatable').DataTable({
        @include('partials.lenguaje-dt'),
        processing: true,
        serverSide: true,
        ajax: "{{ route('get.usuarios') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'nombre'},
            {data: 'documento'},
            {data: 'telefono'},
            {data: 'rol'},
            {data: 'activo'},
            {data: 'creado'},
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false
            },
        ]
    });

    const store = (route) => {
        const mySlideOver = tailwind.Modal.getInstance(document.querySelector(".modal-slide-over"));
        var elements = document.querySelectorAll('.border-danger');
        var form = jQuery('#formData').serialize();
        jQuery.ajax({
            url: route,
            type: 'POST',
            data: form,
            beforeSend: function () {
                for (var i = 0; i < elements.length; i++) {
                    elements[i].classList.remove('border-danger');
                }
            },
            success: function (data) {
                if (data['type'] == 'success') {
                    notificacionExito(data['msj']);
                    setTimeout(() => {
                        document.getElementById("formData").reset();
                        mySlideOver.hide()
                        table.ajax.reload();
                    }, 1000);
                } else {
                    notificacionAlerta(data['msj']);
                }
            },
            error: function (data) {
                var lista_errores = "";
                var data = JSON.parse(data.responseText);
                jQuery.each(data.errors, function (index, value) {
                    lista_errores += value + '<br />';
                    jQuery('#' + index).addClass('border-danger');
                    jQuery('#' + index).next().find('.select2-selection').addClass('border-danger');
                });
                notificacionAlerta(lista_errores)
            }
        });
    };

  </script>
@endsection
