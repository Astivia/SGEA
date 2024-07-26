@extends('layouts.master')
    <title>Articulos</title>
@section('Content')
    <div class="container">
        <div class="search-create">
            <h1 id="titulo-h1">Artículos</h1>
            <button id="create-btn"><i class="las la-plus-circle la-2x"></i></button>
        </div>    
        @if($Articulos->isEmpty())
            <strong>No hay datos</strong>
        @else
        <!-- <div style="overflow-x:auto; overflow-y:auto; max-height:500px;"> -->
            <table id="example" class="display  responsive nowrap" style="width:100%">
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
                                    <li>{{ $autor->orden }}. {{ $autor->usuario->nombre_autor}} <a href="{!! 'usuarios/'.$autor->usuario->id !!}"><i class="las la-info-circle la-1x"></i></a></li>
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
        </div>
      @endif
    </div>
        
    <div id="create-modal" class="modal">
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
                    <option value="">Seleccionar...</option>
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
                <span id="curp-error" style="color:red; display:none;">No se encontró el usuario, favor de llenar todos los campos</span>
                <input type="hidden" name="id" id="id">
                <label for="curp">CURP:</label>
   
                <input type="text" id="curp" name="curp" required>
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" required>
                <label for="ap_paterno">Apellido Paterno:</label>
                <input type="text" id="ap_paterno" name="ap_paterno" required>
                <label for="ap_materno">Apellido Materno:</label>
                <input type="text" id="ap_materno" name="ap_materno" required>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
                <label for="telefono">Teléfono:</label>
                <input type="tel" id="telefono" name="telefono" required>
                <label for="institucion">Institución:</label>
                <input type="text" id="institucion" name="institucion" required>
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

        const createArticleModal = document.getElementById('create-modal');
        const registerAuthorModal = document.getElementById('register-author-modal');

        let selectedAuthors = [];
        

        const updateAuthorList = () => {
            selectedAuthorsList.innerHTML = '';
            selectedAuthors.forEach((author, index) => {
                const checkbox = document.createElement('input');
                const newListItem = document.createElement('li');
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
                id: author.id.toString(),
                corresponding: author.corresponding,
                institucion: author.institucion
            }));
            selectedAuthorsInput.value = JSON.stringify(selectedAuthorsData);
            console.log('Campo oculto:', selectedAuthorsInput.value);
        };

        function resetRegisterAuthorForm() {
            const registerAuthorForm = document.getElementById('register-author-form');
            //reseteamos los valores de los imputs
            registerAuthorForm.reset();
            //habilitamos los campos 
            document.querySelectorAll('#register-author-form input').forEach(input => {
                input.disabled = false;
            });
        }
        

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

                        // Bloquear los campos
                        document.querySelectorAll('#register-author-form input').forEach(input => {
                            if (input.name !== 'institucion') {
                                input.disabled = true;
                            }
                        });
                    }
                } else {
                    selectedAuthors.push({ id: selectedValue, name: selectedText, corresponding: false, institucion: data.user.institucion });
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
            const hasCorrespondingAuthor = selectedAuthors.some(author => author.corresponding);
            if (!hasCorrespondingAuthor) {
                alert('Seleccione un autor de correspondencia.');
                event.preventDefault();
                return
            }else{
                updateSelectedAuthorsInput();
            }
        });

        

        document.getElementById('save-author-btn').addEventListener('click', async () => {
            let newAuthorId = document.querySelector('input[name="id"]').value || '';
            const newAuthorCurp = document.querySelector('input[name="curp"]').value || '';
            const newAuthorNombre = document.querySelector('input[name="nombre"]').value || '';
            const newAuthorApPaterno = document.querySelector('input[name="ap_paterno"]').value || '';
            const newAuthorApMaterno = document.querySelector('input[name="ap_materno"]').value || '';
            const newAuthorTelefono = document.querySelector('input[name="telefono"]').value || '';
            const newAuthorEmail = document.querySelector('input[name="email"]').value || '';
            const newAuthorInstitucion = document.querySelector('input[name="institucion"]').value || '';

            const newAuthorName = `${newAuthorApPaterno} ${newAuthorApMaterno} ${newAuthorNombre}`;

            if (!newAuthorCurp || !newAuthorNombre || !newAuthorApPaterno || !newAuthorApMaterno || !newAuthorTelefono || !newAuthorEmail || !newAuthorInstitucion) {
                alert('Todos los campos son obligatorios.');
                return;
            }

            const authorData = {
                id: newAuthorId, curp: newAuthorCurp, nombre: newAuthorNombre,
                ap_paterno: newAuthorApPaterno, ap_materno: newAuthorApMaterno, telefono: newAuthorTelefono, email: newAuthorEmail, institucion: newAuthorInstitucion
            };

            if (newAuthorId === "" || newAuthorId === null) {
                try {
                    const response = await fetch('{{ route('insertar-usuario') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ authorData })
                    });
                    const data = await response.json();
                    if (data.error) {
                        alert(data.error);
                        return;
                    } else {
                        newAuthorId = data.id.toString();
                    }
                } catch (error) {
                    console.error('Error:', error);
                    return;
                }
            }

            // Verificar si el autor ya existe en el combo o en el array
            const isAuthorInArray = selectedAuthors.some(author => author.id === newAuthorId);
            const isAuthorInSelect = Array.from(selectedAuthorSelect.options).some(option => option.value === newAuthorId);

            if (isAuthorInArray) {
                alert('El autor ya existe.');
                return;
            } else {
                if(isAuthorInSelect===false){
                     // agregar el nuevo autor al combo de selección
                    const newOption = document.createElement('option');
                    newOption.value = newAuthorId;
                    newOption.text = newAuthorName;
                    selectedAuthorSelect.appendChild(newOption);
                }
                // agregar el autor al array
                selectedAuthors.push({ id: newAuthorId, name: newAuthorName, corresponding: false, institucion: newAuthorInstitucion });
                updateAuthorList();
                updateSelectedAuthorsInput();
                // manejo de modales
                registerAuthorModal.style.display = 'none';
                createArticleModal.style.display = 'block';
                resetRegisterAuthorForm();
            }
        });
  
        var registerAuthorBtn = document.getElementById('register-author-btn');
        var saveAuthorBtn = document.getElementById('save-author-btn');

        registerAuthorBtn.addEventListener('click', function () {        
            createArticleModal.style.display = 'none';
            registerAuthorModal.style.display = 'block';
        });

        saveAuthorBtn.addEventListener('click', function () {
            registerAuthorModal.style.display = 'none';
            createArticleModal.style.display = 'block';
        });
        updateAuthorList();
        updateSelectedAuthorsInput();

    });

