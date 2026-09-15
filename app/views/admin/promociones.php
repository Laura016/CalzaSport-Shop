<?php
require_once '../app/views/admin/layouts/header.php';
?>

<div class="admin-content promociones-page">

    <div class="page-header">

        <div>
            <h1>Promociones</h1>
            <p>
                Administra las promociones y descuentos de CalzaSport.
            </p>
        </div>

        <div>
            <a href="admin.php?accion=nuevaPromocion" class="btn-promo-primary">
                <i class="fa-solid fa-plus"></i>
                <span>Nueva promoción</span>
            </a>
        </div>

    </div>


    <div class="promociones-table-card">

        <div class="table-responsive">

            <table id="tablaPromociones" class="admin-table">

                <thead>

                    <tr>

                        <th>ID</th>

                        <th>Promoción</th>

                        <th>Descuento</th>

                        <th>Inicio</th>

                        <th>Finalización</th>

                        <th>Estado</th>

                        <th>Acciones</th>

                    </tr>

                </thead>


                <tbody>

                    <?php if (!empty($promociones)): ?>

                        <?php foreach ($promociones as $promocion): ?>

                            <tr>

                                <td>
                                    <span class="promocion-id">
                                        #<?= (int) $promocion['id'] ?>
                                    </span>
                                </td>


                                <td>

                                    <div class="promocion-info">

                                        <strong>
                                            <?= htmlspecialchars(
                                                $promocion['nombre']
                                            ) ?>
                                        </strong>

                                        <span>
                                            <?= htmlspecialchars(
                                                $promocion['titulo']
                                            ) ?>
                                        </span>

                                    </div>

                                </td>


                                <td>

                                    <span class="promocion-descuento">

                                        <?php if (
                                            $promocion['tipo_descuento'] === 'porcentaje'
                                        ): ?>

                                            -<?= number_format(
                                                $promocion['descuento'],
                                                0
                                            ) ?>%

                                        <?php else: ?>

                                            -$<?= number_format(
                                                $promocion['descuento'],
                                                0,
                                                ',',
                                                '.'
                                            ) ?>

                                        <?php endif; ?>

                                    </span>

                                </td>


                                <td>

                                    <div class="promocion-fecha">

                                        <i class="fa-regular fa-calendar"></i>

                                        <?= date(
                                            'd/m/Y',
                                            strtotime($promocion['fecha_inicio'])
                                        ) ?>

                                    </div>

                                </td>


                                <td>

                                    <div class="promocion-fecha">

                                        <i class="fa-regular fa-calendar-check"></i>

                                        <?= date(
                                            'd/m/Y',
                                            strtotime($promocion['fecha_fin'])
                                        ) ?>

                                    </div>

                                </td>


                                <td>

                                    <?php if (
                                        $promocion['estado'] === 'Activa'
                                    ): ?>

                                        <span class="promocion-estado activa">
                                            Activa
                                        </span>

                                    <?php else: ?>

                                        <span class="promocion-estado inactiva">
                                            Inactiva
                                        </span>

                                    <?php endif; ?>

                                </td>


                                <td>

                                    <div class="promocion-actions">

                                        <a href="admin.php?accion=editarPromocion&id=<?= (int) $promocion['id'] ?>"
                                            class="btn-action btn-edit" title="Editar promoción">
                                            <i class="fa-solid fa-pen"></i>
                                        </a>


                                        <a href="admin.php?accion=eliminarPromocion&id=<?= (int) $promocion['id'] ?>"
                                            class="btn-action btn-delete" title="Eliminar promoción"
                                            onclick="return confirm('¿Estás segura de eliminar esta promoción?');">
                                            <i class="fa-solid fa-trash"></i>
                                        </a>

                                    </div>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>

                            <td colspan="7" class="promociones-empty">

                                <div class="promociones-empty-content">

                                    <i class="fa-solid fa-tags"></i>

                                    <strong>
                                        Aún no tienes promociones creadas
                                    </strong>

                                    <span>
                                        Crea tu primera promoción para comenzar a ofrecer descuentos.
                                    </span>

                                </div>

                            </td>

                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>