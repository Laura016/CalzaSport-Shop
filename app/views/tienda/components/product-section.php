<section class="products">

    <div class="container">

        <div class="section-title">

            <h2><?php echo $titulo; ?></h2>

            <p><?php echo $subtitulo; ?></p>

        </div>

        <div class="products-grid">

            <?php foreach ($productos as $producto): ?>

                <div class="product-card">

                    <?php if($producto['destacado']): ?>

                        <span class="badge">
                            Destacado
                        </span>

                    <?php endif; ?>

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

                        <div class="price">

                            $<?php echo number_format($producto['precio'],0,',','.'); ?>

                        </div>

                        <div class="sizes">

                            <?php
                            $tallas = explode(",", $producto['tallas']);

                            foreach($tallas as $talla):
                            ?>

                                <span><?php echo trim($talla); ?></span>

                            <?php endforeach; ?>

                        </div>

                        <a
                            href="producto.php?id=<?php echo $producto['id']; ?>"
                            class="btn">

                            Ver producto

                        </a>

                    </div>

                </div>

            <?php endforeach; ?>

        </div>

    </div>

</section>