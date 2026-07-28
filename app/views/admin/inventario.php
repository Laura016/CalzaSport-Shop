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

            <h1>Inventario</h1>

        </div>

        <?php

        $href = "admin.php?accion=nuevo";
        $texto = "Actualizar Inventario";
        $icono = "fa-solid fa-arrows-rotate";
        $color = "primary";

        require __DIR__ . "/components/button.php";

        ?>

    </div>

    <div class="inventory-cards">

        <?php

        $titulo = "Total Productos";
        $valor = $totalProductos['total'];
        $icono = "fa-solid fa-box";
        $color = "total";

        require __DIR__ . "/components/stat-card.php";

        ?>

        <?php

        $titulo = "Disponibles";
        $valor = $productosDisponibles['total'];
        $icono = "fa-solid fa-circle-check";
        $color = "disponibles";

        require __DIR__ . "/components/stat-card.php";

        ?>

        <?php

        $titulo = "Agotados";
        $valor = $productosAgotados['total'];
        $icono = "fa-solid fa-circle-xmark";
        $color = "agotados";

        require __DIR__ . "/components/stat-card.php";

        ?>

        <?php

        $titulo = "Poco Stock";
        $valor = $productosBajoStock['total'];
        $icono = "fa-solid fa-triangle-exclamation";
        $color = "bajo";

        require __DIR__ . "/components/stat-card.php";

        ?>

    </div>


    <div class="table-container">

        <table id="tablaProductos">

            <thead>
                <tr>
                    <th>Imagen</th>
                    <th>Producto</th>
                    <th>Precio</th>
                    <th>Stock</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>

                <?php foreach ($productos as $producto): ?>

                    <tr>

                        <td>

                            <img src="assets/img/<?php echo $producto['imagen']; ?>" class="img-producto"
                                alt="<?php echo $producto['nombre']; ?>">

                        </td>

                        <td>

                            <div class="producto-info">

                                <strong>
                                    <?php echo $producto['nombre']; ?>
                                </strong>

                                <small>
                                    REF: <?php echo $producto['referencia']; ?>
                                </small>

                                <small>
                                    <?php echo $producto['marca']; ?>
                                </small>

                                <small>
                                    <?php echo $producto['categoria']; ?>
                                </small>

                            </div>

                        </td>

                        <td>

                            $<?php echo number_format($producto['precio'], 0, ',', '.'); ?>

                        </td>

                        <td>

                            <?php echo $producto['stock']; ?>

                        </td>

                        <td>

                            <?php echo $producto['estado']; ?>

                        </td>

                        <td class="acciones">

                            <a href="admin.php?accion=editar&id=<?php echo $producto['id']; ?>" class="btn-edit"
                                title="Editar">

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

    </div>

</main>

<?php

require_once 'layouts/footer.php';

?>