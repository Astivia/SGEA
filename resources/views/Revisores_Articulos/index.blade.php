@extends('layouts.master')
<title>Revisores de articulos</title>

@section('Content')
    <div class="container">
        <div class="search-create">
            <h1 id="titulo-h1">Articulos en Revision</h1>
                <button id="create-btn"><i class="las la-plus-circle la-2x"></i></button>
        </div>
        @if($articles->isEmpty())
            <strong>No hay Revisores asignados a ningun articulo en este momento</strong>
        @else
            <!-- <div style="overflow-x:auto; overflow-y:auto; max-height:500px;"> -->
            <div class="ajuste" >
            <button id="deleteSelected">Eliminar seleccionados</button>
                <table id="example" class="display nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="selectAll"></th>
                            <th>ARTICULO</th>
                            <th>estado</th>
                            <th>Revisor 1</th>
                            <th>revisor 2</th>
                            <th>revisor 3</th>
                            <th>Controles</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($articles as $ra)
                            <tr>
                            <td><input type="checkbox" class="selectRow" data-id="{{ $ra->id }}"></td>
                                <td><a href="{!! url(session('eventoID').'/articulo/'.$ra->id) !!}" style="color:#000;">{!!$ra->titulo!!} </a></td>
                                <td>{!!$ra->estado!!}</td>
                                @foreach ($ra->revisores as $revisor)
                                    <td><strong>{{ $revisor->usuario->nombre_completo }}</strong></td>
                                @endforeach
                                @for ($i = count($ra->revisores); $i < 3; $i++)
                                    <td>No asignado</td>
                                @endfor
                                <td>
                                    <a href="{{url(session('eventoID').'/revisoresArticulo/'.$ra->id.'/edit')}}"><i class="las la-pencil-alt la-2x"></i></a>
                                    <a href="{{url('/revisores/'.$ra->id)}}"><i class="las la-info-circle la-2x"></i></a>
                                    
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
            <h2>Asignar Revisor</h2>
            {!! Form::open(['url'=>'/revisores','id' => 'revisor-form']) !!}
                <label for="area">Seleccionar Área:</label>
                {!! Form::select('area_id', $areas->pluck('nombre', 'id')->prepend('Seleccionar...', ''), null) !!}

                <label for="articulo">Seleccionar Artículo:</label>
                {!! Form::select('articles',$articulos->pluck('titulo','id')->prepend('Seleccionar...', ''), null, ['required']) !!}

                <label for="Revisor1">Revisor 1:</label>
                {!! Form::select('revisor1', $usuarios->pluck('nombre_completo', 'id')->prepend('Seleccionar...', ''), null,['required']) !!}

                <label for="Revisor2">Revisor 2:</label>
                {!! Form::select('revisor2', $usuarios->pluck('nombre_completo', 'id')->prepend('Seleccionar...', ''), null) !!}

                <label for="Revisor3">Revisor 3:</label>
                {!! Form::select('revisor3', $usuarios->pluck('nombre_completo', 'id')->prepend('Seleccionar...', ''), null) !!}

                <input type="hidden" name="revisores" id="selected-users-input">
                <input type="hidden" name="articulo_id" id="selected-article-input">
                <button type="submit">Asignar</button>
            {!! Form::close() !!} 
        </div>
    </div>