</script>

<script>
    document.addEventListener('DOMContentLoaded', (event) => {
        const curpInput = document.getElementById('curp');
        const idInput = document.getElementById('id');
        const nombreInput = document.getElementById('nombre');
        const apPaternoInput = document.getElementById('ap_paterno');
        const apMaternoInput = document.getElementById('ap_materno');
        const emailInput = document.getElementById('email');
        const telefonoInput = document.getElementById('telefono');
        const institucionInput = document.getElementById('institucion');
        const curpError = document.getElementById('curp-error');

        const clearForm = () => {
            idInput.value = '';
            curpInput.value = '';
            nombreInput.value = '';
            apPaternoInput.value = '';
            apMaternoInput.value = '';
            emailInput.value = '';
            telefonoInput.value = '';
            institucionInput.value = '';

            // Desbloquear todos los campos
            nombreInput.disabled = false;
            apPaternoInput.disabled = false;
            apMaternoInput.disabled = false;
            emailInput.disabled = false;
            telefonoInput.disabled = false;
        };

        curpInput.addEventListener('input', () => {
            if (curpInput.value.length === 18) {
                fetch('{{ route('verify-curp') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ curp: curpInput.value })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'exists') {
                        const user = data.user;
                        idInput.value = user.id;
                        nombreInput.value = user.nombre;
                        apPaternoInput.value = user.ap_paterno;
                        apMaternoInput.value = user.ap_materno;
                        emailInput.value = user.email;
                        telefonoInput.value = user.telefono;
                        
                         // Bloquear los campos
                        document.querySelectorAll('#register-author-form input').forEach(input => {
                            if (input.name !== 'institucion') {
                                input.disabled = true;
                            }
                            if (input.name == 'curp') {
                                input.disabled = false;
                            }
                        });

                        curpError.style.display = 'none';
                    } else {
                        idInput.value = '';
                        nombreInput.value = '';
                        apPaternoInput.value = '';
                        apMaternoInput.value = '';
                        emailInput.value = '';
                        telefonoInput.value = '';
                        institucionInput.value = '';

                        // Desbloquear todos los campos
                        nombreInput.disabled = false;
                        apPaternoInput.disabled = false;
                        apMaternoInput.disabled = false;
                        emailInput.disabled = false;
                        telefonoInput.disabled = false;
                        curpError.style.display = 'block';

                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            } else {
                curpError.style.display = 'none';
            }
        });

        

        const registerAuthorModal = document.getElementById('register-author-modal');
        const closeButtons = document.querySelectorAll('.modal .close');

        closeButtons.forEach(function (closeBtn) {
            closeBtn.addEventListener('click', function () {
                clearForm();
                closeBtn.parentElement.parentElement.style.display = 'none';
            });
        });

        window.addEventListener('click', function (event) {
            if (event.target == registerAuthorModal) {
                clearForm();
                registerAuthorModal.style.display = 'none';
            }
        });
    });
</script>