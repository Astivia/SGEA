@include ('layouts.Header')

@include ('layouts.sidebar')
    <body>
        @if(session('error'))
            <script>
                alert('{{ session('error') }}');
            </script>
        @endif

        @if(session('success'))
            <script>
                alert('{{ session('success') }}');
            </script>
        @endif
        <div class="main-content">
            @include ('layouts.head')
            <main>
                @yield('Content')
            </main>
        </div>
        <script src="{{asset('SGEA/public/js/script.js')}}"></script>

    </body>
@include ('layouts.Footer')


    <!-- Se agrega script para tablas -->
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
             
