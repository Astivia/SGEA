@extends('layouts.master')
<title>Revisores de articulos</title>

@section('Content')
<div class="container">

    <div class="info">
        <p><Strong>{!! $articulo->titulo !!}</Strong></p>
        <p>
            @foreach ($articulo->autores->sortBy('orden') as $autor)                  
                {{ $autor->orden }}. {{ $autor->usuario->nombre_autor }} <a href="{{ url('usuarios/'.$autor->usuario->id) }}"><i class="las la-info-circle la-1x"></i></a>      
            @endforeach
        </p>
        <p>{!! $articulo->area->nombre !!}</p>
    </div>

    <div class="revisores">
        <table>
            <thead>
                <tr>
                    <th>Revisor</th>
                    <th>Puntuacion</th>
                    <th>similitud</th>
                    <th>comentarios</th>
                </tr>
            </thead>
            <tbody>
                @foreach($articulo->revisores as $revisor)
                    <tr>
                        <td><strong>Revisor {!!$revisor->orden!!}: {!!$revisor->usuario->nombre_completo!!}</strong></td>
                        
                        <td>{{ $revisor->puntuacion ?? 'No definido' }}</td>
                        <td>{{$revisor->similitud ?? 'No definido'}}</td>
                        <td>{{$revisor->comentarios ?? 'No hay comentarios'}}</td>
                        
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection