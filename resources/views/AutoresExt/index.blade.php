@extends('layouts.master')
    <title>Autores Externos</title>

@section('Content')
    <div class="container">
        <h1>Autores Externos</h1>
        <div class="search-create">
            <input type="text" id="search-input" placeholder="Buscar artículos...">
            <button id="create-event-btn"><i class="las la-plus-circle la-2x"></i></button>
        </div>
    </div>

    <br><br>

    <div class="container">
     @if($autoresExt->isEmpty())
            <strong>No hay datos</strong>
      @else
      <table border=0>
            <tr>
                <th>PARTICIPANTE</th>
                <th>AFILIACION</th>
                <th>Controles</th>
            </tr>
            @foreach ($autoresExt as $autor)
            <tr>
                <td>{!!$autor->nombre_completo!!} </td>
                <td>{!!$autor->afiliacion!!}</td>
                <td>
                    <a href="{!! 'autores_externos/'.$autor->id !!}"><i class="las la-info-circle la-2x"></i></a>
                    <a href="{!!'autores_externos/'.$autor->id.'/edit'!!}">
                        <i class="las la-user-edit la-2x"></i>
                    </a>
                    <a href="{{url('autores_externos/'.$autor->id)}}"
                        onclick="event.preventDefault(); if (confirm('¿Estás seguro de que deseas eliminar este registro?')) { document.getElementById('delete-form-{{ $autor->id }}').submit(); }">
                        <i class="las la-user-minus la-2x"></i>
                    </a>
                    <form id="delete-form-{{ $autor->id }}" action="{{ url('autores_externos/'.$autor->id) }}" method="POST"
                        style="display: none;">
                        @method('DELETE')
                        @csrf
                    </form>

                </td>
            </tr>
            @endforeach
        </table>
      @endif
    </div>

    <div id="create-event-modal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Registrar Autor</h2>
        {!! Form::open(['url'=>'/autores_externos']) !!}

        <label for="autorExt-name">Nombre:</label>
        <input type="text" id="nombre" name="nombre" required>
        
        <label for="participante-lastName">Apellido Paterno:</label>
        <input type="text" id="participante-lastName" name="ap_pat" required>

        <label for="participante-2lastName">Apellido Materno:</label>
        <input type="text" id="participante-2lastName" name="ap_mat" required>

        <label for="afiliacion">Afiliacion:</label>
        <input type="text" id="afiliacion" name="afiliacion" required>

        <button type="submit">Agregar Autor</button>
        {!!Form::close()!!}
    </div>
</div>
    
@endsection