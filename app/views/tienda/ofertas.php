<?php require_once __DIR__ . '/layouts/header.php'; ?>
<link rel="stylesheet" href="assets/css/tienda/ofertas.css">

<main class="ofertas-page">

    <section class="ofertas-hero">

        <div class="ofertas-hero-content">

            <span class="ofertas-eyebrow">
                CALZASPORT
            </span>

            <h1>
                Ofertas especiales
            </h1>

            <p>
                Aprovecha nuestras promociones por tiempo limitado.
            </p>

        </div>

    </section>


    <section class="ofertas-section">

        <div class="section-title">

            <span class="section-subtitle">
                PROMOCIONES
            </span>

            <h2>
                Encuentra tu próximo par
            </h2>

            <p>
                Descubre descuentos especiales en productos seleccionados.
            </p>

        </div>


        <?php if (empty($promociones)): ?>

            <div class="ofertas-vacias">

                <i class="fa-solid fa-tag"></i>

                <h3>
                    Actualmente no tenemos promociones activas
                </h3>

                <p>
                    Muy pronto tendremos nuevas ofertas para ti.
                </p>

                <a href="catalogo.php" class="btn-ofertas">
                    Ver catálogo
                </a>

            </div>

        <?php else: ?>

            <div class="promociones-publicas">

                <?php foreach ($promociones as $promocion): ?>

                    <article class="promocion-publica">

                        <?php if (!empty($promocion['imagen'])): ?>

                            <div class="promocion-publica-imagen">

                                <img
                                    src="assets/img/ofertas/<?= htmlspecialchars($promocion['imagen']) ?>"
                                    alt="<?= htmlspecialchars($promocion['titulo']) ?>"
                                >

                            </div>

                        <?php endif; ?>


                        <div class="promocion-publica-contenido">

                            <span class="promocion-publica-badge">

                                <?php if ($promocion['tipo_descuento'] === 'porcentaje'): ?>

                                    -<?= rtrim(rtrim(number_format((float)$promocion['descuento'], 2, '.', ''), '0'), '.') ?>%

                                <?php else: ?>

                                    -$<?= number_format((float)$promocion['descuento'], 0, ',', '.') ?>

                                <?php endif; ?>

                            </span>


                            <h2>
                                <?= htmlspecialchars($promocion['titulo']) ?>
                            </h2>


                            <?php if (!empty($promocion['descripcion'])): ?>

                                <p>
                                    <?= nl2br(htmlspecialchars($promocion['descripcion'])) ?>
                                </p>

                            <?php endif; ?>


                            <div class="promocion-publica-fechas">

                                <i class="fa-regular fa-clock"></i>

                                Hasta
                                <?= date('d/m/Y', strtotime($promocion['fecha_fin'])) ?>

                            </div>


                            <?php
                            $productos =
                                $productosPorPromocion[$promocion['id']]
                                ?? [];
                            ?>


                            <?php if (!empty($productos)): ?>

                                <div class="promocion-productos-publicos">

                                    <?php foreach ($productos as $producto): ?>

                                        <?php
$precioOriginal = (float) $producto['precio_original'];
$precioPromocional = (float) $producto['precio_promocional'];
?>

                                        <div class="producto-oferta">

                                            <div class="producto-oferta-imagen">

                                                <img
                                                    src="assets/img/productos/<?= htmlspecialchars($producto['imagen']) ?>"
                                                    alt="<?= htmlspecialchars($producto['nombre']) ?>"
                                                >

                                            </div>


                                            <div class="producto-oferta-info">

                                                <h3>
                                                    <?= htmlspecialchars($producto['nombre']) ?>
                                                </h3>

                                                <span class="producto-oferta-referencia">
                                                    Ref. <?= htmlspecialchars($producto['referencia']) ?>
                                                </span>


                                                <div class="producto-oferta-precios">

                                                    <span class="precio-original">
                                                        $<?= number_format($precioOriginal, 0, ',', '.') ?>
                                                    </span>

                                                    <strong>
                                                        $<?= number_format($precioPromocional, 0, ',', '.') ?>
                                                    </strong>

                                                </div>


                                                <a
                                                    href="catalogo.php"
                                                    class="btn-producto-oferta"
                                                >
                                                    Ver producto
                                                </a>

                                            </div>

                                        </div>

                                    <?php endforeach; ?>

                                </div>

                            <?php endif; ?>

                        </div>

                    </article>

                <?php endforeach; ?>

            </div>

        <?php endif; ?>

    </section>

</main>

<?php require_once __DIR__ . '/layouts/footer.php'; ?>