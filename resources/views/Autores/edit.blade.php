@extends('layouts.master')
    <title>Modificar Datos</title>
</head>
@section('Content')
    <div class="container">
        <h1>Modificar informacion</h1>
        {!! Form::open(['method'=>'PATCH','url'=>'/autores/'.$autor->id]) !!}
                           
            <label for="participante">Seleccionar usuario:</label>
            {!! Form::select('usuario_id', $usuarios->pluck('nombre_completo', 'id'), $autor->usuario_id, ['required' => 'required']) !!}

            <br><br>
            <label for="afiliacion">Afiliacion:</label>
            {!! Form::text ('afiliacion',$autor->afiliacion,['require'])!!}
    
            <br><br>
            <button type="submit">Guardar cambios</button>
            <a href="{{!!asset('/autores')!!}"><button>Cancelar</button></a> 
        {!!Form::close()!!}
    </div>

    
@endsection