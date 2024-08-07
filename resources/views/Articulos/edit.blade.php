@extends('layouts.master')
    <title>Modificar Datos</title>
@section('Content')
    <div class="container">
        <h1>MODIFICAR ARTICULO</h1>

        {!! Form::open(['method'=>'PUT','url'=>$articulo->evento_id.'/articulo/'.$articulo->id, 'files' => true, 'id' => 'edit-form']) !!}
            <strong> {!! Form::label('title', 'Titulo del Articulo:') !!}</strong>
            {!! Form::text ('titulo',$articulo->titulo)!!}

            <strong> {!! Form::label('desc', 'Resumen del Articulo:') !!}</strong>
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
            <p><Strong>Archivo actual: </Strong>{!!$articulo->archivo!!}</p>
            <br>
            <div class="form-group">
                <label for="nuevo_archivo_pdf">Para cambiar el archivo, seleccione un nuevo archivo</label>
                {!! Form::file('archivo', ['id' => 'archivoPDF', 'class' => 'form-control', 'accept' => '.pdf,.docx,.doc']) !!}
            </div>
            <!-------------------------------------------------- AUTORES --------------------------------------------->
            <label for="">Seleccione Autor:</label>
                    <select name="autor" id="selected-author">
                        @if($Autores=== null)
                            <option value="">Aun no se han registrado autores</option>
                        @else
                            <option value="">Seleccionar...</option>
                            @if(Auth::user()->id !== 1)
                            <option value="{{ Auth::user()->id }}">{{ Auth::user()->nombre_completo }}</option>
                            @endif
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
                            <li> {!! $autor->orden !!}. {!! $autor->usuario->nombre_completo !!}</li>
                        @endforeach
                    </ul>
                    <br><br>
                    <p>¿No encuentra su Autor? <a href="#" id="register-author-btn"><strong>Registrar Autor</strong></a></p>
                    <br>
            <br><br>
            <input type="hidden" name="selected_authors" id="selected-authors-input">
            <button type="submit" >Guardar Cambios</button>
        {!!Form::close()!!}   
        <a href="{{ url()->previous() }}"><button>Cancelar</button></a> 
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
                <span id="curp-info" style="color:green; display:none;">Se verifico la CURP, favor de llenar todos los campos</span>
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
                // alert('Por favor, seleccione un autor de la lista desplegable.');
                Swal.fire({
                    title:'Cuidado!',
                    text:'Por favor, seleccione un autor de la lista desplegable.',
                    icon:'warning'
                });
                return;
            }

            if (selectedAuthors.find(author => author.id === selectedValue)) {
                // alert('El autor seleccionado ya se encuentra en la lista.');
                Swal.fire({
                    title:'Cuidado!',
                    text:'El autor seleccionado ya se encuentra en la lista.',
                    icon:'warning'
                });
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
                // alert('Por favor, seleccione un autor de la lista desplegable.');
                Swal.fire({
                    title:'Cuidado!',
                    text:'Por favor, seleccione un autor de la lista desplegable.',
                    icon:'warning'
                });
                return;
            }

            const authorIndex = selectedAuthors.findIndex(author => author.id === selectedValue);
            if (authorIndex === -1) {
                // alert('El autor seleccionado no está en la lista.');
                Swal.fire({
                    title:'Cuidado!',
                    text:'El autor seleccionado no está en la lista.',
                    icon:'warning'
                });
                return;
            }
            selectedAuthors.splice(authorIndex, 1);
            updateAuthorList();
            updateSelectedAuthorsInput();
        });

        UpdateForm.addEventListener('submit', (event) => {
            const hasCorrespondingAuthor = selectedAuthors.some(author => author.corresponding);
            if (!hasCorrespondingAuthor) {
                // alert('Seleccione un autor de correspondencia.');
                Swal.fire({
                    title:'Cuidado!',
                    text:'Seleccione un autor de correspondencia.',
                    icon:'warning'
                });
                event.preventDefault();
                return
            }else{
                updateSelectedAuthorsInput();
            }
        });

        const registerAuthorBtn = document.getElementById('register-author-btn');

        registerAuthorBtn.addEventListener('click', (event) => {
            event.preventDefault();
            registerAuthorModal.style.display = 'block';
        });

        document.getElementById('save-author-btn').addEventListener('click',  async () => {
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
                // alert('Todos los campos son obligatorios.');
                Swal.fire({
                    title:'Cuidado!',
                    text:'Todos los campos son obligatorios.',
                    icon:'warning'
                });
                return;
            }

            const authorData = {
                id: newAuthorId,curp: newAuthorCurp,nombre: newAuthorNombre,
                ap_paterno: newAuthorApPaterno,ap_materno: newAuthorApMaterno,telefono: newAuthorTelefono,email: newAuthorEmail,institucion: newAuthorInstitucion
            };

            if(newAuthorId === "" || newAuthorId === null){
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
                        // alert(data.error);
                        Swal.fire({
                            title:'Cuidado!',
                            text:data.error,
                            icon:'warning'
                        });
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
                // alert('El autor ya existe.');
                Swal.fire({
                    title:'Cuidado!',
                    text:'El autor ya existe.',
                    icon:'warning'
                });
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
                resetRegisterAuthorForm();
            }
        });

        ///////////////////////////////////MODALES////////////////////////////////////////
        const registerAuthorModal = document.getElementById('register-author-modal');
        var saveAuthorBtn = document.getElementById('save-author-btn');
       
        registerAuthorBtn.addEventListener('click', function () {        
            registerAuthorModal.style.display = 'block';
        });

        saveAuthorBtn.addEventListener('click', function () {
            registerAuthorModal.style.display = 'none';
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
        const curpInfo = document.getElementById('curp-info');

        function resetRegisterAuthorForm() {
            const registerAuthorForm = document.getElementById('register-author-form');
            //reseteamos los valores de los inputs
            registerAuthorForm.reset();
        }

        function fillForm(userData){
            if (typeof userData === 'object') {
                idInput.value = userData.id || '';
                nombreInput.value = userData.nombre || '';
                nombreInput.disabled=true;
                apPaternoInput.value = userData.ap_paterno || '';
                apPaternoInput.disabled=true
                apMaternoInput.value = userData.ap_materno || '';
                apMaternoInput.disabled=true;
                emailInput.value = userData.email || '';
                emailInput.disabled=true;
                telefonoInput.value = userData.telefono || '';
                telefonoInput.disabled=true;
            }else {
                console.error('Invalid user data provided to fillForm function!');
            }
        }

        function unlockInputs(){
            document.querySelectorAll('#register-author-form input').forEach(input => {
                if (input.name !== 'institucion'|| input.name == 'curp') {
                    input.disabled = false;
                }
                if (input.name == 'curp') {
                    input.disabled = false;
                }
            });
        }

        curpInput.addEventListener('input', () => {
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
                    fillForm(data.user);
                    curpInfo.textContent="El usuario ya existe, favor de llenar los datos que faltan";
                    curpInfo.style.display = 'block';
                } else {
                    curpInfo.style.display = 'none';
                    unlockInputs();
                    idInput.value = '';
                    nombreInput.value =  '';
                    apPaternoInput.value = '';
                    apMaternoInput.value = '';
                    emailInput.value =  '';
                    telefonoInput.value =  '';
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });



        const registerAuthorModal = document.getElementById('register-author-modal');
        const createArticleModal = document.getElementById('create-modal');
        const closeButtons = document.querySelectorAll('.modal .close');

        closeButtons.forEach(function (closeBtn) {
            closeBtn.addEventListener('click', function () {
                resetRegisterAuthorForm();unlockInputs();
                createArticleModal.style.display='block';
                closeBtn.parentElement.parentElement.style.display = 'none';
            });
        });
    });
</script>