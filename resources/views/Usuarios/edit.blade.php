@extends('layouts.master')
    <title>Modificar Datos</title>
</head>
@section('Content')
<div class="container">
        <h1>MODIFICAR DATOS</h1>
        {!! Form::open(['method'=>'PATCH','url'=>'/usuarios/'.$usu->id]) !!}  
            {!! Form::label('foto', 'Foto Actual:') !!}
            <div class="profile-photo">
                <img src="{{ asset($usu->foto)}}" alt="Imagen" id="actual-img" style="width: 15vw;">

                {!! Form::file('logo', ['id' => 'logo', 'class' => 'form-control', 'accept' => 'image/jpeg, image/png, image/webp']) !!}
                {!! Form::hidden('logo', null, ['id' => 'selected_img']) !!}
            </div>

            {!! Form::label('nombre', 'Nombre:') !!}
            {!! Form::text ('nombre',$usu->nombre)!!}              
                                
            {!! Form::label('ap_paterno', 'Apellido Paterno:') !!}
            {!! Form::text ('ap_paterno',$usu->ap_paterno)!!}
                                
            {!! Form::label('ap_materno', 'Apellido Materno:') !!}
            {!! Form::text ('ap_materno',$usu->ap_materno)!!}
                                
            {!! Form::label('curp', 'CURP:') !!}
            {!! Form::text ('curp',$usu->curp)!!}

            {!! Form::label('email', 'Email:') !!}
            {!! Form::text ('email',$usu->email)!!}

            {!! Form::label('password', 'Nueva Contraseña:') !!}
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
        {!!Form::close()!!}   
        <a href="{{ url()->previous() }}"><button>Cancelar</button></a> 
    </div>

    
@endsection