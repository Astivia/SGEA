@extends('layouts.master')
<title>Revisores de articulos</title>
@section('Content')
    <div class="container">
        <div class="search-create">
            <h1 id="titulo-h1">Revision de Articulos</h1>
                <button id="create-btn"><i class="las la-plus-circle la-2x"></i></button>
        </div>
        @if($articles->isEmpty())
            <strong>No hay Revisores asignados a ningun articulo en este momento</strong>
        @else
            <div class="ajuste" >
                <table id="example" class="display nowrap" style="width:100%">
                    <thead>
                        <tr>
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
                                <td><a href="{!! url(session('eventoID').'/articulo/'.$ra->id) !!}" style="color:#000;">{!!$ra->titulo!!} </a></td>
                                <td>{!!$ra->estado!!}</td>
                                @foreach ($ra->revisores as $revisor)
                                    <td><strong>
                                        <a href="{{ url('usuarios/'.$revisor->usuario->id) }}"  style="color:#000;">{{ $revisor->usuario->nombre_completo }}</a>
                                    </strong></td>
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
            <h3>Seleccionar Articulo</h3>
            {!! Form::open(['url'=>'/revisores','id' => 'revisor-form']) !!}
                {!! Form::label('area', 'Seleccionar Area:') !!}
                {!! Form::select('area_id', $areas->pluck('nombre', 'id')->prepend('Seleccionar...', ''), null) !!}

                {!! Form::label('articulo', 'Seleccionar Articulo:') !!}
                {!! Form::select('articles',$articulos->pluck('titulo','id')->prepend('Seleccionar...', ''), null, ['required']) !!}
                <br><hr><br>
                <!-------------------------------------------------- REVISORES --------------------------------------------->
                <h3>{!! Form::label('', 'Lista de Revisores') !!}</h3>
                <div class="showList" style ="display:flex;justify-content:center;align-items:cener;padding:3%;">
                    <span id="No-Revs"><strong>No hay revisores Asignados</strong></span>
                    <ul class="selectedRevisorsList" ></ul>
                </div>
                {!! Form::label('Revisor', 'Seleccionar Revisores:') !!}
                {!! Form::select('revisor-Combo', $usuarios->pluck('nombre_completo', 'id')->prepend('Seleccionar...', ''), null,['id'=>'user-combo','required']) !!}
                <div class="cntrls" style="display:flex;align-items:center;justify-content:space-evenly;margin-bottom:2vh;">
                    <button type="button" id="add-revisor" style="color:#fff;background-color:#1a2d51;">Asignar</button>
                    <button type="button" id="remove-revisor" style="color:#fff;background-color:#1a2d51;">Quitar</button>
                </div>
                
                {!! Form::hidden('selected_users',null,['id'=> 'selected-users'])!!}
                
                {!! Form::button('Guardar', ['type' => 'submit', 'style' => 'background-color:#1a2d51;color:#fff;']) !!}

            {!! Form::close() !!} 
        </div>
    </div>
@endsection
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const revisorForm = document.getElementById('revisor-form');
        const areaSelect = document.querySelector('select[name="area_id"]');
        const selectedRevLst = document.querySelector('.selectedRevisorsList');
        const articlesSelect = document.querySelector('select[name="articles"]');
        const addBtn = document.getElementById('add-revisor');
        const removeBtn = document.getElementById('remove-revisor');
        const selectedUsersInput = document.getElementById('selected-users');
        const selectedUsersSelect = document.getElementById('user-combo');
        const noRevsTxt = document.getElementById('No-Revs');


        const initialArticles = @json($articulos->pluck('titulo', 'id'));
        const articlesWithRevisores = @json($articles->pluck('id'));
        selectedUsers=[];

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
                        // alert(data.error);
                        Swal.fire({icon: 'error',title: 'Cuidado!',text: data.error});return;
                    } else {
                        articlesSelect.innerHTML = '<option value="">Seleccionar...</option>';
                        if (data.length > 0) {
                            const sortedData = data.sort((a, b) => titleComparator([a.id, a.titulo], [b.id, b.titulo]));
                            sortedData.forEach(article => {
                                let option = document.createElement('option');option.value = article.id;option.textContent = article.titulo;
                                articlesSelect.appendChild(option);
                            });
                            articlesSelect.disabled = false;
                        }
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
            }
        });

        articlesSelect.addEventListener('change', function(){
            const selectedArticleId = parseInt(this.value);
            if (selectedArticleId) {
                if (articlesWithRevisores.includes(selectedArticleId)) {
                    Swal.fire({
                        title:'Cuidado!',
                        text:'Este artículo ya tiene revisores asignados',
                        icon:'warning',
                    });
                    this.selectedIndex = 0; 
                    return;
                } 
            }
        });

        addBtn.addEventListener('click',() => {
            const selectedValue = selectedUsersSelect.value;
            const selectedText = selectedUsersSelect.options[selectedUsersSelect.selectedIndex].text;
           
            //validaciones
            if (!selectedValue) {
                Swal.fire({title:'Advertencia',text:'Por favor, seleccione un usuario de la lista desplegable.',icon:'error',});return;
            }
            if (selectedUsers.find(revisor => revisor.id === selectedValue)) {
                Swal.fire({title:'Advertencia',text:'El revisor ya se encuentra en la lista.',icon:'error',});return;
            }
            //agregamos al vector
            selectedUsers.push({ id: selectedValue, name: selectedText });
            //actualizamos el vector y la Lista
            updateSelectedUsersInput();
            updateAuthorList();
            if(selectedUsers.length===0){
               noRevsTxt.style.display='block';
            }else{
               noRevsTxt.style.display='none';
           }
        });

        removeBtn.addEventListener('click',() => {
            const selectedValue = selectedUsersSelect.value;
            if (!selectedValue) {
                Swal.fire({
                    title:'Cuidado',
                    text:'Primero, seleccione un usuario de la lista desplegable.',
                    icon:'error',
                });
                return;
            }
            const userIndex = selectedUsers.findIndex(user => user.id === selectedValue);
            if (userIndex === -1) {
                Swal.fire({
                    title:'Advertencia',
                    text:'El autor seleccionado no está en la lista.',
                    icon:'error',
                });
                return;
            }
            selectedUsers.splice(userIndex, 1);
            //actualizamos el vector y la Lista
            updateSelectedUsersInput();
            updateAuthorList();
            if(selectedUsers.length===0){
               noRevsTxt.style.display='block';
            }else{
               noRevsTxt.style.display='none';
           }
        });


        revisorForm.addEventListener('submit', (event) => {     
           // Verificamos si al menos un revisor ha sido seleccionado
           
            if(selectedUsers.length=== 0){
                event.preventDefault();
                Swal.fire({
                    title:'Advertencia',
                    text:'Debe seleccionar almenos un usuario.',
                    icon:'error',
                });
                return;
            }else{
                UpdateSelectedUsersInput();
            }
        });

        const updateSelectedUsersInput = () => {
            const selectedUsersData = selectedUsers.map(user => ({
                id:user.id
            }));
            selectedUsersInput.value = JSON.stringify(selectedUsersData);
            console.log('Campo oculto:', selectedUsersInput.value);
        }; 

        const updateAuthorList = () => {
            selectedRevLst.innerHTML = '';
            selectedUsers.forEach((revisor, index) => {
                const newListItem = document.createElement('li');
                newListItem.textContent = `${index + 1}. ${revisor.name} `;
                newListItem.setAttribute('data-value', revisor.id);
                selectedRevLst.appendChild(newListItem);
            });
        };

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

        updateSelectedUsersInput();
        updateAuthorList();
    });
</script>



