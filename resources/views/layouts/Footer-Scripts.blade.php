</html>

<script src="{{asset('SGEA/public/js/script-master.js')}}"></script>
<!-- Se agrega script para tablas -->
<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/fixedheader/3.1.6/js/dataTables.fixedHeader.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>

<script>
    let temp = $("#btn1").clone();
    $("#btn1").click(function(){
        $("#btn1").after(temp);
    });

    $(document).ready(function(){
        var table = $('#example').DataTable({
            orderCellsTop: true,        
            fixedHeader: true,
            // responsive: true,
            lengthMenu:[10,25,50,100],
            responsive: {
                details: true,
                // details: {
                //     renderer: function(api, rowIdx, columns) {
                //         var data = $.map(columns, function(col, i) {
                //             return col.hidden ?
                //                 '<tr data-dt-row="' + col.rowIndex + '" data-dt-column="' + col.columnIndex + '">' +
                //                     '<td>' + col.title + ':' + '</td> ' +
                //                     '<td>' + col.data + '</td>' +
                //                 '</tr>' :
                //                 '';
                //         }).join('');

                //         return data ?
                //             $('<table/>').append(data) :
                //             false;
                //     }
                // },
                // breakpoints: [{ name: 'phone', width: 700 }]
            },
        
            columnDefs: [
                { orderable: false, targets: [0, -1] },            
                { responsivePriority: 1, targets: 1}
            ],
        
            language: {
                url: "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
            }
        });
       
        var isResponsive = false;

        table.on('responsive-resize', function(e, datatable, columns) {
            isResponsive = columns.some(function(column) {
                return column === false;
            });

            if (!isResponsive && $('#example thead tr').length < 2) {
                $('#example thead tr').clone(true).appendTo('#example thead');

                $('#example thead tr:eq(1) th').each(function(i) {
                    var title = $(this).text();
                    if (i < $('#example thead tr:eq(1) th').length - 1 && i > 0) {
                        $(this).html('<input  type="text" placeholder="' + title + '" />');
                        $('input', this).on('keyup change', function() {
                            if (table.column(i).search() !== this.value) {
                                table
                                    .column(i)
                                    .search(this.value)
                                    .draw();
                            }
                        });
                    }
                });
            }
        });

        //eliminacion masiva 
        $('#selectAll').on('click', function(){
        var rows = table.rows({ 'search': 'applied' }).nodes();
        $('input[type="checkbox"]', rows).prop('checked', this.checked);
        });

        $('#example tbody').on('change', 'input[type="checkbox"]', function(){
            if(!this.checked){
                var el = $('#selectAll').get(0);
                if(el && el.checked && ('indeterminate' in el)){
                    el.indeterminate = true;
                }
            }
        });

        $('#deleteSelected').on('click', function(){
            var selectedIds = [];
            $('.selectRow:checked').each(function(){
                selectedIds.push($(this).data('id'));
            });
            console.log("IDs seleccionados:", selectedIds);
            if(selectedIds.length > 0){
                if(confirm('¿Estás seguro de que deseas eliminar los registros seleccionados?')){
                    $.ajax({
                        url: "{{ url('areas/delete-multiple') }}",
                        method: 'POST',
                        data: {
                            ids: selectedIds,
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response){                       
                            location.reload();
                        },
                        error: function(response){                      
                            alert('Error al eliminar los registros.');
                        }
                    });
                }
            } else {
                alert('No hay registros seleccionados');
            }
        });
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


@if(session('reload'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            fetchSidebar();
        });

        function fetchSidebar() {
            fetch('{{ route('get.sidebar') }}')
                .then(response => response.text())
                .then(html => {
                    document.getElementById('sidebar').innerHTML = html;
                });
        }
    </script>
@endif