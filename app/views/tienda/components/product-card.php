<div class="product-card"

    data-nombre="<?= strtolower($producto['nombre']) ?>"

    data-referencia="<?= strtolower($producto['referencia']) ?>"

    data-categoria="<?= strtolower($producto['categoria']) ?>"

    data-tallas="<?= strtolower($producto['tallas']) ?>"

    data-precio="<?= $producto['precio'] ?>">

    <?php if($producto['destacado']): ?>

        <span class="badge">

            Destacado

        </span>

    <?php else: ?>

        <span class="badge nuevo">

            Nuevo

        </span>

    <?php endif; ?>

    <button class="favorite">

        <i class="fa-regular fa-heart"></i>

    </button>

    <div class="product-image">

        <img

            src="assets/img/productos/<?= $producto['imagen']; ?>"

            alt="<?= htmlspecialchars($producto['nombre']); ?>">

    </div>

    <div class="product-info">

        <h3>

            <?= htmlspecialchars($producto['nombre']); ?>

        </h3>

        <span class="reference">

            REF <?= htmlspecialchars($producto['referencia']); ?>

        </span>

        <div class="rating">

            ★★★★★

        </div>

        <?php require __DIR__.'/price.php'; ?>

        <div class="sizes">

            <?php foreach(explode(",", $producto['tallas']) as $talla): ?>

                <span>

                    <?= trim($talla); ?>

                </span>

            <?php endforeach; ?>

        </div>

        <?php require __DIR__.'/product-button.php'; ?>

    </div>

</div>