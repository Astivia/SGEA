@extends('layouts.master')
    <title>Modificar Datos</title>
</head>
@section('Content')
    <div class="container">
        <h1>MODIFICAR ARTICULO</h1>

        {!! Form::open(['method'=>'PUT','url'=>'/articulos/'.$articulo->evento_id.'/'.$articulo->id, 'files' => true,'id' => 'edit-form']) !!}
            <label for="titulo"><strong>Titulo:</strong></label>
            {!! Form::text ('titulo',$articulo->titulo)!!}

            <label for="desc"><strong>Resumen:</strong></label>
            <textarea rows="4" cols="50" id="description" name="resumen" >{!!$articulo->resumen!!}</textarea>
            
            <label for="area"><strong>Area : </strong>
                <select name="area_id" require>
                    @foreach ($Areas as $area)
                        <option value="{{ $area->id }}"{{ $area->id == $articulo->area_id ?'selected':''}}>
                            {{ $area->nombre}}
                        </option>
                    @endforeach
                </select>
            </label>
            <strong>Estado:</strong>
            {!! Form::text ('estado', $articulo->estado) !!}
            <br><br>
            <p><Strong>Archivo actual: </Strong>{!!$articulo->archivo!!}</p>
            <br>
            <div class="form-group">
                <label for="nuevo_archivo_pdf">Para cambiar el archivo, seleccione un nuevo archivo</label>
                <input type="file" class="form-control" id="archivo" name="archivo">
            </div>
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
                    <strong>Autores:</strong><br><br>
                    <ul class="selectedAutors">
                        @foreach ($autores as $index => $autor)
                            <input type="checkbox" name="corresponding_author" {{ $autor->correspondencia ? 'checked' : '' }}>
                            <li> 
                                {!! $autor->orden !!}. {!! $autor->usuario->nombre_completo !!}
                                
                            </li>
                        @endforeach
                    </ul>
                    <br><br>
                    <p>¿No encuentra su Autor? <a href="#" id="register-author-btn"><strong>Registrar Autor</strong></a></p>
                    <br>
            <br><br>
            <input type="hidden" name="selected_authors" id="selected-authors-input">
            <button type="submit" id="create-event-btn">Guardar Cambios</button>
        {!!Form::close()!!}   
        <a href="{{ url('articulos') }}"><button>Cancelar</button></a> 
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
        const UpdateForm = document.getElementById('edit-form');
        const selectedAuthorSelect = document.getElementById('selected-author');
        const selectedAuthorsList = document.querySelector('.selectedAutors');
        const plusAuthorBtn = document.getElementById('plus-author-btn');
        const minusAuthorBtn = document.getElementById('minus-author-btn');
        const selectedAuthorsInput = document.getElementById('selected-authors-input');
        let selectedAuthors = [];

        @foreach ($autores as $index => $autor)
            selectedAuthors.push({
                id: '{{ $autor->usuario->id }}',
                name: '{{ $autor->usuario->nombre_completo }}',
                corresponding: {{ $autor->correspondencia ? 'true' : 'false' }},
                institucion: '{{ $autor->institucion }}'
            });
        @endforeach

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
                id: author.id,
                corresponding: author.corresponding,
                institucion: author.institucion
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
                    registerAuthorModal.style.display = 'block';
                    if (data.user) {
                        document.querySelector('input[name="id"]').value = data.user.id || '';
                        document.querySelector('input[name="curp"]').value = data.user.curp || '';
                        document.querySelector('input[name="nombre"]').value = data.user.nombre || '';
                        document.querySelector('input[name="ap_paterno"]').value = data.user.ap_paterno || '';
                        document.querySelector('input[name="ap_materno"]').value = data.user.ap_materno || '';
                        document.querySelector('input[name="email"]').value = data.user.email || '';
                        document.querySelector('input[name="telefono"]').value = data.user.telefono || '';
                        document.querySelector('input[name="institucion"]').value = data.user.institucion || '';
                        
                        document.querySelectorAll('#register-author-form input').forEach(input => {
                            if (input.name !== 'institucion') {
                                input.disabled = true;
                            }
                        });
                    }
                } else {
                    selectedAuthors.push({
                        id: selectedValue,
                        name: selectedText,
                        corresponding: false,
                        institucion: data.user.institucion || ''
                    });

                    updateAuthorList();
                    updateSelectedAuthorsInput();
                }
            })
            .catch(error => console.error('Error:', error));
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

        UpdateForm.addEventListener('submit', (event) => {
            updateSelectedAuthorsInput();
        });

        const registerAuthorBtn = document.getElementById('register-author-btn');

        registerAuthorBtn.addEventListener('click', (event) => {
            event.preventDefault();
            registerAuthorModal.style.display = 'block';
        });

        document.getElementById('save-author-btn').addEventListener('click', () => {
            const newAuthorId = document.querySelector('input[name="id"]').value || '';
            const newAuthorCurp = document.querySelector('input[name="curp"]').value || '';
            const newAuthorNombre = document.querySelector('input[name="nombre"]').value || '';
            const newAuthorApPaterno = document.querySelector('input[name="ap_paterno"]').value || '';
            const newAuthorApMaterno = document.querySelector('input[name="ap_materno"]').value || '';
            const newAuthorTelefono = document.querySelector('input[name="telefono"]').value || '';
            const newAuthorEmail = document.querySelector('input[name="email"]').value || '';
            const newAuthorInstitucion = document.querySelector('input[name="institucion"]').value || '';

            if (!newAuthorCurp || !newAuthorNombre || !newAuthorApPaterno || !newAuthorApMaterno || !newAuthorTelefono || !newAuthorEmail || !newAuthorInstitucion) {
                alert('Todos los campos son obligatorios.');
                return;
            }
            const newAuthorName = `${newAuthorApPaterno} ${newAuthorApMaterno} ${newAuthorNombre}`;
            // agregar el nuevo autor al combo de selección
            const newOption = document.createElement('option');
            newOption.value = newAuthorId;
            newOption.text = newAuthorName;
            selectedAuthorSelect.appendChild(newOption);
            // agregar el  autor al array
            selectedAuthors.push({ id: newAuthorId, name: newAuthorName, corresponding: false, institucion: newAuthorInstitucion });
            updateAuthorList();
            updateSelectedAuthorsInput();
            //manejo de modales
            registerAuthorModal.style.display = 'none';
            //reseteamos el modal
            const registerAuthorForm = document.getElementById('register-author-form');
            registerAuthorForm.reset();
            document.querySelectorAll('#register-author-form input').forEach(input => {
                input.disabled = false;
            });
        });

        ///////////////////////////////////MODALES////////////////////////////////////////
        const registerAuthorModal = document.getElementById('register-author-modal');
        var saveAuthorBtn = document.getElementById('save-author-btn');
        var closeButtons = document.querySelectorAll('.modal .close');


       
        registerAuthorBtn.addEventListener('click', function () {        
            registerAuthorModal.style.display = 'block';
        });

        saveAuthorBtn.addEventListener('click', function () {
            registerAuthorModal.style.display = 'none';
        });

        closeButtons.forEach(function (closeBtn) {
            closeBtn.addEventListener('click', function () {
                closeBtn.parentElement.parentElement.style.display = 'none';
            });
        });

        window.addEventListener('click', function (event) {
            if (event.target == registerAuthorModal) {
                createAuthorModal.style.display = 'none';
            }
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
                        // Bloquear campos
                        nombreInput.disabled = true;
                        apPaternoInput.disabled = true;
                        apMaternoInput.disabled = true;
                        emailInput.disabled = true;
                        telefonoInput.disabled = true;
                        curpError.style.display = 'none';
                    } else {
                        idInput.value = '';
                        nombreInput.value = '';
                        apPaternoInput.value = '';
                        apMaternoInput.value = '';
                        emailInput.value = '';
                        telefonoInput.value = '';
                        // Desbloquear campos
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