@endsection
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const revisorForm = document.getElementById('revisor-form');
        const areaSelect = document.querySelector('select[name="area_id"]');
        const articlesSelect = document.querySelector('select[name="articles"]');
        const revisoresSelects = Array.from(document.querySelectorAll('select[name="revisor1"], select[name="revisor2"], select[name="revisor3"]'));
        const selectedUsersInput = document.getElementById('selected-users-input');
        const selectedArticleInput = document.getElementById('selected-article-input');

        let selectedUsers = [null, null, null];

        // Guardamos todos los artículos
        const initialArticles = @json($articulos->pluck('titulo', 'id'));
        // Guardamos los artículos con revisores
        const articlesWithRevisores = @json($articles->pluck('id'));

        areaSelect.addEventListener('change', async function() {
            var areaId = this.value;
            var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            if (areaId) {
                try {
                    const response = await fetch('/SGEA/public/get-articles/' + areaId, {
                        method: 'GET',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        }
                    });
                    const data = await response.json();
                    if (data.error) {
                        alert(data.error);
                        return;
                    } else {
                        articlesSelect.innerHTML = '<option value="">Seleccionar...</option>';
                        if (data.length > 0) {
                            const sortedData = data.sort((a, b) => titleComparator([a.id, a.titulo], [b.id, b.titulo]));
                            sortedData.forEach(article => {
                                let option = document.createElement('option');
                                option.value = article.id;
                                option.textContent = article.titulo;
                                articlesSelect.appendChild(option);
                            });
                            articlesSelect.disabled = false;
                        }
                        toggleRevisoresSelects(false);
                    }
                } catch (error) {
                    console.error('Error:', error);
                    return;
                }
            } else {
                // Volver a llenar el combo de artículos con los artículos iniciales ordenados
                articlesSelect.innerHTML = '<option value="">Seleccionar...</option>';
                const sortedInitialArticles = Object.entries(initialArticles).sort(titleComparator);
                for (const [id, titulo] of sortedInitialArticles) {
                    let option = document.createElement('option');
                    option.value = id;
                    option.textContent = titulo;
                    articlesSelect.appendChild(option);
                }
                toggleRevisoresSelects(false);
            }
        });

        articlesSelect.addEventListener('change', function() {
            const selectedArticleId = parseInt(this.value);

            if (selectedArticleId) {
                if (articlesWithRevisores.includes(selectedArticleId)) {
                    alert("Este artículo ya tiene revisores asignados");
                    this.selectedIndex = 0; // Regresamos a "Seleccionar..."
                    toggleRevisoresSelects(false);
                } else {
                    toggleRevisoresSelects(true);
                    selectedArticleInput.value = selectedArticleId;
                }
            } else {
                toggleRevisoresSelects(false);
                selectedUsers = [null, null, null];
                selectedArticleInput.value = '';
            }
        });

        revisoresSelects.forEach((select, index) => {
            select.addEventListener('change', function() {
                const userId = this.value;
                if (selectedUsers.includes(userId)) {
                    alert("El revisor ya ha sido asignado en el artículo");
                    // Regresamos a la opción "Seleccionar..."
                    this.selectedIndex = 0; 
                } else {
                    if (userId === '') {
                        selectedUsers[index] = null;
                    } else {
                        selectedUsers[index] = userId;
                    }
                }
                updateSelectedUsersInput();
            });
        });

        revisorForm.addEventListener('submit', (event) => {     
           // Verificamos si al menos un revisor ha sido seleccionado
            const hasSelectedRevisor = revisoresSelects.some(select => select.value !== '');
            if (!hasSelectedRevisor) {
                alert("Debe seleccionarse al menos un revisor");
                event.preventDefault(); // Evitamos el envío del formulario
                return;
            }
            updateSelectedUsersInput();
        });

        const updateSelectedUsersInput = () => {
            const selectedUsersData = selectedUsers.map(user => ({
                id: user,
            }));
            selectedUsersInput.value = JSON.stringify(selectedUsersData);
            console.log('Campo oculto:', selectedUsersInput.value);
        };

        function toggleRevisoresSelects(enabled) {
            revisoresSelects.forEach(select => { select.disabled = !enabled; });
        }

        const titleComparator = (a, b) => {
            const isAlphabetic = (char) => /^[a-zA-Z]$/.test(char);
            const charA = a[1].charAt(0);
            const charB = b[1].charAt(0);

            if (!isAlphabetic(charA) && isAlphabetic(charB)) {
                return -1;
            }
            if (isAlphabetic(charA) && !isAlphabetic(charB)) {
                return 1;
            }
            return a[1].localeCompare(b[1]);
        };

        toggleRevisoresSelects(false);
    });
</script>





