@extends('layouts.master')
<title>Articulos por Revisar</title>

@section('Content')
    <div class="container">
        <div class="search-create">
            <h1 id="titulo-h1">Articulos por Revisar</h1>
        </div>
        @if($articulos->isEmpty())
            <strong>No Tiene articulos Pendientes</strong>
        @else
            <table>
                <thead>
                    <th>Articulo</th>
                    <th>Autores</th>
                    <th>Correspondencia</th>
                    <th>Controles</th>
                </thead>
                <tbody>
                    @foreach ($articulos as $art)
                        <td><strong>{!! $art->titulo!!}</strong></td>
                        <td>
                            <ul>
                                @foreach ($art->autores->sortBy('orden') as $autor)
                                <li>
                                    <a href="{{url ('usuarios/'.$autor->usuario->id) }}" style="color:#000;">{{ $autor->orden }}. {{ $autor->usuario->nombre_autor}} </a>
                                </li>
                                @endforeach
                            </ul>
                        </td>
                        <td>
                            <a href="mailto:{!!$art->autor_correspondencia->email!!}" style="text-decoration:underline;">{!!$art->autor_correspondencia->email!!}</a>
                        </td>
                        <td>
                            <a href=""><button>Iniciar Revison</button></a>
                        </td>
                    @endforeach
                </tbody>
            </table>
        @endif

    </div>

@endsection