@extends('layouts.master')
    <title>Eventos</title>

@section('Content')
<div class="container">

    <div class="search-create">
    <h1 id="titulo-h1">Eventos</h1>
        <button id="create-btn"><i class="las la-plus-circle la-2x"></i></button>
    </div>
    @if($Eventos->isEmpty())
        <strong>No hay datos</strong>
    @else
    <table id="example" class="display" style="width:100%">
            <thead>
                <tr>
                    <th>LOGO</th>
                    <th>NOMBRE</th>
                    <th>ACRONIMO</th>
                    <th>ED.</th>
                    @role('Administrador')
                    <th> </th>
                    @endrole
                </tr>
            </thead>
            <tbody>
                @foreach ($Eventos as $e)
                <tr>
                    <td>
                        <img id="img-list" src="{{ asset('SGEA/public/assets/uploads/' . $e->logo) }}" alt="logo" style="width: 250px;" >
                    </td>
                    <td>{!!$e->nombre!!}</td>
                    <td>{!!$e->acronimo!!}</td>
                    <td>{!!$e->edicion!!}</td>
                    <td>
                        <a href="{!! 'eventos/'.$e->id !!}"><i class="las la-info-circle la-2x"></i></a>
                        @role(['Administrador', 'Organizador'])
                        <a href="{!!'eventos/'.$e->id.'/edit'!!}">
                            <i class="las la-pen la-2x"></i>
                        </a>
                        <a href="{{url('eventos/'.$e->id)}}"
                            onclick="event.preventDefault(); if (confirm('¿Estás seguro de que deseas eliminar este registro?')) { document.getElementById('delete-form-{{ $e->id }}').submit(); }">
                            <i class="las la-trash-alt la-2x"></i>
                        </a>
                        <form id="delete-form-{{ $e->id }}" action="{{ url('eventos/'.$e->id) }}" method="POST"
                            style="display: none;">
                            @method('DELETE')
                            @csrf
                        </form>
                        @endrole
                    </td>
                </tr>
                @endforeach
            </tbody>

        </table>
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
        <br>
        <hr><br>
        <input type="file" id="logo" name="logo" accept="image/png">
        
        <input type="hidden" id="selected_img" name="logo">
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
</div>

@endsection
<script src="{{asset('SGEA/public/js/script-eventos.js')}}"></script>

<style>
    .selected {
        border: 2px solid blue;
    }
</style>


