document.addEventListener('DOMContentLoaded', function () {

    const inputImagen = document.getElementById('imagen');
    const preview = document.getElementById('imagenPreview');
    const placeholder = document.getElementById('imagenPlaceholder');

    if (!inputImagen || !preview || !placeholder) {
        return;
    }

    inputImagen.addEventListener('change', function () {

        const archivo = this.files[0];

        if (!archivo) {
            preview.src = '';
            preview.style.display = 'none';
            placeholder.style.display = 'flex';
            return;
        }

        const tiposPermitidos = [
            'image/jpeg',
            'image/png',
            'image/webp'
        ];

        if (!tiposPermitidos.includes(archivo.type)) {

            alert(
                'Selecciona una imagen JPG, JPEG, PNG o WEBP.'
            );

            this.value = '';

            preview.src = '';
            preview.style.display = 'none';
            placeholder.style.display = 'flex';

            return;
        }

        if (archivo.size > 5 * 1024 * 1024) {

            alert(
                'La imagen no puede superar los 5 MB.'
            );

            this.value = '';

            preview.src = '';
            preview.style.display = 'none';
            placeholder.style.display = 'flex';

            return;
        }

        const lector = new FileReader();

        lector.onload = function (e) {

            preview.src = e.target.result;

            preview.style.display = 'block';

            placeholder.style.display = 'none';
        };

        lector.readAsDataURL(archivo);

    });

});