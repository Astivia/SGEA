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
                    <th style="width:50vw;">comentarios</th>
                </tr>
            </thead>
            <tbody>
                @foreach($articulo->revisores->sortBy('orden') as $revisor)
                    <tr>
                        <td><strong >Revisor {!!$revisor->orden!!}:</strong><br>
                            <a href="{{ url('usuarios/'.$revisor->usuario->id) }}">{!!$revisor->usuario->nombre_completo!!}</a> 
                       </td>
                        <td><strong style="font-size:20px;"> {{ $revisor->puntuacion ?? 'No definido' }}</strong>
                           
                        </td>
                        <td>{{$revisor->similitud ?? 'No definido'}}</td>
                        <td style="width:50vw;text-align:justify;">{{$revisor->comentarios ?? 'No hay comentarios'}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="calificar">
        <button>Calificar</button>
    </div>
</div>
@endsection