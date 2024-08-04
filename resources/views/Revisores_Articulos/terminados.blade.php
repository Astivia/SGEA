@extends('layouts.master')
    <title>Articulos revisados</title>
@section('Content')
    <div class="container">
        <div class="search-create">
            <h1 id="titulo-h1">Articulos Revisados</h1>
        </div>
        @if($articulos->isEmpty())
            <strong>Aun no ha revisado ningun Articulo</strong>
        @else
            <div class="ajuste">
                <table id="example" class="display nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th>ARTICULO</th>
                            <th>puntuacion asignada</th>
                            <th>estado</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($articulos as $ra)
                            <tr>
                                <td><a href="{!! url(session('eventoID').'/articulo/'.$ra->articulo->id) !!}" style="color:#000;">{!!$ra->articulo->titulo!!} </a></td>
                                <td><strong>{!!$ra->puntuacion!!} / 30</strong></td>
                                <td>
                                    {!!$ra->articulo->estado!!}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection