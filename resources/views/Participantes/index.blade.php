@extends('layouts.master')
    <title>CiDiCi</title>
    <link rel= "stylesheet" href= "https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css" >
    <link rel="stylesheet" href="./css/style-home.css">
    <link rel="stylesheet" href="./css/styles.css">
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
                <h1>Participantes</h1>
                <div class="search-create">
                    <input type="text" id="search-input" placeholder="Buscar Participante...">
                    <button id="create-event-btn">Registrar Participante</button>
                </div>
                <div id="events-list"></div>
                <div id="pagination"></div>
            </div>

            <div class="container">
                <h1>Lista de Participantes</h1>
                <div class="info">
                    <table border=0>
                        <tr>
                            <th>EVENTO</th>
                            <th>NOMBRE</th>
                            <th>APELLIDOS</th>
                            <th>EMAIL</th>
                            <th>CURP</th>
                        </tr>
                        @foreach ($Participantes as $part)
                        <tr>
                            <td>{!!$part->evento->acronimo!!} {!!$part->evento->edicion!!}</td>
                            <td>{!!$part->nombre!!}</td>
                            <td>{!!$part->apellidos!!}</td>
                            <td>{!!$part->email!!}</td>
                            <td>{!!$part->curp!!}</td>
                            <td>
                            <a href="{!!'participantes/'.$part->id.'/edit'!!}">
                                    <button>editar</button>
                                </a>
                                <a href="{{url('participantes/'.$part->id)}}" 
                                    onclick="
                                    event.preventDefault(); 
                                    if (confirm('¿Estás seguro de que deseas eliminar este registro?')) 
                                    { document.getElementById('delete-form-{{ $part->id }}').submit(); }">
                                <button>Eliminar</button>
                                </a>
                                <form id="delete-form-{{ $part->id }}" action="{{ url('participantes/'.$part->id) }}" method="POST" style="display: none;">
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
                    <h2>Registrar Participante</h2>
                    {!! Form::open(['url'=>'/participantes']) !!}
                        <label for="event-name">Seleccionar evento :</label>
                        <select name="evento_id" require>
                        @foreach ($Eventos as $e)
                            <option value="{{ $e->id }}">{{ $e->acronimo }} {{ $e->edicion }}</option>
                        @endforeach
                        </select>
                        
                        <label for="participante-name">Nombre:</label>
                        <input type="text" id="participante-name" name="nombre" required>
                        
                        <label for="participante-lastName">Apellidos:</label>
                        <input type="text" id="participante-lastName" name="apellidos" required>
                        
                        <label for="participante-curp">CURP:</label>
                        <input type="text" id="participante-curp" name="curp" required>

                        <label for="participante-email">Email:</label>
                        <input type="text" id="participante-email" name="email" required>
                        
                        <label for="participante-pass">Contraseña:</label>
                        <input type="password" id="participante-password" name="password" required>

                        <button type="submit">Guardar</button>
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
    
<script src="./js/scriptEventos.js"></script>
@endsection
</html>
