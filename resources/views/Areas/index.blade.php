@extends('layouts.master')
<title>Areas</title>
<!-- <link rel="stylesheet" href="./css/style-areas.css"> -->

</head>

@section('Content')
<div class="container">
    
    <div class="search-create">
    <h1 id="titulo-h1">areas</h1>
        <button id="create-event-btn">Registrar nueva area</button>
    </div>

<table id="example" class="display" style="width:100%">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Descripcion</th>
                <th>Controles</th>
            </tr>

        </thead>
        <tbody>
            @foreach ($Areas as $area)
            <tr>
                <td>{!!$area->nombre!!}</td>
                <td>{!!$area->descripcion!!}</td>
                <td>
                    <a href="{!!'areas/'.$area->id.'/edit'!!}">
                        <button>editar</button>
                    </a>
                    <a href="{{url('areas/'.$area->id)}}"
                        onclick="event.preventDefault(); if (confirm('¿Estás seguro de que deseas eliminar este registro?')) { document.getElementById('delete-form-{{ $area->id }}').submit(); }">
                        <button>Eliminar</button>
                    </a>
                    <form id="delete-form-{{ $area->id }}" action="{{ url('areas/'.$area->id) }}" method="POST"
                        style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
        
    </table>
    
</div>

<div id="create-event-modal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Registrar Area</h2>
        {!! Form::open(['url'=>'/areas']) !!}
        <label for="area-name">Nombre:</label>
        <input type="text" id="nombre" name="nombre" required>

        <label for="area-description">Descripción:</label>
        <textarea id="description" name="descripcion" required></textarea>

        <button type="submit">Guardar</button>

        {!!Form::close()!!}
    </div>
</div>


@endsection
<script src="./js/script-areas.js"></script>