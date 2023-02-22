@extends('layouts.app')

@section('css')
 @parent
 <style>
    .bg-gray-300 {
        --tw-bg-opacity: 1;
        background-color: rgb(209 213 219/var(--tw-bg-opacity));
    }
    .rounded-full {
     border-radius: 9999px;
    }
 </style>
@endsection

@section('content')
<div class="grid grid-cols-12 gap-6 mt-5">
    <div class="intro-y col-span-12">
        <div class="intro-y box">
            <div id="inline-form" class="p-5">
                <div class="preview">
                    <div class="grid grid-cols-12 gap-2">
                        <input type="text" class="form-control col-span-4" onkeyup="buscar_pallet(event)" autofocus
                               placeholder="Ingresar código de barras" aria-label="default input inline 3" id="in-pallet">
                        <p class="col-span-12" id="p_error" style="color: red;font-size:11px"></p>
                        <p class="col-span-12" id="p_info" style="width: 100%"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="timeline" ></div>
@endsection

@section('js')
@parent
<script src="{{asset('dt/js/jquery.js')}}"></script>

<script>
    function buscar_pallet(event){
        $("#p_info").html('');
        $("#p_error").html('');
        $("#timeline").html('');
        if (event.keyCode == 13) {
            loadInfo();
        } else {
            return false;
        }
    }

        $(document).ready(function () {
            loadInfo();
        })

        function loadInfo(showspinner = true){
            var codigo = $("#in-pallet").val();
            if(codigo.length === 0){
                /* notificacionAlerta('Ingrese algún código de barras');
                $("#p_error").html('Ingrese algún código de barras') */
                codigo = 0
            }
            $.ajax({
                url: "{{route('get.pallet.hist')}}",
                type: 'GET',
                data: {codigo},
                beforeSend: function(data) {
                    $("#p_error").html('Aguarde por favor...')
                    if(showspinner) show_spinner()
                },
                success: function(data) {
                    $("#p_error").html('')
                    $("#p_info").html('')
                    $("#in-pallet").val('')
                    if(data['type'] == 'error'){
                        notificacionAlerta(data['msj']);
                    }else{
                        $("#p_info").html(data['msj'])
                        $("#timeline").html(data['html']);
                    }

                },
                error: function(data) {},
                complete: function() {
                    $("#in-pallet").focus()
                    if(showspinner)  hide_spinner()
                }
            });
        }
</script>

@endsection
