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
                {!! Form::open(['url'=>'/revisores_articulos']) !!}

                    <button type="submit">Registrar Revisor</button>
                {!!Form::close()!!} 
            </div>
        </div>
        

    @endsection
