<?php
require_once '../app/views/admin/layouts/header.php';
?>

<div class="admin-content promociones-page nueva-promocion-page">

    <div class="page-header">

        <div>
            <h1>Nueva promoción</h1>
            <p>
                Crea una promoción y asígnala a los productos que deseas destacar.
            </p>
        </div>

        <div>
            <a href="admin.php?accion=promociones" class="btn-promo-secondary">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Volver a promociones</span>
            </a>
        </div>

    </div>

    <div class="promocion-form-card">

        <form action="admin.php?accion=guardarPromocion" method="POST" enctype="multipart/form-data" id="formPromocion">

            <div class="form-grid">

                <div class="form-group">

                    <label for="nombre">
                        Nombre interno
                    </label>

                    <input type="text" id="nombre" name="nombre" placeholder="Ej: Oferta Running Julio" required>

                </div>


                <div class="form-group">

                    <label for="titulo">
                        Título de la promoción
                    </label>

                    <input type="text" id="titulo" name="titulo" placeholder="Ej: 30% de descuento en Running" required>

                </div>


                <div class="form-group form-group-full">

                    <label for="descripcion">
                        Descripción
                    </label>

                    <textarea id="descripcion" name="descripcion" rows="4"
                        placeholder="Describe brevemente la promoción..."></textarea>

                </div>


                <div class="promocion-form-section promocion-imagen-section">

                    <div class="promocion-section-header">

                        <div class="promocion-section-icon">
                            <i class="fa-solid fa-image"></i>
                        </div>

                        <div>
                            <h2>Imagen de la promoción</h2>
                            <p>
                                Agrega una imagen que represente la oferta.
                            </p>
                        </div>

                    </div>


                    <div class="promocion-imagen-upload">

                        <div class="promocion-imagen-preview" id="imagenPreviewContainer">

                            <div class="promocion-imagen-placeholder" id="imagenPlaceholder">

                                <i class="fa-regular fa-image"></i>

                                <strong>
                                    Vista previa
                                </strong>

                                <span>
                                    La imagen aparecerá aquí
                                </span>

                            </div>

                            <img id="imagenPreview" src="" alt="Vista previa de la promoción">

                        </div>


                        <div class="promocion-imagen-info">

                            <label for="imagen" class="promocion-upload-button">
                                <i class="fa-solid fa-cloud-arrow-up"></i>
                                Seleccionar imagen
                            </label>

                            <input type="file" id="imagen" name="imagen" accept=".jpg,.jpeg,.png,.webp" hidden>

                            <p>
                                JPG, JPEG, PNG o WEBP.
                                Tamaño máximo: 5 MB.
                            </p>

                        </div>

                    </div>

                </div>


                <div class="form-group">

                    <label for="tipo_descuento">
                        Tipo de descuento
                    </label>

                    <select id="tipo_descuento" name="tipo_descuento" required>

                        <option value="porcentaje">
                            Porcentaje (%)
                        </option>

                        <option value="valor_fijo">
                            Valor fijo ($)
                        </option>

                    </select>

                </div>


                <div class="form-group">

                    <label for="descuento">
                        Descuento
                    </label>

                    <input type="number" id="descuento" name="descuento" min="0.01" step="0.01" placeholder="Ej: 20"
                        required>

                    <small>
                        Si eliges porcentaje, escribe por ejemplo 20 para 20%.
                    </small>

                </div>


                <div class="form-group">

                    <label for="fecha_inicio">
                        Fecha de inicio
                    </label>

                    <input type="date" id="fecha_inicio" name="fecha_inicio" required>

                </div>


                <div class="form-group">

                    <label for="fecha_fin">
                        Fecha de finalización
                    </label>

                    <input type="date" id="fecha_fin" name="fecha_fin" required>

                </div>


                <div class="form-group">

                    <label for="estado">
                        Estado
                    </label>

                    <select id="estado" name="estado" required>

                        <option value="Activa">
                            Activa
                        </option>

                        <option value="Inactiva">
                            Inactiva
                        </option>

                    </select>

                </div>


                <div class="promocion-form-section promocion-productos-section">

                    <div class="promocion-section-header">

                        <div class="promocion-section-icon">
                            <i class="fa-solid fa-box-open"></i>
                        </div>

                        <div>
                            <h2>Productos incluidos</h2>
                            <p>
                                Selecciona los productos a los que aplicará esta promoción.
                            </p>
                        </div>

                    </div>


                    <div class="productos-promocion">

                        <?php if (!empty($productos)): ?>

                            <?php foreach ($productos as $producto): ?>

                                <label class="producto-promocion-card">

                                    <input type="checkbox" name="productos[]" value="<?= (int) $producto['id'] ?>">

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

            </div>


            <div class="promocion-form-actions">

                <a href="admin.php?accion=promociones" class="btn-promo-secondary">
                    Cancelar
                </a>

                <button type="submit" class="btn-promo-primary">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span>Guardar promoción</span>
                </button>

            </div>

        </form>

    </div>

</div>
<script src="assets/js/admin/promociones.js"></script>