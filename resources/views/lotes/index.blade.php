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
                Lotes
            </h2>
        </div>
        <div class="p-5" id="striped-rows-table">
            <div class="preview">
                <table class="table table-striped yajra-datatable">
                    <thead>
                        <tr>
                            <th >#</th>
                            <th >LoteA</th>
                            <th >Cant. Envasados</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

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
        ajax: "{{ route('get.lotes') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'Lote'},
            {data: 'cantidad_envasados'}
        ]
    });

  </script>
@endsection
