@extends('layouts.master')
<title>Revision de Articulo</title>

@section('Content')
    <div class="container">
        <h1>{!! $articulo->titulo !!}</h1><br>
        <p><strong>Evento: </strong>{!!$articulo->evento->nombre!!} (<strong>{!!$articulo->evento->acronimo!!} {!!$articulo->evento->edicion!!}</strong>)</p>
        <p>
            <strong>Autores: </strong><br>
                <ul style="margin-left: 4%">
                    @foreach ($autores as $index => $autor)
                        <li>
                            <strong>{!! $autor->orden !!}. </strong> 
                            <a href="{{url('usuarios/'.$autor->usuario->id )}}" style="color:#000;">
                            {!! $autor->usuario->nombre_completo !!}
                            </a>
                        </li>
                    @endforeach
                </ul>
        </p>
        <p><strong>Area: </strong>{!! $articulo->area->nombre !!}</p>
        <p><strong>Correspondencia: </strong><a href="mailto:{!! $articulo->autor_correspondencia->email!!}"> {!! $articulo->autor_correspondencia->email!!} </a>({!! $articulo->autor_correspondencia->usuario->nombre_completo!!})</p>
        <strong>Resumen:</strong>
        <div class="resumen">
            {!! $articulo->resumen !!}
        </div>
        <p><strong> Archivo: </strong>
            @if(!$articulo->archivo)
                No asignado
            @else
                {!!$articulo->archivo!!}
            @endif
        </p>
        
        @if($pdfUrl)
        <a href="{{ url()->previous() }}"><button> Cancelar</button></a>
        <a href="{!!$pdfUrl !!}" target="_blank"><button><i class="las la-file-pdf"></i> Ver en nueva Pestaña</button></a>
            <div class="LectorPDF">
                <br><br>
                <div id="pdf-viewer" >
                    <iframe src="{!!$pdfUrl !!}" frameborder="0" ></iframe>
                </div>
                <br><br>
            </div>
        @endif
    </div>
@endsection