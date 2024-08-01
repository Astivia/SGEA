@extends('layouts.master')
    <title>Modificar Datos</title>
@section('Content')
<div class="container">
    <h1>MODIFICAR REVISORES</h1>
    <div class="info">
        <p><Strong>{!! $articulo->titulo !!}</Strong></p>
        <p>
            @foreach ($articulo->autores->sortBy('orden') as $autor)                  
                {{ $autor->orden }}. {{ $autor->usuario->nombre_autor }} <a href="{{ url('usuarios/'.$autor->usuario->id) }}"><i class="las la-info-circle la-1x"></i></a>      
            @endforeach
        </p>
        <p>{!! $articulo->area->nombre !!}</p>
    </div>

    {!! Form::open(['method' => 'PATCH', 'url' => '/revisores/'.$articulo->id, 'id' => 'revisores-form']) !!}
        
        <label for="Revisor1"><Strong>Revisor 1:</Strong></label>
        {!! Form::select('revisor1', $usuarios->pluck('nombre_completo', 'id')->prepend('Seleccionar...', ''), $revisores->get(0)->usuario_id ?? null) !!}

        <label for="Revisor2"><Strong>Revisor 2:</Strong></label>
        {!! Form::select('revisor2', $usuarios->pluck('nombre_completo', 'id')->prepend('Seleccionar...', ''), $revisores->get(1)->usuario_id ?? null) !!}

        <label for="Revisor3"><Strong>Revisor 3:</Strong></label>
        {!! Form::select('revisor3', $usuarios->pluck('nombre_completo', 'id')->prepend('Seleccionar...', ''), $revisores->get(2)->usuario_id ?? null) !!}
        
        <input type="hidden" name="revisores" id="selected-users-input">
        
        <button type="submit">Guardar</button>
    {!! Form::close() !!}   
    <a href="{{ url()->previous() }}"><button>Cancelar</button></a> 
</div>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
    const revisorForm = document.getElementById('revisores-form');
    const revisoresSelects = Array.from(document.querySelectorAll('select[name="revisor1"], select[name="revisor2"], select[name="revisor3"]'));
    const selectedUsersInput = document.getElementById('selected-users-input');

    // Inicializamos los valores seleccionados de los revisores
    const initialSelectedUsers = @json($revisores->pluck('usuario_id')->toArray()).map(String); // Convertimos los valores a strings

    let selectedUsers = initialSelectedUsers;

    revisoresSelects.forEach((select, index) => {
        let previousValue = select.value;
        
        select.addEventListener('change', function() {
            const userId = this.value;
            if (userId && selectedUsers.includes(userId)) {
                alert("Este usuario ya es Revisor en este articulo");
                // Regresamos al valor anterior
                this.value = previousValue;
            } else {
                selectedUsers[index] = userId ? userId : null;
                previousValue = this.value;
            }
            updateSelectedUsersInput();
        });
    });

    revisorForm.addEventListener('submit', (event) => { 
        // Verificamos si al menos un revisor ha sido seleccionado
        const hasSelectedRevisor = revisoresSelects.some(select => select.value !== '');
            if (!hasSelectedRevisor) {
                alert("Debe seleccionarse al menos un Revisor");
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

    revisoresSelects.forEach((select, index) => {
        select.value = selectedUsers[index] ?? '';
    });
    updateSelectedUsersInput();
});

</script>
