<div class="product-card"

    data-id="<?= $producto['id']; ?>"

    data-nombre="<?= strtolower($producto['nombre']) ?>"

    data-referencia="<?= strtolower($producto['referencia']) ?>"

    data-categoria="<?= strtolower($producto['categoria']) ?>"

    data-tallas="<?= strtolower($producto['tallas']) ?>"

    data-precio="<?= $producto['precio'] ?>">

    <!--======================================
    BADGE
    ======================================-->

    <?php if($producto['destacado']): ?>

        <span class="badge">

            Destacado

        </span>

    <?php else: ?>

        <span class="badge nuevo">

            Nuevo

        </span>

    <?php endif; ?>


    <!--======================================
    FAVORITO
    ======================================-->

    <button
        class="favorite"

        data-id="<?= $producto['id']; ?>"

        data-nombre="<?= htmlspecialchars($producto['nombre']); ?>"

        data-precio="<?= $producto['precio']; ?>"

        data-imagen="<?= $producto['imagen']; ?>">

        <i class="fa-regular fa-heart"></i>

    </button>


    <!--======================================
    IMAGEN
    ======================================-->

    <div class="product-image">

        <img
            src="assets/img/productos/<?= $producto['imagen']; ?>"

            alt="<?= htmlspecialchars($producto['nombre']); ?>">

    </div>


    <!--======================================
    INFORMACIÓN
    ======================================-->

    <div class="product-info">

        <h3>

            <?= htmlspecialchars($producto['nombre']); ?>

        </h3>


        <span class="reference">

            REF <?= htmlspecialchars($producto['referencia']); ?>

        </span>


        <!--==================================
        RATING
        ==================================-->

        <div class="rating">

            <i class="fa-solid fa-star"></i>

            <i class="fa-solid fa-star"></i>

            <i class="fa-solid fa-star"></i>

            <i class="fa-solid fa-star"></i>

            <i class="fa-solid fa-star"></i>

        </div>


        <!--==================================
        PRECIO
        ==================================-->

        <?php require __DIR__.'/price.php'; ?>


        <!--==================================
        TALLAS
        ==================================-->

        <div class="sizes">

            <?php foreach(explode(",", $producto['tallas']) as $talla): ?>

                <?php $talla = trim($talla); ?>

                <button
                    type="button"
                    class="size-option"
                    data-talla="<?= htmlspecialchars($talla); ?>">

                    <?= htmlspecialchars($talla); ?>

                </button>

            <?php endforeach; ?>

        </div>


        <!--==================================
        BOTÓN
        ==================================-->

        <div class="product-action">

            <?php require __DIR__.'/product-button.php'; ?>

        </div>

    </div>

</div>