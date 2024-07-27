@extends('layouts.master')
<title>Revisores de articulos</title>

@section('Content')
    <div class="container">
        <div class="search-create">
            <h1 id="titulo-h1">Revisores de Articulos</h1>
                <button id="create-btn"><i class="las la-plus-circle la-2x"></i></button>
        </div>
        @if($Revisores->isEmpty())
            <strong>No hay Revisores asignados a ningun articulo en este momento</strong>
        @else
            <div style="overflow-x:auto; overflow-y:auto; max-height:500px;">
                <table id="example" class="display nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th>PARTICIPANTE</th>
                            <th>ARTICULO</th>
                            <th>ESTADO</th>
                            <th>Controles</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($Revisores as $ra)
                            <tr>
                                <td>{!!$ra->usuario->nombre_completo!!}</td>
                                <td>
                                    {!!$ra->articulo->titulo!!}
                                    <a href="{!! url('articulos/'.$ra->articulo->id) !!}"><i class="las la-info-circle"></i></a>
                                </td>
                                <td>
                                    {!!$ra->articulo->estado!!}
                                </td>
                                <td>
                                    <a href="{!!url('revisores_articulos/'.$ra.'/edit')!!}"><i class="las la-user-edit la-2x"></i></a>
                                    <a href="{{url('revisores_articulos/'.$ra->id)}}" onclick=" event.preventDefault(); 
                                            if (confirm('¿Estás seguro de que deseas eliminar este Revisor en este articulo?')) 
                                            { document.getElementById('delete-form-{{ $ra->articulo->id }}').submit(); }">
                                        <i class="las la-trash la-2x" style="color:red;"></i>
                                    </a>
                                    <form id="delete-form-{{ $ra->articulo->id }}" 
                                                action="{{ url('revisores_articulos/'.$ra->evento->id.'/'.$ra->usuario->id.'/'.$ra->articulo->id) }}"
                                                method="POST" style="display: none;">
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
            <h2>Asignar Revisor</h2>
            {!! Form::open(['url'=>'/revisores','id' => 'revisor-form']) !!}
                <label for="area">Seleccionar Área:</label>
                {!! Form::select('area_id', $areas->pluck('nombre', 'id')->prepend('Seleccionar...', ''), null, ['required']) !!}

                <label for="articulo_id">Seleccionar Artículo:</label>
                {!! Form::select('articles', ['' => 'Seleccionar...'], null, ['required', 'disabled' => true]) !!}

                <label for="Revisor1">Revisor 1:</label>
                {!! Form::select('revisor1', $usuarios->pluck('nombre_completo', 'id')->prepend('Seleccionar...', ''), null) !!}

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
                        data.forEach(article => {
                            let option = document.createElement('option');
                            option.value = article.id;
                            option.textContent = article.titulo;
                            articlesSelect.appendChild(option);
                        });
                        articlesSelect.disabled = false;
                    } else {
                        articlesSelect.disabled = true;
                    }
                    // Disable revisores selects when the area changes
                    toggleRevisoresSelects(false);
                }
            } catch (error) {
                console.error('Error:', error);
                return;
            }
        } else {
            articlesSelect.innerHTML = '<option value="">Seleccionar...</option>';
            articlesSelect.disabled = true;
            toggleRevisoresSelects(false);
        }
    });

    articlesSelect.addEventListener('change', function() {
        if (this.value) {
            toggleRevisoresSelects(true);
            selectedArticleInput.value = this.value;
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
                this.selectedIndex = 0; // Regresamos a "Seleccionar..."
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
        updateSelectedAuthorsInput();
    });

    const updateSelectedUsersInput = () => {
        const selectedUsersData = selectedUsers.map(user => ({
            id: user,
        }));
        selectedUsersInput.value = JSON.stringify(selectedUsersData);
        console.log('Campo oculto:', selectedUsersInput.value);
    };

    function toggleRevisoresSelects(enabled) {
        revisoresSelects.forEach(select => {
            select.disabled = !enabled;
        });
    }
    toggleRevisoresSelects(false);
});


</script>


