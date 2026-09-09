<?php
require_once '../app/views/admin/layouts/header.php';
?>

<div class="admin-content">

    <div class="page-header">

        <div>
            <h1>Nueva promoción</h1>
            <p>
                Crea una promoción y asígnala a los productos que deseas destacar.
            </p>
        </div>

        <div>
            <a href="admin.php?accion=promociones" class="btn-secondary">
                <i class="fa-solid fa-arrow-left"></i>
                Volver
            </a>
        </div>

    </div>

    <div class="form-card">

        <form action="admin.php?accion=guardarPromocion" method="POST" enctype="multipart/form-data">

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


                <div class="form-group">

                    <label for="imagen">
                        Imagen de la promoción
                    </label>

                    <input type="file" id="imagen" name="imagen" accept=".jpg,.jpeg,.png,.webp">

                    <small>
                        Formatos permitidos: JPG, JPEG, PNG y WEBP.
                    </small>

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


                <div class="form-group form-group-full">

                    <label>
                        Productos incluidos
                    </label>

                    <div class="productos-promocion">

                        <?php if (!empty($productos)): ?>

                            <?php foreach ($productos as $producto): ?>

                                <label class="producto-checkbox">

                                    <input type="checkbox" name="productos[]" value="<?= (int) $producto['id'] ?>">

                                    <span>
                                        <?= htmlspecialchars($producto['nombre']) ?>
                                    </span>

                                    <small>
                                        Ref:
                                        <?= htmlspecialchars($producto['referencia']) ?>
                                    </small>

                                </label>

                            <?php endforeach; ?>

                        <?php else: ?>

                            <p>
                                No hay productos disponibles.
                            </p>

                        <?php endif; ?>

                    </div>

                </div>

            </div>


            <div class="form-actions">

                <a href="admin.php?accion=promociones" class="btn-secondary">
                    Cancelar
                </a>

                <button type="submit" class="btn-primary">
                    <i class="fa-solid fa-floppy-disk"></i>
                    Guardar promoción
                </button>

            </div>

        </form>

    </div>

</div>