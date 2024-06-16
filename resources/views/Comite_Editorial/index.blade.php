@extends('layouts.master')
    <title>Comite Editorial</title>
    <link rel= "stylesheet" href= "https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css" >
    <link rel="stylesheet" href="./css/style-home.css">
    <link rel="stylesheet" href="./css/styles.css">
</head>
</head>
@section('Content')
    @if(session('error'))
        <script>
        alert('{{ session('error') }}');
        </script>
    @endif

    @if(session('success'))
        <script>
        alert('{{ session('success') }}');
        </script>
    @endif
    <div class="main-content">
        <header>
            <div class="menu-toggle">
                <label for="">
                    <span class="las la-bars"></span>
                </label>
            </div>
            <div class="header-icons">
                <span class="las la-search"></span>
                <span class="las la-bookmarks"></span>
                <span class="las la-sms"></span>
            </div>
        </header>
        <main>
            <div class="container">
                <h1>Comite Editorial</h1>
                <div class="search-create">
                    <input type="text" id="search-input" placeholder="Buscar participante...">
                    <button id="create-event-btn">Agregar Participante</button>
                </div>
                <div id="events-list"></div>
                <div id="pagination"></div>
            </div>

            <div class="container">
                <h1>Participantes del Comite Editorial</h1>
                <div class="info">
                    <table border=0>
                        <tr>
                            <th>EVENTO</th>
                            <th>PARTICIPANTE</th>
                        </tr>
                        @foreach ($Comite as $ce)
                        <tr>                  
                            <td>{!!$ce->evento->acronimo!!} {!!$ce->evento->edicion!!}</td>
                            <td>{!!$ce->participante->nombre!!} {!!$ce->participante->apellidos!!}</td>
                            <td>
                                <a href="{!!'comite_editorial/'.$ce->id.'/edit'!!}">
                                    <button>editar</button>
                                </a>
                                <a href="{{url('comite_editorial/'.$ce->id)}}" onclick="event.preventDefault(); if (confirm('¿Estás seguro de que deseas eliminar este registro?')) { document.getElementById('delete-form-{{ $ce->id }}').submit(); }">
                                <button>Eliminar</button>
                                </a>
                                <form id="delete-form-{{ $ce->id }}" action="{{ url('comite_editorial/'.$ce->id) }}" method="POST" style="display: none;">
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
                    <h2>Añadir al Comite</h2>
                    {!! Form::open(['url'=>'/comite_editorial']) !!}
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


                        <button type="submit">Agregar al Comite</button>
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
        </main>
    </div>
@endsection
    
<script src="./js/scriptEventos.js"></script>
</html>
