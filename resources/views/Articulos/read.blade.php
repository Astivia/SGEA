@extends('layouts.master')
    <title>Modificar Datos</title>
    
</head>
@section('Content')
    <div class="container">
        <h1>{!!$articulo->titulo!!}</h1>
        <p><strong>Evento: </strong>{!!$articulo->evento->nombre!!} (<strong>{!!$articulo->evento->acronimo!!} {!!$articulo->evento->edicion!!}</strong>)</p>
        <p><strong>Autores: </strong>
            @if($articulo->autores->count() > 0)
                @foreach ($articulo->autores as $autor)
                    <i class="las la-pen-nib"></i>{!!$autor->usuario->nombre_completo!!}
                    <a href="{!! url('usuarios/'.$autor->usuario->id) !!}"><i class="las la-info-circle la-1x"></i></a>
                @endforeach
            @elseif($articulo->autoresExternos->count() > 0)
                @foreach ($articulo->autoresExternos as $autor)
                    <i class="las la-external-link-alt"></i>{!!$autor->nombre_completo!!}
                    <a href="{!! url('autores_externos/'.$autor->id) !!}"><i class="las la-info-circle la-1x"></i></a>
                @endforeach
            @endif
        </p>
        <p><strong>Area: </strong>{!!$articulo->area->nombre!!}</p>
        <p><strong>Revisores: </strong></p>
        <p><strong>Estado: </strong>{!!$articulo->estado!!}</p>
        <p><strong> Archivo: </strong>{!!$articulo->pdf!!}</p><br><br>
        @if($pdfUrl)
            
            
            <div id="pdf-viewer" >
                <iframe src="{!!$pdfUrl !!}" frameborder="0" style="width:90%; height: 70%;"></iframe>
            </div>
            <br><br>
            <a href="{!!$pdfUrl !!}" target="_blank"><button><i class="las la-file-pdf la-2x"></i> Abrir en nueva Pestaña</button></a>
        @else
            <strong>No hay archivo para mostrar</strong>
        @endif
    </div>

@endsection