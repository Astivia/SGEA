@extends('layouts.master')
    <title>Autores</title>
    <link rel="stylesheet" href="./css/style-home.css">
    <link rel="stylesheet" href="./css/styles.css">
</head>
@section('Content')
    <div class="container">
        <h1>Autores</h1>
        <div class="search-create">
            <input type="text" id="search-input" placeholder="Buscar autores...">
            <button id="create-event-btn">Registrar Autor</button>
        </div>
        <div id="events-list"></div>
        <div id="pagination"></div>
    </div>

            <div class="container">
                <h1>Lista de Autores</h1>
                <div class="info">
                    <table border=0>
                        <tr>
                            
                            <th>PARTICIPANTE</th>
                            <th>AFILIACION</th>
                        </tr>
                        @foreach ($Autores as $autor)
                        <tr>                  
                            <td>{!!$autor->participante->nombre!!} {!!$autor->participante->apellidos!!}</td>
                            <td>{!!$autor->afiliacion!!}</td>
                            <td>
                                <a href="{!!'autores/'.$autor->id.'/edit'!!}">
                                    <button>editar</button>
                                </a>
                                <a href="{{url('autores/'.$autor->id)}}" onclick="event.preventDefault(); if (confirm('¿Estás seguro de que deseas eliminar este registro?')) { document.getElementById('delete-form-{{ $autor->id }}').submit(); }">
                                <button>Eliminar</button>
                                </a>
                                <form id="delete-form-{{ $autor->id }}" action="{{ url('autores/'.$autor->id) }}" method="POST" style="display: none;">
                                    @method('DELETE')
                                    @csrf
                                </form>

                            </td>
                        </tr>
                        @endforeach
                    </table>
                </div>
                <div id="events-list"></div>
                <div id="pagination"></div>
            </div>
        
            <div id="register-modal" class="modal">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <p>Se ha registrado correctamente.</p>
                </div>
            </div>
        
            <div id="create-event-modal" class="modal">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <h2>Registrar Autor</h2>
                    {!! Form::open(['url'=>'/autores']) !!}

                        <!-- {!! Form::label ('evento', 'Evento:') !!}
                        {!! Form::select ('evento',  $Eventos->pluck('acronimo','edicion')->all(), null
                        ,['placeholder'=> 'Seleccionar...','class'=>'form-control','onchange'=>'llenar_participantes(this.value);'])!!}


                        {!! Form::label ('participante_id', 'Participante:') !!}
                        {!! Form::select ('participante_id', array(''=>'Seleccionar...'), null
                        ,['placeholder'=> 'Seleccionar...','class'=>'form-control'])!!} -->
                        <label for="evento">Seleccionar evento :</label>
                        <select name="evento_id" require>
                        @foreach ($Eventos as $evento)
                            <option value="{{ $evento->id }}">{{ $evento->acronimo}} {{ $evento->edicion}}</option>
                        @endforeach
                        </select>
                       
                        <label for="participante">Seleccionar participante :</label>
                        <select name="participante_id" require>
                        @foreach ($Participantes as $participante)
                            <option value="{{ $participante->id }}">{{ $participante->nombre}} {{ $participante->apellidos}}</option>
                        @endforeach
                        </select>

                        <label for="afiliacion">Afiliacion:</label>
                        <input type="text" id="afiliacion" name="afiliacion" required>


                        <button type="submit">Guardar articulo</button>
                    {!!Form::close()!!} 
                </div>
            </div>
        
            <div id="edit-event-modal" class="modal">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <h2>Editar Evento</h2>
                    <form id="edit-event-form">
                        <label for="edit-event-name">Nombre del Evento:</label>
                        <input type="text" id="edit-event-name" required>
                        <label for="edit-event-description">Descripción:</label>
                        <textarea id="edit-event-description" required></textarea>
                        <label for="edit-event-photo">Fotografía:</label>
                        <input type="text" id="edit-event-photo" placeholder="URL de la imagen" required>
                        <label for="edit-event-speaker">Ponente:</label>
                        <input type="text" id="edit-event-speaker" required>
                        <label for="edit-event-date">Fecha del Evento:</label>
                        <input type="date" id="edit-event-date" required>
                        <label for="edit-event-time">Hora del Evento:</label>
                        <input type="time" id="edit-event-time" required>
                        <button type="submit">Actualizar Evento</button>
                    </form>
                </div>
            </div>

@endsection
    
<!-- <script src="./js/scriptEventos.js"></script> -->

