@extends('layouts.master')
    <title>Articulos</title>
    <!-- <link rel="stylesheet" href="./css/style-articulos.css"> -->
</head>
@section('Content')
    <div class="container">
        <h1>Artículos</h1>
        <div class="search-create">
            <input type="text" id="search-input" placeholder="Buscar artículos...">
            <button id="create-event-btn">Registrar Articulo</button>
            
        </div>
        <div id="articles-list"></div>
        <div id="pagination"></div>
    </div>
    <br><br>

    <div class="container">
        <h1>Lista de Articulos</h1>
        <div class="info">
            <table border=0>
                <tr>
                    <th>EVENTO</th>
                    <th>TITULO</th>
                    <th>AREA</th>
                    <th>AUTOR</th>
                    <th>Controles</th>
                </tr>
                @foreach ($Articulos as $art)
                <tr>
                    <td>{!!$art->articulo->evento->acronimo!!} {!!$art->articulo->evento->edicion!!}</td>
                    <td>{!!$art->articulo->titulo!!}</td>
                    <td>{!!$art->articulo->area->nombre!!}</td>
                    <td>{!!$art->autor->participante->nombre!!} {!!$art->autor->participante->apellidos!!}</td>

                    <td>
                        <a href="{!!'articulos/'.$art->id.'/edit'!!}">
                            <button>editar</button>
                        </a>
                        <a href="{{url('articulos/'.$art->id)}}" onclick="event.preventDefault(); if (confirm('¿Estás seguro de que deseas eliminar este registro?')) { document.getElementById('delete-form-{{ $art->id }}').submit(); }">
                        <button>Eliminar</button>
                        </a>
                        <form id="delete-form-{{ $art->id }}" action="{{ url('articulos/'.$art->id) }}" method="POST" style="display: none;">
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
        
        
            <div id="create-article-modal" class="modal">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <h2>Registro de Artículo</h2>
                    {!! Form::open(['url'=>'/articulos']) !!}
                        <label for="titulo">Titulo:</label>
                        <input type="text" id="titulo" name="titulo" required>

                        <label for="evento">Seleccionar evento :</label>
                        <select name="evento_id" require>
                        @foreach ($Eventos as $evento)
                            <option value="{{ $evento->id }}">{{ $evento->acronimo}} {{ $evento->edicion}}</option>
                        @endforeach
                        </select>

                        <label for="area">Seleccionar Area :</label>
                        <select name="area_id" require>
                        @foreach ($Areas as $area)
                            <option value="{{ $area->id }}">{{ $area->nombre}}</option>
                        @endforeach
                        </select>

                        <label for="autor">Seleccionar Autor :</label>
                        <select name="autor_id" require>
                        @foreach ($Autores as $autor)
                            <option value="{{ $autor->id }}">{{ $autor->participante->nombre}} {{ $autor->participante->apellidos}}</option>
                        @endforeach
                        </select>

                        <button type="submit">Guardar articulo</button>
                    {!!Form::close()!!} 
                </div>
            </div>
@endsection
<script src="./js/script-articulos.js"></script>