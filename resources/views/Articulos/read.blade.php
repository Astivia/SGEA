@extends('layouts.master')
    <title>Modificar Datos</title>
    
</head>
@section('Content')
    <div class="container">
        <h1>{!!$articulo->titulo!!}</h1>
        <p><strong>Evento: </strong>{!!$articulo->evento->nombre!!} (<strong>{!!$articulo->evento->acronimo!!} {!!$articulo->evento->edicion!!}</strong>)</p>
        <p>
            <i class="las la-pen-nib"></i>
            <strong>Autores: </strong>
            <br>
            <ul style="margin-left: 4%">
                @foreach ($autores as $index => $autor)
                <li>
                    <strong>{!! $autor->orden !!}. </strong> 
                    {!! $autor->usuario->nombre_completo !!}
                    <a href="{{url('usuarios/'.$autor->usuario->id )}}"><i class="las la-info-circle"></i></a>
                </li>
                @endforeach
            </ul>
        </p>
        <p><i class="las la-envelope"></i> <strong>Correspondencia: </strong><a href="mailto:{!! $articulo->autor_correspondencia->email!!}"> {!! $articulo->autor_correspondencia->email!!} </a>({!! $articulo->autor_correspondencia->usuario->nombre_completo!!})</p>
        <p><i class="las la-file-alt"></i> <strong>Resumen: </strong>{!!$articulo->resumen!!}</p>
        <p><i class="las la-id-card"></i> <strong>Area: </strong>{!!$articulo->area->nombre!!}</p>
        <p><strong>Revisores: </strong></p>
        <p><strong>Estado: </strong>{!!$articulo->estado!!}</p>
        <p><strong> Archivo: </strong>{!!$articulo->archivo!!}</p>
        @if($pdfUrl)
            <a href="{{ url()->previous() }}"><button><i class="las la-arrow-left"></i> Regresar</button></a>
            <a href="{{url('articulos/'.$articulo->evento->id.'/'.$articulo->id.'/edit')}}"><button><i class="las la-edit"></i> Modificar Articulo</button></a>
            <a href="{!!$pdfUrl !!}" target="_blank"><button><i class="las la-file-pdf"></i> Ver en nueva Pestaña</button></a>
            <br><br>
            <div id="pdf-viewer" >
                <iframe src="{!!$pdfUrl !!}" frameborder="0" style="width:90%; height: 70%;"></iframe>
            </div>
            <br><br>
            
        @else
            <strong>No hay archivo para mostrar</strong>
        @endif
        
    </div>

@endsection