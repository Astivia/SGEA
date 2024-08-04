@extends('layouts.master')
    <title>Detalles del articulo</title>

@section('Content')
    <div class="container">
        <h1>{!!$articulo->titulo!!}</h1>
        <p class="pRead"><i class="las la-calendar-plus"></i><strong>Evento: </strong>{!!$articulo->evento->nombre!!} (<strong>{!!$articulo->evento->acronimo!!} {!!$articulo->evento->edicion!!}</strong>)</p>
        <p class="pRead">
            <i class="las la-pen-nib"></i>
            <strong>Autores: </strong>
            <br>
            <ul style="margin-left: 4%">
                @foreach ($autores as $index => $autor)
                    <li>
                        <strong>{!! $autor->orden !!}. </strong> 
                        {!! $autor->usuario->nombre_completo !!}
                        <a href="{{url(session('eventoID').'/autor/'.$autor->usuario->id )}}"><i class="las la-info-circle"></i></a>
                    </li>
                @endforeach
            </ul>
        </p>
        @role('Administrador')
            <p class="pRead"><i class="las la-envelope"></i> <strong>Correspondencia: </strong><a href="mailto:{!! $articulo->autor_correspondencia->email!!}"> {!! $articulo->autor_correspondencia->email!!} </a>({!! $articulo->autor_correspondencia->usuario->nombre_completo!!})</p>
        @endrole
        <p class="pRead"><i class="las la-file-alt"></i> <strong>Resumen: </strong>{!!$articulo->resumen!!}</p>
        <p class="pRead"><i class="las la-id-card"></i> <strong>Area: </strong>{!!$articulo->area->nombre!!}</p>
        <p class="pRead"><i class="las la-folder-open"></i><strong>Revisores: </strong>
                @foreach ($revisores as $index => $revisor)
                        {!! $revisor->usuario->nombre_completo !!}<a href="{{url('usuarios/'.$autor->usuario->id )}}"><i class="las la-info-circle"></i></a>
                @endforeach
        </p>
        <p class="pRead"><i class="las la-history"></i><strong>Estado: </strong>{!!$articulo->estado!!}</p>
        <p class="pRead"><i class="las la-folder"></i><strong> Archivo: </strong>{!!$articulo->archivo!!}</p>
        <a href="{{ url()->previous() }}"><button><i class="las la-arrow-circle-left"></i> Regresar</button></a> 
        @role('Administrador')
            <a href="{{url($articulo->evento->id.'/articulo/'.$articulo->id.'/edit')}}"><button><i class="las la-edit"></i> Modificar Articulo</button></a>
        @endrole
        @if($pdfUrl)
            <a href="{!!$pdfUrl !!}" target="_blank"><button><i class="las la-file-pdf"></i> Ver en nueva Pestaña</button></a>
            <br><br>
            <div id="pdf-viewer" >
                <iframe src="{!!$pdfUrl !!}" frameborder="0" style="width:90%; height: 70%;"></iframe>
            </div>
        @else
            <strong>No hay archivo para mostrar</strong>
        @endif
        
    </div>

@endsection