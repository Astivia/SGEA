@extends('layouts.master')
    <title>Articulos revisados</title>
@section('Content')
    <div class="container">
        <div class="search-create">
            @if(session('rol') === 'Autor')
                <h1 id="titulo-h1">Historial de Revisiones</h1>
            @else
                <h1 id="titulo-h1">Articulos Revisados</h1>
            @endif
        </div>
        @if(session('rol') === 'Autor')
        <div class="information" style="margin-bottom:5vh;">
            <i class="las la-info-circle"></i>
            <span>Usted solo podra ver las evaluaciones de los aritculos en los que <strong>usted es autor de contacto</strong> </span>
        </div>
        @endif
        
        @if($articulos->isEmpty())
            @if(session('rol') === 'Autor')
                <strong>Aun no hay Articulos que hayan finalizado su revision</strong>
            @else
                <strong>Aun no ha revisado ningun Articulo</strong>
            @endif
        @else
            <div class="ajuste">
                <table id="example" class="display nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th>ARTICULO</th>
                            @if(session('rol') !== 'Autor')
                            <th>puntuacion asignada</th>
                            @endif
                            <th>estado</th>
                            @if(session('rol') === 'Autor')
                            <th> </th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($articulos as $ra)
                            <tr>
                                <td><a href="{!! url(session('eventoID').'/articulo/'.$ra->articulo->id) !!}" style="color:#000;">{!!$ra->articulo->titulo!!} </a></td>
                                @if(session('rol') !== 'Autor')
                                    <td><strong>{!!$ra->puntuacion!!} / 30</strong></td>
                                @endif
                                <td>
                                    <strong>{!!$ra->articulo->estado!!}</strong>
                                </td>

                                @if(session('rol') === 'Autor')
                                <td> 
                                    <a href="{{url('revisores/'.$ra->articulo->id)}}"> <button>Ver Detalles</button></a>
                                </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection