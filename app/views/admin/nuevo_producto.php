<?php

require_once 'layouts/header.php';
require_once 'layouts/sidebar.php';

?>

<main class="main-content">

    <div class="topbar">

        <div class="left-topbar">

            <button id="menuToggle">
                <i class="fa-solid fa-bars"></i>
            </button>

            <h1>Nuevo Producto</h1>

        </div>

    </div>

    <div class="form-container">

        <form action="admin.php?accion=guardar" method="POST" enctype="multipart/form-data">

           <div class="form-grid">

    <div class="form-left">

        <div class="form-group">
            <label>Nombre</label>
            <input type="text" name="nombre" required>
        </div>

        <div class="form-group">
            <label>Referencia</label>
            <input type="text" name="referencia" required>
        </div>

        <div class="form-group">
            <label>Marca</label>
            <input type="text" name="marca">
        </div>

        <div class="form-group">
            <label>Categoría</label>
            <select name="categoria">
                <option>Running</option>
                <option>Casual</option>
                <option>Baloncesto</option>
                <option>Training</option>
            </select>
        </div>

        <div class="form-group">
            <label>Precio</label>
            <input type="number" name="precio" required>
        </div>

        <div class="form-group">
            <label>Stock</label>
            <input type="number" name="stock" value="1">
        </div>

        <div class="form-group">
            <label>Tallas</label>
            <input type="text" name="tallas" placeholder="37,38,39,40">
        </div>

        <div class="form-group">
            <label>Destacado</label>
            <select name="destacado">
                <option value="1">Sí</option>
                <option value="0">No</option>
            </select>
        </div>

    </div>

    <div class="form-right">

        <div class="form-group">

            <label>Imagen del producto</label>

            <input type="file" name="imagen" id="imagen">

            <div class="preview-container">

                <img id="previewImagen"
                     src="assets/img/no-image.png"
                     alt="Vista previa">

            </div>

        </div>

    </div>

</div>

            <div class="form-group">

                <label>Descripción</label>

                <textarea name="descripcion" rows="5">
                </textarea>

            </div>

            <button class="btn-primary">

                <i class="fa-solid fa-floppy-disk"></i>

                Guardar Producto

            </button>

        </form>

    </div>

</main>

<?php

require_once 'layouts/footer.php';

?>