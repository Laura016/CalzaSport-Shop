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

        <?php

        $href = "admin.php?accion=nuevo";
        $texto = "Nuevo Producto";
        $icono = "fa-solid fa-plus";

        require_once __DIR__ . "/components/button.php";

        ?>

    </div>

    <div class="table-container">

        <table id="tablaProductos">

            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Categoría</th>
                    <th>Precio</th>
                    <th>Stock</th>
                    <th>Estado</th>
                    <th>Destacado</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>

                <?php foreach ($productos as $producto): ?>

                    <tr>

                        <td>

                            <div class="producto-item">

                                <img src="assets/img/productos/<?php echo $producto['imagen']; ?>" class="img-producto"
                                    alt="<?php echo htmlspecialchars($producto['nombre']); ?>">

                                <div class="producto-info">

                                    <strong>

                                        <?php echo htmlspecialchars($producto['nombre']); ?>

                                    </strong>

                                    <small>

                                        Ref: <?php echo htmlspecialchars($producto['referencia']); ?>

                                    </small>

                                    <small>

                                        <?php echo htmlspecialchars($producto['marca']); ?>

                                    </small>

                                </div>

                            </div>

                        </td>

                        <td>

                            <?php echo $producto['categoria']; ?>

                        </td>

                        <td>

                            $<?php echo number_format($producto['precio'], 0, ',', '.'); ?>

                        </td>

                        <td>

                            <?php echo $producto['stock']; ?>

                        </td>

                        <td>

                            <?php

                            $texto = $producto['estado'];

                            $tipo = ($producto['estado'] == "Disponible")
                                ? "success"
                                : "danger";

                            require __DIR__ . "/components/badge.php";

                            ?>

                        </td>

                        <td>

                            <?php

                            $texto = $producto['destacado']
                                ? "⭐ Destacado"
                                : "Normal";

                            $tipo = $producto['destacado']
                                ? "warning"
                                : "primary";

                            require __DIR__ . "/components/badge.php";

                            ?>

                        </td>

                        <td class="acciones">

                            <a href="admin.php?accion=editar&id=<?php echo $producto['id']; ?>" class="btn-edit">

                                <i class="fa-solid fa-pen-to-square"></i>

                            </a>

                            <a href="#" class="btn-delete eliminarProducto" data-id="<?php echo $producto['id']; ?>">

                                <i class="fa-solid fa-trash"></i>

                            </a>

                        </td>

                    </tr>

                <?php endforeach; ?>

            </tbody>

        </table>

        <div class="mobile-products">

            <?php foreach ($productos as $producto): ?>

                <?php require __DIR__ . "/components/product-card.php"; ?>

            <?php endforeach; ?>

        </div>

    </div>

</main>

<?php

require_once 'layouts/footer.php';

?>