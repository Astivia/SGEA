@extends('layouts.master')
<title>Eventos</title>
<link rel="stylesheet" href="./css/style-home.css">
<link rel="stylesheet" href="./css/styles.css">
</head>

@section('Content')

<div class="container">
    <h1>Eventos</h1>
    <div class="search-create">
        <input type="text" id="search-input" placeholder="Buscar eventos...">
        <button id="create-event-btn">Crear nuevo evento</button>
    </div>
</div>
<br><br>
<div class="container">
    <h1>Lista de Eventos</h1>
    <div class="info">
        <table border=0>
            <tr>
                <th>LOGO</th>
                <th>NOMBRE</th>
                <th>ACRONIMO</th>
                <th>ED.</th>
                <th>FECHA INICIO</th>
                <th>FECHA FIN</th>
                <th> </th>
            </tr>
            @foreach ($Eventos as $e)
            <tr>
                <td>
                    <img src="{{ asset('SGEA/public/assets/uploads/' . $e->img) }}" alt="logo" style="width: 150px;">
                </td>
                <td>{!!$e->nombre!!}</td>
                <td>{!!$e->acronimo!!}</td>
                <td>{!!$e->edicion!!}</td>
                <td>{!!$e->fecha_inicio!!}</td>
                <td>{!!$e->fecha_fin!!}</td>
                <td>
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
                </td>
            </tr>
            @endforeach
        </table>
    </div>
    <div id="events-list"></div>
    <div id="pagination"></div>
</div>

<div id="create-event-modal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Registro de Evento</h2>
        {!! Form::open(['url'=>'/eventos', 'enctype' => 'multipart/form-data']) !!}

        <label for="img">Seleccionar Imagen:</label>
        <input type="file" id="img" name="img" accept="image/png" required>

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
<!-- <script src="./js/script-eventos.js"></script> -->