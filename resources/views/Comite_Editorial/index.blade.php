@extends('layouts.master')
<title>Comite Editorial</title>
<link rel="stylesheet" href="./css/styles.css">
</head>
</head>
@section('Content')
<div class="container">
    <h1>Comite Editorial</h1>
    <div class="search-create">
        <input type="text" id="search-input" placeholder="Buscar participante...">
        <button id="create-event-btn">Agregar Participante</button>
    </div>
</div>
<br><br>
<div class="container">
    <h1>Participantes del Comite Editorial</h1>
    <div class="info">
        <table border=0>
            <tr>
                <th>EVENTO</th>
                <th>PARTICIPANTE</th>
                <th>Controles</th>
            </tr>
            @foreach ($Comite as $ce)
            <tr>
                <td>{!!$ce->evento->acronimo!!} {!!$ce->evento->edicion!!}</td>
                <td>{!!$ce->participante->nombre!!} {!!$ce->participante->ap_pat!!} {!!$ce->participante->ap_mat!!}</td>
                <td>
                    <a href="{!!'comite_editorial/'.$ce->id.'/edit'!!}">
                        <button>editar</button>
                    </a>
                    <a href="{{url('comite_editorial/'.$ce->id)}}"
                        onclick="event.preventDefault(); if (confirm('¿Estás seguro de quitar a esta persona del Comite Editorial?')) { document.getElementById('delete-form-{{ $ce->id }}').submit(); }">
                        <button>Eliminar</button>
                    </a>
                    <form id="delete-form-{{ $ce->id }}" action="{{ url('comite_editorial/'.$ce->id) }}" method="POST"
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
        <h2>Añadir al Comite</h2>
        {!! Form::open(['url'=>'/comite_editorial']) !!}
        <label for="evento">Seleccionar evento :</label>
        <select name="evento_id" require>
            @foreach ($Eventos as $evento)
            <option value="{{ $evento->id }}">{{ $evento->acronimo}} {{ $evento->edicion}}</option>
            @endforeach
        </select>

        <label for="participante">Seleccionar participante :</label>
        <select name="participante_id" require>
            @foreach ($Participantes as $participante)
            <option value="{{ $participante->id }}">{{ $participante->nombre}} {{ $participante->ap_pat}} {{ $participante->ap_mat}}</option>
            @endforeach
        </select>


        <button type="submit">Agregar al Comite</button>
        {!!Form::close()!!}
    </div>
</div>


@endsection