<?php require_once __DIR__.'/components/hero.php'; ?>

<?php require_once __DIR__.'/components/categories.php'; ?>

<section class="products">

    <div class="section-title">

        <h2>Productos destacados</h2>

        <p>Descubre nuestros modelos más vendidos.</p>

    </div>

    <div class="products-grid">

        <?php foreach($productos as $producto): ?>

            <?php require __DIR__.'/components/product-card.php'; ?>

        <?php endforeach; ?>

    </div>

</section>