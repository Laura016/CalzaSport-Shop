<?php require_once __DIR__ . '/components/hero.php'; ?>
<?php require_once __DIR__ . '/components/categories.php'; ?>

<!--==========================
PRODUCTOS DESTACADOS
===========================-->

<section class="products">

    <div class="section-title">

        <h2>Productos destacados</h2>

        <p>Descubre nuestros modelos más vendidos.</p>

    </div>

    <div class="products-grid">

        <?php foreach ($productos as $producto): ?>

            <div class="product-card">

                <span class="badge">
                    Nuevo
                </span>

                <div class="favorite">
                    <i class="fa-regular fa-heart"></i>
                </div>

                <img src="assets/img/<?php echo $producto['imagen']; ?>" alt="<?php echo $producto['nombre']; ?>">

                <div class="product-info">

                    <h3><?php echo $producto['nombre']; ?></h3>

                    <span class="reference">

                        REF <?php echo $producto['referencia']; ?>

                    </span>

                    <div class="rating">

                        ★★★★★

                    </div>

                    <div class="price">

                        $<?php echo number_format($producto['precio'], 0, ',', '.'); ?>

                    </div>

                    <div class="sizes">

                        <?php

                        $tallas = explode(",", $producto['tallas']);

                        foreach ($tallas as $talla):

                            ?>

                            <span>

                                <?php echo $talla; ?>

                            </span>

                        <?php endforeach; ?>

                    </div>

                    <a href="producto.php?id=<?php echo $producto['id']; ?>" class="btn-producto">

                        Ver producto

                    </a>

                </div>

            </div>

        <?php endforeach; ?>

    </div>

</section>