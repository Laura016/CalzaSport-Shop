<div class="product-card-mobile">

    <img src="assets/img/<?php echo $producto['imagen']; ?>"
         class="product-card-img"
         alt="<?php echo htmlspecialchars($producto['nombre']); ?>">

    <div class="product-card-body">

        <h3><?php echo htmlspecialchars($producto['nombre']); ?></h3>

        <p>
            <strong>Ref:</strong>
            <?php echo htmlspecialchars($producto['referencia']); ?>
        </p>

        <p><?php echo htmlspecialchars($producto['marca']); ?></p>

        <p><?php echo htmlspecialchars($producto['categoria']); ?></p>

        <div class="product-card-price">

            $<?php echo number_format($producto['precio'],0,",","."); ?>

        </div>

        <div class="product-card-stock">

            Stock: <?php echo $producto['stock']; ?>

        </div>

        <span class="badge <?php echo $producto['estado']=="Disponible" ? "badge-success":"badge-danger"; ?>">

            <?php echo $producto['estado']; ?>

        </span>

        <div class="product-card-actions">

            <a href="admin.php?accion=editar&id=<?php echo $producto['id']; ?>"
               class="btn-edit">

                <i class="fa-solid fa-pen-to-square"></i>

            </a>

            <a href="#"
               class="btn-delete eliminarProducto"
               data-id="<?php echo $producto['id']; ?>">

                <i class="fa-solid fa-trash"></i>

            </a>

        </div>

    </div>

</div>