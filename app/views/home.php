<!-- HERO -->
<section class="hero">

    <div class="hero-overlay">

        <div class="hero-content">

            <span class="hero-tag">
                NUEVA COLECCIÓN
            </span>

            <h1>
                Camina con estilo,<br>
                vive con confianza.
            </h1>

            <p>
                Descubre los mejores zapatos deportivos y casuales para cada ocasión.
                Calidad, comodidad y diseño en un solo lugar.
            </p>

            <a href="#" class="btn">
                Comprar ahora
            </a>

        </div>

    </div>

</section>

<!-- =========================
CATEGORÍAS
========================= -->

<section class="categories">

    <div class="section-title">

        <h2>Compra por categoría</h2>

        <p>Encuentra el estilo perfecto para ti.</p>

    </div>

    <div class="category-grid">

        <div class="category-card">

            <img src="assets/img/hombre.jpg" alt="Hombre">

            <div class="category-overlay">

                <h3>Hombre</h3>

            </div>

        </div>

        <div class="category-card">

            <img src="assets/img/mujer.jpg" alt="Mujer">

            <div class="category-overlay">

                <h3>Mujer</h3>

            </div>

        </div>

        <div class="category-card">

            <img src="assets/img/running.jpg" alt="Running">

            <div class="category-overlay">

                <h3>Running</h3>

            </div>

        </div>

        <div class="category-card">

            <img src="assets/img/casual.jpg" alt="Casual">

            <div class="category-overlay">

                <h3>Casual</h3>

            </div>

        </div>

    </div>

</section>

<!--==========================
PRODUCTOS DESTACADOS
===========================-->

<section class="products">

    <div class="section-title">

        <h2>Productos destacados</h2>

        <p>Descubre nuestros modelos más vendidos.</p>

    </div>

    <div class="products-grid">

    <?php foreach($productos as $producto): ?>

    <div class="product-card">

    <span class="badge">
        Nuevo
    </span>

    <div class="favorite">
        <i class="fa-regular fa-heart"></i>
    </div>

    <img src="assets/img/<?php echo $producto['imagen']; ?>"
         alt="<?php echo $producto['nombre']; ?>">

    <div class="product-info">

        <h3><?php echo $producto['nombre']; ?></h3>

        <span class="reference">

            REF <?php echo $producto['referencia']; ?>

        </span>

        <div class="rating">

            ★★★★★

        </div>

        <div class="price">

            $<?php echo number_format($producto['precio'],0,',','.'); ?>

        </div>

        <div class="sizes">

            <?php

            $tallas = explode(",", $producto['tallas']);

            foreach($tallas as $talla):

            ?>

                <span>

                    <?php echo $talla; ?>

                </span>

            <?php endforeach; ?>

        </div>

        <button>

            Agregar al carrito

        </button>

    </div>

</div>

    <?php endforeach; ?>

</div>

</section>