@extends('layouts.master')
    <title>Areas</title>
    <link rel="stylesheet" href="./css/style-home.css">
    <link rel="stylesheet" href="./css/style-areas.css">
</head>

@section('Content')
    <div class="container">
        <h1>Areas</h1>
        <div class="search-create">
            <input type="text" id="search-input" placeholder="Buscar areas...">
            <button id="create-event-btn">Registrar nueva area</button>
        </div>
        <div id="areas-list"></div>
            <div id="pagination"></div>
        </div>

        

        <div class="container">
            <h1>Lista de Areas</h1>
            <div class="info">
                @foreach($Areas as $a)

                    <div class="areas-list">
                        <div class="area-item">
                            <div class="area">
                                <h3>{!!$a->nombre!!}</h3>

                            </div>
                        </div>
                    </div>

                @endforeach


                <br><br><br><br>

                <table border=0>
                    <tr>
                        <th>Nombre</th>
                        <th>Descripcion</th>
                    </tr>
                    @foreach ($Areas as $area)

                    <tr>
                        <td>{!!$area->nombre!!}</td>
                        <td>{!!$area->descripcion!!}</td>
                        <td>
                            <a href="{!!'areas/'.$area->id.'/edit'!!}">
                                <button>editar</button>
                            </a>
                            <a href="{{url('areas/'.$area->id)}}" onclick="event.preventDefault(); if (confirm('¿Estás seguro de que deseas eliminar este registro?')) { document.getElementById('delete-form-{{ $area->id }}').submit(); }">
                            <button>Eliminar</button>
                            </a>
                            <form id="delete-form-{{ $area->id }}" action="{{ url('areas/'.$area->id) }}" method="POST" style="display: none;">
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

            <div id="create-event-modal" class="modal">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <h2>Registrar Area</h2>
                    {!! Form::open(['url'=>'/areas']) !!}
                        <label for="area-name">Nombre:</label>
                        <input type="text" id="nombre" name="nombre" required>
                        <label for="area-description">Descripción:</label>
                        <textarea id="description" name = "descripcion" required></textarea>
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
@endsection

<script src="./js/script-areas.js"></script>

