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

            <h1>Editar Producto</h1>

        </div>

    </div>

    <div class="form-container">

        <form action="admin.php?accion=actualizar" method="POST" enctype="multipart/form-data">

            <input type="hidden" name="id" value="<?php echo $producto['id']; ?>">

            <input type="hidden" name="imagen_actual" value="<?php echo $producto['imagen']; ?>">

            <div class="form-grid">

                <div class="form-group">

                    <label>Nombre</label>

                    <input type="text" name="nombre" value="<?php echo htmlspecialchars($producto['nombre']); ?>"
                        required>

                </div>

                <div class="form-group">

                    <label>Referencia</label>

                    <input type="text" name="referencia"
                        value="<?php echo htmlspecialchars($producto['referencia']); ?>" required>

                </div>

                <div class="form-group">

                    <label>Marca</label>

                    <input type="text" name="marca" value="<?php echo htmlspecialchars($producto['marca']); ?>">

                </div>

                <div class="form-group">

                    <label>Categoría</label>

                    <select name="categoria">

                        <option value="Running" <?php if ($producto['categoria'] == "Running")
                            echo "selected"; ?>>
                            Running
                        </option>

                        <option value="Casual" <?php if ($producto['categoria'] == "Casual")
                            echo "selected"; ?>>
                            Casual
                        </option>

                        <option value="Baloncesto" <?php if ($producto['categoria'] == "Baloncesto")
                            echo "selected"; ?>>
                            Baloncesto
                        </option>

                        <option value="Training" <?php if ($producto['categoria'] == "Training")
                            echo "selected"; ?>>
                            Training
                        </option>

                    </select>

                </div>

                <div class="form-group">

                    <label>Precio</label>

                    <input type="number" name="precio" value="<?php echo $producto['precio']; ?>" required>

                </div>

                <div class="form-group">

                    <label>Stock</label>

                    <input type="number" name="stock" value="<?php echo $producto['stock']; ?>">

                </div>

                <div class="form-group">

                    <label>Tallas</label>

                    <input type="text" name="tallas" value="<?php echo htmlspecialchars($producto['tallas']); ?>">

                </div>

                <div class="form-group">

                    <label>Destacado</label>

                    <select name="destacado">

                        <option value="1" <?php if ($producto['destacado'] == 1)
                            echo "selected"; ?>>

                            Sí

                        </option>

                        <option value="0" <?php if ($producto['destacado'] == 0)
                            echo "selected"; ?>>

                            No

                        </option>

                    </select>

                </div>

                <div class="form-right">

                    <div class="form-group">

                        <label>Imagen actual</label>

                        <input type="file" name="imagen" id="imagen">

                        <div class="preview-container">

                            <img id="previewImagen" src="assets/img/productos/<?php echo $producto['imagen']; ?>"
                                alt="Vista previa">

                        </div>

                        <small>
                            Si no seleccionas otra imagen, se conservará la actual.
                        </small>

                    </div>

                </div>

            </div>

            <div class="form-group">

                <label>Descripción</label>

                <textarea name="descripcion"
                    rows="5"><?php echo htmlspecialchars($producto['descripcion']); ?></textarea>

            </div>

            <button class="btn-primary">

                <i class="fa-solid fa-floppy-disk"></i>

                Actualizar Producto

            </button>

        </form>

    </div>

</main>

<?php

require_once 'layouts/footer.php';

?>