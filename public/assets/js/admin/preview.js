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