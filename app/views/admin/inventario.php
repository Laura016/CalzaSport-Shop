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

        <a href="admin.php?accion=nuevo" class="btn-primary">

            <i class="fa-solid fa-plus"></i>

            Actualizar Inventario

        </a>

    </div>

    <div class="inventory-cards">

    <div class="inventory-card total">
        <h3>Total Productos</h3>
        <span><?php echo count($productos); ?></span>
    </div>

    <div class="inventory-card disponibles">
        <h3>Disponibles</h3>

        <span>
        <?php

        $totalDisponibles = 0;

        foreach($productos as $p){

            if($p['stock'] > 0){

                $totalDisponibles++;

            }

        }

        echo $totalDisponibles;

        ?>
        </span>

    </div>

    <div class="inventory-card agotados">

        <h3>Agotados</h3>

        <span>

        <?php

        $agotados=0;

        foreach($productos as $p){

            if($p['stock']==0){

                $agotados++;

            }

        }

        echo $agotados;

        ?>

        </span>

    </div>

    <div class="inventory-card bajo">

        <h3>Poco Stock</h3>

        <span>

        <?php

        $bajo=0;

        foreach($productos as $p){

            if($p['stock']>0 && $p['stock']<=5){

                $bajo++;

            }

        }

        echo $bajo;

        ?>

        </span>

    </div>

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