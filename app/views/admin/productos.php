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

        <h1>Productos</h1>

    </div>

    <a href="admin.php?accion=nuevo" class="btn-primary">

        <i class="fa-solid fa-plus"></i>

        Nuevo Producto

    </a>

</div>

    <div class="table-container">

        <table id="tablaProductos">

            <thead>

                <tr>

                    <th>Imagen</th>

                    <th>Nombre</th>

                    <th>Referencia</th>

                    <th>Categoría</th>

                    <th>Marca</th>

                    <th>Precio</th>

                    <th>Stock</th>

                    <th>Estado</th>

                    <th>Destacado</th>

                    <th>Acciones</th>

                </tr>

            </thead>

            <tbody>

            <?php foreach($productos as $producto): ?>

                <tr>

                    <td>

                        <img
src="assets/img/<?php echo $producto['imagen']; ?>"
class="img-producto"
alt="<?php echo $producto['nombre']; ?>">

                    </td>

                    <td>

                        <?php echo $producto['nombre']; ?>

                    </td>

                    <td>

                        <?php echo $producto['referencia']; ?>

                    </td>

                    <td>

                        <?php echo $producto['categoria']; ?>

                    </td>

                    <td>

                        <?php echo $producto['marca']; ?>

                    </td>

                    <td>

                        $<?php echo number_format($producto['precio'],0,',','.'); ?>

                    </td>

                    <td>

                        <?php echo $producto['stock']; ?>

                    </td>

                    <td>

                        <?php echo $producto['estado']; ?>

                    </td>

                    <td>

                        <?php
                        echo $producto['destacado']
                        ? '⭐'
                        : '-';
                        ?>

                    </td>

                    <td>

                        <a
                        href="admin.php?accion=editar&id=<?php echo $producto['id']; ?>">

                        ✏️

                        </a>

                        <a
                        href="admin.php?accion=eliminar&id=<?php echo $producto['id']; ?>">

                        🗑️

                        </a>

                    </td>

                </tr>

            <?php endforeach; ?>

            </tbody>

        </table>

    </div>

</main>

<?php

require_once 'layouts/footer.php';

?>