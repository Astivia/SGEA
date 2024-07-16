@extends('layouts.master')
    <title>Articulos</title>
    <!-- <link rel="stylesheet" href="./css/style-articulos.css"> -->
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
                    <h2>Seleccionar Autores</h2>
                    <p>¿no encuentra su Autor?  <a href="#" id="register-author-btn">Registrar Autor</a></p>
                    <form id="author-form">
                        <label for="">Seleccione Autor:</label>
                        <select name="autor" id="selected-author">
                            @if($Autores=== null)
                                <option value="">Aun no se han registrado autores</option>
                            @else
                                <option value="{{Auth::user()->id}}">{{Auth::user()->nombre_completo}}</option>
                                @foreach ($Autores as $autor)
                                    @if($autor->usuario->id!==Auth::user()->id)
                                        <option value="{{ $autor->usuario->id }}">{{$autor->usuario->nombre_completo }}</option>
                                    @endif
                                @endforeach 
                            @endif
                        </select>
                        <button type="button" id="plus-author-btn"><i class="las la-plus-circle"></i></button>
                        <button type="button" id="minus-author-btn"><i class="las la-minus-circle"></i></button>
                        <br><br>
                        <Strong>Autores Seleccionados:</Strong>
                        <ul class="selectedAutors"></ul>
                        <br><br>
                        <button type="button" id="register-author-btn">Aceptar</button>
                    </form>

                  
                </div>
            </div>


            <div id="register-author-modal" class="modal">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <h2>Registro de Autores </h2>
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
    const selectedAuthorForm = document.getElementById('author-form');
    const selectedAuthorSelect = document.getElementById('selected-author');
    const selectedAuthorsList = document.querySelector('.selectedAutors');
    const registerAuthorButton = document.getElementById('register-author-btn');

    let selectedAuthors = []; // Array de datos

    selectedAuthorForm.addEventListener('click', (event) => {
        if (event.target.id === 'plus-author-btn') {
            const selectedValue = selectedAuthorSelect.value;

            // Validate selection
            if (!selectedValue) {
                alert('Por favor, seleccione un autor de la lista desplegable.');
                return; // Prevent further processing if no author is selected
            }

            // Check if author is already selected (optional optimization)
            if (selectedAuthors.includes(selectedValue)) {
                alert('El autor seleccionado ya se encuentra en la lista.');
                return;
            }

            // Add selected author to the array and display it in the list
            selectedAuthors.push(selectedValue);
            const newListItem = document.createElement('li');
            newListItem.textContent = selectedAuthorSelect.options[selectedAuthorSelect.selectedIndex].text;
            selectedAuthorsList.appendChild(newListItem);

            // Update the console to show the current vector of selected authors
            console.log('Selected authors:', selectedAuthors);
        }
    });

    // Optionally, handle form submission (if not handled elsewhere)
    registerAuthorButton.addEventListener('click', (event) => {
        // Implement your logic to submit the form data (including the selectedAuthors array)
        // ...

        // Prevent default form submission behavior if needed
        event.preventDefault();
    });
});
</script>

<script>
    //  const selectedAuthors = []; 
    //  const plusAuthorBtn = document.getElementById('plus-author-btn');
    //  const selectedAuthorSelect = document.getElementById('selected-author');
    //  const selectedAuthorsList = document.querySelector('.selectedAutors');

    //  plusAuthorBtn.addEventListener('click', () => {
    //     const selectedAuthorId = selectedAuthorSelect.value;
    //     const selectedAuthorText = selectedAuthorSelect.options[selectedAuthorSelect.selectedIndex].text;

    //     if (selectedAuthorId) {
    //         // Check if author is already selected
    //         if (!selectedAuthors.includes(selectedAuthorId)) {
    //         selectedAuthors.push({ id: selectedAuthorId, text: selectedAuthorText });

    //         // Add a new list item for the selected author
    //         const newListItem = document.createElement('li');
    //         newListItem.textContent = selectedAuthorText;
    //         selectedAuthorsList.appendChild(newListItem);

    //         console.log('Selected authors:', selectedAuthors); // Display the updated array in console
    //         } else {
    //         console.warn('Author already selected!');
    //         }
    //     }
    // });
    //////////////////////////////////////////MODALES////////////////////////////////////
    document.addEventListener('DOMContentLoaded', function () {
    // Obtener los modales y los botones
    var createArticleModal = document.getElementById('create-article-modal');
    var createAuthorModal = document.getElementById('create-author-modal');
    var registerAuthorModal = document.getElementById('register-author-modal');
    var createEventBtn = document.getElementById('create-event-btn');
    var addAuthorBtn = document.getElementById('add-author-btn');
    var registerAuthorBtn = document.getElementById('register-author-btn');
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
    registerAuthorBtn.addEventListener('click', function () {        
        createAuthorModal.style.display = 'none';
        registerAuthorModal.style.display = 'block';

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
