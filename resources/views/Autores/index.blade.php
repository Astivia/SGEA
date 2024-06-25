@extends('layouts.master')
<title>Autores</title>
<link rel="stylesheet" href="./css/styles.css">
</head>
@section('Content')
<div class="container">
    <h1>Autores</h1>
    <div class="search-create">
        <input type="text" id="search-input" placeholder="Buscar autores...">
        <button id="create-event-btn">Registrar Autor</button>
    </div>
</div>
<br><br>
<div class="container">
    <h1>Lista de Autores</h1>
    <div class="info">
        <table border=0>
            <tr>

                <th>PARTICIPANTE</th>
                <th>AFILIACION</th>
                <th>Controles</th>
            </tr>
            @foreach ($Autores as $autor)
            <tr>
                <td>{!!$autor->participante->ap_pat!!} {!!$autor->participante->ap_mat!!}
                    {!!$autor->participante->nombre!!}</td>
                <td>{!!$autor->afiliacion!!}</td>
                <td>
                    <a href="{!!'autores/'.$autor->id.'/edit'!!}">
                        <button>editar</button>
                    </a>
                    <a href="{{url('autores/'.$autor->id)}}"
                        onclick="event.preventDefault(); if (confirm('¿Estás seguro de que deseas eliminar este registro?')) { document.getElementById('delete-form-{{ $autor->id }}').submit(); }">
                        <button>Eliminar</button>
                    </a>
                    <form id="delete-form-{{ $autor->id }}" action="{{ url('autores/'.$autor->id) }}" method="POST"
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
        <h2>Registrar Autor</h2>
        {!! Form::open(['url'=>'/autores']) !!}
        <label for="evento">Seleccionar evento :</label>
        <select name="evento_id" require>
            @foreach ($Eventos as $evento)
            <option value="{{ $evento->id }}">{{ $evento->acronimo}} {{ $evento->edicion}}</option>
            @endforeach
        </select>

        <label for="participante">Seleccionar participante :</label>
        <select name="participante_id" require>
            @foreach ($Participantes as $participante)
            <option value="{{ $participante->id }}">{{ $participante->ap_pat}} {{ $participante->ap_mat}}
                {{ $participante->nombre}}</option>
            @endforeach
        </select>

        <label for="afiliacion">Afiliacion:</label>
        <input type="text" id="afiliacion" name="afiliacion" required>


        <button type="submit">Agregar Autor</button>
        {!!Form::close()!!}
    </div>
</div>

@endsection