@extends('layouts.master')
<title>Autores</title>

@section('Content')
    <div class="container">
        <div class="search-create">
            <h1 id="titulo-h1">Autores</h1>
            
        </div>
        @if($autores->isEmpty())
            <strong>No hay autores registrados en este momento</strong>
        @else
        <div style="overflow-x:auto; overflow-y:auto; max-height:500px;">
            <table id="example" class="display nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th>AUTOR</th>
                        <th>INSTITUCION</th>
                        <th>CORRESPONDENCIA</th>
                        <th>CONTROLES</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($autores as $autor)
                    <tr>
                        <td>{!!$autor->usuario->nombre_completo!!} </td>
                        <td>{!!$autor->institucion!!}</td>
                        <td>{!!$autor->email!!}</td>
                        <td>
                            <a href="{!! 'usuarios/'.$autor->usuario->id !!}"><i class="las la-info-circle la-2x"></i></a>
                            @role(['Administrador','Organizador'])
                                <a href="{!!'autores/'.$autor->usuario->id.'/edit'!!}">
                                    <i class="las la-user-edit la-2x"></i>
                                </a>
                                <a href="{{url('autores/'.$autor->usuario->id)}}"
                                    onclick="event.preventDefault(); if (confirm('¿Estás seguro de que deseas eliminar este registro?')) { document.getElementById('delete-form-{{ $autor->id }}').submit(); }">
                                    <i class="las la-user-minus la-2x"></i>
                                </a>
                                <form id="delete-form-{{ $autor->id }}" action="{{ url('autores/'.$autor->id) }}" method="POST"
                                    style="display: none;">
                                    @method('DELETE')
                                    @csrf
                                </form>
                            @endrole
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
@endsection