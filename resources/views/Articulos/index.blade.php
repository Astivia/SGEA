@extends('layouts.master')
    <title>Articulos</title>
    <!-- <link rel="stylesheet" href="./css/style-articulos.css"> -->
</head>
@section('Content')
    <div class="container">
        
        <div class="search-create">
        <h1>Artículos en Sistema</h1>
            <button id="create-event-btn"><i class="las la-plus-circle la-2x"></i></button>
            
        </div>
   
      @if($Articulos->isEmpty())
            <strong>No hay datos</strong>
      @else        
        <table id="example" class="table table-striped" style="width:100%">
        <thead>
                <tr>
                    <th>EVENTO</th>
                    <th>TITULO</th>
                    <th>AUTORES</th>
                    <th>ESTADO</th>
                    <th>Controles</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($Articulos as $art)
                <tr>
                    <td>{!!$art->evento->acronimo!!} {!!$art->evento->edicion!!}</td>
                    <td><strong>{!!$art->titulo!!}</strong></td>
                    <td>
                        <ul>
                            @if($art->autores->count() > 0)
                                @foreach ($art->autores as $autor)
                                    <li><i class="las la-pen-nib"></i>{!!$autor->usuario->nombre_completo!!} </li>
                                    <a href="{!! 'usuarios/'.$autor->usuario->id !!}"><i class="las la-info-circle la-1x"></i></a>
                                @endforeach
                            @elseif($art->autoresExternos->count() > 0)
                                @foreach ($art->autoresExternos as $autor)
                                    <li><i class="las la-external-link-alt"></i>{!!$autor->nombre_completo!!} </li>
                                    <a href="{!! 'autores_externos/'.$autor->id !!}"><i class="las la-info-circle la-1x"></i></a>
                                @endforeach
                            @endif
                        </ul>
                    </td>
                    <td>{!!$art->estado!!}</td>
                    <td>
                        <a href="{!! 'articulos/'.$art->id !!}"><i class="las la-info-circle la-2x"></i></a>
                        <a href="{!!'articulos/'.$art->id.'/edit'!!}">
                         <i class="las la-edit la-2x"></i>
                        </a>
                        <a href="{{url('articulos/'.$art->id)}}" onclick="event.preventDefault(); if (confirm('¿Estás seguro de que deseas eliminar este registro?')) { document.getElementById('delete-form-{{ $art->id }}').submit(); }">
                        <i class="las la-trash-alt la-2x"></i>
                        </a>
                        <form id="delete-form-{{ $art->id }}" action="{{ url('articulos/'.$art->id) }}" method="POST" style="display: none;">
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
        
        <div id="create-article-modal" class="modal">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <h2>Registro de Artículo</h2>
                    {!! Form::open(['url'=>'/articulos']) !!}
                        <label for="evento">Seleccionar evento :</label>
                        <select name="evento_id" required>
                            @foreach ($Eventos as $evento)
                                <option value="{{ $evento->id }}">{{ $evento->acronimo }} {{ $evento->edicion }}</option>
                            @endforeach
                        </select>
                        <br>
                        <label for="titulo">Titulo:</label>
                        <input type="text" id="titulo" name="titulo" required>

                        <label for="autor">Seleccionar Autor</label>
                        {!! Form::select('autor_id_autor', $autoresSistema, null, ['id' => 'autor_id_autor', 'placeholder' => 'Seleccionar del sistema...', 'required' => 'required']) !!}
                        
                        {!! Form::select('autor_id_ext', $autoresExternos, null, ['id' => 'autor_id_ext', 'placeholder' => 'Seleccionar Externo ...', 'required' => 'required']) !!}
                        
                        <br><br>

                        <label for="area">Seleccionar Area :</label>
                        <select name="area_id" required>
                            @foreach ($Areas as $area)
                                <option value="{{ $area->id }}">{{ $area->nombre }}</option>
                            @endforeach
                        </select>

                        <br><br>

                        <button type="submit">Guardar articulo</button>
                    {!! Form::close() !!}
                </div>
            </div>
@endsection
<script src="./js/script-articulos.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var autorSistemaSelect = document.getElementById('autor_id_autor');
        var autorExternoSelect = document.getElementById('autor_id_ext');

        autorSistemaSelect.addEventListener('change', function () {
            if (autorSistemaSelect.value) {
                autorExternoSelect.disabled = true;
            } else {
                autorExternoSelect.disabled = false;
            }
        });

        autorExternoSelect.addEventListener('change', function () {
            if (autorExternoSelect.value) {
                autorSistemaSelect.disabled = true;
            } else {
                autorSistemaSelect.disabled = false;
            }
        });

    });
</script>