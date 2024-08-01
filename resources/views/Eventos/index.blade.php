@extends('layouts.master')
    <title>Eventos</title>

@section('Content')
<div class="container">

    <div class="search-create">
        <h1 id="titulo-h1">Eventos</h1>
        @role(['Administrador','Organizador'])
            <button id="create-btn"><i class="las la-plus-circle la-2x"></i></button>
        @endrole
    </div>
    @if($Eventos->isEmpty())
        <strong>No hay datos</strong>
    @else
        @role('Administrador')
        <button id="migrate-button">Migrar Toda la Informacion</button>
        @endrole
    <!-- <div style="overflow-x:auto; overflow-y:auto; max-height:500px;"> -->
    <div class="ajuste" >
    <button id="deleteSelected">Eliminar seleccionados</button>
        <table id="example" class="display  responsive nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="selectAll"></th>
                        <th>LOGO</th>
                        <th>NOMBRE</th>
                        <th>ACRONIMO</th>
                        <th>ED.</th>
                        <th>Controles </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($Eventos as $e)
                    <tr>
                        <td><input type="checkbox" class="selectRow" data-id="{{ $e->id }}"></td>
                        <td>
                        <a href="{!! 'eventos/'.$e->id !!}" style="color:#000;">
                            <img id="img-list" src="{{ asset('SGEA/public/assets/uploads/' . $e->logo) }}" alt="logo">
                            </a>
                        </td>
                        <td><a href="{!! 'eventos/'.$e->id !!}" style="color:#000;"><strong>{!!$e->nombre!!}</strong></a></td>
                        <td>{!!$e->acronimo!!}</td>
                        <td>{!!$e->edicion!!}</td>
                        <td>
                            <a href="{!! 'eventos/'.$e->id !!}"><i class="las la-info-circle la-2x"></i></a>
                            
                            @role(['Administrador', 'Organizador'])
                            <a href="{!!'eventos/'.$e->id.'/edit'!!}">
                                <i class="las la-pen la-2x"></i>
                            </a>
                            <a href="{{url('eventos/'.$e->id)}}"
                                onclick="event.preventDefault(); if (confirm('¿Estás seguro de que deseas eliminar este evento?')) { document.getElementById('delete-form-{{ $e->id }}').submit(); }">
                                <i class="las la-trash-alt la-2x"></i>
                            </a>
                            <form id="delete-form-{{ $e->id }}" action="{{ url('eventos/'.$e->id) }}" method="POST"
                                style="display: none;">
                                @method('DELETE')
                                @csrf
                            </form>
                            @endrole
                            @if ($e->id !== session('eventoID'))
                                {!! Form::open(['route' => 'participantes.store', 'id' => 'participante-form']) !!}{!! Form::hidden('evento_id', $e->id) !!}{!! Form::hidden('usuario_id', Auth::user()->id) !!}
                                    <button type="submit"><i class="las la-user-plus la-2x"></i> Unirme</button>
                                {!!Form::close()!!}
                            @else
                                {!! Form::open(['route' => ['participantes.destroy', $e->id,Auth::user()->id], 'method' => 'delete', 'style' => 'display:inline-block;']) !!}
                                    <button type="submit" onclick="return confirm('¿Estás seguro de que desea salir del evento?');" style="border:none;"><i class="las la-sign-out-alt la-2x"></i>Salir</button>
                                {!! Form::close() !!}
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>

            </table>
        </div>
    @endif
</div>

<div id="create-modal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Registro de Evento</h2>
        {!! Form::open(['url'=>'/eventos', 'enctype' => 'multipart/form-data']) !!}
        <div class="container">
            <label for="logo">Imagenes en sistema:</label>
            @if (isset($sysImgs) && !empty($sysImgs))
                <div class="carousell">
                    @foreach ($sysImgs as $image)
                    <img src="{{  asset('SGEA/public/assets/uploads/'.$image) }}" alt="Imagen" class="img-thumbnail img-selectable" data-img-name="{{ $image }}" style="width: 70px;">
                    @endforeach
                </div>
            @else
                <strong>Aun no hay imagenes en el sistema</strong>
            @endif
        <br><hr><br>
        <input type="file" id="logo" name="logo" accept="image/png">
        <input type="hidden" id="selected_img" name="logo">
        <br>
        <img id="preview-image" src="#" alt="Previsualización de la imagen" style="display:none; width: 100px; margin-top: 10px;">
    </div>
    
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" required>

        <label for="acronimo">Acrónimo:</label>
        <input type="text" id="acronimo" name="acronimo" required>

        <label for="fecha_inicio">Fecha de inicio:</label>
        <input type="date" id="fecha_inicio" name="fecha_inicio" required>

        <label for="fecha_fin">Fecha de fin:</label>
        <input type="date" id="fecha_fin" name="fecha_fin" required>

        <label for="edicion">Edición:</label>
        <input type="number" id="edicion" name="edicion" required>

        <button type="submit">Guardar evento</button>
        {!!Form::close()!!}
</div>


@endsection
<script src="{{asset('SGEA/public/js/script-eventos.js')}}"></script>
<script>
   
</script>
<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', (event)=>{
        $(document).ready(function() {
            $('#migrate-button').click(function() {
                $.ajax({
                    url: '{{ route('migrate.data') }}',
                    type: 'POST',
                    data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        alert(response.message);
                        window.location.href = '{{ route('eventos.index') }}';
                    },
                    error: function(response) {
                        alert('Migration failed: ' + response.responseJSON.error);
                    }
                });
            });
        });

        fetchSidebar();
    });

    function fetchSidebar() {
            fetch('{{ route('get.sidebar') }}')
                .then(response => response.text())
                .then(html => {
                    document.getElementById('sidebar').innerHTML = html;
                });
        }
    
</script>


<style>
    .selected {
        border: 2px solid blue;
    }
</style>


