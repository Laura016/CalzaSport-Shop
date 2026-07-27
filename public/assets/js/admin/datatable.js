$(document).ready(function () {

    $('#tablaProductos').DataTable({

        responsive: true,

        pageLength: 10,

        language: {

            search: "Buscar:",

            lengthMenu: "Mostrar _MENU_ registros",

            info: "Mostrando _START_ a _END_ de _TOTAL_ productos",

            paginate: {

                previous: "Anterior",

                next: "Siguiente"

            },

            zeroRecords: "No se encontraron productos",

            emptyTable: "No hay productos registrados"

        }

    });

});