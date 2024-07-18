@extends('layouts.master')
    <title>Articulos</title>
</head>
@section('Content')
    <div class="container">
        <div class="search-create">
            <h1 id="titulo-h1">Artículos</h1>
            <button id="create-event-btn"><i class="las la-plus-circle la-2x"></i></button>
        </div>    
      @if($Articulos->isEmpty())
            <strong>No hay datos</strong>
      @else
        <table id="example" class="display" style="width:100%">
            <thead>            
                <tr>
                    <th>TITULO</th>
                    <th>AUTORES</th>
                    <th>ESTADO</th>
                    <th>Controles</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($Articulos as $art)
                <tr>
                    <td><strong>{!!$art->titulo!!}</strong></td>
                    <td>Autor</td>
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
            </tbody>
        </table>
      @endif
    </div>
        
    <div id="create-article-modal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>Registro de Artículo</h2>
                {!! Form::open(['url' => '/articulos', 'enctype' => 'multipart/form-data', 'id' => 'article-form']) !!}
                <label for="titulo">Titulo:</label>
                <input type="text" id="titulo" name="titulo" required>
                
                <label for="desc">Resumen:</label>
                <textarea rows="4" cols="50" id="description" name="resumen"></textarea>
                
                <label for="area">Seleccionar Area :</label>
                <select name="area_id" required>
                    @foreach ($Areas as $area)
                        <option value="{{ $area->id }}">{{ $area->nombre }}</option>
                    @endforeach
                </select>

                <label for="pfd">Subir archivo pdf:</label>
                {!! Form::file('pdf', null, null) !!}
                
                <!-------------------------------------------------- AUTORES --------------------------------------------->
                <label for="">Seleccione Autor:</label>
                <select name="autor" id="selected-author">
                    @if($Autores=== null)
                        <option value="">Aun no se han registrado autores</option>
                    @else
                        <option value="">Seleccionar...</option>
                        <option value="{{ Auth::user()->id }}">{{ Auth::user()->nombre_completo }}</option>
                        @foreach ($Autores as $autor)
                            @if($autor->usuario->id !== Auth::user()->id)
                                <option value="{{ $autor->usuario->id }}">{{ $autor->usuario->nombre_completo }}</option>
                            @endif
                        @endforeach 
                    @endif
                </select>
                <button type="button" id="plus-author-btn">Agregar</button>
                <button type="button" id="minus-author-btn">Quitar</button>
                <br><br>
                <strong>Autores:</strong>
                <strong><ul class="selectedAutors"></ul></strong>
                <br><br>
                <p>¿No encuentra su Autor? <a href="#" id="register-author"><strong>Registrar Autor</strong></a></p>
                <br>
                <input type="hidden" name="selected_authors" id="selected-authors-input">
                <button type="submit">Guardar articulo</button>
            {!! Form::close() !!}

                </div>
            </div>

            <div id="register-author-modal" class="modal">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <h2>Registrar Autor </h2>
                    <form id="">                        
                        <label for="">CURP:</label>                        
                        <input type="text" id="" name="" required>
                        <label for="">Nombre:</label>
                        <input type="text" id="" name="" required>
                        <label for="">Apellido Paterno:</label>
                        <input type="text" id="" name="" required>
                        <label for="">Apellido Materno:</label>
                        <input type="text" id="" name="" required>
                        <label for="">Telefono:</label>
                        <input type="tel" id="" name="" required>
                        <label for="">Email:</label>
                        <input type="email" id="" name="" required>
                        <label for="">Institucion:</label>
                        <input type="text" id="" name="" required>
                        
                        <!-- Otros campos del autor -->
                        <button type="button" id="save-author-btn">Registrar Autor</button>
                        
                    </form>
                </div>
            </div>

@endsection

