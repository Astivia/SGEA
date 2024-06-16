@extends('layouts.master')
    <title>CiDiCi</title>
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
    <link rel="stylesheet" href="./css/style-home.css">
    <link rel="stylesheet" href="./css/style-articulos.css">
</head>


@section('Content')
    @if(session('error'))
        <script>
        alert('{{ session('error') }}');
        </script>
    @endif

    @if(session('success'))
        <script>
        alert('{{ session('success') }}');
        </script>
    @endif
    
    <div class="main-content">
        <header>
            <div class="menu-toggle">
                <label for=""><span class="las la-bars"></span></label>
            </div>
            <div class="header-icons">
                <span class="las la-search"></span>
                <span class="las la-bookmarks"></span>
                <span class="las la-sms"></span>
            </div>
        </header>
        <main>
            <div class="container">
                <h1>Artículos</h1>
                <div class="search-create">
                    <input type="text" id="search-input" placeholder="Buscar artículos...">
                    <button id="create-article-btn">Registrar Artículo</button>
                </div>
                <div id="articles-list"></div>
                <div id="pagination"></div>
            </div>

            <div class="container">
                <h1>Lista de Articulos</h1>
                <div class="info">
                    <table border=0>
                        <tr>
                            <th>EVENTO</th>
                            <th>TITULO</th>
                            <th>AREA</th>
                            <th>AUTOR</th>
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
        
            <div id="register-modal" class="modal">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <p>Se ha registrado correctamente.</p>
                </div>
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
        
            <div id="view-article-modal" class="modal">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <h2>Detalle del Artículo</h2>
                    <div id="article-details"></div>
                    <button id="edit-article-btn">Editar</button>
                </div>
            </div>
        
            <div id="edit-article-modal" class="modal">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <h2>Editar Artículo</h2>
                    <form id="edit-article-form">
                        <label for="edit-article-author-photo">Foto del Autor:</label>
                        <input type="text" id="edit-article-author-photo" placeholder="URL de la imagen" required>
                        <label for="edit-article-title">Título del Artículo:</label>
                        <input type="text" id="edit-article-title" required>
                        <label for="edit-article-description">Descripción:</label>
                        <textarea id="edit-article-description" required></textarea>
                        <label for="edit-article-reviewers-count">Cantidad de Revisores:</label>
                        <input type="number" id="edit-article-reviewers-count" min="1" required>
                        <div id="edit-reviewers-container"></div>
                        <label for="edit-article-publication-date">Fecha de Publicación:</label>
                        <input type="date" id="edit-article-publication-date" required>
                        <button type="submit">Actualizar Artículo</button>
                    </form>
                </div>
            </div>
        </main>
    </div>

@endsection
    <script src="./js/scriptArticulos.js"></script>
</html>