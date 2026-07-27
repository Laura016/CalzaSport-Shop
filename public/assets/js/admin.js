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

const menu=document.querySelector(".sidebar");

const overlay=document.querySelector(".overlay");

const boton=document.getElementById("menuToggle");

if(boton){

boton.addEventListener("click",()=>{

menu.classList.toggle("active");

overlay.classList.toggle("active");

});

overlay.addEventListener("click",()=>{

menu.classList.remove("active");

overlay.classList.remove("active");

});

document.querySelectorAll(".eliminarProducto").forEach((boton)=>{

    boton.addEventListener("click",(e)=>{

        e.preventDefault();

        const id = boton.dataset.id;

        Swal.fire({

            title:"¿Eliminar producto?",

            text:"Esta acción eliminará también la imagen.",

            icon:"warning",

            showCancelButton:true,

            confirmButtonColor:"#2563EB",

            cancelButtonColor:"#DC2626",

            confirmButtonText:"Sí, eliminar",

            cancelButtonText:"Cancelar"

        }).then((result)=>{

            if(result.isConfirmed){

                window.location="admin.php?accion=eliminar&id="+id;

            }

        });

    });

});

}

/* ==========================
   Vista previa de imagen
========================== */

const inputImagen = document.getElementById("imagen");
const previewImagen = document.getElementById("previewImagen");

if (inputImagen && previewImagen) {

    inputImagen.addEventListener("change", function () {

        const archivo = this.files[0];

        if (!archivo) return;

        const reader = new FileReader();

        reader.onload = function (e) {

            previewImagen.src = e.target.result;

        };

        reader.readAsDataURL(archivo);

    });

}