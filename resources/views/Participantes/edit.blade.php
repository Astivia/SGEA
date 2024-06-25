@extends('layouts.master')
    <title>Modificar Datos</title>
</head>
@section('Content')
<div class="container">
        @if(session('info'))
            <div class="alert alert-succes">
                <strong>{{session('info')}}</strong>
            </div>
    
        @endif

        <h1>MODIFICAR DATOS</h1>
        {!! Form::open(['method'=>'PATCH','url'=>'/participantes/'.$part->id]) !!}
            
            <label for="event-name">Seleccionar evento :</label>

            <select name="evento_id" require>
            @foreach ($events as $e)
                <option value="{{$e->id }}" {{ $e->id == $part->evento_id ?'selected':''}}>
                    {{ $e->acronimo }} {{ $e->edicion }}
                </option>
            @endforeach
            </select>
         

            <label for="participante-name">Nombre:</label>
            {!! Form::text ('nombre',$part->nombre)!!}              
                                
            <label for="participante-lastName">Apellido Paterno:</label>
            {!! Form::text ('ap_pat',$part->ap_pat)!!}
                            
                                
            <label for="participante-lastName">Apellido Materno:</label>
            {!! Form::text ('ap_mat',$part->ap_mat)!!}
               
                                
            <label for="participante-curp">CURP:</label>
            {!! Form::text ('curp',$part->curp)!!}

            <label for="participante-email">Email:</label>
            {!! Form::text ('email',$part->email)!!}
                 
                                
            <label for="participante-pass">Nueva Contraseña:</label>
            {!! Form::text ('password',null)!!}

            <h3>Seleccionar Rol:</h3>
            @foreach($roles as $role)
            <div class="">
                <label>
                    {!! Form::checkbox('roles[]',$role->id,null,['class' =>'mr-1'])!!}
                    {{$role->name}}
                </label>
            </div>

            @endforeach
               
            
            <button type="submit">Guardar</button>
            <a href="{{asset('SGEA/public/participantes')}}"><button>Cancelar</button></a>
        {!!Form::close()!!}   
    </div>

    
@endsection