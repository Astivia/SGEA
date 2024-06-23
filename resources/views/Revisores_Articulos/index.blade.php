@extends('layouts.master')
    <title>Revisores de articulos</title>
    <link rel="stylesheet" href="./css/styles.css">
</head>
@section('Content')
        <div class="container">
            <h1>Revisores de Articulos</h1>
            <div class="search-create">
                <input type="text" id="search-input" placeholder="Buscar eventos...">
                <button id="create-event-btn">Crear nuevo revisor</button>
            </div>
            <div id="events-list"></div>
            <div id="pagination"></div>
        </div>

        <div class="container">
            <h1>Revisores</h1>
            <div class="info">
                <table border=0>
                    <tr>
                        <th>REVISOR</th>
                        <th>ARTICULO</th>
                        <th>AREA</th>
                        <th>Controles</th>
                    </tr>
                     @foreach ($RevArt as $ra)
                    <tr>
                        <td>{!!$ra->participante->nombre!!} {!!$ra->participante->apellidos!!}</td>
                        <td>{!!$ra->articulo->titulo!!}</td>
                        <td>{!!$ra->articulo->area->nombre!!}</td>
                        
                        <td>
                            <a href="{!!'revisores_articulos/'.$ra->id.'/edit'!!}">
                                    <button>editar</button>
                                </a>
                                <a href="{{url('revisores_articulos/'.$ra->id)}}" 
                                    onclick="
                                    event.preventDefault(); 
                                    if (confirm('¿Estás seguro de que deseas eliminar este Revisor?')) 
                                    { document.getElementById('delete-form-{{ $ra->id }}').submit(); }">
                                <button>Eliminar</button>
                                </a>
                                <form id="delete-form-{{ $ra->id }}" action="{{ url('revisores_articulos/'.$ra->id) }}" method="POST" style="display: none;">
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
                <h2>Registro de Revisor</h2>
                {!! Form::open(['url'=>'/revisores_articulos']) !!}
                    <label for="participante">Seleccionar participante :</label>
                        <select name="participante_id" require>
                        @foreach ($Participantes as $participante)
                            <option value="{{ $participante->id }}">{{ $participante->nombre}} {{ $participante->apellidos}}</option>
                        @endforeach
                    </select>

                    <label for="articulo">Seleccionar Articulo:</label>
                        <select name="articulo_titulo" require>
                        @foreach ($Articulos as $art)
                            <option value="{{ $art->titulo }}">{{ $art->titulo}} </option>
                        @endforeach
                    </select>
                    <br><br>
                    <button type="submit">Registrar Revisor</button>
                {!!Form::close()!!} 
            </div>
        </div>
        

    @endsection
    <script src="./js/script-eventos.js"></script>
