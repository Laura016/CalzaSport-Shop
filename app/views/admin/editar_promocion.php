<?php
require_once '../app/views/admin/layouts/header.php';

$imagenActual = $promocion['imagen'] ?? '';

$productosSeleccionados = $productosSeleccionados ?? [];
?>

<div class="admin-content promociones-page nueva-promocion-page">

    <div class="page-header">

        <div>

            <h1>Editar promoción</h1>

            <p>
                Actualiza la información, descuento y productos de esta promoción.
            </p>

        </div>


        <div>

            <a
                href="admin.php?accion=promociones"
                class="btn-promo-secondary"
            >
                <i class="fa-solid fa-arrow-left"></i>

                <span>Volver a promociones</span>
            </a>

        </div>

    </div>


    <div class="promocion-form-card">

        <form
            action="admin.php?accion=actualizarPromocion"
            method="POST"
            enctype="multipart/form-data"
            id="formPromocion"
        >

            <input
                type="hidden"
                name="id"
                value="<?= (int) $promocion['id'] ?>"
            >


            <!-- =========================================
                 INFORMACIÓN
            ========================================== -->

            <div class="promocion-form-section">

                <div class="promocion-section-header">

                    <div class="promocion-section-icon">

                        <i class="fa-solid fa-tag"></i>

                    </div>


                    <div>

                        <h2>
                            Información de la promoción
                        </h2>

                        <p>
                            Actualiza los datos principales de la promoción.
                        </p>

                    </div>

                </div>


                <div class="form-grid">


                    <div class="form-group">

                        <label for="nombre">
                            Nombre interno
                        </label>

                        <input
                            type="text"
                            id="nombre"
                            name="nombre"
                            value="<?= htmlspecialchars(
                                $promocion['nombre']
                            ) ?>"
                            required
                        >

                    </div>


                    <div class="form-group">

                        <label for="titulo">
                            Título de la promoción
                        </label>

                        <input
                            type="text"
                            id="titulo"
                            name="titulo"
                            value="<?= htmlspecialchars(
                                $promocion['titulo']
                            ) ?>"
                            required
                        >

                    </div>


                    <div class="form-group form-group-full">

                        <label for="descripcion">
                            Descripción
                        </label>

                        <textarea
                            id="descripcion"
                            name="descripcion"
                            rows="4"
                        ><?= htmlspecialchars(
                            $promocion['descripcion'] ?? ''
                        ) ?></textarea>

                    </div>


                    <div class="form-group">

                        <label for="tipo_descuento">
                            Tipo de descuento
                        </label>

                        <select
                            id="tipo_descuento"
                            name="tipo_descuento"
                            required
                        >

                            <option
                                value="porcentaje"
                                <?= $promocion['tipo_descuento'] === 'porcentaje'
                                    ? 'selected'
                                    : '' ?>
                            >
                                Porcentaje (%)
                            </option>

                            <option
                                value="valor_fijo"
                                <?= $promocion['tipo_descuento'] === 'valor_fijo'
                                    ? 'selected'
                                    : '' ?>
                            >
                                Valor fijo ($)
                            </option>

                        </select>

                    </div>


                    <div class="form-group">

                        <label for="descuento">
                            Descuento
                        </label>

                        <input
                            type="number"
                            id="descuento"
                            name="descuento"
                            min="0.01"
                            step="0.01"
                            value="<?= htmlspecialchars(
                                $promocion['descuento']
                            ) ?>"
                            required
                        >

                    </div>


                    <div class="form-group">

                        <label for="fecha_inicio">
                            Fecha de inicio
                        </label>

                        <input
                            type="date"
                            id="fecha_inicio"
                            name="fecha_inicio"
                            value="<?= htmlspecialchars(
                                $promocion['fecha_inicio']
                            ) ?>"
                            required
                        >

                    </div>


                    <div class="form-group">

                        <label for="fecha_fin">
                            Fecha de finalización
                        </label>

                        <input
                            type="date"
                            id="fecha_fin"
                            name="fecha_fin"
                            value="<?= htmlspecialchars(
                                $promocion['fecha_fin']
                            ) ?>"
                            required
                        >

                    </div>


                    <div class="form-group">

                        <label for="estado">
                            Estado
                        </label>

                        <select
                            id="estado"
                            name="estado"
                            required
                        >

                            <option
                                value="Activa"
                                <?= $promocion['estado'] === 'Activa'
                                    ? 'selected'
                                    : '' ?>
                            >
                                Activa
                            </option>

                            <option
                                value="Inactiva"
                                <?= $promocion['estado'] === 'Inactiva'
                                    ? 'selected'
                                    : '' ?>
                            >
                                Inactiva
                            </option>

                        </select>

                    </div>

                </div>

            </div>


            <!-- =========================================
                 IMAGEN
            ========================================== -->

            <div class="promocion-form-section">

                <div class="promocion-section-header">

                    <div class="promocion-section-icon">

                        <i class="fa-solid fa-image"></i>

                    </div>


                    <div>

                        <h2>
                            Imagen de la promoción
                        </h2>

                        <p>
                            Puedes conservar la imagen actual o reemplazarla.
                        </p>

                    </div>

                </div>


                <div class="promocion-imagen-upload">


                    <div
                        class="promocion-imagen-preview"
                        id="imagenPreviewContainer"
                    >

                        <?php if ($imagenActual !== ''): ?>

                            <img
                                id="imagenPreview"
                                src="assets/img/ofertas/<?= htmlspecialchars(
                                    $imagenActual
                                ) ?>"
                                alt="Imagen actual de la promoción"
                                style="display:block;"
                            >

                            <div
                                class="promocion-imagen-placeholder"
                                id="imagenPlaceholder"
                                style="display:none;"
                            >

                                <i class="fa-regular fa-image"></i>

                                <strong>
                                    Vista previa
                                </strong>

                                <span>
                                    La imagen aparecerá aquí
                                </span>

                            </div>

                        <?php else: ?>

                            <div
                                class="promocion-imagen-placeholder"
                                id="imagenPlaceholder"
                            >

                                <i class="fa-regular fa-image"></i>

                                <strong>
                                    Sin imagen
                                </strong>

                                <span>
                                    Selecciona una imagen para agregarla
                                </span>

                            </div>


                            <img
                                id="imagenPreview"
                                src=""
                                alt="Vista previa de la promoción"
                            >

                        <?php endif; ?>

                    </div>


                    <div class="promocion-imagen-info">

                        <label
                            for="imagen"
                            class="promocion-upload-button"
                        >

                            <i class="fa-solid fa-cloud-arrow-up"></i>

                            Cambiar imagen

                        </label>


                        <input
                            type="file"
                            id="imagen"
                            name="imagen"
                            accept=".jpg,.jpeg,.png,.webp"
                            hidden
                        >


                        <p>
                            Si no seleccionas una nueva imagen,
                            se conservará la actual.
                        </p>

                    </div>

                </div>

            </div>


            <!-- =========================================
                 PRODUCTOS
            ========================================== -->

            <div class="promocion-form-section">

                <div class="promocion-section-header">

                    <div class="promocion-section-icon">

                        <i class="fa-solid fa-box-open"></i>

                    </div>


                    <div>

                        <h2>
                            Productos incluidos
                        </h2>

                        <p>
                            Selecciona los productos que tendrán este descuento.
                        </p>

                    </div>

                </div>


                <div class="productos-promocion">

                    <?php if (!empty($productos)): ?>

                        <?php foreach ($productos as $producto): ?>

                            <?php
                            $productoId = (int) $producto['id'];

                            $seleccionado = in_array(
                                $productoId,
                                $productosSeleccionados,
                                true
                            );
                            ?>


                            <label class="producto-promocion-card">

                                <input
                                    type="checkbox"
                                    name="productos[]"
                                    value="<?= $productoId ?>"
                                    <?= $seleccionado ? 'checked' : '' ?>
                                >


                                <div class="producto-promocion-check">

                                    <i class="fa-solid fa-check"></i>

                                </div>


                                <div class="producto-promocion-info">

                                    <strong>
                                        <?= htmlspecialchars(
                                            $producto['nombre']
                                        ) ?>
                                    </strong>

                                    <span>
                                        Ref:
                                        <?= htmlspecialchars(
                                            $producto['referencia']
                                        ) ?>
                                    </span>

                                </div>


                                <div class="producto-promocion-precio">

                                    $<?= number_format(
                                        $producto['precio'],
                                        0,
                                        ',',
                                        '.'
                                    ) ?>

                                </div>

                            </label>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <div class="productos-promocion-empty">

                            <i class="fa-solid fa-box-open"></i>

                            <strong>
                                No hay productos disponibles
                            </strong>

                            <span>
                                Agrega productos desde la sección Productos.
                            </span>

                        </div>

                    <?php endif; ?>

                </div>

            </div>


            <!-- =========================================
                 BOTONES
            ========================================== -->

            <div class="promocion-form-actions">

                <a
                    href="admin.php?accion=promociones"
                    class="btn-promo-secondary"
                >
                    Cancelar
                </a>


                <button
                    type="submit"
                    class="btn-promo-primary"
                >

                    <i class="fa-solid fa-floppy-disk"></i>

                    <span>
                        Guardar cambios
                    </span>

                </button>

            </div>

        </form>

    </div>

</div>


<script src="assets/js/admin/promociones.js"></script>