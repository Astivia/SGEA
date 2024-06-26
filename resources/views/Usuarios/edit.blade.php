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
        {!! Form::open(['method'=>'PATCH','url'=>'/usuarios/'.$usu->id]) !!}  

            <label for="participante-name">Nombre:</label>
            {!! Form::text ('nombre',$usu->nombre)!!}              
                                
            <label for="participante-lastName">Apellido Paterno:</label>
            {!! Form::text ('ap_pat',$usu->ap_pat)!!}
                            
                                
            <label for="participante-lastName">Apellido Materno:</label>
            {!! Form::text ('ap_mat',$usu->ap_mat)!!}
               
                                
            <label for="participante-curp">CURP:</label>
            {!! Form::text ('curp',$usu->curp)!!}

            <label for="participante-email">Email:</label>
            {!! Form::text ('email',$usu->email)!!}
                 
                                
            <label for="participante-pass">Nueva Contraseña:</label>
            {!! Form::text ('password',null)!!}

        @role('Administrador')
            <h3>Seleccionar Rol:</h3>
            @foreach($roles as $role)
            <div class="">
                <label>
                    {!! Form::checkbox('roles[]',$role->id,null,['class' =>'mr-1'])!!}
                    {{$role->name}}
                </label>
            </div>

            @endforeach
               
        @endrole
            <button type="submit">Guardar</button>
            <a href="{{asset('SGEA/public/usuarios')}}"><button>Cancelar</button></a>
        {!!Form::close()!!}   
    </div>

    
@endsection