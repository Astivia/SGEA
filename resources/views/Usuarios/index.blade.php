@extends('layouts.master')
<title>Usuarios</title>
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
    <link rel="" href="https://cdn.datatables.net/fixedheader/3.1.6/css/fixedHeader.dataTables.min.css">
</head>
@section('Content')
<div class="container">
    <h1>Usuarios</h1>
    <div class="search-create">
        <input type="text" id="search-input" placeholder="Buscar Usuario...">
        <button id="create-event-btn"><i class="las la-plus-circle la-2x"></i></button>
    </div>

<br><br>


    <h1>Usuarios del sistem</h1>
    <table id="example" class="display" style="width:200px">
                <thead>
                    <tr>
                    <th>NOMBRE</th>
                <th>APELLIDOS</th>
                <th>EMAIL</th>
                <th>CURP</th>
                @role(['Administrador','Organizador'])
                <th>Controles</th>
                @endrole
                    </tr>
                </thead>
                <tbody>
                    
            @foreach ($Usuarios as $usu)
            <tr>
                <td>{!!$usu->nombre!!}</td>
                <td>{!!$usu->ap_pat!!} {!!$usu->ap_mat!!}</td>
                <td>{!!$usu->email!!}</td>
                <td>{!!$usu->curp!!}</td>
                @role(['Administrador','Organizador'])
                <td>
                    <a href="{!! 'usuarios/'.$usu->id !!}"><i class="las la-info-circle la-2x"></i></a>
                    <a href="{!!'usuarios/'.$usu->id.'/edit'!!}">
                        <i class="las la-user-edit la-2x"></i>
                    </a>
                    <!-- <a href="{{url('usuarios/'.$usu->id)}}" onclick="
                                            event.preventDefault(); 
                                            if (confirm('¿Estás seguro de que deseas eliminar este registro?')) 
                                            { document.getElementById('delete-form-{{ $usu->id }}').submit(); }">
                        <i class="las la-user-minus la-2x"></i>
                    </a>
                    <form id="delete-form-{{ $usu->id }}" action="{{ url('usuarios/'.$usu->id) }}" method="POST"
                        style="display: none;">
                        @method('DELETE')
                        @csrf
                    </form> -->
                    <a id="mensaje" href="#" onclick="openModal('{{ $usu->id }}')">
                     <i class="las la-user-minus la-2x"></i> 
                </a>

                <!-- Modal -->
                <div id="confirmModal" class="modal">
                    <div class="modal-content">
                        <span class="close" onclick="closeModal()">&times;</span>
                        <p>¿Estás seguro de que deseas eliminar este registro?</p>
                        <button onclick="deleteRecord('{{ $usu->id }}')">Eliminar</button>
                    </div>
                </div>

                <form id="delete-form-{{ $usu->id }}" action="{{  url('usuarios/'.$usu->id)  }}" method="POST" style="display: none;">
                    @csrf
                    @method('DELETE')
                </form>
                </td>
                @endrole
            </tr>
            @endforeach
                </tbody>               
 </table>  

    <button id="btn1">clon</button>

</div>
    
    



<div id="create-event-modal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Nuevo Usuario</h2>
        {!! Form::open(['url'=>'/usuarios']) !!}


        <label for="participante-name">Nombre:</label>
        <input type="text" id="participante-name" name="nombre" required>

        <label for="participante-lastName">Apellido Paterno:</label>
        <input type="text" id="participante-lastName" name="ap_pat" required>

        <label for="participante-2lastName">Apellido Materno:</label>
        <input type="text" id="participante-2lastName" name="ap_mat" required>

        <label for="participante-curp">CURP:</label>
        <input type="text" id="participante-curp" name="curp" required>

        <label for="participante-email">Email:</label>
        <input type="text" id="participante-email" name="email" required>

        <label for="participante-pass">Contraseña:</label>
        <input type="password" id="participante-password" name="password" required>

        <button type="submit">Guardar</button>
        {!!Form::close()!!}
    </div>
</div>


@endsection
<!-- <script src="./js/script-participantes.js"></script> -->
<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/fixedheader/3.1.6/js/dataTables.fixedHeader.min.js"></script> 

<script>
let temp = $("#btn1").clone();
$("#btn1").click(function(){
    $("#btn1").after(temp);
});

$(document).ready(function(){
    var table = $('#example').DataTable({
       orderCellsTop: true,
       fixedHeader: true 
    });

    //Creamos una fila en el head de la tabla y lo clonamos para cada columna
    $('#example thead tr').clone(true).appendTo( '#example thead' );

    $('#example thead tr:eq(1) th').each( function (i) {
        var title = $(this).text(); //es el nombre de la columna
        $(this).html( '<input type="text" placeholder="Search...'+title+'" />' );
 
        $( 'input', this ).on( 'keyup change', function () {
            if ( table.column(i).search() !== this.value ) {
                table
                    .column(i)
                    .search( this.value )
                    .draw();
            }
        } );
    } );   
});

function openModal(id) {
    var modal = document.getElementById('confirmModal');
    modal.style.display = 'block';
    // Guardamos el ID del registro que se desea eliminar en un atributo data del modal
    modal.setAttribute('data-record-id', id);
}

// Función para cerrar el modal
function closeModal() {
    var modal = document.getElementById('confirmModal');
    modal.style.display = 'none';
}

// Función para eliminar el registro
function deleteRecord(id) {
    var modal = document.getElementById('confirmModal');
    modal.style.display = 'none';
    // Enviamos el formulario de eliminación correspondiente
    document.getElementById('delete-form-' + id).submit();
}

 

</script>


<!-- <script>
   function openModal(id) {
    var modal = document.getElementById('confirmModal');
    modal.style.display = 'block';
    // Guardamos el ID del registro que se desea eliminar en un atributo data del modal
    modal.setAttribute('data-record-id', id);
}

// Función para cerrar el modal
function closeModal() {
    var modal = document.getElementById('confirmModal');
    modal.style.display = 'none';
}

// Función para eliminar el registro
function deleteRecord(id) {
    var modal = document.getElementById('confirmModal');
    modal.style.display = 'none';
    // Enviamos el formulario de eliminación correspondiente
    document.getElementById('delete-form-' + id).submit();
}

let temp = $("#btn1").clone();
$("#btn1").click(function(){
    $("#btn1").after(temp);
});

$(document).ready(function(){
    var table = $('#usuarios').DataTable({
       orderCellsTop: true,
       fixedHeader: true 
    });

    //Creamos una fila en el head de la tabla y lo clonamos para cada columna
    $('#usuarios thead tr').clone(true).appendTo( '#example thead' );

    $('#usuarios thead tr:eq(1) th').each( function (i) {
        var title = $(this).text(); //es el nombre de la columna
        $(this).html( '<input type="text" placeholder="Search...'+title+'" />' );
 
        $( 'input', this ).on( 'keyup change', function () {
            if ( table.column(i).search() !== this.value ) {
                table
                    .column(i)
                    .search( this.value )
                    .draw();
            }
        } );
    } );   
});

</script> -->


