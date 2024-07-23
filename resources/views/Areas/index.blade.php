@extends('layouts.master')
<title>Areas</title>


@section('Content')
<div class="container">
    <div class="search-create">
        <h1 id="titulo-h1">areas</h1>
        <button id="create-btn"><i class="las la-plus-circle la-2x"></i></button>
    </div>

    @if($Areas->isEmpty())
        <strong>No hay datos</strong>
      @else
      <div style="overflow-x:auto; overflow-y:auto; max-height:500px;">
      <table id="example" class="display nowrap" style="width:100%">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Descripcion</th>
                    <th>Controles</th>
                </tr>

            </thead>
            <tbody>
                @foreach ($Areas as $area)
                <tr>
                    <td>{!!$area->nombre!!}</td>
                    <td>{!!$area->descripcion!!}</td>
                    <td>
                        <a href="{!! 'areas/'.$area->id !!}"><i class="las la-info-circle la-2x"></i></a>
                        <a href="{!!'areas/'.$area->id.'/edit'!!}"><i class="las la-pen la-2x"></i></a>
                        <a href="{{url('areas/'.$area->id)}}"
                            onclick="event.preventDefault(); if (confirm('¿Estás seguro de que deseas eliminar este registro?')) { document.getElementById('delete-form-{{ $area->id }}').submit(); }">
                            <i class="las la-trash-alt la-2x"></i>
                        </a>
                        <form id="delete-form-{{ $area->id }}" action="{{ url('areas/'.$area->id) }}" method="POST"
                            style="display: none;">
                            @csrf
                            @method('DELETE')
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
        <h2>Registrar Area</h2>
        {!! Form::open(['url'=>'/areas']) !!}
        <label for="area-name">Nombre:</label>
        <input type="text" id="nombre" name="nombre" required>

        <label for="area-description">Descripción:</label>
        <textarea id="description" name="descripcion" required></textarea>

        <button type="submit">Guardar</button>

        {!!Form::close()!!}
    </div>
</div>


@endsection