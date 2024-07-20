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
                    <td><strong>{{ $art->titulo }}</strong></td>
                    <td>
                        <ul>
                            @foreach ($art->autores as $autor)
                                <li>{{ $autor->orden }}. {{ $autor->usuario->nombre_completo }}</li>
                            @endforeach
                        </ul>
                    </td>
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
                <ul class="selectedAutors"></ul>
                <br><br>
                <p>¿No encuentra su Autor? <a href="#" id="register-author-btn"><strong>Registrar Autor</strong></a></p>
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
                    <form id="register-author-form" metod="POST" action="{{route('inicia-sesion')}}">  
                      @csrf  
                       <input type="hidden" name="id" id="id">                    
                        <label for="">CURP:</label>                        
                        <input type="text" id="" name="curp" required>
                        <label for="">Nombre:</label>
                        <input type="text" id="" name="nombre" required>
                        <label for="">Apellido Paterno:</label>
                        <input type="text" id="" name="ap_paterno" required>
                        <label for="">Apellido Materno:</label>
                        <input type="text" id="" name="ap_materno" required>
                        <label for="">Email:</label>
                        <input type="email" id="" name="email" required>
                        <label for="">Telefono:</label>
                        <input type="tel" id="" name="telefono" required>
                        <label for="">Institucion:</label>
                        <input type="text" id="" name="intitucion" required>

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

        const createArticleModal = document.getElementById('create-article-modal');
        const registerAuthorModal = document.getElementById('register-author-modal');

        let selectedAuthors = [];

        const updateAuthorList = () => {
            selectedAuthorsList.innerHTML = '';
            selectedAuthors.forEach((author, index) => {
                const newListItem = document.createElement('li');
                const checkbox = document.createElement('input');
                checkbox.type = 'checkbox';
                checkbox.name = 'corresponding_author';
                checkbox.checked = author.corresponding;
                checkbox.addEventListener('change', () => {
                    selectedAuthors.forEach(a => a.corresponding = false);
                    author.corresponding = checkbox.checked;
                    updateAuthorList();
                });

                newListItem.textContent = `${index + 1}. ${author.name} `;
                newListItem.prepend(checkbox);
                newListItem.setAttribute('data-value', author.id);
                selectedAuthorsList.appendChild(newListItem);
            });

            const anyChecked = selectedAuthors.some(a => a.corresponding);
            selectedAuthorsList.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
                if (!checkbox.checked) {
                    checkbox.disabled = anyChecked;
                }
            });
        };

        const updateSelectedAuthorsInput = () => {
            const selectedAuthorsData = selectedAuthors.map(author => ({
                id: author.id,
                corresponding: author.corresponding
            }));
            selectedAuthorsInput.value = JSON.stringify(selectedAuthorsData);
            console.log('Campo oculto:', selectedAuthorsInput.value);
        };

        plusAuthorBtn.addEventListener('click', () => {
            const selectedValue = selectedAuthorSelect.value;
            const selectedText = selectedAuthorSelect.options[selectedAuthorSelect.selectedIndex].text;

            if (!selectedValue) {
                alert('Por favor, seleccione un autor de la lista desplegable.');
                return;
            }

            if (selectedAuthors.find(author => author.id === selectedValue)) {
                alert('El autor seleccionado ya se encuentra en la lista.');
                return;
            }

            fetch('{{ route('revisar-existencia') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ author_id: selectedValue })
            })
            .then(response => response.json())
            .then(data => {
                if (!data.exists) {
                    createArticleModal.style.display = 'none';
                    registerAuthorModal.style.display = 'block';
                    if (data.user) {
                        document.querySelector('input[name="id"]').value = data.user.id || '';
                        document.querySelector('input[name="curp"]').value = data.user.curp || '';
                        document.querySelector('input[name="nombre"]').value = data.user.nombre || '';
                        document.querySelector('input[name="ap_paterno"]').value = data.user.ap_paterno || '';
                        document.querySelector('input[name="ap_materno"]').value = data.user.ap_materno || '';
                        document.querySelector('input[name="telefono"]').value = data.user.telefono || '';
                        document.querySelector('input[name="email"]').value = data.user.email || '';
                    }
                } else {
                    selectedAuthors.push({ id: selectedValue, name: selectedText, corresponding: false });
                    updateAuthorList();
                    updateSelectedAuthorsInput();
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });

        minusAuthorBtn.addEventListener('click', () => {
            const selectedValue = selectedAuthorSelect.value;

            if (!selectedValue) {
                alert('Por favor, seleccione un autor de la lista desplegable.');
                return;
            }

            const authorIndex = selectedAuthors.findIndex(author => author.id === selectedValue);
            if (authorIndex === -1) {
                alert('El autor seleccionado no está en la lista.');
                return;
            }

            selectedAuthors.splice(authorIndex, 1);
            updateAuthorList();
            updateSelectedAuthorsInput();
        });

        articleForm.addEventListener('submit', (event) => {
            updateSelectedAuthorsInput();
        });

        document.getElementById('save-author-btn').addEventListener('click', () => {
            const newAuthorId = document.querySelector('input[name="id"]').value;
            const newAuthorName = `${document.querySelector('input[name="nombre"]').value} ${document.querySelector('input[name="ap_paterno"]').value} ${document.querySelector('input[name="ap_materno"]').value}`;

            if (!newAuthorId) {
                alert('El campo CURP es obligatorio.');
                return;
            }

            selectedAuthors.push({ id: newAuthorId, name: newAuthorName, corresponding: false });
            updateAuthorList();
            updateSelectedAuthorsInput();

            registerAuthorModal.style.display = 'none';
            createArticleModal.style.display = 'block';
        });

        var createEventBtn = document.getElementById('create-event-btn');
        var registerAuthorBtn = document.getElementById('register-author-btn');
        var saveAuthorBtn = document.getElementById('save-author-btn');
        var closeButtons = document.querySelectorAll('.modal .close');

        createEventBtn.addEventListener('click', function () {
            createArticleModal.style.display = 'block';
        });

        registerAuthorBtn.addEventListener('click', function () {        
            createArticleModal.style.display = 'none';
            registerAuthorModal.style.display = 'block';
        });

        saveAuthorBtn.addEventListener('click', function () {
            registerAuthorModal.style.display = 'none';
            createArticleModal.style.display = 'block';
        });

        closeButtons.forEach(function (closeBtn) {
            closeBtn.addEventListener('click', function () {
                closeBtn.parentElement.parentElement.style.display = 'none';
            });
        });

        window.addEventListener('click', function (event) {
            if (event.target == createArticleModal) {
                createArticleModal.style.display = 'none';
            } else if (event.target == registerAuthorModal) {
                registerAuthorModal.style.display = 'none';
            }
        });
    });
</script>




