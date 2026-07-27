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