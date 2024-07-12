@extends('layouts.master')
    <title>Articulos</title>
    <!-- <link rel="stylesheet" href="./css/style-articulos.css"> -->
</head>
@section('Content')
    <div class="container">
        <h1>Artículos</h1>
        <div class="search-create">
            <input type="text" id="search-input" placeholder="Buscar artículos...">
            <button id="create-event-btn"><i class="las la-plus-circle la-2x"></i></button>
            
        </div>
    </div>

    <br><br>

    <div class="container">
      @if($Articulos->isEmpty())
            <strong>No hay datos</strong>
      @else
        <div class="info">
            <table border=0>
                <tr>
                    <th>EVENTO</th>
                    <th>TITULO</th>
                    <th>AUTORES</th>
                    <th>ESTADO</th>
                    <th>Controles</th>
                </tr>
                @foreach ($Articulos as $art)
                <tr>
                    <td>{!!$art->evento->acronimo!!} {!!$art->evento->edicion!!}</td>
                    <td><strong>{!!$art->titulo!!}</strong></td>
                    <td></td>
                    <td>{!!$art->estado!!}</td>
                    <td>
                    <a href="{!! url('articulos/'.$art->evento_id.'/'.$art->id) !!}"><i class="las la-info-circle la-2x"></i></a>

                        <a href="{!!'articulos/'.$art->evento_id.'/'.$art->id.'/edit'!!}">
                         <i class="las la-edit la-2x"></i>
                        </a>
                        <a href="{{url('articulos/'.$art->id)}}" onclick="event.preventDefault(); if (confirm('¿Estás seguro de que deseas eliminar este registro?')) { document.getElementById('delete-form-{{ $art->id }}').submit(); }">
                        <i class="las la-trash-alt la-2x"></i>
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
      @endif
    </div>
        
        <div id="create-article-modal" class="modal">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <h2>Registro de Artículo</h2>
                    {!! Form::open(['url'=>'/articulos','enctype' => 'multipart/form-data']) !!}
                        
                        <label for="titulo">Titulo:</label>
                        <input type="text" id="titulo" name="titulo" required>
                        
                        <label for="desc">Resumen:</label>
                        <textarea rows="4" cols="50" id="description" name="resumen" ></textarea>
                        
                        <label for="area">Seleccionar Area :</label>
                        <select name="area_id" required>
                            @foreach ($Areas as $area)
                                <option value="{{ $area->id }}">{{ $area->nombre }}</option>
                            @endforeach
                        </select>
                        <br><br>
                        <label for="pfd">Subir archivo pdf:</label>
                        {!! Form::file('pdf',null,null)!!}
                        <br><br>
                        <button type="button" id="add-author-btn">Agregar Autor</button>
                        
                        <button type="submit">Guardar articulo</button>
                    {!! Form::close() !!}
                </div>
            </div>

            <div id="create-author-modal" class="modal">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <h2>Seleccionar Autor</h2>
                    <form id="author-form">
                        <label for="usuario_id">ID del Usuario:</label>
                        <input type="text" id="usuario_id" name="usuario_id" required>
                        <label for="institucion">Institución:</label>
                        <input type="text" id="institucion" name="institucion" required>
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" required>
                        <!-- Otros campos del autor -->
                        <button type="button" id="save-author-btn">Guardar Autor</button>
                    </form>
                </div>
            </div>


@endsection


<script>
    document.addEventListener('DOMContentLoaded', function () {
    // Obtener los modales y los botones
    var createArticleModal = document.getElementById('create-article-modal');
    var createAuthorModal = document.getElementById('create-author-modal');
    var createEventBtn = document.getElementById('create-event-btn');
    var addAuthorBtn = document.getElementById('add-author-btn');
    var saveAuthorBtn = document.getElementById('save-author-btn');
    var closeButtons = document.querySelectorAll('.modal .close');

    // Abrir el modal de creación de artículo
    createEventBtn.addEventListener('click', function () {
        createArticleModal.style.display = 'block';
    });

    // Abrir el modal de creación de autor y ocultar el de artículo
    addAuthorBtn.addEventListener('click', function () {
        createArticleModal.style.display = 'none';
        createAuthorModal.style.display = 'block';
    });

    // Guardar el autor y regresar al modal de artículo
    saveAuthorBtn.addEventListener('click', function () {
        // Aquí puedes agregar el código para guardar el autor temporalmente
        // y luego agregarlo al formulario del artículo.

        createAuthorModal.style.display = 'none';
        createArticleModal.style.display = 'block';
    });

    // Cerrar los modales al hacer clic en la 'X'
    closeButtons.forEach(function (closeBtn) {
        closeBtn.addEventListener('click', function () {
            closeBtn.parentElement.parentElement.style.display = 'none';
        });
    });

    // Cerrar el modal al hacer clic fuera del contenido del modal
    window.addEventListener('click', function (event) {
        if (event.target == createArticleModal) {
            createArticleModal.style.display = 'none';
        } else if (event.target == createAuthorModal) {
            createAuthorModal.style.display = 'none';
        }
    });
});
</script>
