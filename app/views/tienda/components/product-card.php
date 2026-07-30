<div class="product-card">

    <span class="badge">
        Nuevo
    </span>

    <div class="favorite">
        <i class="fa-regular fa-heart"></i>
    </div>

    <img
        src="assets/img/productos/<?php echo $producto['imagen']; ?>"
        alt="<?php echo htmlspecialchars($producto['nombre']); ?>">

    <div class="product-info">

        <h3>

            <?php echo htmlspecialchars($producto['nombre']); ?>

        </h3>

        <span class="reference">

            REF <?php echo htmlspecialchars($producto['referencia']); ?>

        </span>

        <div class="rating">

            ★★★★★

        </div>

        <?php require __DIR__ . '/price.php'; ?>

        <div class="sizes">

            <?php

            $tallas = explode(",", $producto['tallas']);

            foreach($tallas as $talla):

            ?>

                <span>

                    <?php echo trim($talla); ?>

                </span>

            <?php endforeach; ?>

        </div>

        <?php require __DIR__.'/product-button.php'; ?>

    </div>

</div>