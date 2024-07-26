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
                        <th>EMAIL DE CORRESPONDENCIA</th>
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
                            <a href="{{url ('usuarios/'.$autor->usuario->id) }}"><i class="las la-info-circle la-2x"></i></a>
                            @role(['Administrador','Organizador'])
                                <a href="{!! url($autor->evento_id.'/autores/'.$autor->usuario->id.'/edit')!!}">
                                    <i class="las la-user-edit la-2x"></i>
                                </a>
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