@extends('layouts.master')
<title>Participantes</title>
<link rel="stylesheet" href="{{asset('SGEA/public/css/mainModificar.css')}}">
<link rel="stylesheet" href="./css/styles.css">
</head>
@section('Content')


<div class="container">
    <h1>Participantes</h1>
    <div class="search-create">
        <input type="text" id="search-input" placeholder="Buscar Participante...">
        <button id="create-event-btn">Registrar Participante</button>
    </div>
   
    <div class="info">
        
        
        <table>
        
            <tr>
                <th>EVENTO</th>
                <th>NOMBRE</th>
                <th>APELLIDOS</th>
                <th>EMAIL</th>
                <th>CURP</th>
                <th>Controles</th>
            </tr>
            @foreach ($Participantes as $part)
            <tr>
                @if($part->evento == null)
                <td>No Asignado</td>
                @else
                <td>{!!$part->evento->acronimo!!} {!!$part->evento->edicion!!}</td>
                @endif
                <td>{!!$part->nombre!!}</td>
                <td>{!!$part->ap_pat!!} {!!$part->ap_mat!!}</td>
                <td>{!!$part->email!!}</td>
                <td>{!!$part->curp!!}</td>
                <td>
                    <a href="{!!'participantes/'.$part->id.'/edit'!!}">
                        <i class="las la-user-edit la-2x"></i>
                    </a>
                    <a href="{{url('participantes/'.$part->id)}}" onclick="
                                            event.preventDefault(); 
                                            if (confirm('¿Estás seguro de que deseas eliminar este registro?')) 
                                            { document.getElementById('delete-form-{{ $part->id }}').submit(); }">
                        <i class="las la-user-minus la-2x"></i>
                    </a>
                    <form id="delete-form-{{ $part->id }}" action="{{ url('participantes/'.$part->id) }}" method="POST"
                        style="display: none;">
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

        <label for="participante-lastName">Apellido Paterno:</label>
        <input type="text" id="participante-lastName" name="ap_pat" required>

        <label for="participante-lastName">Apellido Materno:</label>
        <input type="text" id="participante-lastName" name="ap_mat" required>

        <label for="participante-curp">CURP:</label>
        <input type="text" id="participante-curp" name="curp" minlength="18" maxlength="18" required>
        <span id="mensaje-error" style="color:red; display:none;">Deben ser 18 caracteres</span>

        <label for="participante-email">Email:</label>
        <input type="text" id="participante-email" name="email" required>

        <label for="participante-pass">Contraseña:</label>
        <input type="password" id="participante-password" name="password" required>

        <button type="submit">Guardar</button>
        {!!Form::close()!!}
    </div>
</div>

<div id="pagination"></div>

@endsection
<!-- <script src="./js/script-participantes.js"></script> -->
 <script src="{{asset('SGEA/public/js/validaciones.js')}}"></script>