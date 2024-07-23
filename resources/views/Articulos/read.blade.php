@extends('layouts.master')
    <title>Modificar Datos</title>
    
</head>
@section('Content')
    <div class="container">
        
        <h1>{!!$articulo->titulo!!}</h1>
        <p>
            <strong>Autores: </strong>
            <br>
            @foreach ($autores as $index => $autor)
            <strong>{!! $autor->orden !!}. </strong>
                @if ($index !== count($autores) - 1)
                    {!! $autor->usuario->nombre_completo !!},
                @else
                    {!! $autor->usuario->nombre_completo !!}.
                @endif
            @endforeach
        </p>
        <p><strong>Correspondencia: </strong>{!! $correspondencia->usuario->email!!} ({!! $correspondencia->usuario->nombre_completo!!})</p>
        <p><strong>Evento: </strong>{!!$articulo->evento->nombre!!} (<strong>{!!$articulo->evento->acronimo!!} {!!$articulo->evento->edicion!!}</strong>)</p>
        <p><strong>Resumen: </strong>{!!$articulo->resumen!!}</p>
        <p><strong>Area: </strong>{!!$articulo->area->nombre!!}</p>
        <p><strong>Revisores: </strong></p>
        <p><strong>Estado: </strong>{!!$articulo->estado!!}</p>
        <p><strong> Archivo: </strong>{!!$articulo->archivo!!}</p><br><br>
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