<div class="product-card"

    data-nombre="<?= strtolower($producto['nombre']) ?>"

    data-referencia="<?= strtolower($producto['referencia']) ?>"

    data-categoria="<?= strtolower($producto['categoria']) ?>"

    data-tallas="<?= strtolower($producto['tallas']) ?>"

    data-precio="<?= $producto['precio'] ?>">

    <!-- Badge -->
    <?php if($producto['destacado']): ?>

        <span class="badge">
            Destacado
        </span>

    <?php else: ?>

        <span class="badge nuevo">
            Nuevo
        </span>

    <?php endif; ?>

    <!-- Favorito -->
    <button
    class="favorite"
    data-id="<?= $producto['id']; ?>"
    data-nombre="<?= htmlspecialchars($producto['nombre']); ?>"
    data-precio="<?= $producto['precio']; ?>"
    data-imagen="<?= $producto['imagen']; ?>">

    <i class="fa-regular fa-heart"></i>

</button>

    <!-- Imagen -->
    <div class="product-image">

        <img
            src="assets/img/productos/<?= $producto['imagen']; ?>"
            alt="<?= htmlspecialchars($producto['nombre']); ?>">

    </div>

    <!-- Información -->
    <div class="product-info">

        <h3><?= htmlspecialchars($producto['nombre']); ?></h3>

        <span class="reference">
            REF <?= htmlspecialchars($producto['referencia']); ?>
        </span>

        <div class="rating">

            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>

        </div>

        <?php require __DIR__.'/price.php'; ?>

        <div class="sizes">

            <?php foreach(explode(",", $producto['tallas']) as $talla): ?>

                <span><?= trim($talla); ?></span>

            <?php endforeach; ?>

        </div>

        <div class="product-action">

            <?php require __DIR__.'/product-button.php'; ?>

        </div>

    </div>

</div>