<script>
    document.addEventListener('DOMContentLoaded', (event) => {
    const articleForm = document.getElementById('article-form');
    const selectedAuthorSelect = document.getElementById('selected-author');
    const selectedAuthorsList = document.querySelector('.selectedAutors');
    const plusAuthorBtn = document.getElementById('plus-author-btn');
    const minusAuthorBtn = document.getElementById('minus-author-btn');
    const selectedAuthorsInput = document.getElementById('selected-authors-input');

    let selectedAuthors = []; // Array de datos

    plusAuthorBtn.addEventListener('click', () => {
        const selectedValue = selectedAuthorSelect.value;

        // Validar selección
        if (!selectedValue) {
            alert('Por favor, seleccione un autor de la lista desplegable.');
            return; // Evitar más procesamiento si no se selecciona ningún autor
        }

        // Verificar si el autor ya está seleccionado
        if (selectedAuthors.includes(selectedValue)) {
            alert('El autor seleccionado ya se encuentra en la lista.');
            return;
        }

        // Agregar autor seleccionado al array y mostrarlo en la lista
        selectedAuthors.push(selectedValue);
        const newListItem = document.createElement('li');
        newListItem.textContent = selectedAuthorSelect.options[selectedAuthorSelect.selectedIndex].text;
        newListItem.setAttribute('data-value', selectedValue); // Guardar el valor del autor en el atributo data-value
        selectedAuthorsList.appendChild(newListItem);

        // Actualizar la consola para mostrar el vector actual de autores seleccionados
        console.log('Autores seleccionados:', selectedAuthors);
    });

    minusAuthorBtn.addEventListener('click', () => {
        const selectedValue = selectedAuthorSelect.value;

        // Validar selección
        if (!selectedValue) {
            alert('Por favor, seleccione un autor de la lista desplegable.');
            return; // Evitar más procesamiento si no se selecciona ningún autor
        }

        // Buscar el índice del autor en el array
        const authorIndex = selectedAuthors.indexOf(selectedValue);
        if (authorIndex === -1) {
            alert('El autor seleccionado no está en la lista.');
            return;
        }

        // Eliminar autor del array
        selectedAuthors.splice(authorIndex, 1);

        // Eliminar autor de la lista (ul)
        const listItem = selectedAuthorsList.querySelector(`li[data-value="${selectedValue}"]`);
        if (listItem) {
            selectedAuthorsList.removeChild(listItem);
        }

        // Actualizar la consola para mostrar el vector actual de autores seleccionados
        console.log('Autores seleccionados:', selectedAuthors);
    });

    // Actualizar el campo oculto con el array de autores seleccionados antes de enviar el formulario
    articleForm.addEventListener('submit', (event) => {
        selectedAuthorsInput.value = JSON.stringify(selectedAuthors);
        console.log('Campo oculto:', selectedAuthorsInput.value); // Verificar valor en consola
    });
});

</script>

<script>

    document.addEventListener('DOMContentLoaded', function () {
        // Obtener los modales y los botones
        var createEventBtn = document.getElementById('create-event-btn');
        var createArticleModal = document.getElementById('create-article-modal');
        var registerAuthorModal = document.getElementById('register-author-modal');
        
        var registerAuthorBtn = document.getElementById('register-author-btn'); //enlace al modal de registro de autor
        var saveAuthorBtn = document.getElementById('save-author-btn'); //boton para guardar autos
        var closeButtons = document.querySelectorAll('.modal .close');

        // Abrir el modal de creación de artículo
        createEventBtn.addEventListener('click', function () {
            createArticleModal.style.display = 'block';
        });

        //Abrir Modal de Creacion de Autor
        registerAuthorBtn.addEventListener('click', function () {        
            createArticleModal.style.display = 'none';
            registerAuthorModal.style.display = 'block';

        });

        // 
        saveAuthorBtn.addEventListener('click', function () {
            // Aquí puedes agregar el código para guardar el autor temporalmente
            // y luego agregarlo al formulario del artículo.

            registerAuthorModal.style.display = 'none';
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
            } else if (event.target == registerAuthorModal) {
                registerAuthorModal.style.display = 'none';
            }
        });
    });
</script>
