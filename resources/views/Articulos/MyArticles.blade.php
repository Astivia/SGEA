@extends('layouts.master')
    <title>Mis Articulos</title>
@section('Content')
    <div class="container">
        <div class="search-create">
            <h1 id="titulo-h1"> Mis Artículos</h1>
            <button id="create-btn"><i class="las la-plus-circle la-2x"></i></button>
        </div>    
        @if($Articulos->isEmpty())
            <strong>No hay datos</strong>
        @else
        <div class="ajuste" >
            <button id="deleteSelected">Eliminar seleccionados</button>
            <table id="example" class="display  responsive nowrap" style="width:100%">
                <thead>            
                    <tr>
                        <th><input type="checkbox" id="selectAll"></th>
                        <th>TITULO</th>
                        <th>AUTORES</th>
                        <th>Area</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($Articulos as $art)
                    <tr>
                        <td><input type="checkbox" class="selectRow" data-id="{{ $art->id }}"></td>
                        <td>
                            <a href="{!! url($art->evento_id.'/articulo/'.$art->id) !!}" style="color:#000;">
                                <strong>{{ Helper::truncate($art->titulo, 65) }}</strong>
                            </a>
                        </td>
                            <td>
                                <ul>
                                    @foreach ($art->autores->sortBy('orden') as $autor)
                                        <li>
                                            <a href="{{url(session('eventoID').'/autor/'.$autor->usuario->id )}}" style="color:#000;">
                                                {{ $autor->orden }}. {{ $autor->usuario->nombre_autor}} 
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </td>
                            @role(['Administrador','Organizador'])
                            <td>
                                <a href="mailto:{!!$art->autor_correspondencia->email!!}" style="text-decoration:underline;">{!!$art->autor_correspondencia->email!!}</a>
                            </td>
                            <td>
                                <ul>
                                    @if($art->revisores->isEmpty())
                                        No asignados
                                    @else
                                        @foreach ($art->revisores->sortBy('orden') as $revisor)
                                            <li style="">
                                                {{ $revisor->orden}}:
                                                <a href="{{url('/usuarios/'.$revisor->usuario->id )}}">
                                                    {{ $revisor->usuario->nombre_completo}}
                                                </a>
                                            </li>
                                        @endforeach
                                    @endif
                                </ul>
                            </td>
                            @endrole
                            <td>{!!$art->area->nombre!!}</td>
                            <td>
                                <a href="{!! url($art->evento_id.'/articulo/'.$art->id) !!}"><i class="las la-info-circle la-2x"></i></a>
                                <a href="{!! url($art->evento_id.'/articulo/'.$art->id.'/edit')!!}"><i class="las la-edit la-2x"></i></a>
                                <a href="{{url('articulos/'.$art->id)}}" onclick="event.preventDefault(); 
                                        Swal.fire({
                                            title: '¿Estás seguro?',
                                            text: '¿Realmente desea eliminar este articulo?',
                                            icon: 'warning',
                                            showCancelButton: true,
                                            confirmButtonText: 'Sí, eliminar',
                                            cancelButtonText: 'No, cancelar'
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                document.getElementById('delete-form-{{ $art->id }}').submit();
                                            }
                                        });
                                    ">
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
                {!! Form::label('title', 'Titulo del Articulo:') !!}
                <input type="text" id="titulo" name="titulo" required>
                
                {!! Form::label('desc', 'Resumen del Articulo:') !!}
                <textarea rows="4" cols="50" id="description" name="resumen"></textarea>
                
                {!! Form::label('are', 'Area del Articulo:') !!}
                <select name="area_id" required>
                    <option value="">Seleccionar...</option>
                    @foreach ($Areas as $area)
                        <option value="{{ $area->id }}">{{ $area->nombre }}</option>
                    @endforeach
                </select>

                <label for="pfd">Subir archivo pdf:</label>
                {!! Form::file('pdf', ['id' => 'archivoPDF', 'class' => 'form-control', 'accept' => '.pdf,.docx,.doc']) !!}

                <br><hr><br>
                <!-------------------------------------------------- AUTORES --------------------------------------------->
                
                {!! Form::hidden('selected_authors',null,['id'=> 'selected-authors-input'])!!}

                <h3>{!! Form::label('', 'Autores del Articulo:') !!}</h3>
                
                <div class="showList" style ="display:flex;justify-content:center;align-items:cener;padding:3%;">
                    <span id="No-Autors"><strong>No hay autores Asignados</strong></span>
                    <ul class="selectedAutors" ></ul>
                </div>
                <span id="corresp-instructions"style ="display:none; color:#348aa7; font-size:15px"><i class="las la-info-circle"></i>Marcar  casilla para seleccionar autor de contacto </span>
               
                {!! Form::label('label_instruction', 'Seleccionar Autor:') !!}
                <select name="autor" id="selected-author">
                    @if($Autores=== null)
                        <option value="">Aun no se han registrado autores</option>
                    @else
                        <option value="">Seleccionar...</option>
                        @if(Auth::user()->id!==1)
                            <option value="{{ Auth::user()->id }}">{{ Auth::user()->nombre_completo }}</option>
                        @endif
                        @foreach ($Autores as $autor)
                            @if($autor->id !== Auth::user()->id)
                                <option value="{{ $autor->id }}">{{ $autor->ap_paterno }} {{ $autor->ap_materno }} {{ $autor->nombre}}</option>
                            @endif
                        @endforeach 
                    @endif
                </select>
                 <div class="cntrls" style="display:flex;align-items:center;justify-content:space-evenly;margin-bottom:2vh;">
                    <button type="button" id="plus-author-btn" style="color:#fff;background-color:#1a2d51;">Asignar</button>
                    <button type="button" id="minus-author-btn" style="color:#fff;background-color:#1a2d51;">Quitar</button>
                </div>
                <p>¿No encuentra su Autor? <a href="#" id="register-author-btn"><strong>Registrar Autor</strong></a></p>
                <button type="submit" style="background-color:#1a2d51;color:#fff;">Guardar articulo</button>
                
            {!! Form::close() !!}
        </div>
    </div>

    <div id="register-author-modal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Registrar Autor </h2>
            <form id="register-author-form" metod="POST" >  
                @csrf  
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
                <label for="email" id= "email-input">Email:</label>
                <span id="email-error" style="color:red; display:none;">Este Correo ya se encuentra registrado en otro usuario</span>
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
document.addEventListener('DOMContentLoaded', function() {
    const deleteSelected = document.getElementById('deleteSelected');
    const selectAll = document.getElementById('selectAll');

    if (deleteSelected) {
        deleteSelected.addEventListener('click', function() {
            const selectedCheckboxes = document.querySelectorAll('.selectRow:checked');
            let ids = [];
            selectedCheckboxes.forEach(function(checkbox) {
                ids.push(checkbox.getAttribute('data-id'));
            });

            if (ids.length > 0) {
                Swal.fire({
                    title: '¿Está seguro?',
                    text: '¡No podrá revertir esto!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'No, cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch('{{ route('articulos.deleteMultiple') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({ ids: ids })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Éxito',
                                    text: 'Registros eliminados correctamente.'
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Error: ' + data.error
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Ha ocurrido un error al eliminar los registros.'
                            });
                        });
                    }
                });
            } else {
                Swal.fire({
                    icon: 'info',
                    title: 'Información',
                    text: 'No se seleccionaron registros.'
                });
            }
        });
    }

    if (selectAll) {
        selectAll.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.selectRow');
            checkboxes.forEach(function(checkbox) {
                checkbox.checked = selectAll.checked;
            });
        });
    }
});
</script>

