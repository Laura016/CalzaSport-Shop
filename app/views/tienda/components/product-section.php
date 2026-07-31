<section class="products">

    <?php if (!empty($titulo)): ?>

        <div class="section-title">

            <h2><?= $titulo ?></h2>

            <p><?= $subtitulo ?></p>

        </div>

    <?php endif; ?>

    <div class="products-grid" id="contenedorProductos">

        <?php foreach ($productos as $producto): ?>

            <?php require __DIR__ . '/product-card.php'; ?>

        <?php endforeach; ?>

    </div>

</section>