<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
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
        <br><br>                   
        <label for="participante-name">Nombre:</label>
        {!! Form::text ('nombre',$part->nombre)!!}
        <br><br>                   
                            
        <label for="participante-lastName">Apellidos:</label>
        {!! Form::text ('apellidos',$part->apellidos)!!}
        <br><br>                   
                            
        <label for="participante-curp">CURP:</label>
        {!! Form::text ('curp',$part->curp)!!}
        <br><br>                   
        
        <label for="participante-email">Email:</label>
        {!! Form::text ('email',$part->email)!!}
        <br><br>                   
                            
        <label for="participante-pass">Contraseña:</label>
        {!! Form::text ('password',$part->password)!!}
        <br><br>                   
        
        <button type="submit">Guardar</button>
    {!!Form::close()!!}   

    

</body>
</html>