<script>
    document.addEventListener('DOMContentLoaded', (event) => {
        const articleForm = document.getElementById('article-form');
        const noAutorsText = document.getElementById('No-Autors');
        const ci = document.getElementById('corresp-instructions');
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

            if (selectedAuthors.length === 0) {
                noAutorsText.style.display = 'block';
                ci.style.display = 'none';
            } else {
                noAutorsText.style.display = 'none'; 
                ci.style.display = 'block'; 
            }

            selectedAuthors.forEach((author, index) => {
                const checkbox = document.createElement('input');
                const newListItem = document.createElement('li');
                checkbox.type = 'checkbox';
                checkbox.class='correspondencia';
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
                // swal('Por favor, seleccione un autor de la lista desplegable.',' ','error');
                Swal.fire({
                    title:'Cuidado!',
                    text:'Por favor, seleccione un autor de la lista desplegable.',
                    icon:'warning',
                });
                return;
            }

            if (selectedAuthors.find(author => author.id === selectedValue)) {
                // swal('El autor seleccionado ya se encuentra en la lista.',' ','error');
                Swal.fire({
                    title:'Cuidado!',
                    text:'El autor seleccionado ya se encuentra en la lista.',
                    icon:'warning',
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
                    noAutorsText.style.display = 'none';
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });

        minusAuthorBtn.addEventListener('click', () => {
            const selectedValue = selectedAuthorSelect.value;

            if (!selectedValue) {
                // swal('Por favor, seleccione un autor de la lista desplegable.',' ','error');
                Swal.fire({
                    title:'Cuidado!',
                    text:'Por favor, seleccione un autor de la lista desplegable.',
                    icon:'warning',
                });
                return;
            }

            const authorIndex = selectedAuthors.findIndex(author => author.id === selectedValue);
            if (authorIndex === -1) {
                // swal('El autor seleccionado no está en la lista.',' ','error');
                Swal.fire({
                    title:'Cuidado!',
                    text:'El autor seleccionado no está en la lista.',
                    icon:'warning',
                });
                return;
            }

            selectedAuthors.splice(authorIndex, 1);
            updateSelectedAuthorsInput();
            updateAuthorList();
        });

        articleForm.addEventListener('submit', (event) => {
            const hasCorrespondingAuthor = selectedAuthors.some(author => author.corresponding);
            if (!hasCorrespondingAuthor) {
                // swal('Seleccione un autor de correspondencia.',' ','error');
                Swal.fire({
                    title:'Cuidado!',
                    text:'Seleccione un autor de correspondencia.',
                    icon:'warning',
                });
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
                // swal('Todos los campos son obligatorios.',' ','error');
                Swal.fire({
                    title:'Cuidado!',
                    text:'Todos los campos son obligatorios. ',
                    icon:'warning',
                });
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
                        // alert(data.error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.error
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
                // swal('El autor ya existe.',' ','error');
                Swal.fire({
                    title:'Cuidado!',
                    text:'El autor ya existe.',
                    icon:'warning',
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
                noAutorsText.style.display = 'none';
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
    document.addEventListener('DOMContentLoaded', function() {
        const deleteSelected = document.getElementById('deleteSelected');
        const selectAll = document.getElementById('selectAll');

        if (deleteSelected) {
            deleteSelected.addEventListener('click', function() {
                const selectedCheckboxes = document.querySelectorAll('.selectRow:checked');
                let ids = [];
                selectedCheckboxes.forEach(function(checkbox) {
                    ids.push(checkbox.getAttribute('data-id'));
                });

                if (ids.length > 0) {
                    fetch('{{ route('articulos.deleteMultiple') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ ids: ids })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Registros eliminados correctamente.');
                            location.reload();
                        } else {
                            alert('Error: ' + data.error);
                        }
                    })
                    .catch(error => console.error('Error:', error));
                } else {
                    alert('No se seleccionaron registros.');
                }
            });
        }

        if (selectAll) {
            selectAll.addEventListener('change', function() {
                const checkboxes = document.querySelectorAll('.selectRow');
                checkboxes.forEach(function(checkbox) {
                    checkbox.checked = selectAll.checked;
                });
            });
        }
    });
</script>

<style>
    .selectedAutors li{
        display:flex;
        flex-direction:row-reverse;
        align-items:center;
        white-space: nowrap;
    }

    .selectedAutors li .correspondencia{
        background-color:red;
    }
</style>